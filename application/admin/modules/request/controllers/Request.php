<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Request handle company users stock requests
 * 
 * @package         CodeIgniter
 * @subpackage      Request
 * @category        Controller
 * @author          Multi channel creative
 */
class Request extends Admin_Controller {

    private $reqStatus = [];
    function __construct() {
        parent::__construct();        
        $this->data = [];
        $this->load->model('Requestmodel');
    }

    private function requestStatus( $reqStatusId = 1){
      $reqStatusId = intval( $reqStatusId );
      $reqStatusId = $reqStatusId ? $reqStatusId : 1;      
      return $this->Requestmodel->reqStatus[ $reqStatusId ];

    }
    function addRequest( ){
        $param = [];
        $param[ 'user_type' ] = $this->user_type;
        $output = $this->Requestmodel->addOrder( $param );
        $this->session->set_flashdata('message', $output[ 'message' ]);
        redirect ("dashboard");
        exit;
    }

    function listing($offset = 0, $perpage = 10 ) {
		$user_type = $this->user_type;
        if (! $this->flexi_auth->is_privileged('View Request History') ){
        	$this->session->set_flashdata('message', '<p class="error_msg"> You do not have privileges to view request history.</p>'); 
        	redirect('dashboard');
        }
        $this->load->model( 'Commonusermodel' );

        $this->data[ 'group_users' ] = [ '' => 'Select User'];
        if( $user_type == CMP_ADMIN ) {
          $group_users  =  $this->Commonusermodel->getCompanyUsersList( );
          $this->data[ 'group_users' ]  = com_makelist( $group_users, 'uacc_id', 'uacc_username', true, 'Select User' );
        } else if ( $user_type == USER ){
            $this->data[ 'group_users' ]  = [ $this->flexi_auth->get_user_custom_data('uacc_id') => ucfirst( $this->flexi_auth->get_user_custom_data('uacc_username') ) ];
        }

        /**/
        $form_data = [];
        /* message to show if passed in session */
        $this->data['INFO'] = (! isset($inner['INFO'])) ? 
                                $this->session->flashdata('message') : $this->data['INFO'];

        $maxLimit = $this->Requestmodel->fetchMaxRequestAmout()[ 'maxl' ];
        $maxOrderQty = $this->Requestmodel->fetchMaxRequestQty()[ 'maxq' ];

        /* requests price range */
        $priceLimit = range(0, round( $maxLimit ), ceil( $maxLimit/10 ));
        $this->data['srequest_prange'] = array_combine(array_values($priceLimit), $priceLimit);

        /* requests qty range */
        $ordQtyLimit = range(0, round( $maxOrderQty ), ceil( $maxOrderQty/10 ));
        $this->data['srequest_qrange'] = array_combine(array_values($ordQtyLimit), $ordQtyLimit);
        
        /* User Group */
        $this->data[ 'user_type' ]  = $user_type;        

        /* Distinct status manually filled */
        //$reqStatus = $this->Requestmodel->fetchDistinctStatus();
        $this->data['srequest_status'] =  $this->Requestmodel->reqStatus;
        
        $fetchRequestParam = [  'user_type' => $user_type ,
                                'offset' => $offset, 
                                'perpage' => $perpage, 
                                'form_data' => $form_data
                            ];
        /* Fetch Request */
        $this->data['request_detail'] = $this->Requestmodel->fetchRequests( $fetchRequestParam );
        $reqCountParam = [  'user_type' => $user_type ,
                            'form_data' => $form_data];
        
        //pagination configuration
        $config['total_rows']     = $this->Requestmodel->countAllRequests( $reqCountParam );
        $config['html_container'] = 'request-view-div';
        $config['base_url']       = 'request/ajax/request/listing';
        $config['per_page']       = $perpage;
        $config['js_rebind']      = '';
        $config['form_serialize'] = 'request-search-form';
        $this->data['pagination'] =  com_ajax_pagination($config);
        $this->data['form_data'] = $form_data;
        $this->data['request_listing'] =  $this->load->view('ajax/request-listing', $this->data, TRUE);
        $this->data['content'] = $this->load->view('request-index', $this->data, true);
        $this->load->view($this->template['default'], $this->data);
    }

    function request_view( $reqNumber = null) {
     	$reqDetail = $this->Requestmodel->fetchReqDet( $reqNumber );                            
     	if( !$reqDetail || !sizeof($reqDetail)){
     		$this->utility->show404();
        return;
     	}
      $this->data[ 'user_type' ] = $this->user_type;
     	/* $orderDetail contains order all details address will repeat till record present */
      $this->data[ 'reqCommon' ] = $reqDetail[ 0 ];
      $this->data[ 'status' ] = $this->requestStatus( $this->data[ 'reqCommon' ][ 'status' ] );
     	$this->data[ 'reqDetail' ] = $reqDetail;

     	$this->data[ 'content' ] = $this->load->view('detail', $this->data, true);;
     	$this->load->view($this->template['default'], $this->data);	
    }    

    function approval_view( $reqNumber = null) {
     	$reqDetail = $this->Requestmodel->fetchReqDet( $reqNumber );                            
     	if( !$reqDetail || !sizeof($reqDetail)){
     		$this->utility->show404();
        return;
     	}
		$this->data[ 'user_type' ] = $this->user_type;
     	/* $orderDetail contains order all details address will repeat till record present */
		$this->data[ 'reqCommon' ] = $reqDetail[ 0 ];
		$req_status = $this->data[ 'reqCommon' ][ 'status' ];		
		$this->data[ 'status' ] = $this->requestStatus( $this->data[ 'reqCommon' ][ 'status' ] );		
		$this->data[ 'req_processed'] = $req_status > 1 ? true : false;
		$this->data[ 'status' ] = $this->requestStatus( $this->data[ 'reqCommon' ][ 'status' ] );
     	$this->data[ 'reqDetail' ] = $reqDetail;
		$this->data[ 'reqActions' ] = $this->Requestmodel->reqActions;
     	$this->data[ 'content' ] = $this->load->view('approval_detail', $this->data, true);;
     	$this->load->view($this->template['default'], $this->data);	
    }

    function approval_action( $reqNumber = null) {
     	$reqDetail = $this->Requestmodel->fetchReqDet( $reqNumber );                            
     	if( !$reqDetail || !sizeof($reqDetail)){
     		$this->utility->show404();
			return;
     	}     	
     	$this->data[ 'reqCommon' ] = $reqDetail[ 0 ];		
     	$req_status = $this->data[ 'reqCommon' ][ 'status' ];		
     	$this->data[ 'status' ] = $this->requestStatus( $this->data[ 'reqCommon' ][ 'status' ] );		
     	$this->data[ 'req_processed'] = $req_status > 1 ? true : false;
     	if( $this->data[ 'req_processed'] ){
			redirect('request/approvals');
		}
		if( $this->input->post( 'approval_action') ){
			$opts = [];
			$opts[ 'action' ] = $this->input->post( 'approval' );
			$opts[ 'store_id' ] = 0;
			$opts[ 'user_type' ] = $this->user_type;
			$opts[ 'quantity' ] = com_gParam( 'quantity', FALSE );;
			$opts[ 'reqNumber' ] = $reqNumber;
			$opts[ 'requestDetail' ] = $reqDetail;
			$this->Requestmodel->processRequest( $opts );
		}
		$this->data[ 'reqActions' ] = $this->Requestmodel->reqActions;
		
		$this->data[ 'user_type' ] = $this->user_type;
     	/* $orderDetail contains order all details address will repeat till record present */		
     	$this->data[ 'reqDetail' ] = $reqDetail;
     	$this->data[ 'content' ] = $this->load->view('approval_detail', $this->data, true);;
     	$this->load->view($this->template['default'], $this->data);	
    }
    
  /* Copied from order */
  private function issue( $orderNumber = null) {
        $orderDetail = $this->Requestmodel->fetchOrderDet( $orderNumber );
        if( !$orderDetail){
            $this->utility->show404();
            return;
        }
        $this->data[ 'orderShip' ] = $orderDetail[ 0 ];        
        if( $this->data[ 'orderShip' ][ 'is_stock_order' ] > 1){
            $order_stock_message = 'Stock order <b>'.$this->data[ 'orderShip' ][ 'order_num' ].'</b> has been issued.';
            if ( $this->data[ 'orderShip' ][ 'is_stock_order' ] == 3) {
                $order_stock_message = 'Stock order <b>'.$this->data[ 'orderShip' ][ 'order_num' ].'</b> has been cancelled.';
            }            
            $this->session->set_flashdata('message', $order_stock_message);
            redirect('order/history');
        }
        if( $this->input->post( 'issue-stock' ) ){
            $this->Requestmodel->issueOrder( $orderDetail );
            $this->session->set_flashdata('message', 'Stock issue to company');
            redirect('order/history');            
        }        
        $this->data[ 'user_type' ] = $this->user_type;
        /* $orderDetail contains order all details address will repeat till record present */        
        $this->data[ 'orderDetail' ] = $orderDetail;        
        $this->data[ 'content' ] = $this->load->view('stock-issue', $this->data, true);;
        $this->load->view($this->template['default'], $this->data); 
    }

  /* Copied from order */
  private function issued( $orderNumber = null) {
        $orderDetail = $this->Requestmodel->fetchOrderDet( $orderNumber );
        if( !$orderDetail){
            $this->utility->show404();
            return;
        }
        $allowedStockDetail = $this->Requestmodel->fetchAssignedOrderDet( $orderNumber );
        $this->data[ 'allowedStock' ] = com_makeArrIndexToField($allowedStockDetail, 'product_code');
        $this->data[ 'orderShip' ] = $orderDetail[ 0 ];
        $this->data[ 'user_type' ] = $this->user_type;
        /* $orderDetail contains order all details address will repeat till record present */
        $this->data[ 'orderDetail' ] = $orderDetail;
        $this->data[ 'content' ] = $this->load->view('stock-assigned', $this->data, true);;
        $this->load->view($this->template['default'], $this->data); 
  }

	function re_request( $orderNumber = null ){
        if ( $this->user_type == USER ){
    		$addToCart = TRUE;
    		$cartReorderRef = $this->session->cartReorderRef;		
    		$this->load->model('cart/Cartmodel', 'Cartmodel');
    		$orderDetail = $this->Requestmodel->fetchOrderDet( $orderNumber );
         	if( !$orderDetail ){
         		$this->utility->show404();
         	}
         	$cartMsg[ 'message' ] = 'Order already added';     	
         	if( $cartReorderRef && is_array($cartReorderRef) ){
         		if( in_array($orderNumber, array_keys( $cartReorderRef ) ) ){
         			$addToCart = FALSE;
         		}
         	}
         	if( $addToCart ){
    			$param = [];
         		$param[ 'orderDetail' ] = $orderDetail;
    			$reOrderStatus = $this->Cartmodel->reorderFromSavedOrder( $param );
                $cartMsg[ 'message' ] = $reOrderStatus[ 'message' ];
         	}
         	$addOrderStatus = $this->Requestmodel->addOrder();                    
            if( $addOrderStatus['status'] ){
                $cartMsg[ 'message' ] = $addOrderStatus[ 'message' ];
            }            
         	$this->session->set_flashdata('message', $cartMsg[ 'message' ]);
        }     	
     	redirect('order/history');
	}

  function user_bonus_stock_log( ) {    
      if (! $this->flexi_auth->is_privileged('Stock log')){
          $this->session->set_flashdata('message', 
                          '<p class="error_msg">You do not have privileges to distribute bonus stock.</p>'); 
          redirect('dashboard'); 
      }
      $this->data['per_page'] = 20;
      $this->load->model('company/Userstockmodel', 'Userstockmodel');
      $user_id = $this->flexi_auth->get_user_id();      
      $offset = com_gParam('offset', TRUE, 0);
      ///Setup pagination
      $options = [];
      $options[ 'user_id' ]       = $user_id;
      $config[ 'cur_page' ]       = $offset;
      $config['total_rows']       = $this->Userstockmodel->get_count( $options )[ 'ttl' ];      
      $config['html_container']   = 'bonus-log-view-div';
      $config['base_url']         = 'request/user_bonus_stock_log/';
      $config['per_page']         =  $this->data['per_page'];
      $config['js_rebind']        = '';
      $config['request_type']     = 'get';
      $this->data['pagination']   =  com_ajax_pagination($config);

      $options[ 'offset' ]    = $offset;
      $options[ 'limit' ]     = $this->data['per_page'];

      //Bonus list
      $bonus_log = [];
      $this->data[ 'bonus_stock' ] = $this->Userstockmodel->get_list( $options );      
      $this->data[ 'bonus_stock_list' ] = $this->load->view('request/ajax/bonus-stock-listing', $this->data, TRUE);
        
      if( $this->input->is_ajax_request() ){
        $output = [
              'success'   => 1,
              'html'    => $this->data[ 'bonus_stock_list' ],
          ];
        echo json_encode( $output );
        exit();
      }
      $this->data['content'] = $this->load->view('bonus-stock-index', $this->data, TRUE);
      $this->load->view($this->template['default'], $this->data);
    }
    
	function approvals($offset = 0, $perpage = 10 ) {
        if (! $this->flexi_auth->is_privileged('Approvals') ){
        	$this->session->set_flashdata('message', '<p class="error_msg"> You do not have privileges to view request approvals.</p>'); 
        	redirect('dashboard');
        }
        $user_type = $this->user_type;
        $this->load->model( 'Commonusermodel' );
        
        $this->data[ 'group_users' ] = [ '' => 'Select User'];
        $request_opt = [];
        $logged_id = '0';
        if ( in_array($user_type, [CMP_ADMIN, CMP_PM, CMP_MD ]) ) {
			$logged_id = $this->flexi_auth->get_user_custom_data('uacc_id');
			$request_opt[ 'where' ][ 'request_approval_for' ] = $logged_id;
			$opt = [];
			$opt[ 'select' ] = 'upro_uacc_fk, uacc_username as name ';
			$opt[ 'from_tbl' ] = 'user_profiles';
			$opt[ 'join' ][] = [ 	'tbl' => 'user_accounts', 
									'cond'=> 'uacc_id=upro_uacc_fk',
									'type'=> 'left',
								];
			$opt[ 'where' ][ 'upro_approval_acc' ] = $logged_id;
			$grp_users = $this->Commonusermodel->get_all( $opt );
			$this->data[ 'group_users' ]  = com_makelist($grp_users, 'upro_uacc_fk', 'name');
		} else if( $user_type == CMP_ADMIN ) {
          $group_users  =  $this->Commonusermodel->getCompanyUsersList( );
          $this->data[ 'group_users' ]  = com_makelist( $group_users, 'uacc_id', 'uacc_username', true, 'Select User' );
        } else if ( $user_type == USER ){
            $this->data[ 'group_users' ]  = [ $this->flexi_auth->get_user_custom_data('uacc_id') => ucfirst( $this->flexi_auth->get_user_custom_data('uacc_username') ) ];
        }

        /**/
        $form_data = [];
        /* message to show if passed in session */
        $this->data['INFO'] = (! isset($inner['INFO'])) ? 
                                $this->session->flashdata('message') : $this->data['INFO'];

        $maxLimit = $this->Requestmodel->fetchMaxRequestAmout( $request_opt )[ 'maxl' ];        
        $maxOrderQty = $this->Requestmodel->fetchMaxRequestQty( $request_opt )[ 'maxq' ];
       
        /* requests price range */
        $priceLimit = range(0, round( $maxLimit ), ceil( $maxLimit/10 ));
        $this->data['srequest_prange'] = array_combine(array_values($priceLimit), $priceLimit);

        /* requests qty range */
        $ordQtyLimit = range(0, round( $maxOrderQty ), ceil( $maxOrderQty/10 ));
        $this->data['srequest_qrange'] = array_combine(array_values($ordQtyLimit), $ordQtyLimit);
        
        /* User Group */
        $this->data[ 'user_type' ]  = $user_type;        

        /* Distinct status manually filled */
        //$reqStatus = $this->Requestmodel->fetchDistinctStatus();
        $this->data['srequest_status'] =  $this->Requestmodel->reqStatus;        
		$this->data[ 'srequest_status' ][ '' ] = 'Select';
		ksort( $this->data[ 'srequest_status' ] );
        $fetchRequestParam = [  'user_type' => $user_type ,
                                'offset' => $offset, 
                                'perpage' => $perpage, 
                                'form_data' => $form_data,
                                'approval_from' => $logged_id
                            ];
        /* Fetch Request */
        $this->data['request_detail'] = $this->Requestmodel->fetchRequests( $fetchRequestParam );
        
        $reqCountParam = [  'user_type' => $user_type ,
                            'form_data' => $form_data, 
                            'approval_from' => $logged_id
                            ];
        
        //pagination configuration
        $config['total_rows']     = $this->Requestmodel->countAllRequests( $reqCountParam );
        
        $config['html_container'] = 'request-view-div';
        $config['base_url']       = 'request/ajax/request/listing';
        $config['per_page']       = $perpage;
        $config['js_rebind']      = '';
        $config['form_serialize'] = 'request-search-form';
        $this->data['pagination'] =  com_ajax_pagination($config);
        $this->data['form_data'] = $form_data;
        $this->data['request_listing'] =  $this->load->view('ajax/approval-listing', $this->data, TRUE);
        $this->data['content'] = $this->load->view('approval-index', $this->data, true);
        $this->load->view($this->template['default'], $this->data);		
	}
}
