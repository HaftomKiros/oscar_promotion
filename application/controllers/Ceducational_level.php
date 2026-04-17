<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ceducational_level extends CI_Controller {
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
		$content = $this->leducation->education_add_form();
	//Here ,0 means array position 0 will be active class
		$this->template->full_admin_html_view($content);
	
	}
	public function insert_educational_level()
{
    $this->load->library('form_validation');

  $this->form_validation->set_rules(
    'educational_level',
    'Educational Level',
    'required|trim|is_unique[educational_level.level]',
    array('is_unique' => 'This educational level already exists.')
);



    if ($this->form_validation->run() == FALSE) {

       $this->session->set_flashdata('error_message', validation_errors());
        redirect('Ceducational_level');

    } else {

        $data = array(
            'level' => $this->input->post('educational_level', TRUE),
            'status' => 1
        );

        // call model
        $this->Educations->insert($data);

        $this->session->set_flashdata('message', 'Educational level added successfully.');
        redirect('Ceducational_level');
    }
}
public function manage_educational_level()
{
    $this->load->model('Educations');

    // Fetch all educational levels
    $data['educational_levels'] = $this->Educations->get_all();

    // Page title
    $data['title'] = "Manage Educational Levels";

    // Flash message (success or error)
    $data['message'] = $this->session->flashdata('message');
    $data['error_message'] = $this->session->flashdata('error_message');

    // Load view
    $content = $this->parser->parse('education/manage_educational_level', $data, true);

    // Load layout
    $this->template->full_admin_html_view($content);
}
public function edit($id)
{
    $this->load->model('Educations');

    // Get record
    $educational_level = $this->Educations->get_by_id($id);

    if (!$educational_level) {
        $this->session->set_flashdata('error_message', 'Record not found.');
        redirect('Ceducational_level/manage_educational_level');
    }

    // Page data
    $data = array(
        'title'               => 'Edit Educational Level',
        'educational_level'   => $educational_level,
    );

    $content = $this->parser->parse('education/edit_educational_level', $data, true);
    $this->template->full_admin_html_view($content);
}


public function delete($id)
{
    $this->load->model('Educations');

    $this->Educations->delete($id);

    $this->session->set_flashdata('message', 'Educational level deleted successfully.');
    redirect('Ceducational_level/manage_educational_level');
}
public function update($id)
{
    $this->load->library('form_validation');
    $this->load->model('Educations');

    // Fetch existing record
    $existing = $this->Educations->get_by_id($id);
    if (!$existing) {
        $this->session->set_flashdata('error_message', 'Record not found.');
        redirect('Ceducational_level/manage_educational_level');
    }

    $new_level = $this->input->post('educational_level', TRUE);

    // Check if the same name exists (excluding current record)
    $this->db->where('level', $new_level);
    $this->db->where('id !=', $id);
    $duplicate = $this->db->get('educational_level')->row();

    if ($duplicate) {
        $this->session->set_flashdata('error_message', 'This educational level already exists.');
        redirect('Ceducational_level/edit/'.$id);
    }

    // Prepare data
    $data = array(
        'level'  => $new_level,
        'status' => $this->input->post('status', TRUE)
    );

    // Update record
    $this->Educations->update($id, $data);

    $this->session->set_flashdata('message', 'Educational level updated successfully.');
    redirect('Ceducational_level/manage_educational_level');
}




}




