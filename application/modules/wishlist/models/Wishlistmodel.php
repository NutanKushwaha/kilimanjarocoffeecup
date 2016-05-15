<?php

class Wishlistmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function countAll($cid) {
        $this->db->where('category_id', $cid);
        $this->db->where('product_is_active', 1);
        $query = $this->db->get('product');
        return $query->num_rows();
    }
    function listAll($userId)
    {
        $this->db->where('wishlist.user_id',$userId);
        $this->db->join('product','product.product_id = wishlist.product_id');
        $query = $this->db->get('wishlist');
        return array('num_rows'=>$query->num_rows(),'result'=>$query->result_array());
    }
    function insertRecord($user_id) {
        $product_id = $this->input->post('product_id');
        if (self::IfNotExistingProduct($user_id, $product_id)) {
            $data = array();
            $data['product_id'] = $this->input->post('product_id');
            $data['user_id'] = $user_id;
            $data['is_bought'] = 0;
            $this->db->insert('wishlist', $data);
            return true;
        } else {
            return false;
        }
    }

    function IfNotExistingProduct($userId, $productId) {
        $this->db->where('user_id', $userId);
        $this->db->where('product_id', $productId);
        $query = $this->db->get('wishlist');
        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
    function deleteRecord($userID,$pid)
    {
        $this->db->where('user_id',$userID);
        $this->db->where('product_id',$pid);
        $this->db->delete('wishlist');
    }

}
