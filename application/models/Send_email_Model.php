<?php

class Send_email_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Manila');
        $this->load->library('email');
    }

    public function send_get_queue_email($insertdata)
    {
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '587',
            'smtp_timeout' => '7',
            'smtp_user' => 'geordansobretodo@gmail.com',
            'smtp_pass' => 'holxpqemmmhhgaud',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'mailtype'  => 'html',
            'validation' => true,
            'smtp_crypto' => 'tls'
        );

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('noreply@gmail.com', 'Automatic Email');
        $this->email->to($insertdata['email']); // gmail of RECIPIENT		
        $this->email->subject('Queue Details: ' . $insertdata['queue_number']); // Set email subject

        $details['name'] = $insertdata['name'];
        $details['queue_number'] = $insertdata['queue_number'];
        $details['studentNumber'] = $insertdata['student_number'];
        $details['selectedService'] = $insertdata['service_type'];
        $details['selectedPaymentFor'] = $insertdata['payment_for'];
        $details['selectedPaymentMode'] = $insertdata['payment_mode'];
        $details['queue_time'] = $insertdata['queue_time'];
        $details['email'] = $insertdata['email'];

        $this->email->message($this->load->view('pages/receipt_email_get_queue', $details, true));

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_id($param)
    {
        $this->db->where('id', $param);
        $id = $this->db->query("SELECT * FROM priority_queue
                                UNION ALL
                                SELECT * FROM regular_queue");
        return $id->row_array();
    }

    //CASHIERS
    public function send_queue_complete_email($data)
    {
        if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $config = array(
                'protocol'  => 'smtp',
                'smtp_host' => 'ssl://smtp.gmail.com',
                'smtp_port' => '465',
                'smtp_timeout' => '7',
                'smtp_user' => 'geordansobretodo@gmail.com',
                'smtp_pass' => 'holxpqemmmhhgaud',
                'charset' => 'utf-8',
                'newline' => "\r\n",
                'mailtype'  => 'html',
                'validation' => true
            );

            $this->email->initialize($config);
            $this->email->from('noreply@gmail.com', 'QUEUEASE');
            $this->email->to($data['email']);
            $this->email->subject('Transaction Complete: ' . $data['queue_number']);

            // Load view and set as email message
            $message = $this->load->view('pages/receipt_email_complete', $data, true);
            $this->email->message($message);

            if ($this->email->send()) {
                return ['status' => 'success', 'message' => 'Email sent.'];
            } else {
                return ['status' => 'error', 'message' => 'Email sending failed.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Invalid email address.'];
        }
    }

    public function send_queue_no_show_email($data)
    {
        if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $config = array(
                'protocol'  => 'smtp',
                'smtp_host' => 'ssl://smtp.gmail.com',
                'smtp_port' => '465',
                'smtp_timeout' => '7',
                'smtp_user' => 'geordansobretodo@gmail.com',
                'smtp_pass' => 'holxpqemmmhhgaud',
                'charset' => 'utf-8',
                'newline' => "\r\n",
                'mailtype'  => 'html',
                'validation' => true
            );

            $this->email->initialize($config);
            $this->email->from('noreply@gmail.com', 'QUEUEASE');
            $this->email->to($data['email']);
            $this->email->subject('Queue Voided: ' . $data['queue_number']);

            // Load view and set as email message
            $message = $this->load->view('pages/receipt_email_no_show', $data, true);
            $this->email->message($message);

            if ($this->email->send()) {
                return ['status' => 'success', 'message' => 'Email sent.'];
            } else {
                return ['status' => 'error', 'message' => 'Email sending failed.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Invalid email address.'];
        }
    }
}
