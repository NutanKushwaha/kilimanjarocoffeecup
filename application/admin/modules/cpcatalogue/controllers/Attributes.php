<?php

/**
* 
*/
class Attributes extends Admin_Controller
{
	private $rules;
	private $page;
	private $inner;
	private $type_list;
	function __construct(){
		parent::__construct();

		$this->is_admin_protected = TRUE;		
		$this->page = $this->inner = array();

		$this->load->model('AttributeModel');
		$this->load->model('AttributesetModel');
		$this->load->model('AttributeTypeModel');
		$this->load->model('AttrSetAttrOptionModel');		
		$this->inner['mod_menu'] = 'layout/inc-menu-catalog';
		$this->type_list = com_makelist($this->AttributeTypeModel->list_all(), 'id', 'type', false);
	}

	function add($set_id = 0){
		if (! $this->flexi_auth->is_privileged('Insert Attributes')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Attributes.</p>'); redirect('dashboard'); }

		$set_id = intval($set_id);
		$this->AttributesetModel->check_record_exist($set_id);

		$this->rules[] = array('field' => 'type', 'label' => 'Attribute Type', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'label', 'label' => 'Title', 
								'rules' => 'trim|required|min_length[3]|max_length[12]');
		$this->rules[] = array('field' => 'searchable', 'label' => 'Searchable', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'visible', 'label' => 'Visibile', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'required', 'label' => 'Required', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'is_numeric', 'label' => 'Required', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'is_userrelated', 'label' => 'Required', 'rules' => 'trim|numeric|required');		

		$param = array();
		$param['set_id'] = $set_id;

		$isValid = $this->AttributeModel->add($this->rules, $param);
	  	if (!$isValid){
	  		$this->inner['edit'] = false;
    		$this->inner['type_list'] =  $this->type_list;
	  		$this->page['content'] = $this->load->view('attributes/sub-page/attribute-add', $this->inner, TRUE);
			$this->load->view($this->template['default'], $this->page);
        }else{
       		$this->session->set_flashdata('SUCCESS', 'attribute_added');
            redirect("cpcatalogue/attributes/add/$set_id", 'location');
            exit();
        }
	}

	function manage($set_id = 0, $offset = 0){
		if (! $this->flexi_auth->is_privileged('View Attributes')){ 
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Attributes.</p>'); 
			redirect('dashboard'); 
		}

		$set_id = intval($set_id);
		$offset = intval($offset);
		$this->AttributesetModel->check_record_exist($set_id);

		$limit = 10;
        $count_condition = array();
        $count_condition['where']['set_id'] = $set_id;
        $count_condition['result'] = 'num';
        $this->inner['page_links'] = com_pagination(
											array(	'per_page' => $limit, 
													'base_url' => 'cpcatalogue/attributes/manage/'.$set_id.'/',
													'total_rows' => $this->AttributeModel->get_all($count_condition),
													'uri_segment' => '5'
												)
										);
        $condition = array();
        $condition['where']['set_id'] = $set_id;
        $condition['limit'] = array('offset' => $offset, 'limit' => $limit);
        $this->inner['offset'] = $offset;
    	$this->inner['attr_list'] = $this->AttributeModel->get_all($condition); 
    	$this->inner['type_list'] =  $this->type_list;    	
		$this->inner['list_view'] = $this->load->view('attributes/sub-page/attribute-listing', $this->inner, TRUE);

        
        $this->page['content'] = $this->load->view('attributes/attribute', $this->inner, TRUE);
        $this->load->view($this->template['default'], $this->page);
	}	

	function edit($attr_id = 0){
		if (! $this->flexi_auth->is_privileged('Update Attributes')){ 
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update Attributes.</p>'); 
			redirect('dashboard'); 
		}

		$attr_id = intval($attr_id);
		$attribute_det = $this->AttributeModel->get_by_pk($attr_id, false);
		if( !$attribute_det ){
			$opt = [];
			$opt[ 'top_text' ] = 'Attribute not exist';
			$opt[ 'bottom_text' ] = 'Attribute does not exist';
			$this->utility->show404( $opt );
			return;
		}
		$attribute_det_sub_options = $this->AttrSetAttrOptionModel->getSubOpt($attr_id);
		$attribute_occup_sub_options = com_makelist($this->AttrSetAttrOptionModel->getOccupiedOpt($attr_id) , 'attribute_value', 'attribute_value', 0 );
		$this->rules[] = array('field' => 'searchable', 'label' => 'Searchable', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'visible', 'label' => 'Visibile', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'required', 'label' => 'Required', 'rules' => 'trim|numeric|required');
		$this->rules[] = array('field' => 'is_numeric', 'label' => 'Required', 'rules' => 'trim|numeric|required');
		$param = array();
		$param['id'] = $attr_id;

		$isValid = $this->AttributeModel->update($this->rules, $param);
	  	if (!$isValid){
	  		$this->inner['edit'] = false;
	  		$this->inner['attr_id'] = $attr_id;
    		$this->inner['type_list'] =  $this->type_list;
    		$this->inner['edit_det'] = $attribute_det;
    		$this->inner['edit_sub'] = $attribute_det_sub_options;
    		$this->inner['occu_sub'] = $attribute_occup_sub_options;    		
	  		$this->page['content'] = $this->load->view('attributes/sub-page/attribute-edit', $this->inner, TRUE);
			$this->load->view($this->template['default'], $this->page);
        }else{        	
        	$this->AttrSetAttrOptionModel->updateSubOpt($attr_id);
       		$this->session->set_flashdata('SUCCESS', 'attribute_updated');
            redirect("cpcatalogue/attributes/manage/".$attribute_det['set_id'], 'location');
            exit();
        }
	}

	function delete($attr_id = 0){	
		if (! $this->flexi_auth->is_privileged('Delete Attributes')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Attributes.</p>'); redirect('dashboard'); }
	
		$attr_id = intval($attr_id);
		$attribute_det = $this->AttributeModel->get_by_pk($attr_id, false);
		if( !$attribute_det ){
			$opt = [];
			$opt[ 'top_text' ] = 'Attribute not exist';
			$opt[ 'bottom_text' ] = 'Attribute does not exist';
			$this->utility->show404( $opt );
			return;
		}
		$condition = array();
		$condition['where']['attribute_id'] = $attr_id;
		$this->AttrSetAttrOptionModel->delete_record($condition);		
		$this->AttributeModel->delete_by_pk($attr_id);
		redirect("cpcatalogue/attributes/manage/".$attribute_det['set_id'], 'location');
        exit();
	}

	function getAttributeForSetId($set_id){
        $condition = array();
        $condition['select'] = 'attributes_set_attributes.*,attributes_type.type,attributes_set_attributes_option.id as option_id,attributes_set_attributes_option.option_text';
        $condition['where']['set_id'] = $set_id;
        $condition['join'][] = array('tbl' => 'attributes_set_attributes_option', 'cond' => 'attributes_set_attributes_option.attribute_id=attributes_set_attributes.id', 'type' => 'left');
        $condition['join'][] = array('tbl' => 'attributes_type', 'cond' => 'attributes_type.id=attributes_set_attributes.type', 'type' => 'inner');        
    	$attr_list = $this->AttributeModel->get_all($condition);
    	$attr_opt = array();
    	$new_attr_list = array();
    	$attr_key_comb = array();    	

    	foreach ($attr_list as $key => $value) {
    		$new_attr_list[$value['id']] = $value;
    		$attr_key_comb[$value['id']] = $value['type'];
    		if(in_array($value['type'], array('MULTISELECT', 'DROPDOWN'))){
    			if(!empty($value['option_id'])){
    				$attr_opt[$value['id']]['0'] = 'Select';
    				$attr_opt[$value['id']][$value['option_id']] = $value['option_text'];
    			}
    		}
    	}    	
    	$this->inner['fp'] = 0;
    	$this->inner['attr_opt'] = $attr_opt;
    	$this->inner['attr_list'] = $new_attr_list;
    	$type =isset($_GET['data'])?$_GET['data']:'';
    	$output = array();
    	$output['success'] = 1;
    	if($type){
			$output['html'] = $this->load->view('attributes/sub-page/for-product-accord-listing', $this->inner, true);
    	}else{
			$output['html'] = $this->load->view('attributes/sub-page/for-product-listing', $this->inner, true);
    	}    	
    	echo json_encode($output);
    	exit();
	}
}	
