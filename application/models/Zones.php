<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Zones extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function zone_insert($data)
{
    return $this->db->insert('zone', $data);
}
public function get_all_zones()
{
    return $this->db->select('*')
                    ->from('zone')
                    ->order_by('id', 'DESC')
                    ->get()
                    ->result_array();
}
public function get_zone_by_id($id)
{
    return $this->db->where('id', $id)->get('zone')->row_array();
}

public function update_zone($id, $data)
{
    return $this->db->where('id', $id)->update('zone', $data);
}
 public function delete_zone($id)
    {
        return $this->db->where('id', $id)->delete('zone');
    }



}