<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lzone {

    public function zone_add_form(){
		$CI =& get_instance();
		$CI->load->model('zones');
		$data = array(
				'title' => display('add_zone'),
			);
		$customerForm = $CI->parser->parse('zone/add_zone_form',$data,true);
		return $customerForm;
	}
   public function zone_list()
{
    $CI =& get_instance();      
    $CI->load->model('Zones');

    // Fetch all zones
    $zones = $CI->Zones->get_all_zones();

    // Pass data to a view and return HTML
    $data = array(
        'title' => 'Manage Zones',
        'zones' => $zones
    );

    return $CI->parser->parse('zone/manage_zone', $data, true);
}

}