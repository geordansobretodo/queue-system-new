<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cashier_list_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_cashiers($search, $limit, $offset)
    {
        $this->db->select('*');
        $this->db->from('cashier');

        if (!empty($search)) {
            $this->db->like('name', $search);
            $this->db->or_like('status', $search);
            $this->db->or_like('id', $search);
        }
        
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_cashiers($search)
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('cashier');

        if (!empty($search)) {
            $this->db->like('name', $search);
        }

        $query = $this->db->get();
        return $query->row()->count;
    }

    public function get_cashier_by_id($id)
    {
        $query = $this->db->get_where('cashier', array('id' => $id));
        return $query->row_array();
    }

    public function update_cashier($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('cashier', $data);
    }

    public function add_cashier($data)
    {
        return $this->db->insert('cashier', $data);
    }
}
