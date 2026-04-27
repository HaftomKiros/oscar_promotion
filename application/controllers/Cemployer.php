<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cemployer extends CI_Controller {
	public $menu;
	function __construct() {
      parent::__construct();
		$this->load->library('auth');
		$this->load->library('lemployer');
		$this->load->library('session');
		$this->auth->check_admin_auth();
        //$this->load->model('Educations');
        $this->load->model('Web_settings');
    }
	//Default loading for Customer System.
	public function index()
	{
        //Calling Customer add form which will be loaded by help of "lcustomer,located in library folder"
		$content = $this->lemployer->employer_add_form();
	//Here ,0 means array position 0 will be active class
		$this->template->full_admin_html_view($content);
	
	
	}
    public function insert_employer()
{
    $this->load->library('form_validation');
    $this->load->model('Company_model');

    // -------------------------
    // FORM VALIDATION RULES
    // -------------------------
    $this->form_validation->set_rules('company_name', 'Company Name', 'required|trim|is_unique[company.company_name]');
    $this->form_validation->set_rules('sector', 'Sector', 'trim');
    $this->form_validation->set_rules('no_emp', 'Number of Employees', 'integer|trim');

    $this->form_validation->set_rules('company_size', 'Company Size', 'trim');
    $this->form_validation->set_rules('operation_since', 'Operation Since', 'integer|trim');

    $this->form_validation->set_rules('description_services_products', 'Description of Services/Products', 'trim');
    $this->form_validation->set_rules('mission_vision_statement', 'Mission/Vision Statement', 'trim');

    $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim');
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
    $this->form_validation->set_rules('website', 'Website', 'trim');

    $this->form_validation->set_rules('address', 'Address', 'trim');

    // -------------------------
    // RUN VALIDATION
    // -------------------------
    if ($this->form_validation->run() === false) {
        $this->session->set_flashdata('error_message', validation_errors());
        redirect('Cemployer');
        return;
    }

    // -------------------------
    // COLLECT DATA
    // -------------------------
    $data = [
        'company_name'                  => $this->input->post('company_name', true),
        'sector'                        => $this->input->post('sector', true),
        'no_emp'                        => $this->input->post('no_emp', true),
        'company_size'                  => $this->input->post('company_size', true),
        'operation_since'               => $this->input->post('operation_since', true),
        'description_services_products' => $this->input->post('description_services_products', true),
        'mission_vision_statement'      => $this->input->post('mission_vision_statement', true),
        'phone_number'                  => $this->input->post('phone_number', true),
        'email'                         => $this->input->post('email', true),
        'website'                       => $this->input->post('website', true),
        'address'                       => $this->input->post('address', true),
        'status'                        => 1 // default active
    ];

    // -------------------------
    // INSERT INTO DATABASE
    // -------------------------
    $insert_id = $this->Company_model->insert_employer($data);

    if ($insert_id) {
        $this->session->set_flashdata('message', 'Employer added successfully.');
    } else {
        $this->session->set_flashdata('error_message', 'Failed to add employer, please try again.');
    }

    redirect('Cemployer/manage_employer');
}

public function manage_employer()
{
    $this->load->model('Company_model');
    $content = $this->lemployer->employer_list();
    $this->template->full_admin_html_view($content);
}
public function delete($id)
{
    $this->load->model('Company_model');

    if ($this->Company_model->delete_employer($id)) {
        $this->session->set_flashdata('message', 'Employer deleted successfully.');
    } else {
        $this->session->set_flashdata('error_message', 'Failed to delete employer.');
    }

    redirect('Cemployer/manage_employer');
}
public function edit($id)
{
    $this->load->model('Company_model');

    $data['title']    = display('edit_employer');
    $data['employer'] = $this->Company_model->get_employer_by_id($id);

    if (!$data['employer']) {
        $this->session->set_flashdata('error_message', display('employer_not_found'));
        redirect('Cemployer/manage_employer');
    }

    $content = $this->load->view('employer/edit_employer', $data, true);
    $this->template->full_admin_html_view($content);
}
public function update_employer($id)
{
    $this->load->library('form_validation');
    $this->load->model('Company_model');

    // Validate ID
    if (empty($id) || !is_numeric($id)) {
        $this->session->set_flashdata('error_message', 'Invalid Employer ID');
        redirect('Cemployer/manage_employer');
    }

    // Validation rules (only company_name required)
    $this->form_validation->set_rules('company_name', 'Company Name', 'required|trim');
    $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim');
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
    $this->form_validation->set_rules('address', 'Address', 'trim');
    $this->form_validation->set_rules('status', 'Status', 'trim');

    if ($this->form_validation->run() == FALSE) {

        $this->session->set_flashdata('error_message', validation_errors());
        redirect('Cemployer/edit/' . $id);

    } else {

        // Collect all fields from the form
        $data = [
            'company_name'                  => $this->input->post('company_name', TRUE),
            'sector'                        => $this->input->post('sector', TRUE),
            'no_emp'                        => $this->input->post('no_emp', TRUE),
            'company_size'                  => $this->input->post('company_size', TRUE),
            'operation_since'               => $this->input->post('operation_since', TRUE),
            'description_services_products' => $this->input->post('description_services_products', TRUE),
            'mission_vision_statement'      => $this->input->post('mission_vision_statement', TRUE),
            'phone_number'                  => $this->input->post('phone_number', TRUE),
            'email'                         => $this->input->post('email', TRUE),
            'website'                       => $this->input->post('website', TRUE),
            'address'                       => $this->input->post('address', TRUE),
            'status'                        => $this->input->post('status', TRUE),
        ];

        // Check if employer exists
        $employer = $this->Company_model->get_employer_by_id($id);
        if (!$employer) {
            $this->session->set_flashdata('error_message', 'Employer not found.');
            redirect('Cemployer/manage_employer');
        }

        // Update record
        $update = $this->Company_model->update_employer($id, $data);

        if ($update) {
            $this->session->set_flashdata('message', 'Employer updated successfully!');
        } else {
            $this->session->set_flashdata('error_message', 'Update failed! Please try again.');
        }

        redirect('Cemployer/manage_employer');
    }
}









}




