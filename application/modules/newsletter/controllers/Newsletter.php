<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Newsletter extends CMS_Controller {

    function index() {

        $this->load->library('form_validation');
        $this->load->model('cms/Pagemodel');
        $this->load->model('cms/Templatemodel');
        $this->load->helper('string');
        $this->load->library('encrypt');
        $this->load->library('parser');
        $this->load->library('semail');
        $this->load->helper('form');




        //validation check
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //Render View
        if ($this->form_validation->run() == FALSE) {
            echo 'Email is rquired';
//            redirect(base_url());
            exit();
        } else {
            $data['email'] = $this->input->post('email', TRUE);
            $data['datetime'] = date('Y-m-d h:i:s');
            $insert_id = $this->db->insert('newsletter', $data);
            if ($insert_id) {
                $emailData = array();
                $emailData['DATE'] = date("jS F, Y");
                $emailData['EMAIL'] = $this->input->post('email', TRUE);
                $emailBody = $this->parser->parse('newsletter/emails/newsletter-subscription', $emailData, TRUE);
               
                $config = array();
//                $this->semail->initialize($this->config->item('EMAIL_CONFIG'));
//
//                $this->semail->from(MCC_EMAIL_NOREPLY, MCC_EMAIL_FROM);
//                $this->semail->reply_to(MCC_EMAIL_REPLY_TO);
//                $this->semail->to(MCC_EMAIL_ADMIN);
//                $this->semail->subject('Newsletter Subscription');
//                $this->semail->message($emailBody);
                $email_config = [
			'to' => MCC_EMAIL_ADMIN,
			'subject' => 'Newsletter Subscription',
			'from' => MCC_EMAIL_FROM,
			'body' => $emailBody
		];
                
                $status =  $this->semail->send_mail( $email_config );	
                if ($status == TRUE) {
                    redirect('newsletter/success');
                    exit();
                }
                redirect('newsletter/failed');
                exit();
            }
        }
    }

    //Successfully Registred
    function success() {

        $this->load->library('form_validation');
        $this->load->model('cms/Pagemodel');
        $this->load->model('cms/Templatemodel');
        $this->load->helper('form');
        $this->load->library('cart');

        //Render View
        $inner = array();
        $shell = array();
        $shell['contents'] = $this->load->view('subscription-success', $inner, TRUE);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    function failed() {

        $this->load->library('form_validation');
        $this->load->model('cms/Pagemodel');
        $this->load->model('cms/Templatemodel');
        $this->load->helper('form');
        $this->load->library('cart');

        //Render View
        $inner = array();

        $shell = array();
        $shell['contents'] = $this->load->view('subscription-failed', $inner, TRUE);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    function preview($id = false) {

        $this->db->where('email_log_id', $id);
        $rs = $this->db->get('email_log');
        if (!$rs || $rs->num_rows() != 1) {
            $this->utility->show404();
            return;
        }
        $log_entry = $rs->row_array();


        $shell['log_entry'] = $log_entry;
        $this->load->view("themes/" . THEME . "/templates/newsletter", $shell);
    }

    function unsubscribe($cid = false) {
        $this->db->where('customer_id', intval($cid));
        $rs = $this->db->get('customer');
        if (!$rs || $rs->num_rows() != 1) {
            $this->utility->show404();
            return;
        }
        $customer = $rs->row_array();

        $update = array();
        $update['news_subscription'] = 0;
        $this->db->where('customer_id', $customer['customer_id']);
        $this->db->update('customer', $update);

        redirect('unsubscribed');
    }

}

?>
