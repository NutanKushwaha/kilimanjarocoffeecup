<?php

class Creditplansmodel extends CI_Model {

	function __construct() {
		// Call the Model constructor
		parent::__construct();
	}

	//get details of supplier
	function detail($sid) {
		$this->db->where('credit_plan_id', intval($sid));
		$rs = $this->db->get('credit_plan');
		if ($rs->num_rows() == 1) {
			return $rs->row_array();
		}
		return FALSE;
	}

	//count all suppliers
	function countAll() {
		$this->db->from('credit_plan');
		return $this->db->count_all_results();
	}

	//list all suppliers
	function listAll($offset = false, $limit = false) {
		if ($offset)
			$this->db->offset($offset);
		if ($limit)
			$this->db->limit($limit);
		$rs = $this->db->get('credit_plan');
		return $rs->result_array();
	}

	//function to insert record
	function insertRecord() {

		$data = array();
		$data['plan_name'] = $this->input->post('plan_name', TRUE);
		$data['credits'] = $this->input->post('credits', TRUE);
		$data['price'] = $this->input->post('price', TRUE);

		//insert into different table
		$this->db->insert('credit_plan', $data);
	}

	function updateRecord($cid) {
		$data = array();
		$data['plan_name'] = $this->input->post('plan_name', TRUE);
		$data['credits'] = $this->input->post('credits', TRUE);
		$data['price'] = $this->input->post('price', TRUE);
		$this->db->where('credit_plan_id', $cid);
		$this->db->update('credit_plan', $data);
	}

	//delete record
	function deleteRecord($cid) {
		$this->db->where('credit_plan_id', $cid);
		$this->db->delete('credit_plan');
	}

}

?>