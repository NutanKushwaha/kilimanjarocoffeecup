<?php

class Reviewsmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function fetchProductNameById($p_id) {
        $this->db->select('product.product_name,product.reviews,product.rating,product.review_users');
        $this->db->from('product');
        $this->db->where('product_id', $p_id);
        $this->db->where('p_active', 1);
        $rs = $this->db->get();

        if ($rs && $rs->num_rows == 1) {
            $name = $rs->row_array();
            return $name;
        }
        return false;
    }

    
    function insertRecord(){
        
        $data['rating'] = $this->input->post('control9591456', true);
        $data['review'] = $this->input->post('control9591462', true);
        $data['customer_id'] = $this->input->post('customer_id', true);
        $product_id = $this->input->post('product_id', true);
        $data['product_id'] = $product_id ; 
        
        $status = $this->db->insert('product_reviews',$data);
         if ($status) {
            return true;
        }
        //$pro_details = $this->fetchProductNameById($product_id);
    }
    
    
    
    function listReviews($p_id){
        $this->db->select('*');
        $this->db->from('product_reviews');
        $this->db->where('product_id', $p_id);
        $this->db->where('admin_approve', 1);
        $rs = $this->db->get();

        if ($rs) {
            //$name = $rs->row_array();
            return $rs->result_array();
        }
        return false;
    }

    function updateRecord() {

        $review = array();
        $data = array();
        //Add Entry to database
        $rating = $this->input->post('control9591456', true);
        $rev = $this->input->post('control9591462', true);
        $name = $this->input->post('name', true);
        $review[$name] = $rev;
       // $data['reviews'] = json_encode($review);
        $product_id = $this->input->post('product_id', true);
        $pro_details = $this->fetchProductNameById($product_id);
        $data['rating']= $pro_details['rating'] +  $rating ; 
        $earlier_rev = json_decode($pro_details['reviews'],true);
        $earlier_rev[] = $review ; 
        
        $data['review_users'] = ($pro_details['review_users'] + 1) ;
        $all_rev = json_encode($earlier_rev);
        $data['reviews'] = $all_rev ;
       
        //print_r($data); exit;
        $this->db->where('product_id', intval($product_id));
        $status = $this->db->update('product', $data);
        if ($status) {
            return true;
        }
    }

}

?>