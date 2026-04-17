<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cjob extends CI_Controller {
	public $menu;
	function __construct() {
      parent::__construct();
		$this->load->library('auth');
		$this->load->library('ljob');
		$this->load->library('session');
		$this->auth->check_admin_auth();
        $this->load->model('Web_settings');
    }
	//Default loading for Customer System.
	public function index()
	{
        //Calling Customer add form which will be loaded by help of "lcustomer,located in library folder"
		$content = $this->ljob->job_add_form();
	//Here ,0 means array position 0 will be active class
		$this->template->full_admin_html_view($content);
	
	
	}
    public function insert_job()
{
    $this->load->library('form_validation');

    // ------------------------------
    // VALIDATION RULES
    // ------------------------------
    $this->form_validation->set_rules('company_id', 'Company', 'required');
    $this->form_validation->set_rules('job_title', 'Job Title', 'required');
    // positions is not required

    // sex not required, defaults to Both
    $this->form_validation->set_rules('sex', 'Sex', '');
    
    // age not required, defaults to >=0
    $this->form_validation->set_rules('age_operator', 'Age Operator', '');
    $this->form_validation->set_rules('age_value', 'Age Value', 'integer');

    $this->form_validation->set_rules('location[]', 'Location', 'required');

    $this->form_validation->set_rules('education_level[]', 'Education Level', 'required');
    $this->form_validation->set_rules('field_of_study[]', 'Field of Study', 'required');

    // GPA not required, defaults to >=0
    $this->form_validation->set_rules('gpa_operator', 'GPA Operator', '');
    $this->form_validation->set_rules('gpa_value', 'GPA Value', '');

    // Experience not required, defaults to >=0
    $this->form_validation->set_rules('experience_operator', 'Experience Operator', '');
    $this->form_validation->set_rules('experience_value', 'Experience Value', 'integer');

    // Skills not required
    $this->form_validation->set_rules('skills', 'Skills', '');
    
    // Employment Type not required, defaults to All
    $this->form_validation->set_rules('employment_type', 'Employment Type', '');
    
    // Salary Range is required
    $this->form_validation->set_rules('salary_range', 'Salary Range', 'required');
    
    $this->form_validation->set_rules('work_location', 'Work Location', 'required');

    $this->form_validation->set_rules('job_start_date', 'Post Date', 'required');
    $this->form_validation->set_rules('job_end_date', 'Application Deadline', 'required');

    if ($this->form_validation->run() == FALSE) {

        $this->session->set_flashdata('error_message', validation_errors());
        redirect('Cjob');

    } else {

        // ------------------------------
        // PREPARE DATA FOR INSERT
        // ------------------------------
        $data = [
            'company_id'           => $this->input->post('company_id'),
            'job_title'            => $this->input->post('job_title'),
            'positions'            => $this->input->post('positions'),

            'sex'                  => $this->input->post('sex'),
            'age_operator'         => $this->input->post('age_operator'),
            'age_value'            => $this->input->post('age_value'),

            'location'             => json_encode($this->input->post('location')), // ARRAY
            'education_level'      => json_encode($this->input->post('education_level')), // ARRAY
            'field_of_study'       => json_encode($this->input->post('field_of_study')), // ARRAY

            'gpa_operator'         => $this->input->post('gpa_operator'),
            'gpa_value'            => $this->input->post('gpa_value'),

            'experience_operator'  => $this->input->post('experience_operator'),
            'experience_value'     => $this->input->post('experience_value'),

            // NEW FIELDS
            'skills'               => $this->input->post('skills'),
            'employment_type'      => $this->input->post('employment_type'),
            'employment_period'    => $this->input->post('employment_period'),
            'salary'              => $this->input->post('salary_range'),
            'work_location'        => $this->input->post('work_location'),
            'special_skill'        => $this->input->post('special_skill'),

            'job_start_date'       => $this->input->post('job_start_date'),
            'job_end_date'         => $this->input->post('job_end_date'),

            'created_at'           => date('Y-m-d H:i:s'),
        ];

        // ------------------------------
        // INSERT INTO DATABASE
        // ------------------------------
        $this->load->model('Job_model');
        
        // Debug: Check data before insert
        log_message('debug', 'Job Data: ' . print_r($data, true));
        
        $insert = $this->Job_model->insert_job($data);

        if ($insert) {
            $this->session->set_flashdata('message', "Job inserted successfully!");
        } else {
            $error = $this->db->error();
            $this->session->set_flashdata('error_message', "Failed to insert job. Error: " . $error['message']);
        }

        redirect('Cjob');
    }
}

public function manage_job()
{
   $this->load->model('Job_model');
    $jobs = $this->Job_model->get_all_jobs();

    $data = array(
        'title' => 'manage_job',
        'jobs' => $jobs
    );

    $content = $this->parser->parse('job/manage_job', $data, true);
    $this->template->full_admin_html_view($content);

}
public function edit($id)
{
    $this->load->model('Job_model');
    $this->load->model('Employment');

    // === Fetch job ===
    $data['job'] = $this->Job_model->get_job_by_id($id);

    if (!$data['job']) {
        $this->session->set_flashdata('error_message', 'Job not found!');
        redirect('Cjob/manage_job');
    }

    // === Page Title ===
    $data['title'] = "edit_job";   // <-- add this

    // === Dropdown lists ===
    $data['companies']          = $this->Job_model->get_companies();
    $data['zones']              = $this->Job_model->get_zones();
    $data['educational_levels'] = $this->Job_model->get_education_levels();
    $data['fields_of_study']    = $this->Job_model->get_fields();
    
    // === Employment data ===
    $data['employment_types']  = $this->Employment->get_all_emp_type();
    $data['salary_ranges']     = $this->Employment->get_all_sal_range();

    // === Load page using Admin Template ===
    $content = $this->parser->parse('job/edit_job', $data, true);
    $this->template->full_admin_html_view($content);
}
public function update_job($id)
{
    $this->load->model('Job_model');

    // Collect POST
    $data = [
        'company_id'         => $this->input->post('company_id'),
        'job_title'          => $this->input->post('job_title'),
        'positions'          => $this->input->post('positions'),
        'sex'                => $this->input->post('sex'),
        'age_operator'       => $this->input->post('age_operator'),
        'age_value'          => $this->input->post('age_value'),
        'location'           => json_encode($this->input->post('location')),  // array
        'education_level'     => json_encode($this->input->post('education_level')), // array
        'field_of_study'     => json_encode($this->input->post('field_of_study')), // array
        'gpa_operator'       => $this->input->post('gpa_operator'),
        'gpa_value'          => $this->input->post('gpa_value'),
        'experience_operator'=> $this->input->post('experience_operator'),
        'experience_value'   => $this->input->post('experience_value'),
        
        // New fields
        'skills'             => $this->input->post('skills'),
        'employment_type'    => $this->input->post('employment_type'),
        'employment_period'  => $this->input->post('employment_period'),
        'salary'            => $this->input->post('salary'),
        'work_location'     => $this->input->post('work_location'),
        'special_skill'     => $this->input->post('special_skill'),
        
        'job_start_date'     => $this->input->post('job_start_date'),
        'job_end_date'       => $this->input->post('job_end_date'),
        'updated_at'         => date('Y-m-d H:i:s'),
    ];

    // Update
    $updated = $this->Job_model->update_job($id, $data);

    if (!$updated) {
        // Show the DB error — DEBUG ONLY
        $error = $this->db->error();
        $this->session->set_flashdata('error_message', 'Update failed: ' . $error['message']);
        redirect('Cjob/edit/' . $id);
    }

    $this->session->set_flashdata('message', 'Job updated successfully!');
    redirect('Cjob/manage_job');
}
public function delete($id)
{
    $this->load->model('Job_model');

    $deleted = $this->Job_model->delete_job($id);

    if ($deleted) {
        $this->session->set_flashdata('message', 'Job deleted successfully!');
    } else {
        $this->session->set_flashdata('error_message', 'Failed to delete job!');
    }

    redirect('Cjob/manage_job');
}
public function matching_candidates($job_id)
{
    $this->load->model('Job_model');
    $this->load->model('Candidate_model');

    // Get job details
    $job = $this->Job_model->get_job_by_id($job_id);

    if (!$job) {
        $this->session->set_flashdata('error_message', 'Job not found');
        redirect('Cjob/manage_job');
    }

    try {
        // Fetch candidates that match this job
        $candidates = $this->Candidate_model->get_matching_candidates($job);
        
        // Ensure candidates is always an array
        if ($candidates === null) {
            $candidates = [];
        }
    } catch (Exception $e) {
        // Log the error
        log_message('error', 'Error in matching_candidates: ' . $e->getMessage());
        $candidates = [];
        $this->session->set_flashdata('error_message', 'Error loading candidates: ' . $e->getMessage());
    }

    $data = [
        'job' => $job,
        'candidates' => $candidates,
        'title' => 'matched candidates'
    ];

    // Load page inside admin template
    $content = $this->parser->parse('job/matching_candidates', $data, true);
    $this->template->full_admin_html_view($content);
}







}