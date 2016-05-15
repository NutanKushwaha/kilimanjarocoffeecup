<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Specialpricemodel extends Commonmodel {

    function __construct() {
        parent::__construct();
    }

    function countAll() {
        $sku = $this->input->get('product');
        ($sku) ? $this->db->where('(product.product_name like "%' . $sku . '%" or product.product_sku like "%' . $sku . '%")') : "";
        $status = $this->input->get('status');
        ($status != null) ? $this->db->where('active', $status) : 1;
        $this->db->join('product', 'product.product_sku = front_special_price.product_sku');
        $query = $this->db->get('front_special_price');
        return $query->num_rows();
    }

    function listAll($offset = 0, $limit = 1) {

        (isset($offset)) ? $this->db->offset($offset) : NULL;
        (isset($limit)) ? $this->db->limit($limit) : NULL;
        /* --------search variables---------- */
        $sku = $this->input->get('product');
        ($sku) ? $this->db->where('(product.product_name like "%' . $sku . '%" or product.product_sku like "%' . $sku . '%")') : "";
        $status = $this->input->get('status');
        ($status != null) ? $this->db->where('active', $status) :$this->db->where('active', 1) ;
        /* --------End search variables---------- */
        $this->db->select('product.product_name,front_special_price.*');
        $this->db->join('product', 'product.product_sku = front_special_price.product_sku');
        $query = $this->db->get('front_special_price');
        
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    //get Special Price Detail
    function getDetails($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('front_special_price');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    function getActiveProductSpecialPrice($sku) {
        $this->db->where('product_sku', $sku);
        $this->db->where("CURDATE() BETWEEN from_date AND to_date");
        $query = $this->db->get('front_special_price');
        return $query->num_rows();
    }

    //configurable products are not considered.
    //Only active products 
    function getProducts() {
        $this->db->where('product_type_id', 1);
        $this->db->where('ref_product_id', 0);
        $this->db->where('product_is_active', 1);
        $query = $this->db->get('product');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    function InsertRecord() {
        $input = $this->input->post();
        $data = array();
        $data['product_sku'] = $input['product'];
        $data['price'] = $input['price'];
        $data['from_date'] = $input['from_date'];
        $data['to_date'] = $input['to_date'];
        $data['active'] = 1;
        $this->db->insert('front_special_price', $data);
    }

    function UpdateRecord($id) {
        $input = $this->input->post();
        $data = array();
        $data['product_sku'] = $input['product'];
        $data['price'] = $input['price'];
        $data['from_date'] = $input['from_date'];
        $data['to_date'] = $input['to_date'];
        //$data['active'] = 1;
        $this->db->where('id', $id);
        $this->db->update('front_special_price', $data);
    }

    function deleteRecord($id) {
        $data = array();
        $data['active'] = 0;
        $this->db->where('id', $id);
        $this->db->update('front_special_price', $data);
    }

    function SearchByProduct() {
        $this->db->like('product_sku', $this->input->get('product'));
        $this->db->or_where('active', $this->input->get('status'));
        $query = $this->db->get('front_special_price');
    }

}
