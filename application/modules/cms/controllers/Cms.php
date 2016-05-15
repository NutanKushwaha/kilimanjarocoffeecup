<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms extends Front_Controller {

    public $COMPANY = false;

    function getorganization($alias) {
//        bg.com/cms/setorganization/1234567890
        if ($alias) {

            $sess_array['unique_code'] = $alias;
            $sess_array['company_user_id'] = 24;
            $sess_array['company_id'] = 2;
            $sess_array['company_name'] = 'Maxi Tec';
            $this->session->unset_userdata('company', $sess_array);
//            com_e($this->session->userdata('company'));
        }
    }

    function index() {
        $this->load->helper('url');
        $this->load->helper('text');
        $this->load->library('Cart');
        $this->load->model('Pagemodel');
        $this->load->helper('cms_helper');
        $this->load->model('catalogue/Productmodel');
        $this->load->model('slideshow/Slidemodel');
        $this->load->model('Testimonialmodel');
        $this->load->model('catalogue/Categorymodel');

        $code = $this->uri->segment(1);
        if ($code) {
            $company = $this->Pagemodel->getOrganization($code);
            if ($company) {
                $sess_array['unique_code'] = $company->unique_code;
                $sess_array['company_user_id'] = $company->user_id;
                $sess_array['company_id'] = $company->conpany_id;
                $sess_array['company_name'] = $company->company_name;
                $this->session->set_userdata('company', $sess_array);
                redirect(base_url());
            } 
        }


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
//        echo 'here'; exit();
        //Page Alias
        $alias = 'homepage';

        $segment_1 = $this->uri->uri_string();
        if ($segment_1) {
            if (!$lang_trigger) {
                $alias = str_replace('/', '', $segment_1);
            } else {
                $lang_arr = explode('/', $lang_uri);
                $lang_arr = array_slice($lang_arr, 1);
                $alias = implode('/', $lang_arr);
            }
        }

        /* ------------featured products------------- */
        $featuredProduct = $this->Productmodel->getFeaturedProduct();

        //Get Page Details
        $page = array();
        $page = $this->Pagemodel->getDetails($alias, $lang);
//        print_r($page); exit();
        if (!$page) {
            $this->utility->show404();
            return;
        }

        $this->setPage($page);

        //Compiled blocks
        $compiled_blocks = array();
        $compiled_blocks = $this->Pagemodel->compiledBlocks($page);
//        print_r($compiled_blocks);
//        exit;
        //fetch page languages
        $languages = array();
        $languages = $this->Pagemodel->getAllLanguages($page, $lang);

// categories for homepage
        $categories = $this->Categorymodel->getTopCategoriesforHomepage(10);
        

        $breadcrumbs = array();
        $breadcrumbs = $this->Pagemodel->breadcrumbs($page['page_id']);

        $slides = array();
        $slides = $this->Slidemodel->ListHomeSlides();
        $attributes = gParam('attributes', array());



        //tesimonials fetching
        $topTestimonial = $this->Testimonialmodel->listLatestTen(10);
        $shell = array();
        $inner = array();
        $inner['page'] = $page;
        $inner['breadcrumbs'] = $breadcrumbs;
        $inner['featuredProduct'] = $featuredProduct;
        $inner['languages'] = $languages;
        $inner['categories'] = $categories;
        $inner['slides'] = $slides;
        $inner['testimonial'] = $topTestimonial;



        if ($page['frontend_modules']) {
            $modules = explode(',', $page['frontend_modules']);
            foreach ($modules as $page_module) {
                $this->load->library("page_modules/" . $page_module, array('page' => $page));
                $module_name = "module_$page_module";
                $inner[$module_name] = $this->$module_name;
            }
        }

        $compiledblocks = array();
        foreach ($compiled_blocks as $key => $val) {
            $compiledblocks[$key] = $val;
            $inner[$key] = $val;
        }


        $inner['compiledblocks'] = $compiledblocks;
        //     $inner['comapny'] = $company;

        foreach ($inner as $key => $val) {
            $shell[$key] = $val;
        }
        //File Name & File Path
        $file_name = str_replace('/', '_', $page['page_uri']);

        $file_path = "application/views/themes/" . THEME . "/cms/" . $file_name . ".php";
//echo $file_path;
        //Meta
        $meta_file = "application/views/themes/" . THEME . "/meta/" . $file_name . ".php";
        if (file_exists($meta_file)) {
            $this->html->addMeta($this->load->view("themes/" . THEME . "/meta/cms/" . $file_name, array('page' => $page), TRUE));
        } else {
            //echo "themes/" . THEME . "/meta/default";
            $this->html->addMeta($this->load->view("themes/" . THEME . "/meta/default", array('page' => $page), TRUE));
        }
        //Assets
//        if (file_exists("application/views/themes/" . THEME . "/headers/cms/" . $file_name . ".php")) {
//            $this->assets->loadFromFile("themes/" . THEME . "/headers/cms/" . $file_name);
//        }
//  

        $shell['INFO'] = (!isset($shell['INFO'])) ?
                $this->session->flashdata('message') : $shell['INFO'];
        if (file_exists($file_path)) {

            $shell['contents'] = $this->load->view("themes/" . THEME . "/cms/" . $file_name, $inner, true);
        } else if ($this->COMPANY == TRUE && $inner['page']['template_alias'] == 'homepage') {

            $shell['contents'] = $this->load->view("themes/" . THEME . "/cms/" . 'default', $inner, true);
        } else if ($this->COMPANY == FALSE && $inner['page']['template_alias'] == 'homepage') {

            $inner['page']['page_contents'] = '';
            $shell['contents'] = $this->load->view("themes/" . THEME . "/cms/" . 'homepage-global.php', $inner, true);
        } else if ($this->COMPANY == FALSE && isset($inner['page']['template_alias'])) {
            $shell['contents'] = $page['page_contents'];
            $shell['compiledblocks'] = $inner['compiledblocks'];
        } else {

            $this->load->view("themes/" . THEME . "/cms/" . 'pages', $inner, true);
        }
        if ($this->COMPANY == TRUE && $page['page_uri'] == 'homepage') {

            $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
        } else {
            if ($page['page_uri'] == 'homepage-global' || $page['page_uri'] == 'homepage') {
                

                $this->load->view("themes/" . THEME . "/templates/default-homepage", $shell);
            } else {
                $this->load->view("themes/" . THEME . "/templates/" . $page['template_alias'], $shell);
            }
        }
    }

}
