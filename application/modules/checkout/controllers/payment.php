<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends CMS_Controller {

    private $debug = TRUE;
    private $log_level = 'error';
    private $sandbox = TRUE;

    function __construct() {
        parent::__construct();
    }

    function index() {

        if (!$this->verify_ipn()) {
            return;
        }

        if ($_POST['receiver_email'] != MCC_MERCHANT_EMAIL) {
            if ($this->debug)
                log_message($this->log_level, "Receiver Email ({$_POST['receiver_email']}) didn't match client's PayPal ID " . MCC_MERCHANT_EMAIL);
            return;
        }

        log_message($this->log_level, "Order Detail: executing proccess");

        $this->process();
    }

    function verify_ipn() {


        if (count($_POST) == 0) {
            if ($this->debug)
                log_message($this->log_level, "Direct Access to IPN script");
            return false;
        }

        //Build up verification request
        $req = 'cmd=_notify-validate';
        $original = '';
        foreach ($_POST as $ipnkey => $ipnval) {
            $original .= "$ipnkey=$ipnval&";
            $ipnval = str_replace("\n", "\r\n", $ipnval);
            $ipnval = stripslashes($ipnval);
            $req .= '&' . $ipnkey . '=' . ($ipnval);
        }

        //Initialize Curl and Send verification request
        $ch = curl_init();
        if (MCC_DEMO_MODE == '1') {

            curl_setopt($ch, CURLOPT_URL, "https://www.sandbox.paypal.com/cgi-bin/webscr");
            //curl_setopt($ch, CURLOPT_URL, "http://www.belahost.com/pp/");
        } else {
            curl_setopt($ch, CURLOPT_URL, "https://www.paypal.com/cgi-bin/webscr");
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        $ret = curl_exec($ch);
        if ($this->debug)
            log_message($this->log_level, "IPN Response: $ret");

        //Invalid IPN
        if (strcmp($ret, "VERIFIED") != 0) {
            if ($this->debug) {
                log_message($this->log_level, "Failed to verify IPN. See next two lines for details.");
                log_message($this->log_level, "IPN Received: $original");
                log_message($this->log_level, "IPN Sent: $req");
            }
            curl_close($ch);
            return false;
        }

        //fclose($clog);
        curl_close($ch);

        return true;
    }

    private function process() {
        $this->load->model('customer/Ordermodel');
        $this->load->model('Checkoutmodel');
        $this->load->library('parser');
        $this->load->library('email');

        log_message($this->log_level, "Order Detail: start of proccess");

        if ($_POST['payment_status'] != 'Completed')
            return;

        log_message($this->log_level, "Order Detail: After complete");
        log_message($this->log_level, "Order id: " . $_POST['custom']);


        //Fetch order details
        $order = array();
        $order = $this->Ordermodel->fetchById($_POST['custom']);
        log_message($this->log_level, "Order id: " . serialize($order));

        //insert trasection id

        log_message($this->log_level, "transcation id: " . $_POST['txn_id']);
        $data = array();
        $data['txn_id'] = $_POST['txn_id'];
        $this->db->where('booking_id', $order['booking_id']);
        $this->db->update('eventbooking_bookings', $data);


        if (!$order) {
            if ($this->debug)
                log_message($this->log_level, 'Invalid order_id:' . $_POST['custom']);
            return;
        }


        //Verify amount
        if (!$this->input->post('amount') || $this->input->post('amount') != $order['amount']) {
            if ($this->debug)
                log_message($this->log_level, 'Amount posted by MB does not match Ordered amount: ' . $this->input->post('amount') . "==" . $order['amount']);
            //return;
        }

        if (!$_POST['mc_gross'] || $_POST['mc_gross'] != $order['amount']) {
            if ($this->debug)
                log_message($this->log_level, 'Amount posted by MB does not match Ordered amount: ' . $this->input->post('amount') . "==" . $order['amount']);
            //return;
        }

        $order_details = serialize($order);

        log_message($this->log_level, "Order Detail: $order_details");

        $this->Checkoutmodel->orderConfirmed($order);
    }

//    //function cancel
//    function cancel() {
//        //render view
//        $inner = array();
//        $shell = array();
//
//        $shell['contents'] = $this->load->view("payment/payment-cancel", $inner, TRUE);
//        //$this->load->view('themes/'.MCC_THEME.'/shell', $shell);
//        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
//    }
//
//    //function success
//    function success() {
//        //Render view
//        $inner = array();
//        $shell = array();
//
//        $this->html->addMeta($this->load->view("meta/payment_success.php", $inner, TRUE));
//        $shell['contents'] = $this->load->view("payment/payment-success", $inner, TRUE);
//        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
//    }

}

?>