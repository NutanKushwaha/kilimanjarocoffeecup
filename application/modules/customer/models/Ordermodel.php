<?php

class Ordermodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

//count all customer
    function countAll($customer) {

        $this->db->where('customer_id', $customer['uacc_id']);
        $query = $this->db->get('order');

        return $query->num_rows();
    }

    // order details
    function fetchDetails($customer, $onum) {
        $this->db->where('order.order_num', $onum);
        $this->db->where('order.customer_id', $customer['uacc_id']);
        $query = $this->db->get('order');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    //List all customer
    function listAll($customer, $offset, $limit) {

        if ($offset) {
            $this->db->offset($offset);
        }
        if ($limit) {
            $this->db->limit($limit);
        }


        $this->db->where('customer_id', $customer['uacc_id']);
        $query = $this->db->get('order');

        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    //fetch details
    function listOrderItems($onum) {
        $this->db->where('order_item.order_id', $onum);
        // $this->db->join('order_item','order_item.order_id = order.id');
        $query = $this->db->get('order_item');
        return $query->result_array();
    }

    function getShippingBillingDetails($onum) {
        $this->db->where('order_ship_detail.order_id', $onum);
        $query = $this->db->get('order_ship_detail');
        return $query->row_array();
    }

    function getGuestOrderDetail($onum) {
        $this->db->where('order.id', $onum);
        $query = $this->db->get('order');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    function getOrderDetail($onum) {
        $this->db->where('order.order_num', $onum);
        $query = $this->db->get('order');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

}

?>