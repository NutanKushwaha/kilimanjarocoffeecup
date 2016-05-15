<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utilities extends Admin_Controller {

	private $_uploaded;
	private $_prod_images;
	private $_prod_img_dim;
	
    function __construct() {
        parent::__construct();
        if (! $this->flexi_auth->is_privileged('Utilities')){ 
			$this->utility->show404();
            return;
    	}
        $this->_prod_images = [];
        $this->_prod_images[ ] = [
								'Image' => "Image",
								'Search For SKU' 	=> 'Search For SKU',
								'Product Exist' 	=> 'Product Exist',
								'Product Updated'	=> 'Product Updated',
								'Image Uploaded' 	=> 'Image Uploaded',
								];
        $this->_prod_img_dim = [
									0 => [ 'width' => 50, 'height' => 50],
									1 => [ 'width' => 197, 'height' => 134],
									2 => [ 'width' => 200, 'height' => 200],
									3 => [ 'width' => 230, 'height' => 200],
									4 => [ 'width' => 250, 'height' => 180],
									5 => [ 'width' => 250, 'height' => 250],
									6 => [ 'width' => 350, 'height' => 500],
									6 => [ 'width' => 600, 'height' => 600],
								];
    }

    function users_csv() {

		/*
        if (!$this->flexi_auth->is_privileged('View CSV')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Products.</p>');
            redirect('dashboard');
        }
        */
        if (empty($_FILES['csv']['name'])) {
            $this->form_validation->set_rules('file', 'csv', 'trim|required');
        }
        
        $this->form_validation->set_rules('csvfile', 'csv', 'trim|required');
        $inner = array();
        $page = array();
        if ($this->form_validation->run() == FALSE) {
			$inner[ 'post_size' ] = ini_get('upload_max_filesize');
            $page['content'] = $this->load->view('user-csv-upload', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $config = array();
            $config['file_name'] = $_FILES['csv']['name'];
            $config['upload_path'] = $this->config->item('USER_CSV_PATH');
            $config['allowed_types'] = 'csv';
            $config['overwrite'] = false;
            $config['encrypt_name'] = TRUE;            
			$config['file_size']  = 7168;
            $this->load->library('upload', $config);
            if (count($_FILES) > 0) {
				if (!$this->upload->do_upload('csv')) {
					show_error($this->upload->display_errors('<p class="err">', '</p>'));
					return FALSE;
				} else {
					$upload_data = $this->upload->data();					
					$server_file_name = $upload_data['file_name'];
					$this->load->model('CsvUtilitymodel');
					if (file_exists($this->config->item('USER_CSV_PATH') . $server_file_name)) {
						$csvfile = $this->CsvUtilitymodel->readFile( $this->config->item('USER_CSV_PATH') . $server_file_name );						
						$csvoperations = $this->CsvUtilitymodel->userUpload($csvfile);
					} else {
						show_error("File is not readable" );
						return FALSE;
					}
				}
            }            
        }
    }
    
    function prod_csv() {

		/*
        if (!$this->flexi_auth->is_privileged('View CSV')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Products.</p>');
            redirect('dashboard');
        }
        */
        if (empty($_FILES['csv']['name'])) {
            $this->form_validation->set_rules('file', 'csv', 'trim|required');
        }
        
        $this->form_validation->set_rules('csvfile', 'csv', 'trim|required');
        $inner = array();
        $page = array();
        if ($this->form_validation->run() == FALSE) {
			$inner[ 'post_size' ] = ini_get('upload_max_filesize');
            $page['content'] = $this->load->view('prod-csv-upload', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $config = array();
            $config['file_name'] = $_FILES['csv']['name'];
            $config['upload_path'] = $this->config->item('PROD_CSV_PATH');
            $config['allowed_types'] = 'csv';
            $config['overwrite'] = false;
            $config['encrypt_name'] = TRUE;            
			$config['file_size']  = 7168;
            $this->load->library('upload', $config);
            if (count($_FILES) > 0) {
				if (!$this->upload->do_upload('csv')) {
					show_error($this->upload->display_errors('<p class="err">', '</p>'));
					return FALSE;
				} else {
					$upload_data = $this->upload->data();					
					$server_file_name = $upload_data['file_name'];
					$csv_log = [];
					$csv_log[ 'upload_file_name' ] = $_FILES['csv']['name'];
					$csv_log[ 'server_file_name' ] = $server_file_name;
					$csv_log[ 'upload_date_time' ] = com_getDTFormat( 'mdatetime');
					$upload_csv_id = $this->db->insert( 'product_csv_upload_log', $csv_log);
					$this->load->model('CsvUtilitymodel');
					if (file_exists($this->config->item('PROD_CSV_PATH') . $server_file_name)) {
						$csvfile = $this->CsvUtilitymodel->readFile( $this->config->item('PROD_CSV_PATH') . $server_file_name );
						$csvoperations = $this->CsvUtilitymodel->CsvOpertations($csvfile);
					} else {
						show_error("File is not readable" );
						return FALSE;
					}
				}
            }            
        }
    }

	public function prod_images() {		
		$data['title'] = 'Multiple file upload';		
		//~ let's consider that the form would come with more fields than just the files to be uploaded. 
		//~ If this is the case, we would need to do some sort of validation. If we are talking about images, 
		//~ the only method of validation for us would be to put the upload process inside a validation callback;		 
		//now we set a callback as rule for the upload field
		$this->form_validation->set_rules('report_name','Report Name','trim|required|max_length[255]');		
		$this->form_validation->set_rules('uploadedimages[]','Upload image','callback_valid_fileupload');		
		if($this->input->post()) {
		  //run the validation
		  if($this->form_validation->run())
		  {
			$report_name = $this->input->post( 'report_name' );
			$report_name = str_replace(" ","-", $report_name);
			$report_name = strtolower( $report_name ).'.csv';
			com_array_to_csv($this->_prod_images, $report_name);
		  }
		} else {
			$page['content'] = $this->load->view('prod-img-upload', $data, TRUE);
			$this->load->view($this->template['default'], $page); 
		}		
	 }
	 
	// now the callback validation that deals with the upload of files
	function valid_fileupload() {
		$this->load->model('cpcatalogue/Productmodel', 'Productmodel');
		// we retrieve the number of files that were uploaded
		$number_of_files = sizeof($_FILES['uploadedimages']['tmp_name']);		
		if( $number_of_files ){
			$files = $_FILES['uploadedimages'];			
			$this->load->library('upload');			
			$config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH');			
			$config['allowed_types'] = 'gif|jpg|png';
			$config['encrypt_name']  =  true;			
			for ($i = 0; $i < $number_of_files; $i++){
				$name = trim( $files['name'][$i] );
				$sku_exist	= false;
				$img_load  	= false;
				$sku_for  	= false;
				$img_load_error  = "";
				$prod_db_updated  = false;
				$file_name = pathinfo($name, PATHINFO_FILENAME);
				$extension = pathinfo($name, PATHINFO_EXTENSION);
				$product_id = $this->Productmodel->checkProductBySku( $file_name );
				$sku_for  	= $file_name;
				if( $name  && $product_id ){
					$sku_exist = true;
					$_FILES['uploadedimage']['name'] = $files['name'][$i];
					$_FILES['uploadedimage']['type'] = $files['type'][$i];
					$_FILES['uploadedimage']['tmp_name'] = $files['tmp_name'][$i];
					$_FILES['uploadedimage']['error'] = $files['error'][$i];
					$_FILES['uploadedimage']['size'] = $files['size'][$i];
			  
					//now we initialize the upload library
					$this->upload->initialize($config);
					if ($this->upload->do_upload( 'uploadedimage' )) {
						$this->_uploaded[$i] = $this->upload->data();
						$img_load  = true;
						$product_image	=	$this->_uploaded[$i]['file_name'];
						foreach( $this->_prod_img_dim as $stIndx => $stDet ){
							$params = [
									'image_url' => $this->config->item('PRODUCT_IMAGE_URL') . $product_image,
									'image_path' => $this->config->item('PRODUCT_IMAGE_PATH') . $product_image,
									'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
									'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
									'width' => $stDet[ 'width' ],
									'height' => $stDet[ 'height' ],
									];
							$new_image_url = resize($params);
						}
						if( $product_image && $product_id ){
							$prod_update = [];
							$prod_update[ 'product_image' ] = $product_image;
							$update_status = $this->db->where('product_id' , $product_id)
													->update('product', $prod_update);
							if( $update_status ){
								$prod_db_updated  = true;
							}
						}
					} else {
						$img_load_error = $this->upload->display_errors();
					}
				} else {
					$img_load_error = "Product Sku not found";
				}
				$this->_prod_images[ ] =[
										'Image' => $name,
										'Search For SKU' 	=> $sku_for,
										'Product Exist' => $sku_exist ? 'Yes' : 'No',										
										'Product Updated'=> $prod_db_updated ? 'Yes' : 'No',
										'Image Uploaded'=> $img_load ? 'Yes' : $img_load_error,
									];
			}
		} else {
			$this->_prod_images[ ] = [
										'Image' => "No Image found",
										'Search For SKU' 	=> 'No',
										'Product Exist' 	=> 'No',
										'Product Updated'	=> 'No',
										'Image Uploaded' 	=> 'No',
									];
		}		
		return TRUE;
	}
	 
    private function test_prod_csv(){
		$this->load->model('CsvUtilitymodel');
		$csvfile = array('1' => array('ref_product_code' => 
		'c001','sku' => 'c001','attribute_set_id' => '6','category_id' 
		=> '175','product_name' => 'ladies l/sleeve poplin 
		shirt','product_description' => '65% polyester, 35% cotton.  
		ladies long sleeve poplin shirt. made from classic shirt 
		fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => '','colour' => '','stock' => '0'), '2' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-2xl','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-2xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => '2xl','colour' => 'blck','stock' => '0'), '3' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-3xl','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-3xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => '3xl','colour' => 'blck','stock' => '0'), '4' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-4xl','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-4xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => '4xl','colour' => 'blck','stock' => '0'), '5' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-lrg','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-l','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'lrg','colour' => 'blck','stock' => '1'), '6' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-med','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-m','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'med','colour' => 'blck','stock' => '0'), '7' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-sml','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-s','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'sml','colour' => 'blck','stock' => '1'), '8' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-xlg','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'xlg','colour' => 'blck','stock' => '5'), '9' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-blck-xsm','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-black-xs','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'xsm','colour' => 'blck','stock' => '0'), '10' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-2xl','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-2xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => '2xl','colour' => 'whit','stock' => '0'), '11' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-3xl','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-3xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => '3xl','colour' => 'whit','stock' => '0'), '12' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-4xl','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-4xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => '4xl','colour' => 'whit','stock' => '0'), '13' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-lrg','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-l','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'lrg','colour' => 'whit','stock' => '0'), '14' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-med','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-m','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'med','colour' => 'whit','stock' => '0'), '15' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-sml','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-s','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'sml','colour' => 'whit','stock' => '0'), '16' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-xlg','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-xl','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'xlg','colour' => 'whit','stock' => '1'), '17' => 
		array('ref_product_code' => 'c001','sku' => 
		'c001-whit-xsm','attribute_set_id' => '6','category_id' => 
		'175','product_name' => 'ladies l/sleeve poplin 
		shirt-white-xs','product_description' => '65% polyester, 35% 
		cotton.  ladies long sleeve poplin shirt. made from classic 
		shirt fabric, and incorporating single button collar. rounded 2 
		button adjustable cuffs. v-shaped pocket over left chest. 
		rounded hem. available in sizes xs - 4xl      available in 
		white and black','supplier_code' => 's00025','price' => 
		'0','size' => 'xsm','colour' => 'whit','stock' => '0'), '18' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain 
		jacket','product_description' => '100% polyester pvc coated 
		rain jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'','colour' => '','stock' => '0'), '19' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-blck-2xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket black size 
		2xl','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'2xl','colour' => 'blck','stock' => '7'), '20' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-blck-3xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket black size 
		3xl','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'3xl','colour' => 'blck','stock' => '0'), '21' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-blck-l','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket black size 
		large','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'lrg','colour' => 'blck','stock' => '0'), '22' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-blck-m','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket black size 
		medium','product_description' => '100% polyester pvc coated 
		rain jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'med','colour' => 'blck','stock' => '0'), '23' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-blck-s','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket black size 
		small','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'sml','colour' => 'blck','stock' => '0'), '24' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-blck-xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket black size 
		xl','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'xl','colour' => 'blck','stock' => '1'), '25' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-navy-2xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket navy size 
		2xl','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'2xl','colour' => 'navy','stock' => '4'), '26' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-navy-3xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket navy size 
		3xl','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'3xl','colour' => 'navy','stock' => '15'), '27' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-navy-l','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket navy size 
		large','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'lrg','colour' => 'navy','stock' => '1'), '28' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-navy-m','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket navy size 
		medium','product_description' => '100% polyester pvc coated 
		rain jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'med','colour' => 'navy','stock' => '0'), '29' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-navy-s','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket navy size 
		small','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'sml','colour' => 'navy','stock' => '0'), '30' => 
		array('ref_product_code' => 'c002','sku' => 
		'c002-navy-xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain jacket navy size 
		xl','product_description' => '100% polyester pvc coated rain 
		jacket. designed to be worn in foul weather. practical and 
		durable with back vents and eyelets for ventilation. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '7.92','size' => 
		'xlg','colour' => 'navy','stock' => '1'), '31' => 
		array('ref_product_code' => 'c003','sku' => 
		'c003','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain 
		trousers','product_description' => '100% polyester pvc coated 
		rain trousers. designed to be worn in foul weather. roomy for 
		easy layering on top of trousers with side access pockets and 
		stud adjustable hem for secure fit around work boots. available 
		in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '6.25','size' => 
		'','colour' => '','stock' => '0'), '32' => 
		array('ref_product_code' => 'c003','sku' => 
		'c003-blck-2xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain trousers black size 
		2xl','product_description' => '100% polyester pvc coated rain 
		trousers. designed to be worn in foul weather. roomy for easy 
		layering on top of trousers with side access pockets and stud 
		adjustable hem for secure fit around work boots. available in 
		sizes xs - 5xl. available in black and navy.','supplier_code' 
		=> 's00092','price' => '6.25','size' => '2xl','colour' => 
		'blck','stock' => '10'), '33' => array('ref_product_code' => 
		'c003','sku' => 'c003-blck-3xl','attribute_set_id' => 
		'6','category_id' => '109','product_name' => 'classic rain 
		trousers black size 3xl','product_description' => '100% 
		polyester pvc coated rain trousers. designed to be worn in foul 
		weather. roomy for easy layering on top of trousers with side 
		access pockets and stud adjustable hem for secure fit around 
		work boots. available in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '6.25','size' => 
		'3xl','colour' => 'blck','stock' => '6'), '34' => 
		array('ref_product_code' => 'c003','sku' => 
		'c003-blck-l','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain trousers black size 
		large','product_description' => '100% polyester pvc coated rain 
		trousers. designed to be worn in foul weather. roomy for easy 
		layering on top of trousers with side access pockets and stud 
		adjustable hem for secure fit around work boots. available in 
		sizes xs - 5xl. available in black and navy.','supplier_code' 
		=> 's00092','price' => '6.25','size' => 'lrg','colour' => 
		'blck','stock' => '8'), '35' => array('ref_product_code' => 
		'c003','sku' => 'c003-blck-m','attribute_set_id' => 
		'6','category_id' => '109','product_name' => 'classic rain 
		trousers black size medium','product_description' => '100% 
		polyester pvc coated rain trousers. designed to be worn in foul 
		weather. roomy for easy layering on top of trousers with side 
		access pockets and stud adjustable hem for secure fit around 
		work boots. available in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '6.25','size' => 
		'med','colour' => 'blck','stock' => '21'), '36' => 
		array('ref_product_code' => 'c003','sku' => 
		'c003-blck-s','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain trousers black size 
		small','product_description' => '100% polyester pvc coated rain 
		trousers. designed to be worn in foul weather. roomy for easy 
		layering on top of trousers with side access pockets and stud 
		adjustable hem for secure fit around work boots. available in 
		sizes xs - 5xl. available in black and navy.','supplier_code' 
		=> 's00092','price' => '6.25','size' => 'sml','colour' => 
		'blck','stock' => '3'), '37' => array('ref_product_code' => 
		'c003','sku' => 'c003-blck-xl','attribute_set_id' => 
		'6','category_id' => '109','product_name' => 'classic rain 
		trousers black size xl','product_description' => '100% 
		polyester pvc coated rain trousers. designed to be worn in foul 
		weather. roomy for easy layering on top of trousers with side 
		access pockets and stud adjustable hem for secure fit around 
		work boots. available in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '6.25','size' => 
		'xl','colour' => 'blck','stock' => '30'), '38' => 
		array('ref_product_code' => 'c003','sku' => 
		'c003-navy-2xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain trousers navy size 
		2xl','product_description' => '100% polyester pvc coated rain 
		trousers. designed to be worn in foul weather. roomy for easy 
		layering on top of trousers with side access pockets and stud 
		adjustable hem for secure fit around work boots. available in 
		sizes xs - 5xl. available in black and navy.','supplier_code' 
		=> 's00092','price' => '6.25','size' => '2xl','colour' => 
		'navy','stock' => '17'), '39' => array('ref_product_code' => 
		'c003','sku' => 'c003-navy-3xl','attribute_set_id' => 
		'6','category_id' => '109','product_name' => 'classic rain 
		trousers navy size 3xl','product_description' => '100% 
		polyester pvc coated rain trousers. designed to be worn in foul 
		weather. roomy for easy layering on top of trousers with side 
		access pockets and stud adjustable hem for secure fit around 
		work boots. available in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '6.25','size' => 
		'3xl','colour' => 'navy','stock' => '15'), '40' => 
		array('ref_product_code' => 'c003','sku' => 
		'c003-navy-l','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain trousers navy size 
		large','product_description' => '100% polyester pvc coated rain 
		trousers. designed to be worn in foul weather. roomy for easy 
		layering on top of trousers with side access pockets and stud 
		adjustable hem for secure fit around work boots. available in 
		sizes xs - 5xl. available in black and navy.','supplier_code' 
		=> 's00092','price' => '6.25','size' => 'lrg','colour' => 
		'navy','stock' => '19'), '41' => array('ref_product_code' => 
		'c003','sku' => 'c003-navy-m','attribute_set_id' => 
		'6','category_id' => '109','product_name' => 'classic rain 
		trousers navy size medium','product_description' => '100% 
		polyester pvc coated rain trousers. designed to be worn in foul 
		weather. roomy for easy layering on top of trousers with side 
		access pockets and stud adjustable hem for secure fit around 
		work boots. available in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '6.25','size' => 
		'med','colour' => 'navy','stock' => '16'), '42' => 
		array('ref_product_code' => 'c003','sku' => 
		'c003-navy-s','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'classic rain trousers navy size 
		small','product_description' => '100% polyester pvc coated rain 
		trousers. designed to be worn in foul weather. roomy for easy 
		layering on top of trousers with side access pockets and stud 
		adjustable hem for secure fit around work boots. available in 
		sizes xs - 5xl. available in black and navy.','supplier_code' 
		=> 's00092','price' => '6.25','size' => 'sml','colour' => 
		'navy','stock' => '8'), '43' => array('ref_product_code' => 
		'c003','sku' => 'c003-navy-xl','attribute_set_id' => 
		'6','category_id' => '109','product_name' => 'classic rain 
		trousers navy size xl','product_description' => '100% polyester 
		pvc coated rain trousers. designed to be worn in foul weather. 
		roomy for easy layering on top of trousers with side access 
		pockets and stud adjustable hem for secure fit around work 
		boots. available in sizes xs - 5xl. available in black and 
		navy.','supplier_code' => 's00092','price' => '6.25','size' => 
		'xlg','colour' => 'navy','stock' => '20'), '44' => 
		array('ref_product_code' => 'c004','sku' => 
		'c004','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'fortex storm flex 
		jacket','product_description' => 'waterproof stretchable pu 
		tricot fabric zip front with studded storm flap concealed hood 
		large pockets with velcro® adjustable cuffs with velcro® welded 
		seams available in sizes s - 3xl. available in navy or 
		olive','supplier_code' => 's00028','price' => '11.67','size' => 
		'','colour' => '','stock' => '0'), '45' => 
		array('ref_product_code' => 'c004','sku' => 
		'c004-navy-2xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'fortex storm flex 
		jacket-2xl-navy','product_description' => 'waterproof 
		stretchable pu tricot fabric zip front with studded storm flap 
		concealed hood large pockets with velcro® adjustable cuffs with 
		velcro® welded seams available in sizes s - 3xl. available in 
		navy or olive','supplier_code' => 's00028','price' => 
		'11.67','size' => '2xl','colour' => 'navy','stock' => '2'), 
		'46' => array('ref_product_code' => 'c004','sku' => 
		'c004-navy-3xl','attribute_set_id' => '6','category_id' => 
		'109','product_name' => 'fortex storm flex 
		jacket-3xl-navy','product_description' => 'waterproof 
		stretchable pu tricot fabric zip front with studded storm flap 
		concealed hood large pockets with velcro® adjustable cuffs with 
		velcro® welded seams available in sizes s - 3xl. available in 
		navy or olive','supplier_code' => 's00028','price' => 
		'11.67','size' => '3xl','colour' => 'navy','stock' => '2'), );
		//$this->CsvUtilitymodel->CsvOpertations($csvfile);
		com_e( "exit" );
	}
}
