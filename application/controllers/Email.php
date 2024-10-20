<?php

class Email extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('Send_email_Model');
    }

    public function transaccomplete(){
        
        $data = array(
            'name' => $this->input->post('name'),
            'queue_number' => $this->input->post('queue_number'),
            'studentNumber' => $this->input->post('studentNumber'),
            'selectedService' => $this->input->post('selectedService'),
            'selectedPaymentFor' => $this->input->post('selectedPaymentFor'),
            'selectedPaymentMode' => $this->input->post('selectedPaymentMode'),
            'queue_time' => $this->input->post('queue_time'),
            'email' => $this->input->post('email')
        );

        $this->Send_email_Model->QueueViaEmail($data);
    }
}

?>