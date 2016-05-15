<?php

class Emailmessagesmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //fetch detail
    function detail($eid) {

        $this->db->from('email_content');
        $this->db->where('email_content_id', intval($eid));
        $rs = $this->db->get();
        if ($rs->num_rows() == 1)
            return $rs->row_array();
        return false;
    }

    //List All Records
    function listAll($market_email = false) {

        $this->db->select('*');
        $this->db->from('email_content');
        $rs = $this->db->get();
        return $rs->result_array();
    }

    //get sort order
    function getOrder() {
        $this->db->select_max('sort_order');

        $query = $this->db->get('email_content');
        $sort_order = $query->row_array();
        return $sort_order['sort_order'] + 1;
    }

    //function update Record
    function insertMarketingTemplateRecord() {
        $data = array();
        $data['email_name'] = $this->input->post('email_name', FALSE);
        if ($this->input->post('email_name', TRUE) != '') {
            $data['email_alias'] = $this->_slug($this->input->post('email_name', TRUE));
        }

        $data['email_subject'] = $this->input->post('email_subject', TRUE);
        $data['email_content'] = $this->input->post('email_content', FALSE);
        $data['marketing_email'] = 1;


        $this->db->insert('email_content', $data);
        return;
    }

    //function update Record
    function updateRecord($messages) {
        $data = array();
        $data['email_name'] = $this->input->post('email_name', FALSE);
        $data['email_subject'] = $this->input->post('email_subject', TRUE);
        $data['email_content'] = $this->input->post('email_content', FALSE);


        $this->db->where('email_content_id', $messages['email_content_id']);
        $this->db->update('email_content', $data);
        return;
    }

    //delete record
    function deleteRecord($messages) {
        $this->db->where('email_content_id', $messages['email_content_id']);
        $this->db->delete('email_content');
    }

    function _slug($name) {
        $new = ($name) ? $name : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $new;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, '-', true);
        $this->db->limit(1);
        $this->db->where('email_alias', $slug);
        $rs = $this->db->get('email_content');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('email_alias', $alt_slug);
                $rs = $this->db->get('email_content');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }

}

?>