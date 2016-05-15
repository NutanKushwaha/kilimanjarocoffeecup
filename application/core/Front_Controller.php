<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Front_Controller extends CI_Controller {

    protected $company_id;
    protected $ids = array();

    function __construct() {
        parent::__construct();
        $this->auth = new stdClass;
        $this->load->library('flexi_auth');

        $this->module_path = realpath(APPPATH . '/views/' . $this->router->directory . '../');
        $this->load->vars(array('CI' => $this));        
        //self::init();
    }

    function getReviews() {
        $this->load->model('catalog/Reviewsmodel');
        return $this->Reviewsmodel->countAll();
    }

    function getUser() {
        return $this->member_data;
    }
    

    function getUserName() {
        return $this->user_name;
    }

    function getUserID() {
        return $this->user_id;
    }     
    function setPage($page) {
            $this->page = $page;
    }

    function getPage() {
            return $this->page;
    }    

    function baseURL() {
        return base_url();
    }    
}
