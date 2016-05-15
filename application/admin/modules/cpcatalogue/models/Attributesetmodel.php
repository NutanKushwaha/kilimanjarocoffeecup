<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
  Attribute Set 

	CREATE TABLE `bg_attributes_set` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `set_name` varchar(255) NOT NULL DEFAULT '',
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1
*/
class AttributesetModel extends Commonmodel{
	
	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'attributes_set';
        $this->tbl_pk_col = 'id';

        $this->tbl_cols['id'] = 'id';
        $this->tbl_cols['set_name'] = 'set_name';
	}

	function add($rules = array()){
		$this->validation_rules = $rules;
		if($this->validate($rules)){
			$this->data['set_name'] = $this->input->post('set_name', true);
			$new_set_id = $this->insert($this->data);
			$this->data = array();

			$source_set = $this->input->post('source_name', true);
			if($source_set){

				$this->load->model('AttributeModel');				
				$condition = array();
				$condition['where'] = array('set_id' => $source_set);
				$attribute_arr_lst = $this->AttributeModel->get_all($condition);
				if(count($attribute_arr_lst)){
					foreach($attribute_arr_lst as $attr_prop){
						$current_attr_id = $attr_prop['id'];
						$attr_prop['set_id'] = $new_set_id;
						unset($attr_prop['id']);
						$new_attribute_id = $this->AttributeModel->insert($attr_prop);

						$attribue_options = $this->db
												->from('attributes_set_attributes_option')
												->where('attribute_id', $current_attr_id)
												->get()
												->result_array();
						if($attribue_options){
							$options_insert = array();
							foreach ($attribue_options as $key => $attribue_value) {
							       	unset($attribue_value['id']);
							       	$attribue_value['attribute_id'] = $new_attribute_id;
									$options_insert[] = $attribue_value;
							}
							$this->db->insert_batch('attributes_set_attributes_option', $options_insert);
							$options_insert = array();
						}
					}									
				}
			}
			
			return true;
		}else{		 	
			return false;
		}
	}
	
	function getAttributeSet( $set_id = 0){
		$set_id = intval( $set_id );
		$opt = [];
		$opt[ 'result' 	] = 'row';
		$opt[ 'where' 	][ 'id' ] = $set_id ;
		return $this->get_all( $opt );		
	}
}
