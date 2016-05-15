<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
	CREATE TABLE `bg_request` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`customer_id` int(10) unsigned NOT NULL,
		`company_code` varchar(255) NOT NULL DEFAULT '',
		`company_name` varchar(255) NOT NULL DEFAULT '',
		`req_num` varchar(255) NOT NULL,
		`req_qty` int(10) unsigned NOT NULL DEFAULT '0',
		`cart_total` decimal(10,2) NOT NULL DEFAULT '0.00',
		`req_total` decimal(10,2) NOT NULL DEFAULT '0.00',
		`req_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 request, 2 request, 3 request + stock issue, 4 request + cancel, 5 all cancelled',
		`status_updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`is_rerequested` varchar(255) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`),
		UNIQUE KEY `req_num` (`req_num`),
		KEY `customer_id` (`customer_id`)
	)
*/
class RequestModel extends Commonmodel {

	private $data;
    public $reqStatus;
    function __construct() {
        parent::__construct();        
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();
        $this->tbl_name = 'request';
        $this->tbl_pk_col = 'id';
        $this->tbl_alias = 'req';
        $manage_stock = 1;
        if( $this->user_type == CMP_MD or $this->user_type == CMP_PM or 
			$this->user_type == USER or $this->user_type == CMP_ADMIN){
			if( $this->user_type == CMP_ADMIN ){
				$comp_code = $this->flexi_auth->get_comp_admin_company_code();
			} else {
				$comp_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			}
			$opt = [];
			$opt[ 'result' ] = 'row';
			$opt[ 'select' ] = 'manage_stock';
			$opt[ 'from_tbl' ] = 'company';
			$opt[ 'where' ][ 'company_code' ] = $comp_code;
			$company_det = $this->get_all( $opt );
			if( !$company_det[ 'manage_stock' ] ){
				$manage_stock = 0;
			}
		} 

        $this->reqStatus = [
                      1 => 'Pending at company',
                      2 => 'Order issued',
                      3 => 'Order and stock issued',
                      4 => 'Stock issued and cancel N/A',
                      5 => 'Canceled',
                    ];
        $this->reqActions = [                      
                      2 => 'Issue order',
                      3 => 'Issue order and stock',
                      4 => 'Issue stock and cancel other',
                      5 => 'Cancel stock request',
                    ];
		if( !$manage_stock ){
			
			$this->reqStatus = [
                      1 => 'Pending',
                      2 => 'Order issued',
                      5 => 'Canceled',
                    ];
			$this->reqActions = [
						  2 => 'Issue order',
						  5 => 'Cancel request',
						];
		}

	}

    public function addRequest( $opts = [] ) {		
        extract( $opts );
        $output = [];
        $output['status'] = '0';
        $output['message'] = 'Please add item in cart';
        $this->load->model('cart/Cartmodel', 'Cartmodel');
        $this->load->model('Requestitemmodel');
        $availCart = $this->cart->contents();
        if ($availCart) {
            $requestItems = [];
            $insert_request_id = 0;
            $is_rerequested = '';
            foreach ($availCart as $cartIndex => $cartItemDetail) {
                $is_rerequested = com_arrIndex($cartItemDetail['options'], 'rerequest', 0);
                $productSku = $cartItemDetail['options']['product']['product_sku'];
                $requestItems[$cartIndex]['req_id'] = &$insert_request_id;
                $requestItems[$cartIndex]['product_ref'] = $productSku;
                $requestItems[$cartIndex]['req_item_qty'] = $cartItemDetail['qty'];
                $requestItems[$cartIndex]['req_item_name'] = $cartItemDetail['name'];
                $requestItems[$cartIndex]['req_item_price'] = $cartItemDetail['price'];
                $requestItems[$cartIndex]['req_item_options'] = json_encode($cartItemDetail['options']);
            }
            //com_e( $requestItems );
            if ($requestItems) {
                $request = [];
                /* 	
            		Inser request
                	Logged user Company code
					request made by company user
                */                
                $userCompanyCode = $this->flexi_auth->get_user_custom_data('upro_company');
                /* Company name set to "" */
                $param = [];
                $param['select'] = 'name';
                $param['result'] = 'row';
                $param['from_tbl'] = 'company';
                $param['where']['company_code'] = $userCompanyCode;
                $companyName = $this->Cartmodel->get_all($param);
                $request['company_name'] = '';
                if ($companyName) {
                    /* Company name set to code owner name */
                    $request['company_name'] = $companyName['name'];
                }
                $request['request_approval_for'] = $this->flexi_auth->get_user_custom_data( 'upro_approval_acc' );
                $request['is_rerequested'] = $is_rerequested;
                $request['req_num'] = strtoupper( uniqid('REQ-') );
                $request['req_time'] = date('Y-m-d H:i:s');
                $request['cart_total'] = $this->cart->total();
                $request['req_total'] = $this->cart->total();
                $request['status_updated_on'] = date('Y-m-d H:i:s');
                $request['req_qty'] = $this->cart->total_items();
                $request['customer_id'] = $this->flexi_auth->get_user_custom_data('uacc_id');
                $request['company_code'] = $this->flexi_auth->get_user_custom_data('upro_company');
                $insert_request_id = $this->insert($request);
                $this->Requestitemmodel->insert_bulk($requestItems);
                $output['status'] = '1';
                $output['message'] = 'Request placed successfully';
            }
        }
        $this->Cartmodel->emptyCart();
        return $output;
    }

    public function fetchMaxRequestQty( $param = []){
		$where = com_arrIndex($param, 'where', []);
		if( $where ){
			$param[ 'where' ]  = $where;
		}		
        $param[ 'select' ]  ='max(req_qty) as maxq';
        $param[ 'result' ]  ='row';
        return $this->get_all( $param );
    }

    public function fetchMaxRequestAmout( $param = []){
		$where = com_arrIndex($param, 'where', []);
		if( $where ){
			$param[ 'where' ]  = $where;
		}
        $param[ 'select' ]  ='max(req_total) as maxl';
        $param[ 'result' ]  ='row';
        return $this->get_all( $param );
    }

    public function fetchDistinctStatus( $param = []){
        $param[ 'select' ]  ='distinct(status) as status';
        $param[ 'where' ][ 'status != ' ]  ='';
        return $this->get_all( $param );
    }

    private function _search_form_check( $form_data, &$param ){

        /* Order num check */
        $stock_req_num = com_arrIndex($form_data ,'stock-order-num' , '');
        if( $stock_req_num ){
            $param[ 'where' ][ 'like' ][  ] = [ 'req_num',
                                                $stock_req_num,
                                            ];
        }

        /* Order quantity check */
        $stock_req_qty_to = com_arrIndex($form_data ,'stock-request-orderqty-to' , 0);
        $stock_req_qty_from = com_arrIndex($form_data ,'stock-request-orderqty-from' , 0);
        if( $stock_req_qty_to == $stock_req_qty_from && $stock_req_qty_to){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'req_qty', $stock_req_qty_to ];
        } else {
            if( $stock_req_qty_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'req_qty >=', $stock_req_qty_from ];
            }
            if(  $stock_req_qty_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'req_qty <=', $stock_req_qty_to ];
            }
        }

        /* Order date check */
        $stock_request_date_to = com_arrIndex($form_data ,'stock-request-orderdate-to' , 0);
        $stock_request_date_from = com_arrIndex($form_data ,'stock-request-orderdate-from' , 0);
        if( $stock_request_date_from == $stock_request_date_to && $stock_request_date_from){
            $sreq_frm_date_in_format = date( 'Y-m-d 00:00:00', strtotime( $stock_request_date_from) );
            $sreq_too_date_in_format = date( 'Y-m-d 23:59:59', strtotime( $stock_request_date_from) );                
            $param[ 'where' ][ 'and_array' ][  ] = [ 'req_time >= ', $sreq_frm_date_in_format ];
            $param[ 'where' ][ 'and_array' ][  ] = [ 'req_time <= ', $sreq_too_date_in_format ];
        } else {            
            if(  $stock_request_date_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'req_time >=', date( 'Y-m-d 00:00:00', strtotime( $stock_request_date_from) ) ];
            }
            if(  $stock_request_date_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'req_time <=', date( 'Y-m-d 23:59:59', strtotime( $stock_request_date_to) ) ];
            }
        }

        /* Order Customer check */
        $customer_id = com_arrIndex($form_data ,'stock-grp-holder' , 0);
        if( $customer_id ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'customer_id', $customer_id ];
        }

        /* Order Price Total check */
        $stock_request_total_to = com_arrIndex($form_data ,'stock-request-orderttl-to' , 0);
        $stock_request_total_from = com_arrIndex($form_data ,'stock-request-orderttl-from' , 0);
        if( $stock_request_total_to == $stock_request_total_from  && $stock_request_total_to){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'req_total', $stock_request_total_from ];
        } else {
            if(  $stock_request_total_from ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'req_total >=', $stock_request_total_from ];
            }
            if(  $stock_request_total_to ){
                $param[ 'where' ][ 'and_array' ][  ] = [ 'req_total <=', $stock_request_total_to ];
            }
        }

        /* Order Status check */
        $stock_request_status = com_arrIndex($form_data ,'stock-request-order-status' , 0);
        if( $stock_request_status ){
            $param[ 'where' ][ 'and_array' ][  ] = [ 'status', $stock_request_status ];
        }
    }

    public function fetchRequests( $opts = [] ){
		$approval_from = 0;
        $offset = 0; 
        $perpage = 15; 
        $user_id = 0;
        $user_type = NULL;
        extract( $opts );
        $param = [];
        if( $form_data ){
                $this->_search_form_check($form_data, $param);
        }
        if( $approval_from ){
			$param[ 'where' ][ 'request_approval_for' ] = $approval_from;
		}
        /*
            if( $user_type == ADMIN ) {
                $param[ 'where' ][ 'order_to' ]   =   1;        
            } else if( $user_type == CMP_ADMIN ) {
                if( !isset( $company_code ) ) 
                    $companyCode = $this->flexi_auth->get_comp_admin_company_code();
                else 
                    $companyCode = $company_code;
                $param[ 'where' ][ 'is_guest_order' ]   =   0;
                $customer_id = com_arrIndex($form_data ,'search-order-grp-holder' , 0);
                if( $this->flexi_auth->get_user_custom_data('uacc_id') !== $customer_id)
                    $param[ 'where' ][ 'company_code' ]     =   $companyCode;
            } else if ( $user_type == USER  ) {
                $param[ 'where' ][ 'is_guest_order' ]   =   0;
                $param[ 'where' ][ 'customer_id' ]   =   $this->flexi_auth->get_user_custom_data('uacc_id');
            }
        */
		if( $user_type == CMP_ADMIN ) {
            if( !isset( $company_code )  )
                $companyCode = $this->flexi_auth->get_comp_admin_company_code();
            else 
                $companyCode = $company_code;
            $param[ 'select' ]  =   'id';
            $param[ 'where' ][ 'company_code' ]     =   $companyCode;
            if( $user_id ){
                $param[ 'where' ][ 'customer_id' ]     =   $user_id;
            }
		}
        if ( $user_type == USER  ) {
            $param[ 'where' ][ 'customer_id' ]   =   $this->flexi_auth->get_user_custom_data('uacc_id');
        }

        $param[ 'limit' ]   =   [ 'limit' => $perpage, 'offset' => $offset ];
        $param[ 'select' ]  ='  uacc_username,  customer_id, 
                                company_code, req_num, req_total, ugrp_name, req_qty, 
                                DATE_FORMAT( req_time,  "%d-%m-%Y") as req_date, status ';
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

    function countAllRequests( $opts = [] ){
		$approval_from = 0;
        $user_type = NULL; 
        $user_id = 0;
        extract( $opts );
        $param = [];
        if( $form_data ){
            $this->_search_form_check($form_data, $param);
        }
        if( $approval_from ){
			$param[ 'where' ][ 'request_approval_for' ] = $approval_from;
		}        
        /*
        if( $user_type == ADMIN ){
            $param[ 'where' ][ 'order_to' ]   =   1;        
        }else if( $user_type == CMP_ADMIN ) {
            if( !isset( $company_code )  )
                $companyCode = $this->flexi_auth->get_comp_admin_company_code();
            else 
                $companyCode = $company_code;
            $param[ 'select' ]  =   'id';
            $param[ 'where' ][ 'is_guest_order' ]   =   0;
            $param[ 'where' ][ 'company_code' ]     =   $companyCode;
            if( $user_id ){
                $param[ 'where' ][ 'customer_id' ]     =   $user_id;
            }
        } else 
        */
        
		if( $user_type == CMP_ADMIN ) {
            if( !isset( $company_code )  ) {  $companyCode = $this->flexi_auth->get_comp_admin_company_code(); }
            else { $companyCode = $company_code; }
            $param[ 'select' ]  =   'id';
            $param[ 'where' ][ 'company_code' ]     =   $companyCode;
            if( $user_id ){
                $param[ 'where' ][ 'customer_id' ]     =   $user_id;
            }
        } else if ( $user_type == USER  ) {
            $param[ 'select' ]  =   'id';            
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

    function fetchReqDet( $req_num = NUll ){
        $param = [];
        $param[ 'join' ][] = [  'tbl' => $this->db->dbprefix('request_item') .' as req_item',
                                'cond' => 'req.id=req_item.req_id',
                                'type' => 'left',
                                'pass_prefix' => true,
                            ];    
        $param[ 'where'][ 'req_num' ] = $req_num;
        return $this->get_all( $param );
    }    

    function processRequest( $param = []){        
        extract( $param );
        switch ($action) {
            case '2': # Issue order
            case '3': # Issue order and stock
            case '4': # Issue stock and cancel other
                    $this->_makeOrder( $param );
                    $param = [];
                    $param[ 'data' ][ 'status' ] = $action;
                    $param[ 'data' ][ 'status_updated_on' ] = com_getDTFormat( 'mdatetime' );
                    $param[ 'where' ][ 'req_num' ] = $reqNumber;
                    $this->update_record( $param );
                break;

            case '5': # Cancel stock request
                $param = [];
                $param[ 'data' ][ 'status' ] = 5;
                $param[ 'data' ][ 'status_updated_on' ] = com_getDTFormat( 'mdatetime' );
                $param[ 'where' ][ 'req_num' ] = $reqNumber;                
                $this->update_record( $param );
                break;

            default:
				if( $user_type == CMP_ADMIN ){
                    redirect('company/stock_request');
				} else if( $user_type == CMP_MD OR $user_type == CMP_PM ){
					redirect('request/approvals');
				}
			break;
        }
    }

    private function  _makeOrder( $param ){        
        extract( $param );
        $orderDetails   = [];
        $customer_id    = 0;
        $cart_total     = 0.00;
        $cart_items     = 0;

        $req_items = [];
        $availCart = [];
        $availItem = 0;
        $availTotal = 0;
        $requested_product_sku = []; # it hold all product sku about which request has benn placed.
        foreach ($requestDetail as $reqInd => $reqDet) {
            $requestOptions = json_decode( $reqDet[ 'req_item_options' ] , true );
            $reqSku  = com_arrIndex( $requestOptions[ 'product' ] , 'product_sku' , ''); 
            if( $reqSku ){
                $requested_product_sku[] = $reqSku;
            }

            $req_items[ $reqSku ] = $reqDet[ 'id' ];

            $customer_id = $reqDet[ 'customer_id'];
            $availCart[ $reqSku ][ 'qty' ] = $reqDet[ 'req_item_qty' ];
            $availCart[ $reqSku ][ 'name' ] = $reqDet[ 'req_item_name' ];
            $availCart[ $reqSku ][ 'price' ] = $reqDet[ 'req_item_price' ];
            $availCart[ $reqSku ][ 'options' ] = $requestOptions;
            $availTotal += $reqDet[ 'req_item_qty' ] * $reqDet[ 'req_item_price' ];
            $availItem += $reqDet[ 'req_item_qty' ];
        }
				
		if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else {			
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}

        if( $action == 3 || $action == 4 ){
            $param = [];
            $param[ 'from_tbl' ]    = 'company_stock';
            $param[ 'select' ]      = 'sum(stock_qty) as ttl, product_code';
            $param[ 'where' ][ 'store_id' ] = $store_id;
            $param[ 'where' ][ 'in_array' ][ ] = ['product_code' ,$requested_product_sku];
            $param[ 'where' ][ 'company_code' ] = $company_code;            
            $param[ 'group' ] = 'product_code';
            $prodInStock = com_makelist( $this->Requestmodel->get_all( $param ), 'product_code', 'ttl', FALSE) ;
            
            $makeCart = [];
            $stockIssue = [];
            $cancelCart = [];
            $cancelStockDet = [];            
            foreach ($availCart as $pSkuKey => $cartDet) {
                   if( isset( $prodInStock[ $pSkuKey ] ) ) {
                        $diffInStock = $prodInStock[ $pSkuKey ] - $cartDet['qty'];                        
                        $stock_qty = $cartDet['qty'];
                        if( $diffInStock < 0){
                            $stock_qty   = $stock_qty + $diffInStock;
                            $diffInStock = abs( $diffInStock );
                            $makeCart[ $reqSku ][ 'qty' ] = $diffInStock;
                            $makeCart[ $reqSku ][ 'name' ] = $cartDet[ 'name' ];
                            $makeCart[ $reqSku ][ 'price' ] = $cartDet[ 'price' ];
                            $makeCart[ $reqSku ][ 'options' ] = $cartDet[ 'options' ];
                            $cancelCart[ ] = $reqSku;
                            $cart_total += $diffInStock * $cartDet[ 'price' ];
                            $cart_items += $diffInStock;
                        }
                        $stockIssue[ $pSkuKey ][ 'user_id' ] = $customer_id;
                        $stockIssue[ $pSkuKey ][ 'store_id' ] = $store_id;
                        $stockIssue[ $pSkuKey ][ 'stock_qty'] = $stock_qty;
                        $stockIssue[ $pSkuKey ][ 'product_code'] = $pSkuKey;
                        $stockIssue[ $pSkuKey ][ 'request_ref' ] = $reqNumber;
                        $stockIssue[ $pSkuKey ][ 'company_code'] = $company_code;
                        $stockIssue[ $pSkuKey ][ 'issue_date_time' ] = &$issueDTime;
                   } else if( $action == 4 ) {
                        $cancelCart[ ] = $pSkuKey;
                    } else if( $action == 3 ) {
                        $makeCart[ $pSkuKey  ] = $cartDet;
                        $cart_total += $cartDet['qty'] * $cartDet[ 'price' ];
                        $cart_items += $cartDet['qty'];
                    }                    
            }            

            if( $action == 4 ){
                $this->load->model('request/Requestitemmodel' , 'Requestitemmodel'); 
                foreach ($cancelCart as $cancelInd => $cancelVal) {
                    if( isset( $makeCart[ $cancelVal  ] ) ){
                        $cart_total -= $makeCart['qty'] * $makeCart[ 'price' ];
                        $cart_items -= $makeCart['qty'];
                        unset( $makeCart[ $cancelVal  ] );                    
                    }
                    $cancelStockDet = [];
                    $cancelStockDet[ 'where' ][ 'id' ] = $req_items[ $cancelVal ];
                    $cancelStockDet[ 'data' ][ 'is_cancelled' ] = 1;
                    $this->Requestitemmodel->update_record( $cancelStockDet );
                }
            }
            if( $stockIssue ){
                $this->load->model('company/Userstockmodel', 'Userstockmodel');
                $issueDTime = com_getDTFormat( 'mdatetime' );
                $this->Userstockmodel->insert_bulk( $stockIssue);
            }
        } else {
            $makeCart = $availCart;
            $cart_items = $availItem;
            $cart_total = $availTotal;
        }

        $addParam = [];
        $addParam[ 'result' ] = 'row';
        $addParam[ 'from_tbl' ] = 'user_address';
        $addParam[ 'where' ][ 'uadd_uacc_fk' ] = $customer_id;
        $addParam[ 'limit' ] = 1;
        $custAddress = $this->get_all( $addParam );
		$custAddress[ 'uacc_email' ] = '';
        $opt = [];
        $opt[ 'result' ] = 'row';
        $opt[ 'select' ] = 'uacc_email, upro_last_name, upro_first_name';
        $opt[ 'from_tbl' ] = 'user_accounts';
        $opt[ 'join' ][ ] = [
								'tbl' => 'user_profiles',
								'cond' => 'upro_uacc_fk=uacc_id',
								'type' => 'left',
							];
        $opt[ 'where' ][ 'uacc_id' ] = $customer_id;
        $user_email = $this->get_all( $opt );
        
        if( $user_email && $custAddress ){
			$custAddress[ 'uacc_email' ] = $user_email[ 'uacc_email' ];			
			$custAddress[ 'upro_last_name' ] = $user_email[ 'upro_last_name' ];
			$custAddress[ 'upro_first_name' ] = $user_email[ 'upro_first_name' ];
		}
		
        $orderDetails[ 'req_reference' ] = $reqNumber;
        $orderDetails[ 'cart_items' ] = $cart_items;
        $orderDetails[ 'cart_total' ] = $cart_total;
        $orderDetails[ 'customer_id' ] = $customer_id;
        $orderDetails[ 'cartContents' ] = $makeCart;
        $orderDetails[ 'company_name' ] = $this->flexi_auth->get_user_custom_data( 'upro_first_name' ).' '.$this->flexi_auth->get_user_custom_data( 'upro_last_name' ); 
        $orderDetails[ 'company_code' ] = $company_code; 
        $orderDetails[ 'custAddress' ] = $custAddress;
        
        if( $makeCart ){
            $this->load->model('order/Ordermodel', 'Ordermodel');
            $this->Ordermodel->addOrderFromReqStock( $orderDetails );
        }        
    }
}
