<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map {

    private $page;
    private $module_name = 'map';

    function __construct($params) {

        $this->page = $params['page'];
        $this->init();
    }

    function init() {
        $CI = & get_instance();
        $CI->load->model('Pagemodel');
        $map = $CI->Pagemodel->getPageModuleSettings($this->page, 'map');

        $rs = $CI->db->get('marker');
        $markers = $rs->result_array();

        $module_name = "module_" . $this->module_name;
        $CI->$module_name = $markers;
    }

    function getName() {
        return $this->module_name;
    }

}

?>