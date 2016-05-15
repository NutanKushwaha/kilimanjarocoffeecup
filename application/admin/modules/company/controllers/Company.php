<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Company extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Companymodel');
        $this->data = [];
        $this->data['perpage'] = 20;
    }

    //validation for product image thumbnail
    function valid_images($str) {
        if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
            $this->form_validation->set_message('valid_images', 'The Category Image field is required.');
            return FALSE;
        }

        $imginfo = @getimagesize($_FILES['image']['tmp_name']);

        if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
            $this->form_validation->set_message('valid_images', 'Only GIF, JPG and PNG Images are accepted');
            return FALSE;
        }
        return TRUE;
    }

    //function for edit valid image
    function validImage($str) {
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

            $imginfo = @getimagesize($_FILES['image']['tmp_name']);
            if (!$imginfo) {
                $this->form_validation->set_message('validImage', 'Only image files are allowed');
                return false;
            }

            if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
                $this->form_validation->set_message('validImage', 'Only GIF, JPG and PNG Images are accepted.');
                return FALSE;
            }
        }
        return TRUE;
    }
    
    function index(){
        if (! $this->flexi_auth->is_privileged('View Companies')){ 
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Companies.</p>'); 
				redirect('dashboard'); 
		}

        ///Setup pagination
        $perpage = 20;
        $config['base_url'] = base_url() . "company/index/";
        $config['uri_segment'] = 3;
        $config['total_rows'] = 0;
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        //Companies list
        $companies = [];
        $companies = $this->Companymodel->listAll();
        

        //render view
        $inner = [];
        $inner['table_labels'] = [
						            'company_code' => 'Company Code',
						            'name' => 'Name',
						            'email_address' => 'Email',
						            'contact_person' => 'Contact Person',                                    
						            'action' => 'Action',
						        ];

        $inner['companies'] = $companies;
        $inner['pagination'] = $this->pagination->create_links();
        $inner['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $inner['INFO'];
        $page = [];
        $page['content'] = $this->load->view('company-list', $inner, TRUE);

        $this->load->view($this->template['default'], $page);
    }


    function addCompany(){
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('text');
        $this->load->library('email');
        $this->load->library('parser');
        if (! $this->flexi_auth->is_privileged('View Companies')){ 
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Companies.</p>'); 
                redirect('dashboard'); 
        }

        $this->form_rules[] = ['field' => 'uacc_password', 'label' => 'User Password', 
                                    'rules' => 'trim|required|min_length[5]|max_length[15]'];
        $this->form_rules[] = ['field' => 'uacc_username', 'label' => 'User Name', 
                                    'rules' => 'trim|required|alpha_numeric|is_unique[user_accounts.uacc_username]'];
        $this->form_rules[] = ['field' => 'uacc_email', 'label' => 'Email', 
                                    'rules' => 'trim|required|valid_email|max_length[75]|is_unique[user_accounts.uacc_email]'];
        $this->form_rules[] = ['field' => 'upro_first_name', 'label' => 'First Name', 
                                    'rules' => 'trim|max_length[50]|required'];
        $this->form_rules[] = ['field' => 'upro_last_name', 'label' => 'Last Name', 
                                    'rules' => 'trim|max_length[50]|required'];
        $this->form_rules[] = ['field' => 'company_code', 'label' => 'Company Name', 
                                    'rules' => 'trim|max_length[50]|required'];     
        $this->form_rules[] = ['field' => 'uadd_recipient', 'label' => 'Address Receipient ', 
                                    'rules' => 'trim|required|max_length[75]'];
        $this->form_rules[] = ['field' => 'uadd_phone', 'label' => 'Phone Number', 
                                    'rules' => 'trim|numeric'];
        $this->form_rules[] = ['field' => 'uadd_address_01', 'label' => 'Address 1', 
                                    'rules' => 'trim|max_length[50]'];
        $this->form_rules[] = ['field' => 'uadd_address_02', 'label' => 'Address 2', 
                                    'rules' => 'trim|max_length[50]'];
        $this->form_rules[] = ['field' => 'uadd_city', 'label' => 'City', 
                                    'rules' => 'trim|max_length[30]'];
        $this->form_rules[] = ['field' => 'uadd_county', 'label' => 'County', 
                                    'rules' => 'trim|max_length[30]'];
        $this->form_rules[] = ['field' => 'uadd_post_code', 'label' => 'Post Code', 
                                    'rules' => 'trim|max_length[10]'];
        $this->form_rules[] = ['field' => 'uadd_country', 'label' => 'Country', 
                                    'rules' => 'trim|max_length[30]'];
        if($this->Companymodel->insertCompany($_POST)) {
            // Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
            $this->session->set_flashdata('message', $this->flexi_auth->get_messages());            
            redirect('company');
        }else{
            $page['content'] = $this->load->view('company-add', '', TRUE);
            $this->load->view($this->template['default'], $page);
        }
    }

    function set_request_to_order_mark($company_id, $mark){
		$company = $this->Companymodel->getCompanyProfile($company_id, true);
        if( !$company || !sizeof($company)){
            $opt = [];
            $opt[ 'top_text' ] = 'Company not exist';
            $opt[ 'bottom_text' ] = 'Company does not exist';
            $this->utility->show404( $opt );
            return;
        }        
        if( in_array($mark, [ 'active', 'deactive' ]) ){
			$company_id = $company['id'];			
			$this->Companymodel->updateCompanyRequestOrderMark($company_id, $mark);
			$this->session->set_flashdata('message',
                    '<p class="error_msg">Company flag request to order updated successfully.</p>');
		} else {
			$this->session->set_flashdata('message',
                    '<p class="error_msg">Invalid option is supplied.</p>');             
		}
		redirect('company');
    }

    function view($company_id){
		$inner = [];
    	$inner['comp_detail'] = $this->Companymodel->getCompanyProfile($company_id);
        if (!$inner['comp_detail']) {
			$opt = [];
			$opt[ 'top_text' ] = 'Company not exist';
			$opt[ 'bottom_text' ] = 'Company does not exist';
            $this->utility->show404( $opt );
            return;
        }
        $comp_logo = "";
		if( isset( $inner['comp_detail'][ 'company_logo' ] ) && !empty( $inner['comp_detail'][ 'company_logo' ] ) ){
			$comp_logo = $this->config->item('COMPANY_LOGO_RESIZED_IMAGE_URL').'50_50/'.$inner['comp_detail'][ 'company_logo' ];
		}
		$inner[ 'comp_logo' ] = $comp_logo;
        $inner['INFO'] = (!isset($inner['INFO'])) ?
                $this->session->flashdata('message') : $inner['INFO'];		        		
        $page = [];
        $page['content'] = $this->load->view('company-profile', $inner, TRUE);

        $this->load->view($this->template['default'], $page);
    }
    
	function activate_email( $company_id ){
		$inner = [];
    	$inner['comp_detail'] = $this->Companymodel->getCompanyProfile($company_id);
    	$account_id = $inner['comp_detail'][ 'account_id' ];    	
        if (!$inner['comp_detail'] || !$account_id ) {
			$opt = [];
			$opt[ 'top_text' ] = 'Company not exist';
			$opt[ 'bottom_text' ] = 'Company does not exist';
            $this->utility->show404( $opt );
            return;
        }
		$company_profile = $this->flexi_auth->get_user_by_id( $account_id )->row_array();		
		$this->flexi_auth->resend_activation_token( $company_profile[ 'uacc_username' ] );
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		redirect('company');
	}    
    /*
        Part of first flow , in which admin create department        
    */ 
    private function dept_allocation($company_id){

        $company = $this->Companymodel->getCompanyProfile($company_id);
        if (!$company) {
            $this->utility->show404();
            return;
        }

        $this->load->model('Departmentmodel');
        $this->load->model('Relatedcompdeptmodel');

        if($this->input->post('policy-submit')){
            $this->Relatedcompdeptmodel->insert_policy();
            $this->session->set_flashdata('SUCCESS', 'company_policy_updated');
            redirect('company/dept_allocation/'.$company['details']['id']);
        }

        $this->data['company'] = $company;
        $this->data['departments'] = $this->Departmentmodel->get_all();
        $this->data['comp_detail'] = $this->Companymodel->getCompanyProfile($company_id);
        $this->data['policy'] = $this->Relatedcompdeptmodel->getDeptList($company['details']['company_code']);

        $policy = [];
        foreach ($this->data['policy'] as $key => $value) {
            $policy[$value['department_id']] = $value;
        }
        $this->data['company_policy'] = $policy;
        $this->data['offset'] = $this->Companymodel->getOffsetIndex($company_id)['offset'];
        
        $this->data['content'] = $this->load->view('company-dept-allocation', $this->data, TRUE);
        $this->load->view($this->template['default'], $this->data);
    }

    /*
        company Department , no in use
     */
    private  function department( ){
        if (! $this->flexi_auth->is_privileged('view company department')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Related Assigned Department.</p>'); 
            redirect('dashboard'); 
        }
        
        $this->load->model('company/Relatedcompdeptmodel', 'relatedmodel');

        $company_code = $this->flexi_auth->get_comp_admin_company_code();

        $inner = [];
        $inner['depts'] = com_makelist( $this->relatedmodel->getDeptList( $company_code), 'id', 'name', false);        
        
        $page['content'] = $this->load->view('company-dept', $inner, TRUE);
        $this->load->view($this->template['default'], $page);        
    }    

    function company_stock( $company_code = null ){
        if (! $this->flexi_auth->is_privileged('view company stock')){ 
            $this->session->set_flashdata('message', 
                    '<p class="error_msg">You do not have privileges to view company stock.</p>'); 
            redirect('dashboard'); 
        }
		$this->data['INFO'] = (!isset( $this->data['INFO'] )) ?
                $this->session->flashdata('message') : $this->data['INFO'];
        if( is_null( $company_code ) ){
			if( $this->user_type == CMP_ADMIN ){
				$company_code = $this->flexi_auth->get_comp_admin_company_code();
			} else {
				$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			}
            
        }
        $this->load->model('company/Compstoremodel', 'Companystoremodel');
        $param = [];
        $param[ 'company_code'] = $company_code;
        $this->data['company_store_stock'] = com_makeArrIndexToField(
												$this->Companymodel->getCompanyStoreStock( $param ),
												'product_code'
											);
		$stock_product_sku = array_keys( $this->data['company_store_stock'] );
		
        $this->data['company_store_user_issued_stock'] = com_makeArrIndexToField(
														$this->Companymodel->getCompanyStoreUserIssuedStock( $param ), 
														'product_code'
														);
		$issued_product_sku = array_keys( $this->data['company_store_user_issued_stock'] );
		
		$this->data['company_store_carry_forward'] = com_makeArrIndexToField(
														$this->Companymodel->getLyearCompanyStoreStockCarryForward( $param ),
														'product_code'
													);
		$carried_product_sku = array_keys( $this->data['company_store_carry_forward'] );
		
		$this->data['company_stores_stock_exchange'] = $this->Companymodel->getCompanyStoreStockExchange( $param );
		if( $this->data['company_stores_stock_exchange'] && is_array( $this->data['company_stores_stock_exchange'] ) ){
			$keysComb = ['product_code', 'is_debit'];
			$this->data['company_stores_stock_exchange'] = com_makeArrIndexToArrayFieldComb( 
															$this->data['company_stores_stock_exchange'],$keysComb, 
															TRUE);
		}
		$stock_exchange_keys = array_keys( $this->data['company_stores_stock_exchange'] );
		$combDecode = function($combKey) {
			$combination = explode(":", $combKey);
			return $combination[ '0' ];
		};
		$exchange_product_sku = array_unique(array_map($combDecode, $stock_exchange_keys));
		$all_available_product_sku = array_unique( array_merge(	$stock_product_sku, 
																$issued_product_sku, 
																$carried_product_sku,
																$exchange_product_sku) );
		$prod_det = [];
		if( $all_available_product_sku && is_array( $all_available_product_sku ) ){
			$opt = [];
			$opt[ 'select' ] = 'product_sku, product_name, product_image';
			$opt[ 'from_tbl' ] = 'product';
			$opt[ 'where' ][ 'in_array' ][] = ['product_sku', $all_available_product_sku];
			$prod_det = $this->Companymodel->get_all( $opt );
		}
		$this->data[ 'all_product_detail' ] = $prod_det;
		$this->data['all_available_product_sku'] = $all_available_product_sku;
        $this->data['company_stores'] = $this->Companystoremodel->get_list( $param );
        $this->data['company_detail'] = $this->Companymodel->getCompanyDetailFromCompanyCode( $company_code );
        $this->data[ 'content' ] = $this->load->view('company-stock', $this->data, true);
        $this->load->view($this->template['without_menu'], $this->data);
    }
	
    function stock_request( $company_code = null ){
        $this->data['form_data'] = [];
        parse_str($this->input->post("form_data"), $form_data);
        
        if (! $this->flexi_auth->is_privileged('view stock request')){
            $this->session->set_flashdata('message', 
                    '<p class="error_msg">You do not have privileges to view stock request.</p>'); 
            redirect('dashboard'); 
        }
        if( is_null( $company_code ) ){         
			$company_code = "";
			if( $this->user_type == CMP_ADMIN ){
				$company_code = $this->flexi_auth->get_comp_admin_company_code();
			} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
				$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			}        
        }
        $this->load->model('request/Requestmodel', 'Requestmodel');
        $offset     =   com_gParam( 'offset', false, 0);
        $perpage    =   10;
        $reqParam   = [];
        $reqParam[ 'order_to'] = 2;
        $reqParam[ 'user_type' ] = $this->user_type;
        $reqParam[ 'form_data' ] = $form_data;
        $reqParam[ 'company_code'] = $company_code;
        
        $maxLimit = $this->Requestmodel->fetchMaxRequestAmout( $reqParam )[ 'maxl' ];
        $maxOrderQty = $this->Requestmodel->fetchMaxRequestQty( $reqParam )[ 'maxq' ];        
        
        //$srequestStatus = $this->Requestmodel->fetchDistinctStatus( $reqParam );
        $this->data['srequest_status'] =  $this->Requestmodel->reqStatus;
        $this->data['srequest_status'][ 0 ] = 'All';
        ksort( $this->data['srequest_status'] );
        /* stock request price range */
        $priceLimit = range(0, round( $maxLimit ), ceil( $maxLimit/10 ));
        $this->data['srequest_prange'] = array_combine(array_values($priceLimit), $priceLimit);

        /* stock request qty range */
        $ordQtyLimit = range(0, round( $maxOrderQty ), ceil( $maxOrderQty/10 ));
        $this->data['srequest_qrange'] = array_combine(array_values($ordQtyLimit), $ordQtyLimit);

        $this->load->model( 'Commonusermodel' );
        $group_users    =  $this->Commonusermodel->getCompanyUsersList( );
        $this->data[ 'group_users' ]    = com_makelist( $group_users, 'uacc_id', 'uacc_username', true, 'Select User' );
        $request_counts = $this->Requestmodel->countAllRequests( $reqParam );
        $reqParam[ 'offset' ] = $offset;        
        $reqParam[ 'perpage' ] = $perpage;
        $this->data['request_detail'] = $this->Requestmodel->fetchRequests( $reqParam );
        $this->data['form_data'] = $form_data;
        //pagination configuration
        $config['cur_page']     = $offset;
        $config['total_rows']     = $request_counts;
        $config['html_container'] = 'request-view-div';
        $config['base_url']       = 'company/stock_request';
        $config['per_page']       = $perpage;
        $config['js_rebind']      = '';
        $config['form_serialize'] = 'company-stock-request';        
        $this->data['pagination'] =  com_ajax_pagination($config);
        //com_e( $this->data['pagination'] );
        $this->data['request_listing'] =  $this->load->view('ajax/request-listing', $this->data, TRUE);

        if(  $this->input->is_ajax_request() ){
            $output = [];
            $output[ 'success' ] = TRUE;
            $output[ 'html' ] = $this->data['request_listing'];
            echo json_encode(  $output );
            exit();
        }
        $this->data[ 'content' ] = $this->load->view('company-request', $this->data, true);
        $this->load->view($this->template['default'], $this->data);
        /*
            $this->data['form_data'] = $form_data;    
            $this->data['orders_history_html'] =  $this->load->view('ajax/orders-history-table', $this->data, TRUE);
            $this->data['content'] = $this->load->view('history', $this->data, true);        
            $this->load->view($this->template['without_menu'], $this->data);
        */
    }

    function request_view( $reqNumber = null ){
        $this->load->model('request/Requestmodel', 'Requestmodel');
        $reqDetail = $this->Requestmodel->fetchReqDet( $reqNumber );
        if( !$reqDetail || !sizeof($reqDetail)){
            $opt = [];
            $opt[ 'top_text' ] = 'Request not exist';
            $opt[ 'bottom_text' ] = 'Request does not exist';
            $this->utility->show404( $opt );
            return;
        }
        $processedStatus = '';
        if( $reqDetail[ 0 ][ 'status' ] > 1 ){
            $processedStatus = $this->Requestmodel->reqStatus[ $reqDetail[ 0 ][ 'status' ] ];
        }
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
        $this->load->model('company/Compstoremodel', 'Companystoremodel');
        $param = [];
        $param[ 'company_code'] = $company_code;
        $this->data['company_stores'] = $this->Companystoremodel->get_list( $param );
        $this->data[ 'req_num' ] = $reqNumber;
        $this->data[ 'user_type' ] = $this->user_type;
        $this->data[ 'processedStatus' ] = $processedStatus;
        /* $orderDetail contains order all details address will repeat till record present */        
        $this->data[ 'reqDetail' ] = $reqDetail;        
        $this->data[ 'customer_detail' ] = $this->flexi_auth->get_user_by_id( $this->data[ 'reqDetail' ][ 0 ][ 'customer_id' ] )->row_array();
        $this->data[ 'content' ] = $this->load->view('company-view-request', $this->data, true);;
        $this->load->view($this->template['default'], $this->data);         
    }

    function stock_request_issue ( $reqNumber = null, $store_id = null ){
        $this->load->model('request/Requestmodel', 'Requestmodel');
        $requestDetail = $this->Requestmodel->fetchReqDet( $reqNumber );
        if( !$requestDetail || !sizeof($requestDetail)){
            $opt = [];
            $opt[ 'top_text' ] = 'Request not exist';
            $opt[ 'bottom_text' ] = 'Request does not exist';
            $this->utility->show404( $opt );
            return;
        }
        $this->load->model('company/Compstoremodel', 'Companystoremodel');
		$company_code = $this->flexi_auth->get_comp_admin_company_code();
		$param = [];
		$param[ 'store_id' ] = intval( $store_id );
		$param[ 'company_code' ] = $company_code;
		$store_details = $this->Companystoremodel->getDetail( $param );
        if( !$store_details || !sizeof($store_details)){
            $store_details[ 'id' ] = 0;
            $store_details[ 'store_name' ] = 'main store';
        }
		$this->data[ 'company_stores' ] = $this->Companystoremodel->get_list( $param );
        $requested_product_sku = [];
        foreach ($requestDetail as $reqInd => $reqDet) {
            $requestOptions = json_decode( $reqDet[ 'req_item_options' ] , true );
            $reqSku  = com_arrIndex( $requestOptions[ 'product' ] , 'product_sku' , ''); 
            if( $reqSku ){
                $requested_product_sku[] = $reqSku;
            }            
        }        
        $param = [];
        $param[ 'from_tbl' ]    = 'company_stock';
        $param[ 'select' ]      = 'sum(stock_qty) as ttl, product_code';
        $param[ 'where' ][ 'in_array' ][ ] = ['product_code' ,$requested_product_sku];
        $param[ 'where' ][ 'company_code' ] = $company_code;
        $param[ 'where' ][ 'store_id' ] = $store_details[ 'id' ];
        $param[ 'group' ] = 'product_code';
        $prod_in_stock = $this->Requestmodel->get_all( $param );        
        $this->data[ 'prod_in_stock' ] = com_makelist( $prod_in_stock, 'product_code', 'ttl', FALSE);
        $this->data[ 'reqShip' ] = $requestDetail[ 0 ];
        $this->data[ 'customer_detail' ] = $this->flexi_auth->get_user_by_id( $this->data[ 'reqShip' ][ 'customer_id' ] )->row_array();
        /*
        if( $this->data[ 'orderShip' ][ 'is_stock_order' ] > 1){
            $order_stock_message = 'Stock order <b>'.$this->data[ 'orderShip' ][ 'order_num' ].'</b> has been issued.';
            if ( $this->data[ 'orderShip' ][ 'is_stock_order' ] == 3) {
                $order_stock_message = 'Stock order <b>'.$this->data[ 'orderShip' ][ 'order_num' ].'</b> has been cancelled.';
            }            
            $this->session->set_flashdata('message', $order_stock_message);
            redirect('company/stock_request');
        }
        if( $this->input->post( 'issue-stock' ) ){
            /*
                $this->Ordermodel->issueOrder( $orderDetail );
            */
            /*
            $this->session->set_flashdata('message', 'Stock issue to user pending');
            redirect('order/history');            
        }
        */
        $this->data[ 'store_details' ] = $store_details;
        $this->data[ 'stock_request_actions' ] = $this->Requestmodel->reqActions;
        $this->data[ 'stock_request_action_taken' ] = $this->Requestmodel->reqStatus;
        $this->data[ 'user_type' ] = $this->user_type;
        /* $orderDetail contains order all details address will repeat till record present */        
        $this->data[ 'requestDetail' ] = $requestDetail;
        $this->data[ 'reqNumber'] = $reqNumber;        
        $this->data[ 'content' ] = $this->load->view('comp-stock-request-allocation', $this->data, true);
        $this->load->view($this->template['default'], $this->data);
    }

    function stock_request_taction (  ){		
		$store_id = com_gParam( 'store_id', FALSE );
        $action = com_gParam( 'req_action', FALSE );
        $reqNumber = com_gParam( 'req_number', FALSE );
        $this->load->model('request/Requestmodel', 'Requestmodel');        
        $requestDetail = $this->Requestmodel->fetchReqDet( $reqNumber );        
        if( !$requestDetail || !sizeof($requestDetail) || !$action){
            $this->utility->show404();
            return;
        }
        if( $requestDetail[ 0 ][ 'status' ] > 1 ){
            $actionMsg = 'Action already has been taken on this request.';
        } else {
            $opts = [];
            $opts[ 'action' ] = $action;            
            $opts[ 'store_id' ] = $store_id;
            $opts[ 'user_type' ] = $this->user_type;
            $opts[ 'quantity' ] = com_gParam( 'quantity', FALSE );;
            $opts[ 'reqNumber' ] = $reqNumber;
            $opts[ 'requestDetail' ] = $requestDetail;
            $this->Requestmodel->processRequest( $opts );
            $actionMsg = '';
            if( $action == 2){
                $actionMsg = 'Request changed to order successfully.';
            } else if( $action == 3){
                $actionMsg = 'Request changed to order successfully and stock issued.';
            } else if( $action == 4){
                $actionMsg = 'Available stock issued successfully and left cancelled against request.';
            } else if( $action == 3){
                $actionMsg = 'Request cancelled successfully.';
            }
        }
        $this->session->set_flashdata('message', $actionMsg); 
        redirect('dashboard');
    }

    function stock_product_allot( $product_code = NULL ){
        $this->load->model( 'company/Userstockmodel', 'Userstockmodel' );
        $this->load->model( 'cpcatalogue/Productmodel', 'Productmodel' );
        $company_code = $this->flexi_auth->get_comp_admin_company_code();
        $product_detail = $this->Productmodel->details( $product_code );
        if( !$product_detail ){
            $opt = [];
            $opt[ 'top_text' ] = 'Product not exist';
            $opt[ 'bottom_text' ] = 'Product does not exist';
            $this->utility->show404( $opt );
            return ;
        }
        $param = [];
        $param[ 'product_code' ] = $product_code;
        $param[ 'company_code' ] = $company_code;
        $this->data[ 'logDet' ] = $this->Userstockmodel->getProdIssueLog( $param );        
        $this->data[ 'company_code' ]   = $company_code;
        $this->data[ 'product_det'  ]   = $product_detail;
        $this->data[ 'content' ] = $this->load->view('comp-stock-product-allocation-log', $this->data, true);
        $this->load->view($this->template['default'], $this->data);
    }
    
    function store_stock_product_exchange( ){        		
		$store_id = $this->input->post('store', true);
		com_changeNull($store_id, 0);
		$product_code = $this->input->post('product_code', true);
		$company_code = $this->flexi_auth->get_comp_admin_company_code();		
		if( $this->input->post( 'stock-exchange' ) ){
			$opt = [];
			$opt[ 'company_code' ] = $company_code;
			$this->Companymodel->add_exchange_stock( $opt );
			$this->session->set_flashdata('message', "Stock exchange done successfully."); 
			redirect( 'company/company_stock' , 'location');
		}
		$this->load->model('company/Compstoremodel', 'Companystoremodel');
		$opt = [];
		$opt[ 'result' ] = 'row';
		$opt[ 'store_id' ] = $store_id;
		$opt[ 'company_code'] = $company_code;
		$this->data['company_stores'] = com_makelist($this->Companystoremodel->get_list( $opt ),
													'id', 'store_name', '0');
		unset( $this->data['company_stores'][ $store_id ] );
		if( $store_id !== 0){
			$this->data['company_stores'][ 0 ] = 'Main';
			ksort( $this->data['company_stores'] );
		}
		$this->data[ 'store_found' ] = 1;
		$store_details = $this->Companystoremodel->getDetail( $opt );
        if( (!$store_details || !sizeof($store_details)) ){
			if( $store_id !== 0 ){
				$this->data[ 'store_found' ] = 0;
			}
			$opt[ 'store_id' ] = 0;			
            $store_details[ 'id' ] = 0;
            $store_details[ 'store_name' ] = 'main store';
        }
        $this->data[ 'store_details' ] = $store_details;
		$opt[ 'product_sku' ] = [ $product_code ];
		$this->data[ 'product_code' ] = $product_code;
		$this->data[ 'company_stock' ] = $this->Companymodel->getCompanyStock( $opt );
		$this->data[ 'company_issued_stock' ] = $this->Companymodel->getCompanyIssuedStock( $opt );
		$this->data[ 'company_carry_fwd_stock' ] = $this->Companymodel->getLyearCompanySingleStoreStockCarryForward( $opt );
		$opt[ 'result' ] = 'result';
		$this->data['company_stores_stock_exchange'] = com_makelist(
															$this->Companymodel->getCompanySingleStoreStockExchange( $opt ),
															'is_debit', 'ttl', FALSE
														);		
		$stock_exchange = 0;
		if( $this->data['company_stores_stock_exchange'] && is_array( $this->data['company_stores_stock_exchange'] ) ){
			 $stock_exchange = com_arrIndex( $this->data['company_stores_stock_exchange'], '0', 0)
							-	com_arrIndex( $this->data['company_stores_stock_exchange'], '1', 0);
		}		
		
		$this->data[ 'stock_available' ] = com_arrIndex($this->data[ 'company_stock' ], 'ttl', 0) 
										+ $stock_exchange
										+ com_arrIndex($this->data[ 'company_carry_fwd_stock' ], 'ttl', 0) 
										- com_arrIndex($this->data[ 'company_issued_stock' ], 'ttl', 0);											
		if( $this->input->is_ajax_request() ){
			$output = [];
			$output[ 'success' ] = '1';
			$output[ 'html' ] = $this->load->view( 'ajax/exchange-product-stock-form', $this->data, true ); 
			echo json_encode( $output );
			exit;
		}
	}
	
	function logo_add( ){
		$company_id = $this->input->post( 'company_id' );
		$comp_detail = $this->Companymodel->getCompanyProfile($company_id);
		$redirect_loc = 'company';
        if ( !$comp_detail ) {
			$this->session->set_flashdata('message', "Company could not found");             
        } else {
			$redirect = 'company/view/'.$comp_detail[ 'id' ];
			$this->form_validation->set_rules('company_id', 'Company Ref', 'trim|required|callback_valid_images');
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('message', validation_errors());
			} else {
				$img_load = $this->Companymodel->company_add_logo( $comp_detail );
				$this->session->set_flashdata('message', $img_load[ 'msg' ]);
			}
		}
        redirect($redirect_loc, 'location');
        exit();
	}
	
	function logo_delete( ){
		$inner['comp_detail'] = $this->Companymodel->getCompanyProfile($company_id);
        //get image details
        $image = array();
        $image = $this->Imagesmodel->getDetails($image_id);
        if (!$image) {
            $this->utility->show404();
            return;
        }

        $this->Imagesmodel->deleteImage($image);
        $this->session->set_flashdata('SUCCESS', 'image_deleted');
        redirect("cpcatalogue/product_images/index/{$image['product_id']}");
        exit();		
	}
	
	function update_config(){
		$company_id = $this->input->post( 'company_id' );
		$comp_detail = $this->Companymodel->getCompanyProfile($company_id);
		$redirect_loc = 'company';
        if ( !$comp_detail ) {
			$this->session->set_flashdata('message', "Company could not found");             
        } else {
			$redirect = 'company/view/'.$comp_detail[ 'id' ];
			
			$this->form_validation->set_rules('cat_suffix', 'Category suffix', 'trim|max_length[10]');
			$this->form_validation->set_rules('menu-base-color', 'Menu base color', 'trim|required');
			$this->form_validation->set_rules('menu-hover-color', 'Menu Hover color', 'trim|required');
			$this->form_validation->set_rules('multi_kit', 'Kit ', 'trim|required');
			$this->form_validation->set_rules('manage_stock', 'Stock management ', 'trim|required');
			$this->form_validation->set_rules('skip_policy', 'Skip Policy ', 'trim|required');
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('message', validation_errors());
			} else {
				$img_load = $this->Companymodel->company_update_config( $comp_detail );
				$this->session->set_flashdata('message', $img_load[ 'msg' ]);
			}
		}
        redirect($redirect_loc, 'location');
        exit();
	}
}
