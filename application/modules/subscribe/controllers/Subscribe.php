<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subscribe extends Front_Controller {

    function __construct() {
        parent::__construct();
        //use DrewM\MailChimp\MailChimp;
    }

    function index() {
        $this->load->model('cms/Pagemodel');
        include(APPPATH . 'third_party/mailchimp/Mailchimp.php');
        
        $email = $this->input->post('email', TRUE);
        if(!$email)
        {
            redirect(base_url());
        }
        
        $MailChimp = new MailChimp(MCC_MAILCHIMP_API_KEY);
        $list_id = MCC_MAILCHIMP_UNIQUE_ID;
        $result = $MailChimp->post("lists/$list_id/members", [
            'email_address' => $email, 
            'status' => 'subscribed',
        ]);

        if ($result['status'] == "subscribed") {
            $data['email'] = $this->input->post('email', TRUE);
            $data['datetime'] = date('Y-m-d h:i:s');
            $insert_id = $this->db->insert('newsletter', $data);
            $inner = array();
            $shell = array();
            $shell['contents'] = $this->load->view('subscription-success', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
        } else {
            $inner['error'] = $result['title'] ;//. '! ' . $result['detail'];
            $shell = array();
            $shell['contents'] = $this->load->view('subscription-failed', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
        }
    }

}

?>