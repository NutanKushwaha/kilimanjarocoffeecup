<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EmailModel extends Commonmodel {

    function __construct() {
        parent::__construct();
    }

    function getEmailByTag($tag) {
        $this->db->where('tag', $tag);
        $query = $this->db->get('email_content');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

}
