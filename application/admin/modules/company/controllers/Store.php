<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Store extends Admin_Controller {

    function __construct() {
        parent::__construct();        
        if (! $this->flexi_auth->is_privileged('Manage Store')) {
        		$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to Manage Companies store.</p>');
        		redirect('dashboard'); 
		}
        $this->data = [];
        $this->data['per_page'] = 10;
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
        $this->data['company_code'] = $company_code;
		$this->load->model('Compstoremodel');
    }

    function check_store_name($field_val, $other_param){
    	list($company_code, $edit_record) = explode('|', $other_param);
    	$options = 	[];    
    	$options[ 'company_code' ] 	= $this->data['company_code'];
		$options[ 'param' ][ 'where' ][ 'id != ' ]		=	$edit_record;
		$options[ 'param' ][ 'where' ][ 'store_name' ]	=	$field_val;    	
        if ($this->Compstoremodel->count_list( $options )[ 'ttl' ]) {
            $this->form_validation->set_message('check_store_name', 'Store name alreadt occupied!');
            return false;
        }
        return true;    	
    }

    // Display
	function index( ){
		$offset = com_gParam('offset', TRUE, 0);		
        ///Setup pagination
        $options = [];
        $options[ 'company_code' ] 	= $this->data['company_code'];
        $config[ 'cur_page' ] 		= $offset;
        $config['total_rows']     	= $this->Compstoremodel->count_list( $options )[ 'ttl' ];
        $config['html_container'] 	= 'store-view-div';
        $config['base_url']       	= 'company/store/index/';
        $config['per_page'] 		= $this->data['per_page'];
        $config['js_rebind']      	= '';
        $config['request_type']     = 'get';
        $this->data['pagination'] 	=  com_ajax_pagination($config);

        $options[ 'offset' ] 		= $offset;
        $options[ 'limit' ] 		= $this->data['per_page'];
        //Companies list
        $companies_store = [];
        $this->data[ 'companies_store' ] = $this->Compstoremodel->get_list( $options );
		$this->data[ 'comp_store_list' ] = $this->load->view('company/ajax/comp-store-listing', $this->data, TRUE);;
        
        if( $this->input->is_ajax_request() ){
        	$output = [
        				'success' 	=> 1,
    					'html'		=> $this->data[ 'comp_store_list' ],
        			];
			echo json_encode( $output );
			exit();
        }
        $this->data['content'] = $this->load->view('comp-store-index', $this->data, TRUE);

        $this->load->view($this->template['default'], $this->data);
    }

    // Add
    function add() {
        //Form Validations
        $check_store_name 	= $this->data['company_code'].'|0';
		$this->form_rules[] = ['field' => 'store_name', 'label' => 'Store name', 
							'rules' => 'trim|required|max_length[75]|callback_check_store_name['.$check_store_name.']'];
		$this->form_rules[] = ['field' => 'is_active', 'label' => 'Active', 
							'rules' => 'trim|required'];
		$opt = [];
		$opt[ 'company_code' ] = $this->data['company_code'];
		if($this->Compstoremodel->addStore( $opt )) {
			$this->session->set_flashdata('SUCCESS', 'store_added');
			redirect('company/store/', 'location');
		} else {
			$this->data['content'] = $this->load->view('comp-store-add', $this->data, TRUE);
		}
		$this->load->view($this->template['default'], $this->data);
    }

    // Edit
    function edit($store_id) {
        $opt = [];
        $opt[ 'store_id' ] = $store_id;
        $opt[ 'company_code' ] = $this->data['company_code'];
        $storeDetail = $this->Compstoremodel->getDetail( $opt );
        
        if (!$storeDetail) {
        	$opt = [];
        	$opt[ 'top_text' ] = 'Store not found';
        	$opt[ 'bottom_text' ] = 'Store does not exist';
            $this->utility->show404( $opt );
            return;
        }
        $check_store_name 	= $this->data['company_code'].'|'.$storeDetail['id'];
		$this->form_rules[] = ['field' => 'store_name', 'label' => 'Store name', 
							'rules' => 'trim|required|max_length[75]|callback_check_store_name['.$check_store_name.']'];
		$this->form_rules[] = ['field' => 'is_active', 'label' => 'Active', 
							'rules' => 'trim|required'];
		$opt = [];
		$opt[ 'company_code' ] 	= $this->data['company_code'];
		$opt[ 'store_id' ] 		= $storeDetail['id'];
		if($this->Compstoremodel->updateStore( $opt )) {
			$this->session->set_flashdata('SUCCESS', 'store_updated');
			redirect('company/store/', 'location');
		} else {
			$this->data['storeDetail'] = $storeDetail;
			$this->data['content'] = $this->load->view('comp-store-edit', $this->data, TRUE);
		}
		$this->load->view($this->template['default'], $this->data);
    }

    // Enable
    function enable($store_id = FALSE) {
        $opt = [];
        $opt[ 'store_id' ] = $store_id;
        $opt[ 'company_code' ] = $this->data['company_code'];
        $storeDetail = $this->Compstoremodel->getDetail( $opt );
        
        if (!$storeDetail) {
        	$opt = [];
        	$opt[ 'top_text' ] = 'Store not found';
        	$opt[ 'bottom_text' ] = 'Store does not exist';
            $this->utility->show404( $opt );
            return;
        }
        $this->Compstoremodel->enableRecord( $storeDetail );
        $this->session->set_flashdata('SUCCESS', 'store_updated');
        redirect('company/store/', 'location');
        exit();
    }

    // Disable
    function disable($store_id) {
        $opt = [];
        $opt[ 'store_id' ] = $store_id;
        $opt[ 'company_code' ] = $this->data['company_code'];
        $storeDetail = $this->Compstoremodel->getDetail( $opt );        
        if (!$storeDetail) {
        	$opt = [];
        	$opt[ 'top_text' ] = 'Store not found';
        	$opt[ 'bottom_text' ] = 'Store does not exist';
            $this->utility->show404( $opt );
            return;
        }
        $this->Compstoremodel->disableRecord( $storeDetail );
        $this->session->set_flashdata('SUCCESS', 'store_updated');
        redirect('company/store/', 'location');
        exit();
    }

	function stock_detail($store_id) {
        $opt = [];
        $opt[ 'store_id' ] = $store_id;
        $opt[ 'company_code' ] = $this->data['company_code'];
        $storeDetail = $this->Compstoremodel->getDetail( $opt );        
        if (!$storeDetail) {
        	$opt = [];
        	$opt[ 'top_text' ] = 'Store not found';
        	$opt[ 'bottom_text' ] = 'Store does not exist';
            $this->utility->show404( $opt );
            return;
        }                
        $this->data['storeDetail'] = $storeDetail;
        $this->load->model('Companymodel');
        $param = [];
        $param[ 'store_id'] = $storeDetail[ 'id' ];
        $param[ 'company_code'] = $this->data['company_code'];
        $this->data['company_store_stock'] = com_makeArrIndexToField(
												$this->Companymodel->getCompanyStock( $param ),
												'product_code'
											);
		$stock_product_sku = array_keys( $this->data['company_store_stock'] );
		
        $this->data['company_store_user_issued_stock'] = com_makeArrIndexToField(
														$this->Companymodel->getCompanyIssuedStock( $param ), 
														'product_code'
														);
		$issued_product_sku = array_keys( $this->data['company_store_user_issued_stock'] );		
		
		$this->data['company_store_carry_forward'] = com_makeArrIndexToField(
														$this->Companymodel->getLyearCompanySingleStoreStockCarryForward( $param ),
														'product_code'
													);		
		$carried_product_sku = array_keys( $this->data['company_store_carry_forward'] );	
		
		$this->data['company_stores_stock_exchange'] = $this->Companymodel->getCompanySingleStoreStockExchange( $param );
		
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
		$delete_stores = $this->input->post( 'delete_store' );
		if( $delete_stores == 'Delete Store' ){
			$store_stock_detail = [];
			foreach ($all_available_product_sku as $stockIndex => $stockSku ) {
				if( isset( $stockSku ) ){
					$currrent_product = com_arrIndex($this->data['company_store_stock'], $stockSku, []);					
					$isStockAssignExist = com_arrIndex($this->data['company_store_user_issued_stock'], $stockSku, 0);
					$issuedStock = [];
					if( $isStockAssignExist ){
						$issuedStock = $isStockAssignExist;
					}

					$isStockCarryFwdExist = com_arrIndex($this->data['company_store_carry_forward'], $stockSku, 0);
					$cFwdStock = [];
					if( $isStockCarryFwdExist ){
						$cFwdStock = $isStockCarryFwdExist;
					}
					$company_store_product_debit = 0;
					$company_store_product_credit = 0;
					if( isset( $this->data['company_stores_stock_exchange'][ $stockSku.":0" ] ) ){
						$company_store_product_credit = $this->data['company_stores_stock_exchange'][ $stockSku.":0" ][ 'ttl' ];
					}								
					if( isset( $this->data['company_stores_stock_exchange'][ $stockSku.":1" ] ) ){
						$company_store_product_debit  = $this->data['company_stores_stock_exchange'][ $stockSku.":1" ][ 'ttl' ];
					}
					$store_stock_exchange = 0;
					$store_debit_qty = intval( $company_store_product_debit );
					$store_credit_qty = intval( $company_store_product_credit );
					
					$store_stock_exchange = $store_credit_qty - $store_debit_qty;
					
					$sum_store_stock = com_arrIndex($currrent_product, 'ttl', 0 );
					$carry_fwd_stock = com_arrIndex($cFwdStock, 'ttl', 0 );
					$issued_store_stock = com_arrIndex($issuedStock, 'ttl', 0 );
					
					$simple = 'D-'.$store_debit_qty."<br/>".'C-'.$store_credit_qty."<br/>".'Ex-'.$store_stock_exchange
						."<br/>".'S-'.$sum_store_stock."<br/>".'Cf-'.$carry_fwd_stock."<br/>".'Iss-'.$issued_store_stock."<br/>";
					//echo $simple;
					$store_stock = $sum_store_stock + $carry_fwd_stock- $issued_store_stock
									+ $store_stock_exchange;
					$store_stock_detail[ $stockSku ] = $store_stock;
				}
			}
			$to_transfers_stores = $this->input->post( 'transfer_stores' );
			foreach( $store_stock_detail as $prod_sku => $stock ){
				if( $stock && isset( $to_transfers_stores[ $prod_sku ] ) ){
					$_POST = [];
					$_POST[ 'qty' ] = $stock;
					$_POST[ 'from_store' ] 	= $storeDetail[ 'id' ];
					$_POST[ 'to_store' ] 	= $to_transfers_stores[ $prod_sku ];
					$_POST[ 'product_code' ] = $prod_sku;
					$opt = [];
					$opt[ 'company_code' ] = $this->data['company_code'];
					$this->Companymodel->add_exchange_stock( $opt );
				}
			}
			$this->delete( $storeDetail );
		}
		$opt = [];
		$opt[ 'select' ] = 'product_sku, product_name, product_image';
		$opt[ 'from_tbl' ] = 'product';
		$opt[ 'where' ][ 'in_array' ][] = ['product_sku', $all_available_product_sku];
		$this->data[ 'all_product_detail' ] = $this->Companymodel->get_all( $opt );			
		$this->data['all_available_product_sku'] = $all_available_product_sku;		
		$this->load->model('company/Compstoremodel', 'Companystoremodel');
		
        $param = [];
        $param[ 'company_code'] = $this->data['company_code'];
		$this->data['company_stores'] = com_makelist($this->Companystoremodel->get_list( $param ),'id', 'store_name', '0');
		unset( $this->data['company_stores'][ $storeDetail[ 'id' ] ] );
		if( $storeDetail[ 'id' ] !== 0){
			$this->data['company_stores'][ 0 ] = 'Main';
			ksort( $this->data['company_stores'] );
		}
		$this->data['company_detail'] = $this->Companymodel->getCompanyDetailFromCompanyCode( $this->data['company_code'] );		
		//com_e($this->data, 0);
        $this->data[ 'content' ] = $this->load->view('company-store-stock', $this->data, true);
        $this->load->view($this->template['default'], $this->data);		
	}
	
    // Delete
    private function delete( $storeDetail ) {
        $this->Compstoremodel->deleteRecord( $storeDetail );
        $this->session->set_flashdata('SUCCESS', 'store_deleted');
        redirect('company/store/', 'location');
        exit();
    }

}
