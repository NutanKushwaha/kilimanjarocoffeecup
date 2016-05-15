<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Request extends Adminajax_Controller {

    function __construct() {
        parent::__construct();        
        $this->load->model('Requestmodel');
        $this->data = [];
    }

    function addRequest( ){
		$cartRelatedDet = $this->session->userdata( 'cartAssocDet' );
		if( $cartRelatedDet ){
			$direct_order = $this->flexi_auth->get_user_custom_data('upro_direct_order');
			$make_request_to_order = FALSE;
			if( $direct_order ){
				$make_request_to_order = TRUE;
			} else {
				$this->load->model('company/Companymodel', 'Companymodel');
				$company_code = $this->flexi_auth->get_user_custom_data('upro_company');
				$company_detail = $this->Companymodel->getCompanyDetailFromCompanyCode( $company_code );
				if( $company_detail && $company_detail['make_req_order'] ){
					$make_request_to_order = TRUE;
					$opt = [];
					$opt[ 'company_code'] = $company_detail[ 'company_code' ];
					$opt[ 'product_sku' ] = array_keys( $cartRelatedDet );
					$company_stock = $this->Companymodel->getCompanyStock( $opt );
					if( $company_stock ){
						$make_request_to_order = FALSE;
						$company_stock = com_makelist($company_stock, 'product_code', 'ttl', FALSE);
						$not_available_prod_sku = array_diff_key($cartRelatedDet, $company_stock);					
						if( !$not_available_prod_sku ){
							$company_issued_stock = com_makelist($this->Companymodel->getCompanyIssuedStock( $opt ),
													'product_code', 'ttl', FALSE);
							$stock_analyse = [];
							$make_request_to_order = TRUE;
							foreach($company_stock as $prod_sku => $inStock){
								$stock_analyse[ $prod_sku ] = $inStock;
								if( isset( $company_issued_stock[ $prod_sku ] ) ){
									$stock_analyse[ $prod_sku ] -= $company_issued_stock[ $prod_sku ];
								}
								if( $stock_analyse[ $prod_sku ] ){								
									$make_request_to_order = FALSE;
									break;
								}
							}						
						}
					}
				}
			}
			if( $make_request_to_order ){
				$this->load->model('order/Ordermodel', 'Ordermodel');
				$opt = [];
				$opt[ 'user_type' ] = $this->user_type;
				$output = $this->Ordermodel->addOrder( $opt );
				echo json_encode( $output );
				exit();
			}
		}
        $param = [];
        $param[ 'user_type' ] = $this->user_type;
        $output = $this->Requestmodel->addRequest( $param );
        echo json_encode( $output );
        exit;
    }

    function listing( $perpage = 10 ) {
    	$this->load->model( 'Commonusermodel' );
        $form_data = [];
        parse_str($this->input->post("form_data"), $form_data);
    	$offset = $this->input->get_post('offset'); 
    	$user_id = com_gParam( 'user_id', TRUE , 0);
		$user_type = $this->user_type;
		$reqCountParam = [ 'user_type' => $user_type , 
                        'user_id' => $user_id, 
                        'form_data' => $form_data ];
		$fetchParam = [ 'offset' => $offset, 
                        'user_id' => $user_id, 
                        'perpage' => $perpage, 
                        'user_type' => $user_type , 
                        'form_data' => $form_data ];
        
        $this->data['groups'] =  $this->Commonusermodel->getGroups( [ 'user_type' => $user_type ] );
        $maxLimit = $this->Requestmodel->fetchMaxRequestAmout()[ 'maxl' ];
        $maxReqQty = $this->Requestmodel->fetchMaxRequestQty()[ 'maxq' ];

        /* orders price range */
        $priceLimit = range(0, round( $maxLimit ), ceil( $maxLimit/10 ));
        $this->data['srequest_prange'] = array_combine(array_values($priceLimit), $priceLimit);

        /* orders qty range */
        $reqQtyLimit = range(0, round( $maxReqQty ), ceil( $maxReqQty/10 ));
        $this->data['srequest_qrange'] = array_combine(array_values($reqQtyLimit), $reqQtyLimit);

        /*
		$reqStatus = $this->Requestmodel->fetchDistinctStatus();
        $this->data['srequest_status'] =  com_makelist($reqStatus, 'status', 'status', TRUE, 'Select' );
        */
        $this->data['srequest_status'] =  $this->Requestmodel->reqStatus;
        $grp_users_select_opt = false;
        /*        
        if ( $user_type == ADMIN ){
            $grp_users_select_opt = true;
            $groupId = com_arrIndex($form_data, 'search-order-grp', '0');
            $opt = [];
            $opt[ 'groupId' ] = $groupId;
            $opt[ 'user_type' ] = $user_type;
            $group_users  =  $this->Commonusermodel->getUsersListWithGrpID( $opt );
        } else if ( $user_type == CMP_ADMIN ){
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
		}  else 
		*/
		
		if ( $user_type == USER ){
            $group_users = [ ];
            $group_users[ 0 ][ 'uacc_id' ] = $this->flexi_auth->get_user_custom_data('uacc_id');
            $group_users[ 0 ][ 'uacc_username' ] = ucfirst($this->flexi_auth->get_user_custom_data('uacc_username'));
        }
        $this->data[ 'group_users' ]   = com_makelist( $group_users, 'uacc_id', 'uacc_username', $grp_users_select_opt, 'Select User' );
		$this->data['request_detail'] = $this->Requestmodel->fetchRequests( $fetchParam );
        $this->data['user_type'] = $user_type;
        $this->data['form_data']  = $form_data;

        // pagination configuration start
            $config['cur_page']       = $offset;
	        $config['total_rows']     = $this->Requestmodel->countAllRequests( $reqCountParam );
	        $config['html_container'] = 'request-view-div';
	        $config['base_url']       = 'request/ajax/request/listing';
	        $config['per_page']       = $perpage;
	        $config['js_rebind']      = '';
	        $config['form_serialize'] = 'request-search-form';
	        $this->data['pagination'] =  com_ajax_pagination($config);            
        // pagination configuration end
                
        $output = [
                    'success' => 1,
                    'html' => $this->load->view('ajax/request-listing', $this->data, TRUE),
        		];
        echo json_encode( $output );
        exit;
    }
    
    function approvals( $perpage = 10 ) {
    	$this->load->model( 'Commonusermodel' );
        $form_data = [];
        parse_str($this->input->post("form_data"), $form_data);
    	$offset = $this->input->get_post('offset'); 
    	$user_id = com_gParam( 'user_id', TRUE , 0);
		$user_type = $this->user_type;
		$request_opt = [];
		$logged_id = $this->flexi_auth->get_user_custom_data('uacc_id');
		$grp_users_select_opt = false;
		if ( in_array($user_type, [CMP_ADMIN, CMP_PM, CMP_MD ]) ) {
			$grp_users_select_opt = true;
			$logged_id = $this->flexi_auth->get_user_custom_data('uacc_id');
			$request_opt[ 'where' ][ 'request_approval_for' ] = $logged_id;
			$opt = [];
			$opt[ 'select' ] = 'upro_uacc_fk as uacc_id, uacc_username';
			$opt[ 'from_tbl' ] = 'user_profiles';
			$opt[ 'join' ][] = [ 	'tbl' => 'user_accounts', 
									'cond'=> 'uacc_id=upro_uacc_fk',
									'type'=> 'left',
								];
			$opt[ 'where' ][ 'upro_approval_acc' ] = $logged_id;
			$group_users = $this->Commonusermodel->get_all( $opt );
			
		}		
		$reqCountParam = [ 'user_type' => $user_type , 
                        'user_id' => $user_id, 
                        'form_data' => $form_data,
                        'approval_from' => $logged_id
                         ];
		$fetchParam = [ 'offset' => $offset, 
                        'user_id' => $user_id, 
                        'perpage' => $perpage, 
                        'user_type' => $user_type, 
                        'form_data' => $form_data,
                        'approval_from' => $logged_id ];
		
		$grp_opt = [ 'user_type' => $user_type, 'grp_order' => $this->flexi_auth->get_user_custom_data( 'ugrp_order' ) ];
		
        $this->data['groups'] =  $this->Commonusermodel->getGroups( $grp_opt );
        $maxLimit = $this->Requestmodel->fetchMaxRequestAmout( $request_opt )[ 'maxl' ];
        $maxReqQty = $this->Requestmodel->fetchMaxRequestQty( $request_opt )[ 'maxq' ];

        /* orders price range */
        $priceLimit = range(0, round( $maxLimit ), ceil( $maxLimit/10 ));
        $this->data['srequest_prange'] = array_combine(array_values($priceLimit), $priceLimit);

        /* orders qty range */
        $reqQtyLimit = range(0, round( $maxReqQty ), ceil( $maxReqQty/10 ));
        $this->data['srequest_qrange'] = array_combine(array_values($reqQtyLimit), $reqQtyLimit);

        /*
		$reqStatus = $this->Requestmodel->fetchDistinctStatus();
        $this->data['srequest_status'] =  com_makelist($reqStatus, 'status', 'status', TRUE, 'Select' );
        */
        $this->data['srequest_status'] =  $this->Requestmodel->reqStatus;
        $this->data[ 'srequest_status' ][ '' ] = 'Select';
        ksort( $this->data[ 'srequest_status' ] );
        /*        
        if ( $user_type == ADMIN ){
            $grp_users_select_opt = true;
            $groupId = com_arrIndex($form_data, 'search-order-grp', '0');
            $opt = [];
            $opt[ 'groupId' ] = $groupId;
            $opt[ 'user_type' ] = $user_type;
            $group_users  =  $this->Commonusermodel->getUsersListWithGrpID( $opt );
        } else if ( $user_type == CMP_ADMIN ){
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
		}  else 
		*/
		
		if ( $user_type == USER ){
            $group_users = [ ];
            $group_users[ 0 ][ 'uacc_id' ] = $this->flexi_auth->get_user_custom_data('uacc_id');
            $group_users[ 0 ][ 'uacc_username' ] = ucfirst($this->flexi_auth->get_user_custom_data('uacc_username'));
        }
        
        $this->data[ 'group_users' ]   = com_makelist( $group_users, 'uacc_id', 'uacc_username', $grp_users_select_opt, 'Select User' );
        
		$this->data['request_detail'] = $this->Requestmodel->fetchRequests( $fetchParam );
		
        $this->data['user_type'] = $user_type;
        $this->data['form_data']  = $form_data;

        // pagination configuration start
            $config['cur_page']       = $offset;
	        $config['total_rows']     = $this->Requestmodel->countAllRequests( $reqCountParam );	        
	        $config['html_container'] = 'request-view-div';
	        $config['base_url']       = 'request/ajax/request/approvals';
	        $config['per_page']       = $perpage;
	        $config['js_rebind']      = '';
	        $config['form_serialize'] = 'request-search-form';
	        $this->data['pagination'] =  com_ajax_pagination($config);            
        // pagination configuration end
                
        $output = [
                    'success' => 1,
                    'html' => $this->load->view('ajax/approval-listing', $this->data, TRUE),
        		];
        echo json_encode( $output );
        exit;
    }    
}    
