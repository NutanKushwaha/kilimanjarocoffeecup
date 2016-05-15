<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;

        $this->load->model('Productmodel');
        $this->load->model('Imagesmodel');
        $this->load->model('CategoryModel');
        $this->load->model('Suppliermodel');
        $this->load->model('AttributeModel');
        $this->load->model('AttributesetModel');
        $this->load->model('company/Departmentmodel', 'Departmentmodel');
        //$this->load->model('RelatedprodDepartmentmodel');        
    }

//******************************************* validation start******************************************
    //validation for product image thumbnail
    function valid_images($str) {
        if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
            $this->form_validation->set_message('valid_images', 'Product Image Field is required.');
            return FALSE;
        }

        $imginfo = @getimagesize($_FILES['image']['tmp_name']);

        if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
            $this->form_validation->set_message('valid_images', 'Only GIF, JPG and PNG Images are accepted');
            return FALSE;
        }
        return TRUE;
    }

    //function for edit valid image
    function validImage($str) {
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

            $imginfo = @getimagesize($_FILES['image']['tmp_name']);
            if (!$imginfo) {
                $this->form_validation->set_message('validImage', 'Only image files are allowed');
                return false;
            }

            if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
                $this->form_validation->set_message('validImage', 'Only GIF, JPG and PNG Images are accepted.');
                return FALSE;
            }
        }
        return TRUE;
    }

    //****************************************** end of validation  ************************************************

    function index( $offset = 0 ) {
        if (! $this->flexi_auth->is_privileged('View Products')){
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Products.</p>'); 
            redirect('dashboard'); 
        }        
        $prodName = $this->input->post('prodName');
        $category_list = [];
        $category_list = $this->CategoryModel->indentedListTwo(0);        

        $options = [];
        $options[''] = '-ALL-';
        foreach ($category_list as $item) {
            $options[$item['category_id']] = str_repeat("&nbsp;", ($item['depth'] * 5)) . $item['category'];
        }
		
        //list all Products
        $search_param  =   [];
        $search_param[ 'category' ] =  $this->input->post('category');
        $search_param[ 'prodName' ] =  $this->input->post('prodName');

        //Setup pagination
        $perpage = 15;

        //render view
        $inner = [];
        $page = [];
        $offset     =   0;
        $perpage    =   15;
        $config['cur_page']         =   '0';
        $config['request_type']     =   'get';
        $config['per_page']         =   $perpage;
        $config['html_container']   =   'products-list-div';
        $config['base_url']         =    base_url().'cpcatalogue/ajax/product/';
        $config['total_rows']       =   $this->Productmodel->countAllProducts( $search_param );

        $config['additional_param'][]['js'] = ['category' => "$('#category').val()"];
        $config['additional_param'][]['js'] = ['prodName' => "$('#prodName').val()"];

        $inner['pagination'] = com_ajax_pagination($config);

        $search_param[ 'limit' ]    =  $perpage;
        $search_param[ 'offset' ]   =  $offset;        

        $products = [];
        $products = $this->Productmodel->listAllProducts( $search_param );

        // echo "<pre>";
        // print_r($products); exit;

        $inner['options'] = $options;
        $inner['products'] = $products;
        $inner['prodName'] = $prodName;
        $inner['category_list'] = $category_list;
        $inner['products_list_view'] = $this->load->view('products/ajax/products-list', $inner, TRUE);
        $page['content'] = $this->load->view('products/product-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    //Function Add Product
    function add() {
        if (! $this->flexi_auth->is_privileged('Insert Products')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Products.</p>'); 
            redirect('dashboard'); 
        }

        //fetch category dopdown
        $categories = [];
        $condition = [];
        $condition['where'] = array('parent_id' => 0);
        $relation = array('rlt_col' => 'parent_id', 'rlt_fld' => 'category_id');
        $categories = $this->CategoryModel->indentedListWithOptgrp($condition, $relation);
        $suppliers = [];
        $suppliers = $this->Suppliermodel->listAllWithBrands();
        $options = com_makelist($suppliers, 'supplier_id', 'supplier_name', TRUE, 'Select Supplier' );
        //validation check
        $this->form_validation->set_rules('category_id', 'Category', 'trim');
        $this->form_validation->set_rules('product_type_id', 'Product type', 'trim|required');
        $this->form_validation->set_rules('product_name[]', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('product_alias[]', 'URL Alias', 'trim|strtolower');
        $this->form_validation->set_rules('product_sku[]', 'SKU', 'trim');
        $this->form_validation->set_rules('product_price[]', 'Price', 'trim');
        $this->form_validation->set_rules('supplier_id', 'Supplier Name', 'trim');
        $this->form_validation->set_rules('branch_id', 'Branch Name', 'trim');
        $this->form_validation->set_rules('stock_level[]', 'Stock Level', 'trim|numeric');
        $this->form_validation->set_rules('weight[]', 'Weight', 'trim');
        $this->form_validation->set_rules('product_description', 'Description', 'trim');
        $this->form_validation->set_rules('product_meta_title', 'Meta Title', 'trim');
        $this->form_validation->set_rules('product_meta_keywords', 'Meta Keywords ', 'trim');
        $this->form_validation->set_rules('product_meta_description', 'Meta Description', 'trim');
        $this->form_validation->set_rules('is_featured', 'Is Featured', 'trim');
        $this->form_validation->set_rules('new_to', 'New from', 'trim');
        $this->form_validation->set_rules('new_from', 'New to', 'trim');
		$this->form_validation->set_rules('technical_detail', 'Technical Detail', 'trim');
        $this->form_validation->set_rules('attribute_set_id', 'Attribute Set', 'trim|required');        

        $condition = [];
        if($this->input->post('attribute_set_id', true)){
            $set_id = $this->input->post('attribute_set_id', true);
            $condition['select'] = 'attributes_set_attributes.*,attributes_type.type,attributes_set_attributes_option.id as option_id,attributes_set_attributes_option.option_text';
            $condition['where']['set_id'] = $set_id;
            $condition['join'][] = array('tbl' => 'attributes_set_attributes_option', 'cond' => 'attributes_set_attributes_option.attribute_id=attributes_set_attributes.id', 'type' => 'left');
            $condition['join'][] = array('tbl' => 'attributes_type', 'cond' => 'attributes_type.id=attributes_set_attributes.type', 'type' => 'inner');        
            $attr_list = $this->AttributeModel->get_all($condition);
        }else{
            $condition['min'] = 'id';
            $condition['result'] = 'row';
            $set_result = $this->AttributesetModel->get_all($condition);
            $set_id = $set_result['id'];
            $attr_list = [];            
        }
        $inner = [];
        $inner['set_id'] = $set_id;
        $attr_opt = [];
        $new_attr_list = [];
        $attr_key_comb = [];       

        foreach ($attr_list as $key => $value) {
            $new_attr_list[$value['id']] = $value;
            $attr_key_comb[$value['id']] = $value['type'];
            if(in_array($value['type'], array('MULTISELECT', 'DROPDOWN'))){
                if(!empty($value['option_id'])){
                    $attr_opt[$value['id']]['0'] = 'Select';
                    $attr_opt[$value['id']][$value['option_id']] = $value['option_text'];
                }
            }
        }

        foreach ($new_attr_list as $key => $val) {
            if($val['is_sys']){
                continue;
            }
            $rules = [];
            $rules[] = 'trim';
            if($val['required']){
                $rules[] = 'required';
            }
            if($val['is_numeric']){
                $rules[] = 'numeric';
            }
            $rules = implode('|', $rules);            
            $this->form_validation->set_rules('attribute['.$val['id'].']', $val['label'], $rules);
        }
        $inner['attr_opt'] = $attr_opt;
        $inner['attr_list'] = $new_attr_list;
        $this->form_validation->set_error_delimiters('<li>', '</li>');        
        if ($this->form_validation->run() == FALSE) {
            $page = [];
            $this->editor('600px');            
            $inner['categories'] = $categories;
            $inner['options'] = $options;
            $inner['attribute_set'] = com_makelist($this->AttributesetModel->get_all(), 'id', 'set_name');
            $page['content'] = $this->load->view('products/product-add-wizard', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {            
            $this->Productmodel->insertRecord();
            $this->session->set_flashdata('SUCCESS', 'product_added');
            redirect("cpcatalogue/product");
            exit();
        }
    }

    //Function Edit Product
    function edit($pid = false) {
        if (! $this->flexi_auth->is_privileged('Update Product')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update Product.</p>'); redirect('dashboard'); }

        //Get Product Detail
        $product = [];
        $product = $this->Productmodel->details($pid, 1);

        if (!$product) {
            $this->utility->show404();
            return;
        }

        $categories = [];
        $condition = [];
        $condition['where'] = array('parent_id' => 0);
        $relation = array('rlt_col' => 'parent_id', 'rlt_fld' => 'category_id');
        $categories = $this->CategoryModel->indentedListWithOptgrp($condition, $relation);        

        $suppliers = [];
        $options = [];
        //$suppliers = $this->Suppliermodel->listAllWithBrands();
        //$options = com_makelist($suppliers, 'supplier_id', 'supplier_name', TRUE, 'Select Supplier' );
        $brand_opt = [];

        foreach( $suppliers as $index => $supplier_det){
			if( $supplier_det[ 'supplier_id' ] == $product[ 'supplier_id' ] && $supplier_det[ 'id' ] !== NULL ){
				$brand_opt[ $supplier_det[ 'id' ] ] = $supplier_det[ 'brand_name' ];
			}
		}
        //Attributes
        $attributes = [];        
        $attributes_rs = $this->Productmodel->fetchAttributes($product['attribute_set_id']);        
        foreach ($attributes_rs as $attr) {
            $attributes[] = $attr;
        }        

        //Attribute values
        $attribute_values = $this->Productmodel->fetchAttributeValues($product['product_id']);
        //print_r($attribute_values); exit();                
        /*
        *Fetch Tier Prices
        *Not in use
        $productTierPrices = $this->Productmodel->getTierPricesByProductId($product['product_id']);
        */
        
        
        //validation check
        $this->form_validation->set_rules('category_id', 'Category', 'trim');
        $this->form_validation->set_rules('product_name[]', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('product_alias[]', 'URL Alias', 'trim|strtolower');
        $this->form_validation->set_rules('product_sku[]', 'SKU', 'trim');
        $this->form_validation->set_rules('product_price[]', 'Price', 'trim');
        $this->form_validation->set_rules('supplier_id', 'Supplier Name', 'trim');
        $this->form_validation->set_rules('branch_id', 'Branch Name', 'trim');
        $this->form_validation->set_rules('stock_level[]', 'Stock Level', 'trim');
        $this->form_validation->set_rules('weight[]', 'Weight', 'trim');
        $this->form_validation->set_rules('product_description', 'Description', 'trim');
        $this->form_validation->set_rules('product_meta_title', 'Meta Title', 'trim');
        $this->form_validation->set_rules('product_meta_keywords', 'Meta Keywords ', 'trim');
        $this->form_validation->set_rules('product_meta_description', 'Meta Description', 'trim');
        $this->form_validation->set_rules('is_featured', 'Is Featured', 'trim');        
        $this->form_validation->set_rules('new_to', 'New from', 'trim');
        $this->form_validation->set_rules('new_from', 'New to', 'trim');
        $condition = [];
        
        $set_id = $product['attribute_set_id'];
        $condition['select'] = 'attributes_set_attributes.*,attributes_type.type,attributes_set_attributes_option.id as option_id,attributes_set_attributes_option.option_text';
        $condition['where']['set_id'] = $set_id;
        $condition['join'][] = array('tbl' => 'attributes_set_attributes_option', 'cond' => 'attributes_set_attributes_option.attribute_id=attributes_set_attributes.id', 'type' => 'left');
        $condition['join'][] = array('tbl' => 'attributes_type', 'cond' => 'attributes_type.id=attributes_set_attributes.type', 'type' => 'inner');        
        $attr_list = $this->AttributeModel->get_all($condition);
        
        $inner = [];
        $inner['set_id'] = $set_id;
        $attr_opt = [];
        $new_attr_list = [];
        $attr_key_comb = [];       

        foreach ($attr_list as $key => $value) {
            $new_attr_list[$value['id']] = $value;
            $attr_key_comb[$value['id']] = $value['type'];
            if(in_array($value['type'], array('MULTISELECT', 'DROPDOWN'))){
                if(!empty($value['option_id'])){
                    $attr_opt[$value['id']]['0'] = 'Select';
                    $attr_opt[$value['id']][$value['option_id']] = $value['option_text'];
                }
            }
        }

        $available_attr = [];
        foreach ($new_attr_list as $key => $val) {
            if($val['is_sys']){
                continue;
            }
            $available_attr[] = $val;
            $rules = [];
            $rules[] = 'trim';
            if($val['required']){
                $rules[] = 'required';
            }
            if($val['is_numeric']){
                $rules[] = 'numeric';
            }
            $rules = implode('|', $rules);            
            $this->form_validation->set_rules('attribute['.$val['id'].']', $val['label'], $rules);
        }
        $inner['product'] = $product;
        $inner['attr_opt'] = $attr_opt;
        $inner['new_attr_list'] = $new_attr_list;
        $inner['attr_list'] = $available_attr;
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {

            $inner['categories'] = $categories;
            $inner['product'] = $product;
            $inner['options'] = $options;
            $inner['attributes'] = $attributes;
            $inner['attribute_values'] = $attribute_values;
            $inner[ 'suppliers'] = $suppliers;
            $inner[ 'brand_opt'] = $brand_opt;
            //com_e($inner);
            if($product['product_type_id'] == 1){  
                
                $inner['sub_view'] = $this->load->view('products/edit-general-field', $inner, true);
            }else if($product['product_type_id'] == 2){
                
                $inner['config_attrib'] = $this->getAttributeForSetId($product['attribute_set_id'] , $product['product_id']);
                $inner['sub_view'] = $this->load->view('products/edit-config-field', $inner, true);
            }            
            $page = [];
            $page['content'] = $this->load->view('products/product-edit-wizard', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
                
            $this->Productmodel->updateRecord($product);
            $this->session->set_flashdata('SUCCESS', 'product_updated');
            redirect("cpcatalogue/product");
            exit();
        }
    }

    //Function Delete product
    function delete($pid = false) {
        if (! $this->flexi_auth->is_privileged('Delete Product')){ 
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Product.</p>'); 
			redirect('dashboard'); 
		}

        $this->load->model('Productmodel');
        //get product detail
        $product = [];
        $product = $this->Productmodel->details($pid);
        if (!$product) {
			$opt = [];
			$opt[ 'top_text' ] = 'Product not found';
			$opt[ 'bottom_text' ] = 'Product could not found';
            $this->utility->show404();
            return;
        }
        
        $configProducts = [];
		if( $product[ 'product_type_id' ] == 2){
			$configProducts = $this->Productmodel->fetchConfigProduct( $product[ 'product_id' ] );
		}
		
        $inner = [];
        $page = [];
        $inner['product_id'] = $product[ 'product_id' ];
        $inner['product'] = $product;
        $inner['configProducts'] = $configProducts;        
        $page['content'] = $this->load->view('products/product-delete', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }
    
	//Function Delete config product
	function product_delete_config(){
		$ref_product_id = $this->input->post( 'ref_product_id' );
		$config_products = $this->input->post( 'config_products' );
		if( $ref_product_id &&  $config_products){
			foreach( $config_products as  $stIndex => $stDet ){
				$opt = [];
				$opt[ 'result' ] = 'row';
				$opt[ 'where' ][] = [ '0' => 'product_id', '1' => $stDet ];
				$opt[ 'where' ][] = [ '0' => 'ref_product_id', '1' => $ref_product_id ];
				$product = $this->Productmodel->getProduct( "", $opt);
				if ($product) {
					$this->Productmodel->deleteProduct($product);
				}
			}
			$this->session->set_flashdata('SUCCESS', 'product_deleted');
			$url = 'cpcatalogue/product/index';
			if($product){
				$url = 'cpcatalogue/product/index/'.$product['category_id'];
			}
			redirect($url, 'location');
			exit();								
		}
	}
	
//Function Delete product
    function adctive($pid = false, $act = 0) {
        if (! $this->flexi_auth->is_privileged('Active Deactive Product')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Active Deactive Product.</p>'); redirect('dashboard'); }

        $this->load->model('Productmodel');
        
        

        //get product detail
        $product = [];
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }

		$this->Productmodel->disableRecord($product, $act);
		
        $url = 'cpcatalogue/product/index';

        /*
        if($product){
			$url = 'cpcatalogue/product/index/'.$product['category_id'];
		}*/

        redirect($url, 'location');
        exit();
    }
    
    function product_delete($pid = FALSE) {
        //function delete
        $this->load->model('Productmodel');
        $this->load->model('cpcatalogue/Imagesmodel');
        
        

        //get product detail
        $product = [];
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }

        $this->Productmodel->deleteProduct($product);

        $this->session->set_flashdata('SUCCESS', 'product_deleted');
        $url = 'cpcatalogue/product/index';
        if($product){
			$url = 'cpcatalogue/product/index/'.$product['category_id'];
		}
        redirect($url, 'location');
        exit();
    }

    function getFields(){
        $this->editor('580px');
        $data = [];
        $data['success'] = '0';
        $prod_type = $this->input->get('prod_type', true);
        $attribute_set = $this->input->get('set_id', true);        
        if($attribute_set and $prod_type){
            $inner = [];
            $data['success'] = '1';
            $categories = [];
            $condition = [];
            $condition['where'] = array('parent_id' => 0);
            $relation = array('rlt_col' => 'parent_id', 'rlt_fld' => 'category_id');
            $categories = $this->CategoryModel->indentedListWithOptgrp($condition, $relation);
			$suppliers = [];
            $options=[];
			//$suppliers = $this->Suppliermodel->listAllWithBrands();			
			//$options = com_makelist($suppliers, 'supplier_id', 'supplier_name', TRUE, 'Select Supplier' );            
            $inner['options'] = $options;
            $inner['categories'] = $categories;
            
            $condition = [];
            $set_id = $attribute_set;
            $condition['select'] = 'attributes_set_attributes.*,attributes_type.type,attributes_set_attributes_option.id as option_id,attributes_set_attributes_option.option_text';
            $condition['where']['set_id'] = $set_id;
            $condition['where']['is_sys'] = '0';
            $condition['join'][] = array('tbl' => 'attributes_set_attributes_option', 'cond' => 'attributes_set_attributes_option.attribute_id=attributes_set_attributes.id', 'type' => 'left');
            $condition['join'][] = array('tbl' => 'attributes_type', 'cond' => 'attributes_type.id=attributes_set_attributes.type', 'type' => 'inner');
            $attr_list = $this->AttributeModel->get_all($condition);
            


            $inner['set_id'] = $set_id;
            $attr_opt = [];
            $new_attr_list = [];
            $attr_key_comb = [];

            foreach ($attr_list as $key => $value) {
                $new_attr_list[$value['id']] = $value;
                $attr_key_comb[$value['id']] = $value['type'];
                if(in_array($value['type'], array('MULTISELECT', 'DROPDOWN'))){
                    if(!empty($value['option_id'])){
                        $attr_opt[$value['id']]['0'] = 'Select';
                        $attr_opt[$value['id']][$value['option_id']] = $value['option_text'];
                    }
                }
            }			
            $inner['attr_opt'] = $attr_opt;
            $inner['attr_list'] = $new_attr_list;
            $inner[ 'suppliers'] = $suppliers;
            
            if($prod_type == 1){
                $data['html'] = $this->load->view('products/ajax/general-field', $inner, true);
            }else if($prod_type == 2){
                $config_attrib = $this->getAttributeForSetId($set_id);
                $inner['config_attrib'] = $config_attrib;
                $data['html'] = $this->load->view('products/ajax/config-field', $inner, true);
            }
        }
        echo json_encode($data);
        exit();
    }

    private function getAttributeForSetId($set_id, $product_id = 0){    
        $this->load->model('AttributeModel');
        $condition = [];
        $condition['select'] = 'attributes_set_attributes.*,attributes_type.type,attributes_set_attributes_option.id as option_id,attributes_set_attributes_option.option_text';
        $condition['where']['set_id'] = $set_id;
        $condition['join'][] = array('tbl' => 'attributes_set_attributes_option', 'cond' => 'attributes_set_attributes_option.attribute_id=attributes_set_attributes.id', 'type' => 'left');
        $condition['join'][] = array('tbl' => 'attributes_type', 'cond' => 'attributes_type.id=attributes_set_attributes.type', 'type' => 'inner');        
        $attr_list = $this->AttributeModel->get_all($condition);
        $attr_opt = [];
        $new_attr_list = [];
        $attr_key_comb = [];       

        foreach ($attr_list as $key => $value) {
            $new_attr_list[$value['id']] = $value;
            $attr_key_comb[$value['id']] = $value['type'];
            if(in_array($value['type'], array('MULTISELECT', 'DROPDOWN'))){
                if(!empty($value['option_id'])){
                    $attr_opt[$value['id']]['0'] = 'Select';
                    $attr_opt[$value['id']][$value['option_id']] = $value['option_text'];
                }
            }
        }
        $inner['attr_opt'] = $attr_opt;
        $inner['attr_list'] = $new_attr_list;        
        if($product_id){
            $inner['config_product'] = $this->Productmodel->fetchConfigProduct($product_id);            
            return $this->load->view('attributes/sub-page/for-product-edit-accord-listing', $inner, true);
        }else{			
            return $this->load->view('attributes/sub-page/for-product-accord-listing', $inner, true);
        }        
    }    

    function getdescription($pid){
        $this->editor('600px');
        $product = [];
        $product = $this->Productmodel->details($pid);
        $output = [];

        if (!$product) {
            $default_value = '';
        }else{
            $default_value =  $product['product_description'];
        }   
        echo $this->ckeditor->editor('product_description',@$default_value);
        exit();
    }
    
	function allImages($offset = 0){
		
		$page = [];
		$inner = [];				
		
		
		//Setup pagination
        $perpage = 500;
        $config['base_url'] = base_url() . "cpcatalogue/product/allImages/";
        $config['uri_segment'] = 4;
        $config['total_rows'] = $this->Productmodel->countAllProdImages();
        
        $config['per_page'] = $perpage;        
        $config['num_links'] = 5;
        $this->pagination->initialize($config);
		
		$inner['prod_images'] = $this->Productmodel->listAllProdImages($offset, $perpage);
		$inner['total_rows'] = $config['total_rows'];
		$inner['offset'] = $offset;
		$inner['pagination'] = $this->pagination->create_links();
        $page['content'] = $this->load->view('products/img-listing', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
	}
	
	function removeImage($prod_id = null) {
        $product = [];
        $product = $this->Productmodel->details($prod_id);

        if (!$product) {
            $this->session->set_flashdata('ERROR', 'prod_nfound');
        }else{
            $this->Productmodel->removeImage($product);
            $this->session->set_flashdata('SUCCESS', 'image_removed');            
        }
        echo 'done';
        exit();
    }    

    private function product_attributes($pid){
        $this->load->helper('text');
        
        
        $this->load->model('Productmodel');
        $this->load->model('supplier/Suppliermodel');
        $this->load->model('CategoryModel');
        $inner = [];
        //Get Product Detail
        $product = [];
        $product = $this->Productmodel->details($pid);

        if (!$product) {
            $this->utility->show404();
            return;
        }

        

        $inner['product'] = $product;
        $inner['mod_menu'] = 'layout/inc-menu-catalog';

        $inner['product_categories'] = $product_categories;
        //Attributes
        $attributes = [];        
        $attributes_rs = $this->Productmodel->fetchAttributes($product_categories['attribute_set_id']);
        $inner['attributes'] = $attributes_rs;
        foreach($attributes_rs as $key => $val){
            $rules = [];
            $rules[] = 'trim';
            if($val['required']){
                $rules[] = 'required';
            }
            if($val['is_numeric']){
                $rules[] = 'numeric';
            }
            $rules = implode('|', $rules);            
            $this->form_validation->set_rules('attribute['.$val['id'].']', $val['label'], $rules);
        }
        //Attribute values
        $attribute_values = $this->Productmodel->fetchAttributeValues($product['product_id']);
        $inner['attribute_values'] = $attribute_values;
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {            
            $page = [];
            $page['content'] = $this->load->view('products/product-attribute', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        }else{
            $this->Productmodel->deleteAttributes($pid);
            $param = [];
            $param['product_id'] = $pid;
            $this->Productmodel->insertAttributes($param);
            $this->session->set_flashdata('SUCCESS', 'product_attributes_updated');
            redirect("cpcatalogue/product", 'location');
            exit();        
        }        
    }

    function config_product($pid){
        $this->load->helper('text');
        
        
        $this->load->model('Productmodel');
        $this->load->model('supplier/Suppliermodel');
        $this->load->model('CategoryModel');
        $inner = [];
        //Get Product Detail
        $product = [];
        $product = $this->Productmodel->details($pid);

        if (!$product) {
            $this->utility->show404();
            return;
        }        
        e('Pending config products', 1);
    }

    function check_unique(){
        $data = [];
        $data['success'] = '0';
        $data['unique'] = '';        
        $fld = $this->input->get('fld', true);
        $val = $this->input->get('val', true);
        $sys = $this->input->get('sys_attr', true);
        $product_id = $this->input->get('product_ref', true);        
        if($fld && $val){
            $param = [];
            $param['sys'] = $sys;
            $param['field'] = $fld;
            $param['value'] = $val;
            $param['product_id'] = $product_id;

            $is_unique = $this->Productmodel->checkAttrUnique($param);
            $data['success'] = '1';
            $data['unique'] = $is_unique;
        }
        echo json_encode($data);
        exit();
    }
}

?>
