<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
CREATE TABLE `bg_attributes_set_attributes_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` int(10) unsigned NOT NULL,
  `option_text` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1
*/
class AttrSetAttrOptionModel extends Commonmodel{
	
	private $data;
  private $condition;
	function __construct()
	{
		# code...
        parent::__construct();
		    $this->data = array();
        $this->condition = array();

        $this->tbl_name = 'attributes_set_attributes_option';
        $this->tbl_alias = 'saop';
        $this->tbl_pk_col = 'id';

        $this->tbl_cols['id'] = 'id';
        $this->tbl_cols['option_text'] = 'option_text';
        $this->tbl_cols['attribute_id'] = 'attribute_id';        
	}
  
  function delete($attribute){
      if(is_array($attribute)){
        $values = array();

        foreach($attribute as $attr_key => $attr_val){
          $values[] = $attr_val['id'];
        }

        $this->condition['where']['in'] = array('col' => $this->tbl_cols['id'],
                                               'opt' => $values);
      }else{
        $this->condition['where'][$this->tbl_cols['id']] = intval($attribute);
      }
      $this->delete_record($this->condition);
  }

  function getSubOpt($pid){
    $param = array();
    $param['select'] = "$this->tbl_alias.id, $this->tbl_alias.option_text";   
    $param['where'] = array( "$this->tbl_alias.attribute_id" => $pid);
    return $this->get_all($param);
  }

  function getOccupiedOpt( $pid ){
	$param = array();
	$param['select'] = "distinct(attribute_value)";
	$param['from_tbl'] = "product_attribute";
	$param['where'] = array( "attribute_id" => $pid);
	return $this->get_all($param);	
  }

  function updateSubOpt($pid){
    $param = array();
    $param['select'] = "id";    
    $param['where'] = array( "attribute_id" => $pid);
    $db_ids = $this->get_all($param);
    $present_ids = array_column($db_ids, 'id');    

    $options = json_decode($this->input->post('options', true), true);
    $existing_ids = array_column($options, 'related_id');
    
    $deleted = array_diff($present_ids, $existing_ids);
        
    $insert_array = array();
    foreach($options as $opt_key => $opt_arr){
      if(isset($opt_arr['related_id']) ){
          $param = array();
          $param['where'] = array('id' => $opt_arr['related_id']);
          $param['data'] = array('option_text' => $opt_arr['item']);
          $this->update_record($param);          
        
      }else{
        $insert_array[] = array(
                          $this->tbl_cols['option_text'] => $opt_arr['item'],
                          $this->tbl_cols['attribute_id'] => $pid
                        );
      }
    }

    foreach( $deleted as $del){
        $this->delete($del);
    }
    if($insert_array){
      $this->insert_bulk($insert_array);
    }
  }

  function getAttrOptDet( $primaryid ){
    $param = [];
    $param['result'] = 'row';
    $param['where']['id'] = $primaryid;
    return $this->get_all( $param );
  }
}
