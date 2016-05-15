<?php

class Option_rows extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function index($oid = 0, $offset = 0) {
        $this->load->model('Rowsmodel');
        $this->load->model('Optionsmodel');
        
        
        $this->load->helper('text');
        

        //get attributes details
        $options = array();
        $options = $this->Optionsmodel->getDetails($oid);
        if (!$options) {
            $this->utility->show404();
            return;
        }

        //Setup Pagination
        $perpage = 50;
        $config['base_url'] = base_url() . "option_rows/index/$oid";
        $config['uri_segment'] = 4;
        $config['total_rows'] = $this->Rowsmodel->countAll($options['option_id']);
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        //get all attributes options
        $rows = array();
        $rows = $this->Rowsmodel->listAll($options['option_id'], $offset, $perpage);


        //render view
        $inner = array();
        $inner['options'] = $options;
        $inner['rows'] = $rows;
        $inner['pagination'] = $this->pagination->create_links();

        $page = array();
        $page['content'] = $this->load->view('options/rows/rows-index', $inner, TRUE);
        $this->load->view('shell', $page);
    }

    //Function Add attributes
    function add($oid = false) {
        $this->load->model('Rowsmodel');
        $this->load->model('Optionsmodel');
        
        

        //Get attributes Detail
        $options = array();
        $options = $this->Optionsmodel->getDetails($oid);
        if (!$options) {
            $this->utility->show404();
            return;
        }

        //validation check
        $this->form_validation->set_rules('row_label', 'Row Label', 'trim|required');
        $this->form_validation->set_rules('row_value', 'Value', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim');
        $this->form_validation->set_rules('model_no', 'Model No.', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();

            $inner['options'] = $options;
            $page['content'] = $this->load->view('options/rows/row-add', $inner, TRUE);
            $this->load->view('shell', $page);
        } else {
            $this->Rowsmodel->insertRecord($options);

            $this->session->set_flashdata('SUCCESS', 'option_row_added');

            redirect("cpcatalogue/option_rows/index/$oid");
            exit();
        }
    }

    //Function Edit attributesvalue
    function edit($r_id = 0) {
        $this->load->model('Rowsmodel');
        $this->load->model('Optionsmodel');
        
        

        //Get attributes Detail
        $rows = array();
        $rows = $this->Rowsmodel->details($r_id);
        if (!$rows) {
            $this->utility->show404();
            return;
        }

        //validation check
        $this->form_validation->set_rules('row_label', 'Row Label', 'trim|required');
        $this->form_validation->set_rules('row_value', 'Value', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim');
        $this->form_validation->set_rules('model_no', 'Model No.', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['rows'] = $rows;
            $page['content'] = $this->load->view('options/rows/row-edit', $inner, TRUE);
            $this->load->view('shell', $page);
        } else {
            $this->Rowsmodel->updateRecord($rows);

            $this->session->set_flashdata('SUCCESS', 'option_row_updated');

            redirect("cpcatalogue/option_rows/index/{$rows['option_id']}");
            exit();
        }
    }

}

?>