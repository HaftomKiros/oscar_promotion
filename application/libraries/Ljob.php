<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ljob {
     
	public function job_add_form() {
    $CI =& get_instance();
    $CI->load->model('Candidate_model');
    $CI->load->model('Company_model');
    $CI->load->model('Employment');

    // Fetch data
    $data['educational_levels'] = $CI->Candidate_model->getEducationalLevels();
    $data['fields_of_study']   = $CI->Candidate_model->getFieldsOfStudy();
    $data['zones']             = $CI->Candidate_model->getZones();

    // Fetch companies
    $data['companies']         = $CI->Company_model->get_all_employers(); 

    // Fetch employment types
    $data['employment_types']  = $CI->Employment->get_all_emp_type();

    // Fetch salary ranges
    $data['salary_ranges']     = $CI->Employment->get_all_sal_range();

    // Page title
    $data['title'] = display('add_job');

    // Load view
    $customerForm = $CI->parser->parse('job/job', $data, true);
    return $customerForm;
}


    

}