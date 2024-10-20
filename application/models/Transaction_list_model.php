<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_list_model extends CI_Model 
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_option($option_name, $option_type) 
    {
        $data = array(
            'option_name' => $this->db->escape_str($option_name),
            'option_type' => $option_type,
            'valid' => 1
        );
        return $this->db->insert('transaction_options', $data);
    }

    public function get_options($option_type) 
    {
        $this->db->where('option_type', $option_type);
        $query = $this->db->get('transaction_options');
        return $query->result_array();
    }

    public function delete_option($id) 
    {
        $this->db->where('id', $id);
        return $this->db->delete('transaction_options');
    }
}
