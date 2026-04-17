<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Educations extends CI_Model {
    public function __construct()
	{
		parent::__construct();
	}
    public function insert($data) {
        return $this->db->insert('educational_level', $data);
    }
    public function get_all()
    {
        return $this->db->select('*')
                        ->from('educational_level')
                        ->order_by('id', 'DESC')
                        ->get()
                        ->result_array();
    }
    public function get_by_id($id)
{
    return $this->db
        ->where('id', $id)
        ->get('educational_level')
        ->row_array();
}

    public function delete($id)
{
    return $this->db->where('id', $id)->delete('educational_level');
}
public function update($id, $data)
{
    return $this->db->where('id', $id)->update('educational_level', $data);
}
public function insert_field_of_study($data)
{
    return $this->db->insert('field_of_study', $data);
}
 public function get_all_fields()
    {
        return $this->db->select('*')
                        ->from('field_of_study')
                        ->order_by('id', 'DESC')
                        ->get()
                        ->result_array();
    }
   public function get_field_by_id($id)
{
    return $this->db->where('id', $id)->get('field_of_study')->row();
}
public function update_field_of_study($id, $data)
{
    return $this->db->where('id', $id)->update('field_of_study', $data);
}
public function delete_field_of_study($id)
{
    return $this->db->where('id', $id)->delete('field_of_study');
}





}