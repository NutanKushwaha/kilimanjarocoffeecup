<?php

class Widgetmodel extends CI_Model {

	function __construct() {
		// Call the Model constructor
		parent::__construct();
	}

	function countAll($pid) {
		$this->db->where('page_id', $pid);
		$this->db->where('widget_is_active', 1);
		$this->db->from('page_widget');
		return $this->db->count_all_results();
	}

	function listAll($pid, $offset = FALSE, $limit = FALSE) {
		if ($offset)
			$this->db->offset($offset);
		if ($limit)
			$this->db->limit($limit);

		$this->db->where('page_id', $pid);
		$this->db->where('widget_is_active', 1);
		//$this->db->order_by('widget_name', 'ASC');
		$this->db->order_by('widget_sort_order', 'ASC');
		$query = $this->db->get('page_widget');
		return $query->result_array();
	}

	function listWidgetTypes() {
		$this->db->where('widget_type_active', 1);
		$query = $this->db->get('widget_type');
		return $query->result_array();
	}

	function getWidgetType($id) {
		$this->db->where('widget_type_id', $id);
		$rs = $this->db->get('widget_type');
		return $rs->row_array();
	}

	function getWidgetTypeFields($id) {
		$this->db->where('widget_type_id', $id);
		$rs = $this->db->get('widget_field');
		return $rs->result_array();
	}

	function getWidgetLocations() {
		$rs = $this->db->get('widget_location');
		return $rs->result_array();
	}

	function maxOrder($page, $widget_location_id) {
		$this->db->where('page_id', $page['page_id']);
		$this->db->where('widget_location_id', $widget_location_id);
		$this->db->select_max('widget_sort_order');
		$rs = $this->db->get('page_widget');
		if ($rs->num_rows() > 0) {
			$row = $rs->row_array();
			return $row['widget_sort_order'] + 1;
		}
		return 0;
	}

	function getWidgetDetails($wid) {
		$this->db->from('page_widget');
		$this->db->join('widget_type', 'widget_type.widget_type_id = page_widget.widget_type_id');
		$this->db->where('widget_id', $wid);
		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
			return $rs->row_array();
		}
		return false;
	}

	//fetch the page widget filed data
	function fetchWidgetData($widget) {
		$this->db->from('widget_field_data');
		$this->db->join('widget_field', 'widget_field.widget_field_id = widget_field_data.widget_field_id');
		$this->db->where('widget_id', $widget['widget_id']);
		$this->db->where('widget_type_id', $widget['widget_type_id']);
		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
			foreach ($rs->result_array() as $item) {
				$data[$item['widget_field_name']] = $item['widget_field_data'];
			}
			return $data;
		}
	}

	//function insert record
	function addWidget($page, $widget_type, $widget_fields) {
		$order = $this->maxOrder($page, $this->input->post('widget_location_id', TRUE));

		$data = array();
		$data['page_id'] = $page['page_id'];
		$data['widget_sort_order'] = $order;
		$data['widget_type_id'] = $widget_type['widget_type_id'];
		$data['widget_location_id'] = $this->input->post('widget_location_id', TRUE);
		$data['widget_name'] = $this->input->post('widget_name', TRUE);
		$data['widget_alias'] = url_title(strtolower($this->input->post('widget_name', TRUE)));
		$data['widget_is_active'] = 1;
		$status = $this->db->insert('page_widget', $data);

		$widget_id = $this->db->insert_id();



		foreach ($widget_fields as $field) {

			$field_data = array();
			$field_data['widget_id'] = $widget_id;
			$field_data['widget_field_id'] = $field['widget_field_id'];
			if ($field['widget_field_is_upload'] == 0) {
				$field_data['widget_field_data'] = $this->input->post("{$field['widget_field_name']}", TRUE);
			} else {
				//Upload block image
				$config['upload_path'] = $this->config->item('WIDGET_PATH');
				$config['allowed_types'] = '*';
				$config['overwrite'] = FALSE;
				$this->load->library('upload', $config);
				if (count($_FILES) > 0) {
					//Check for valid image upload
					if ($_FILES[$field['widget_field_name']]['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES[$field['widget_field_name']]['tmp_name'])) {
						if (!$this->upload->do_upload($field['widget_field_name'])) {
							show_error($this->upload->display_errors('<p class="err">', '</p>'));
							return FALSE;
						} else {
							$upload_data = $this->upload->data();
							$field_data['widget_field_data'] = $upload_data['file_name'];
						}
					}
				}
			}
			$this->db->insert('widget_field_data', $field_data);
		}
	}

	//function insert record
	function editWidget($widget, $widget_data, $widget_fields) {

		$data = array();
		$data['widget_location_id'] = $this->input->post('widget_location_id', TRUE);
		$data['widget_name'] = $this->input->post('widget_name', TRUE);
		$data['widget_alias'] = url_title(strtolower($this->input->post('widget_name', TRUE)));
		$this->db->where('widget_id', $widget['widget_id']);
		$this->db->update('page_widget', $data);

		foreach ($widget_fields as $field) {
			$field_data = array();
			if ($field['widget_field_is_upload'] == 0) {
				$field_data['widget_field_data'] = $this->input->post("{$field['widget_field_name']}", TRUE);
			} else {
				//Upload block image
				$config['upload_path'] = $this->config->item('WIDGET_PATH');
				$config['allowed_types'] = '*';
				$config['overwrite'] = FALSE;
				$this->load->library('upload', $config);
				if (count($_FILES) > 0) {
					//Check for valid image upload
					if ($_FILES[$field['widget_field_name']]['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES[$field['widget_field_name']]['tmp_name'])) {
						if (!$this->upload->do_upload($field['widget_field_name'])) {
							show_error($this->upload->display_errors('<p class="err">', '</p>'));
							return FALSE;
						} else {
							$upload_data = $this->upload->data();
							$field_data['widget_field_data'] = $upload_data['file_name'];

							//delete the  image
							$path = $this->config->item('WIDGET_PATH');
							$filename = $path . $widget_data['image'];
							if (file_exists($filename)) {
								@unlink($filename);
							}
						}
					}
				}
			}

			if ($field_data) {
				$this->db->where('widget_id', $widget['widget_id']);
				$this->db->where('widget_field_id', $field['widget_field_id']);
				$this->db->update('widget_field_data', $field_data);
			}
		}
	}

	//fetch the widget
	function fetchPageWidgets($page, $alias) {
		$widgets = array();
		$this->db->from('page_widget');
		$this->db->join('widget_location', 'widget_location.widget_location_id = page_widget.widget_location_id');
		$this->db->where('page_id', $page['page_id']);
		$this->db->where('widget_location_alias', $alias);
		$this->db->order_by('widget_sort_order', 'ASC');
		$rs = $this->db->get();
		return $rs->result_array();
	}

	function deleteRecord($widget, $widget_data) {
		//delete from widget_field_data\
		$this->db->where('widget_id', $widget['widget_id']);
		$this->db->delete('widget_field_data');

		//unlink the image if any
		if (isset($widget_data['image']) && $widget_data['image'] != '') {
			//delete the  image
			$path = $this->config->item('WIDGET_PATH');
			$filename = $path . $widget_data['image'];
			if (file_exists($filename)) {
				@unlink($filename);
			}
		}

		//delete the page widgets
		$this->db->where('widget_id', $widget['widget_id']);
		$this->db->delete('page_widget');
	}

}

?>
