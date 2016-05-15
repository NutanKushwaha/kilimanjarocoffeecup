<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News extends CMS_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($offset = false) {
        $this->load->model('Newsmodel');
        $this->load->library('form_validation');
        $this->load->model('cms/Pagemodel');
        $this->load->model('cms/Templatemodel');
        $this->load->helper('form');
        $this->load->library('pagination');
        $this->load->helper('text');

        //Get Page Details
        $page = array();
        $page = $this->Pagemodel->getDetails('news');
        if (!$page) {
            $this->utility->show404();
            return;
        }
        $this->setPage($page);
        $this->assets->addHeadJS('js/jquery.mCustomScrollbar.js', 200);
        $this->assets->addHeadJS('js/mcscroll.js', 200);
        $this->assets->addCSS('js/jquery.mCustomScrollbar.css', 200);
        //Setup pagination
        $perpage = 20;
        $config['base_url'] = base_url() . "news/index/";
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Newsmodel->countAll();
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);


        //Get all news
        $news = array();
        $news = $this->Newsmodel->listAll($offset, $perpage);
//        echo '<pre>';
//        print_r($news);
//        exit;
        //Variables
        $inner = array();
        $shell = array();

        $compiled_blocks = array();
        $compiled_blocks = $this->Pagemodel->compiledBlocks($page);
        foreach ($compiled_blocks as $key => $val) {
            $compiledblocks[] = $val;
            $inner[$key] = $val;
        }
        $inner['page'] = $page;
        $inner['news'] = $news;
        $inner['pagination'] = $this->pagination->create_links();


        if ($page['frontend_modules']) {
            $modules = explode(',', $page['frontend_modules']);
            foreach ($modules as $page_module) {
                $this->load->library("page_modules/" . $page_module, array('page' => $page));
                $module_name = "module_$page_module";
                $inner[$module_name] = $this->$module_name;
            }
        }
        $shell['contents'] = $this->load->view('news-listing', $inner, true);
        $this->load->view("themes/" . THEME . "/templates/" . $page['template_name'], $shell);
    }

    function details($alias = false) {
        $this->load->model('Newsmodel');
        $this->load->model('cms/Pagemodel');
        $this->load->model('cms/Templatemodel');
        
        $page = array();
        $page = $this->Pagemodel->getDetails('news');
        if (!$page) {
            $this->utility->show404();
            return;
        }
        $this->setPage($page);
        
        $compiled_blocks = array();
        $compiled_blocks = $this->Pagemodel->compiledBlocks($page);
        foreach ($compiled_blocks as $key => $val) {
            $compiledblocks[] = $val;
            $inner[$key] = $val;
        }
        //Get news
        $news = array();
        $news = $this->Newsmodel->getDetails($alias);

        $inner = array();
        $shell = array();
        $inner['news'] = $news;
        $inner['compiledblocks'] = $compiledblocks;
        $shell['contents'] = $this->load->view('news-detail', $inner, true);
        $this->load->view("themes/" . THEME . "/templates/news-details", $shell);
    }

    function test() {
        $data = array();
        //$this->load->model('Productmodel');
        $this->load->model('Reviewsmodel');
        $this->load->library('form_validation');
        $status = $this->Reviewsmodel->insertRecord();

        if ($status) {
            echo 'Thank you for your reviews. We appericiate it .';
            exit();
        }
    }

}

?>