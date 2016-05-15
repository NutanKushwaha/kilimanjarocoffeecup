<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
    CREATE TABLE `bg_order` (
     `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
     `customer_id` int(10) unsigned NOT NULL,
     `company_code` varchar(255) NOT NULL DEFAULT '',
     `company_name` varchar(255) NOT NULL DEFAULT '',
     `is_guest_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
     `order_num` varchar(255) NOT NULL,
     `order_qty` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0',
     `transaction_no` varchar(255) NOT NULL DEFAULT '',
     `cart_total` decimal(10,2) NOT NULL DEFAULT '0.00',
     `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
     `order_total` decimal(10,2) NOT NULL DEFAULT '0.00',
     `order_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
     `status` varchar(255) NOT NULL DEFAULT '',
     `status_updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
     `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
     PRIMARY KEY (`id`),
     UNIQUE KEY `order_num` (`order_num`),
     KEY `customer_id` (`customer_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
*/
class OrderModel extends Commonmodel {
	private $data;
	private $comp_order_status;
    function __construct() {
        parent::__construct();        
        $this->set_attributes();
        $this->comp_order_status = [ 'normal' => '0',
									 'stock_order' => '1',
									 'stock_issued' => '2',
									 'stock_cancelled' => '3',
									];
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'order';
        $this->tbl_pk_col = 'id';

        $this->tbl_alias = 'ord';
	}

    public function addOrder( $opts = [] ) {
        extract( $opts );
        $output = [];
        $output['status'] = '0';
        $output['message'] = 'Please add item in cart';
        $this->load->model('cart/Cartmodel', 'Cartmodel');
        $this->load->model('order/Orderitemmodel', 'Orderitemmodel');
        $this->load->model('order/Ordershipdetmodel', 'Ordershipdetmodel');
        $cartContents = $this->cart->contents();
        if ($cartContents) {
            $orderItems = [];
            $insert_order_id = 0;
            $is_reordered = '';
            foreach ($cartContents as $cartIndex => $cartItemDetail) {
                $is_reordered = com_arrIndex($cartItemDetail['options'], 'reorder', 0);
                $productSku = $cartItemDetail['options']['product']['product_sku'];
                $orderItems[$cartIndex]['order_id'] = &$insert_order_id;
                $orderItems[$cartIndex]['product_ref'] = $productSku;
                $orderItems[$cartIndex]['order_item_qty'] = $cartItemDetail['qty'];
                $orderItems[$cartIndex]['order_item_name'] = $cartItemDetail['name'];
                $orderItems[$cartIndex]['order_item_price'] = $cartItemDetail['price'];
                $orderItems[$cartIndex]['order_item_options'] = json_encode($cartItemDetail['options']);
            }
            if ($orderItems) {
                $order = [];
                /* Inser Order */
                /* Logged user Company code */
                $userCompanyCode = $this->flexi_auth->get_user_custom_data('upro_company');
                if( !$userCompanyCode ){
					/* For company stock request */					
					$userCompanyCode = $this->flexi_auth->get_comp_admin_company_code();
				}				
                /* Company name set to "" */
                $param = [];
                $param['select'] = 'name';
                $param['result'] = 'row';
                $param['from_tbl'] = 'company';
                $param['where']['company_code'] = $userCompanyCode;
                $companyName = $this->Cartmodel->get_all($param);
                $order['company_name'] = '';
                if ($companyName) {
                    /* Company name set to code owner name */
                    $order['company_name'] = $companyName['name'];
                }
                $order['is_paid'] = 0;
                $order['discount'] = 0;
                $order['is_guest_order'] = 0;
                $order['status'] = '';
                $order['transaction_no'] = '';
                $order['is_reordered'] = $is_reordered;
                $order['order_num'] = strtoupper( uniqid('ORD_') );                
                $order['order_time'] = date('Y-m-d H:i:s');
                $order['cart_total'] = $this->cart->total();
                $order['order_total'] = $this->cart->total();
                $order['status_updated_on'] = date('Y-m-d H:i:s');
                $order['order_qty'] = $this->cart->total_items();
                $order['customer_id'] = $this->flexi_auth->get_user_custom_data('uacc_id');
                $order['company_code'] = $this->flexi_auth->get_user_custom_data('upro_company');
                if( isset( $user_type ) && $user_type == CMP_ADMIN ){
                    $order['is_stock_order'] = $this->comp_order_status[ 'stock_order' ];
                }
                if( isset( $user_type ) && $user_type == USER ){
                    //$order['order_to'] = 2;
                }
                $insert_order_id = $this->Ordermodel->insert($order);
                $order_ship_detail = [];
                $order_ship_detail['order_id'] = $insert_order_id;
                $order_ship_detail['billing_city'] = '';
                $order_ship_detail['billing_county'] = '';
                $order_ship_detail['billing_zipcode'] = '';
                $order_ship_detail['billing_address1'] = '';
                $order_ship_detail['billing_address2'] = '';
                $order_ship_detail['phone'] = '';
                $order_ship_detail['city'] = $this->flexi_auth->get_user_custom_data('uadd_city');
                $order_ship_detail['county'] = $this->flexi_auth->get_user_custom_data('uadd_county');
                $order_ship_detail['country'] = $this->flexi_auth->get_user_custom_data('uadd_country');
                $order_ship_detail['order_email'] = $this->flexi_auth->get_user_custom_data('uacc_email');
                $order_ship_detail['postcode'] = $this->flexi_auth->get_user_custom_data('uadd_post_code');
                $order_ship_detail['last_name'] = $this->flexi_auth->get_user_custom_data('upro_last_name');
                $order_ship_detail['address_1'] = $this->flexi_auth->get_user_custom_data('uadd_address_01');
                $order_ship_detail['address_2'] = $this->flexi_auth->get_user_custom_data('uadd_address_02');
                $order_ship_detail['first_name'] = $this->flexi_auth->get_user_custom_data('upro_first_name');
                foreach ($order_ship_detail as $shipKey => $shipDet) {
                    /* change null to blank */
                    com_changeNull( $order_ship_detail[$shipKey ] , '');
                }                
                $this->Orderitemmodel->insert_bulk($orderItems);
                $this->Ordershipdetmodel->insert($order_ship_detail);
                $this->sendOrderEmail($insert_order_id);
                $output['status'] = '1';
                $output['message'] = 'Order placed successfully';
            }
        }
        $this->Cartmodel->emptyCart();
        return $output;
    }

    function sendOrderEmail($order_id)
    {
        $orderDetails = $this->Ordermodel->getOrderDetails($order_id);
        $orderItems = $this->Ordermodel->getOrderItems($order_id);
        
        $details = array();
        $details['order_details'] = $orderDetails;
        $details['order_items'] = $orderItems;
        $emailContent = $this->load->view('includes/order-email',$details,true);
        /*
        $this->load->library('email');
         $this->email->clear();
            $this->email->to($orderDetails['uacc_email']);
            $this->email->from(MCC_EMAIL_NOREPLY);
            $this->email->subject('Order placed successfully #'.$orderDetails['order_num'] );
            $this->email->message($emailContent);
            $this->email->send();*/
        $this->load->library('SEmail');
        $email_config = [
            'to' => $orderDetails['uacc_email'],
            //'to' => 'devrohit46@gmail.com',
            'subject' => 'Order email',
            'from' => MCC_EMAIL_FROM,
            'body' => $emailBody
        ];
        $status =  $this->semail->send_mail( $email_config );   
        unset($details);
    }
	public function check_order_exist( $order_num ){
		$param = [];
		$param[ 'result' ] = 'num';		
		$param[ 'where'  ][ 'order_num' ] = $order_num;
		return $this->get_all( $param );
	}
	
	public function update_order_status( $update_order ){
		
		$output[ 'status' ] = 0;
		$output[ 'msg' ] 	= "OrderNum could not found";
		if( isset( $update_order[ 'OrderNum' ] ) ){
			$order_num = $update_order[ 'OrderNum' ];
			$output[ 'status' ] = 1;
			$output[ 'msg' ] 	= "OrderNum is not exist";
			if(  $this->check_order_exist( $order_num ) && isset( $update_order[ 'TrackNum' ] ) ){
				$order_status = $update_order[ 'OrderStatus' ];
				$track_num = $update_order[ 'TrackNum' ];
				$data = [];
				$data[ 'data'   ][ 'track_num' ] = $track_num;
				$data[ 'data'   ][ 'status' ] = $order_status;
				$data[ 'where'  ][ 'order_num' ] = $order_num;
				if( $this->update_record( $data ) ){					
					$this->load->model('order/Orderdocsmodel', 'Orderdocsmodel');
					$doc_info = com_arrIndex($update_order, 'DocInfo', '' );
					$doc_name = com_arrIndex($update_order, 'DocuName', '' );
					$data = [];
					$data[ 'order_num' 	 ] = $order_num;
					$data[ 'order_status'] = $order_status;
					$data[ 'doc_info' 	 ] = $doc_info;
					$data[ 'doc_name' 	 ] = $doc_name;
					$this->Orderdocsmodel->insert( $data );
					$output[ 'status' ] = 4;
					$output[ 'msg' ] 	= 'Order status updated successfully';
				} else {
					$output[ 'status' ] = 3;
					$output[ 'msg' ] 	= 'Server internal error';
					
				}
			} 
		}
		return $output;
	}
	
    public function fetchMaxOrderQty( $param = []){
        $param[ 'select' ]  ='max(order_qty) as maxq';
        $param[ 'result' ]  ='row';
        return $this->get_all( $param );
    }

    public function fetchMaxOrderAmout( $param = []){
        $param[ 'select' ]  ='max(order_total) as maxl';
        $param[ 'result' ]  ='row';
        return $this->get_all( $param );
    }

    public function fetchDistinctStatus( $param = []){
        $param[ 'select' ]  ='distinct(status) as status';
        $param[ 'where' ][ 'status != ' ]  ='';
        return $this->get_all( $param );
    }

    private function _search_form_check( $form_data, &$param ){
        /* paid */
        $order_paid = com_arrIndex($form_data ,'paid' , '');
        if( $order_paid ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'is_paid', 1 ];
        }

        /* stock order */
        $stock_order = com_arrIndex($form_data ,'stock_order' , '');
        if( $stock_order ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'is_stock_order >', 0 ];
        }

        /* search-order-grp */
        $ordered_user_group = com_arrIndex($form_data ,'search-order-grp' , '');
        if( $ordered_user_group ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'ugrp_id', $ordered_user_group ];
        }

        /* Order num check */
        $order_num_from = com_arrIndex($form_data ,'search-order-num' , '');
        if( $order_num_from ){
            $param[ 'where' ][ 'like' ][  ] = [ 'order_num',
                                                $order_num_from,
                                            ];
        }

        /* Order quantity check */
        $order_qty_to = com_arrIndex($form_data ,'search-orderqty-to' , 0);
        $order_qty_from = com_arrIndex($form_data ,'search-orderqty-from' , 0);
        if( $order_qty_from == $order_qty_to && $order_qty_to){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_qty', $order_qty_to ];
        } else {
            if( $order_qty_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_qty >=', $order_qty_from ];
            }
            if(  $order_qty_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_qty <=', $order_qty_to ];
            }
        }

        /* Order date check */
        $order_date_to = com_arrIndex($form_data ,'search-orderdate-to' , 0);
        $order_date_from = com_arrIndex($form_data ,'search-orderdate-from' , 0);
        if( $order_date_from == $order_date_to && $order_date_from){
            $order_frm_date_in_format = date( 'Y-m-d 00:00:00', strtotime( $order_date_from) );
            $order_too_date_in_format = date( 'Y-m-d 23:59:59', strtotime( $order_date_from) );                
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time >= ', $order_frm_date_in_format ];
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time <= ', $order_too_date_in_format ];
        } else {            
            if(  $order_date_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time >=', date( 'Y-m-d 00:00:00', strtotime( $order_date_from) ) ];
            }
            if(  $order_date_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time <=', date( 'Y-m-d 23:59:59', strtotime( $order_date_to) ) ];
            }
        }

        /* Order Customer check */
        $customer_id = com_arrIndex($form_data ,'search-order-grp-holder' , 0);
        if( $customer_id ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'customer_id', $customer_id ];
        }

        /* Order Price Total check */
        $order_total_to = com_arrIndex($form_data ,'search-orderttl-to' , 0);
        $order_total_from = com_arrIndex($form_data ,'search-orderttl-from' , 0);
        if( $order_total_to == $order_total_from  && $order_total_to){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_total', $order_total_from ];
        } else {
            if(  $order_total_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_total >=', $order_total_from ];
            }
            if(  $order_total_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_total <=', $order_total_to ];
            }
        }

        /* Order Status check */
        $order_status = com_arrIndex($form_data ,'search-order-status' , 0);
        if( $order_status ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'status', $order_status ];
        }
    }
    
    public function fetchOrders( $opts = []){
        $offset = 0; 
        $perpage = 15; 
        $user_id = 0;
        $user_type = NULL;
        extract( $opts );
        $param = [];
        if( $form_data ){
            $this->_search_form_check($form_data, $param);
        }
        if( $user_type == ADMIN ){
//            $param[ 'where' ][ 'order_to' ]   =   1;        
        }else if( $user_type == CMP_ADMIN ) {
            if( !isset( $company_code ) ) 
                $companyCode = $this->flexi_auth->get_comp_admin_company_code();
            else 
                $companyCode = $company_code;                
            $param[ 'where' ][ 'is_guest_order' ]   =   0;
            $customer_id = com_arrIndex($form_data ,'search-order-grp-holder' , 0);
            if( $this->flexi_auth->get_user_custom_data('uacc_id') !== $customer_id){
                $param[ 'where' ][ 'company_code' ] =  $companyCode;
			}
        } else if( in_array($user_type, [CMP_PM, CMP_MD] ) ){
			$companyCode = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			$group_order = $this->flexi_auth->get_user_custom_data( 'ugrp_order' );
			$param[ 'where' ][ 'company_code' ] =  $companyCode;
			$param[ 'where' ][ 'ugrp_admin' ] = 1;
			$param[ 'where' ][ 'ugrp_order >= ' ] = $group_order;
		} else if ( $user_type == USER  ) {
            $param[ 'where' ][ 'is_guest_order' ]   =   0;
            $param[ 'where' ][ 'customer_id' ]   =   $this->flexi_auth->get_user_custom_data('uacc_id');
        }
        $param[ 'limit' ]   =   [ 'limit' => $perpage, 'offset' => $offset ];
        $param[ 'select' ]  ='  uacc_username,  customer_id, is_guest_order, 
                                company_code, order_num, order_total, ugrp_name, order_qty, 
                                DATE_FORMAT( order_time,  "%d-%m-%Y") as order_date, status, is_stock_order ';
        $param[ 'join' ][ ] = [ 'alias' =>      'uac',
                                'tbl'   =>      'user_accounts',
                                'cond'  =>      'uac.uacc_id=customer_id',
                                'type'  =>       'left',
                                'pass_prefix' => true
                                ];
        $param[ 'join' ][ ] = [ 'alias' =>      'ugrp',
                                'tbl'   =>      'user_groups',
                                'cond'  =>      'ugrp.ugrp_id=uacc_group_fk',
                                'type'  =>       'left',
                                'pass_prefix' => true
                            ];
        return $this->get_all( $param );
    }

    function countAllOrders( $opts = [] ){
        $user_type = NULL; 
        $user_id = 0;
        extract( $opts );
        $param = [];
        if( $form_data ){
            $this->_search_form_check($form_data, $param);
        }
        if( $user_type == ADMIN ){
//            $param[ 'where' ][ 'order_to' ]   =   1;        
        }else if( $user_type == CMP_ADMIN ) {
            if( !isset( $company_code )  ) {				
					$companyCode = $this->flexi_auth->get_comp_admin_company_code();				
			}
            else 
                $companyCode = $company_code;
            $param[ 'select' ]  =   'id';
            $param[ 'where' ][ 'is_guest_order' ]   =   0;
            $param[ 'where' ][ 'company_code' ]     =   $companyCode;
            if( $user_id ){
                $param[ 'where' ][ 'customer_id' ]     =   $user_id;
            }
        } else if( in_array($user_type, [CMP_PM, CMP_MD] ) ){
			$companyCode = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			$group_order = $this->flexi_auth->get_user_custom_data( 'ugrp_order' );
			$param[ 'where' ][ 'company_code' ] =  $companyCode;
			$param[ 'where' ][ 'ugrp_admin' ] = 1;
			$param[ 'where' ][ 'ugrp_order >= ' ] = $group_order;
		} else if ( $user_type == USER  ) {
            $param[ 'select' ]  =   'id';
            $param[ 'where' ][ 'is_guest_order' ]   =   0;
            $param[ 'where' ][ 'customer_id'    ]   =   $this->flexi_auth->get_user_custom_data('uacc_id');
        }
        $param[ 'join' ][ ] = [ 'alias' =>      'uac',
                                'tbl'   =>      'user_accounts',
                                'cond'  =>      'uac.uacc_id=customer_id',
                                'type'  =>       'left',
                                'pass_prefix' => true
                                ];
        $param[ 'join' ][ ] = [ 'alias' =>      'ugrp',
                                'tbl'   =>      'user_groups',
                                'cond'  =>      'ugrp.ugrp_id=uacc_group_fk',
                                'type'  =>       'left',
                                'pass_prefix' => true
                            ];        
        return $this->count_rows( $param );
    }

    function fetchOrderDet( $order_num = null ){
        $param = [];
        $param[ 'join' ][] = [  'tbl' => $this->db->dbprefix('order_item') .' as ord_item',
                                'cond' => 'ord.id=ord_item.order_id',
                                'type' => 'left',
                                'pass_prefix' => true,
                            ];
        $param[ 'join' ][] = [  'tbl' => $this->db->dbprefix('order_ship_detail') .' as ordShip',
                                'cond' => 'ord.id=ordShip.order_id',
                                'type' => 'left',
                                'pass_prefix' => true,
                            ];
        $param[ 'where'][ 'order_num' ] = $order_num;
        return $this->get_all( $param );
    }

    function fetchAssignedOrderDet ( $order_num = null ){
        $param = [];
        $param[ 'select' ]      = 'company_code, product_code, stock_qty, store_id';
        $param[ 'from_tbl' ]    = 'company_stock';
        $param[ 'where'][ 'order_ref' ] = $order_num;
        return $this->get_all( $param );
    }

    function issueOrder( $order_detail ){        
        $order_ref = $order_detail[ 0 ][ 'order_num' ];
        $customer_id = $order_detail[ 0 ][ 'customer_id' ];
        $params = [];
        $params[ 'select' ] = 'company_code';
        $params[ 'from_tbl' ] = 'company';
        $params[ 'result' ] = 'row';
        $params[ 'where' ][ 'account_id' ] = $customer_id;
        $company_code  = $this->get_all( $params )[ 'company_code' ];
        $insert_company_stock_allocation = [];
        $posted_products = com_gParam( 'product', False );
        $posted_cStores = com_gParam( 'comp_store', False );
        $prodIndex = 0;
        foreach( $posted_products as $productSku => $productQty ){
			$insert_company_stock_allocation[ $prodIndex ][ 'store_id' ] = $posted_cStores[ $productSku ];
            $insert_company_stock_allocation[ $prodIndex ][ 'order_ref' ] = $order_ref;
            $insert_company_stock_allocation[ $prodIndex ][ 'stock_qty' ] = $productQty;
            $insert_company_stock_allocation[ $prodIndex ][ 'product_code' ] = $productSku;
            $insert_company_stock_allocation[ $prodIndex ][ 'company_code' ] = $company_code;            
            $insert_company_stock_allocation[ $prodIndex ][ 'issue_date_time' ] = date('Y-m-d H:i:s');
            $prodIndex++;
        }
        if( $insert_company_stock_allocation ){
            $this->load->model('company/Compstockmodel', 'Compstockmodel');
            $this->Compstockmodel->insert_bulk( $insert_company_stock_allocation );
            $params = [];
            $params[ 'data' ][ 'is_stock_order' ] = '2';
            $params[ 'where' ][ 'order_num' ] = $order_ref;
            $this->update_record( $params );
        }

    }

    private function _stock_search_form_check( $form_data, &$param ){
        /* Order num check */
        $order_num_from = com_arrIndex($form_data ,'stock-order-num' , '');
        if( $order_num_from ){
            $param[ 'where' ][ 'like' ][  ] = [ 'order_num',
                                                $order_num_from,
                                            ];
        }

        /* Order quantity check */
        $stock_qty_to = com_arrIndex($form_data ,'stock-request-orderqty-to' , 0);
        $stock_qty_from = com_arrIndex($form_data ,'stock-request-orderqty-from' , 0);
        if( $stock_qty_from == $stock_qty_to && $stock_qty_to){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_qty', $stock_qty_to ];
        } else {
            if( $stock_qty_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_qty >=', $stock_qty_from ];
            }
            if(  $stock_qty_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_qty <=', $stock_qty_to ];
            }
        }

        /* Order date check */
        $stock_date_to = com_arrIndex($form_data ,'stock-request-orderdate-to' , 0);
        $stock_date_from = com_arrIndex($form_data ,'stock-request-orderdate-from' , 0);
        if( $stock_date_from == $stock_date_to && $stock_date_from){
            $stock_frm_date_in_format = date( 'Y-m-d 00:00:00', strtotime( $stock_date_from) );
            $stock_too_date_in_format = date( 'Y-m-d 23:59:59', strtotime( $stock_date_from) );
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time >= ', $stock_frm_date_in_format ];
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time <= ', $stock_too_date_in_format ];
        } else {            
            if(  $stock_date_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time >=', date( 'Y-m-d 00:00:00', strtotime( $stock_date_from) ) ];
            }
            if(  $stock_date_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_time <=', date( 'Y-m-d 23:59:59', strtotime( $stock_date_to) ) ];
            }
        }

        /* Order Customer check */
        $customer_id = com_arrIndex($form_data ,'stock-grp-holder' , 0);
        if( $customer_id ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'customer_id', $customer_id ];
        }

        /* Order Price Total check */
        $order_total_to = com_arrIndex($form_data ,'stock-request-orderttl-to' , 0);
        $order_total_from = com_arrIndex($form_data ,'stock-request-orderttl-from' , 0);
        if( $order_total_to == $order_total_from  && $order_total_to){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'order_total', $order_total_from ];
        } else {
            if(  $order_total_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_total >=', $order_total_from ];
            }
            if(  $order_total_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'order_total <=', $order_total_to ];
            }
        }

        /* Order Status check */
        $order_status = com_arrIndex($form_data ,'stock-request-order-status' , 0);
        if( $order_status ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'status', $order_status ];
        }
    }

    public function addOrderFromReqStock( $opts = [] ) {		
        extract( $opts );
        $output = [];
        $output['status'] = '0';
        $output['message'] = 'Please add item in cart';
        $this->load->model('cart/Cartmodel', 'Cartmodel');
        $this->load->model('order/Orderitemmodel');
        $this->load->model('order/Ordershipdetmodel');
        
        if ($cartContents) {
            $orderItems = [];
            $insert_order_id = 0;
            $is_reordered = '';
            foreach ($cartContents as $cartIndex => $cartItemDetail) {
                $is_reordered = com_arrIndex($cartItemDetail['options'], 'reorder', 0);
                $productSku = $cartItemDetail['options']['product']['product_sku'];
                $orderItems[$cartIndex]['order_id'] = &$insert_order_id;
                $orderItems[$cartIndex]['product_ref'] = $productSku;
                $orderItems[$cartIndex]['order_item_qty'] = $cartItemDetail['qty'];
                $orderItems[$cartIndex]['order_item_name'] = $cartItemDetail['name'];
                $orderItems[$cartIndex]['order_item_price'] = $cartItemDetail['price'];
                $orderItems[$cartIndex]['order_item_options'] = json_encode($cartItemDetail['options']);
            }
            if ($orderItems) {
                $order = [];
                $order['company_name'] = $company_name;                
                $order['is_paid'] = 0;
                $order['discount'] = 0;
                $order['is_guest_order'] = 0;
                $order['status'] = '';
                $order['transaction_no'] = '';
                $order['is_reordered'] = $is_reordered;
                $order['order_num'] = strtoupper( uniqid('ORD_') );
                $order['order_time'] = date('Y-m-d H:i:s');
                $order['cart_total'] = $cart_total;
                $order['order_total'] = $cart_total;
                $order['status_updated_on'] = date('Y-m-d H:i:s');
                $order['order_qty'] = $cart_items;
                $order['customer_id'] = $customer_id;
                $order['company_code'] = $company_code;
                $order['req_reference'] = $req_reference;
                
                $insert_order_id = $this->Ordermodel->insert($order);
                $order_ship_detail = [];
                $order_ship_detail['order_id'] = $insert_order_id;
                $order_ship_detail['billing_city'] = '';
                $order_ship_detail['billing_county'] = '';
                $order_ship_detail['billing_zipcode'] = '';
                $order_ship_detail['billing_address1'] = '';
                $order_ship_detail['billing_address2'] = '';
                $order_ship_detail['phone'] = '';
                $order_ship_detail['city'] = $custAddress[ 'uadd_city' ];
                $order_ship_detail['county'] = $custAddress[ 'uadd_county' ];
                $order_ship_detail['country'] = $custAddress[ 'uadd_country' ];
                $order_ship_detail['order_email'] = $custAddress[ 'uacc_email' ];
                $order_ship_detail['postcode'] = $custAddress[ 'uadd_post_code' ];
                $order_ship_detail['last_name'] = $custAddress[ 'upro_last_name' ];
                $order_ship_detail['address_1'] = $custAddress[ 'uadd_address_01' ];
                $order_ship_detail['address_2'] = $custAddress[ 'uadd_address_02' ];
                $order_ship_detail['first_name'] = $custAddress[ 'upro_first_name' ];
                foreach ($order_ship_detail as $shipKey => $shipDet) {
                    /* change null to blank */
                    com_changeNull( $order_ship_detail[$shipKey ] , '');
                }                
                $this->Orderitemmodel->insert_bulk($orderItems);
                $this->Ordershipdetmodel->insert($order_ship_detail);
                $output['status'] = '1';
                $output['message'] = 'Order placed successfully';
            }
        }        
        return $output;
    }    
    
    function getOrderWithDetailAsObjectForXml( $opt = [] ){
		extract( $opt );
        $param = [];
        if( isset( $where ) && is_array( $where ) ){
            foreach($where as $whereDet){                
                $param[ 'where' ][ $whereDet[0] ] = $whereDet[1];
            }
		}
		if( isset( $limit ) ){
			$param[ 'limit' ][ 'limit' ] = intval( $limit );
			$param[ 'limit' ][ 'offset' ] = isset( $offset ) ? intval( $offset ) : 0;
		}

        $param[ 'select' ] = 'customer_id, company_code, company_name, is_guest_order, order_num, order_qty, transaction_no, cart_total,
								discount, vat, order_total, order_time, status, is_paid, VendorTxCode, 	product_ref, order_item_qty, 
								order_item_name, order_item_price, order_item_desc, order_item_options, order_email, first_name, last_name, 
								address_1, address_2, city, county, postcode, country, phone, company, billing_phone, billing_company, 
								billing_country, billing_zipcode, billing_address1, billing_address2, upro_first_name, upro_last_name
							';
        $param[ 'join' ][] = [  'tbl' => $this->db->dbprefix('order_item') .' as ord_item',
                                'cond' => 'ord.id=ord_item.order_id',
                                'type' => 'left',
                                'pass_prefix' => true,
                            ];
        $param[ 'join' ][] = [  'tbl' => $this->db->dbprefix('order_ship_detail') .' as ordShip',
                                'cond' => 'ord.id=ordShip.order_id',
                                'type' => 'left',
                                'pass_prefix' => true,
                            ];
        $param[ 'join' ][] = [  'tbl' => $this->db->dbprefix('user_profiles') .' as userProfile',
                                'cond' => 'ord.customer_id=userProfile.upro_uacc_fk',
                                'type' => 'left',
                                'pass_prefix' => true,
                            ];
		$param[ 'where' ][ 'in_array' ][ ] = [ '0' => 'is_stock_order', '1' => [ '0', '2' ]];
        $orders_with_detail = $this->get_all( $param );        
        $sale_orders = [];
        
        $order_index	= 0;
        $order_detail	= 0;
        $order_num 		= null;
        foreach($orders_with_detail as $order_key => $order_det){
			if( $order_num !== $order_det[ 'order_num' ] ){
				$order_index++;
				$order_detail = 0;
				$order_num = $order_det[ 'order_num' ];
			}else{
				$order_detail++;
			}
			$parent_product_sku = '';
			if( !empty($order_det[ 'order_item_options' ]) ){
				$product_options = json_decode($order_det[ 'order_item_options' ], true);
				$attribute_selection = "";
				if( isset( $product_options[ 'attributes' ] ) && !empty( $product_options[ 'attributes' ] ) ){
					$attribute_selection = [];
					foreach( $product_options[ 'attributes' ] as $attrbIndex => $attrbDet ){						
						$attribute_selection[] = $attrbDet['label'].':'.$attrbDet['value'] ;
					}					
				}
				if( is_array( $attribute_selection ) && $attribute_selection ){
					$attribute_selection = implode(", ", $attribute_selection);
				}				
				if( isset( $product_options[ 'product' ] )  && !empty( $product_options[ 'product' ] ) ){
					if( isset( $product_options[ 'is_sap' ] ) && !$product_options[ 'is_sap' ] 
						&& isset( $product_options[ 'parent_sku' ] ) && !empty( $product_options[ 'parent_sku' ] ) ){
						$parent_product_sku = $product_options[ 'parent_sku' ];
					}
				}
			}
			$product_price_after_vat = $order_det[ 'order_item_price' ] + ($order_det[ 'order_item_price' ] * $order_det[ 'vat' ] / 100);
			$sale_orders[ $order_index ][ 'Order' ][ 'WardRobeOrder' ] = $order_num;
			$sale_orders[ $order_index ][ 'Order' ][ 'CardCode' ] = in_array( trim($order_det[ 'company_code' ]), [ 'No Company', '' ] ) 
																	? "" : $order_det[ 'company_code' ];
			$sale_orders[ $order_index ][ 'Order' ][ 'CardName' ] = $order_det[ 'company_name' ];
			$sale_orders[ $order_index ][ 'Order' ][ 'NumAtCard' ] = $order_det[ 'upro_first_name' ].' '.$order_det[ 'upro_last_name' ];
			$sale_orders[ $order_index ][ 'Order' ][ 'DocTotal' ] = $order_det[ 'order_total' ];
			$sale_orders[ $order_index ][ 'Order' ][ 'VatPercent' ] = $order_det[ 'vat' ];
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'LineNum' ] = $order_detail;
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'ItemCode' ] = $order_det[ 'company_name' ];
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'ItemCode' ] = $order_det[ 'product_ref' ];
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'ItemDescription' ] = $order_det[ 'order_item_name' ];
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'Quantity' ] = $order_det[ 'order_item_qty' ];
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'Price' ] = $order_det[ 'order_item_price' ];
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'PriceAfterVAT' ] = $product_price_after_vat;
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'RowTotal' ] = $product_price_after_vat * $order_det[ 'order_item_qty' ];
			$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'Options' ]	= $attribute_selection;
			if( $parent_product_sku ){
				$sale_orders[ $order_index ][ 'OrderDetail' ][ $order_detail ][ 'ParentSku' ]	= $parent_product_sku;	
			}
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToStreet' ]	= $order_det[ 'address_1' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToBlock' ]	= $order_det[ 'address_2' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToStreetNo']= "";			
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToBuilding']= "";
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToCity']	= $order_det[ 'city' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToZipCode']	= $order_det[ 'postcode' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToCounty']	= $order_det[ 'county' ];			
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'ShipToCountry']	= $order_det[ 'country' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'BillToStreet' ]	= $order_det[ 'billing_address1' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'BillToBlock' ]	= $order_det[ 'billing_address2' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'BillToStreetNo']= "";			
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'BillToBuilding']= "";			
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'BillToZipCode']	= $order_det[ 'billing_zipcode' ];
			$sale_orders[ $order_index ][ 'OrderAddress'][ 'BillToCountry']	= $order_det[ 'billing_country' ];
		}
		return $sale_orders;
	}
	
	
	function insertFromXML( $sap_order ){
		$output = [];
		$output[ 'success' ] = FALSE;
		$output[ 'message' ] = 'Server internal error';		
		if ( $sap_order ) {			
			$output[ 'success' ] = TRUE;
			$output[ 'message' ] = 'Order inserted successfully';	
			$this->load->model('order/Orderitemmodel', 'Orderitemmodel');
			$this->load->model('order/Ordershipdetmodel', 'Ordershipdetmodel');
			if ( !isset( $sap_order[ 'Orders' ][0] ) ) {
				$sap_data = $sap_order[ 'Orders' ];
				unset( $sap_order[ 'Orders' ] );
				$sap_order[ 'Orders' ] = [ '0' => $sap_data ];
				unset( $sap_data );
			}			
			foreach( $sap_order[ 'Orders' ] as $order_detail ){
				if( isset( $order_detail['CardCode']) && !empty($order_detail['CardCode']) ){
					$orderItems = [];
					$insert_order_id = 0;
					$is_reordered = '';
					$order_qty = 0;
					
					foreach ($order_detail['Lines'] as $itemIndex => $orderItemDetail) {
						$productSku = com_arrIndex($orderItemDetail, 'ItemCode', '' );
						if( $productSku && !empty( $productSku ) ){
							$orderItems[$itemIndex]['order_id'] = &$insert_order_id;
							$orderItems[$itemIndex]['product_ref'] = $productSku;
							$orderItems[$itemIndex]['order_item_qty'] = floatval( com_arrIndex($orderItemDetail, 'Quantity', '0' ) );
							$orderItems[$itemIndex]['order_item_name'] = com_arrIndex($orderItemDetail, 'Dscription', '0' );
							$orderItems[$itemIndex]['order_item_price'] = com_arrIndex($orderItemDetail, 'Price', '0' );
							$orderItems[$itemIndex]['order_item_options'] = "";
							$order_qty = $order_qty + intval( $orderItems[$itemIndex]['order_item_qty'] );
						}
					}
					
					if( $orderItems ){
						$b_partner_code = $order_detail[ 'CardCode' ];
						$b_partner_name = $order_detail[ 'CardName' ];
						$b_partner_account = $this->db->select( 'upro_uacc_fk' )
														->where('upro_company', $b_partner_code)
														->get( 'user_profiles' )->row_array();
						
						$b_partner_bguard_id = com_arrIndex($b_partner_account, 'upro_uacc_fk', '0');
						$vat_ttl = floatval( $order_detail[ 'VatSum' ] );
						$order_ttl = floatval( $order_detail[ 'DocTotal' ] );
						$cart_total = $order_ttl - $vat_ttl;

						$order['is_paid'] = 0;
						$order['donation'] = '0';
						$order['is_stock_order'] = "0";
						$order['donation_type'] = "";
						$order['donation_amount'] = "";
						$order['req_reference'] = "";
						$order['vat'] = $vat_ttl;
						$order['is_reordered'] = 0;
						$order['discount'] = 0.00;
						$order['voucher_code'] = '';
						$order['transaction_no'] = '';
						$order['customer_id'] = $b_partner_bguard_id;
						$order['company_code'] = $b_partner_code;
						$order['company_name'] = $b_partner_name;
						$order['is_guest_order'] = 0;
						$order['order_num'] = strtoupper( uniqid('ORD_') );
						$order['order_qty'] = $order_qty;
						$order['cart_total'] = $cart_total;
						$order['order_total'] = $order_ttl;
						$order['status'] = '';
						$order['transaction_no'] = '';
						$order['order_time'] = date('Y-m-d H:i:s');
						$order['status_updated_on'] = date('Y-m-d H:i:s');						
						
						$order_ship_detail = [];
						$order_ship_detail['order_id'] = &$insert_order_id;
						$order_ship_detail['billing_city'] = '';
						$order_ship_detail['billing_county'] = '';
						$order_ship_detail['billing_zipcode'] = '';
						$order_ship_detail['billing_address1'] = '';
						$order_ship_detail['billing_address2'] = '';
						$order_ship_detail['phone'] = '';
						$order_ship_detail['city'] = '';
						$order_ship_detail['county'] = '';
						$order_ship_detail['country'] = '';
						$order_ship_detail['order_email'] = '';
						$order_ship_detail['postcode'] = '';
						$order_ship_detail['last_name'] = '';
						$order_ship_detail['address_1'] = '';
						$order_ship_detail['address_2'] = '';
						$order_ship_detail['first_name'] = '';
						$insert_order_id = $this->Ordermodel->insert($order);
						$this->Orderitemmodel->insert_bulk( $orderItems );
						$this->Ordershipdetmodel->insert( $order_ship_detail );
						$output['status'] = '1';
						$output['message'] = 'Order placed successfully';						
					}
				}
			}
		}
		return $output;
	}

    function getOrderDetails($id = false){
        $this->db->select("ord.*,ua.*,up.*,osd.*",false);
        $this->db->where('ord.id',$id);
        $this->db->from("order ord");
        $this->db->join("user_accounts  ua","ua.uacc_id = ord.id");
        $this->db->join("user_profiles  up","up.upro_uacc_fk  = ua.uacc_id");
        $this->db->join("order_ship_detail  osd","osd.order_id  = ord.id");
        $query = $this->db->get();
        return $query->row_array();
    }

    function getOrderItems($order_id)
    {
        $this->db->select("itms.*");
        $this->db->where("order_id",$order_id);
        $query_item = $this->db->get("order_item itms");
        return $query_item->result_array();
    }
}
