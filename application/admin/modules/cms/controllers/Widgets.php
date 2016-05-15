<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Widgets extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->is_admin_protected = TRUE;
	}

	//**********************validation start**************
	function valid_image($str) {
		if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
			$this->form_validation->set_message('valid_image', ' Image Field is required.');
			return FALSE;
		}
		$imginfo = @getimagesize($_FILES['image']['tmp_name']);

		if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
			$this->form_validation->set_message('valid_image', 'Only GIF, JPG and PNG images are accepted');
			return FALSE;
		}
		return TRUE;
	}

	function validImage($str) {
		if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

			$imginfo = @getimagesize($_FILES['image']['tmp_name']);
			if (!$imginfo) {
				$this->form_validation->set_message('validImage', 'Only image files are allowed');
				return false;
			}

			if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
				$this->form_validation->set_message('validImage', 'Only GIF, JPG and PNG Images are accepted.');
				return FALSE;
			}
		}
		return TRUE;
	}

	//***************validation end****************

	function index($pid = 0, $offset = 0) {
		$this->load->model('Widgetmodel');
		$this->load->model('Pagemodel');
		
		
		

		//Get page Detail
		$page = array();
		$page = $this->Pagemodel->detail($pid);
		if (!$page) {
			$this->utility->show404();
			return;
		}

		//Setup pagination
		$perpage = 200;
		$config['base_url'] = base_url() . "cms/widgets/index/$pid/";
		$config['uri_segment'] = 5;
		$config['total_rows'] = $this->Widgetmodel->countAll($pid);
		$config['per_page'] = $perpage;
		$this->pagination->initialize($config);

		//list all widgets
		$widgets = array();
		$widgets = $this->Widgetmodel->listAll($pid, $offset, $perpage);

		//render view
		$inner = array();
		$inner['widgets'] = $widgets;
		$inner['page'] = $page;
		$inner['pagination'] = $this->pagination->create_links();

		$page = array();
		$page['content'] = $this->load->view('widgets/listing', $inner, TRUE);
		$this->load->view($this->shellFile, $page);
	}

	function add($pid = false) {
		$this->load->model('Widgetmodel');
		$this->load->model('Pagemodel');
		
		


		//Get page Detail
		$page = array();
		$page = $this->Pagemodel->detail($pid);
		if (!$page) {
			$this->utility->show404();
			return;
		}

		$widget_types = array();
		$widget_types = $this->Widgetmodel->listWidgetTypes();

		if ($this->form_validation->run() == FALSE) {
			$inner = array();
			$inner['page'] = $page;
			$inner['widget_types'] = $widget_types;

			$page = array();
			$page['content'] = $this->load->view('widgets/add-1', $inner, TRUE);
			$this->load->view($this->shellFile, $page);
		} else {
			$this->Blockmodel->insertRecord($page_details);

			$this->session->set_flashdata('SUCCESS', 'block_added');

			redirect("cms/block/index/$pid", "location");
			exit();
		}
	}

	//function add block
	function add_2($pid = false, $wid = false) {
		$this->load->model('Widgetmodel');
		$this->load->model('Pagemodel');
		$this->load->model('Menumodel');
		
		

		//Get page Detail
		$page = array();
		$page = $this->Pagemodel->detail($pid);
		if (!$page) {
			$this->utility->show404();
			return;
		}

		$widget_type = array();
		$widget_type = $this->Widgetmodel->getWidgetType($wid);
		if (!$widget_type) {
			$this->utility->show404();
			return;
		}


		// $function = "_{$widget_type['widget_type_alias']}";
		//$this->$function($page['page_id'], $widget_type['widget_type_id']);
		//fetch the widget fields
		$widget_fields = array();
		$widget_fields = $this->Widgetmodel->getWidgetTypeFields($widget_type['widget_type_id']);

		//list all block
		$menu = array();
		$menu[''] = 'Select Menu';
		$rs = $this->Menumodel->listAll();
		foreach ($rs as $item) {
			$menu[$item['menu_alias']] = $item['menu_alias'];
		}

		//All forms
		$forms = array();
		$forms[''] = ' - Select - ';
		$forms_rs = $this->db->get('form');
		foreach ($forms_rs->result_array() as $row) {
			$forms[$row['form_alias']] = $row['form_name'];
		}

		$locations = array();
		$locations[''] = ' - Select - ';
		$rs = $this->Widgetmodel->getWidgetLocations();
		foreach ($rs as $row) {
			$locations[$row['widget_location_id']] = $row['widget_location'];
		}

		//validation check
		$this->form_validation->set_rules('widget_location_id', 'Widget Location', 'trim|required');
		$this->form_validation->set_rules('widget_name', 'Widget Name', 'trim|required');
		//$this->form_validation->set_rules('widget_alias', 'Widget Alias', 'trim|required');
		foreach ($widget_fields as $field) {
			if ($field['widget_field_is_upload'] == 0) {
				$this->form_validation->set_rules("{$field['widget_field_name']}", "{$field['widget_field_label']}", "trim|{$field['widget_field_validations']}");
			} else {
				$this->form_validation->set_rules("v_{$field['widget_field_name']}", "{$field['widget_field_label']}", "trim|{$field['widget_field_validations']}|callback_valid_image");
			}
		}
		/* switch ($widget_type['widget_type_alias']) {
		  case 'form':
		  $this->form_validation->set_rules('form', 'Form', 'trim|required');
		  break;
		  } */

		$this->form_validation->set_error_delimiters('<li>', '</li>');

		if ($this->form_validation->run() == FALSE) {
			$inner = array();
			$shell = array();
			$inner['page'] = $page;
			$inner['widget_type'] = $widget_type;
			$inner['locations'] = $locations;
			$inner['forms'] = $forms;
			$inner['menu'] = $menu;
			$shell['content'] = $this->load->view("widgets/{$widget_type['widget_type_alias']}/add", $inner, TRUE);
			$this->load->view($this->shellFile, $shell);
		} else {
			$this->Widgetmodel->addWidget($page, $widget_type, $widget_fields);

			$this->session->set_flashdata('SUCCESS', 'widget_added');

			redirect("cms/widgets/index/$pid");
			exit();
		}
	}

	/* function _text($pid = false, $wid = false) {
	  $this->load->model('Widgetmodel');
	  $this->load->model('Pagemodel');
	  
	  

	  //Get page Detail
	  $page = array();
	  $page = $this->Pagemodel->detail($pid);
	  if (!$page) {
	  $this->utility->show404();
	  return;
	  }

	  $widget_type = array();
	  $widget_type = $this->Widgetmodel->getWidgetType($wid);
	  if (!$widget_type) {
	  $this->utility->show404();
	  return;
	  }

	  //fetch the widget fields
	  $widget_fields = array();
	  $widget_fields = $this->Widgetmodel->getWidgetTypeFields($widget_type['widget_type_id']);


	  $locations = array();
	  $locations[''] = ' - Select - ';
	  $rs = $this->Widgetmodel->getWidgetLocations();
	  foreach ($rs as $row) {
	  $locations[$row['widget_location_id']] = $row['widget_location'];
	  }

	  //validation check
	  $this->form_validation->set_rules('widget_location_id', 'Widget Location', 'trim|required');
	  $this->form_validation->set_rules('widget_name', 'Widget Name', 'trim|required');
	  $this->form_validation->set_rules('widget_alias', 'Widget Alias', 'trim|required');
	  $this->form_validation->set_rules('widget_text_content', 'Text Contents', 'trim');

	  $this->form_validation->set_error_delimiters('<li>', '</li>');

	  if ($this->form_validation->run() == FALSE) {
	  $inner = array();
	  $shell = array();
	  $inner['page'] = $page;
	  $inner['widget_type'] = $widget_type;
	  $inner['locations'] = $locations;
	  $shell['content'] = $this->load->view('widgets/text/add', $inner, TRUE);
	  $this->load->view($this->shellFile, $shell);
	  } else {
	  $this->Widgetmodel->addTextWidget($page, $widget_type);

	  $this->session->set_flashdata('SUCCESS', 'widget_added');

	  redirect("cms/widgets/index/$pid");
	  exit();
	  }
	  } */

	//function add block
	function edit($wid = false) {
		$this->load->model('Widgetmodel');
		$this->load->model('Pagemodel');
		$this->load->model('Menumodel');
		
		

		//widget details
		$widget = array();
		$widget = $this->Widgetmodel->getWidgetDetails($wid);
		if (!$widget) {
			$this->utility->show404();
			return;
		}


		//Get page Detail
		$page = array();
		$page = $this->Pagemodel->detail($widget['page_id']);
		if (!$page) {
			$this->utility->show404();
			return;
		}

		$widget_data = array();
		$widget_data = $this->Widgetmodel->fetchWidgetData($widget);
		//print_r($widget_data);
		//fetch the widget fields
		$widget_fields = array();
		$widget_fields = $this->Widgetmodel->getWidgetTypeFields($widget['widget_type_id']);

		//list all block
		$menu = array();
		$menu[''] = 'Select Menu';
		$rs = $this->Menumodel->listAll();
		foreach ($rs as $item) {
			$menu[$item['menu_alias']] = $item['menu_alias'];
		}

		//All forms
		$forms = array();
		$forms[''] = ' - Select - ';
		$forms_rs = $this->db->get('form');
		foreach ($forms_rs->result_array() as $row) {
			$forms[$row['form_alias']] = $row['form_name'];
		}

		$locations = array();
		$locations[''] = ' - Select - ';
		$rs = $this->Widgetmodel->getWidgetLocations();
		foreach ($rs as $row) {
			$locations[$row['widget_location_id']] = $row['widget_location'];
		}

		//validation check
		$this->form_validation->set_rules('widget_location_id', 'Widget Location', 'trim|required');
		$this->form_validation->set_rules('widget_name', 'Widget Name', 'trim|required');
		//$this->form_validation->set_rules('widget_alias', 'Widget Alias', 'trim|required');
		foreach ($widget_fields as $field) {
			if ($field['widget_field_is_upload'] == 0) {
				$this->form_validation->set_rules("{$field['widget_field_name']}", "{$field['widget_field_label']}", "trim|{$field['widget_field_validations']}");
			} else {
				$this->form_validation->set_rules("v_{$field['widget_field_name']}", "{$field['widget_field_label']}", "trim|{$field['widget_field_validations']}|callback_validImage");
			}
		}
		/* switch ($widget_type['widget_type_alias']) {
		  case 'form':
		  $this->form_validation->set_rules('form', 'Form', 'trim|required');
		  break;
		  } */

		$this->form_validation->set_error_delimiters('<li>', '</li>');

		if ($this->form_validation->run() == FALSE) {
			$inner = array();
			$shell = array();
			$inner['page'] = $page;
			$inner['widget'] = $widget;
			$inner['widget_data'] = $widget_data;
			$inner['locations'] = $locations;
			$inner['forms'] = $forms;
			$inner['menu'] = $menu;
			$shell['content'] = $this->load->view("widgets/{$widget['widget_type_alias']}/edit", $inner, TRUE);
			$this->load->view($this->shellFile, $shell);
		} else {
			$this->Widgetmodel->editWidget($widget, $widget_data, $widget_fields);

			$this->session->set_flashdata('SUCCESS', 'widget_updated');

			redirect("cms/widgets/index/{$widget['page_id']}");
			exit();
		}
	}

	//
	function sort($pid) {
		$this->load->model('Widgetmodel');
		$this->load->model('Pagemodel');
		
		

		//Get page Detail
		$page = array();
		$page = $this->Pagemodel->detail($pid);
		if (!$page) {
			$this->utility->show404();
			return;
		}



		$left_widget = array();
		$left_widget = $this->Widgetmodel->fetchPageWidgets($page, 'left_column');

		$right_widget = array();
		$right_widget = $this->Widgetmodel->fetchPageWidgets($page, 'right_column');

		//render view
		$inner = array();
		$inner['left_widget'] = $left_widget;
		$inner['right_widget'] = $right_widget;
		$inner['page'] = $page;

		$page = array();
		$page['content'] = $this->load->view('widgets/sort-widgets', $inner, TRUE);
		$this->load->view('shell-overlay', $page);
	}

	function delete($wid = false) {
		$this->load->model('Widgetmodel');
		$this->load->model('Pagemodel');
		$this->load->model('Menumodel');
		
		

		//widget details
		$widget = array();
		$widget = $this->Widgetmodel->getWidgetDetails($wid);
		if (!$widget) {
			$this->utility->show404();
			return;
		}

		$widget_data = array();
		$widget_data = $this->Widgetmodel->fetchWidgetData($widget);

		$this->Widgetmodel->deleteRecord($widget, $widget_data);

		$this->session->set_flashdata('SUCCESS', 'widget_deleted');
		redirect("cms/widgets/index/{$widget['page_id']}");
		exit();
	}

}

?>