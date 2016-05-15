<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Http {

    function __construct() {
        log_message('debug', "HTTP Class Initialized");
    }

    function isSSL() {
        $https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 0;
        $https = strtolower($https);
        if (($https == 'on' || $https == 1)) {
            return true;
        }

        return false;
    }

    function baseURL() {
        $url = base_url();
        if ($this->isSSL()) {
            return str_replace('http://', 'https://', $url);
        }

        return $url;
    }

    function baseURLNoSSL() {
        $url = $this->baseURL();
        return str_replace('https://', 'http://', $url);
    }

    function baseURLSSL() {
        $url = $this->baseURL();
        return str_replace('http://', 'https://', $url);
    }

    function isMobile() {
        $CI = & get_instance();
        return $CI->agent->is_mobile();
    }

    function show404($params = array()) {
        $CI = & get_instance();

        set_status_header('404');

        $lang = 'en';

        //Get Page Details
        $page = array();
        $page = $CI->Pagemodel->getDetails('404', $lang);
        if (!$page) {
            show_404();
            return;
        }

        //Compiled blocks
        $compiled_blocks = array();
        $compiled_blocks = $CI->Pagemodel->compiledBlocks($page);

        //fetch page languages
        $languages = array();
        $languages = $CI->Pagemodel->getAllLanguages($page, $lang);

        $breadcrumbs = array();
        $breadcrumbs = $CI->Pagemodel->breadcrumbs($page['page_id']);

        //Variables
        $shell = array();
        $inner = array();
        $inner['page'] = $page;
        $inner['breadcrumbs'] = $breadcrumbs;
        $inner['languages'] = $languages;
        if ($page['admin_modules']) {
            $modules = explode(',', $page['frontend_modules']);
            foreach ($modules as $page_module) {
                $CI->load->library("page_modules/" . $page_module, array('page' => $page));
                $module_name = "module_$page_module";
                $inner[$module_name] = $CI->$module_name;
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
            $CI->html->addMeta($CI->load->view("themes/" . THEME . "/meta/cms/" . $file_name, array('page' => $page), TRUE));
        } else {
            $CI->html->addMeta($CI->load->view("themes/" . THEME . "/meta/default", array('page' => $page), TRUE));
        }

        //Assets
        if (file_exists("application/views/" . THEME . "/headers/cms/" . $file_name . ".php")) {
            $CI->assets->loadFromFile("themes/" . THEME . "/headers/cms/" . $file_name);
        }

        if (file_exists($file_path)) {
            $shell['contents'] = $CI->load->view("themes/" . THEME . "/cms/" . $file_name, $inner, true);
        } else {
            $shell['contents'] = $CI->load->view("themes/" . THEME . "/cms/" . 'default', $inner, true);
        }
        $CI->load->view("themes/" . THEME . "/templates/{$page['template_alias']}", $shell);

        /* $shell = array();
          $shell['contents'] = $CI->load->view("themes/" . THEME . "/404", array(), true);
          $CI->load->view("themes/" . THEME . "/templates/default", $shell); */
    }

}

?>