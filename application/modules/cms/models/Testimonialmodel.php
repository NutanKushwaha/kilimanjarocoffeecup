<?php

class Testimonialmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function listLatestTen($limit=10)
    {
        $this->db->select("DATE_FORMAT(FROM_UNIXTIME(`testimonial_added_on`), '%e %b %Y') AS 'date_formatted',testimonial,name,image");
        $this->db->order_by('date_formatted','DESC');
        $this->db->limit($limit);
        $res = $this->db->get('testimonial');
        
        
        return array('num_rows'=>$res->num_rows(),'result'=>$res->result_array());
    }
}
