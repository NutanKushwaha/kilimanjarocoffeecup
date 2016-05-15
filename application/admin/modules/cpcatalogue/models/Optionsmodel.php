<?php
class Optionsmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }
	
	//function get deatails of attributes
	function getDetails($oid) {
		$this->db->select('*');
		$this->db->from('options');
		$this->db->where('option_id', intval($oid));
		$rs = $this->db->get();
		if($rs->num_rows() == 1) {
			return $rs->row_array();
		}
		return false;
		
	}
	
	//count all product attributes
	function countAll($pid) {
		$this->db->select('*');
		$this->db->from('options');
		$this->db->where('product_id', intval($pid));
		return $this->db->count_all_results();	
	}
	
	//list all product attributes
	function listAll($pid,$offset = FALSE, $limit = FALSE) {
		if($offset) $this->db->offset($offset);
		if($limit) $this->db->limit($limit);
		
		$this->db->select('*');
		$this->db->from('options');
		$this->db->where('product_id', intval($pid));
		$query = $this->db->get();
        return $query->result_array();
	}
	
	//function insert records
	function insertRecord($product) {
		$data = array();
		$data['product_id'] = $product['product_id'];
		$data['option_name'] = $this->input->post('option_name',true);
		$data['option_label'] = $this->input->post('option_label',true);
		$data['option_field_type'] = $this->input->post('option_field_type',true);
		$data['is_required'] = $this->input->post('is_required', true);
		
		$this->db->insert('options', $data);
	}
	
	//function update records
	function updateRecord($options) {
		$data = array();
		
		$data['option_name'] = $this->input->post('option_name',true);
		$data['option_label'] = $this->input->post('option_label',true);
		$data['option_field_type'] = $this->input->post('option_field_type',true);
		$data['is_required'] = $this->input->post('is_required', true);
		
		
		$this->db->where('option_id', $options['option_id']);
		$this->db->update('options', $data);
		return;
	}
	
	//Function Delete Record	
	function deleteRecord($options){
		
		$this->db->where('option_id', $options['option_id']);
		$this->db->delete('option_rows');
		//delete attribute
		$this->db->where('option_id', $options['option_id']);
		$this->db->delete('options');
		return;
	}
	
	//Function Active Record	
	function activeRecord($options){
		$data = array();
		$data['is_visible'] = $options['is_visible'] ? '0' : '1';
		$this->db->where('option_id', $options['option_id']);
		$this->db->update('options', $data);
		return;
	}	
	
	//list all attributes
	function fetchAttributes($offset = FALSE, $limit = FALSE) {
		if($offset) $this->db->offset($offset);
		if($limit) $this->db->limit($limit);
		
		$this->db->select('*');
		$this->db->from('attribute');
		$this->db->join('product_attribute', 'product_attribute.attribute_id = attribute.attribute_id');
		//$this->db->join('attribute_value', 'attribute_value.attributes_id = attributes.attributes_id');
		
		$query = $this->db->get();
        return $query->result_array();
	}
	
	//list all attributes
	function listOptions() {
		
		$this->db->select('*');
		$this->db->from('attribute_option');
		//$this->db->join('attribute', 'attributes.attributes_id = attributes_value.attributes_id');
		//$this->db->where('attribute_value_id', $options['attribute_value_id']);
		$query = $this->db->get();
        return $query->result_array();
	}
	
	
}
?>
