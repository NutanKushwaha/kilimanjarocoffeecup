<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminajax_Controller extends CMS_Controller {

    protected $company_id;
    protected $ids = array();

    function __construct() {
        parent::__construct();
        /* if not ajax request redirect to homepage. */
        if( !$this->input->is_ajax_request()){            
            redirect('/');
        }        
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
