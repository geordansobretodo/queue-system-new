<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        // add other library here if you need it
        // $this->load->library()

        $this->load->model('Admin_model');
        $this->load->model('Users_model');
        $this->load->model('Cashier_list_model');
        $this->load->model('Transaction_list_model');
        $this->load->model('Daily_reports_model');
        $this->load->model('Customer_details_model');
        $this->load->model('Cashier_metrics_model');

        $this->load->library('set_views');
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['admin'] = $this->Admin_model->get_active_admin();
            $this->load->view('pages/login', $data);
        } else {
            $admin_id = $this->input->post('username');
            $password = $this->input->post('password');

            $admin = $this->Admin_model->login($admin_id, $password);

            if ($admin) {
                $user_data = array(
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                );

                $this->session->set_userdata($user_data);
                $this->session->set_userdata('log', 'logged');
                redirect('Admin/home');
            } else {
                $this->session->set_flashdata('login_error', 'Invalid name or password');
                redirect('Login/admin');
            }
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('log');
        $this->session->sess_destroy();

        redirect('Login/admin');
    }


    public function home()
    {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/admin');
        }

        $priority_served = $this->Admin_model->count_ServedPriority();
        $regular_served = $this->Admin_model->count_ServedRegular();

        $priority_pending = $this->Admin_model->count_PendingPriority();
        $regular_pending = $this->Admin_model->count_PendingRegular();

        $priority_noShow = $this->Admin_model->count_NoShowPriority();
        $regular_noShow = $this->Admin_model->count_NoShowRegular();

        $priority_missedQueues = $this->Admin_model->count_MissedQueuesPriority();
        $regular_missedQueues = $this->Admin_model->count_MissedQueuesRegular();

        $missedQueues = $priority_missedQueues + $regular_missedQueues;

        $data['totalServed'] = $priority_served + $regular_served;
        $data['totalNoShow'] = $priority_noShow + $regular_noShow;
        $data['totalPending'] = $regular_pending + $priority_pending;
        $data['missedQueues'] = $missedQueues;
        $data['peakHoursRegular'] = $this->Admin_model->count_PeakHoursRegular();
        $data['peakHoursPriority'] = $this->Admin_model->count_PeakHoursPriority();

        $this->template($this->set_views->admin_dashboard(),$data);
    }

    public function users()
    {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/admin');
        }

        $this->template($this->set_views->user());
    }

    public function fetch_user_data()
    {
        $search = $this->input->get('search');
        $page = $this->input->get('page');
        $entriesPerPage = 5;
        $offset = ($page - 1) * $entriesPerPage;

        $data['users'] = $this->Users_model->get_users($search, $entriesPerPage, $offset);
        $data['total_rows'] = $this->Users_model->count_users($search);
        $data['total_pages'] = ceil($data['total_rows'] / $entriesPerPage);

        echo json_encode($data);
    }

    public function delete($user_id)
    {
        if ($this->Users_model->delete_user($user_id)) {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('Admin/users');
    }

    public function update_user()
    {
        $user_id = $this->input->post('editUserId');
        $updateData = array(
            'username' => $this->input->post('editUsername')
        );

        if (!empty($this->input->post('editPassword'))) {
            $updateData['password'] = password_hash($this->input->post('editPassword'), PASSWORD_DEFAULT);
        }

        $result = $this->Users_model->update_user($user_id, $updateData);
        if ($result) {
            $this->session->set_flashdata('success', 'User data updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update user data.');
        }
        redirect('Admin/users');
    }

    public function add_user()
    {
        $newUserData = array(
            'name' => $this->input->post('newName'),
            'username' => $this->input->post('newUsername'),
            'password' => password_hash($this->input->post('newPassword'), PASSWORD_DEFAULT),
        );
        
        if (!empty($newUserData['name'] && $newUserData['username'] && $newUserData['password'])) {

            if ($this->Users_model->add_user($newUserData)) {
                $this->session->set_flashdata('success', 'User data added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add user data.');
            }
            redirect('Admin/users');
        }

        $this->session->set_flashdata('error', 'Please fill out all fields.');
        redirect('Admin/users');
    }

    public function cashiers()
    {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/admin');
        }
        $this->template($this->set_views->cashier_list());
    }

    public function add_cashier()
    {
        $data = array(
            'name' => $this->input->post('newName'),
            'password' => password_hash($this->input->post('newPassword'), PASSWORD_BCRYPT),
            'status' => 1
        );

        if (!empty($data['name'] && $data['password'] && ['status'])) {

            if ($this->Cashier_list_model->add_cashier($data)) {
                $this->session->set_flashdata('success', 'Cashier data added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add cashier data');
            }
            redirect('Admin/cashiers');
        }
        $this->session->set_flashdata('error', 'Please fill out all fields.');
        redirect('Admin/cashiers');
    }

    public function update_cashier()
    {
        $id = $this->input->post('editCashierId');
        $data = array(
            'name' => $this->input->post('editName'),
            'status' => $this->input->post('editStatus')
        );

        if (!empty($this->input->post('editPassword'))) {
            $data['password'] = password_hash($this->input->post('editPassword'), PASSWORD_BCRYPT);
        }

        $result = $this->Cashier_list_model->update_cashier($id, $data);
        if ($result) {
            $this->session->set_flashdata('success', 'Cashier data updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update cashier data.');
        }
        redirect('Admin/cashiers');
    }

    public function fetch_cashier_data()
    {
        $search = $this->input->get('search');
        $page = $this->input->get('page');
        $entriesPerPage = 5;
        $offset = ($page - 1) * $entriesPerPage;

        $data['cashiers'] = $this->Cashier_list_model->get_cashiers($search, $entriesPerPage, $offset);
        $data['total_rows'] = $this->Cashier_list_model->count_cashiers($search);
        $data['total_pages'] = ceil($data['total_rows'] / $entriesPerPage);

        echo json_encode($data);
    }

    public function transaction_management()
    {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/admin');
        }
        $this->template($this->set_views->transaction_list());
    }

    public function add_option()
    {
        $option_type = $this->input->post('option_type');
        $option_name = $this->input->post('option_name');

        if ($option_name && $option_type) {
            $result = $this->Transaction_list_model->add_option($option_name, $option_type);

            if ($result) {
                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    }

    public function get_options($type)
    {
        $options = $this->Transaction_list_model->get_options($type);
        echo json_encode($options);
    }

    public function delete_option()
    {
        $id = $this->input->post('id');

        if ($id) {
            $result = $this->Transaction_list_model->delete_option($id);
            echo $result ? 'success' : 'error';
        } else {
            echo 'error';
        }
    }

    public function daily_reports()
    {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/admin');
        }

        $selectedDate = $this->input->post('selectedDate');

        $data['totalServed'] = $this->Daily_reports_model->getTotalServed($selectedDate);
        $data['totalPending'] = $this->Daily_reports_model->getTotalPending($selectedDate);
        $data['peakHour'] = $this->Daily_reports_model->getPeakHour($selectedDate);
        $data['missedQueues'] = $this->Daily_reports_model->getMissedQueues($selectedDate);

        $regularTime = $this->Daily_reports_model->getRegularAverageServiceTime($selectedDate);
        $data['averageServiceTimeRegular'] = $regularTime;

        $priorityTime = $this->Daily_reports_model->getPriorityAverageServiceTime($selectedDate);
        $data['averageServiceTimePriority'] = $priorityTime;

        $data['selectedDate'] = $selectedDate;

        $this->template($this->set_views->daily_reports(), $data);
    }

    public function customer_details()
    {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/admin');
        }
        
        $this->template($this->set_views->customer_details());
    }

    public function filter_data()
    {
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');  

        $data = $this->Customer_details_model->getFilteredData($startDate, $endDate);

        echo json_encode($data);
    }

    function cashier_metrics() {
        if ($this->session->userdata('log') != 'logged') {
            redirect('Login/admin');
        }

        $this->template($this->set_views->cashier_metrics());
    }

    public function filter_cashier_data() {
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');   
    
        $customerData = $this->Cashier_metrics_model->getFilteredData($startDate, $endDate);
        $cashierData = $this->Cashier_metrics_model->cashierMetrics($startDate, $endDate);
    
        if (isset($cashierData['total_transactions']) && 
            isset($cashierData['total_completed']) && 
            isset($cashierData['total_noshows'])) {
            
            $data['customerData'] = $customerData;
            $data['cashierData']['total_transactions'] = $cashierData['total_transactions'];
            $data['cashierData']['total_completed'] = $cashierData['total_completed'];
            $data['cashierData']['total_noshows'] = $cashierData['total_noshows'];
            $data['cashierData']['average_service_time'] = $cashierData['average_service_time'];
            $data['cashierData']['date_range'] = $cashierData['date_range'];
        } else {
            $data['customerData'] = $customerData;
            $data['cashierData']['total_transactions'] = 0;
            $data['cashierData']['total_completed'] = 0;
            $data['cashierData']['total_noshows'] = 0;
            $data['cashierData']['average_service_time'] = '0 hours, 0 minutes, 0 seconds';
            $data['cashierData']['date_range'] = 'DD/MM/YYYY';
        }
        
        header('Content-type: application/json');
        echo json_encode($data);
    }
    
}
