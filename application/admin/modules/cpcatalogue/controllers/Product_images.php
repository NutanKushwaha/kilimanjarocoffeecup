<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_images extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    //validation for product image thumbnail
    function valid_images($str) {
        if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
            $this->form_validation->set_message('valid_images', 'The Category Image field is required.');
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

    function index($pid = false) {
        $this->load->model('Imagesmodel');
        $this->load->model('Productmodel');
        
        $this->load->helper('text');
        

        //Get Product Detail
        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }
        //print_r($product); exit();
        //list all attributes
        $images = array();
        $images = $this->Imagesmodel->listAll($product['product_sku']);

        //render view
        $inner = array();
        $inner['images'] = $images;
        $inner['mod_menu'] = 'layout/inc-menu-catalog';
        $inner['product'] = $product;
        $inner['pagination'] = $this->pagination->create_links();
        $page = array();
        $page['content'] = $this->load->view('images/images-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    //function add product images
    function add($pid = false) {
        $this->load->model('Imagesmodel');
        $this->load->model('Productmodel');
        
        

        //Get Product Detail
        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }
        //print_r($product); exit();
        //validation check
        $this->form_validation->set_rules('image_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('v_image', 'Product Image', 'trim|required|callback_valid_images');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['product'] = $product;
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $page = array();
            $page['content'] = $this->load->view('images/image-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Imagesmodel->insertRecord($product);
			$forAllProducts = $this->input->post('img_products', true);
			
            /*
			if($forAllProducts){				
				$this->updateAllProductsImage($product);				
			}*/
            
            $this->session->set_flashdata('SUCCESS', 'image_added');

            redirect("cpcatalogue/product_images/index/{$product['product_id']}");
            exit();
        }
    }

    //function edit product images
    function edit($image_id) {
        $this->load->model('Imagesmodel');
        
        

        //get image details
        $image = array();
        $image = $this->Imagesmodel->getDetails($image_id);
        if (!$image) {
            $this->utility->show404();
            return;
        }

        $this->form_validation->set_rules('image_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('v_image', 'Product Image', 'trim|required|callback_validImage');


        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['image'] = $image;
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $page = array();
            $page['content'] = $this->load->view('images/image-edit', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Imagesmodel->updateRecord($image);

            $this->session->set_flashdata('SUCCESS', 'image_updated');

            redirect("cpcatalogue/product_images/index/{$image['product_id']}");
            exit();
        }
    }

    function delete($image_id) {
        $this->load->model('Imagesmodel');
        
        

        //get image details
        $image = array();
        $image = $this->Imagesmodel->getDetails($image_id);
        if (!$image) {
            $this->utility->show404();
            return;
        }

        $this->Imagesmodel->deleteImage($image);
        $this->session->set_flashdata('SUCCESS', 'image_deleted');
        redirect("cpcatalogue/product_images/index/{$image['product_id']}");
        exit();
    }

    function set_main($image_id) {
        $this->load->model('Imagesmodel');
        $this->load->model('Productmodel');
        
        

        //get image details
        $image = array();
        $image = $this->Imagesmodel->getDetails($image_id);
        if (!$image) {
            $this->utility->show404();
            return;
        }

        $set_main_image = array();
        $set_main_image = $this->Imagesmodel->setMainImage($image);
        redirect("cpcatalogue/product_images/index/{$image['product_id']}");
        exit();
    }

	function updateAllProductsImage($category){
		$this->load->model('Productmodel');		
		$this->load->model('Imagesmodel');		
		$_POST['category'] = 'category';        
        $search_param = [];
        $search_param[ 'category' ] = $category['category_id'];
		$allProducts =  $this->Productmodel->listAllProducts( $search_param );
		foreach($allProducts as $product){
			$this->Imagesmodel->insertRecord($product);
		}
	}	
}

?>
