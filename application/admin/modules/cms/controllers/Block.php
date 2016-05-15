<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Block extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    //**************************validation start***************************
    //function valid page block for add
    function valid_block($str) {
        $this->db->where('block_alias', $str);
        $this->db->where('page_id', $this->input->post('page_id', TRUE));
        $this->db->from('block');
        $block_count = $this->db->count_all_results();
        if ($block_count != 0) {
            $this->form_validation->set_message('valid_block', 'Block Alias is already being used!');
            return false;
        }
        return true;
    }

    //function valid page block for edit
    function validBlock($str) {
        $this->db->where('block_alias', $str);
        $this->db->where('block_id !=', $this->input->post('block_id', true));
        $this->db->where('page_id', $this->input->post('page_id', TRUE));
        $this->db->from('block');
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
    function index($pid = 0) {
        $this->load->model('Blockmodel');
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
        /* $perpage = 20;
          $config['base_url'] = base_url() . "cms/block/index/$pid/";
          $config['uri_segment'] = 5;
          $config['total_rows'] = $this->Blockmodel->countAll($pid);
          $config['per_page'] = $perpage;
          $this->pagination->initialize($config); */

        //list all block
        $block = array();
        $block = $this->Blockmodel->listAll($pid);

        //render view
        $inner = array();
        $inner['block'] = $block;
        $inner['pages'] = $pages;
        $inner['page_lang'] = $page_lang;

        $page = array();
        $page['content'] = $this->load->view('block/block-index', $inner, TRUE);
        $this->load->view($this->shellFile, $page);
    }

    //function add block
    function add($pid = false) {
        $this->load->model('Blockmodel');
        $this->load->model('Pagemodel');
        
        //Get page Detail
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        $page_lang = array();
        $page_lang = $this->Pagemodel->getLanguage($page_details['language_code']);

        //validation check
        $this->form_validation->set_rules('block_layout_id', 'Block Layout', 'trim|required');
        $this->form_validation->set_rules('block_title', 'Block Title', 'trim|required');
        $this->form_validation->set_rules('block_alias', 'Block Alias', 'trim|callback_valid_block');
        $this->form_validation->set_rules('v_image', 'Block Image', 'trim|required|callback_validImage');
        $this->form_validation->set_rules('block_contents', 'Block Content', 'trim');
        $this->form_validation->set_rules('alt', 'Alt', 'trim');
        $this->form_validation->set_rules('link', 'Link', 'trim');
        $this->form_validation->set_rules('new_window', 'New Window', 'trim');
        //$this->form_validation->set_rules('block_contents', 'Contents', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //fetch the block layout
        $block_layout = array();
        $rs = $this->Blockmodel->fetchBlockLayouts();
        foreach ($rs as $item) {
            $block_layout[$item['block_layout_id']] = $item['block_layout'];
        }

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['page_details'] = $page_details;
            $inner['page_lang'] = $page_lang;
            $inner['block_layout'] = $block_layout;

            $page = array();
            $page['content'] = $this->load->view('block/block-add', $inner, TRUE);
            $this->load->view($this->shellFile, $page);
        } else {
            $this->Blockmodel->insertRecord($page_details);

            $this->session->set_flashdata('SUCCESS', 'block_added');

            redirect("cms/block/index/$pid", "location");
            exit();
        }
    }

    //function edit page block
    function edit($bid = false) {
        $this->load->model('Blockmodel');
        $this->load->model('Pagemodel');
        $this->load->helper('text');
        



        //fetch the block details
        $block = array();
        $block = $this->Blockmodel->getDetails($bid);
        if (!$block) {
            $this->utility->show404();
            return;
        }

        //Get page Detail
        $page_details = array();
        $page_details = $this->Pagemodel->detail($block['page_id']);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        $page_lang = array();
        $page_lang = $this->Pagemodel->getLanguage($page_details['language_code']);

        //validation check
        $this->form_validation->set_rules('block_layout_id', 'Block Layout', 'trim|required');
        $this->form_validation->set_rules('block_title', 'Block Title', 'trim|required');
        $this->form_validation->set_rules('block_alias', 'Block Alias', 'trim|callback_validBlock');
        $this->form_validation->set_rules('v_image', 'Block Image', 'trim|required|callback_validImage');
        $this->form_validation->set_rules('block_contents', 'Block Content', 'trim');
        $this->form_validation->set_rules('alt', 'Alt', 'trim');
        $this->form_validation->set_rules('link', 'Link', 'trim');
        $this->form_validation->set_rules('new_window', 'New Window', 'trim');
        //$this->form_validation->set_rules('block_contents', 'Contents', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //fetch the block layout
        $block_layout = array();
        $rs = $this->Blockmodel->fetchBlockLayouts();
        foreach ($rs as $item) {
            $block_layout[$item['block_layout_id']] = $item['block_layout'];
        }

        //render view
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['block'] = $block;
            $inner['block_layout'] = $block_layout;
            $inner['page_lang'] = $page_lang;
            $inner['page_details'] = $page_details;
            $page['content'] = $this->load->view('block/block-edit', $inner, TRUE);
            $this->load->view($this->shellFile, $page);
        } else {
            $this->Blockmodel->updateRecord($block);

            $this->session->set_flashdata('SUCCESS', 'block_updated');

            redirect("cms/block/index/{$block['page_id']}");
            exit();
        }
    }

    function delete($bid = false) {
        $this->load->model('Blockmodel');
        
        



        //fetch the block details
        $block = array();
        $block = $this->Blockmodel->getDetails($bid);
        if (!$block) {
            $this->utility->show404();
            return;
        }

        if ($block['do_not_delete'] == 1) {
            redirect("cms/block/index/{$block['page_id']}", "location");
            exit();
        }

        $this->Blockmodel->deleteRecord($block);

        $this->session->set_flashdata('SUCCESS', 'block_deleted');
        redirect("cms/block/index/{$block['page_id']}", "location");
        exit();
    }
    
    function disable($bid = false) {
        $this->load->model('Blockmodel');
        
        



        //fetch the block details
        $block = array();
        $block = $this->Blockmodel->getDetails($bid);
        if (!$block) {
            $this->utility->show404();
            return;
        }

        if ($block['block_active'] == 0) {
            redirect("cms/block/index/{$block['page_id']}", "location");
            exit();
        }

        $this->Blockmodel->disableRecord($block);

        $this->session->set_flashdata('SUCCESS', 'block_updated');
        redirect("cms/block/index/{$block['page_id']}", "location");
        exit();
    }
    
    function enable($bid = false) {
        $this->load->model('Blockmodel');
        
        



        //fetch the block details
        $block = array();
        $block = $this->Blockmodel->getDetails($bid);
        if (!$block) {
            $this->utility->show404();
            return;
        }

        if ($block['block_active'] == 1) {
            redirect("cms/block/index/{$block['page_id']}", "location");
            exit();
        }

        $this->Blockmodel->enableRecord($block);

        $this->session->set_flashdata('SUCCESS', 'block_updated');
        redirect("cms/block/index/{$block['page_id']}", "location");
        exit();
    }

}

?>