<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ContactusModel extends Commonmodel {

    function __construct() {
        parent::__construct();
    }

    function countAll() {
        $query = $this->db->get('contact');
        return $query->num_rows();
    }

    function listAll($offset,$limit) {
        if($offset){
            $this->db->offset($offset);
        }
        if($limit){
            $this->db->limit($limit);
        }
        $query = $this->db->get('contact');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    function deleteRecord($id) {
        $this->db->where('id', $id);
        $this->db->delete('contact');
    }

}
