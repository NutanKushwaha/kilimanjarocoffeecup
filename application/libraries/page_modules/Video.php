<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Video {

    private $CI;
    private $page;
    private $module_name = 'video';

    function __construct($params) {
        $this->CI = & get_instance();
        $this->page = $params['page'];
        $this->init();
    }

    function init() {
        $this->CI->load->model('Pagemodel');
        $this->CI->load->model('video/Videomodel');
		$videos = array();
		$videos = $this->CI->Videomodel->getPageVideo($this->page);
		
		$module_name = "module_".$this->module_name;

		$this->CI->$module_name = $videos;
    }



}

?>