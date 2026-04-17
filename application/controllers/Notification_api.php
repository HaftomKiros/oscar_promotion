<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_api extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    // Check for new incomplete candidate registrations
    public function check_new_candidates() {
        if (!$this->session->userdata('isLogIn')) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $is_admin = $this->session->userdata('isAdmin');
        $user_id  = $this->session->userdata('user_id');

        // Get ALL incomplete candidates for the bell count/list
        $this->db->select('c.id, c.seeker_id, c.full_name, c.phone_number, c.created_at, c.assigned_to, CONCAT(IFNULL(u.first_name,"")," ",IFNULL(u.last_name,"")) AS assigned_name');
        $this->db->from('candidates c');
        $this->db->join('users u', 'u.user_id = c.assigned_to', 'left');
        $this->db->where('c.profile_complete', 0);

        // Admin (isAdmin=1 or isAdmin='1') sees all; data clerks see only theirs
        if (!$is_admin || $is_admin == 0 || $is_admin === '0') {
            $this->db->where('c.assigned_to', $user_id);
        }

        $this->db->order_by('c.created_at', 'DESC');
        $all_pending = $this->db->get()->result_array();

        // Get only NEW ones since last check (for alert popup)
        $last_check = $this->session->userdata('last_candidate_check');
        if (!$last_check) {
            $last_check = date('Y-m-d H:i:s', strtotime('-1 minute'));
        }

        $new_ids = [];
        foreach ($all_pending as $c) {
            if ($c['created_at'] > $last_check) {
                $new_ids[] = (int)$c['id'];
            }
        }

        $this->session->set_userdata('last_candidate_check', date('Y-m-d H:i:s'));

        echo json_encode([
            'status'     => 'success',
            'total'      => count($all_pending),
            'has_new'    => count($new_ids) > 0,
            'new_ids'    => $new_ids,
            'candidates' => $all_pending
        ]);
    }
}
