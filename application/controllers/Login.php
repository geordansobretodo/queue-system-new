<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('Cashiers_model');
    }

    public function admin()
    {
        $this->load->view('pages/admin_login');
    }

    public function cashier()
    {
        $this->form_validation->set_rules('cashier_id', 'Cashier ID', 'required|integer');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['cashier'] = $this->Cashiers_model->get_active_cashier();
            $this->load->view('pages/cashier_login', $data);
        } else {
            $cashier_id = $this->input->post('cashier_id');
            $password = $this->input->post('password');

            $cashier = $this->Cashiers_model->login($cashier_id, $password);

            if ($cashier) {
                $user_data = array(
                    'id' => $cashier['id'],
                    'name' => $cashier['name'],
                    'status' => $cashier['status'],
                );

                $this->session->set_userdata($user_data);
                $this->session->set_userdata('log', 'logged');
                redirect('Cashier/dashboard');
            } else {
                $this->session->set_flashdata('login_error', 'Invalid name or password');
                redirect('Login/cashier');
            }
        }
    }
}
?>