<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class contact_sale_frm {

	private $CI;
	private $settings;
	private $db_settings;
	private $widget;

	function __construct() {
		$this->CI = & get_instance();
	}

	function init($widget) {
		$this->widget = $widget;
	}
	
	function install($page_id) {
		$data = array();
		$data['widget_setting_value'] = '1';
		$data['widget_id'] = $this->widget['widget_id'];
		$data['page_id'] = $page_id;
		$data['widget_setting_label'] = 'is_sdf';
		$this->CI->db->insert('widget_setting', $data);
		
		$data = array();
		$data['widget_setting_value'] = '2012';
		$data['widget_id'] = $this->widget['widget_id'];
		$data['page_id'] = $page_id;
		$data['widget_setting_label'] = 'vehicle_year';
		$this->CI->db->insert('widget_setting', $data);
		
		$data = array();
		$data['widget_setting_value'] = 'Honda';
		$data['widget_id'] = $this->widget['widget_id'];
		$data['page_id'] = $page_id;
		$data['widget_setting_label'] = 'vehicle_make';
		$this->CI->db->insert('widget_setting', $data);
		
		$data = array();
		$data['widget_setting_value'] = 'Accord';
		$data['widget_id'] = $this->widget['widget_id'];
		$data['page_id'] = $page_id;
		$data['widget_setting_label'] = 'vehicle_model';
		$this->CI->db->insert('widget_setting', $data);
		
		$data = array();
		$data['widget_setting_value'] = 'contact_sales';
		$data['widget_id'] = $this->widget['widget_id'];
		$data['page_id'] = $page_id;
		$data['widget_setting_label'] = 'form_alias';
		$this->CI->db->insert('widget_setting', $data);
	}
	
	function uninstall($page) {
		$this->CI->db->where('widget_id', $this->widget['widget_id']);
		$this->CI->db->where('page_id', $page['page_id']);
		$this->CI->db->delete('widget_setting');
		
	}

	function settings($page) {
		$settings = array(
			'is_sdf' => 0,
			'vehicle_year' => '2012',
			'vehicle_make' => 'Honda',
			'vehicle_model' => 'Accord',
			'form_alias' => 'contact_sales'
		);

		$db_settings = array();
		$this->CI->db->where('widget_id', $this->widget['widget_id']);
		$this->CI->db->where('page_id', $page['page_id']);
		$rs = $this->CI->db->get('widget_setting');
		if($rs->num_rows() > 0) {
			foreach($rs->result_array() as $row) {
				$db_settings[$row['widget_setting_label']] = $row['widget_setting_value'];
			}
		}

		foreach($settings as $key => $val) {
			if(isset($db_settings[$key])) {
				$settings[$key] = $db_settings[$key];
			}
		}

		$this->settings = $settings;
		$this->db_settings = $db_settings;

		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('setting/formmodel');

		$this->CI->form_validation->set_rules('is_sdf', 'Email Type', 'trim|required');
		$this->CI->form_validation->set_error_delimiters('<li>', '</li>');
		
		//get all testimonials
		$form_emails = array();
		$rs = $this->CI->formmodel->listAll();
		foreach ($rs as $item) {
			$form_emails[$item['form_alias']] = $item['form_name'];
		}

	

		if ($this->CI->form_validation->run() == FALSE) {
			$inner = array();
			$inner['settings'] = $this->settings;
			$inner['form_emails'] = $form_emails;
			$this->CI->load->view('setting/widget/contact-sale/settings', $inner);
		} else {
			$settings = $this->settings;
			foreach($settings as $key => $val) {
				if($this->CI->input->post($key, TRUE) || $this->CI->input->post($key, TRUE) == '0') {
					$data = array();
					$data['widget_setting_value'] = $this->CI->input->post($key, TRUE);
					if(isset($this->db_settings[$key])) {
						$this->CI->db->where('widget_setting_label', $key);
						$this->CI->db->where('widget_id', $this->widget['widget_id']);
						$this->CI->db->where('page_id', $page['page_id']);
						$this->CI->db->update('widget_setting', $data);
					}else {
						$data['widget_id'] = $this->widget['widget_id'];
						$data['page_id'] = $page['page_id'];
						$data['widget_setting_label'] = $key;
						$this->CI->db->insert('widget_setting', $data);
					}
				}
			}
			
			$this->CI->session->set_flashdata('SUCCESS', 'settings_updated');
			redirect(uri_string());
			exit();
		}
	}
}

?>