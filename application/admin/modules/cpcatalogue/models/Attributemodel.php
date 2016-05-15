<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 	Attribute Set Attributes
	CREATE TABLE `bg_attributes_set_attributes` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `label` varchar(100) NOT NULL,
	 `set_id` int(10) unsigned NOT NULL,
	 `type` varchar(50) NOT NULL,
	 `required` tinyint(1) NOT NULL DEFAULT '0',
	 `visible` tinyint(1) NOT NULL DEFAULT '1',
	 `searchable` tinyint(1) NOT NULL DEFAULT '1',
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='contains set attributes'
*/
class AttributeModel extends Commonmodel{

	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){		
        $this->data = array();

        $this->tbl_name = 'attributes_set_attributes';
        $this->tbl_pk_col = 'id';
        $this->tbl_cols['id'] = 'id';
        $this->tbl_cols['type'] = 'type';
        $this->tbl_cols['label'] = 'label';
        $this->tbl_cols['set_id'] = 'set_id';
        $this->tbl_cols['visible'] = 'visible';
        $this->tbl_cols['required'] = 'required';
        $this->tbl_cols['searchable'] = 'searchable';
        $this->tbl_cols['is_numeric'] = 'is_numeric';
	}

	function add($rules = array(), $param){
		$this->validation_rules = $rules;
		if($this->validate($rules)){
			$options = $this->input->post('options', true);
			$options = json_decode($options, true);

			$this->data['set_id'] = $param['set_id'];
			$this->data['type'] = $this->input->post('type', true);
			$this->data['label'] = $this->input->post('label', true);
			$this->data['visible'] = $this->input->post('visible', true);
			$this->data['required'] = $this->input->post('required');
			$this->data['is_numeric'] = $this->input->post('is_numeric');
			$this->data['is_userrelated'] = $this->input->post('is_userrelated');
			$this->data['searchable'] = $this->input->post('searchable', true);
			if( $this->data['type'] == 3 ){
				/* Hardcore may be issue */				
				$this->data['is_config'] =  1;
			}
			/* Field work as mark to make attribute work as option during product allocation */
			$this->data['asOptMark'] =  '1';

			com_changeNull($this->data['required']);
			com_changeNull($this->data['is_numeric']);
			com_changeNull($this->data['is_userrelated']);
			$new_set_id = $this->insert($this->data);
			$data = array();
			foreach($options as $key => $text){
				$data[] = array('attribute_id' => $new_set_id, 'option_text' => $text['item']);
			}
			if(count($data)){
				$this->load->model('AttrSetAttrOptionModel');
				$this->AttrSetAttrOptionModel->insert_bulk($data);
			}
			return true;
		}else{
			return false;
		}
	}

	function update($rules = array(), $param){
		$this->validation_rules = $rules;
		if($this->validate($rules)){
			$update_data = array();
			//$this->data['type'] = $this->input->post('type', true);	
			$this->data['visible'] = $this->input->post('visible', true);
			$this->data['required'] = $this->input->post('required', true);
			$this->data['is_numeric'] = $this->input->post('is_numeric', true);
			$this->data['searchable'] = $this->input->post('searchable', true);
			$this->data['is_userrelated'] = $this->input->post('is_userrelated');
			
			com_changeNull($this->data['required']);
			com_changeNull($this->data['is_numeric']);
			com_changeNull($this->data['is_userrelated']);

			$update_data['data'] = $this->data;			
			$update_data['where']['id'] = $param['id'];

			$new_set_id = $this->update_record($update_data);
			return $new_set_id;
		}else{
			return false;
		}
	}

	/* return attributes det ref to set id */
	function getAttrbDetailsViaSetIds($opts){
		extract( $opts );
       	$param = [];
        $param['select'] = 'id,is_sys,label,sys_label,set_id,is_userrelated,required,is_config';
        $param['where']['in'][0] = 'set_id';
        $param['where']['in'][1] = $attribute_set_ids;
        $param['where']['asOptMark'] = '1';
        $param['where']['visible'] = '1';
        $param['order'][ 0 ] = 'required,is_config';
        $param['order'][ 1 ] = 'desc';
        return $this->get_all($param);		
	}

	/* return attributes det ref id or PK */
	function getAttrbDetailsViaIds($opts){
		extract( $opts );
       	$param = [];
        $param['select'] = 'id,is_sys,label,sys_label,set_id,is_userrelated,required,asOptMark,is_config';
        $param['where']['in'][0] = 'id';
        $param['where']['in'][1] = $attribute_ids;
        return $this->get_all($param);		
	}	
}
