<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Related_department extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
        $this->load->model('Relatedproddepartmentmodel');
    }

    function validRelatedDepartment($str) {
        $this->db->where('related_department_name', $str);
        $this->db->where('product_id', $this->input->post('product_id', TRUE));
        $query = $this->db->get('related_department');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('validRelatedDepartment', 'Related Department Already Exists!');
            return false;
        }
        return TRUE;
    }

    function index($pid) {
        $this->load->model('Productmodel');        
        $this->load->helper('text');
        

        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }

        $related_departments = array();
        $related_departments = $this->Relatedproddepartmentmodel->getProductList($product['product_id']);

        $inner = array();
        $inner['product'] = $product;
        $inner['related_departments'] = $related_departments;
        $inner['mod_menu'] = 'layout/inc-menu-catalog';
        $page = array();
        $page['content'] = $this->load->view('related-department/listing', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    function add($pid = FALSE) {
        $this->load->model('Productmodel');
        $this->load->helper('text');
        
        

        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }
        //print_r($product); exit();

        $products = array();
        $products = $this->Relatedproddepartmentmodel->listAll($product['product_id']);

        $options = array();
        $options[''] = '-Select-';
        foreach ($products as $item) {
            $options[$item['id']] = $item['name'];
        }
        //print_r($options); exit();

        $this->form_validation->set_rules('related_department_name', 'Related Department', 'trim|required|callback_validRelatedDepartment');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['product'] = $product;
            $inner['options'] = $options;
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $page = array();
            $page['content'] = $this->load->view('related-department/add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Relatedproddepartmentmodel->insertRecord($product['product_id']);

            $this->session->set_flashdata('SUCCESS', 'related_department_added');
            redirect("cpcatalogue/related_department/index/{$product['product_id']}");
            exit();
        }
    }

    function delete($rid) {
        $related_department = array();
        $related_department = $this->Relatedproddepartmentmodel->getDetails($rid);
        if (!$related_department) {
            $this->utility->show404();
            return;
        }

        $this->Relatedproddepartmentmodel->deleteRecord($related_department);

        $this->session->set_flashdata('SUCCESS', 'related_department_deleted');
        redirect("cpcatalogue/related_department/index/{$related_department['product_id']}");
        exit();
    }

}

?>