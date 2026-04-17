<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job_model extends CI_Model {

    // Cache for lookup tables to avoid repeated queries
    private $cache = [];
    
    /**
     * Get cached lookup data - loads once per request
     */
    private function get_lookup_cache() {
        if (empty($this->cache)) {
            // Clear any previous queries first
            $this->db->reset_query();
            $this->cache['zones'] = $this->db->get('zone')->result_array();
            
            $this->db->reset_query();
            $this->cache['education'] = $this->db->get('educational_level')->result_array();
            
            $this->db->reset_query();
            $this->cache['fields'] = $this->db->get('field_of_study')->result_array();
            
            // Build maps for O(1) lookups
            $this->cache['zoneMap'] = array_column($this->cache['zones'], 'zone_name', 'id');
            $this->cache['educationMap'] = array_column($this->cache['education'], 'level', 'id');
            $this->cache['fieldMap'] = array_column($this->cache['fields'], 'field', 'id');
        }
        return $this->cache;
    }

    public function insert_job($data)
    {
        return $this->db->insert('jobs', $data);
    }
    
   public function get_all_jobs()
   {
       // Fetch job list with company name - single query
       $jobs = $this->db->select('jobs.*, company.company_name')
                        ->from('jobs')
                        ->join('company', 'company.id = jobs.company_id', 'left')
                        ->order_by('jobs.id', 'DESC')
                        ->get()
                        ->result_array();

       // Early return if no jobs - avoid unnecessary processing
       if (empty($jobs)) {
           return $jobs;
       }
       
       // Get cached lookup tables (loaded once)
       $this->get_lookup_cache();
       $zoneMap = $this->cache['zoneMap'];
       $educationMap = $this->cache['educationMap'];
       $fieldMap = $this->cache['fieldMap'];

       // Process each job
       foreach ($jobs as &$job) {

           // ----- LOCATION (JSON) -----
           $locIds = json_decode($job['location'], true);
           if (is_array($locIds)) {
               $locNames = [];
               foreach ($locIds as $locId) {
                   if (isset($zoneMap[$locId])) {
                       $locNames[] = $zoneMap[$locId];
                   }
               }
               $job['location_names'] = implode(", ", $locNames);
           } else {
               $job['location_names'] = '';
           }

           // ----- EDUCATION (JSON Array) -----
           $eduIds = json_decode($job['education_level'], true);
           if (is_array($eduIds)) {
               $eduNames = [];
               foreach ($eduIds as $eduId) {
                   if (isset($educationMap[$eduId])) {
                       $eduNames[] = $educationMap[$eduId];
                   }
               }
               $job['education_name'] = implode(", ", $eduNames);
           } else {
               $job['education_name'] = isset($educationMap[$job['education_level']])
                                       ? $educationMap[$job['education_level']]
                                       : 'Unknown';
           }

           // ----- FIELD OF STUDY (JSON) -----
           $fieldIds = json_decode($job['field_of_study'], true);
           if (is_array($fieldIds)) {
               $fnames = [];
               foreach ($fieldIds as $fid) {
                   if (isset($fieldMap[$fid])) {
                       $fnames[] = $fieldMap[$fid];
                   }
               }
               $job['field_names'] = implode(", ", $fnames);
           } else {
               $job['field_names'] = '';
           }
       }

       return $jobs;
   }
   
   public function get_job_by_id($id)
   {
       return $this->db->select('jobs.*, company.company_name')
                       ->from('jobs')
                       ->join('company', 'company.id = jobs.company_id', 'left')
                       ->where('jobs.id', $id)
                       ->get()
                       ->row_array();
   }
   

   public function get_companies() {
       // Check cache first
       if (!isset($this->cache['companies'])) {
           $this->cache['companies'] = $this->db->get('company')->result_array();
       }
       return $this->cache['companies'];
   }

   public function get_zones() {
       $this->db->reset_query();
       return $this->db->get('zone')->result_array();
   }

   public function get_education_levels() {
       $this->db->reset_query();
       return $this->db->get('educational_level')->result_array();
   }

   public function get_fields() {
       $this->db->reset_query();
       return $this->db->get('field_of_study')->result_array();
   }

   public function update_job($id, $data)
   {
       $this->db->where('id', $id);
       $this->db->update('jobs', $data);

       // Return TRUE if update succeeded, FALSE if error
       if ($this->db->affected_rows() >= 0) {
           return true;
       } else {
           return false;
       }
   }
   public function delete_job($id)
   {
       $this->db->where('id', $id);
       return $this->db->delete('jobs');
   }

}
