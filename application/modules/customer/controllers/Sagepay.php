<?php

class Sagepay extends Front_Controller {

    function __construct() {
        parent::__construct();
        // ini_set('error_log',)
        $this->load->model('Cartmodel');
        ini_set('error_log', dirname(dirname(dirname(dirname((dirname(dirname(__FILE__))))))) . '/ipn_errors.log');
    }

    function order_placed($onum) {
        $this->load->model('customer/Ordermodel');
        $orderDetail = $this->Ordermodel->getOrderDetail($onum);

        $orderItems = $this->Ordermodel->listOrderItems($orderDetail['id']);
        $inner = array();
        $shell = array();
        $inner['order'] = $orderDetail;
        $inner['order_items'] = $orderItems;
        $shell['contents'] = $this->load->view("order-success", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }
}