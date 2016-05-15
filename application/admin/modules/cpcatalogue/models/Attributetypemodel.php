<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class AttributeTypeModel extends Commonmodel{
	
	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
    	$this->data = array();

        $this->tbl_name = 'attributes_type';
        $this->tbl_pk_col = 'id';

        $this->tbl_cols['id'] = 'id';
        $this->tbl_cols['type'] = 'type';
        $this->tbl_cols['active'] = 'active';
	}

	function list_all(){
        $condition = array();
        $condition['result'] = 'all';        
        $condition['where'] = array($this->tbl_cols['active'] => 1);
        return $this->get_all($condition);
	}
}