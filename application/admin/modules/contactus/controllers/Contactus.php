<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contactus extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->data = [];
        $this->data['perpage'] = 20;
    }

    function index($offset = false) {
        if (!$this->flexi_auth->is_privileged('View Companies')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Companies.</p>');
            redirect('dashboard');
        }
        $this->data['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $this->data['INFO'];
        $this->load->model('Contactusmodel');
        $list = $this->Contactusmodel->listAll($offset,$this->data['perpage']);

        $config['base_url'] = base_url() . "contactus/index/";
        $config['uri_segment'] = 3;
        $config['total_rows'] =  $this->Contactusmodel->countAll();
        $config['per_page'] = $this->data['perpage'];
        $this->pagination->initialize($config);

        $inner = [];
        $this->data['table_labels'] = [
            'company_code' => 'Company Code',
            'name' => 'Name',
            'email_address' => 'Email',
            'contact_person' => 'Contact Person',
            'action' => 'Action',
        ];

        $this->data['list'] = $list;
        $this->data['pagination'] = $this->pagination->create_links();

        
        $this->data['content'] = $this->load->view('contact-index', $this->data, TRUE);

        $this->load->view($this->template['default'], $this->data);
    }

    function delete($id = false) {
        if (!$id) {
            redirect('contactus');
        }
        $this->load->model('Contactusmodel');
        $this->Contactusmodel->deleteRecord($id);
        $this->session->set_flashdata('message', 'Enquiry delete Successfully');
        redirect('contactus');
    }

}
