<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reviews extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($offset = 0) {
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Reviews.</p>'); 
            redirect('dashboard');

        $this->load->model('Reviewsmodel');
        
        $this->load->helper('text');
        


        //Setup pagination
        $perpage = 20;
        $config['base_url'] = base_url() . "reviews/index/";
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Reviewsmodel->countAll();
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        //Get all 
        $enquiries = array();
        $enquiries = $this->Reviewsmodel->listAll($offset, $perpage);

        //render view
        $inner = array();
        $inner['enquiries'] = $enquiries;
        $inner['pagination'] = $this->pagination->create_links();

        $page = array();
        $page['content'] = $this->load->view('reviews-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    function enable($offset = 0) {
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Reviews.</p>'); 
            redirect('dashboard');

        $this->load->model('Reviewsmodel');
        $this->Reviewsmodel->enable($offset);
        redirect('reviews');
    }

    function disable($offset = 0) {
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Reviews.</p>'); 
            redirect('dashboard');
        $this->load->model('Reviewsmodel');
        $this->Reviewsmodel->disable($offset);
        redirect('reviews');
    }

    //delete order
    function delete($eid) {
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Reviews.</p>'); 
            redirect('dashboard');
        $this->load->model('Reviewsmodel');
        $enquiry = array();
        $enquiry = $this->Reviewsmodel->delete($eid);
        redirect('reviews');
    }

}

?>