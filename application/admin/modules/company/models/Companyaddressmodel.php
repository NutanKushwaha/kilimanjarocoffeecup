<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyaddressModel extends Commonmodel{
	
	function __construct()
	{
		# code...
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){
        $this->data = array();

        $this->tbl_name = 'company_address';
        $this->tbl_pk_col = 'id';		
		$this->tbl_cols['id'] = 'id';
		$this->tbl_cols['company_code'] = 'company_code';

		$this->tbl_cols['address_name'] = 'address_name';
		$this->tbl_cols['street'] = 'street';
		$this->tbl_cols['zip_code'] = 'zip_code';
		$this->tbl_cols['county'] = 'county';
		$this->tbl_cols['country'] = 'country';
		$this->tbl_cols['address_type'] = 'address_type';
		$this->tbl_cols['address_name2'] = 'address_name2';
		$this->tbl_cols['address_name3'] = 'address_name3';		
	}
}