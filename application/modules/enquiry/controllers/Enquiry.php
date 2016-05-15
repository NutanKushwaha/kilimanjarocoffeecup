<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Enquiry extends CMS_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('news/Newsmodel');
    }

    function index() {
        $this->load->model('catalog/Cartmodel');
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->library('encrypt');
        $this->load->helper('form');
        $this->load->library('email');
        $this->load->library('parser');
        
        $this->form_validation->set_rules('name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('c_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Telephone', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $page['page_title'] = null;
            $page['page_contents'] = null;
            $inner['page'] = $page;
            $inner['news'] = $this->Newsmodel->listAll(0,2);
            $this->html->addMeta($this->load->view("meta/enquiry_index", $inner, TRUE));
            $this->load->view("themes/" . THEME . "/templates/pages", $inner);
        } else {

            //send email	
            $email = $this->input->post('email', TRUE);
            $emailData = array();
            $emailData['DATE'] = date("jS F, Y");
            $emailData['TITLE'] = $this->input->post('name', TRUE);
            $emailData['COMPANY'] = $this->input->post('c_name', TRUE);
            $emailData['EMAIL'] = $this->input->post('email', TRUE);
            $emailData['PHONE'] = $this->input->post('phone', TRUE);
            //admin email
            $emailBody = $this->parser->parse('emails/enquiry-details.php', $emailData, TRUE);

            $this->email->initialize($this->config->item('EMAIL_CONFIG'));
            $this->email->from($email);
            $this->email->reply_to(MCC_EMAIL_REPLY_TO);
            $this->email->to(MCC_EMAIL_ADMIN);
            $this->email->subject('Inquiry placed at DesktopDeli.co.uk');
            $this->email->message($emailBody);
            $status = $this->email->send();
            if ($status == TRUE) {
                $inner = array();
                $output = array(
                    'status' => '1',
                    'message' => $this->load->view('message/thankyou', $inner, true)
                );
                echo json_encode($output);
                exit();
            } else {
                $inner = array();
                $output = array(
                    'status' => '1',
                    'message' => $this->load->view('message/error', $inner, true)
                );
                echo json_encode($output);
                exit();
            }
        }
    }

    //DOWNLOAD YOUR FREE INFORMATION PACK

    function downloadbox() {
//        echo '<pre>';
//        print_r($_POST);
//        exit;
        $this->load->model('cms/Pagemodel');
        $this->load->library('form_validation');
        $this->load->library('encrypt');
        $this->load->helper('form');
        $this->load->library('email');
        $this->load->library('parser');

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('number', 'number', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            
            $inner = array();
            $this->html->addMeta($this->load->view("meta/enquiry_index", $inner, TRUE));
            $this->load->view("themes/" . THEME . "/templates/default-homepage", $inner);
        } else {
            echo 'here';
            exit;
        }
    }

}
?>
