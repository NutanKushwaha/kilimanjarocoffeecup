<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->data = [];
        $this->data['user_type'] = $this->user_type;
    }

    function index() {
        $this->data['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $this->data['INFO'];
        $this->load->model('reports/Reportsmodel');
        $Userdata = $this->flexi_auth->get_user_custom_data();
  
        if ($Userdata['uacc_group_fk'] == 3) {
            $this->data['yealychartdata'] = $this->Reportsmodel->getYearlySalesReport();
            $this->data['orderdata'] = $this->Reportsmodel->getAllOrders();
        }

        if ($Userdata['uacc_group_fk'] == 7) {
            $this->data['orderdata'] = $this->Reportsmodel->getUserAllOrders($Userdata['uacc_id']);
        }

        if ($Userdata['uacc_group_fk'] == 2) {
//            $this->data['barchartdata'] = $this->Reportsmodel->getCompanyDashboardBarchart($Userdata['upro_profession'], $duration = NULL);
            $this->data['orderdata'] = $this->Reportsmodel->getComapanyAllOrders($Userdata['uacc_id']);
        }

        if ($Userdata['uacc_group_fk'] == 6) {
            $this->data['orderdata'] = $this->Reportsmodel->getComapanyAllOrders($Userdata['upro_approval_acc']);
//            $this->data['piechartdata'] = $this->Reportsmodel->getCompanyDashboardPiechart($Userdata['uacc_id']);
        }

        $this->data['content'] = $this->load->view(THEME . 'welcome/dashboard', $this->data, TRUE);
        $this->load->view(THEME . 'templates/default-with-menu-home', $this->data);
    }

    function profile() {
        if (!$this->flexi_auth->is_privileged('View Profile')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Profile.</p>');
            redirect('dashboard');
        }
        $this->load->model('Commonusermodel');
        $this->Commonusermodel->get_comp_user_profile($this->flexi_auth->get_user_id());
        if ($this->user_type == USER) {
            $users_assigned_dept = json_decode($this->data['user_profile']->upro_department);
            if (!is_array($users_assigned_dept) or is_null($users_assigned_dept or empty($users_assigned_dept))) {
                $users_assigned_dept = [''];
            }
            $this->data['user_dept'] = $this->Commonusermodel->getUserAssignedDept($users_assigned_dept);
        }
        $this->data['user_company'] = '';
        if ($this->data['user_profile']->upro_company && !empty($this->data['user_profile']->upro_company)) {
            $opt = [];
            $opt['result'] = 'row';
            $opt['select'] = 'name';
            $opt['from_tbl'] = 'company';
            $opt['where']['company_code'] = $this->data['user_profile']->upro_company;
            $comp = $this->Commonusermodel->get_all($opt);
            $this->data['user_company'] = $comp['name'];
        }
        $this->data['user_approval_title'] = '';
        $this->data['user_approval_name'] = '';
        if (!empty($this->data['user_profile']->upro_approval_acc) && $this->data['user_profile']->upro_approval_acc) {


            $this->data['user_approval_title'] = $this->data['user_profile']->ugrp_name == USER ? 'Register User' : 'Council';
            $opt = [];
            $opt['result'] = 'row';
            $opt['select'] = 'upro_first_name, upro_last_name';
            $opt['from_tbl'] = 'user_profiles';
            $opt['where']['upro_uacc_fk'] = $this->data['user_profile']->upro_approval_acc;
            $approver_name = $this->Commonusermodel->get_all($opt);

            if (!empty($approver_name)) {
                $this->data['user_approval_name'] = ucfirst($approver_name['upro_first_name']) . ' ' . $approver_name['upro_last_name'];
            }
        }
        $this->data['profile'] = true;
        $this->data['content'] = $this->load->view('user/users/user-profile', $this->data, TRUE);
        $this->load->view($this->template['default'], $this->data);
    }

}
