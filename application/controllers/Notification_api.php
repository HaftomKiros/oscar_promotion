<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_api extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    // Check for new incomplete candidate registrations
    public function check_new_candidates() {
        // Only for logged-in users
        if (!$this->session->userdata('isLogIn')) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $is_admin  = $this->session->userdata('isAdmin');
        $user_id   = $this->session->userdata('user_id');

        // Get last checked timestamp from session
        $last_check = $this->session->userdata('last_candidate_check');
        if (!$last_check) {
            $last_check = date('Y-m-d H:i:s', strtotime('-1 hour'));
        }

        // Build query
        $this->db->select('id, seeker_id, full_name, phone_number, created_at, assigned_to');
        $this->db->from('candidates');
        $this->db->where('profile_complete', 0);
        $this->db->where('created_at >', $last_check);

        // Admin sees all; data clerks see only their assigned candidates
        if (!$is_admin) {
            $this->db->where('assigned_to', $user_id);
        }

        $this->db->order_by('created_at', 'DESC');
        $new_candidates = $this->db->get()->result_array();

        // Update last check time
        $this->session->set_userdata('last_candidate_check', date('Y-m-d H:i:s'));

        if (count($new_candidates) > 0) {
            echo json_encode([
                'status'     => 'success',
                'has_new'    => true,
                'count'      => count($new_candidates),
                'candidates' => $new_candidates
            ]);
        } else {
            echo json_encode([
                'status'  => 'success',
                'has_new' => false,
                'count'   => 0
            ]);
        }
    }
}
