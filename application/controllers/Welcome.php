<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends Front_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Common_auth_model');
        $this->load->model('Commonusermodel');
        //$this->load->library('flexi_auth');
        $this->load->library('form_validation');
        $this->data = null;
    }

    function index() {
        //redirect('customer/login');
        $this->load->model('Settingsmodel');
        $this->load->model('Pagemodel');

        //Page Alias
        $alias = 'homepage';
        //Get Page Details
        $page = array();
        $page = $this->Pagemodel->getDetails($alias);

        if (!$page) {
            $this->utility->show404();
            return;
        }

        $this->cms->setPage($page);


//        Get Page Blocks
        $blocks = array();
        $blocks = $this->Pagemodel->getAllBlocks($page['page_id']);

        //Compiled blocks
        $compiled_blocks = array();
        $compiled_blocks = $this->Pagemodel->compiledBlocks($page, $blocks);


        //Variables
        $inner = array();
        $inner['page'] = $page;

        foreach ($compiled_blocks as $key => $val) {
            $inner[$key] = $val;
        }

        //Compile page template
        echo $this->Pagemodel->compiledPage($page, $inner);
        exit();
    }

    function test() {
        print_r($this->session->all_userdata());
    }

    function manual_reset_forgotten_password($user_id = FALSE, $token = FALSE) {
        //com_e( func_get_args() );
        // If the 'Change Forgotten Password' form has been submitted, then update the users password.
        if ($this->input->post('change_forgotten_password')) {
            $this->Commonusermodel->manual_reset_forgotten_password($user_id, $token);
        }

        // Get any status message that may have been set.
        $this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') :
                $this->data['message'];
        $this->load->view('themes/' . THEME . '/welcome/user-forgot-pass-update', $this->data);
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */