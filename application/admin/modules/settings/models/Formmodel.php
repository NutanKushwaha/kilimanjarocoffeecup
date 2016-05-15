<?php

class Formmodel extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	//Count All Records
	function countAll() {
		$this->db->from('form_setting');
		return $this->db->count_all_results();
	}

	//List All Records
	function listAll($offset = false ,$limit = false){
		if($offset)$this->db->offset($offset);
		if($limit)$this->db->limit($limit);

		$rs = $this->db->get('form_setting');
        return $rs->result_array();
	}

	//Function Get Details
	function getDetails($form_alias){
        $this->db->where('form_alias', $form_alias);
		$rs = $this->db->get('form_setting');
		if($rs->num_rows() == 1) {
	        return $rs->row_array();
		}
		 return FALSE;

	}

	//Function Update Record
	function updateRecord($form_details){
		$data = array();
		$data['email_address'] = $this->input->post('email_address', FALSE);
        //$data['captcha_active'] = $this->input->post('captcha_active', TRUE);
        $data['captcha_active'] = 0;

		$this->db->where('form_alias', $form_details['form_alias']);
		$this->db->update('form_setting', $data);
	}
}
?>