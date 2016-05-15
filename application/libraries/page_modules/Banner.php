<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Banner {

    private $page;
    private $module_name = 'banner';

    function __construct($params) {
        $CI = & get_instance();
        $this->page = $params['page'];
        $this->init();
    }

    function init() {
        $CI = & get_instance();
        $CI->load->model('Pagemodel');
        $banner = $CI->Pagemodel->getPageModuleSettings($this->page, 'banner');
        $module_name = "module_" . $this->module_name;
        $CI->$module_name = $banner;
    }

    function getName() {
        return $this->module_name;
    }

    function addView() {
        return "add";
    }

    //function to edit record
    function editView() {
        $CI = & get_instance();
        $CI->load->library('form_validation');
        $CI->load->helper('form');
        $CI->load->model('Pagemodel');

        $banner = array();
        $banner = $CI->Pagemodel->getPageData($this->page, 'banner');

        $show_banner = array();
        $show_banner = $CI->Pagemodel->getPageData($this->page, 'show_banner');

        $banner_new_window = array();
        $banner_new_window = $CI->Pagemodel->getPageData($this->page, 'new_window');

        $banner_link = array();
        $banner_link = $CI->Pagemodel->getPageData($this->page, 'banner_url');

        $inner = array();
        $inner['page'] = $this->page;
        $inner['show_banner'] = $show_banner;
        $inner['banner'] = $banner;
        $inner['banner_link'] = $banner_link;
        $inner['banner_new_window'] = $banner_new_window;

        //include APPPATH.'/modules/cms/views/page_modules/banner/edit.php';

        return $CI->load->view('page_modules/banner/edit', $inner, true);
    }
    
    function cms_banner() {
        $CI = & get_instance();
        $module_banner = $CI->Pagemodel->getPageModuleSettings($this->page, 'banner');
        if (isset($module_banner) && $module_banner && $module_banner !== '' && $module_banner->show_banner == 1) {
            return "style='background: url(".$CI->config->item("PAGE_DATA_IMAGE_URL") . $module_banner->banner.") no-repeat left top'";
        }
    }

}

?>