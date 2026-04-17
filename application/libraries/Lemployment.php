<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lemployment {
    public function employment_add_form(){
        $CI =& get_instance();
        $CI->load->model('Employment');
        $data = array(
            'title' => display('add_employment_type'),
        );
        $form = $CI->parser->parse('employment/add_emp_type',$data,true);
        return $form;
    }
    
    public function salary_range_add_form(){
        $CI =& get_instance();
        $CI->load->model('Employment');
        $data = array(
            'title' => display('add_salary_range'),
        );
        $form = $CI->parser->parse('employment/add_sal_range',$data,true);
        return $form;
    }
}
