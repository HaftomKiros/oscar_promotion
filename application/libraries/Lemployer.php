<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lemployer {
     
	public function employer_add_form(){
		 $CI =& get_instance();
		$CI->load->model('Educations');
		$data = array(
				'title' => display('Add Employer'),
			);
		$customerForm = $CI->parser->parse('employer/add_employer',$data,true);
		return $customerForm;

	}
    public function employer_list()
    {
        $CI =& get_instance();
        $CI->load->model('Company_model');

        $employers = $CI->Company_model->get_all_employers();

        $data = array(
            'title'         => 'Manage Employers',
            'employer_list' => $employers
        );

        // Use load->view instead of parser to ensure $this->permission1 works correctly
        return $CI->load->view('employer/employer_list', $data, true);
    }


}