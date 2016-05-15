<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_message extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    //function index
    function index($marketing = false) {
        $this->load->model('Emailmessagesmodel');




        $market_email = 0;
        if ($marketing) {
            $market_email = 1;
        }
        //print_R($market_email); exit();
        //list all email
        $messages = array();
        $messages = $this->Emailmessagesmodel->listAll($market_email);



        //render view
        $inner = array();
        $inner['messages'] = $messages;
        $inner['market_email'] = $market_email;

        $page = array();
        $page['content'] = $this->load->view('email-messages-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    //function to add marketing email template record
    function add() {


        $this->load->model('Emailmessagesmodel');

        $this->editor('600px');
        //Form Validations
        $this->form_validation->set_rules('email_name', 'Name ', 'trim|required');
        $this->form_validation->set_rules('email_subject', 'Email Subject ', 'trim|required');
        $this->form_validation->set_rules('email_content', 'Email Content ', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();

            $page['content'] = $this->load->view('marketing/email-template-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Emailmessagesmodel->insertMarketingTemplateRecord($messages);

            $this->session->set_flashdata('SUCCESS', 'email_template_added');

            redirect('email_message/index/1', 'location');

            exit();
        }
    }

    //function to edit record
    function edit($eid) {


        $this->load->model('Emailmessagesmodel');
        $this->editor('600px');
        //Get Menu Details
        $messages = array();
        $messages = $this->Emailmessagesmodel->detail($eid);

        if (!$messages) {
            $this->utility->show404();
            return;
        }

        //Form Validations$this->form_validation->set_rules('email_name', 'Name ', 'trim|required');

        $this->form_validation->set_rules('email_subject', 'Email Subject ', 'trim|required');
        $this->form_validation->set_rules('email_content', 'Email Content ', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $messages['marketing_email'] = 0;
            $inner['messages'] = $messages;            
            $page['content'] = $this->load->view('email-messages-edit', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Emailmessagesmodel->updateRecord($messages);
            $this->session->set_flashdata('SUCCESS', 'email_template_updated');

            if ($messages['marketing_email'] == 1) {

                redirect('email_message/index/1', 'location');
            } else {

                redirect('email_message/index', 'location');
            }
            exit();
        }
    }

    function delete($eid) {


        $this->load->model('Emailmessagesmodel');


// get email content
        $messages = array();
        $messages = $this->Emailmessagesmodel->detail($eid);

        if (!$messages) {
            $this->utility->show404();
            return;
        }

        //Delete Marketing Email Template
        $this->Emailmessagesmodel->deleteRecord($messages);
        $this->session->set_flashdata('SUCCESS', 'email_template_deleted');

        redirect('email_message/index/1', 'location');
        exit();
    }

}

?>
