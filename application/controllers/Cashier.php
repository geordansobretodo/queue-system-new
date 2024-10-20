<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Cashier extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation', 'session');
        $this->load->model('Cashiers_model');
        $this->load->model('Send_email_Model');
    }

    //CASHIER LOGIN

    public function dashboard()
    {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/cashier');
        }

        // Get the logged-in cashier's ID
        $cashier_id = $this->session->userdata('id');

        // Fetch cashier details and current queue
        $data['cashier'] = $this->Cashiers_model->get_cashier_by_id($cashier_id);
        $data['cashier_name'] = $this->session->userdata('name') ? $this->session->userdata('name') : 'Guest';
        $data['current_queue'] = $this->Cashiers_model->get_current_queue(); // Fetch current queue


        $page = "cashier_dashboard";

        // Check if the view file exists
        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }

        $this->load->view('pages/cashier_dashboard', $data);
    }

    public function get_cashier_name()
    {
        // Fetch the cashier name from session
        $data['cashier_name'] = $this->session->userdata('name') ? $this->session->userdata('name') : 'Guest';

        $this->load->view('pages/cashier_dashboard', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('log');
        $this->session->sess_destroy();

        redirect('Login/cashier');
    }

    //CASHIER DASHBOARD

    public function fetch_and_update_next_pending_customer()
    {
        $cashierId = $this->input->post('cashier_id'); // Fetch cashier ID from POST request

        if ($cashierId) {
            $this->load->model('Cashiers_model');
            $response = $this->Cashiers_model->fetch_and_update_next_pending_customer($cashierId);

            if (isset($response['error'])) {
                echo json_encode(['status' => 'error', 'message' => $response['error']]);
            } else {
                echo json_encode(['status' => 'success', 'data' => $response]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cashier ID is missing']);
        }
    }

    public function fetch_details()
    {
        // Fetch the cashier ID from the POST request
        $cashierId = $this->input->post('cashier_id');

        log_message('debug', 'Cashier ID received: ' . $cashierId); // Log the cashier ID received

        if ($cashierId) {
            $this->load->model('Cashiers_model');
                $response = $this->Cashiers_model->fetch_and_update_next_customer($cashierId);

                if (isset($response['error'])) {
                    // Log error message
                    log_message('debug', 'Error fetching customer details: ' . $response['error']);
                    echo json_encode(['status' => 'error', 'message' => $response['error']]);
                } else {
                    // Log successful response
                    log_message('debug', 'Customer details fetched successfully');
                    echo json_encode(['status' => 'success', 'data' => $response]);
                }
            // }
        } else {
            // Log missing cashier ID error
            log_message('debug', 'Cashier ID is missing');
            echo json_encode(['status' => 'error', 'message' => 'Cashier ID is missing']);
        }
    }

    public function fetch_details_claiming()
    {
        // Fetch the cashier ID from the POST request
        $cashierId = $this->input->post('cashier_id');

        log_message('debug', 'Cashier ID received: ' . $cashierId); // Log the cashier ID received

        if ($cashierId) {
            $this->load->model('Cashiers_model');
                $response = $this->Cashiers_model->fetch_and_update_next_claiming_customer($cashierId);

                if (isset($response['error'])) {
                    // Log error message
                    log_message('debug', 'Error fetching customer details: ' . $response['error']);
                    echo json_encode(['status' => 'error', 'message' => $response['error']]);
                } else {
                    // Log successful response
                    log_message('debug', 'Customer details fetched successfully');
                    echo json_encode(['status' => 'success', 'data' => $response]);
                }
            // }
        } else {
            // Log missing cashier ID error
            log_message('debug', 'Cashier ID is missing');
            echo json_encode(['status' => 'error', 'message' => 'Cashier ID is missing']);
        }
    }

    public function fetch_details_priority()
    {
        // Fetch the cashier ID from the POST request
        $cashierId = $this->input->post('cashier_id');

        log_message('debug', 'Cashier ID received: ' . $cashierId); // Log the cashier ID received

        if ($cashierId) {
            $this->load->model('Cashiers_model');
                $response = $this->Cashiers_model->fetch_and_update_next_priority_customer($cashierId);

                if (isset($response['error'])) {
                    // Log error message
                    log_message('debug', 'Error fetching customer details: ' . $response['error']);
                    echo json_encode(['status' => 'error', 'message' => $response['error']]);
                } else {
                    // Log successful response
                    log_message('debug', 'Customer details fetched successfully');
                    echo json_encode(['status' => 'success', 'data' => $response]);
                }
            // }
        } else {
            // Log missing cashier ID error
            log_message('debug', 'Cashier ID is missing');
            echo json_encode(['status' => 'error', 'message' => 'Cashier ID is missing']);
        }
    }

    public function update_status_complete()
    {
        $id = $this->input->post('id');
        $email = $this->input->post('email');

        $response = array();

        if ($id) {
            $result = $this->Cashiers_model->update_queue_complete_status($id, 'served');
            if ($result) {
                $queue_data = $this->Cashiers_model->get_queue_data($id);
                if ($queue_data) {
                    $data = $queue_data['data'];
                    $data['queue_type'] = $queue_data['queue_type'];
                    $data['email'] = $email;

                    $email_result = $this->Send_email_Model->send_queue_complete_email($data);

                    if ($email_result['status'] === 'success') {
                        $response['status'] = 'success';
                        $response['message'] = 'Queue status updated and email sent.';
                    } else {
                        $response['status'] = 'success';
                        $response['message'] = 'Queue status updated, but ' . $email_result['message'];
                    }
                } else {
                    $response['message'] = 'No data found for the provided queue number.';
                }
            } else {
                $response['message'] = 'Failed to update status.';
            }

            ob_end_clean();
            echo json_encode($response);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'JSON encoding error: ' . json_last_error_msg());
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid queue number.']);
        }
    }

    public function update_status_no_show()
    {
        $id = $this->input->post('id');
        $email = $this->input->post('email');

        $response = array();

        if ($id) {
            $result = $this->Cashiers_model->update_queue_no_show_status($id, 'no show');
            if ($result) {
                $queue_data = $this->Cashiers_model->get_queue_data($id);
                if ($queue_data) {
                    $data = $queue_data['data'];
                    $data['queue_type'] = $queue_data['queue_type'];
                    $data['email'] = $email;

                    $email_result = $this->Send_email_Model->send_queue_no_show_email($data);

                    if ($email_result['status'] === 'success') {
                        $response['status'] = 'success';
                        $response['message'] = 'Queue status updated and email sent.';
                    } else {
                        $response['status'] = 'success';
                        $response['message'] = 'Queue status updated, but ' . $email_result['message'];
                    }
                } else {
                    $response['message'] = 'No data found for the provided queue number.';
                }
            } else {
                $response['message'] = 'Failed to update status.';
            }

            ob_end_clean();
            echo json_encode($response);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'JSON encoding error: ' . json_last_error_msg());
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid queue number.']);
        }
    }

    public function update_queue_status()
    {
        $id = $this->input->post('id');
        if (!$this->input->post('trigger_update_served') || !$id) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request: Missing parameters.']);
            return;
        }

        $id = intval($id);
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid cashier ID.']);
            return;
        }
    }

    public function update_notify_status()
    {
        $queue_number = $this->input->post('queue_number'); // Get the queue number from the POST data
        $show_on_monitor = $this->input->post('show_on_monitor');

        // Log the received queue number for debugging
        log_message('debug', 'Received Queue Number: ' . $queue_number);

        if (is_null($show_on_monitor)) {
            echo json_encode(['status' => 'success', 'message' => 'Show on monitor value is missing.']);
            return;
        }

        // Your logic to update the notify status (or other database operations)
        $this->load->model('Cashiers_model');

        // Check if queue data exists
        $queue_data = $this->Cashiers_model->get_queue_data($queue_number);

        if ($queue_data) {
            // Your logic to update the notify status (or other database operations)
            $update_result = $this->Cashiers_model->update_notify_status($queue_number, $show_on_monitor);

            // Check if the operation was successful
            if (!$update_result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'noop']);
            }
        } else {
            // If no data is found for the given queue number
            echo json_encode(['status' => 'no_data', 'message' => 'No data found for the given queue number.']);
        }
    }

    public function show_on_monitor()
    {
        // Load necessary models if needed
        $this->load->model('Cashiers_model'); // Assuming you're using a model to handle queue data

        // Get the queue number from the AJAX POST request
        $queue_number = $this->input->post('queue_number');
        $cashier_name = $this->input->post('cashier_name');
        $cashier_id = $this->input->post('cashier_id');

        if ($queue_number) {
            // Fetch queue details or update the monitor status
            // For example, retrieve customer details associated with the queue number
            $queue_data = $this->Cashiers_model->get_queue_data($queue_number);

            if ($queue_data) {
                // You could send the data to a view that updates the monitor
                // Or return a JSON response with the data to display on the monitor
                $response = [
                    'status' => 'success',
                    'queue_data' => $queue_data['data'],
                    'queue_type' => $queue_data['queue_type'],
                    'cashier_name' => $cashier_name,
                    'cashier_id' => $cashier_id,
                    'message' => 'Queue data successfully fetched for monitor display.'
                ];
                echo json_encode($response);
            } else {
                // If no queue data is found
                $response = [
                    'status' => 'error',
                    'message' => 'No data found for the given queue number.'
                ];
                echo json_encode($response);
            }
        } else {
            // Handle cases where queue_number was not sent in the request
            $response = [
                'status' => 'error',
                'message' => 'Queue number is missing.'
            ];
            echo json_encode($response);
        }
    }
}
