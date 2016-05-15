<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Translate extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function index($pid = false) {
        $this->load->model('Pagemodel');
        $this->load->model('Translatemodel');
        $this->load->helper('text');


        //Get Page  Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }
		
		//print_r ($page_details); exit();

        //Get all pages translate
        $pages_translate = array();
        $pages_translate = $this->Translatemodel->listAll($page_details);

        $pages = array();
        $rs = $this->Translatemodel->listAllPage($page_details);
        foreach ($rs as $row) {
            $pages[$row['language_code']] = $row['language_code'];
        }


        //fetch page languages
        $languages = array();
        $languages = $this->Translatemodel->listAllLanguages($page_details);

        //render view
        $inner = array();
        $inner['page_details'] = $page_details;
        $inner['pages'] = $pages;
        $inner['pages_translate'] = $pages_translate;
        $inner['languages'] = $languages;
        $page = array();
        $page['content'] = $this->load->view('translates/translate-index', $inner, TRUE);
        $this->load->view($this->shellFile, $page);
    }

    function add($lang = '', $pid = false) {
        $this->load->model('Pagemodel');
        $this->load->model('Translatemodel');
        $this->load->model('Blockmodel');
        
        


        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        //fetch the block of the page
        $blocks = array();
        $blocks = $this->Blockmodel->fetchAllBlocks($page_details['page_id']);

        $this->Translatemodel->insertRecord($lang, $page_details, $blocks);

        $this->session->set_flashdata('SUCCESS', 'translate_added');

        redirect("cms/translate/index/{$page_details['page_id']}", 'location');
        exit();
	}
	
	 //function to delete record
    function delete($pid = false, $tid = false) {
        
        
        $this->load->model('Pagemodel');
        $this->load->model('Translatemodel');

        //Get Page
        $page_rs = array();
        $page_rs = $this->Pagemodel->detail($pid);
        if (!$page_rs) {
            $this->utility->show404();
            return;
        }
		
		

        //Get Page Details
        $page_details = array();
        $page_details = $this->Translatemodel->detail($tid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }
	

        $this->Pagemodel->deleteRecord($page_details);

        $this->session->set_flashdata('SUCCESS', 'translate_deleted');

        redirect("/cms/translate/index/{$page_rs['page_id']}", 'location');
        exit();
    }

}

?>