<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Special_price extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Commonusermodel');
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->load->model('Specialpricemodel');
        $this->data = [];
    }

    function date_valid($date) {
        $day = (int) substr($date, 0, 2);
        $month = (int) substr($date, 3, 2);
        $year = (int) substr($date, 6, 4);
        if (checkdate($month, $day, $year)) {
            return true;
        } else {
            $this->form_validation->set_message('date_valid', 'Please Enter a Valid Date.');
            return FALSE;
        }
    }

    function compare_date() {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        if (strtotime($to_date) <= strtotime($from_date)) {
            $this->form_validation->set_message('compare_date', '"Date To" should be greater than "Date From".');
            return FALSE;
        } else {
            return true;
        }
    }

    function index() {
        
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Special_price.</p>'); 
            redirect('dashboard');

        $this->data['table_labels'] = array(
            'Sr.no',
            'Product Name',
            'Product Sku',
            'Date From',
            'Date To',
            'Price',
            ''
        );
        
        $product = $this->input->get('product');
        $status = $this->input->get('status');
        $perpage = 10;
        
        $config['base_url'] = base_url() . "special_price/index?product=" . $product . "&status=" . $status;
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Specialpricemodel->countAll();
        $config['per_page'] = $perpage;
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $status = array('1' => "Active", '0' => 'De-active');
        $this->data['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $this->data['INFO'];
        $offset = $this->input->get('per_page');
        $specialPriceList = $this->Specialpricemodel->listAll($offset, $perpage);

        $this->data['status'] = $status;
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['specialPriceList'] = $specialPriceList;
        //echo $this->template['default'];
        $this->data['content'] = $this->load->view('special-price-list', $this->data, true);
        $this->load->view($this->template['default'], $this->data);
    }

    function add() {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Special_price.</p>'); 
            redirect('dashboard');

        $this->form_validation->set_rules('product', 'Product', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required|decimal');
        $this->form_validation->set_rules('from_date', 'From Date', 'trim|required|callback_date_valid');
        $this->form_validation->set_rules('to_date', 'To Date', 'trim|required|callback_date_valid|callback_compare_date');

        $status = array( '1' => "Active", '0' => 'De-active');

        $this->data['products'] = $this->Specialpricemodel->getProducts();
        if ($this->form_validation->run() == FALSE) {
            $this->data['detail'] = $this->input->post();
            $this->data['status'] = $status;
            $this->data['content'] = $this->load->view('special-price-add', $this->data, true);
            $this->load->view($this->template['default'], $this->data);
        } else {
            if ($this->Specialpricemodel->getActiveProductSpecialPrice($this->input->post('product'))) {
                $this->session->flashdata('message', 'Special price exists for this Product');
                redirect(base_url('special_price'));
            } else {
                $this->session->flashdata('message', 'Special price added Successfully');
                $this->Specialpricemodel->InsertRecord();
                redirect(base_url('special_price'));
            }
        }
    }

    function edit($id) {
        
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Special_price.</p>'); 
            redirect('dashboard');

        $this->form_validation->set_rules('product', 'Product', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required|decimal');
        $this->form_validation->set_rules('from_date', 'From Date', 'trim|required|callback_date_valid');
        $this->form_validation->set_rules('to_date', 'To Date', 'trim|required|callback_date_valid|callback_compare_date');
        $detail = $this->Specialpricemodel->getDetails($id);
        if (!$detail) {
            $this->utility->show404();
            return false;
        }
        $status = array( '1' => "Active", '0' => 'De-active');
        $this->data['products'] = $this->Specialpricemodel->getProducts();
        if ($this->form_validation->run() == FALSE) {
            $this->data['detail'] = $detail;
            $this->data['status'] = $status;
            $this->data['content'] = $this->load->view('special-price-add', $this->data, true);
            $this->load->view($this->template['default'], $this->data);
        } else {
            $this->Specialpricemodel->UpdateRecord($id);
            $this->session->flashdata('message', 'Special price edited Successfully');
            redirect(base_url('special_price'));
        }
    }

    function delete($id) {
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Special_price.</p>'); 
            redirect('dashboard');

        $detail = $this->Specialpricemodel->getDetails($id);
        if (!$detail) {
            $this->utility->show404();
            return false;
        }
        $this->Specialpricemodel->DeleteRecord($id);
        $this->session->flashdata('message', 'Special price deleted Successfully');
        redirect(base_url('special_price'));
    }

}
