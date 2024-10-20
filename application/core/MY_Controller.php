<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public $template = array();
    public $data = array();
    public $middle = '';

    function __construct()
    {

        parent::__construct();
    }

    public function template($middleParam = '', $data = [])
    {
        //Get status of email verification
        // $this->load->library('user_sessionhandler');

        // $this->user_data = $this->user_sessionhandler->user_session();

        // $modal = $this->get_modal_content($this->user_data);
        // echo '<pre>'.print_r($this->user_data,1).'</pre>';exit;
        // $this->data['instructor_selection'] = $this->InstructorSelection_Model->getInstructorsList($this->user_data);
        // echo '<pre>'.print_r($this->data['instructor_selection'],1).'</pre>';exit;
        // $this->data['admin_data'] = $this->user_sessionhandler->navbar_session();
        // $this->template['title'] = 'SDCALMS';
        $this->template['header'] = $this->load->view('templates/admin_dashboard_header', true);
        // $this->template['navbar'] = $this->load->view('Layout/Navbar.php', $this->data, true);
        // $this->template['sidebar'] = $this->load->view('Layout/Sidebar.php', $this->data, true);
        $this->template['middle'] = $this->load->view($middleParam, $data);
        $this->template['footer'] = ''; //$this->load->view('Layout/Scripts.php', $this->data, true);
        // $this->template['script'] = $this->load->view('Layout/Scripts.php', $this->data, true);
        // $this->template['modal'] = $modal;

        // $this->load->view('Skeleton/main', $this->template);
    }
}
