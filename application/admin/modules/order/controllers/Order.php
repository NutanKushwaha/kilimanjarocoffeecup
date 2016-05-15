<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->data = [];
        $this->load->model('Ordermodel');
        $this->load->model('request/Requestmodel', 'Requestmodel');
    }

    function addOrder() {
        $param = [];
        $param['user_type'] = $this->user_type;
        $output = $this->Ordermodel->addOrder($param);
        $this->session->set_flashdata('message', $output['message']);
        redirect("dashboard");
        exit;
    }

    function history($offset = 0, $perpage = 5) {
        $user_type = $this->user_type;
        if (!$this->flexi_auth->is_privileged('View Order History')) {
            $this->session->set_flashdata('message', '<p class="error_msg"> You do not have privileges to view order history.</p>');
            redirect('dashboard');
        }
        $this->load->model('Commonusermodel');

        /**/
        $form_data = [];
        /* message to show if passed in session */
        $this->data['INFO'] = (!isset($inner['INFO'])) ? $this->session->flashdata('message') : $this->data['INFO'];
        /*
		if( $this->user_type == ADMIN ){
		} else if( $this->user_type == CMP_ADMIN ){
		} else if( $this->user_type == CMP_MD ){
		}*/
        $maxLimit = $this->Ordermodel->fetchMaxOrderAmout()['maxl'];
        $maxOrderQty = $this->Ordermodel->fetchMaxOrderQty()['maxq'];		
        /* orders price range */
        $priceLimit = range(0, round($maxLimit), ceil($maxLimit / 10));
        $this->data['orders_prange'] = array_combine(array_values($priceLimit), $priceLimit);

        /* orders qty range */
        $ordQtyLimit = range(0, round($maxOrderQty), ceil($maxOrderQty / 10));
        $this->data['orders_qrange'] = array_combine(array_values($ordQtyLimit), $ordQtyLimit);

        /* User Group */
        $this->data['user_type'] = $user_type;

        /* List Groups for view */
        $opts = [];
        $opts['user_type'] = $user_type;
        $opts['grp_order'] = $this->flexi_auth->get_user_custom_data( 'ugrp_order' );        
        $this->data['groups'] = $this->Commonusermodel->getGroups($opts);

        /* Distinct status manually filled */
        $orderStatus = $this->Ordermodel->fetchDistinctStatus();
        $this->data['orders_status'] = com_makelist($orderStatus, 'status', 'status', TRUE, 'Select');

        $fetchParam = [ 'user_type' => $user_type, 'offset' => $offset, 'perpage' => $perpage, 'form_data' => $form_data];
        $orderParam = [ 'user_type' => $user_type, 'form_data' => $form_data];

        /* Fetch Orders */
        $this->data['orders'] = $this->Ordermodel->fetchOrders($fetchParam);
        
        $this->data['group_users'] = [ '' => 'Select User'];
        if ($user_type == CMP_ADMIN) {
            $group_users = $this->Commonusermodel->getCompanyUsersList();
            $this->data['group_users'] = com_makelist($group_users, 'uacc_id', 'uacc_username', true, 'Select User');
        } else if ($user_type == USER) {
            $this->data['group_users'] = [ $this->flexi_auth->get_user_custom_data('uacc_id') => ucfirst($this->flexi_auth->get_user_custom_data('uacc_username'))];
        }
        //pagination configuration
        
        $config['total_rows'] = $this->Ordermodel->countAllOrders($orderParam);
        
        $config['html_container'] = 'order-view-div';
        $config['base_url'] = base_url() . 'order/ajax/order/history/';
        $config['per_page'] = $perpage;
        $config['js_rebind'] = '';
        $config['form_serialize'] = 'order-search-for';
        $this->data['pagination'] = com_ajax_pagination($config);
        $this->data['form_data'] = $form_data;
        $this->data['orders_history_html'] = $this->load->view('ajax/orders-history-table', $this->data, TRUE);
        $this->data['content'] = $this->load->view('history', $this->data, true);
        $this->load->view($this->template['without_menu'], $this->data);
    }

    function view($orderNumber = null) {
        $orderDetail = $this->Ordermodel->fetchOrderDet($orderNumber);
        if (!$orderDetail || !sizeof($orderDetail)) {
            $this->utility->show404();
            return;
        }
        $this->data['user_type'] = $this->user_type;
        /* $orderDetail contains order all details address will repeat till record present */
        $this->data['orderShip'] = $orderDetail[0];
        $this->data['orderDetail'] = $orderDetail;
        $this->data['content'] = $this->load->view('detail', $this->data, true);
        ;
        $this->load->view($this->template['default'], $this->data);
    }

    function issue($orderNumber = null) {
        $orderDetail = $this->Ordermodel->fetchOrderDet($orderNumber);
        if (!$orderDetail) {
            $this->utility->show404();
            return;
        }
        $store_list = [];
        $this->data['orderShip'] = $orderDetail[0];
        if( isset( $this->data['orderShip'][ 'company_code' ] ) &&  $this->data['orderShip'][ 'company_code' ] ){
			$this->load->model('company/Compstoremodel', 'Compstoremodel');
			$opt = [];
			$opt[ 'company_code' ] = $this->data['orderShip'][ 'company_code' ];
			$store_list = com_makelist( $this->Compstoremodel->get_list( $opt ), 'id', 'store_name', FALSE);
		}
        if ($this->data['orderShip']['is_stock_order'] > 1) {
            $order_stock_message = 'Stock order <b>' . $this->data['orderShip']['order_num'] . '</b> has been issued.';
            if ($this->data['orderShip']['is_stock_order'] == 3) {
                $order_stock_message = 'Stock order <b>' . $this->data['orderShip']['order_num'] . '</b> has been cancelled.';
            }
            $this->session->set_flashdata('message', $order_stock_message);
            redirect('order/history');
        }
        if ($this->input->post('issue-stock')) {
            $this->Ordermodel->issueOrder($orderDetail);
            $this->session->set_flashdata('message', 'Stock issue to company');
            redirect('order/history');
        }
        $this->data[ 'store_list' ] = $store_list;
        $this->data['user_type'] = $this->user_type;
        /* $orderDetail contains order all details address will repeat till record present */
        $this->data['orderDetail'] = $orderDetail;
        $this->data['content'] = $this->load->view('stock-issue', $this->data, true);
        ;
        $this->load->view($this->template['default'], $this->data);
    }

    function issued($orderNumber = null) {
        $orderDetail = $this->Ordermodel->fetchOrderDet($orderNumber);
        if (!$orderDetail) {
            $this->utility->show404();
            return;
        }
        $allowedStockDetail = $this->Ordermodel->fetchAssignedOrderDet($orderNumber);        
        $this->data['allowedStock'] = com_makeArrIndexToField($allowedStockDetail, 'product_code');
        $this->data['orderShip'] = $orderDetail[0];
        $store_list = [];
        if( isset( $this->data['orderShip'][ 'company_code' ] ) &&  $this->data['orderShip'][ 'company_code' ] ){
			$this->load->model('company/Compstoremodel', 'Compstoremodel');
			$opt = [];
			$opt[ 'company_code' ] = $this->data['orderShip'][ 'company_code' ];
			$store_list = com_makelist( $this->Compstoremodel->get_list( $opt ), 'id', 'store_name', FALSE);
		}		
		$this->data['store_list'] = $store_list;
        $this->data['user_type'] = $this->user_type;
        /* $orderDetail contains order all details address will repeat till record present */
        $this->data['orderDetail'] = $orderDetail;
        $this->data['content'] = $this->load->view('stock-assigned', $this->data, true);
        ;
        $this->load->view($this->template['default'], $this->data);
    }

    function reorder($orderNumber = null) {
        if ($this->user_type == USER) {
            $addToCart = TRUE;
            $cartReorderRef = $this->session->cartReorderRef;
            $this->load->model('cart/Cartmodel', 'Cartmodel');
            $orderDetail = $this->Ordermodel->fetchOrderDet($orderNumber);
            if (!$orderDetail) {
                $this->utility->show404();
            }
            $cartMsg['message'] = 'Order already added';
            if ($cartReorderRef && is_array($cartReorderRef)) {
                if (in_array($orderNumber, array_keys($cartReorderRef))) {
                    $addToCart = FALSE;
                }
            }
            if ($addToCart) {
                $param = [];
                $param['orderDetail'] = $orderDetail;
                $reOrderStatus = $this->Cartmodel->reorderFromSavedOrder($param);
                $cartMsg['message'] = $reOrderStatus['message'];
            }
            $addOrderStatus = $this->Ordermodel->addOrder();
            if ($addOrderStatus['status']) {
                $cartMsg['message'] = $addOrderStatus['message'];
            }
            $this->session->set_flashdata('message', $cartMsg['message']);
        }
        redirect('order/history');
    }

    /* Not in use */

    private function showCart() {
        $this->load->model('cart/Cartmodel', 'Cartmodel');
        $this->data['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $this->data['INFO'];
        $this->data['cartContents'] = $this->cart->contents();
        $this->data['cartVariables'] = $this->Cartmodel->getCartVariables();
        $this->data['content'] = $this->load->view('reorder', $this->data, true);
        $this->load->view($this->template['default'], $this->data);
    }

}
