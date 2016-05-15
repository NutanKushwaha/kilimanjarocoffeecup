<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_Controller extends CMS_Controller {

    protected $company_id;
    protected $ids = array();

    function __construct() {
        parent::__construct();
        $this->isLogged();
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
}
