<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order extends CMS_Controller {

    function __construct() {

        parent::__construct();
    }

    function index($offset = false) {



        $this->load->model('Customermodel');

        $this->load->model('Ordermodel');

        $this->load->library('pagination');

        $this->load->helper('text');



        $userSession = $this->flexi_auth->get_user_custom_data();

//        e($userSession);
        //setup Pagenation

        $perpage = 10;

        $config = array();

        $config['base_url'] = base_url() . "customer/order/index/";

        $config['uri_segment'] = 3;

        $config['total_rows'] = $this->Ordermodel->countAll($userSession);

        $config['per_page'] = $perpage;

        $this->pagination->initialize($config);

        $orders = array();

        $orders = $this->Ordermodel->listAll($userSession, $offset, $perpage);



        //Render View

        $inner = array();

        $shell = array();

        $inner['customer'] = $userSession;

        $inner['orders'] = $orders;

        $inner['pagination'] = $this->pagination->create_links();

        $shell['contents'] = $this->load->view("order-index", $inner, true);

        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
    }

    //Order Details

    function details($onum) {



        $this->load->model('Ordermodel');

        $this->load->model('Customermodel');

        $this->load->helper('text');



        $userSession = $this->flexi_auth->get_user_custom_data();



        //get order details

        $order = array();

        $order = $this->Ordermodel->fetchDetails($userSession, $onum);



        if (!$order) {

            $this->utility->show404();

            return;
        }





        //get order items

        $order_items = array();

        $order_items = $this->Ordermodel->listOrderItems($order['id']);



        //check customer see own order
        //render view

        $inner = array();

        $inner['order'] = $order;

        $inner['order_items'] = $order_items;

        $inner['customer'] = $userSession;



        $shell['contents'] = $this->load->view("order-detail", $inner, true);

        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
    }

    function reorder($orderNumber = null) {

        $this->load->model('catalogue/Ordermodel');



        $addToCart = TRUE;

        $cartReorderRef = $this->session->cartReorderRef;

        $this->session->unset_userdata('cartReorderRef');

        $orderDetail = $this->Ordermodel->fetchOrderDet($orderNumber);

        if (!$orderDetail) {

            $this->utility->show404();
        }

        $cartMsg['message'] = 'Order already added';

//     e( $cartReorderRef);                

        if ($cartReorderRef && is_array($cartReorderRef)) {



            if (in_array($orderNumber, array_keys($cartReorderRef))) {

                $addToCart = FALSE;
            }
        }



        if ($addToCart) {



            $param = [];

            $param['orderDetail'] = $orderDetail;

            $reOrderStatus = $this->Ordermodel->reorderFromSavedOrder($param);

            $cartMsg['message'] = $reOrderStatus['message'];
        }



        //$addOrderStatus = $this->Ordermodel->addOrder();                    

        if ($addOrderStatus['status']) {

            $cartMsg['message'] = $addOrderStatus['message'];
        }

        $this->session->set_flashdata('message', $cartMsg['message']);

        redirect(createUrl('catalogue/cart/process'));
    }

    function getPdf($onum) {

        $this->load->model('Ordermodel');

        $this->load->model('Customermodel');

        $this->load->helper('text');



        $userSession = $this->flexi_auth->get_user_custom_data();



        //get order details

        $order = array();

        $order = $this->Ordermodel->fetchDetails($userSession, $onum);



        if (!$order) {

            $this->utility->show404();

            return;
        }

        $orderShipBill = $this->Ordermodel->getShippingBillingDetails($order['id']);



        //get order items

        $order_items = array();

        $order_items = $this->Ordermodel->listOrderItems($order['id']);



        //render view

        $inner = array();

        $inner['order'] = $order;

        $inner['order_items'] = $order_items;

        $inner['shipBill'] = $orderShipBill;

        $inner['customer'] = $userSession;





        $content = $this->load->view("InvoicePdf/email", $inner, true);

        $this->load->library('m_pdf');

        $this->m_pdf->pdf->WriteHTML($content);

        $this->m_pdf->pdf->output($order['order_num'] . '.pdf', "D");
    }

}

?>