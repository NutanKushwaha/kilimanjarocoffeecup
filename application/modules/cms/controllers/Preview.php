<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Preview extends Front_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($alias) {
        $this->load->model('Pagemodel');
        $this->load->library('Cart');
        $this->load->helper('text');
        $this->load->helper('url');

        //Language
        $lang = 'en';
        $lang_trigger = false;
        $lang_uri = $this->uri->uri_string();
        if ($lang_uri) {
            $lang_arr = explode('/', $lang_uri);
            if (count($lang_arr) > 1) {
                $lang_code = $lang_arr[0];
                $this->db->where('language_code', $lang_code);
                $rs = $this->db->get('language');
                if ($rs->num_rows() == 1) {
                    $lang = $lang_code;
                    $lang_trigger = true;
                }
            }
        }
        
        
        $session = $this->session->userdata('ADMIN_USER_ID');
        if (!$session) {
            $this->utility->show404();
            return;
        }
        
        
        //Get Page Details
        $page = array();
        $page = $this->Pagemodel->getDetailsForPreview($alias, $lang);        
        if (!$page) {
            $this->utility->show404();
            return;
        }

        $this->setPage($page);

        //Compiled blocks
        $compiled_blocks = array();
        $compiled_blocks = $this->Pagemodel->compiledBlocks($page);

        //fetch page languages
        $languages = array();
        $languages = $this->Pagemodel->getAllLanguages($page, $lang);

        $breadcrumbs = array();
        $breadcrumbs = $this->Pagemodel->breadcrumbs($page['page_id']);

        //Variables
        $shell = array();
        $inner = array();
        $inner['page'] = $page;
        $inner['breadcrumbs'] = $breadcrumbs;
        $inner['languages'] = $languages;
        if ($page['admin_modules']) {
            $modules = explode(',', $page['frontend_modules']);
            foreach ($modules as $page_module) {
                $this->load->library("page_modules/" . $page_module, array('page' => $page));
                $module_name = "module_$page_module";
                $inner[$module_name] = $this->$module_name;
            }
        }

        $compiledblocks = array();
        foreach ($compiled_blocks as $key => $val) {
            $compiledblocks[] = $val;
            $inner[$key] = $val;
        }
        $inner['compiledblocks'] = $compiledblocks;

        //File Name & File Path
        $file_name = str_replace('/', '_', $page['page_uri']);
        $file_path = "application/views/themes/" . THEME . "/cms/" . $file_name . ".php";

        //Meta
        $meta_file = "application/views/" . THEME . "/meta/" . $file_name . ".php";
        if (file_exists($meta_file)) {
            $this->html->addMeta($this->load->view("themes/" . THEME . "/meta/cms/" . $file_name, array('page' => $page), TRUE));
        } else {
            $this->html->addMeta($this->load->view("themes/" . THEME . "/meta/default", array('page' => $page), TRUE));
        }

        //Assets
        if (file_exists("application/views/" . THEME . "/headers/cms/" . $file_name . ".php")) {
            $this->assets->loadFromFile("themes/" . THEME . "/headers/cms/" . $file_name);
        }

        if (file_exists($file_path)) {
            $shell['contents'] = $this->load->view("themes/" . THEME . "/cms/" . $file_name, $inner, true);
        } else {
            $shell['contents'] = $this->load->view("themes/" . THEME . "/cms/" . 'default', $inner, true);
        }
        $this->load->view("themes/" . THEME . "/templates/{$page['template_alias']}", $shell);
    }

}

?>