<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms extends Admin_Controller {

	function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function index() {		
    	if (! $this->flexi_auth->is_privileged('View Cms')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Cms.</p>'); redirect('dashboard'); }

        $this->data['content'] = '';
        $this->load->view($this->template['default'], $this->data);
    }	
}