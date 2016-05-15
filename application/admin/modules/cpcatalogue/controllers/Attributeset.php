<?php

/**
* 
*/
class Attributeset extends Admin_Controller
{
	function __construct(){
		parent::__construct();		
		$this->is_admin_protected = TRUE;
		$this->page = $this->inner = array();

		$this->load->model('AttributeModel');
		$this->load->model('AttributesetModel');		
		$this->load->model('AttrSetAttrOptionModel');
		$this->inner['mod_menu'] = 'layout/inc-menu-catalog';
	}

	function index($offset = 0){

        if (! $this->flexi_auth->is_privileged('View Attribute Set'))
        {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Attribute Set Listing.</p>');
            redirect('dashboard');
        }

		$limit = 4;
        //render view
        $this->inner['page_links'] = com_pagination(
											array(	'per_page' => $limit, 
													'base_url' => 'cpcatalogue/attributeset/index/',
													'total_rows' => $this->AttributesetModel->count_rows(), 
													'uri_segment' => '4'
												)
										);
        $condition = array();
        $condition['limit'] = array('offset' => $offset, 'limit' => $limit);
    	$this->inner['set_list'] = $this->AttributesetModel->get_all($condition);    	
		$this->inner['list_view'] = $this->load->view('attributes/sub-page/attributeset-listing', $this->inner, TRUE);

        
        $this->page['content'] = $this->load->view('attributes/attributeset', $this->inner, TRUE);
        $this->load->view($this->template['default'], $this->page);
	}

	function add(){

		if (! $this->flexi_auth->is_privileged('Insert Attribure set')) { 
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Attribure set .</p>'); 
			redirect('dashboard'); 
		}

		$this->rules[] = array('field' => 'source_name', 'label' => 'Source Set', 'rules' => 'trim|numeric');
		$this->rules[] = array('field' => 'set_name', 'label' => 'Attribute Set', 'rules' => 'trim|is_unique[attributes_set.set_name]|required|min_length[5]|max_length[25]');

		$isValid = $this->AttributesetModel->add($this->rules);	   	
	  	if (!$isValid){	
	        $condition = array('select' => 'id,set_name');
    		$this->inner['set_list'] = $this->AttributesetModel->get_all($condition);
    		$this->inner['set_list'] =  com_makelist($this->inner['set_list'], 'id', 'set_name', 0);
	  		$this->page['content'] = $this->load->view('attributes/sub-page/attributeset-add', $this->inner, TRUE);
			$this->load->view($this->template['default'], $this->page);
        }else{
       		$this->session->set_flashdata('SUCCESS', 'attribute_set_added');
            redirect("cpcatalogue/attributeset/index/");
            exit();
        }
    	
	}


	function delete($set_id = 0){
		
		if (! $this->flexi_auth->is_privileged('Delete Attribure set')){ 
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Attribure set .</p>'); 
			redirect('dashboard'); 
		}

		$set_id = intval($set_id);
		$attribute_det = $this->AttributesetModel->get_by_pk($set_id, false);
		if( !$attribute_det ){
			$opt = [];
			$opt[ 'top_text' ] = 'Attribute set not exist';
			$opt[ 'bottom_text' ] = 'Attribute set does not exist';
			$this->utility->show404( $opt );
			return;
		}
		$condition = array();
		$condition['select'] = 'id';
		$condition['where']['set_id'] = $attribute_det['id'];
		$set_attributes_id =  $this->AttributeModel->get_all($condition) ;
		if($set_attributes_id){
			$this->AttrSetAttrOptionModel->delete($set_attributes_id);
		}		
		$condition = array();
		$condition['where']['set_id'] = $attribute_det['id'];
		$this->AttributeModel->delete_record($condition);
		$this->AttributesetModel->delete_by_pk($set_id);
		redirect("cpcatalogue/attributeset/", 'location');
        exit();
	}
}	
