<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Video {

	private $CI;
	private $page;
	private $module_name = 'Video';
	private $module_alias = 'Video';

	function __construct($params) {
		$this->CI = & get_instance();
		$this->page = $params['page'];
		//$this->init();
	}

	function getName() {
		return $this->module_name;
	}

	function getAlias() {
		return $this->module_alias;
	}

	function addView() {
		return "add";
	}

	//function to edit record
	function editView() {
		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('Pagemodel');

		$inner = array();
		$inner['page'] = $this->page;
		return $this->CI->load->view('cms/page_modules/video/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionUpdate() {
		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('Pagemodel');

		$inner = array();
		$inner['page'] = $this->page;
		return $this->CI->load->view('cms/page_modules/video/edit', $inner, true);
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$this->CI->load->model('video/Videomodel');
		$page = $this->page;

		$video_details = array();
		$video_details = $this->CI->Videomodel->listAll($page['page_id']);
		if (!empty($video_details)) {
			foreach ($video_details as $video) {
				//delete the previos data if any
				$this->CI->db->where('video_id', $video['video_id']);
				$this->CI->db->delete('video');
			}
		}
	}

}

?>