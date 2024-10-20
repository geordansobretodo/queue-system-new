<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_total_users()
    {
        return $this->db->count_all('userss');
    }

    public function get_users($search, $limit, $offset)
    {
        $this->db->select('*');
        $this->db->from('userss');

        if (!empty($search)) {
            $this->db->like('name', $search);
            $this->db->or_like('username', $search);
            $this->db->or_like('id', $search);
        }

        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_users($search)
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('userss');

        if (!empty($search)) {
            $this->db->like('name', $search);
            $this->db->or_like('username', $search);
        }

        $query = $this->db->get();
        return $query->row()->count;
    }

    public function get_user_by_id($user_id)
    {
        $this->db->where('id', $user_id);
        $query = $this->db->get('userss');
        return $query->row_array();
    }

    public function delete_user($user_id)
    {
        // $this->db->set('valid', 1);
        // $this->db->where('id', $user_id);
        // $this->db->update('userss');

        $this->db->delete('userss', array('id' => $user_id));
        return ($this->db->affected_rows() > 0);
    }

    public function update_user($user_id, $data)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('userss', $data);
    }

    public function add_user($data)
    {
        $this->db->insert('userss', $data);
        return ($this->db->affected_rows() > 0);
    }
}