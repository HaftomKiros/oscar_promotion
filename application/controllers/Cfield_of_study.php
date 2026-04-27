<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cfield_of_study extends CI_Controller {
	public $menu;
	function __construct() {
      parent::__construct();
		$this->load->library('auth');
		$this->load->library('leducation');
		$this->load->library('session');
		$this->auth->check_admin_auth();
        $this->load->model('Educations');
        $this->load->model('Web_settings');
    }
	//Default loading for Customer System.
	public function index()
	{
		//Calling Customer add form which will be loaded by help of "lcustomer,located in library folder"
		$content = $this->leducation->field_add_form();
	//Here ,0 means array position 0 will be active class
		$this->template->full_admin_html_view($content);
	
	}
   public function insert_field_of_study()
{
    $this->load->model('Educations');

    $this->form_validation->set_rules(
    'field_of_study',
    'Field of Study',
    'required|trim|is_unique[field_of_study.field]',
    array(
        'is_unique' => 'This field of study already exists.'
    )
);


    if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('error_message', validation_errors());
        redirect('Cfield_of_study');
    }

    $data = [
        'field' => $this->input->post('field_of_study', true),
        'status'     => 1,
    ];

    // Insert via Model
    $this->Educations->insert_field_of_study($data);

    $this->session->set_flashdata('message', 'Field of Study added successfully');
    redirect('Cfield_of_study/manage_field_of_study');
}
public function manage_field_of_study(){
     $this->load->model('Educations');

    // Fetch all educational levels
    $data['fields_of_study'] = $this->Educations->get_all_fields();

    // Page title
    $data['title'] = "Manage Filds of study";

    // Flash message (success or error)
    $data['message'] = $this->session->flashdata('message');
    $data['error_message'] = $this->session->flashdata('error_message');

    // Load view
    $content = $this->parser->parse('education/manage_fields_of_study', $data, true);

    // Load layout
    $this->template->full_admin_html_view($content);
}
public function edit($id)
{
    $this->load->model('Educations');

    $field = $this->Educations->get_field_by_id($id);

    if (!$field) {
        $this->session->set_flashdata('error_message', 'Record not found.');
        redirect('Cfield_of_study/manage_field_of_study');
    }

    $data = array(
        'title' => 'Edit Field of Study',
        'field' => $field,
    );

    // Use load->view directly — parser can't handle object variables
    $content = $this->load->view('education/edit_field_of_study', $data, true);
    $this->template->full_admin_html_view($content);
}

public function update_field_of_study()
{
    $this->load->model('Educations');

    $id = $this->input->post('id', true);
    $field = $this->input->post('field_of_study', true);
    $status = $this->input->post('status', true);

    // Validation: required only
    $this->form_validation->set_rules(
        'field_of_study',
        'Field of Study',
        'required|trim'
    );

    $this->form_validation->set_rules(
        'status',
        'Status',
        'required'
    );

    if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('error_message', validation_errors());
        redirect('Cfield_of_study/edit/' . $id);
    }

    // Prepare data for update
    $data = [
        'field'  => $field,
        'status' => $status
    ];

    // Update record
    $this->Educations->update_field_of_study($id, $data);

    $this->session->set_flashdata('message', 'Field of Study updated successfully.');
    redirect('Cfield_of_study/manage_field_of_study');
}
public function delete($id = null)
{
    if ($id == null) {
        $this->session->set_flashdata('error_message', 'Invalid request.');
        redirect('Cfield_of_study/manage_field_of_study');
    }

    $this->load->model('Educations');

    // Delete record
    $deleted = $this->Educations->delete_field_of_study($id);

    if ($deleted) {
        $this->session->set_flashdata('message', 'Field of Study deleted successfully.');
    } else {
        $this->session->set_flashdata('error_message', 'Failed to delete Field of Study.');
    }

    redirect('Cfield_of_study/manage_field_of_study');
}






}




