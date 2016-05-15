<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Alternative_product extends Adminajax_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function updateSortOrder() {
        $sort_data = $this->input->post('alternative_product', TRUE);
        foreach ($sort_data as $key => $val) {
            $update = array();
            $update['sort_order'] = $key + 1;
            
            $this->db->where('alternative_product_id', $val);
            $this->db->update('alternative_product', $update);
            //echo $this->db->last_query(); continue;
        }
        //echo "Done";
        print_r($_POST);
    }

}

?>