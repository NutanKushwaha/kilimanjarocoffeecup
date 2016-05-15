<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Alternative_product extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function validAlternativeProduct($str) {
        $this->db->where('alternative_product_id', $str);
        $this->db->where('product_id', $this->input->post('product_id', TRUE));
        $query = $this->db->get('alternative_product');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('validAlternativeProduct', 'Alter-native Product Already Exists!');
            return false;
        }
        return TRUE;
    }

    function index($pid = false) {
        if (!$this->flexi_auth->is_privileged('View Alternative Products')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Alternative Products.</p>');
            redirect('dashboard');
        }
        
        $this->load->model('Productmodel');
        $this->load->model('Alternativeproductmodel');
        $this->load->helper('text');
        

        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product || !$pid) {
            $this->utility->show404();
            return;
        }

        $alternative_products = array();
        $alternative_products = $this->Alternativeproductmodel->getProductList($product['product_sku']);
        //print_r($alternative_products); exit();

        $inner = array();
        $inner['product'] = $product;

        $inner['alternative_products'] = $alternative_products;
        $inner['mod_menu'] = 'layout/inc-menu-catalog';

        $page = array();
        $page['content'] = $this->load->view('alternative-product/listing', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    function add($pid = FALSE) {
        if (!$this->flexi_auth->is_privileged('Insert Alternative Products')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Alternative Products.</p>');
            redirect('dashboard');
        }
        $Searchtree = $this->Commonmodel->categoriesSearchTree(0);
        $this->load->model('Productmodel');
        $this->load->model('Alternativeproductmodel');
        $this->load->helper('text');
        
        

        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }
        //print_r($product); exit();

        $products = array();
        $products = $this->Alternativeproductmodel->listAll($product['product_sku']);

        $options = array();

        foreach ($products as $item) {
            $options[$item['product_sku']] = $item['product_name'];
        }
        //print_r($options); exit();
        if (empty($this->input->post('alternative_product_name'))) {
            $this->form_validation->set_rules('alternative_product_name', 'Alternative Product', 'trim|required|callback_validAlternativeProduct');
        }
        $this->form_validation->set_rules('product_id', 'Product', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['searchTree'] = $Searchtree;
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $inner['product'] = $product;
            $inner['options'] = $options;
            $page = array();
            $page['content'] = $this->load->view('alternative-product/add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {

            $this->Alternativeproductmodel->insertRecord($product['product_sku']);
            $this->session->set_flashdata('SUCCESS', 'alternative_product_added');
            redirect("cpcatalogue/alternative_product/index/{$product['product_sku']}");
            exit();
        }
    }

    function delete($aid) {
        if (!$this->flexi_auth->is_privileged('Delete Alternative Product')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Alternative Product.</p>');
            redirect('dashboard');
        }

        $this->load->model('Alternativeproductmodel');

        $alternative_product = array();
        $alternative_product = $this->Alternativeproductmodel->getDetails($aid);

        if (!$alternative_product) {
            $this->utility->show404();
            return;
        }

        $this->Alternativeproductmodel->deleteRecord($alternative_product);

        $this->session->set_flashdata('SUCCESS', 'alternative_product_deleted');
        redirect("cpcatalogue/alternative_product/index/{$alternative_product['product_id']}");
        exit();
    }

}

?>