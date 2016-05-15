<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cost extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function index() {
        $this->load->model('Costmodel');
        $this->load->helper('text');
        
        
        $this->load->model('Categorymodel');


        //Get Search Criteria
        if ($this->input->post('category')) {
            $keywords = $this->input->post('category', TRUE);
        }

        $category_list = array();
        $category_list = $this->Costmodel->indentedList(0);

        $options = array();
        $options[''] = '-Select-';
        foreach ($category_list as $item) {
            $options[$item['category_id']] = str_repeat("&nbsp;", ($item['depth'] * 5)) . $item['category'];
        }

        $this->form_validation->set_rules('category_id[]', 'Category', 'trim');
        $this->form_validation->set_rules('pricing', 'Pricing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['options'] = $options;
            $page['content'] = $this->load->view('cost/cost-index', $inner, TRUE);
            $this->load->view('shell', $page);
        } else {

            $this->Costmodel->insertRecord();

            $this->session->set_flashdata('SUCCESS', 'category_updated');

            redirect("cpcatalogue/cost");
            exit();
        }
    }

    function costlog($offset = 0) {
        $this->load->model('Costmodel');
        
        $this->load->helper('text');
        $this->load->helper('url');

        $perpage = 10;
        $config['base_url'] = base_url() . "cpcatalogue/cost/costlog";
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Costmodel->costcountAll();
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        $cost_log = $this->Costmodel->costListAll($offset, $perpage);

        $inner = array();
        $page = array();
        $inner['costlog'] = $cost_log;
        $inner['pagination'] = $this->pagination->create_links();
        $page['content'] = $this->load->view('cost/cost-list', $inner, TRUE);
        $this->load->view('shell', $page);
    }

    function history($offset = 0) {
        $this->load->model('Costmodel');
        
        $this->load->helper('text');
        $this->load->helper('url');

        $perpage = 10;
        $config['base_url'] = base_url() . "cpcatalogue/cost/history";
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Costmodel->countAll();
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        $history_log = $this->Costmodel->ListAll($offset, $perpage);

        $inner = array();
        $page = array();
        $inner['historylog'] = $history_log;
        $inner['pagination'] = $this->pagination->create_links();
        $page['content'] = $this->load->view('cost/cost-history', $inner, TRUE);
        $this->load->view('shell', $page);
    }

    function action($cat_id) {
        $this->load->model('Costmodel');

        $cost_log = $this->Costmodel->costListAction($cat_id);
        redirect('cpcatalogue/cost/costlog');
        exit;
    }

}

?>