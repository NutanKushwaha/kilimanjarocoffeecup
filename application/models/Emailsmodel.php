<?php

class Emailsmodel extends CI_Model {

    function fetchDetails($email_alias) {
        $this->db->where('email_alias', $email_alias);
        $query = $this->db->get('email_content');
        if ($query && $query->num_rows() == 1) {
            return $query->row_array();
        }
        return false;
    }

}

?>