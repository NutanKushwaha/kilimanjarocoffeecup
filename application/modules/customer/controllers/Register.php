<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends Front_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Commonusermodel');
        $this->load->model('Companymodel');
        $this->isLogged = $this->flexi_auth->is_logged_in();
        if ($this->isLogged) {
            redirect('customer/dashboard');
        }
    }

    function forgot_password() {
        $this->load->library('form_validation');
        $this->load->model('Customermodel');
        $inner = array();
        $shell = array();
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $shell['contents'] = $this->load->view('change_pass', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
        } else {
            $this->Customermodel->sendVerificationEmail();
            $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
            redirect('customer/register/forgot_password/');
        }
    }

    function index() {

        $this->load->model('Commonusermodel');
        $this->load->library('form_validation');
        $this->load->helper('string');
        $this->load->library('encrypt');
        $this->load->library('parser');
        $this->load->library('session');
        //$this->load->library('email');
        $this->load->helper('form');
        //$this->load->model('EmailModel');
        //Validation checks
        /*
          if(empty($_FILES['image']['name']))
          {
          $this->form_validation->set_rules('imagefile', 'profile pic', 'trim|required');
          }
         */
          $this->form_validation->set_message('is_unique', 'The %s is already taken');
        $this->form_validation->set_rules('uacc_username', 'User Name', 'trim|required|is_unique[user_accounts.uacc_username]');
        $this->form_validation->set_rules('upro_first_name', 'First Name', 'trim|max_length[50]|required');
        $this->form_validation->set_rules('upro_last_name', 'Last Name', 'trim|max_length[50]|required');
        $this->form_validation->set_rules('uacc_email', 'Email', 'trim|required|valid_email|max_length[75]|is_unique[user_accounts.uacc_email]');
        $this->form_validation->set_rules('uacc_password', 'User Password', 'trim|required|min_length[5]|max_length[15]');
        $this->form_validation->set_rules('uacc_cpassword', 'Confirm Password', 'required|matches[uacc_password]');
        $this->form_validation->set_rules('uadd_address_01', 'Address 1', 'trim|required');
        $this->form_validation->set_rules('uadd_city', 'City ', 'trim|required');
        $this->form_validation->set_rules('uadd_county', 'County ', 'trim|required');
        $this->form_validation->set_rules('uadd_post_code', 'Postcode ', 'trim|required');
        $this->form_validation->set_rules('uadd_country', 'Country ', 'trim|required');
        $this->form_validation->set_rules('uadd_phone', 'Phone Number', 'trim|required|numeric');

        $this->form_validation->set_rules('uadd_recipient', 'Address Receipient', 'trim|required|max_length[75]');
        //$this->form_validation->set_rules('uadd_alias', 'Address Alias', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //Render View
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $shell = array();
            $inner['INFO'] = (!isset($inner['INFO'])) ?
                    $this->session->flashdata('message') : $inner['INFO'];
            $inner['post'] = $_POST;
            $shell['contents'] = $this->load->view('register', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {
            $customer = $this->Commonusermodel->adduser([]);
            if ($customer) {
                redirect('customer/register/success');
                exit();
            } else {
                redirect('customer/register/error');
                exit();
            }
        }
    }

    function success() {
        $this->load->model('Customermodel');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->library('cart');

        //Render View
        $inner = array();
        $shell = array();

        $shell['contents'] = $this->load->view('registration-success', $inner, TRUE);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    function error() {
        $this->load->model('Customermodel');
        $this->load->library('form_validation');
        $this->load->model('cms/Pagemodel');
        $this->load->model('cms/Templatemodel');
        $this->load->helper('form');
        $this->load->library('cart');

        //Render View
        $inner = array();
        $this->html->addMeta($this->load->view("meta/register_error.php", $inner, TRUE));

        $shell = array();
        $shell['contents'] = $this->load->view('registration-error', $inner, TRUE);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    function register_email() {
        $this->load->library('parser');
        $this->load->model('Emailmodel');
        $data = array(
            'title' => 'Blog Title',
            'blog_heading' => 'http://www.google.com/',
        );
        $this->Emailmodel->getEmailByTag('register_email');

        ( $this->parser->parse('register_email', $data));
    }

    function test_email() {
        $this->load->library('parser');




        $message = "<h1>Test Email</h1>";

        $this->load->library('SEmail');
        $this->load->model('EmailModel');
        $email_config = [
            'to' => "test@testmail.com",
            'subject' => 'Testing',
            'from' => 'test@testmail.com',
            'body' => $message
        ];
        $emailArray = $this->EmailModel->getEmailByTag('CUSTOMER_REGISTER');
        $emailContent = $emailArray['email_content'];

        e($this->parser->parse('blog_template', $data));
        return $this->semail->send_mail($email_config);
    }
    function generate()
    {
     $token = str_replace(".","",microtime(true));   
    }

}

?>
