<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test extends CMS_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('Common_auth_model');
        $this->load->model('Commonusermodel');
        $this->data = null;
    }

    function index()
    {
    	$this->load->model('Order/Ordermodel');
    	$this->load->library('semail');
    	$orderDetails = $this->Ordermodel->getOrderDetails(62);
    	$orderItems = $this->Ordermodel->getOrderItems(62);
    	
    	$details = array();
    	$details['order_details'] = $orderDetails;
    	$details['order_items'] = $orderItems;
    	$emailBody = $this->load->view('includes/order-email',$details,true);

    	 $email_config = [
			//'to' => MCC_EMAIL_ADMIN,
            'to' => 'devrohit46@gmail.com',
			'subject' => 'Order email',
			'from' => MCC_EMAIL_FROM,
			'body' => $emailBody
		];
                
        $status =  $this->semail->send_mail( $email_config );	
    }
}