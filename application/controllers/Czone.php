<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Czone extends CI_Controller {
	public $menu;
	function __construct() {
      parent::__construct();
		$this->load->library('auth');
		$this->load->library('lzone');
		$this->load->library('session');
		$this->auth->check_admin_auth();
        $this->load->model('Zones');
        $this->load->model('Web_settings');
    }
	//Default loading for Customer System.
	public function index()
	{
	//Calling Customer add form which will be loaded by help of "lcustomer,located in library folder"
		$content = $this->lzone->zone_add_form();
	//Here ,0 means array position 0 will be active class
		$this->template->full_admin_html_view($content);
	}

 public function insert_zone()
{
    $this->load->library('form_validation');

    $this->form_validation->set_rules(
        'zone',
        'Zone Name',
        'required|trim|is_unique[zone.zone_name]',
        array(
            'required'  => 'Zone name is required.',
            'is_unique' => 'This zone name already exists.'
        )
    );

    if ($this->form_validation->run() == FALSE) {

        $this->session->set_flashdata('error_message', validation_errors());
        redirect('Czone');

    } else {

        $data = array(
            'zone_name' => $this->input->post('zone', TRUE),
            'status'    => 1
        );

        $result = $this->Zones->zone_insert($data);

        if ($result) {
            $this->session->set_flashdata('message', 'Zone added successfully!');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to add zone.');
        }

        redirect('Czone/manage_zone');
    }
}


public function manage_zone()
    {
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lzone');
        $CI->load->model('Zones');
        $content =$this->lzone->zone_list();
        $this->template->full_admin_html_view($content);
       
    }
  public function edit($id)
{
    $this->load->model('Zones');

    $data['zone'] = $this->Zones->get_zone_by_id($id);

    if (!$data['zone']) {
        $this->session->set_flashdata('error_message', 'Zone not found');
        redirect('Czone/manage_zone');
    }

    // Add title here
    $data['title'] = 'edit_zone';

    // Load the page
    $content = $this->parser->parse('zone/edit_zone', $data, true);
    $this->template->full_admin_html_view($content);
}


public function update_zone($id)
{
    $this->load->library('form_validation');
    $this->form_validation->set_rules('zone_name', 'Zone Name', 'required|trim');

    if ($this->form_validation->run() === FALSE) {
        $this->session->set_flashdata('error_message', validation_errors());
        redirect('Czone/edit/'.$id);
    }

    $data = [
        'zone_name' => $this->input->post('zone_name', true),
        'status' => $this->input->post('status')
    ];

    $this->load->model('Zones');
    $update = $this->Zones->update_zone($id, $data);

    if ($update) {
        $this->session->set_flashdata('message', 'Zone updated successfully!');
    } else {
        $this->session->set_flashdata('error_message', 'Failed to update zone.');
    }

    redirect('Czone/manage_zone');
}
// Controller: Czone.php
public function delete($id)
{
   

    $this->load->model('Zones');

    // Check if zone exists
    $zone = $this->Zones->get_zone_by_id($id);
    if (!$zone) {
        $this->session->set_flashdata('error_message', 'Zone not found.');
        redirect('Czone/manage_zone');
    }

    // Attempt delete
    $deleted = $this->Zones->delete_zone($id);

    if ($deleted) {
        $this->session->set_flashdata('message', 'Zone deleted successfully.');
    } else {
        $this->session->set_flashdata('error_message', 'Unable to delete the zone. It may have related data.');
    }

    redirect('Czone/manage_zone');
}




}