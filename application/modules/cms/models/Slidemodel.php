<?php

class Slidemodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function slideshowDetails($id) {
        $this->db->where('slideshow_id', $id);
        $this->db->where('active', 1);
        $rs = $this->db->get('slideshow');
        if($rs && $rs->num_rows() == 1) {
			$slideshow = $rs->row_array();
			$slides = $this->listAllSlides($slideshow['slideshow_id']);
			$slideshow['slides'] = $slides;
			return $slideshow;
		}

        return false;
    }

    //list al slides
    function listAllSlides($slide_id) {

        $this->db->where('slideshow_id', intval($slide_id));
        $this->db->where('image_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $rs = $this->db->get('slideshow_image');
        return $rs->result_array();
    }

}

?>
