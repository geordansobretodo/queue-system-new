<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function login($admin_id, $password) {
        $this->db->where('username', $admin_id);
        $result = $this->db->get('userss');

        if ($result->num_rows() === 1) {
            $admin = $result->row_array();

            // if (password_verify($password, $admin['password'])) {
                return $admin;
            // }
        }
        return false;
    }

    public function get_active_admin()
    {

        $query = $this->db->get('userss');
        return $query->result_array();
    }

    public function count_ServedRegular()
    {
        $this->db->where('status', 'served');
        $this->db->where('DATE(queue_time)', date('Y-m-d'));
        return $this->db->count_all_results('regular_queue');
    }

    public function count_ServedPriority()
    {
        $this->db->where('status', 'served');
        $this->db->where('DATE(queue_time)', date('Y-m-d'));
        return $this->db->count_all_results('priority_queue');
    }

    public function count_PendingRegular()
    {
        $this->db->where('status', 'pending');
        $this->db->where('DATE(queue_time)', date('Y-m-d'));
        return $this->db->count_all_results('regular_queue');
    }

    public function count_PendingPriority()
    {
        $this->db->where('status', 'pending');
        $this->db->where('DATE(queue_time)', date('Y-m-d'));
        return $this->db->count_all_results('priority_queue');
    }

    public function count_NoShowRegular()
    {
        $this->db->where('status', 'No Show');
        $this->db->where('DATE(queue_time)', date('Y-m-d'));
        return $this->db->count_all_results('regular_queue');
    }

    public function count_NoShowPriority()
    {
        $this->db->where('status', 'No Show');
        $this->db->where('DATE(queue_time)', date('Y-m-d'));
        return $this->db->count_all_results('priority_queue');
    }

    public function count_PeakHoursRegular()
    {
        $this->db->select("DATE_FORMAT(queue_time, '%h:%i %p') as startHour");
        $this->db->select("DATE_FORMAT(queue_time + INTERVAL 1 HOUR, '%h:%i %p') as endHour");
        $this->db->select("COUNT(*) as peakHourCount");
        $this->db->group_by(["startHour", "endHour"]);
        $this->db->order_by("peakHourCount", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('regular_queue');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->startHour . ' - ' . $row->endHour;
        }

        return null;
    }

    public function count_PeakHoursPriority()
    {
        $this->db->select("DATE_FORMAT(queue_time, '%h:%i %p') as startHour");
        $this->db->select("DATE_FORMAT(queue_time + INTERVAL 1 HOUR, '%h:%i %p') as endHour");
        $this->db->select("COUNT(*) as peakHourCount");
        $this->db->group_by(["startHour", "endHour"]);
        $this->db->order_by("peakHourCount", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('priority_queue');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->startHour . ' - ' . $row->endHour;
        }

        return null;
    }

    public function count_MissedQueuesRegular()
    {
        $this->db->where('status', 'no show');
        return $this->db->count_all_results('regular_queue');
    }

    public function count_MissedQueuesPriority()
    {
        $this->db->where('status', 'no show');
        return $this->db->count_all_results('priority_queue');
    }
}
