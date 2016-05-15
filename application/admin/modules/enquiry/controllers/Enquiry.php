<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Enquiry extends Admin_Controller {
    
    private $enq_status;
    function __construct() {
        parent::__construct();
        $this->load->model('Enquirymodel');
        $this->load->model('user/usermodel');
        $this->load->helper('date');
        
        $this->load->library('email');
        $this->load->library('parser');
        
        $this->load->helper('text');
        $this->enq_status = array(
                                    '0' => 'today',
                                    '1' => 'all',
                                    '2' => 'closed'
                                );
    }

    function checkStatus($id = null) {
        $status = array(1 => 'enquiry',
            2 => 'process',
            3 => 'pending',
            4 => 'forward',
            5 => 'finish');
        $ret_status = $status[1];
        if (isset($status[$id])) {
            $ret_status = $status[$id];
        }
        return $ret_status;
    }

    function index($param = null) {


        $this->load->model('cms/Pagemodel');
        $this->load->model('cms/Widgetmodel');
        $this->load->model('news/Newsmodel');

        $news = array();
        $news = $this->Newsmodel->listAll(0, 2);
        //Get Page Details
        $page = array();
        $breadcrumbs = array();


        //validation check
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email_addr', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('tel_number', 'Telephone', 'trim|required');
        $this->form_validation->set_rules('post_code', 'Postcode', 'trim|required');
        $this->form_validation->set_rules('enq_reason', 'Enquiry', 'trim');
        $this->form_validation->set_rules('receive_update_news', 'New Updates', 'trim');
        $this->form_validation->set_rules('how_reach', 'How you reach', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $inner = array();
        $shell = array();
        $inner['news'] = $news;
        $inner['page'] = $page;
        $inner['enquiryList'] = $this->Enquirymodel->getEnquiryTypeList();
        $inner['availEnquiryList'] = $this->Enquirymodel->getAvailEnquiryList();
        //e($inner['availEnquiryList']);
//        ep($inner['availEnquiryList']);
        $inner['userAllEnqCount'] = $this->Enquirymodel->getUserAllEnquiriesCount();
        $inner['userClosedEnqCount'] = $this->Enquirymodel->getUserAllEnquiriesCount(1);
        $inner['userActiveEnqCount'] = $this->Enquirymodel->getUserAllEnquiriesCount(2);
        $inner['grapArray'] = array(
                                array(
                                    'name' => 'enquiryGraph',
                                    'data' => array(
                                            'Running' => $inner['userActiveEnqCount']['ttl'],
                                            'Completed' => $inner['userClosedEnqCount']['ttl'],                                            
                                        )
                                    )
                            );
        $inner['enq_filter_status'] = 0; 
        $inner['enq_filter_date'] = ''; 
        $shell = $inner;
        $inner['tab_1'] = 'active';
        $inner['tab_2'] = '';        
        $enq_filter_status = $this->input->post('filter-enq-status', TRUE);
        $enq_filter_date = $this->input->post('filter-enq-date', TRUE);
        if(!empty($enq_filter_status) || !empty($enq_filter_date)){
            $inner['tab_2'] = '';
            $inner['tab_1'] = 'active';
            $inner['enq_filter_status'] = $enq_filter_status; 
            $inner['enq_filter_date'] = $enq_filter_date;            
            $shell['content'] = $this->load->view('enquiry-form', $inner, true);
            $this->load->view($this->template['default'], $shell);            
        }
        else if ($this->form_validation->run() == FALSE) {
            $shell['content'] = $this->load->view('enquiry-form', $inner, true);
            $this->load->view($this->template['default'], $shell);
        } else {

            $data = $this->Enquirymodel->insertRecord();
            if ($data) {
                $this->session->set_flashdata('SUCCESS', 'enquiry_insert');
            } else {
                $this->session->set_flashdata('ERROR', 'enquiry_insert_error');
            }
            redirect('enquiry/enquiry');
            exit();
        }
    }

    function view($id = null) {
        if (!$id)
            redirect('enquiry');
        $inner = array();
        $enq_det = $this->Enquirymodel->fetchByID($id);
        $enq_det['enq_status'] = $this->checkStatus($enq_det['status']);

        $inner['enq_det'] = $enq_det;
        $inner['enq_follow'] = $this->Enquirymodel->getEnquiryFollowUpList($id);
        $inner['todayDate'] = date('d-m-Y',NOW());
        $shell['content'] = $this->load->view('enquiry-followup', $inner, true);
        $this->load->view($this->template['default'], $shell);
    }

    function addFollow() {
        

        $data = array();
        $enquiryActive = $this->input->post('deactive', TRUE);
        $enq_id = $_POST['enq_id'];
        $data['enquiry_id'] = $enq_id;

                //validation check
        $this->form_validation->set_rules('follow_way', 'How you follow', 'trim|required');
        $this->form_validation->set_rules('next_follow_up', 'Fill Follow Date', 'trim|required');
        $this->form_validation->set_rules('description', 'Comment Required', 'trim|required');
        

        $data['follow_way'] = $this->input->post('follow_way', TRUE);
        $data['next_follow_up'] = $this->input->post('next_follow_up', TRUE);
        $data['description'] = $this->input->post('description', TRUE);
        $data['followup_dtime'] = date('Y-m-d', NOW());
        $data['next_follow_up'] = date('Y-m-d', strtotime($data['next_follow_up']));
        if ($enquiryActive) {
            $data['enq_status'] = 5;
        }
        if ($this->db->insert('dpd_enquiry_followup', $data)) {
            if ($enquiryActive) {
                $data = array('active' => 0, 
                                'status' => 5 ,
                                'next_follow_up' => $data['next_follow_up'],
                                'enquiry_id' => $data['enquiry_id']);
                $this->db->where('id', $data['enquiry_id'])
                        ->update('enquiry', $data);
            }else{
                $data = array('next_follow_up' => $data['next_follow_up']); 
                $this->db->where('id', $enq_id)
                        ->update('enquiry', $data);
            }
        }
        redirect('enquiry');
        exit;
    }
}

?>