<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
  Attribute Set 

	CREATE TABLE `bg_department` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `name` varchar(255) NOT NULL DEFAULT '',
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1
*/
class DepartmentModel extends Commonmodel{
	
	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'department';
        $this->tbl_pk_col = 'id';

        $this->tbl_cols['id'] = 'id';
        $this->tbl_cols['name'] = 'name';
        $this->tbl_cols['company_code'] = 'company_code';
	}

	function add($rules = array()){
		$this->validation_rules = $rules;
		if($this->validate($rules)){
			$this->data['name'] = $this->input->post('name', true);
			$this->data['company_code'] = $this->flexi_auth->get_comp_admin_company_code();
			$new_set_id = $this->insert($this->data);
			return true;
		}else{		 	
			return false;
		}
	}

	function getCompanyDept( $comp_code = null ){
		$param = [];
		$param['where']['company_code'] = $comp_code ?  $comp_code : $this->flexi_auth->get_comp_admin_company_code();
		return $this->get_all($param);
	}
	
	function checkCompanyDept( $name ){
		$param = [];
		$param['result'] = 'num';
		$param['where']['name'] = $name;
		$param['where']['company_code'] = $this->flexi_auth->get_comp_admin_company_code();
		return $this->get_all($param);
	}	
}
