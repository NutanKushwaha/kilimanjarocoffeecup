<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rating extends Front_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($alias = '') {
       
        $this->load->library('form_validation');
        $this->load->model('cms/Pagemodel');
        $this->load->model('ratingmodel');
        $this->load->model('catalogue/Productmodel');
        $this->load->library('email');

        $response = array();
        $response['msg'] = "";
        $response['status'] = "";
        $response['html'] = "";
        $rating = $this->input->post('score');
        $name = $this->input->post('name');
        $summary = $this->input->post('summary');

        $product_id = $this->input->post('product_id');
        $product = $this->Productmodel->fetchById($product_id);
       
        if ( !is_numeric($rating) && floatval($rating)  == "0") {
            $response['status'] = 'error';
            $response['msg'] = ' .ratingerror ,';
        }
        if (trim($name) == "") {
            $response['status'] = 'error';
            $response['msg'] .= ' .nameerror ,';
        }
        if (trim($summary) == "") {
            $response['status'] = 'error';
            $response['msg'] .= ' .summaryerror ';
        }
        if ($response['status'] == "error") {
            echo json_encode($response);
            return false;
        }
        //if ($this->ratingmodel->checkIp($this->input->post('product_id'))) {
           
            $this->ratingmodel->insertRecord();
            $response['status'] = 'success';
            $response['html'] = 'Thanks for Rating this Product';

            if ($insertRes = 1) {
                $emailBody = 'This is confirmation mail regarding Review on product &nbsp;"' . $product['product_name'].'"';
               
                $this->email->initialize($this->config->item('EMAIL_CONFIG'));
                $this->email->from(MCC_EMAIL_NOREPLY, MCC_EMAIL_FROM);
//            $this->email->to('info@eventhireuk.com');
                $this->email->to(MCC_EMAIL_ADMIN);
                $this->email->subject('Alert for Review confirmation');
                $this->email->message($emailBody);
                $this->email->send();
            }
        //} 
//        else {
//            $response['status'] = 'error';
//            $response['html'] = 'Rating Already submitted ';
//        }
        echo json_encode($response);

        //Render View
    }

}

?>