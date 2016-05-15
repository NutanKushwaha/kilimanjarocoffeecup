<?php



class Paypal extends Front_Controller {



    function __construct() {

        parent::__construct();

        $this->load->library('paypal_class');

        // ini_set('error_log',)

        $this->load->model('Cartmodel');

        ini_set('error_log', dirname(dirname(dirname(dirname((dirname(dirname(__FILE__))))))) . '/ipn_errors.log');

    }



    function index($insert_order_id) {
        $this->load->model('customer/Ordermodel');
        $this->load->model('customer/Customermodel');
        $orderDetail = $this->Ordermodel->getGuestOrderDetail($insert_order_id);
        $orderItems = $this->Ordermodel->listOrderItems($orderDetail['id']);


        $customer_details = $this->Customermodel->getGuestUserInfo($orderDetail['customer_id']);

        $whole_total=$this->cart->total();

        if ($this->session->userdata('shipping_charges') != null) {

            $whole_total =  $whole_total + $this->session->userdata('shipping_charges');
        }

        $p = new paypal_class;             // initiate an instance of the class

        if (MCC_PAYPAL_DEMO_MODE) {

            $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url

        } else {

            $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

        }





        $this_script = createUrl('paypal/');

// if there is not action variable, set the default action of 'process'

        if (empty($_GET['action']))

            $_GET['action'] = 'process';



        switch ($_GET['action']) {

            case 'process':      // Process and order...
                $p->add_field('first_name', $customer_details['upro_first_name']);
                $p->add_field('last_name', $customer_details['upro_last_name']);
                $p->add_field('business', MCC_PAYPAL_MERCHENT_EMAIL);

                //      $p->add_field('business', 'devrohit46@gmail.com');

                $p->add_field('return', $this_script . 'success');

                $p->add_field('cancel_return', $this_script . 'cancel');

                $p->add_field('notify_url', $this_script . 'ipn');

                    
                $p->add_field('item_name', 'Total Amount with Shipping');
                
                $p->add_field('amount', $whole_total);
                
                $p->add_field('custom', $insert_order_id);

                $p->add_field('currency_code', 'USD');
                $p->submit_paypal_post(); // submit the fields to paypal

                die();

                //$p->dump_fields();      // for debugging, output a table of all the fields

                break;

        }

    }



    function ipn() {

        $p = new paypal_class;



        if ($p->validate_ipn()) {

            $re = "";

            foreach ($_REQUEST as $r => $s) {

                $re .= "\n$r: $s";

            }

            $this->Cartmodel->emptyCart();

            $this->db->where('id', $_REQUEST['custom']);

            $this->db->update('order', array('is_paid' => '1', 'transaction_no' => $_REQUEST['verify_sign']));

            $this->session->unset_userdata('checkoutRole');

            $this->session->unset_userdata('user_register');

            $this->session->unset_userdata('guest_user_id');

            $this->session->unset_userdata('CheckoutAddress');

            $this->session->unset_userdata('guestUserInfo');

            $this->session->unset_userdata('last_order');

        }

    }



    function success() {

        $this->load->model('customer/Ordermodel');

        $this->db->where('id', $_REQUEST['custom']);

        $this->db->update('order', array('is_paid' => '1', 'transaction_no' => $_REQUEST['verify_sign'], 'status' => $_REQUEST['payment_status']));

        $orderDetail = $this->Ordermodel->getGuestOrderDetail($this->input->post('custom'));

        $this->session->unset_userdata('checkoutRole');

        $this->session->unset_userdata('user_register');

        $this->session->unset_userdata('guest_user_id');

        $this->session->unset_userdata('CheckoutAddress');

        $this->session->unset_userdata('guestUserInfo');

        $this->session->unset_userdata('last_order');

        $this->session->unset_userdata('shipping_charges');
//        echo base_url('paypal/order_placed/'.$orderDetail['order_num']);

        //   file_put_contents( 'ipn_errors.log', $body);

        $this->Cartmodel->emptyCart();

        redirect(base_url('paypal/order_placed/' . $orderDetail['order_num']));

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

        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);

    }



    function cancel() {

        $inner = array();

        $shell = array();

        $this->session->unset_userdata('CheckoutAddress');
        $shell['contents'] = $this->load->view("order-cancel", $inner, true);

        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);

    }

}

