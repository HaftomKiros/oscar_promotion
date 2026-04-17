<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Leducation {
     public function education_add_form(){
          $CI =& get_instance();
		$CI->load->model('Educations');
		$data = array(
				'title' => display('add_educational_level'),
			);
		$customerForm = $CI->parser->parse('education/add_educational_level_form',$data,true);
		return $customerForm;
		
	}
	public function field_add_form(){
		 $CI =& get_instance();
		$CI->load->model('Educations');
		$data = array(
				'title' => display('add_field_of_study'),
			);
		$customerForm = $CI->parser->parse('education/add_field_form',$data,true);
		return $customerForm;

	}


}