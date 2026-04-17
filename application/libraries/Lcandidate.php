<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lcandidate {
     
	public function candidate_add_form() {
    $CI =& get_instance();
    $CI->load->model('Candidate_model');

    // Fetch data from DB
    $data['educational_levels'] = $CI->Candidate_model->getEducationalLevels();
    $data['fields_of_study'] = $CI->Candidate_model->getFieldsOfStudy();
    $data['zones'] = $CI->Candidate_model->getZones();

    $data['title'] = display('add_candidate');

    $customerForm = $CI->parser->parse('candidate/add_candidate', $data, true);
    return $customerForm;
}

    

}