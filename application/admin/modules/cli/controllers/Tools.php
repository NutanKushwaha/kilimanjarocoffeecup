<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tools extends CI_Controller {
	

	function __construct(){
		# code...
        parent::__construct();        
        if( !$this->input->is_cli_request() ){
			die("Not accessible");
		}
	}
	
	public function message($to = 'World')
	{
		echo "Hello {$to}!".PHP_EOL;
	}

	public function carry_stock( ) {
		$this->load->model('Company/Companymodel', 'Companymodel');
		$this->load->model('Company/Compstoremodel', 'Companystoremodel');
		$companies = $this->Companymodel->listAll();
		$period_from 	=	date('Y-m-d 00:00:00', strtotime('01/01'));
		$period_to		= 	date('Y-m-d H:i:s', mktime(23, 59, 59, 12, 31, date('Y')));
		$carry_date_time = com_getDTFormat( 'mdatetime' );
		$carry_data = [];
		$carry_index = 0;		
		foreach($companies as $compStackId => $compDetail){
			$param = [];
			$param[ 'company_code'] = $compDetail[ 'company_code' ];
			$company_stores = com_makelist($this->Companystoremodel->get_list( $param ), 'id' , 'store_name', FALSE);						
			$company_stores[ 0 ] = 'Main';
			ksort( $company_stores );
			
			$company_store_stock = com_makeArrIndexToField(
													$this->Companymodel->getCompanyStoreStock( $param ),
													'product_code'
												);
			$stock_product_sku = array_keys( $company_store_stock );
			
			$company_store_user_issued_stock = com_makeArrIndexToField(
															$this->Companymodel->getCompanyStoreUserIssuedStock( $param ), 
															'product_code'
															);
			$issued_product_sku = array_keys( $company_store_user_issued_stock );
			
			$company_store_carry_forward = com_makeArrIndexToField( 
															$this->Companymodel->getLyearCompanyStoreStockCarryForward( $param ),
															'product_code'
														);
			$carried_product_sku = array_keys( $company_store_carry_forward );
		
			$company_stores_stock_exchange = $this->Companymodel->getCompanyStoreStockExchange( $param );
			if( $company_stores_stock_exchange && is_array( $company_stores_stock_exchange ) ){
				$keysComb = ['product_code', 'is_debit'];
				$company_stores_stock_exchange = com_makeArrIndexToArrayFieldComb( 
																$company_stores_stock_exchange,$keysComb, 
																TRUE);
			}
			$stock_exchange_keys = array_keys( $company_stores_stock_exchange );
			$combDecode = function($combKey) {
				$combination = explode(":", $combKey);
				return $combination[ '0' ];
			};		
			$exchange_product_sku = array_unique(array_map($combDecode, $stock_exchange_keys));
			
			$all_available_product_sku = array_unique( array_merge(	$stock_product_sku, 
																	$issued_product_sku, 
																	$carried_product_sku,
																	$exchange_product_sku) );

			foreach( $all_available_product_sku as $stackIndex => $skuDet ){
				$currrent_product = com_arrIndex($company_store_stock, $skuDet, []);
				$isStockAssignExist = com_arrIndex($company_store_user_issued_stock, $skuDet, 0);
				$issuedStock = [];
				if( $isStockAssignExist ){
					$issuedStock = $isStockAssignExist;
				}

				$isStockCarryFwdExist = com_arrIndex($company_store_carry_forward, $skuDet, 0);
				$cFwdStock = [];
				if( $isStockCarryFwdExist ){
					$cFwdStock = $isStockCarryFwdExist;
				}
				
				$company_store_product_debit = [];
				$company_store_product_credit = [];                                
				if( isset( $company_stores_stock_exchange[ $skuDet.":0" ] ) ){
					$company_store_product_credit = $company_stores_stock_exchange[ $skuDet.":0" ];
				}
				if( isset( $company_stores_stock_exchange[ $skuDet.":1" ] ) ){
					$company_store_product_debit  = $company_stores_stock_exchange[ $skuDet.":1" ];
				}
				
				foreach( $company_stores as $store_id => $store_name){
					$store_stock_exchange = 0;
					$store_debit_qty = com_arrIndex($company_store_product_debit, 'store_'.$stId, 0);
					$store_credit_qty = com_arrIndex($company_store_product_credit, 'store_'.$stId, 0);
					$store_stock_exchange = $store_credit_qty - $store_debit_qty;
					$sum_store_stock = com_arrIndex($currrent_product, 'store_'.$stId, 0 );
					$carry_fwd_stock = com_arrIndex($cFwdStock, 'store_'.$stId, 0 );
					$issued_store_stock = com_arrIndex($issuedStock, 'store_'.$stId, 0 );										
					$store_stock = $sum_store_stock + $carry_fwd_stock- $issued_store_stock + $store_stock_exchange;
					
					$carry_data[ $carry_index ][ 'period_to' ] 		= $period_to;
					$carry_data[ $carry_index ][ 'period_from' ] 	= $period_from;
					$carry_data[ $carry_index ][ 'carry_date_time'] = $carry_date_time;
					$carry_data[ $carry_index ][ 'company_code' ] 	= $compDetail[ 'company_code' ];
					$carry_data[ $carry_index ][ 'product_code' ] 	= $skuDet;
					$carry_data[ $carry_index ][ 'store_id' ] 		= $store_id;
					$carry_data[ $carry_index ][ 'stock_qty' ] 		= $store_stock;
					$carry_index++;
				}
			}
		}
		if( $carry_data ){			
			$this->db->insert('company_stock_carry_forward', $carry_data);
		}
	}
}
