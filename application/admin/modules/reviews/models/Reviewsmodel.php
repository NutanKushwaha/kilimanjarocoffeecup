<?php

class Reviewsmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function countAll()
    {
        return $this->db->get('review')->num_rows();
    }

 

    //list all order
    function listAll($offset = FALSE, $limit = FALSE) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $this->db->select('*');
        $this->db->from('review');
        $this->db->join('product','review.product_id = product.product_id');

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }


    function enable($reviewId) {
        $this->db->where('id',$reviewId);
        $data['status'] = 1;
        $this->db->update('review',$data);

      
    }
    function disable($reviewId) {
        $this->db->where('id',$reviewId);
        $data['status'] = 0;
        $this->db->update('review',$data);
  
    }
    function delete($reviewId) {
        $this->db->where('id',$reviewId);
//        echo $reviewId;
//        die();
        $data['status'] = 0;
        $this->db->delete('review');
        
    }






}

?>