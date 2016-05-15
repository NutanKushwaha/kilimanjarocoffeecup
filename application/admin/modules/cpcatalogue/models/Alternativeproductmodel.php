<?php

class Alternativeproductmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDetails($aid) {
        $this->db->where('alternative_product_id', intval($aid));
        $rs = $this->db->get('alternative_product');
        if ($rs->num_rows() > 0) {
            return $rs->row_array();
        }
        return FALSE;
    }

    function getProductList($pid) {
        $this->db->select('product.product_name as alternative_product_name,alternative_product_id');
        $this->db->from('alternative_product');
        $this->db->order_by('sort_order', 'ASC');
        $this->db->where('alternative_product.product_id', intval($pid));
        $this->db->join('product', 'product.product_sku = alternative_product.alt_product_id');
        $rs = $this->db->get();
        return $rs->result_array();
    }

    function listAll($pid) {
        $str = $this->input->get('q');
        $cat = $this->input->get('cat');
        if ($cat != null || $cat != "") {
            $this->db->where('category.category_alias', $cat);
            $this->db->join('category', 'category.category_id = product.category_id');
        }
        if ($str != "") {
            //$this->db->select('product.product_name,product.product_id,product.product_alias,product.product_sku,product.product_price,product.product_image,(select avg(rating) from bg_review where product_id = bg_product.product_id) as avgRating');
            $this->db->where('(product.product_name like "%' . $str . '%" or product.product_sku like "%' . $str . '%")');
        }
        $this->db->where('product_sku !=', $pid);
        $rs = $this->db->get('product');
        return $rs->result_array();
    }

    function getOrder() {
        $this->db->select_max('sort_order');
        $query = $this->db->get('alternative_product');
        $sort_order = $query->row_array();
        return $sort_order['sort_order'] + 1;
    }

    function insertRecord($pid) {

        foreach ($this->input->post('alternative_product_name', TRUE) as $alterateProduct) {
            if (self::getProductExistence($pid,$alterateProduct)) {
                $data = array();
                $data['product_id'] = $pid;
                $data['sort_order'] = $this->getOrder();
                $data['alt_product_id'] = $alterateProduct;
                $this->db->insert('alternative_product', $data);
            }
        }
    }

    function getProductExistence($proId, $alterateProduct) {
        $this->db->where('product_id', $proId);
        $this->db->where('alt_product_id', $alterateProduct);
        $query = $this->db->get('alternative_product');
        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function deleteRecord($alternative_product) {
        $this->db->where('alternative_product_id', $alternative_product['alternative_product_id']);
        $this->db->delete('alternative_product');
    }

}

?>