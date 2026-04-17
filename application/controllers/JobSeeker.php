<?php defined('BASEPATH') OR exit('No direct script access allowed');

class JobSeeker extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Candidate_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    // Default method - redirects to register
    public function index() {
        $this->register();
    }

    // Public registration form
    public function register() {
        // If user is already logged in, redirect to admin dashboard
        if ($this->session->userdata('isLogIn')) {
            redirect('Admin_dashboard');
            return;
        }
        $data['educational_levels'] = $this->Candidate_model->getEducationalLevels();
        $this->load->view('jobseeker/register', $data);
    }

    // Handle form submission
    public function submit() {

        // ── reCAPTCHA verification (temporarily disabled - re-enable before hosting) ──
        /*
        $recaptcha_secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ_4Z6n3N'; // replace with your secret key
        $recaptcha_response = $this->input->post('g-recaptcha-response');

        if (empty($recaptcha_response)) {
            $data['errors'] = '<strong>Please complete the reCAPTCHA</strong> verification to confirm you are not a robot.';
            $data['recaptcha_error'] = 'Please tick the "I\'m not a robot" box above.';
            $data['educational_levels'] = $this->Candidate_model->getEducationalLevels();
            $this->load->view('jobseeker/register', $data);
            return;
        }

        $verify = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret=' .
            urlencode($recaptcha_secret) . '&response=' .
            urlencode($recaptcha_response)
        );
        $captcha_result = json_decode($verify);

        if (!$captcha_result || !$captcha_result->success) {
            $data['errors'] = '<strong>reCAPTCHA failed.</strong> Please try again.';
            $data['recaptcha_error'] = 'Verification failed. Please tick the box and try again.';
            $data['educational_levels'] = $this->Candidate_model->getEducationalLevels();
            $this->load->view('jobseeker/register', $data);
            return;
        }
        */

        $this->form_validation->set_rules('full_name',    'Full Name',    'required|trim');
        $this->form_validation->set_rules('sex',          'Sex',          'required');
        $this->form_validation->set_rules('dob_ethiopian','Date of Birth','required|trim');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|trim|is_unique[candidates.phone_number]');
        $this->form_validation->set_rules('education_level','Education Level','required');
        $this->form_validation->set_rules('experience',   'Years of Experience','required|trim');
        $this->form_validation->set_rules('qualification_skills','Qualification / Profession','required|trim');
        $this->form_validation->set_rules('location',     'Location',     'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['errors'] = validation_errors();
            $data['educational_levels'] = $this->Candidate_model->getEducationalLevels();
            $this->load->view('jobseeker/register', $data);
            return;
        }

        // Auto-generate seeker ID
        $max = $this->db->select_max('seeker_id')->get('candidates')->row()->seeker_id;
        $new_seeker_id = ($max == NULL || $max < 10000) ? 10000 : $max + 1;

        $data = array(
            'seeker_id'           => $new_seeker_id,
            'full_name'           => $this->input->post('full_name'),
            'sex'                 => $this->input->post('sex'),
            'dob_ethiopian'       => $this->input->post('dob_ethiopian'),
            'age'                 => $this->input->post('age'),
            'phone_number'        => $this->input->post('phone_number'),
            'education_level'     => $this->input->post('education_level'),
            'experience'          => $this->input->post('experience'),
            'qualification_skills'=> $this->input->post('qualification_skills'),
            'location'            => 0,
            'location_text'       => $this->input->post('location'),
            // Mark as incomplete - call center needs to fill rest
            'profile_complete'    => 0,
            // defaults for required fields
            'email'               => '',
            'total_family_size'   => 0,
            'field_of_study'      => 0,
            'gpa'                 => 0,
            'graduated_year'      => 0,
            'woreda'              => '',
            'tabia'               => '',
            'created_at'          => date('Y-m-d H:i:s'),
            'status'              => 1,
        );

        $this->db->insert('candidates', $data);
        $candidate_id = $this->db->insert_id();

        // ── Round-robin assignment to data clerks / call center agents ──
        // Only runs if the assigned_to column exists (run the migration SQL first)
        $fields = $this->db->list_fields('candidates');
        if (in_array('assigned_to', $fields)) {
            $this->db->select('user_id');
            $this->db->from('users');
            $this->db->where('status', 1);
            $this->db->order_by('user_id', 'ASC');
            $staff = $this->db->get()->result_array();

            if (!empty($staff)) {
                $assigned_to = $staff[0]['user_id'];
                $min_count   = PHP_INT_MAX;

                foreach ($staff as $s) {
                    $count = $this->db->where('assigned_to', $s['user_id'])
                                      ->where('profile_complete', 0)
                                      ->count_all_results('candidates');
                    if ($count < $min_count) {
                        $min_count   = $count;
                        $assigned_to = $s['user_id'];
                    }
                }

                $this->db->where('id', $candidate_id)
                         ->update('candidates', ['assigned_to' => $assigned_to]);
            }
        }

        $this->load->view('jobseeker/success', array('seeker_id' => $new_seeker_id, 'name' => $data['full_name']));
    }
}
