<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends Front_Controller {

    function __construct() {
        parent::__construct();
    }

//***************************Validation Functions Start ****************************************************************
    function login_check($str) {
//        $email = gParam('email');
//        $password = gParam('password');
//        if (!$email || !$password) {
//            return false;
//        }
//        $this->db->from('applicants');
//        $this->db->where('email', $email);
//        $this->db->where('password', md5($password));
//        $result = $this->db->get()->row_array();
//        return count($result);
        return true;
    }

//*****************************Validation Functions End ********************************************************************
    function logout() {
        $this->session->unset_userdata('checkoutRole');
        $this->session->unset_userdata('user_register');
        $this->session->unset_userdata('guest_user_id');
        $this->session->unset_userdata('CheckoutAddress');
        $this->session->unset_userdata('guestUserInfo');
        $this->session->unset_userdata('last_order');
        $this->flexi_auth->logout(false);
        die();
        //   redirect('customer/login');
    }

    function activate_account($user_id = false, $token = FALSE) {
        log_message('debug', 'ServerAgent:'.$_SERVER['HTTP_USER_AGENT']);
        log_message('debug', 'ServerDetal:'.json_encode($_SERVER));
        if (!$user_id && !$token) {
            redirect('/');
        }
        // The 3rd activate_user() parameter verifies whether to check '$token' matches the stored database value.
        // This should always be set to TRUE for users verifying their account via email.
        // Only set this variable to FALSE in an admin environment to allow activation of accounts without requiring the activation token.
        
         $this->db->where("uacc_id",$user_id);
         $queryUser = $this->db->get("user_accounts")->row_array();
         if($queryUser['uacc_active']=="1")
         {
             $this->session->set_flashdata('message', "Your email is succesfully verified.");
             redirect('customer/login');
         }elseif ($queryUser['uacc_active']=="0") {
             
            $dataAr = array();
            $dataAr['uacc_active'] = "1";
            $dataAr['uacc_activation_token'] = "";
            $this->db->where("uacc_id",$user_id);
            $this->db->update("user_accounts",$dataAr);
             $this->session->set_flashdata('message', "Your email is succesfully verified.");
             redirect('customer/login');
        }
        
        $this->flexi_auth->activate_user($user_id, $token, TRUE);

        // Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
        $this->session->set_flashdata('message', $this->flexi_auth->get_messages());

        redirect('customer/login');
    }

    function index() {
        //print_r($this->session->all_userdata());
        $this->load->library('user_agent');
        $referred_from_url = $this->agent->referrer();
        $this->load->model('Common_auth_model');
        $this->load->model('Commonusermodel');
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->library('parser');
        $this->load->helper('form');
        $this->load->helper('text');

//Get Page Details
//validation check
        $this->isLogged = $this->flexi_auth->is_logged_in();
        if ($this->isLogged) {
            redirect('customer/dashboard');
        }
        if (count($_POST)) {
            $this->session->set_flashdata('error', '<h1 style="color:red">Wrong email id or password</h1>');
        }
        $this->form_validation->set_rules('login_identity', 'Email', 'trim|required');
        $this->form_validation->set_rules('login_password', 'Password', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');


        if ($this->form_validation->run() == FALSE) {

            $inner = array();
            $inner['INFO'] = (!isset($inner['INFO'])) ?
                    $this->session->flashdata('message') : $inner['INFO'];
            $shell = array();
            $shell['contents'] = $this->load->view('login-page', $inner, true);
            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {

            if (!$this->Common_auth_model->login()) {

                $this->session->set_flashdata('error', $this->flexi_auth->get_messages());
                redirect('customer/login');
            } else {
                $this->session->unset_userdata('checkoutRole');
                $this->session->unset_userdata('user_register');
                $this->session->unset_userdata('guest_user_id');
                $this->session->unset_userdata('CheckoutAddress');
                $this->session->unset_userdata('guestUserInfo');
                $this->session->unset_userdata('last_order');
                if($this->session->userdata('refer_to')=="CART")
                {
                    $this->session->unset_userdata('refer_to');
                     redirect('catalogue/cart');
                }
                redirect('customer/dashboard');
            }
        }
    }

}

?>
