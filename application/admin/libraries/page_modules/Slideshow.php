<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Slideshow {

	private $CI;
	private $page;
	private $module_name = 'Slideshow';
	private $module_alias = 'Slideshow';

	function __construct($params) {
		$this->CI = & get_instance();
		$this->page = $params['page'];
		$this->init();
	}

	function init() {
		$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_rules('slideshow_id', 'Slide Show', 'trim');
		$this->CI->form_validation->set_rules('show_slideshow', 'Slide Show', 'trim|required');
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
		$this->CI->load->model('slideshow/Slideshowmodel');

		$current_slideshow = array();
		$current_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'slideshow');

		$show_slideshow = array();
		$show_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'show_slideshow');

		//fetch slideshow
		$slideshow = array();
		$slideshow[''] = '--Select Slideshow--';
		$slideshow_rs = $this->CI->Slideshowmodel->listAll(TRUE);
		foreach ($slideshow_rs as $row) {
			$slideshow[$row['slideshow_id']] = $row['slideshow_title'];
		}

		$inner = array();
		$inner['page'] = $this->page;
		$inner['slideshow'] = $slideshow;
		$inner['current_slideshow'] = $current_slideshow;
		$inner['show_slideshow'] = $show_slideshow;

		//include APPPATH.'/modules/cms/views/page_modules/slideshow/edit.php';
		return $this->CI->load->view('cms/page_modules/slideshow/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $this->CI->Pagemodel->getPageData($this->page, 'slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		$show_slideshow = array();
		$show_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'show_slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $show_slideshow['page_data_id']);
		$this->CI->db->delete('page_data');
	}

	function actionUpdate() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $this->CI->Pagemodel->getPageData($this->page, 'slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'slideshow';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $this->CI->input->post('slideshow_id', true);
		$data['page_id'] = $page['page_id'];
		$this->CI->db->insert('page_data', $data);

		$show_slideshow = array();
		$show_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'show_slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $show_slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'show_slideshow';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $this->CI->input->post('show_slideshow', true);
		$data['page_id'] = $page['page_id'];
		$this->CI->db->insert('page_data', $data);
	}

}

?>