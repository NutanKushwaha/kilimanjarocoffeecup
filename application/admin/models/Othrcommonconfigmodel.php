<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class OthrcommonconfigModel extends Commonmodel{
	
	function __construct()
	{
		# code...
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){
        $this->data = array();

        $this->tbl_name = 'other_common_config';
        $this->tbl_pk_col = 'id';
	}

	public function getDetailWithType($ref){
		$param = [];
		$param['select'] = 'config_id, config_label';
		$param['type_ref'] = $ref;
		return $this->get_all( $param );
	}
}