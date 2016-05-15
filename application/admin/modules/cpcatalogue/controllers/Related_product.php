<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Related_product extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function validRelatedProduct($str) {
        $this->db->where('related_product_id', $str);
        $this->db->where('product_id', $this->input->post('product_id', TRUE));
        $query = $this->db->get('related_product');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('validRelatedProduct', 'Related Product Already Exists!');
            return false;
        }
        return TRUE;
    }

    function index($pid) {
        if (!$this->flexi_auth->is_privileged('View Related Products')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Related Products.</p>');
            redirect('dashboard');
        }

        $this->load->model('Productmodel');
        $this->load->model('Relatedproductmodel');
        $this->load->helper('text');
        

        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }

        $related_products = array();
        $related_products = $this->Relatedproductmodel->getProductList($product['product_sku']);
        //print_r($related_products); exit();

        $inner = array();
        $inner['product'] = $product;
        $inner['related_products'] = $related_products;
        $inner['mod_menu'] = 'layout/inc-menu-catalog';
        $page = array();
        $page['content'] = $this->load->view('related-product/listing', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    function add($pid = FALSE) {
        if (!$this->flexi_auth->is_privileged('Insert Related Products')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Related Products.</p>');
            redirect('dashboard');
        }

        $this->load->model('Productmodel');
        $this->load->model('Relatedproductmodel');
        $this->load->helper('text');
        
        

        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }

        $Searchtree = $this->Commonmodel->categoriesSearchTree(0);

        $products = array();
        $products = $this->Relatedproductmodel->listAll($product['product_sku']);

        $options = array();
        foreach ($products as $item) {
            $options[$item['product_sku']] = $item['product_name'];
        }
        //print_r($options); exit();

        if (empty($this->input->post('related_product_name'))) {
            $this->form_validation->set_rules('related_product_name', 'Related Product', 'trim|required|callback_validAlternativeProduct');
        }
        $this->form_validation->set_rules('product_id', 'Product', 'trim|required');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['searchTree'] = $Searchtree;
            $inner['product'] = $product;
            $inner['options'] = $options;
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $page = array();
            $page['content'] = $this->load->view('related-product/add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            //com_e($this->input->post());
            $this->Relatedproductmodel->insertRecord($product['product_sku']);
            $this->session->set_flashdata('SUCCESS', 'related_product_added');
            redirect("cpcatalogue/related_product/index/{$product['product_sku']}");
            exit();
        }
    }

    function delete($rid) {
        if (!$this->flexi_auth->is_privileged('Delete Related Product')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Related Product.</p>');
            redirect('dashboard');
        }

        $this->load->model('Relatedproductmodel');

        $related_product = array();
        $related_product = $this->Relatedproductmodel->getDetails($rid);
        if (!$related_product) {
            $this->utility->show404();
            return;
        }

        $this->Relatedproductmodel->deleteRecord($related_product);

        $this->session->set_flashdata('SUCCESS', 'related_product_deleted');
        redirect("cpcatalogue/related_product/index/{$related_product['product_id']}");
        exit();
    }

}

?>