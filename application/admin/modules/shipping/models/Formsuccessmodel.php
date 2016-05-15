<?php

class Formsuccessmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //Count All Records
    function countSuccessForm() {
        $this->db->from('form_success_page');
        return $this->db->count_all_results();
    }

    //List All Records
    function listSuccessForm($offset = false, $limit = false) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $rs = $this->db->get('form_success_page');
        return $rs->result_array();
    }

    //Function Get Details
    function getDetails($form_alias) {
        $this->db->where('form_alias', $form_alias);
        $rs = $this->db->get('form_success_page');
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //Function Update Record
    function updateRecord($form_details) {
        $data = array();
        $data['before_body_close'] = $this->input->post('before_body_close', FALSE);
        
        $this->db->where('form_alias', $form_details['form_alias']);
        $this->db->update('form_success_page', $data);
    }
}
?>