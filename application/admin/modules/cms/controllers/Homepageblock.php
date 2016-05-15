<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Homepageblock extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    //**************************validation start***************************
    //function valid page block for add
    function valid_block($str) {
        $this->db->where('page_id', $this->input->post('page_id', TRUE));
        $this->db->from('cash_for_cars');
        $block_count = $this->db->count_all_results();
        if ($block_count != 0) {
            $this->form_validation->set_message('valid_block', 'Block Alias is already being used!');
            return false;
        }
        return true;
    }

    //function valid page block for edit
    function validBlock($str) {
        $this->db->where('block_id !=', $this->input->post('block_id', true));
        $this->db->where('page_id', $this->input->post('page_id', TRUE));
        $this->db->from('cash_for_cars');
        $block_count = $this->db->count_all_results();
        if ($block_count != 0) {
            $this->form_validation->set_message('validBlock', 'Block Alias is already being used!');
            return false;
        }
        return true;
    }


	//validation for valid image
    function valid_images($str) {
        if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
            $this->form_validation->set_message('valid_images', 'Image not uploaded.');
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

    //*************************************validation end**********************************************
    function index($pid = 0, $offset = 0) {
        $this->load->model('Homeblockmodel');
        $this->load->model('Pagemodel');                        

        //Get page Detail
        $pages = array();
        $pages = $this->Pagemodel->detail($pid);
        if (!$pages) {
            $this->utility->show404();
            return;
        }

        $page_lang = array();
        $page_lang = $this->Pagemodel->getLanguage($pages['language_code']);

        //Setup pagination
        $perpage = 2000;
        $config['base_url'] = base_url() . "cms/homepageblock/index/$pid/";
        $config['uri_segment'] = 5;
        $config['total_rows'] = $this->Homeblockmodel->countAll($pid);
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        //list all block
        $block = array();
        $block = $this->Homeblockmodel->listAll($pid, $offset, $perpage);

        //render view
        $inner = array();
        $inner['block'] = $block;
        $inner['pages'] = $pages;
        $inner['page_lang'] = $page_lang;
        $inner['pagination'] = $this->pagination->create_links();

        $page = array();
        $page['content'] = $this->load->view('homepage/block-index', $inner, TRUE);
        $this->load->view('shell-overlay', $page);
    }

    //function add block
    function add($bid = false) {
        $this->load->model('Homeblockmodel');
        $this->load->model('Pagemodel');
        
        



        //Get page Detail
        $pages = array();
        $pages = $this->Homeblockmodel->getPageDetail($bid);
		if (!$pages) {
            $this->utility->show404();
            return;
        }

        $page_lang = array();
        $page_lang = $this->Pagemodel->getLanguage($pages['language_code']);

        //validation check
        $this->form_validation->set_rules('block_title', 'Block Title', 'trim|required');
		$this->form_validation->set_rules('v_image', 'BLock Image', 'trim|callback_validImage');
		$this->form_validation->set_rules('readmore_url', 'Read More URL', 'trim|required');
        $this->form_validation->set_rules('block_contents', 'Contents', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['pages'] = $pages;
            $inner['page_lang'] = $page_lang;

            $page = array();
            $page['content'] = $this->load->view('homepage/block-add', $inner, TRUE);
            $this->load->view('shell-overlay', $page);
        } else {
            $this->Homeblockmodel->insertRecord($pages);

            $this->session->set_flashdata('SUCCESS', 'block_added');

            redirect("cms/homepageblock/index/$bid", "location");
            exit();
        }
    }

    //function edit page block
    function edit($bid = false) {
        $this->load->model('Homeblockmodel');
        $this->load->model('Pagemodel');
        $this->load->helper('text');
        



        //fetch the block details
        $block = array();
        $block = $this->Homeblockmodel->getDetails($bid);
        if (!$block) {
            $this->utility->show404();
            return;
        }

        //Get page Detail
        $pages = array();
        $pages = $this->Homeblockmodel->getPageDetail($block['page_id']);
        if (!$pages) {
            $this->utility->show404();
            return;
        }

        $page_lang = array();
        $page_lang = $this->Pagemodel->getLanguage($pages['language_code']);

        //fetch  page template
        $lang_code = '';
        if ($pages['language_code'] !== 'en') {
            $lang_code = '_' . $pages['language_code'];
        }

		//Create File
        /*if($block['block_template']!= ''){
			$file_name = str_ireplace('/', '_', $pages['page_uri']) . '_' . $block['block_alias'] . $lang_code . '.php';
			if (file_exists("../application/views/themes/" . THEME . "/blocks/" . $file_name)) {
				$status = file_put_contents("../application/views/themes/" . THEME . "/blocks/" . $file_name, $block['block_template']);
				if (!$status) {
						show_error('<p class="err">The system was unable to copy Block Template  for Page!</p>');
						return FALSE;
					}
				//$blocktemplate = file_get_contents("../application/views/themes/" . THEME . "/blocks/" . $file_name);
			}
		}*/
        //validation check
        $this->form_validation->set_rules('block_title', 'Block Title', 'trim|required');
		$this->form_validation->set_rules('v_image', 'Block Image', 'trim|callback_validImage');
        $this->form_validation->set_rules('block_contents', 'Contents', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //render view
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['block'] = $block;
            $inner['page_lang'] = $page_lang;
            $inner['pages'] = $pages;
            $page['content'] = $this->load->view('homepage/block-edit', $inner, TRUE);
            $this->load->view('shell-overlay', $page);
        } else {
            $this->Homeblockmodel->updateRecord($block, $pages);

            $this->session->set_flashdata('SUCCESS', 'block_updated');

            redirect("cms/homepageblock/index/{$block['page_id']}");
            exit();
        }
    }

    function delete($bid = false) {
        $this->load->model('Homeblockmodel');
        
        



        //fetch the block details
        $block = array();
        $block = $this->Homeblockmodel->getDetails($bid);
        if (!$block) {
            $this->utility->show404();
            return;
        }

		if($block['do_not_delete'] == 1){
			redirect("cms/homepageblock/index/{$block['page_id']}", "location");
			exit();
		}

        $this->Homeblockmodel->deleteRecord($block);

        $this->session->set_flashdata('SUCCESS', 'block_deleted');
        redirect("cms/homepageblock/index/{$block['page_id']}", "location");
        exit();
    }
}

?>