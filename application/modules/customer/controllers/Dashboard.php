<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CMS_Controller {

    function __construct() {

        parent::__construct();
    }

    function index() {

        $this->load->model('Customermodel');

        $customer = array();



        //Render View

        $inner = array();

        $shell = array();

        $inner['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $inner['INFO'];

        $inner['userDetails'] = $this->Customermodel->getUserInformation();

        $inner['shippingAddress'] = $this->Customermodel->getAddressByType('s');

        $inner['BilllingAddress'] = $this->Customermodel->getAddressByType('b');

        $shell['contents'] = $this->load->view('dashboard/dashboard', $inner, true);

        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
    }

    function change_pass() {

        $this->load->library('form_validation');

        $this->load->model('Customermodel');

        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        $this->form_validation->set_rules('passconf', 'Password', 'trim|required|matches[password]');



        if ($this->form_validation->run() == FALSE) {

            $inner = array();

            $inner['customer'] = $customer = $this->Customermodel->userByID(curUsrId());

            $shell['contents'] = $this->load->view('change_pass', $inner, true);

            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {

            $this->Customermodel->changePassword();

            $this->session->set_flashdata('SUCCESS', 'pass_changed');

            redirect('customer/dashboard');
        }
    }

}
?>

