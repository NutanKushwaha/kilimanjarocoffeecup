<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order extends Adminajax_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Ordermodel');
        $this->load->model('request/Requestmodel', 'Requestmodel');
        $this->data = [];
    }

    function addOrder( ){
        $opt = [];
        $opt[ 'user_type' ] = $this->user_type;
    	$output = $this->Ordermodel->addOrder( $opt );
    	echo json_encode( $output );
    	exit();
    }

    function history( $perpage = 5 ) {
        $form_data = [];
        parse_str($this->input->post("form_data"), $form_data);
    	$offset = $this->input->get_post('offset'); 
    	$user_id = com_gParam( 'user_id', TRUE , 0);
		$user_type = $this->user_type;
		$orderParam = [ 'user_type' => $user_type , 
                        'user_id' => $user_id, 
                        'form_data' => $form_data ];
		$fetchParam = [ 'offset' => $offset, 
                        'user_id' => $user_id, 
                        'perpage' => $perpage, 
                        'user_type' => $user_type , 
                        'form_data' => $form_data ];
        $this->load->model( 'Commonusermodel' );
        $grp_opt = [ 
					'user_type' => $user_type,  
					'grp_order' => $this->flexi_auth->get_user_custom_data( 'ugrp_order' )
				];
        $this->data['groups'] =  $this->Commonusermodel->getGroups( $grp_opt );
        $maxLimit = $this->Ordermodel->fetchMaxOrderAmout()[ 'maxl' ];
        $maxOrderQty = $this->Ordermodel->fetchMaxOrderQty()[ 'maxq' ];

        /* orders price range */
        $priceLimit = range(0, round( $maxLimit ), ceil( $maxLimit/10 ));
        $this->data['orders_prange'] = array_combine(array_values($priceLimit), $priceLimit);

        /* orders qty range */
        $ordQtyLimit = range(0, round( $maxOrderQty ), ceil( $maxOrderQty/10 ));
        $this->data['orders_qrange'] = array_combine(array_values($ordQtyLimit), $ordQtyLimit);
        $grp_users_select_opt = false;
        if ( $user_type == ADMIN ){
            $grp_users_select_opt = true;
            $groupId = com_arrIndex($form_data, 'search-order-grp', '0');
            $opt = [];
            $opt[ 'groupId' ] = $groupId;
            $opt[ 'user_type' ] = $user_type;
            $group_users  =  $this->Commonusermodel->getUsersListWithGrpID( $opt );
        } else if ( in_array( $user_type , [ CMP_ADMIN, CMP_PM, CMP_MD ] ) ){
            $grp_users_select_opt = true;
            $groupId = com_arrIndex($form_data, 'search-order-grp', '0');
            $for_user_id = com_arrIndex( $form_data, 'search-order-grp-holder', 0);
            if( $groupId && ( $for_user_id == $this->flexi_auth->get_user_custom_data( 'uacc_id' ) ) ){
                $opt = [];
                $opt[ 'groupId' ] = $groupId;
                $opt[ 'user_id' ] = $for_user_id;
                $opt[ 'user_type' ] = $user_type;
                $group_users  =  $this->Commonusermodel->getUsersListWithGrpID( $opt );
            } else {
                $group_users =  $this->Commonusermodel->getCompanyUsersList( );
            }
		}  else if ( $user_type == USER ){
            $group_users = [ ];
            $group_users[ 0 ][ 'uacc_id' ] = $this->flexi_auth->get_user_custom_data('uacc_id');
            $group_users[ 0 ][ 'uacc_username' ] = ucfirst($this->flexi_auth->get_user_custom_data('uacc_username'));
        }
        $this->data[ 'group_users' ]   = com_makelist( $group_users, 'uacc_id', 'uacc_username', $grp_users_select_opt, 'Select User' );
		$this->data['orders'] = $this->Ordermodel->fetchOrders( $fetchParam );        
        $orderStatus = $this->Ordermodel->fetchDistinctStatus();
        $this->data['orders_status'] =  com_makelist($orderStatus, 'status', 'status', TRUE, 'Select' );
        $this->data['user_type'] = $user_type;
        $this->data['form_data']  = $form_data;

        // pagination configuration start
            $config['cur_page']       = $offset;
            $config['total_rows']     = $this->Ordermodel->countAllOrders( $orderParam );
            $config['html_container'] = 'order-view-div';
            $config['base_url']       = base_url().'order/ajax/order/history/';
            $config['per_page']       = $perpage;
            $config['js_rebind']      = '';
            $config['form_serialize'] = 'order-search-for';
            $this->data['pagination'] =  com_ajax_pagination( $config );
        // pagination configuration end
                
        $output = [
                    'success' => 1,
                    'html' => $this->load->view('ajax/orders-history-table', $this->data, TRUE),
        		];
        echo json_encode( $output );
        exit;
    }
}
