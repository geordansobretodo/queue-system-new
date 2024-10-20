<?php

class Queue extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Send_email_Model');
        $this->load->model('Queue_model');
        $this->load->library('email');
    }

    public function landing_page($param = null)
    {
        if ($param === null) {

            $page = "index";

            if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                show_404();
            }

            $this->load->view('pages/' . $page);
        }
    }

    public function get_queue()
    {
        $page = "get_queue";

        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }

        $custype = $this->Queue_model->get_typecustomer();
        $sertype = $this->Queue_model->get_typeservice();
        $payfor = $this->Queue_model->get_paymentfor();
        $paymode = $this->Queue_model->get_paymentmode();

        $data['customertype'] = $custype;
        $data['servicetype'] = $sertype;
        $data['paymentfor'] = $payfor;
        $data['paymentmode'] = $paymode;

        $this->load->view('pages/' . $page, $data);
    }

    private function validate_recaptcha($recaptcha_response)
    {
        $recaptcha_secret = "6LcHMQUqAAAAACwUSNVNebTgum-ZbkPE_MFw-p9A"; 
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
        $response_keys = json_decode($response, true);

        return isset($response_keys["success"]) && intval($response_keys["success"]) === 1;
    }

    public function insert_queue()
    {
        // Set header to indicate the response type as JSON
        header('Content-Type: application/json');

        // Get POST data
        $recaptcha_response = $this->input->post('recaptcha_response');
        $name = $this->input->post('name');
        $studentNumber = $this->input->post('studentNumber');
        $selectedCustomer = $this->input->post('selectedCustomer');
        $selectedService = $this->input->post('selectedService');
        $selectedPaymentFor = $this->input->post('selectedPaymentFor');
        $selectedPaymentMode = $this->input->post('selectedPaymentMode');
        $email = $this->input->post('email');
        $id = $this->input->post('id');

        
        

        // Validate reCAPTCHA
        if (!$this->validate_recaptcha($recaptcha_response)) {
            echo json_encode(['success' => false, 'error' => 'reCAPTCHA verification failed.']);
            return;
        } 

        if (intval($studentNumber) < 20020000) {
            $response['success'] = false;
            echo json_encode($response);
            return;
        }

        // Insert queue and get response
        $insertData = [
            'name' => $name,
            'id' => $id,
            'studentNumber' => $studentNumber,
            'selectedCustomer' => $selectedCustomer,
            'selectedService' => $selectedService,
            'selectedPaymentFor' => $selectedPaymentFor,
            'selectedPaymentMode' => $selectedPaymentMode,
            'queue_time' => date('Y-m-d h:i'),
            'email' => $email
        ];
        $response = $this->Queue_model->insert_to_queue($insertData);

        // Encode and return the response as JSON
        echo json_encode($response);

        $this->Send_email_Model->get_id($response['id']);
        $this->Send_email_Model->send_get_queue_email($response);
    }

    public function monitor()
    {
        $page = "monitor";

        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }

        $this->load->view('pages/' . $page);
    }

    public function fetch_notify_queue()
    {
        // Set header to indicate the response type as JSON
        header('Content-Type: application/json');

        // Fetch and sanitize input data
        $queue_type = $this->input->post('queue_type', TRUE);
        $notify_queue = $this->input->post('notify_queue', TRUE);
        $id = $this->input->post('id', TRUE);

        // Use the model to update the notify queue
        $data = $this->Queue_model->get_notify_queue($queue_type, $id, $notify_queue);

        // Encode and return the data as JSON
        echo json_encode($data);
    }

    public function fetch_current_serving()
    {
        // Set header to indicate the response type as JSON
        header('Content-Type: application/json');

        $cashier_id = $this->input->post('cashier_id');

        try {
            // Use the model to get pending queues
            $data = $this->Queue_model->get_current_serving();

            // Encode and return the data as JSON
            echo json_encode($data);
        } catch (Exception $e) {
            // In case of an error, return a JSON object with an error message
            echo json_encode(['error' => 'An error occurred while fetching data: ' . $e->getMessage()]);
        }
    }

    public function fetch_pending_queue()
    {
        // Set header to indicate the response type as JSON
        header('Content-Type: application/json');

        try {
            // Use the model to get pending queues
            $data = $this->Queue_model->get_pending_queues();

            // Encode and return the data as JSON
            echo json_encode($data);
        } catch (Exception $e) {
            // In case of an error, return a JSON object with an error message
            echo json_encode(['error' => 'An error occurred while fetching data: ' . $e->getMessage()]);
        }
    }

    public function fetch_cashier_name()
    {
        // Set header to indicate the response type as JSON
        header('Content-Type: application/json');

        // Get the cashier ID from the query parameters
        $cashierId = $this->input->get('cashier_id', TRUE);

        // Use the model to get the cashier's name
        $data = $this->Queue_model->fetch_cashier_name($cashierId);

        // Encode and return the data as JSON
        echo json_encode($data);
    }
}
