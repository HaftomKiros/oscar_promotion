<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company_model extends CI_Model {

    public function insert_employer($data)
    {
        $this->db->insert('company', $data);
        return $this->db->insert_id();
    }

    public function check_unique_email($email)
    {
        return $this->db->where('email', $email)->get('company')->num_rows() == 0;
    }

    public function check_unique_phone($phone)
    {
        return $this->db->where('phone_number', $phone)->get('company')->num_rows() == 0;
    }
  public function get_all_employers()
    {
        return $this->db->get('company')->result_array();
    }
    public function delete_employer($id)
{
    return $this->db->where('id', $id)->delete('company');
}
public function get_employer_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get('company')
                        ->row_array();
    }
public function update_employer($id, $data)
{
    return $this->db->where('id', $id)->update('company', $data);
}
public function count_employer()
{
    return $this->db->count_all('company');
}



}
 