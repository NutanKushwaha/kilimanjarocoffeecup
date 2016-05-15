<?php

class Menumodel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	//Get menu details
	function detail($mid) {
		$this->db->from('menu');
		$this->db->where('menu_id', $mid);
		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
			return $rs->row_array();
		}
		return FALSE;
	}

	//Count All Records
	function countAll() {
		$this->db->from('menu');
		return $this->db->count_all_results();
	}

	//List All Records
	function listAll($offset = FALSE, $limit = FALSE) {
		if ($offset)
			$this->db->offset($offset);
		if ($limit)
			$this->db->limit($limit);

		$this->db->order_by('menu_alias', 'ASC');
		$rs = $this->db->get('menu');
		return $rs->result_array();
	}

	//add record
	function insertRecord() {
		$data = array();
		$data['menu_name'] = $this->input->post('menu_name', TRUE);
		$data['menu_alias'] = $this->input->post('menu_alias', TRUE);

		$this->db->insert('menu', $data);
	}

	//function update Record
	function updateRecord($menu) {
		$data = array();
		$data['menu_name'] = $this->input->post('menu_name', TRUE);
		$data['menu_alias'] = $this->input->post('menu_alias', TRUE);

		$this->db->where('menu_id', $menu['menu_id']);
		$this->db->update('menu', $data);
		return;
	}

	//enable page
	function enableRecord($menu_details) {
		$data = array();

		$data['is_active'] = 1;

		$this->db->where('menu_id', $menu_details['menu_id']);
		$this->db->update('menu', $data);
		return;
	}

	//disable page
	function disableRecord($menu_details) {
		$data = array();

		$data['is_active'] = 0;

		$this->db->where('menu_id', $menu_details['menu_id']);
		$this->db->update('menu', $data);
		return;
	}

	//function for delete record
	function deleteRecord($menu) {
		$this->db->where('menu_id', $menu['menu_id']);
		$this->db->delete('menu_item');

		$this->db->where('menu_id', $menu['menu_id']);
		$this->db->delete('menu');
		return;
	}

}

?>