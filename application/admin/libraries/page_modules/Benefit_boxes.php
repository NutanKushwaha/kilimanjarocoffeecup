<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


//used for the partner label
class Benefit_boxes {

	private $CI;
	private $page;
	private $module_alias = 'benefit_boxes';
	private $module_name = 'Benefit Boxes';

	function __construct($params) {
		$this->CI = & get_instance();
		$this->page = $params['page'];
		$this->init();
	}

	function valid_image1($str) {
		if ($_FILES['image_1']['size'] > 0 && $_FILES['image_1']['error'] == UPLOAD_ERR_OK) {

			$imginfo = @getimagesize($_FILES['image_1']['tmp_name']);
			if (!$imginfo) {
				$this->CI->form_validation->set_message('module', 'Only image files are allowed');
				return false;
			}

			if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
				$this->CI->form_validation->set_message('module', 'Only GIF, JPG and PNG Images are accepted.');
				return FALSE;
			}
		}

		return TRUE;
	}
	
	function valid_image2($str) {
		if ($_FILES['image_2']['size'] > 0 && $_FILES['image_2']['error'] == UPLOAD_ERR_OK) {

			$imginfo = @getimagesize($_FILES['image_2']['tmp_name']);
			if (!$imginfo) {
				$this->CI->form_validation->set_message('module', 'Only image files are allowed');
				return false;
			}

			if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
				$this->CI->form_validation->set_message('module', 'Only GIF, JPG and PNG Images are accepted.');
				return FALSE;
			}
		}

		return TRUE;
	}
	
	
	function valid_image3($str) {
		if ($_FILES['image_3']['size'] > 0 && $_FILES['image_3']['error'] == UPLOAD_ERR_OK) {

			$imginfo = @getimagesize($_FILES['image_3']['tmp_name']);
			if (!$imginfo) {
				$this->CI->form_validation->set_message('module', 'Only image files are allowed');
				return false;
			}

			if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
				$this->CI->form_validation->set_message('module', 'Only GIF, JPG and PNG Images are accepted.');
				return FALSE;
			}
		}

		return TRUE;
	}

	function init() {
		$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_rules('image_v1', 'Benefit Image', 'trim|required|callback_module[benefit_boxes, valid_image1]');
		$this->CI->form_validation->set_rules('benefit_title_1', 'Benefit Title 1', 'trim|required');
		$this->CI->form_validation->set_rules('benefit_text_1', 'Benefit Text 1', 'trim|required');
		
		$this->CI->form_validation->set_rules('image_v2', 'Benefit Image 2', 'trim|required|callback_module[benefit_boxes, valid_image2]');
		$this->CI->form_validation->set_rules('benefit_title_2', 'Benefit Title 2', 'trim|required');
		$this->CI->form_validation->set_rules('benefit_text_2', 'Benefit Text 2', 'trim|required');
		
		$this->CI->form_validation->set_rules('image_v3', 'Benefit Image 3', 'trim|required|callback_module[benefit_boxes, valid_image3]');
		$this->CI->form_validation->set_rules('benefit_title_2', 'Benefit Title 3', 'trim|required');
		$this->CI->form_validation->set_rules('benefit_text_2', 'Benefit Text 3', 'trim|required');
	}

	function getName() {
		return $this->module_name;
	}

	function getAlias() {
		return $this->module_alias;
	}

	function addView() {
		return "add";
	}

	//function to edit record
	function editView() {
		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('partners/Partnerpagemodel');

		$benefit_image_1 = array();
		$benefit_image_1 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_image_1');

		$benefit_title_1 = array();
		$benefit_title_1 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_title_1');

		$benefit_text_1 = array();
		$benefit_text_1 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_text_1');
		
		
		$benefit_image_2 = array();
		$benefit_image_2 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_image_2');

		$benefit_title_2 = array();
		$benefit_title_2 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_title_2');

		$benefit_text_2 = array();
		$benefit_text_2 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_text_2');
		
		
		$benefit_image_3 = array();
		$benefit_image_3 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_image_3');

		$benefit_title_3 = array();
		$benefit_title_3 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_title_3');

		$benefit_text_3 = array();
		$benefit_text_3 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_text_3');

		$inner = array();
		$inner['page'] = $this->page;
		$inner['benefit_image_1'] = $benefit_image_1;
		$inner['benefit_title_1'] = $benefit_title_1;
		$inner['benefit_text_1'] = $benefit_text_1;
		
		$inner['benefit_image_2'] = $benefit_image_2;
		$inner['benefit_title_2'] = $benefit_title_2;
		$inner['benefit_text_2'] = $benefit_text_2;
		
		$inner['benefit_image_3'] = $benefit_image_3;
		$inner['benefit_title_3'] = $benefit_title_3;
		$inner['benefit_text_3'] = $benefit_text_3;


		return $this->CI->load->view('cms/page_modules/Benefit_boxes/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$banner = array();
		$banner = $this->CI->Pagemodel->getPageData($this->page, 'banner');

		//delete the previous pdf_image
		if ($banner && isset($banner['page_setting_value'])) {
			$path = $this->CI->config->item('PAGE_DATA_IMAGE_PATH');
			$filename = $path . $banner['page_setting_value'];
			if (file_exists($filename)) {
				@unlink($filename);
			}

			$this->CI->db->where('page_data_id', $banner['page_data_id']);
			$this->CI->db->delete('page_data');
		}

		$show_banner = array();
		$show_banner = $this->CI->Pagemodel->getPageData($this->page, 'show_banner');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $show_banner['page_data_id']);
		$this->CI->db->delete('page_data');

		$banner_link = array();
		$banner_link = $this->CI->Pagemodel->getPageData($this->page, 'banner_url');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $banner_link['page_data_id']);
		$this->CI->db->delete('page_data');

		$banner_new_window = array();
		$banner_new_window = $this->CI->Pagemodel->getPageData($this->page, 'new_window');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $banner_new_window['page_data_id']);
		$this->CI->db->delete('page_data');
	}

	function actionUpdate() {
		$this->CI->load->model('partners/Partnerpagemodel');
		
		$page = $this->page;

		

		$upload_folder = $this->CI->config->item('PARTNER_PATH') . $page['partner_id'] . '/content_data/';
		$upload_rpath = $this->CI->config->item('PARTNER_RPATH') . $page['partner_id'] . '/content_data/';
		
		if (!file_exists($upload_folder)) {
			mkdir($upload_folder, 0755, true);
		}
		
		
		//*************************benefit box 1 entry start here***********************************
		$benefit_image_1 = array();
		$benefit_image_1 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_image_1');
		//Upload Image
		$config['upload_path'] = $upload_folder;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = FALSE;
		$this->CI->load->library('upload', $config);
		if (count($_FILES) > 0) {
			//Check for valid image upload
			if ($_FILES['image_1']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image_1']['tmp_name'])) {
				if (!$this->CI->upload->do_upload('image_1')) {
					show_error($this->CI->upload->display_errors('<p class="err">', '</p>'));
					return FALSE;
				} else {
					$upload_data = $this->CI->upload->data();
					$banner_image = $upload_rpath . $upload_data['file_name'];

					//delete the previous pdf_image
					if ($benefit_image_1 && isset($benefit_image_1['page_setting_value'])) {	
						$path = $this->CI->config->item('ROOT_PATH');
						$filename = $path . $benefit_image_1['page_setting_value'];
						if (file_exists($filename)) {
							@unlink($filename);
						}

						$this->CI->db->where('partner_content_data_id', $benefit_image_1['partner_content_data_id']);
						$this->CI->db->delete('partner_content_data');
					}

					//insert new image
					$data = array();
					$data['page_setting'] = 'benefit_image_1';
					$data['module_name'] = $this->getAlias();
					$data['page_setting_value'] = $banner_image;
					$data['partner_content_id'] = $page['partner_content_id'];
					$this->CI->db->insert('partner_content_data', $data);
				}
			}
		}
		
		$benefit_title_1 = array();
		$benefit_title_1 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_title_1');
		
		//delete the previos data if any
		$this->CI->db->where('partner_content_data_id', $benefit_title_1['partner_content_data_id']);
		$this->CI->db->delete('partner_content_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'benefit_title_1';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $this->CI->input->post('benefit_title_1', true);
		$data['partner_content_id'] = $page['partner_content_id'];
		$this->CI->db->insert('partner_content_data', $data);


		$benefit_text_1 = array();
		$benefit_text_1 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_text_1');

		//delete the previos data if any
		$this->CI->db->where('partner_content_data_id', $benefit_text_1['partner_content_data_id']);
		$this->CI->db->delete('partner_content_data');

		//insert new data
		$data = array();
		$data['module_name'] = $this->getAlias();
		$data['page_setting'] = 'benefit_text_1';
		$data['page_setting_value'] = $this->CI->input->post('benefit_text_1', true);
		$data['partner_content_id'] = $page['partner_content_id'];
		$this->CI->db->insert('partner_content_data', $data);
		//****************************benefit box 1 entry end here**********************
		
		//*************************benefit box 2 entry start here***********************************
		$benefit_image_2 = array();
		$benefit_image_2 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_image_2');
		//Upload Image
		$config['upload_path'] = $upload_folder;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = FALSE;
		$this->CI->load->library('upload', $config);
		if (count($_FILES) > 0) {
			//Check for valid image upload
			if ($_FILES['image_2']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image_2']['tmp_name'])) {
				if (!$this->CI->upload->do_upload('image_2')) {
					show_error($this->CI->upload->display_errors('<p class="err">', '</p>'));
					return FALSE;
				} else {
					$upload_data = $this->CI->upload->data();
					$banner_image = $upload_rpath . $upload_data['file_name'];

					//delete the previous pdf_image
					if ($benefit_image_2 && isset($benefit_image_2['page_setting_value'])) {	
						$path = $this->CI->config->item('ROOT_PATH');
						$filename = $path . $benefit_image_2['page_setting_value'];
						if (file_exists($filename)) {
							@unlink($filename);
						}

						$this->CI->db->where('partner_content_data_id', $benefit_image_2['partner_content_data_id']);
						$this->CI->db->delete('partner_content_data');
					}

					//insert new image
					$data = array();
					$data['page_setting'] = 'benefit_image_2';
					$data['module_name'] = $this->getAlias();
					$data['page_setting_value'] = $banner_image;
					$data['partner_content_id'] = $page['partner_content_id'];
					$this->CI->db->insert('partner_content_data', $data);
				}
			}
		}
		
		$benefit_title_2 = array();
		$benefit_title_2 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_title_2');
		
		//delete the previos data if any
		$this->CI->db->where('partner_content_data_id', $benefit_title_2['partner_content_data_id']);
		$this->CI->db->delete('partner_content_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'benefit_title_2';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $this->CI->input->post('benefit_title_2', true);
		$data['partner_content_id'] = $page['partner_content_id'];
		$this->CI->db->insert('partner_content_data', $data);


		$benefit_text_2 = array();
		$benefit_text_2 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_text_2');

		//delete the previos data if any
		$this->CI->db->where('partner_content_data_id', $benefit_text_2['partner_content_data_id']);
		$this->CI->db->delete('partner_content_data');

		//insert new data
		$data = array();
		$data['module_name'] = $this->getAlias();
		$data['page_setting'] = 'benefit_text_2';
		$data['page_setting_value'] = $this->CI->input->post('benefit_text_2', true);
		$data['partner_content_id'] = $page['partner_content_id'];
		$this->CI->db->insert('partner_content_data', $data);
		//****************************benefit box 2 entry end here**********************
		
		//*************************benefit box 3 entry start here***********************************
		$benefit_image_3 = array();
		$benefit_image_3 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_image_3');
		//Upload Image
		$config['upload_path'] = $upload_folder;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = FALSE;
		$this->CI->load->library('upload', $config);
		if (count($_FILES) > 0) {
			//Check for valid image upload
			if ($_FILES['image_3']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image_3']['tmp_name'])) {
				if (!$this->CI->upload->do_upload('image_3')) {
					show_error($this->CI->upload->display_errors('<p class="err">', '</p>'));
					return FALSE;
				} else {
					$upload_data = $this->CI->upload->data();
					$banner_image = $upload_rpath . $upload_data['file_name'];

					//delete the previous pdf_image
					if ($benefit_image_3 && isset($benefit_image_3['page_setting_value'])) {	
						$path = $this->CI->config->item('ROOT_PATH');
						$filename = $path . $benefit_image_3['page_setting_value'];
						if (file_exists($filename)) {
							@unlink($filename);
						}

						$this->CI->db->where('partner_content_data_id', $benefit_image_3['partner_content_data_id']);
						$this->CI->db->delete('partner_content_data');
					}

					//insert new image
					$data = array();
					$data['page_setting'] = 'benefit_image_3';
					$data['module_name'] = $this->getAlias();
					$data['page_setting_value'] = $banner_image;
					$data['partner_content_id'] = $page['partner_content_id'];
					$this->CI->db->insert('partner_content_data', $data);
				}
			}
		}
		
		$benefit_title_3 = array();
		$benefit_title_3 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_title_3');
		
		//delete the previos data if any
		$this->CI->db->where('partner_content_data_id', $benefit_title_3['partner_content_data_id']);
		$this->CI->db->delete('partner_content_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'benefit_title_3';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $this->CI->input->post('benefit_title_3', true);
		$data['partner_content_id'] = $page['partner_content_id'];
		$this->CI->db->insert('partner_content_data', $data);


		$benefit_text_3 = array();
		$benefit_text_3 = $this->CI->Partnerpagemodel->getModuleData($this->page, 'benefit_text_3');

		//delete the previos data if any
		$this->CI->db->where('partner_content_data_id', $benefit_text_3['partner_content_data_id']);
		$this->CI->db->delete('partner_content_data');

		//insert new data
		$data = array();
		$data['module_name'] = $this->getAlias();
		$data['page_setting'] = 'benefit_text_3';
		$data['page_setting_value'] = $this->CI->input->post('benefit_text_3', true);
		$data['partner_content_id'] = $page['partner_content_id'];
		$this->CI->db->insert('partner_content_data', $data);
	}

}

?>