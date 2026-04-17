<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate_model extends CI_Model {

    // Cache for lookup tables to avoid repeated queries per request
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

    public function getEducationalLevels() {
        $this->db->reset_query();
        return $this->db->get('educational_level')->result_array();
    }

    public function getFieldsOfStudy() {
        $this->db->reset_query();
        return $this->db->get('field_of_study')->result_array();
    }

    public function getZones() {
        $this->db->reset_query();
        return $this->db->get('zone')->result_array();
    }
    
    public function get_all_candidates()
    {
        // Single optimized query with joins
        $this->db->select('
            c.*,
            l.zone_name AS location,
            e.level AS education_level,
            f.field AS field_of_study
        ');
        $this->db->from('candidates c');

        // INNER JOINS - using index-friendly joins
        $this->db->join('zone l', 'l.id = c.location', 'left');
        $this->db->join('educational_level e', 'e.id = c.education_level', 'left');
        $this->db->join('field_of_study f', 'f.id = c.field_of_study', 'left');

        // Order by for consistent results
        $this->db->order_by('c.id', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    public function get_candidate_by_id($id)
    {
        $this->db->select('
            c.*,
            c.location_text,
            l.id AS location_id,
            l.zone_name AS location_zone,
            e.id AS education_level_id,
            e.level AS education_level,
            f.id AS field_of_study_id,
            f.field AS field_of_study
        ');

        $this->db->from('candidates c');

        $this->db->join('zone l', 'l.id = c.location', 'left');
        $this->db->join('educational_level e', 'e.id = c.education_level', 'left');
        $this->db->join('field_of_study f', 'f.id = c.field_of_study', 'left');

        $this->db->where('c.id', $id);

        return $this->db->get()->row_array();
    }
    
    public function delete_candidate($id)
    {
        return $this->db->where('id', $id)->delete('candidates');
    }
    
    public function count_candidate()
    {
        return $this->db->count_all('candidates');
    }
    
    /**
     * Get candidates with pagination (server-side)
     */
    public function get_candidates_paginated($limit, $offset, $search = '', $order_column = 'id', $order_dir = 'DESC', $woreda = '', $sex = '') {
        // Build base query for counting
        $this->db->from('candidates c');
        
        // Woreda filter
        if (!empty($woreda)) {
            $this->db->where('c.woreda', $woreda);
        }
        
        // Sex filter
        if (!empty($sex)) {
            $this->db->where('c.sex', $sex);
        }
        
        // Search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.full_name', $search);
            $this->db->or_like('c.seeker_id', $search);
            $this->db->or_like('c.phone_number', $search);
            $this->db->or_like('c.email', $search);
            $this->db->group_end();
        }
        
        // Get total filtered count
        $total = $this->db->count_all_results();
        
        // Build query with joins for data
        $this->db->reset_query();
        $this->db->select('
            c.*,
            l.zone_name AS location,
            e.level AS education_level,
            f.field AS field_of_study
        ');
        $this->db->from('candidates c');
        $this->db->join('zone l', 'l.id = c.location', 'left');
        $this->db->join('educational_level e', 'e.id = c.education_level', 'left');
        $this->db->join('field_of_study f', 'f.id = c.field_of_study', 'left');
        
        // Woreda filter
        if (!empty($woreda)) {
            $this->db->where('c.woreda', $woreda);
        }
        
        // Sex filter
        if (!empty($sex)) {
            $this->db->where('c.sex', $sex);
        }
        
        // Search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.full_name', $search);
            $this->db->or_like('c.seeker_id', $search);
            $this->db->or_like('c.phone_number', $search);
            $this->db->or_like('c.email', $search);
            $this->db->group_end();
        }
        
        // Order
        $this->db->order_by("c." . $order_column, $order_dir);
        
        // Limit and offset
        $this->db->limit($limit, $offset);
        
        $result = $this->db->get()->result_array();
        
        return [
            'data' => $result,
            'total' => $total,
            'filtered' => $total
        ];
    }
    
    /**
     * Get ALL candidates for export (no pagination)
     */
    public function get_all_candidates_for_export($sex = '')
    {
        // Single optimized query with joins
        $this->db->select('
            c.*,
            l.zone_name AS location,
            e.level AS education_level,
            f.field AS field_of_study
        ');
        $this->db->from('candidates c');

        // INNER JOINS - using index-friendly joins
        $this->db->join('zone l', 'l.id = c.location', 'left');
        $this->db->join('educational_level e', 'e.id = c.education_level', 'left');
        $this->db->join('field_of_study f', 'f.id = c.field_of_study', 'left');
        
        // Sex filter
        if (!empty($sex)) {
            $this->db->where('c.sex', $sex);
        }

        // Order by for consistent results
        $this->db->order_by('c.id', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    /**
     * Get distinct woredas with count for filtering
     */
    public function get_distinct_woredas()
    {
        $this->db->select('woreda, COUNT(*) as count');
        $this->db->where('woreda IS NOT NULL', null, false);
        $this->db->where('woreda !=', '');
        $this->db->group_by('woreda');
        $this->db->order_by('woreda', 'ASC');
        $result = $this->db->get('candidates')->result_array();
        
        $woreda_counts = [];
        foreach ($result as $row) {
            $woreda_counts[$row['woreda']] = $row['count'];
        }
        return $woreda_counts;
    }
    
    /**
     * Get woredas with sex counts for filtering
     */
    public function get_woredas_with_sex_counts()
    {
        // Get male counts per woreda
        $this->db->reset_query();
        $this->db->select('woreda, COUNT(*) as count');
        $this->db->where('woreda IS NOT NULL', null, false);
        $this->db->where('woreda !=', '');
        $this->db->where('sex', 'Male');
        $this->db->group_by('woreda');
        $male_result = $this->db->get('candidates')->result_array();

        $woreda_data = [];
        foreach ($male_result as $row) {
            $woreda_data[$row['woreda']] = ['Male' => (int)$row['count'], 'Female' => 0];
        }

        // Get female counts per woreda
        $this->db->reset_query();
        $this->db->select('woreda, COUNT(*) as count');
        $this->db->where('woreda IS NOT NULL', null, false);
        $this->db->where('woreda !=', '');
        $this->db->where('sex', 'Female');
        $this->db->group_by('woreda');
        $female_result = $this->db->get('candidates')->result_array();

        foreach ($female_result as $row) {
            if (!isset($woreda_data[$row['woreda']])) {
                $woreda_data[$row['woreda']] = ['Male' => 0, 'Female' => 0];
            }
            $woreda_data[$row['woreda']]['Female'] = (int)$row['count'];
        }

        // Total = Male + Female
        foreach ($woreda_data as $w => $d) {
            $woreda_data[$w]['total'] = $d['Male'] + $d['Female'];
        }

        ksort($woreda_data);
        return $woreda_data;
    }
    
    /**
     * Get candidates filtered by woreda
     */
    public function get_candidates_by_woreda($woreda, $sex = '')
    {
        $this->db->select('candidates.*, zone.zone_name as location', FALSE);
        $this->db->from('candidates');
        $this->db->join('zone', 'zone.id = candidates.location', 'left');
        $this->db->where('woreda', $woreda);
        
        // Sex filter
        if (!empty($sex)) {
            $this->db->where('sex', $sex);
        }
        
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result_array();
    }
    
    /**
     * Get matching candidates for a job
     * Optimized with proper indexing support and cached lookups
     */
    public function get_matching_candidates($job)
    {
        // Decode the job's field of study and education level data
        // Add error handling for malformed JSON
        $job_fields = json_decode($job['field_of_study'] ?? '', true);
        $job_education = json_decode($job['education_level'] ?? '', true);
        
        // Ensure they are arrays, not null or false
        if (!is_array($job_fields)) {
            $job_fields = [];
        }
        if (!is_array($job_education)) {
            $job_education = [];
        }

        // Select required columns and the names from the lookup tables
        $this->db->select("
            candidates.*,
            zone.zone_name as location_name,
            educational_level.level as education_name,
            field_of_study.field as field_name,
            candidates.qualification_skills as skills
        ", FALSE);
        
        // From the candidates table
        $this->db->from('candidates');

        // Join with the zone, educational_level, and field_of_study tables
        $this->db->join('zone', 'zone.id = candidates.location', 'left');
        $this->db->join('educational_level', 'educational_level.id = candidates.education_level', 'left');
        $this->db->join('field_of_study', 'field_of_study.id = candidates.field_of_study', 'left');

        // Sex condition
        if (isset($job['sex']) && !empty($job['sex']) && $job['sex'] != 'Both') {
            $this->db->where('sex', $job['sex']);
        } else {
            $this->db->where_in('sex', ['Male', 'Female']);
        }

        // Age condition
        if (isset($job['age_value']) && $job['age_value'] !== '' && $job['age_value'] !== null) {
            $age_op = !empty($job['age_operator']) ? $job['age_operator'] : '>=';
            $this->db->where("age {$age_op}", (int)$job['age_value']);
        }

        // Education level condition - only add if array is not empty
        if (!empty($job_education)) {
            // Filter out any non-numeric values to prevent SQL errors
            $job_education = array_filter($job_education, 'is_numeric');
            if (!empty($job_education)) {
                $this->db->where_in('education_level', $job_education);
            }
        }

        // Field of study condition - only add if array is not empty
        if (!empty($job_fields)) {
            // Filter out any non-numeric values to prevent SQL errors
            $job_fields = array_filter($job_fields, 'is_numeric');
            if (!empty($job_fields)) {
                $this->db->where_in('field_of_study', $job_fields);
            }
        }

        // GPA condition
        if (isset($job['gpa_value']) && $job['gpa_value'] !== '' && $job['gpa_value'] !== null) {
            $gpa_op = !empty($job['gpa_operator']) ? $job['gpa_operator'] : '>=';
            $this->db->where("gpa {$gpa_op}", (float)$job['gpa_value']);
        }

        // Experience condition
        if (isset($job['experience_value']) && $job['experience_value'] !== '' && $job['experience_value'] !== null) {
            $exp_op = !empty($job['experience_operator']) ? $job['experience_operator'] : '>=';
            $this->db->where("experience {$exp_op}", (int)$job['experience_value']);
        }
    
        // Order by experience and GPA for best matches first
        $this->db->order_by('experience', 'DESC');
        $this->db->order_by('gpa', 'DESC');

        return $this->db->get()->result_array();
    }

}
