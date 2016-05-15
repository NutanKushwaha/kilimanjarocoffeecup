<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact extends CMS_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('Commonusermodel');

        $this->load->model('Customermodel');
    }

    function index() {

        redirect('customer/contact/edit');
    }

    function addressUniqueAgainstUser() {

        $userDetails = $this->flexi_auth->get_user_custom_data();

        $user_id = $userDetails["uacc_id"];

        $this->db->where('uadd_alias', $this->input->post('alias'));

        $this->db->where('uadd_uacc_fk', $user_id);

        $query = $this->db->get('user_address');

        if ($query->num_rows() > 0) {

            $this->form_validation->set_message('addressUniqueAgainstUser', 'Address alias is already used.');

            return false;
        }

        return true;
    }

    function edit() {

        //e($this->Customermodel->getUserInformation());

        $userDetails = $this->Customermodel->getUserInformation();

        $userAccId = "uacc_id";

        $user_id = $userDetails[$userAccId];

        $this->data['user_id'] = $user_id;

        $this->load->library('form_validation');

        $this->form_validation->set_rules('uacc_username', 'User Name', 'trim|required|identity_available[' . $user_id . ']');

        $this->form_validation->set_rules('upro_first_name', 'First Name', 'trim|max_length[50]|required');

        $this->form_validation->set_rules('upro_last_name', 'Last Name', 'trim|max_length[50]|required');

        $this->form_validation->set_rules('uacc_email', 'Email', 'trim|required|valid_email|max_length[75]|identity_available[' . $user_id . ']');

        if ($this->input->post('uacc_password')) {

            $this->form_validation->set_rules('uacc_password', 'User Password', 'trim|required|min_length[5]|max_length[15]');

            $this->form_validation->set_rules('uacc_cpassword', 'Confirm Password', 'required|matches[uacc_password]');
        }

        if ($this->form_validation->run() == false) {

            $inner = array();

            $shell = array();

            $inner['post'] = $_POST;

            $inner['userDetails'] = $userDetails;

            $shell['contents'] = $this->load->view('dashboard/edit-contact', $inner, true);

            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {



            if ($this->Commonusermodel->updateuser([])) {



                // Save any public status or error messages 
                //	(Whilst suppressing any admin messages) to CI's flash session data.                            

                $this->session->set_flashdata('message', $this->flexi_auth->get_messages());

                redirect('customer/dashboard');
            }
        }
    }

    function all_address() {

        $this->load->model('Contactmodel');



        $userDetails = $this->flexi_auth->get_user_custom_data();

        $userAccId = "uacc_id";

        $user_id = $userDetails[$userAccId];



        $allAddress = $this->Contactmodel->listAllAddress($user_id);



        $inner = array();

        $inner['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $inner['INFO'];

        $shell = array();

        $inner['post'] = $_POST;

        $inner['allAddress'] = $allAddress;

        $inner['userDetails'] = $userDetails;

        $shell['contents'] = $this->load->view('dashboard/address-index', $inner, true);

        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
    }

    /* -------------edit address funciton-------------- */

    function add_address() {

        $this->load->library('form_validation');

        $this->load->model('Contactmodel');

        $userDetails = $this->flexi_auth->get_user_custom_data();

        $user_id = $userDetails["uacc_id"];



        $addressType = array('s' => "Shipping Address", 'b' => "billing Address");

        $this->form_validation->set_rules('alias', 'Address Alias', 'trim|required|callback_addressUniqueAgainstUser');

        $this->form_validation->set_rules('uadd_recipient', 'Address Recipient', 'trim|required');

        $this->form_validation->set_rules('uadd_address_01', 'Address 1', 'trim|required');

        $this->form_validation->set_rules('uadd_city', 'City', 'trim|required');

        $this->form_validation->set_rules('uadd_post_code', 'Post code', 'trim|required');

        $this->form_validation->set_rules('uadd_country', 'Country', 'trim|required');

        $this->form_validation->set_rules('uadd_county', 'County', 'trim|required');



        if ($this->form_validation->run() == false) {

            $inner = array();

            $shell = array();

            $inner['address'] = $this->input->post();

            $inner['addressType'] = $addressType;

            $shell['contents'] = $this->load->view('dashboard/add-address', $inner, true);

            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {

            $this->Contactmodel->InsertAddress();

            $this->session->set_flashdata('message', 'Address Added successfully !');

            redirect('customer/contact/all_address');
        }
    }

    /* -------------edit address funciton-------------- */

    function edit_address($AddressId = false) {

        $this->load->library('form_validation');

        if (!$AddressId) {

            redirect('customer/dashboard');
        }

        $this->load->model('Contactmodel');

        $userDetails = $this->flexi_auth->get_user_custom_data();

        $user_id = $userDetails["uacc_id"];

        $allAddress = $this->Contactmodel->getAddressById($AddressId);



        $addressType = array('s' => "Shipping Address", 'b' => "billing Address");

        if ($allAddress['num_rows'] < 1) {

            redirect('customer/dashboard');
        }

        //$this->form_validation->set_rules('alias', 'Address Alias', 'trim|required');

        $this->form_validation->set_rules('uadd_recipient', 'Address Recipient', 'trim|required');

        $this->form_validation->set_rules('uadd_address_01', 'Address 1', 'trim|required');

        $this->form_validation->set_rules('uadd_city', 'City', 'trim|required');

        $this->form_validation->set_rules('uadd_post_code', 'Post code', 'trim|required');

        $this->form_validation->set_rules('uadd_country', 'Country', 'trim|required');

        $this->form_validation->set_rules('uadd_county', 'County', 'trim|required');





        if ($this->form_validation->run() == false) {

            $inner = array();

            $shell = array();

            $inner['address'] = $allAddress['result'];

            $inner['addressType'] = $addressType;

            $shell['contents'] = $this->load->view('dashboard/edit-address', $inner, true);

            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {

            $this->Contactmodel->UpdateAddress($AddressId);

            $this->session->set_flashdata('message', 'Address updated successfully !');

            redirect('customer/contact/all_address');
        }
    }

    function addressDetail() {



        $this->load->library('form_validation');

        $this->form_validation->set_rules('alias', 'Address', 'trim|required');

        if ($this->form_validation->run() == false) {

            echo json_encode(array('response' => false, 'msg' => validation_errors()));

            return false;
        }

        $userDetails = $this->flexi_auth->get_user_custom_data();

        $user_id = $userDetails["uacc_id"];

        $this->db->where('uadd_alias', $this->input->post('alias'));

        $this->db->where('uadd_uacc_fk ', $user_id);

        $query = $this->db->get('user_address');

        if ($query->num_rows() > 0) {

            $json = array('response' => true, 'result' => $query->row_array(), 'fillmode' => $this->input->post('fillmode'));
        } else {

            $json = array('response' => false, 'msg' => "No record Found.");
        }

        echo json_encode($json);
    }

}
