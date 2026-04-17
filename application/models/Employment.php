<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employment extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }
    
    // ========== Employment Type Methods ==========
    public function insert_emp_type($data) {
        return $this->db->insert('emp_type', $data);
    }
    
    public function get_all_emp_type()
    {
        return $this->db->select('*')
                        ->from('emp_type')
                        ->order_by('id', 'DESC')
                        ->get()
                        ->result_array();
    }
    
    public function get_emp_type_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get('emp_type')
            ->row_array();
    }
    
    public function update_emp_type($id, $data)
    {
        return $this->db->where('id', $id)->update('emp_type', $data);
    }
    
    public function delete_emp_type($id)
    {
        return $this->db->where('id', $id)->delete('emp_type');
    }
    
    // ========== Salary Range Methods ==========
    public function insert_sal_range($data) {
        return $this->db->insert('sal_range', $data);
    }
    
    public function get_all_sal_range()
    {
        return $this->db->select('*')
                        ->from('sal_range')
                        ->order_by('id', 'DESC')
                        ->get()
                        ->result_array();
    }
    
    public function get_sal_range_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get('sal_range')
            ->row_array();
    }
    
    public function update_sal_range($id, $data)
    {
        return $this->db->where('id', $id)->update('sal_range', $data);
    }
    
    public function delete_sal_range($id)
    {
        return $this->db->where('id', $id)->delete('sal_range');
    }
}
