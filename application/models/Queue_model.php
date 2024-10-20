<?php
class Queue_model extends CI_model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_typecustomer()
    {

        $this->db->where('option_type', 'customer');
        $this->db->where('valid', 1);
        $query = $this->db->get('transaction_options');
        return $query->result_array();
    }

    public function get_typeservice()
    {

        $this->db->where('valid', 1);
        $this->db->where('option_type', 'service');
        $query = $this->db->get('transaction_options');
        return $query->result_array();
    }

    public function get_paymentfor()
    {

        $this->db->where('valid', 1);
        $this->db->where('option_type', 'payment_for');
        $query = $this->db->get('transaction_options');
        return $query->result_array();
    }


    public function get_paymentmode()
    {

        $this->db->where('valid', 1);
        $this->db->where('option_type', 'payment_mode');
        $query = $this->db->get('transaction_options');
        return $query->result_array();
    }

    public function insert_to_queue($data)
    {
        $this->db->trans_start();

        // Sanitize and assign POST data
        $name = $this->db->escape_str($data['name']);
        $studentNumber = $this->db->escape_str($data['studentNumber']);
        $selectedCustomer = $this->db->escape_str($data['selectedCustomer']);
        $selectedService = $this->db->escape_str($data['selectedService']);
        $selectedPaymentFor = $this->db->escape_str($data['selectedPaymentFor']);
        $selectedPaymentMode = $this->db->escape_str($data['selectedPaymentMode']);
        $email = $this->db->escape_str($data['email']);
        $id = $this->db->escape_str($data['id']);

        // Determine the queue table based on customer type
        $queueTable = ($selectedCustomer === 'Priority (Senior, Pregnant, PWD)') ? 'priority_queue' : 'regular_queue';

        // Get the last inserted queue number and timestamp
        $this->db->select('queue_number, queue_time');
        $this->db->from($queueTable);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $lastQueueInfo = $this->db->get()->row_array();

        if ($lastQueueInfo) {
            $lastQueueNumber = $lastQueueInfo['queue_number'];
            $lastQueueTime = $lastQueueInfo['queue_time'];
            $currentDate = date('Y-m-d');

            // Check if the current date is different from the stored date
            $nextNumericPart = ($currentDate > date('Y-m-d', strtotime($lastQueueTime))) ? '000' : sprintf('%03d', (int)filter_var($lastQueueNumber, FILTER_SANITIZE_NUMBER_INT) + 1);
        } else {
            // If no previous entries, start from '000'
            $nextNumericPart = '000';
        }

        // Create the queue number
        $firstLetter = strtoupper(substr($selectedCustomer, 0, 1));
        $queueNumber = $firstLetter . $nextNumericPart;

        // Insert data into the queue table
        $insertData = [
            'name' => $name,
            'student_number' => $studentNumber,
            'service_type' => $selectedService,
            'payment_for' => $selectedPaymentFor,
            'payment_mode' => $selectedPaymentMode,
            'email' => $email,
            'id' => $id,
            'status' => "pending",
            'queue_number' => $queueNumber,
            'queue_time' => date('Y-m-d H:i:s')
        ];
        $this->db->insert($queueTable, $insertData);

        // Calculate average service time
        $this->db->select('AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) / 60 AS average_service_time_minutes');
        $this->db->from($queueTable);
        $this->db->where('status', 'served');
        $this->db->where('DATE(queue_time)', date('Y-m-d'));
        $averageServiceTime = $this->db->get()->row_array();

        if (!$averageServiceTime) {
            $response = [];
            if ($this->db->trans_status()) {
                $response['success'] = true;
                $response['queueNumber'] = $queueNumber;
                $response['average_service_time'] = isset($averageServiceTime['average_service_time_minutes']) ? floatval($averageServiceTime['average_service_time_minutes']) : null;
                $response['name'] = $insertData['name'];
                $response['queue_number'] = $insertData['queue_number'];
                $response['student_number'] = $insertData['student_number'];
                $response['service_type'] = $insertData['service_type'];
                $response['payment_for'] = $insertData['payment_for'];
                $response['payment_mode'] = $insertData['payment_mode'];
                $response['email'] = $insertData['email'];
                $response['id'] = $insertData['id'];
                $response['queue_time'] = $insertData['queue_time'];
            } else {
                $response['success'] = false;
                $response['error'] = 'Failed to insert data into the database';
            }
        } else {
            $current_date = new DateTime();
            $this->db->select('AVG(TIMESTAMPDIFF(SECOND, start_service_time, end_service_time)) / 60 AS average_service_time_minutes');
            $this->db->from($queueTable);
            $this->db->where('status', 'served');
            $this->db->where('DATE(queue_time)', $current_date->sub(new DateInterval("P1D"))->format('Y-m-d'));

            $yesterday_averagetime = $this->db->get()->row_array();

            $response = [];
            if ($this->db->trans_status()) {
                $response['success'] = true;
                $response['queueNumber'] = $queueNumber;
                $response['average_service_time'] = isset($yesterday_averagetime['average_service_time_minutes']) ? floatval($yesterday_averagetime['average_service_time_minutes']) : null;
                $response['name'] = $insertData['name'];
                $response['queue_number'] = $insertData['queue_number'];
                $response['student_number'] = $insertData['student_number'];
                $response['service_type'] = $insertData['service_type'];
                $response['payment_for'] = $insertData['payment_for'];
                $response['payment_mode'] = $insertData['payment_mode'];
                $response['email'] = $insertData['email'];
                $response['id'] = $insertData['id'];
                $response['queue_time'] = $insertData['queue_time'];
            } else {
                $response['success'] = false;
                $response['error'] = 'Failed to insert data into the database';
            }
        }

        $this->db->trans_complete();

        return $response;
    }

    public function get_notify_queue($queue_type, $id, $notify_queue)
    {
        $data = array();

        $s_id = intval($id);

        try {
            if (isset($queue_type) && isset($notify_queue) && isset($s_id) && is_numeric($s_id) && is_numeric($notify_queue)) {
                $this->db->set('notify_queue', $notify_queue);
                $this->db->where('id', $s_id);
                $this->db->update($queue_type);

                if ($this->db->affected_rows() > 0) {
                    $data['success'] = 'Queue notification updated successfully.';
                } else {
                    $data['error'] = 'No rows were updated.';
                }
            } else {
                $data['error'] = 'Invalid input data.';
            }
        } catch (Exception $e) {
            $data['error'] = 'An error occurred while updating data: ' . $e->getMessage();
        }

        return $data;
    }

    public function get_current_serving()
    {
        $this->db->select('id, queue_number, cashierid, start_service_time, notify_queue, "priority_queue" AS queue_type');
        $this->db->from('priority_queue');
        $this->db->where('status', 'serving');
        $this->db->order_by('start_service_time', 'ASC');
        $this->db->limit(7);
        $priority = $this->db->get()->result_array();

        $this->db->select('id, queue_number, cashierid, start_service_time, notify_queue, "regular_queue" AS queue_type');
        $this->db->from('regular_queue');
        $this->db->where('status', 'serving');
        $this->db->order_by('start_service_time', 'ASC');
        $this->db->limit(7);
        $regular = $this->db->get()->result_array();

        // Collect all cashier IDs to fetch names
        $cashierIds = array_merge(
            array_column($priority, 'cashierid'),
            array_column($regular, 'cashierid')
        );
        $cashierIds = array_unique($cashierIds); // Remove duplicates

        // If there are no cashier IDs, return the data as is
        if (empty($cashierIds)) {
            foreach ($priority as &$item) {
                $item['cashier_name'] = 'Unknown';
            }
            foreach ($regular as &$item) {
                $item['cashier_name'] = 'Unknown';
            }
            return [
                'priority' => $priority,
                'regular' => $regular
            ];
        }

        // Fetch cashier names
        $this->db->select('id, name');
        $this->db->from('cashier');
        $this->db->where_in('id', $cashierIds);
        $cashier = $this->db->get()->result_array();

        $cashierNames = array_column($cashier, 'name', 'id');

        // Add cashier names to priority and regular queues
        foreach ($priority as &$item) {
            $item['cashier_name'] = isset($cashierNames[$item['cashierid']]) ? $cashierNames[$item['cashierid']] : 'Unknown';
        }
        foreach ($regular as &$item) {
            $item['cashier_name'] = isset($cashierNames[$item['cashierid']]) ? $cashierNames[$item['cashierid']] : 'Unknown';
        }

        return [
            'priority' => $priority,
            'regular' => $regular
        ];
    }


    public function get_pending_queues()
    {
        $this->db->select('queue_number');
        $this->db->from('priority_queue');
        $this->db->where('status', 'pending');
        $this->db->order_by('queue_time', 'ASC');
        $this->db->limit(7);
        $priority = $this->db->get()->result_array();

        $this->db->select('queue_number');
        $this->db->from('regular_queue');
        $this->db->where('status', 'pending');
        $this->db->order_by('queue_time', 'ASC');
        $this->db->limit(7);
        $regular = $this->db->get()->result_array();

        return [
            'priority' => $priority,
            'regular' => $regular
        ];
    }

    public function fetch_cashier_name($cashierId)
    {
        $data = array();

        if ($cashierId !== NULL) {
            try {
                $this->db->select('name');
                $this->db->from('cashier');
                $this->db->where('id', $cashierId);
                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $data = ['name' => $row['name']];
                } else {
                    $data = ['error' => 'Cashier not found'];
                }
            } catch (Exception $e) {
                $data = ['error' => 'An error occurred while fetching data: ' . $e->getMessage()];
            }
        } else {
            $data = ['error' => 'Missing cashier ID'];
        }

        return $data;
    }
}
