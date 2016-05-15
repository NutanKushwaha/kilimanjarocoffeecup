<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class SpecialpricesModel extends Commonmodel{

	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){		
        $this->data = array();

        $this->tbl_name = 'product_special_price';
        $this->tbl_pk_col = 'id';		
		$this->tbl_cols['id'] = 'id';
		$this->tbl_cols['product_sku'] = 'product_sku';
		$this->tbl_cols['company_code'] = 'company_code';
		$this->tbl_cols['price_list'] = 'price_list';
		$this->tbl_cols['price'] = 'price';
		$this->tbl_cols['currency'] = 'currency';
		$this->tbl_cols['discount'] = 'discount';
	}

	function insertFromXML($data){
		$retVal = false;

		if( isset($data[ 'SpecialPrices' ]) ){
			if ( !isset($data[ 'SpecialPrices' ][0]) ) {
				$data_items = $data[ 'SpecialPrices' ];
				unset( $data[ 'SpecialPrices' ] );
				$data[ 'SpecialPrices' ] = [ '0' => $data_items];
			}
			$this->data = [];
			$tmp_del = [];			
			foreach( $data[ 'SpecialPrices' ] as $spIndex => $rowvalue ){
				if( isset( $rowvalue[ 'CardCode' ] ) &&  isset( $rowvalue[ 'ItemCode' ] ) 
				&& !empty( $rowvalue[ 'CardCode' ] ) &&  isset( $rowvalue[ 'ItemCode' ] ) ){
					$tmp = [];
					$tmp_del[] = [  $rowvalue['CardCode'],
									$rowvalue['ItemCode']
								 ];
					$tmp['company_code']= $rowvalue['CardCode'];
					$tmp['product_sku'] = $rowvalue['ItemCode'];
					$tmp['price_list'] 	= isset( $rowvalue['ListNum'] ) ? $rowvalue['ListNum'] : '0' ;
					$tmp['price'] 		= isset( $rowvalue['Price'] ) ? $rowvalue['Price'] : '0' ;
					$tmp['currency'] 	= isset( $rowvalue['Currency'] ) ? $rowvalue['Currency'] : '' ;
					$tmp['discount'] 	= isset( $rowvalue['Discount'] ) ? $rowvalue['Discount'] : '' ;
					$this->data[] = $tmp;
				}
			}			
			if($this->data){
				foreach ($tmp_del as $key => $del) {
					$param = [];
					$param['where']['company_code'] = $del['0'];
					$param['where']['product_sku'] = $del['1'];
					$this->delete_record($param);
				}
				$this->insert_bulk($this->data);
				$retVal = true;
			}
		}
		return $retVal;
	}
	
	function insertListNameFromXML( $data ){
		$retVal = false;

		if( isset($data[ 'PriceLists' ]) ){
			if ( !isset($data[ 'PriceLists' ][0]) ) {
				$data_items = $data[ 'PriceLists' ];
				unset( $data[ 'PriceLists' ] );
				$data[ 'PriceLists' ] = [ '0' => $data_items];
			}
			$this->data = [];
			$tmp_del = [];			
			foreach( $data[ 'PriceLists' ] as $spIndex => $rowvalue ){
				if( isset( $rowvalue[ 'ListNum' ] ) &&  isset( $rowvalue[ 'ListName' ] ) 
				&& !empty( $rowvalue[ 'ListNum' ] ) &&  isset( $rowvalue[ 'ListName' ] ) ){
					$tmp = [];
					$tmp_del[] = $rowvalue['ListNum'];					
					$tmp['list_num'] 	= isset( $rowvalue['ListNum'] ) ? $rowvalue['ListNum'] : '0' ;					
					$tmp['list_name'] 	= isset( $rowvalue['ListName'] ) ? $rowvalue['ListName'] : '' ;					
					$this->data[] = $tmp;
				}
			}			
			if($this->data){
				foreach ($tmp_del as $key => $del) {
					$param = [];					
					$param['where']['list_num'] = $del;					
					$this->db->where('list_num' , $del)
							->delete( 'product_special_price_list_name' );
				}
				$this->db->insert_batch('product_special_price_list_name', $this->data);
				$retVal = true;
			}
		}
		return $retVal;
	}
}
