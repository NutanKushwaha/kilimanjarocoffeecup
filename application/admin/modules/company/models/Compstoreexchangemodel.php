<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class CompstoreexchangeModel extends Commonmodel{
	
	private $data;	
	function __construct()
	{		
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){
        $this->data = array();
        $this->tbl_name = 'company_store_stock_exchange';
        $this->tbl_pk_col = 'id';
	}
}
