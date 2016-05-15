<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Playlist {

	private $CI;
	private $page;
	private $module_name = 'Playlist';
	private $module_alias = 'Playlist';

	function __construct($params) {
		$this->CI = & get_instance();
		$this->page = $params['page'];
		$this->init();
	}

	function init() {
		$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_rules('playlist_id', 'Playlist ID', 'trim');
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

		$current_playlist = array();
		$current_playlist = $this->CI->Pagemodel->getPageData($this->page, 'youtube_playlist');

		$show_playlist = array();
		$show_playlist = $this->CI->Pagemodel->getPageData($this->page, 'show_id');

		$inner = array();
		$inner['page'] = $this->page;
		$inner['current_playlist'] = $current_playlist;
		$inner['show_playlist'] = $show_playlist;

		return $this->CI->load->view('page_modules/youtube_playlist/playlist_edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$youtube_playlist = array();
		$youtube_playlist = $this->CI->Pagemodel->getPageData($this->page, 'youtube_playlist');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $youtube_playlist['page_data_id']);
		$this->CI->db->delete('page_data');
	}

	function actionUpdate() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$youtube_playlist = array();
		$youtube_playlist = $this->CI->Pagemodel->getPageData($this->page, 'youtube_playlist');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $youtube_playlist['page_data_id']);
		$this->CI->db->delete('page_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'youtube_playlist';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $this->CI->input->post('playlist_id', true);
		$data['page_id'] = $page['page_id'];
		$this->CI->db->insert('page_data', $data);
	}

}

?>