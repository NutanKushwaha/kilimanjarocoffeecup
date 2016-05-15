<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanycustomerModel extends Commonmodel{
	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){
        $this->data = array();

        $this->tbl_name = 'company_customer';
        $this->tbl_pk_col = 'id';		
		$this->tbl_cols['id'] = 'id';
		$this->tbl_cols['account_id'] = 'account_id';
		$this->tbl_cols['company_code'] = 'company_code';
		$this->tbl_cols['title'] = 'title';
		$this->tbl_cols['name'] = 'name';
		$this->tbl_cols['first_name'] = 'first_name';
		$this->tbl_cols['middle_name'] = 'middle_name';
		$this->tbl_cols['last_name'] = 'last_name';
		$this->tbl_cols['position'] = 'position';
		$this->tbl_cols['address'] = 'address';
		$this->tbl_cols['phone1'] = 'phone1';
		$this->tbl_cols['phone2'] = 'phone2';
		$this->tbl_cols['mobile'] = 'mobile';
		$this->tbl_cols['fax'] = 'fax';
		$this->tbl_cols['email'] = 'email';
		$this->tbl_cols['active'] = 'active';
	}
}