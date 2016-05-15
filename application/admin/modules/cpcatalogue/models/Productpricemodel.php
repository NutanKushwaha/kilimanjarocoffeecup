<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductpriceModel extends Commonmodel{

	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){		
        $this->data = array();

        $this->tbl_name = 'product_price_list';
        $this->tbl_pk_col = 'id';		
		$this->tbl_cols['id'] = 'id';
		$this->tbl_cols['product_sku'] = 'product_sku';
		$this->tbl_cols['price_list'] = 'price_list';
		$this->tbl_cols['price'] = 'price';
		$this->tbl_cols['currency'] = 'currency';
	}

	function insertFromXML($data){
		$retVal = true;
		if(isset($data['ItemPrice']) && is_array($data['ItemPrice'])){
			$data = $data['ItemPrice'];
			if($data){
				$this->data = [];
		 		$product_sku = array_column($data, 'ItemCode');
		 		if($product_sku){
			 		$param['where'] = ['in' => [ 'col' => $this->tbl_cols['product_sku'], 
			 									 'opt' => $product_sku]];
			 		$this->delete_record($param);
		 		}
		 		$price_list_xml_fld = ['ItemCode', 'ListNum', 'Currency', 'Price'];
		 		$price_list_xml_data_fld = [
		 								'ItemCode' => $this->tbl_cols['product_sku'], 
		 								'ListNum' => $this->tbl_cols['price_list'], 
		 								'Currency' => $this->tbl_cols['currency'], 
		 								'Price' => $this->tbl_cols['price'], 
		 								];
		 		foreach ($data as $key => $value) {
		 			$price_list_data_fld = [
		 							'product_sku'=> $value['ItemCode'],
									'price_list'=> $value['ListNum'],
									'price'=> $value['Price'],
									'currency'=> $value['Currency']
		 						];
					$this->data[] = $price_list_data_fld;
		 		}
		 		if($this->data){
		 			$this->insert_bulk($this->data);
		 			$retVal = true;
		 		}else{
		 			$retVal = false;
		 		}
			}else{
				$retVal = false;
			}
		}else{
			$retVal = false;
		}
		return $retVal;
	}
}
