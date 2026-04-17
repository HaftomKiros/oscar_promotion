<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cemployment extends CI_Controller {
    public $menu;
    function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lemployment');
        $this->load->library('session');
        $this->auth->check_admin_auth();
        $this->load->model('Employment');
        $this->load->model('Web_settings');
    }
    
    //Default loading for Employment Type and Salary Range
    public function index()
    {
        $content = $this->lemployment->employment_add_form();
        $this->template->full_admin_html_view($content);
    }
    
    //Add Salary Range Form
    public function add_sal_range()
    {
        $content = $this->lemployment->salary_range_add_form();
        $this->template->full_admin_html_view($content);
    }
    
    // ========== EMPLOYMENT TYPE METHODS ==========
    
    //Insert Employment Type
    public function insert_emp_type()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules(
            'emp_type',
            'Employment Type',
            'required|trim|is_unique[emp_type.emp_name]',
            array('is_unique' => 'This employment type already exists.')
        );
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
            redirect('Cemployment');
        } else {
            $data = array(
                'emp_name' => $this->input->post('emp_type', TRUE),
                'status' => 1
            );
            
            $this->Employment->insert_emp_type($data);
            
            $this->session->set_flashdata('message', 'Employment type added successfully.');
            redirect('Cemployment');
        }
    }
    
    //Manage Employment Type
    public function manage_emp_type()
    {
        $data['emp_types'] = $this->Employment->get_all_emp_type();
        $data['title'] = "Manage Employment Types";
        $data['message'] = $this->session->flashdata('message');
        $data['error_message'] = $this->session->flashdata('error_message');
        
        $content = $this->parser->parse('employment/manage_emp_type', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    //Edit Employment Type
    public function edit_emp_type($id)
    {
        $emp_type = $this->Employment->get_emp_type_by_id($id);
        
        if (!$emp_type) {
            $this->session->set_flashdata('error_message', 'Record not found.');
            redirect('Cemployment/manage_emp_type');
        }
        
        $data = array(
            'title'     => 'Edit Employment Type',
            'emp_type'  => $emp_type,
        );
        
        $content = $this->parser->parse('employment/edit_emp_type', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    //Update Employment Type
    public function update_emp_type($id)
    {
        $this->load->library('form_validation');
        
        $existing = $this->Employment->get_emp_type_by_id($id);
        if (!$existing) {
            $this->session->set_flashdata('error_message', 'Record not found.');
            redirect('Cemployment/manage_emp_type');
        }
        
        $new_emp_type = $this->input->post('emp_type', TRUE);
        
        // Check duplicate
        $this->db->where('emp_name', $new_emp_type);
        $this->db->where('id !=', $id);
        $duplicate = $this->db->get('emp_type')->row();
        
        if ($duplicate) {
            $this->session->set_flashdata('error_message', 'This employment type already exists.');
            redirect('Cemployment/edit_emp_type/'.$id);
        }
        
        $data = array(
            'emp_name'  => $new_emp_type,
            'status'    => $this->input->post('status', TRUE)
        );
        
        $this->Employment->update_emp_type($id, $data);
        
        $this->session->set_flashdata('message', 'Employment type updated successfully.');
        redirect('Cemployment/manage_emp_type');
    }
    
    //Delete Employment Type
    public function delete_emp_type($id)
    {
        $this->Employment->delete_emp_type($id);
        
        $this->session->set_flashdata('message', 'Employment type deleted successfully.');
        redirect('Cemployment/manage_emp_type');
    }
    
    // ========== SALARY RANGE METHODS ==========
    
    //Insert Salary Range
    public function insert_sal_range()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules(
            'sal_range',
            'Salary Range',
            'required|trim|is_unique[sal_range.sal_range]',
            array('is_unique' => 'This salary range already exists.')
        );
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
            redirect('Cemployment/manage_sal_range');
        } else {
            $data = array(
                'sal_range' => $this->input->post('sal_range', TRUE),
                'status' => 1
            );
            
            $this->Employment->insert_sal_range($data);
            
            $this->session->set_flashdata('message', 'Salary range added successfully.');
            redirect('Cemployment/manage_sal_range');
        }
    }
    
    //Manage Salary Range
    public function manage_sal_range()
    {
        $data['sal_ranges'] = $this->Employment->get_all_sal_range();
        $data['title'] = "Manage Salary Ranges";
        $data['message'] = $this->session->flashdata('message');
        $data['error_message'] = $this->session->flashdata('error_message');
        
        $content = $this->parser->parse('employment/manage_sal_range', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    //Edit Salary Range
    public function edit_sal_range($id)
    {
        $sal_range = $this->Employment->get_sal_range_by_id($id);
        
        if (!$sal_range) {
            $this->session->set_flashdata('error_message', 'Record not found.');
            redirect('Cemployment/manage_sal_range');
        }
        
        $data = array(
            'title'     => 'Edit Salary Range',
            'sal_range' => $sal_range,
        );
        
        $content = $this->parser->parse('employment/edit_sal_range', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    //Update Salary Range
    public function update_sal_range($id)
    {
        $this->load->library('form_validation');
        
        $existing = $this->Employment->get_sal_range_by_id($id);
        if (!$existing) {
            $this->session->set_flashdata('error_message', 'Record not found.');
            redirect('Cemployment/manage_sal_range');
        }
        
        $new_sal_range = $this->input->post('sal_range', TRUE);
        
        // Check duplicate
        $this->db->where('sal_range', $new_sal_range);
        $this->db->where('id !=', $id);
        $duplicate = $this->db->get('sal_range')->row();
        
        if ($duplicate) {
            $this->session->set_flashdata('error_message', 'This salary range already exists.');
            redirect('Cemployment/edit_sal_range/'.$id);
        }
        
        $data = array(
            'sal_range' => $new_sal_range,
            'status'    => $this->input->post('status', TRUE)
        );
        
        $this->Employment->update_sal_range($id, $data);
        
        $this->session->set_flashdata('message', 'Salary range updated successfully.');
        redirect('Cemployment/manage_sal_range');
    }
    
    //Delete Salary Range
    public function delete_sal_range($id)
    {
        $this->Employment->delete_sal_range($id);
        
        $this->session->set_flashdata('message', 'Salary range deleted successfully.');
        redirect('Cemployment/manage_sal_range');
    }
}
