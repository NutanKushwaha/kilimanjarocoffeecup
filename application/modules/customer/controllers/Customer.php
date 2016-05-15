<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends Front_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Commonusermodel');
        $this->session->unset_userdata('CheckoutAddress');
    }

    function index() {
        $inner['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $inner['INFO'];
        redirect('customer/login');
    }

    function confirm() {
        $this->load->library("form_validation");
        if ($this->cart->total_items() == 0) {
            $this->session->set_flashdata('message', "Cart is Empty");
            redirect('catalogue/cart');
            return false;
        }
        $this->form_validation->set_rules('role', 'Checkout type', 'trim|required');
        $inner = array();
        if ($this->form_validation->run() == FALSE) {
            $shell['contents'] = $this->load->view('confirm', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {
            self::confirm_user();
        }
    }

    function confirm_user() {
        $role = $this->input->post('role');
        
        if ($role == "guest") {
            $checkout = array('checkoutRole' => "guest");
            $this->session->set_userdata($checkout);
            redirect('customer/guest_details');
            //Guest Functionality
        } else {
            $this->session->set_userdata(array('refer_to'=>"CART"));
            $checkout = array('checkoutRole' => "user");
            $this->session->set_userdata($checkout);
            redirect('customer/login');
            //user Functionality
        }
    }



    function guest_details() {

        $this->session->unset_userdata("user_register");
        $this->load->model('Emailmodel');
        $this->load->model('Customermodel');
        $this->load->model('Commonusermodel');
        $this->load->helper('string');
        $this->load->library('encrypt');
        $this->load->library('parser');
        $this->load->library('session');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->library("form_validation");
        if ($this->cart->total_items() == 0) {
            $this->session->set_flashdata('message', 'No item found in cart .');
            redirect('catalogue/cart');
            return false;
        }
        $this->form_validation->set_rules('upro_first_name', 'First Name', 'trim|max_length[50]|required');
        $this->form_validation->set_rules('upro_last_name', 'Last Name', 'trim|max_length[50]|required');
        $this->form_validation->set_rules('uacc_email', 'Email', 'trim|required|valid_email|max_length[75]');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['post'] = false;
            $shell['contents'] = $this->load->view('get_guest_details', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {
            $opts = [];
            $opts['guest_order'] = true;
            $customer = $this->Customermodel->getUserAccountByEmail($this->input->post('uacc_email'));
            $customer = arrIndex($customer, 'uacc_id');
            if ($customer == null) {
                $this->flexi_auth->change_config_setting('auto_increment_username', 1);
                $customer = $this->Commonusermodel->adduser($opts);
                $this->flexi_auth->change_config_setting('auto_increment_username', 0);
            }
            if ($customer) {
                $guestInfo = $this->Customermodel->getGuestUserInfo($customer);
                $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
                $this->session->set_userdata(array('user_register' => "1", 'guest_user_id' => $customer, 'guestUserInfo' => $guestInfo));
                redirect('customer/shipping_details');
                exit();
            }
            redirect('customer/register/error');
            exit();
        }
    }

    function shipping_details() {

        $this->load->model('Contactmodel');
        $this->load->model('Customermodel');
        if ($this->session->userdata('CheckoutAddress') != null) {
            redirect('catalogue/cart/process');
        }
        if ($this->cart->total_items() == 0) {
            $this->session->set_flashdata('message', 'No item found in cart .');
            redirect('catalogue/cart');
            return false;
        }
        $this->load->library("form_validation");
        /* ---------Shipping Address--------- */
        $this->form_validation->set_rules('uadd_recipient', 'Shipping Address Recpient', 'trim|required');
        $this->form_validation->set_rules('uadd_phone', 'Shipping Phone', 'trim|required');
        $this->form_validation->set_rules('uadd_address_01', 'Shipping Address 1', 'trim|required');
        $this->form_validation->set_rules('uadd_city', 'Shipping City', 'trim|required');
        $this->form_validation->set_rules('uadd_post_code', 'Shipping Post Code', 'trim|required');
        $this->form_validation->set_rules('uadd_county', 'Shipping County', 'trim|required|max_length[2]');
        $this->form_validation->set_rules('uadd_country', 'Shipping Country', 'trim|required');
        /* ---------Billing Address--------- */
        $this->form_validation->set_rules('billing_uadd_recipient', 'Billing Address Recpient', 'trim|required');
        $this->form_validation->set_rules('billing_uadd_phone', 'Billing Phone', 'trim|required');
        $this->form_validation->set_rules('billing_uadd_address_01', 'Billing Address 1', 'trim|required');
        $this->form_validation->set_rules('billing_uadd_city', 'Billing City', 'trim|required');
        $this->form_validation->set_rules('billing_uadd_post_code', 'Billing Post Code', 'trim|required');
        $this->form_validation->set_rules('billing_uadd_county', 'Billing County', 'trim|required|max_length[2]');
        $this->form_validation->set_rules('billing_uadd_country', 'Billing Country', 'trim|required');
        $this->isLogged = $this->flexi_auth->is_logged_in();


        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            if ($this->isLogged) {
                $userDetails = $this->flexi_auth->get_user_custom_data();
                $user_id = $userDetails["uacc_id"];
                $inner['loggedIn'] = true;
                $inner['allAddress'] = $this->Contactmodel->listAllAddress($user_id);
            } else {
                $inner['loggedIn'] = false;
            }
            $inner['address'] = $this->input->post();
            $shell['contents'] = $this->load->view('get_guest_shipping_details', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {
            $this->Customermodel->saveShipingAndBillingAddress();
            $this->session->set_userdata(array('CheckoutAddress' => $this->input->post()));
            //redirect('catalogue/cart/process');
            redirect('catalogue/cart/shippingSetUp');
            exit();
        }
    }

    /*
      $this->data['INFO'] = (!isset($inner['INFO'])) ?
      $this->session->flashdata('message') : $this->data['INFO'];
     */

    function contactUs() {
        $this->load->library("form_validation");
        $this->load->model('Customermodel');
        $this->load->library('session');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[99]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[99]');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[99]');
        $this->form_validation->set_rules('comment', 'comment', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', validation_errors());
            redirect('/contact-us');
        } else {
            $output = $this->Customermodel->ContactUsQuery();
            $this->session->set_flashdata('message', $output['message']);
            redirect('contact-us', 'refresh');
        }
    }

}
