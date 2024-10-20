<?php
class Cashiers_model extends CI_Model
{

    private $conn;


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('email');
    }

    //CASHIER LOGIN

    public function login($cashier_id, $password)
    {
        // Fetch cashier details based on name and password
        $this->db->where('id', $cashier_id);
        $this->db->where('status', 1); // Ensure cashier is active
        $result = $this->db->get('cashier');

        if ($result->num_rows() === 1) {
            $cashier = $result->row_array();
            // Verify the password
            if (password_verify($password, $cashier['password'])) {
                return $cashier;
            }
        }
        return false;
    }

    public function get_active_cashier()
    {

        $this->db->where('status', 1);
        $query = $this->db->get('cashier');
        return $query->result_array();
    }

    public function get_cashier_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->where('status', 1); // Ensure cashier is active
        $result = $this->db->get('cashier');

        if ($result->num_rows() === 1) {
            return $result->row_array();
        }
        return false;
    }

    public function get_current_queue()
    {
        // Define the SQL query to fetch the next customer from both priority and regular queues
        $sql = "SELECT * FROM (
            SELECT * FROM priority_queue WHERE status = 'pending'
            UNION ALL
            SELECT * FROM regular_queue WHERE status = 'pending'
        ) AS combined_queue
        ORDER BY queue_number ASC
        LIMIT 1";

        // Execute the query
        $query = $this->db->query($sql);

        // Return the result set
        return $query->row_array();
    }

    //CASHIER DASHBOARD

    public function update_queue_complete_status($id, $status)
    {
        $this->db->set('status', $status);

        if ($status === 'served') {
            $this->db->set('end_service_time', 'NOW()', FALSE); // Use MySQL's NOW() function to set the current timestamp
        }

        $this->db->where('id', $id);
        $this->db->update('priority_queue');
        $affected_rows_priority = $this->db->affected_rows();

        if ($affected_rows_priority == 0) {
            $this->db->set('status', $status);
            if ($status === 'served') {
                $this->db->set('end_service_time', 'NOW()', FALSE);
            }
            $this->db->where('id', $id);
            $this->db->update('regular_queue');
            $affected_rows_regular = $this->db->affected_rows();
        } else {
            $affected_rows_regular = 0;
        }

        log_message('debug', 'Queue Id: ' . $id);
        log_message('debug', 'Last query on priority_queue: ' . $this->db->last_query());
        log_message('debug', 'Affected rows in priority_queue: ' . $affected_rows_priority);
        log_message('debug', 'Affected rows in regular_queue: ' . $affected_rows_regular);

        return ($affected_rows_priority > 0 || $affected_rows_regular > 0);
    }

    public function update_queue_no_show_status($id, $status)
    {
        $this->db->set('status', $status);
        if ($status === 'no show') {
            $this->db->set('end_service_time', 'NOW()', FALSE);
        }

        $this->db->where('id', $id);
        $this->db->update('priority_queue');
        $affected_rows_priority = $this->db->affected_rows();

        if ($affected_rows_priority == 0) {
            $this->db->set('status', $status);
            if ($status === 'no show') {
                $this->db->set('end_service_time', 'NOW()', FALSE);
            }
            $this->db->where('id', $id);
            $this->db->update('regular_queue');
            $affected_rows_regular = $this->db->affected_rows();
        } else {
            $affected_rows_regular = 0;
        }

        log_message('debug', 'Queue Id: ' . $id);
        log_message('debug', 'Last query on priority_queue: ' . $this->db->last_query());
        log_message('debug', 'Affected rows in priority_queue: ' . $affected_rows_priority);
        log_message('debug', 'Affected rows in regular_queue: ' . $affected_rows_regular);

        return ($affected_rows_priority > 0 || $affected_rows_regular > 0);
    }

    public function update_queue_status($id, $status)
    {
        $this->db->set('status', $status);

        if ($status === 'served') {
            $this->db->set('end_service_time', 'NOW()', FALSE); // Use MySQL's NOW() function to set the current timestamp
        }

        $this->db->where('id', $id);
        $this->db->update('priority_queue');
        $affected_rows_priority = $this->db->affected_rows();

        if ($affected_rows_priority == 0) {
            $this->db->set('status', $status);
            if ($status === 'served') {
                $this->db->set('end_service_time', 'NOW()', FALSE);
            }
            $this->db->where('id', $id);
            $this->db->update('regular_queue');
            $affected_rows_regular = $this->db->affected_rows();
        } else {
            $affected_rows_regular = 0;
        }

        log_message('debug', 'Queue Id: ' . $id);
        log_message('debug', 'Last query on priority_queue: ' . $this->db->last_query());
        log_message('debug', 'Affected rows in priority_queue: ' . $affected_rows_priority);
        log_message('debug', 'Affected rows in regular_queue: ' . $affected_rows_regular);

        return ($affected_rows_priority > 0 || $affected_rows_regular > 0);
    }

    public function get_current_customer($queue_number, $id)
    {
        $this->db->where('status', 'serving');
        $this->db->where('id', $id);
        $this->db->where('queue_number', $queue_number);
        $this->db->limit(1);
        $query = $this->db->get($queue_number);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function get_transaction_detail($queue_number, $id)
    {
        $this->db->where('id', $id);
        $this->db->limit(1);
        $query = $this->db->get($queue_number);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function get_timedifference($start, $end)
    {
        $startTime = new DateTime($start);
        $endTime = new DateTime($end);
        $interval = $startTime->diff($endTime);

        $hours = $interval->h;
        $minutes = $interval->i;
        return sprintf("%d hours and %d minutes", $hours, $minutes);
    }

    public function update_status_and_time($customerId, $queue_number)
    {
        $this->db->set('status', 'served');
        $this->db->set('end_service_time', 'NOW()', false);
        $this->db->where('id', $customerId);
        $this->db->where('status', 'serving');
        $this->db->limit(1);
        $this->db->update($queue_number);

        return $this->db->affected_rows() > 0;
    }

    public function fetch_and_update_next_customer($cashierId)
    {
        $response = [];

        $sql = "SELECT id, name, student_number, email, payment_for, service_type, payment_mode, queue_number, cashierid, status, queue_time, notify_queue, 'Priority' AS queue_type
        FROM priority_queue
        WHERE status = 'serving' AND cashierid = ?";

        $query = $this->db->query($sql, array($cashierId));
        if ($query->num_rows() > 0) {
            $response = $query->row_array();
            $response['modal'] = true;
        } else {
            $sql = "SELECT id, name, student_number, email, payment_for, service_type, payment_mode, queue_number, cashierid, status, queue_time, notify_queue, 'Regular' AS queue_type
            FROM regular_queue
            WHERE status = 'serving' AND cashierid = ?";

            $query = $this->db->query($sql, array($cashierId));
            if ($query->num_rows() > 0) {
                $response = $query->row_array();
                $response['modal'] = true;
            } else {
                $sql = "SELECT id, email, name, student_number, service_type, payment_for, payment_mode, status, queue_number, 'Regular' AS queue_type
                FROM regular_queue 
                WHERE status = 'pending' AND queue_number LIKE 'R%'
                ORDER BY queue_time ASC 
                LIMIT 1";
                $query = $this->db->query($sql, array($cashierId));

                if ($query->num_rows() > 0) {
                    $row = $query->row_array();

                    $this->db->set('status', 'serving');
                    $this->db->set('notify_queue', 1);
                    $this->db->set('cashierid', $cashierId);
                    $this->db->set('start_service_time', 'NOW()', FALSE);
                    $this->db->where('id', $row['id']);
                    $this->db->update('regular_queue');

                    $response = $row;
                    $response['modal'] = true;
                } else {
                    $response['error'] = 'No pending customers found';
                }
            }
        }
        return $response;
    }

    public function fetch_and_update_next_claiming_customer($cashierId)
    {
        $response = [];

        $sql = "SELECT id, name, student_number, email, payment_for, service_type, payment_mode, queue_number, cashierid, status, queue_time, notify_queue, 'Priority' AS queue_type
        FROM priority_queue
        WHERE status = 'serving' AND cashierid = ?;";

        $query = $this->db->query($sql, array($cashierId));
        if ($query->num_rows() > 0) {
            $response = $query->row_array();
            $response['modal'] = true;
        } else {
            $sql = "SELECT id, name, student_number, email, payment_for, service_type, payment_mode, queue_number, cashierid, status, queue_time, notify_queue, 'Regular' AS queue_type
            FROM regular_queue
            WHERE status = 'serving' AND cashierid = ?;";

            $query = $this->db->query($sql, array($cashierId));
            if ($query->num_rows() > 0) {
                $response = $query->row_array();
                $response['modal'] = true;
            } else {

                $sql = "SELECT id, email, name, student_number, service_type, payment_for, payment_mode, email, queue_number, 'Regular' AS queue_type
                FROM regular_queue 
                WHERE status = 'pending' AND queue_number LIKE 'C%'
                ORDER BY queue_time ASC 
                LIMIT 1";
                $query = $this->db->query($sql, array($cashierId));

                if ($query->num_rows() > 0) {
                    $row = $query->row_array();

                    $this->db->set('status', 'serving');
                    $this->db->set('notify_queue', 1);
                    $this->db->set('cashierid', $cashierId);
                    $this->db->set('start_service_time', 'NOW()', FALSE);
                    $this->db->where('id', $row['id']);
                    $this->db->update('regular_queue');

                    $response = $row;
                } else {
                    $response['error'] = 'No pending customers found';
                }
            }
        }
        return $response;
    }

    public function fetch_and_update_next_priority_customer($cashierId)
    {
        $response = [];

        $sql = "SELECT id, name, student_number, email, payment_for, service_type, payment_mode, queue_number, cashierid, status, queue_time, notify_queue, 'Priority' AS queue_type
        FROM priority_queue
        WHERE status = 'serving' AND cashierid = ?;";

        $query = $this->db->query($sql, array($cashierId));
        if ($query->num_rows() > 0) {
            $response = $query->row_array();
            $response['modal'] = true;
        } else {
            $sql = "SELECT id, name, student_number, email, payment_for, service_type, payment_mode, queue_number, cashierid, status, queue_time, notify_queue, 'Regular' AS queue_type
            FROM regular_queue
            WHERE status = 'serving' AND cashierid = ?;";

            $query = $this->db->query($sql, array($cashierId));
            if ($query->num_rows() > 0) {
                $response = $query->row_array();
                $response['modal'] = true;
            } else {

                $sql = "SELECT id, email, name, student_number, service_type, payment_for, payment_mode, email, queue_number, 'Regular' AS queue_type
                FROM priority_queue 
                WHERE status = 'pending' AND queue_number LIKE 'P%'
                ORDER BY queue_time ASC 
                LIMIT 1";
                $query = $this->db->query($sql, array($cashierId));

                if ($query->num_rows() > 0) {
                    $row = $query->row_array();

                    $this->db->set('status', 'serving');
                    $this->db->set('notify_queue', 1);
                    $this->db->set('cashierid', $cashierId);
                    $this->db->set('start_service_time', 'NOW()', FALSE);
                    $this->db->where('id', $row['id']);
                    $this->db->update('priority_queue');

                    $response = $row;
                } else {
                    $response['error'] = 'No pending customers found';
                }
            }
        }
        return $response;
    }

    public function update_notify_status($id, $show_on_monitor)
    {
        $this->db->set('notify_queue', $show_on_monitor);
        $this->db->where('id', $id);
        $this->db->update('priority_queue');
        $affected_rows_priority = $this->db->affected_rows();

        if ($affected_rows_priority == 0) {
            $this->db->set('notify_queue', $show_on_monitor);
            $this->db->where('id', $id);
            $this->db->update('regular_queue');
            $affected_rows_regular = $this->db->affected_rows();
        } else {
            $affected_rows_regular = 0;
        }

        return ($affected_rows_priority > 0 || $affected_rows_regular > 0);
    }

    public function get_cashier_name($cashierId)
    {
        $this->db->select('name');
        $this->db->from('cashier');
        $this->db->where('id', $cashierId);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->name;
        } else {
            return 'Unknown'; // Default value if not found
        }
    }

    public function fetch_pending_queues()
    {
        $data = array();

        $this->db->select('queue_number');
        $this->db->from('priority_queue');
        $this->db->where('status', 'pending');
        $this->db->order_by('queue_number', 'ASC');
        $priority_query = $this->db->get();
        if ($priority_query->num_rows() > 0) {
            $data = array_merge($data, $priority_query->result_array());
        }

        $this->db->select('queue_number');
        $this->db->from('regular_queue');
        $this->db->where('status', 'pending');
        $this->db->order_by('queue_number', 'ASC');
        $regular_query = $this->db->get();
        if ($regular_query->num_rows() > 0) {
            $data = array_merge($data, $regular_query->result_array());
        }

        return $data;
    }

    public function fetch_notifyqueue($queue_type, $id, $notify_queue)
    {
        $queue_type = $this->db->escape_str($queue_type);

        $this->db->set('notify_queue', $notify_queue);
        $this->db->where('id', $id);
        $this->db->update($queue_type);

        if ($this->db->affected_rows() > 0) {
            return ['success' => 'Queue notification updated successfully.'];
        } else {
            return ['error' => 'No rows were updated.'];
        }
    }

    public function fetch_customer_details($cashierId)
    {
        $cashierId = (int)$cashierId;

        if ($cashierId <= 0) {
            return ['error' => 'Invalid Cashier ID.'];
        }

        $sql = "SELECT q.name, q.student_number, q.service_type, q.payment_for, q.payment_mode, q.email, q.queue_number, q.queue_time, q.status, c.name AS cashier_name
                FROM (SELECT * FROM priority_queue
                      UNION ALL
                      SELECT * FROM regular_queue) AS q
                LEFT JOIN cashier AS c ON q.cashierid = c.id
                WHERE q.cashierid = ?
                ORDER BY q.queue_time ASC";

        $query = $this->db->query($sql, array($cashierId));
        $result = $query->result_array();

        return $result;
    }

    public function fetch_all_customer()
    {
        // Start the query builder for UNION ALL
        $this->db->select('q.name, q.student_number, q.service_type, q.payment_for, q.payment_mode, q.email, q.queue_number, q.queue_time, q.status, c.name AS cashier_name');
        $this->db->from('(SELECT * FROM priority_queue UNION ALL SELECT * FROM regular_queue) AS q');
        $this->db->join('cashier AS c', 'q.cashierid = c.id', 'left');
        $this->db->order_by('q.queue_time', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return [];
    }

    public function get_queue_data($id)
    {
        $this->db->select('id, name, queue_number, student_number, service_type, payment_for, payment_mode, start_service_time, end_service_time');
        $this->db->where('id', $id);
        $query = $this->db->get('priority_queue');
        if ($query->num_rows() > 0) {
            return ['data' => $query->row_array(), 'queue_type' => 'Priority Queue'];
        }

        $this->db->select('id, name, queue_number, student_number, service_type, payment_for, payment_mode, start_service_time, end_service_time');
        $this->db->where('id', $id);
        $query = $this->db->get('regular_queue');
        if ($query->num_rows() > 0) {
            return ['data' => $query->row_array(), 'queue_type' => 'Regular Queue'];
        }

        return null;
    }
}
