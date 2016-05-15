<?php

class Slidemodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function listAll() {
        $this->db->where('image_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $rs = $this->db->get('slideshow_image');
        $query = $rs->result_array();
        return $query;
    }
    
     function ListHomeSlides(){
        $this->db->select('slideshow_image,alt,link,new_window,img_title,img_desc')
                ->from('slideshow_image')
                ->where('slideshow_id','1')
                ->where('image_active','1');
        $this->db->order_by('sort_order','ASC');
        $query = $this->db->get();
        if($query->num_rows()>0){
           return $query->result_array();
           
        }
        return FALSE;
        
     }
}

?>