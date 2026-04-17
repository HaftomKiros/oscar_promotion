<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ccandidate extends CI_Controller {
	public $menu;
	function __construct() {
      parent::__construct();
		$this->load->library('auth');
		$this->load->library('lcandidate');
		$this->load->library('session');
		$this->auth->check_admin_auth();
    $this->load->model('Zones');
    $this->load->model('Educations');
        $this->load->model('Candidate_model');
        $this->load->model('Web_settings');
    }
	//Default loading for Customffer System.finser
	public function index()
	{
        //Calling Customer add form which will be loaded by help of "lcustomer,located in library folder"
		$content = $this->lcandidate->candidate_add_form();
	//Here ,0 means array position 0 will be active class
		$this->template->full_admin_html_view($content);
	
	
	}
 public function insert_candidate()
 {
     $this->load->library('form_validation');

     // Validation rules
     $this->form_validation->set_rules('full_name', 'Full Name', 'required');
     $this->form_validation->set_rules('sex', 'Sex', 'required');
     $this->form_validation->set_rules('age', 'Age', 'required');
     $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|is_unique[candidates.phone_number]');
     $this->form_validation->set_rules('location', 'Location', 'required');
     $this->form_validation->set_rules('education_level', 'Education Level', 'required');
     $this->form_validation->set_rules('field_of_study', 'Field of Study', 'required');
     $this->form_validation->set_rules('gpa', 'GPA', 'required');
     $this->form_validation->set_rules('experience', 'Experience', 'required');
     $this->form_validation->set_rules('dob_ethiopian', 'DOB (Ethiopian)', 'required');
     $this->form_validation->set_rules('total_family_size', 'Total Family Size', 'required');
     $this->form_validation->set_rules('woreda', 'Woreda', 'required');
     $this->form_validation->set_rules('tabia', 'Tabia', 'required');
     $this->form_validation->set_rules('qualification_skills', 'Qualification Skills', 'required');
     $this->form_validation->set_rules('graduated_year', 'Graduated Year', 'required');

     if ($this->form_validation->run() == FALSE) {
         $this->session->set_flashdata('error_message', validation_errors());
         redirect('Ccandidate');
     }

     // -------------------- AUTO GENERATED SEEKER ID --------------------
     $max = $this->db->select_max('seeker_id')->get('candidates')->row()->seeker_id;

     if ($max == NULL || $max < 10000) {
         $new_seeker_id = 10000;
     } else {
         $new_seeker_id = $max + 1;
     }

     // -------------------- RESUME FILE UPLOAD --------------------
     $resume_file = "";

     if (!empty($_FILES['resume']['name'])) {

         $config['upload_path'] = FCPATH . 'uploads/candidate_resumes/';
         $config['allowed_types'] = 'pdf';
         $config['max_size'] = 5000; // 5 MB
         $config['encrypt_name'] = TRUE;

         if (!is_dir($config['upload_path'])) {
             mkdir($config['upload_path'], 0777, TRUE);
         }

         $this->load->library('upload', $config);

         if (!$this->upload->do_upload('resume')) {
             echo "<h3>UPLOAD ERROR:</h3>";
             echo $this->upload->display_errors();
             exit;
         } else {
             $file_data = $this->upload->data();
             $resume_file = $file_data['file_name'];
         }
     }

     // -------------------- INSERT INTO DATABASE --------------------
     $data = array(
         'seeker_id'          => $new_seeker_id,
         'full_name'          => $this->input->post('full_name'),
         'sex'                => $this->input->post('sex'),
         'martial_status'     => $this->input->post('martial_status'),
         'dob_ethiopian'      => $this->input->post('dob_ethiopian'),
         'age'                => $this->input->post('age'),
         'total_family_size'  => $this->input->post('total_family_size'),
         'hh_male'            => $this->input->post('hh_male'),
         'hh_female'          => $this->input->post('hh_female'),
         'household_type'     => $this->input->post('household_type'),
         'disability_status'  => $this->input->post('disability_status'),
         'disability_male'    => $this->input->post('disability_male'),
         'disability_female'  => $this->input->post('disability_female'),

         'phone_number'       => $this->input->post('phone_number'),
         'email'              => $this->input->post('email'),
         'location'           => $this->input->post('location'),
         'woreda'             => $this->input->post('woreda'),
         'tabia'              => $this->input->post('tabia'),

         'education_level'    => $this->input->post('education_level'),
         'field_of_study'     => $this->input->post('field_of_study'),
         'gpa'                => $this->input->post('gpa'),
         'qualification_skills' => $this->input->post('qualification_skills'),
         'graduated_year'     => $this->input->post('graduated_year'),

         'experience'         => $this->input->post('experience'),
         'resume'             => $resume_file,
         'created_at'         => date("Y-m-d H:i:s"),
         'status'             => 1, // default active
     );

     if (!$this->db->insert('candidates', $data)) {
         echo "<h3>DB ERROR:</h3>";
         print_r($this->db->error());
         exit;
     }

     $this->session->set_flashdata('message', 'Candidate added successfully.');
     redirect('Ccandidate/manage_candidate');
 }

public function manage_candidate()
{
    $this->load->model('Candidate_model');
    $candidates = $this->Candidate_model->get_all_candidates();
    $woredas = $this->Candidate_model->get_woredas_with_sex_counts();

    // Get incomplete profiles for call center
    $this->db->select('id, seeker_id, full_name, sex, phone_number, education_level, experience, qualification_skills, location, created_at');
    $this->db->from('candidates');
    $this->db->where('profile_complete', 0);
    $this->db->order_by('created_at', 'DESC');
    $incomplete_candidates = $this->db->get()->result_array();

    $data = array(
        'title'                => 'manage_candidate',
        'candidates'           => $candidates,
        'woredas'              => $woredas,
        'incomplete_candidates'=> $incomplete_candidates
    );

    $content = $this->parser->parse('candidate/manage_candidate', $data, true);
    $this->template->full_admin_html_view($content);
}

// Mark a profile as complete
public function mark_complete($id) {
    $this->db->where('id', $id);
    $this->db->update('candidates', array('profile_complete' => 1));
    $this->session->set_flashdata('message', 'Profile marked as complete.');
    redirect('Ccandidate/manage_candidate');
}

// Delete an incomplete profile
public function delete_incomplete($id) {
    $this->db->where('id', $id);
    $this->db->where('profile_complete', 0); // safety: only delete incomplete
    $this->db->delete('candidates');
    $this->session->set_flashdata('message', 'Incomplete profile deleted.');
    redirect('Ccandidate/manage_candidate');
}

/**
 * DataTables server-side processing for candidates
 */
public function get_candidates_datatable()
{
    $this->load->model('Candidate_model');
    
    // Get DataTables parameters
    $draw = $this->input->post('draw');
    $start = $this->input->post('start');
    $length = $this->input->post('length');
    $search = $this->input->post('search')['value'];
    $orderColumn = $this->input->post('order')[0]['column'] ?? 0;
    $orderDir = $this->input->post('order')[0]['dir'] ?? 'DESC';
    $woreda = $this->input->post('woreda') ?? '';
    $sex = $this->input->post('sex') ?? '';
    
    // Map column names
    $columns = ['id', 'seeker_id', 'full_name', 'sex', 'martial_status', 'phone_number', 'email', 'location', 'created_at'];
    $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'id';
    
    // Get paginated data
    $result = $this->Candidate_model->get_candidates_paginated($length, $start, $search, $orderBy, $orderDir, $woreda, $sex);
    
    // Format data for DataTables
    $data = [];
    foreach ($result['data'] as $row) {
        $data[] = [
            $row['id'],
            $row['seeker_id'],
            html_escape($row['full_name']),
            $row['sex'],
            $row['martial_status'] ?? 'Single',
            $row['dob_ethiopian'] ?? '',
            $row['age'] ?? '',
            $row['total_family_size'] ?? '',
            $row['hh_male'] ?? '',
            $row['hh_female'] ?? '',
            $row['household_type'] ?? '',
            $row['disability_status'] ?? '',
            $row['disability_male'] ?? '',
            $row['disability_female'] ?? '',
            $row['phone_number'],
            $row['email'] ?? '',
            $row['location'] ?? '',
            $row['woreda'] ?? '',
            $row['tabia'] ?? '',
            $row['education_level'] ?? '',
            $row['field_of_study'] ?? '',
            $row['gpa'] ?? '',
            $row['qualification_skills'] ?? '',
            $row['graduated_year'] ?? '',
            $row['experience'] ?? '',
            !empty($row['resume']) ? 'Yes' : 'No',
            $row['created_at'],
            $row['status']
        ];
    }
    
    $response = [
        'draw' => $draw,
        'recordsTotal' => $result['total'],
        'recordsFiltered' => $result['total'],
        'data' => $data
    ];
    
    echo json_encode($response);
}

/**
 * Export all candidates to CSV (faster than Excel)
 */
public function export_candidates_excel()
{
    // Temporary: show errors for debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $this->load->model('Candidate_model');
    $sex = $this->input->get('sex') ?? '';
    $candidates = $this->Candidate_model->get_all_candidates_for_export($sex);

    require_once FCPATH . 'vendor/autoload.php';
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = [
        'SL', 'Seeker ID', 'Full Name', 'Sex', 'Martial Status', 'DOB (Ethiopian)', 'Age',
        'Family Size', 'HH Male', 'HH Female', 'Household Type', 'Disability Status',
        'Disability Male', 'Disability Female', 'Phone', 'Email', 'Location',
        'Woreda', 'Tabia', 'Education Level', 'Field of Study', 'GPA', 'Qualification/Skills',
        'Graduated Year', 'Experience', 'Resume', 'Created At', 'Status'
    ];
    $sheet->fromArray($headers, NULL, 'A1');

    $sl = 1; $row = 2;
    $statusLabels = [0=>'Job Seeker',1=>'Fetched',2=>'Applied',3=>'Shortlisted',4=>'Interview',5=>'Hired',6=>'Rejected'];
    foreach ($candidates as $c) {
        $sheet->fromArray([
            $sl++, $c['seeker_id'], $c['full_name'], $c['sex'],
            $c['martial_status'] ?? 'Single', $c['dob_ethiopian'] ?? '', $c['age'] ?? '',
            $c['total_family_size'] ?? '', $c['hh_male'] ?? '', $c['hh_female'] ?? '',
            $c['household_type'] ?? '', $c['disability_status'] ?? '',
            $c['disability_male'] ?? '', $c['disability_female'] ?? '',
            $c['phone_number'], $c['email'] ?? '', $c['location'] ?? '',
            $c['woreda'] ?? '', $c['tabia'] ?? '',
            $c['education_level'] ?? '', $c['field_of_study'] ?? '',
            $c['gpa'] ?? '', $c['qualification_skills'] ?? '',
            $c['graduated_year'] ?? '', $c['experience'] ?? '',
            !empty($c['resume']) ? 'Yes' : 'No', $c['created_at'],
            $statusLabels[$c['status']] ?? 'Unknown'
        ], NULL, 'A'.$row++);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename=candidates_export_' . date('Y-m-d_H-i-s') . '.xlsx');
    header('Cache-Control: max-age=0');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

/**
 * Export candidates by woreda to Excel
 */
public function export_candidates_by_woreda()
{
    $this->load->model('Candidate_model');

    $woreda = $this->input->get('woreda') ?? '';
    $sex    = $this->input->get('sex') ?? '';

    if (empty($woreda)) {
        $this->session->set_flashdata('error_message', 'Please select a woreda');
        redirect('Ccandidate/manage_candidate');
        return;
    }

    // Use joined query so education/field names come as text not IDs
    $this->db->select('
        c.*,
        z.zone_name AS location_name,
        e.level AS education_name,
        f.field AS field_name
    ');
    $this->db->from('candidates c');
    $this->db->join('zone z', 'z.id = c.location', 'left');
    $this->db->join('educational_level e', 'e.id = c.education_level', 'left');
    $this->db->join('field_of_study f', 'f.id = c.field_of_study', 'left');
    $this->db->where('c.woreda', $woreda);
    if (!empty($sex)) $this->db->where('c.sex', $sex);
    $this->db->order_by('c.id', 'DESC');
    $candidates = $this->db->get()->result_array();

    if (empty($candidates)) {
        $this->session->set_flashdata('error_message', 'No candidates found for the selected woreda');
        redirect('Ccandidate/manage_candidate');
        return;
    }

    require_once FCPATH . 'vendor/autoload.php';
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = [
        'SL', 'Seeker ID', 'Full Name', 'Sex', 'Martial Status', 'DOB (Ethiopian)', 'Age',
        'Family Size', 'HH Male', 'HH Female', 'Household Type', 'Disability Status',
        'Disability Male', 'Disability Female', 'Phone', 'Email', 'Location',
        'Woreda', 'Tabia', 'Education Level', 'Field of Study', 'GPA', 'Qualification/Skills',
        'Graduated Year', 'Experience', 'Resume', 'Created At', 'Status'
    ];
    $sheet->fromArray($headers, NULL, 'A1');

    $sl = 1; $rowNum = 2;
    $statusLabels = [0=>'Job Seeker',1=>'Fetched',2=>'Applied',3=>'Shortlisted',4=>'Interview',5=>'Hired',6=>'Rejected'];
    foreach ($candidates as $c) {
        $sheet->fromArray([
            $sl++, $c['seeker_id'], $c['full_name'], $c['sex'],
            $c['martial_status'] ?? 'Single', $c['dob_ethiopian'] ?? '', $c['age'] ?? '',
            $c['total_family_size'] ?? '', $c['hh_male'] ?? '', $c['hh_female'] ?? '',
            $c['household_type'] ?? '', $c['disability_status'] ?? '',
            $c['disability_male'] ?? '', $c['disability_female'] ?? '',
            $c['phone_number'], $c['email'] ?? '',
            $c['location_name'] ?? $c['location'] ?? '',
            $c['woreda'] ?? '', $c['tabia'] ?? '',
            $c['education_name'] ?? '', $c['field_name'] ?? '',
            $c['gpa'] ?? '', $c['qualification_skills'] ?? '',
            $c['graduated_year'] ?? '', $c['experience'] ?? '',
            !empty($c['resume']) ? 'Yes' : 'No', $c['created_at'],
            $statusLabels[$c['status']] ?? 'Unknown'
        ], NULL, 'A'.$rowNum++);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename=candidates_' . str_replace(' ', '_', $woreda) . '_export_' . date('Y-m-d_H-i-s') . '.xlsx');
    header('Cache-Control: max-age=0');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function download_resume($id)
{
    $candidate = $this->db->get_where('candidates', ['id' => $id])->row();

    if (!$candidate || empty($candidate->resume)) {
        show_404();
        return;
    }

    $file_path = FCPATH . "uploads/candidate_resumes/" . $candidate->resume;

    if (!file_exists($file_path)) {
        show_404("File not found");
        return;
    }

    // Create download filename using full name
    $ext = pathinfo($candidate->resume, PATHINFO_EXTENSION);
    $download_name = str_replace(' ', '_', $candidate->full_name) . "_Resume." . $ext;

    $this->load->helper('download');
    force_download($download_name, file_get_contents($file_path));
}
public function edit($id)
{

    $data['candidate'] = $this->Candidate_model->get_candidate_by_id($id);
   

    if (!$data['candidate']) {
        $this->session->set_flashdata('error_message', 'Candidate not found');
        redirect('Ccandidate/manage_candidate');
    }

    // Dropdown lists
    $data['zones'] = $this->Zones->get_all_zones();
    $data['educational_levels'] = $this->Educations->get_all();
    $data['fields_of_study'] = $this->Educations->get_all_fields();

    $data['title'] = "Edit Candidate";

    $content = $this->parser->parse('candidate/edit_candidate', $data, true);
    $this->template->full_admin_html_view($content);
}

public function update()
{
    $id = $this->input->post('id');
    
    $candidate = $this->Candidate_model->get_candidate_by_id($id);

    if (!$candidate) {
        $this->session->set_flashdata('error_message', 'Candidate not found');
        redirect('Ccandidate/manage_candidate');
    }

    // Keep existing resume
    $resume = $candidate['resume'];
    

    // -----------------------------
    // Resume Upload
    // -----------------------------
    if (!empty($_FILES['resume']['name'])) {

        $config['upload_path']   = './uploads/candidate_resumes/';
        $config['allowed_types'] = 'pdf|doc|docx';
        $config['max_size']      = 2048;
        $config['file_name']     = time() . '_' . $_FILES['resume']['name'];

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('resume')) {
            $resume = $this->upload->data('file_name');
        } else {
            $this->session->set_flashdata('error_message', $this->upload->display_errors());
            redirect('Ccandidate/edit/' . $id);
        }
    }

    // ----------------------------------
    // Prepare Updated Data (MATCH DB)
    // ----------------------------------
    $update_data = [
        'full_name'           => $this->input->post('full_name'),
        'sex'                 => $this->input->post('sex'),
        'martial_status'      => $this->input->post('martial_status'),
        'dob_ethiopian'       => $this->input->post('dob_ethiopian'),
        'age'                 => $this->input->post('age'),
        'total_family_size'   => $this->input->post('total_family_size'),
        'hh_male'             => $this->input->post('hh_male'),
        'hh_female'           => $this->input->post('hh_female'),
        'household_type'      => $this->input->post('household_type'),
        'disability_status'   => $this->input->post('disability_status'),
        'disability_male'     => $this->input->post('disability_male'),
        'disability_female'   => $this->input->post('disability_female'),
        'phone_number'        => $this->input->post('phone_number'),
        'email'               => $this->input->post('email'),
        'location'            => $this->input->post('location'),
        'woreda'              => $this->input->post('woreda'),
        'tabia'               => $this->input->post('tabia'),
        'education_level'     => $this->input->post('education_level'),
        'field_of_study'      => $this->input->post('field_of_study'),
        'gpa'                 => $this->input->post('gpa'),
        'qualification_skills'=> $this->input->post('qualification_skills'),
        'graduated_year'      => $this->input->post('graduated_year'),
        'experience'          => $this->input->post('experience'),
        'status'              => $this->input->post('status'),
        'resume'              => $resume,
        'profile_complete'    => 1,
    ];

    // ----------------------------------
    // Update in DB
    // ----------------------------------
    $this->db->where('id', $id);
    $this->db->update('candidates', $update_data);

    $this->session->set_flashdata('message', 'Candidate updated successfully');
    redirect('Ccandidate/manage_candidate');
}

public function delete($id = null)
{
    // Permission check
    if (!$this->permission1->method('delete_candidate','delete')->access()) {
        $this->session->set_flashdata('error_message', 'Permission Denied');
        redirect('Ccandidate/manage_candidate');
    }

    // Validate ID
    if ($id == null || !is_numeric($id)) {
        $this->session->set_flashdata('error_message', 'Invalid candidate ID');
        redirect('Ccandidate/manage_candidate');
    }

    // Load model
    $this->load->model('Candidate_model');

    // Get candidate
    $candidate = $this->Candidate_model->get_candidate_by_id($id);

    if (!$candidate) {
        $this->session->set_flashdata('error_message', 'Candidate not found');
        redirect('Ccandidate/manage_candidate');
    }

    // Delete from database
    $deleted = $this->Candidate_model->delete_candidate($id);

    if ($deleted) {

        // Remove resume file
        if (!empty($candidate['resume'])) {
            $file_path = FCPATH . $candidate['resume'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $this->session->set_flashdata('message', 'Candidate deleted successfully');
    } else {
        $this->session->set_flashdata('error_message', 'Failed to delete candidate');
    }


    redirect('Ccandidate/manage_candidate');
}

// Update candidate status (called from matching_candidates page)
public function update_status()
{
    $this->load->model('Candidate_model');
    
    $candidate_id = $this->input->post('candidate_id');
    $status = $this->input->post('status');
    $job_id = $this->input->post('job_id');
    $company_id = $this->input->post('company_id');
    
    if (!$candidate_id || $status === null || $status === '') {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        return;
    }
    
    try {
        // Update the candidate status in candidates table (using 'id' column)
        $this->db->where('id', $candidate_id);
        $result = $this->db->update('candidates', ['status' => $status]);
        
        if ($result) {
            // Insert into candidate_report table
            $report_data = [
                'company_id' => $company_id ?? 0,
                'job_id' => $job_id ?? 0,
                'candidate_id' => $candidate_id,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $insert_result = $this->db->insert('candidate_report', $report_data);
            
            if ($insert_result) {
                echo json_encode(['status' => 'success', 'message' => 'Status updated successfully']);
            } else {
                $error = $this->db->error();
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert report: ' . $error['message']]);
            }
        } else {
            $error = $this->db->error();
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status: ' . $error['message']]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
}

}
