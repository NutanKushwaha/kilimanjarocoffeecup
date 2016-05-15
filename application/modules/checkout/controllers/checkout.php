<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checkout extends CMS_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->library('session');
        $this->load->library('form_validation');

        /*
          $title = array();
          $title[''] = '--Select--';
          $title['mr'] = 'Mr';
          $title['mrs'] = 'Mrs';
          $title['mis'] = 'Mis';

          //Get customers Details
          if (!$this->aauth->isCustomer(curUsrId())) {
          redirect('/customer/logout', "location");
          exit();
          }
          $variables = $this->Cartmodel->variables();
          extract($variables);

          //Render View
          $inner = array();
          $inner['customer'] = $this->aauth->get_user();
          $inner['cart_total'] = $cart_total;
          $inner['title'] = $title;
          $inner['tax'] = $tax;
          $inner['order_total'] = $order_total;
         */
        $inner = array();
        $this->html->addMeta($this->load->view("meta/checkout-index.php", $inner, TRUE));
        $shell = array();
        $shell['contents'] = $this->load->view('checkout-index', $inner, true);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    //Payment process
    function process() {
        $this->load->model('booking/Cartmodel');
        $this->load->model('customer/Customermodel');
        $this->load->model('Checkoutmodel');
        $this->load->library('booking/cart');
        $this->load->library('parser');
        $this->load->library('email');
        //Get customers Details
        if (!$this->aauth->isCustomer(curUsrId())) {
            redirect('/customer/logout', "location");
            exit();
        }
        $customer = $this->aauth->get_user();
        $variables = $this->Cartmodel->variables();
        extract($variables);

        $order = $this->Checkoutmodel->addBooking($customer, $order_total);
        redirect("checkout/payments/{$order['booking']['unique_id']}");
        exit();
    }

    //Payment processing
    function payments($onum) {
        $this->load->model('Checkoutmodel');
        $this->load->library('form_validation');
        $this->load->library('booking/cart');
        //fetch order details
        $order = array();
        $order = $this->Checkoutmodel->detail($onum);
//        render view
        $inner = array();
        $shell = array();
        $inner['order'] = $order;
        $this->assets->addFooterJS('js/payment.js', 200);
        $inner['order']['paypal'] = 'shibi.arora@gmail.com';
        $shell['contents'] = $this->load->view('checkout/order-processing', $inner, true);
        $this->load->view("themes/" . THEME . "/templates/booking", $shell);
    }

    //function success
    function success($onum) {
        $this->load->model('Checkoutmodel');
        $this->load->library('form_validation');
        $this->load->helper('form');

        //fetch the order details
        $order = $this->Checkoutmodel->fetchDetails($onum);
        //render vierw
        $inner = array();
        $inner['order'] = $order;
        $shell = array();
        $shell['contents'] = $this->load->view('payment/payment-success', $inner, true);

        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    //function failed
    function failed($onum) {
        $inner = array();
        $shell = array();
        $this->load->model('Checkoutmodel');
        $orders = $this->Checkoutmodel->updateBooking($onum, array('booking_status' => Checkoutmodel::$STATUS[0]));
        $shell['contents'] = $this->load->view("payment/payment-cancel", $inner, TRUE);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

}

?>