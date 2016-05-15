<?php

/**
* 
*/
class Department extends Admin_Controller
{
	private $rules;
	private $page;
	private $inner;

	function __construct(){
		parent::__construct();

		$this->is_admin_protected = TRUE;
		$this->page = $this->inner = array();
		$this->data = [];
		$this->data['perpage'] = '10';
		$this->load->model('Departmentmodel');
	}

    function check_department_name($field_val){		
        if ($this->Departmentmodel->checkCompanyDept( $field_val )) {
            $this->form_validation->set_message('check_department_name', 'Department name already occupied!');
            return false;
        }
        return true;
    }
    
	function index($offset = 0){
		$limit = 4;
        //render view
        $param = array();
        $company_code = '';
		if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
        $param['where']['company_code'] = $company_code;
        $this->inner['page_links'] = com_pagination(
											array(	'per_page' => $limit, 
													'base_url' => 'company/department/index/',
													'total_rows' => $this->Departmentmodel->count_rows($param), 
													'uri_segment' => '4'
												)
										);
        $param['limit'] = array('offset' => $offset, 'limit' => $limit);
    	$this->inner['list'] = $this->Departmentmodel->get_all($param);
		$this->inner['list_view'] = $this->load->view('department/sub-page/listing', $this->inner, TRUE);

        
        $this->page['content'] = $this->load->view('department/department-index', $this->inner, TRUE);
        $this->load->view($this->template['default'], $this->page);
	}

	function add(){
		        
		$this->rules[] = array('field' => 'name', 
								'label' => 'Department', 
								'rules' => 'trim|required|min_length[3]|max_length[50]|callback_check_department_name');

		$isValid = $this->Departmentmodel->add($this->rules);
	  	if (!$isValid){
	        $condition = array('select' => 'id,name');
    		$this->inner['list'] = $this->Departmentmodel->get_all($condition);
    		$this->inner['list'] =  com_makelist($this->inner['list'], 'id', 'name');
	  		$this->page['content'] = $this->load->view('department/sub-page/department-add', $this->inner, TRUE);
			$this->load->view($this->template['default'], $this->page);
        }else{
       		$this->session->set_flashdata('SUCCESS', 'department_added');
            redirect("company/department/", 'location');
            exit();
        }
    	
	}


	function delete($id = 0){
		
		$id = intval($id);
		$department = $this->Departmentmodel->get_by_pk($id, false);
		if( !$department ){
			$opt = [];
			$opt[ 'top_text' ] = 'Department not found';
			$opt[ 'bottom_text' ] = 'Department does not exist';
			$this->utility->show404( $opt );
			return;
		}		
		$this->Departmentmodel->delete_by_pk($id);
		$this->session->set_flashdata('SUCCESS', 'department_deleted');
		redirect("company/department/", 'location');
        exit();
	}

	function assignProduct( $dept_id = 0){

		$department = $this->Departmentmodel->get_by_pk($dept_id, false);
		if( !$department ){
			$opt = [];
			$opt[ 'top_text' ] = 'Department not found';
			$opt[ 'bottom_text' ] = 'Department does not exist';			
			$this->utility->show404( $opt );
			return;
		}
		$this->load->model('Companymodel');
		$this->load->model('Compdeptprodmodel');
		
		if( $this->input->post('dept-prod')){			
			$this->Compdeptprodmodel->insertDeptPolicy($dept_id);
			$this->session->set_flashdata('SUCCESS', 'dept_policy_updated');
			redirect('company/department');			
		}
		$offset = 0;
		$keywords = '';		
        if( $this->user_type == CMP_ADMIN ){
			$comp_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$comp_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
		$cmp_config = [];
		$cmp_config[ 'result' ] = 'row';
		$cmp_config[ 'select' ] = 'account_id, multi_kit, skip_policy, make_req_order';
		$cmp_config[ 'from_tbl' ] = 'company';
		$cmp_config[ 'where' ][ 'company_code' ] = $comp_code;
	 	$company_config = $this->Companymodel->get_all( $cmp_config );	 	
		$this->data['skip_policy'] = $company_config[ 'skip_policy' ];
		
		$this->data['products'] = $this->Companymodel->getCompAssignProdWithDetails(
										$comp_code,$offset, $this->data['perpage']);		
		$searchP = [];
		$searchP[ 'comp_code' ] = $comp_code;
        $config['total_rows'] = $this->Companymodel->getCompAssignProdCount( $searchP )['ttl'];
		
		$this->data['search_product'] = '';
		$this->data['exact_match'] = '';
		$this->data['hidden'] = [];		
		$this->data['hidden_days'] = [];
		$this->data['group_products'] = [];
		$this->data['hidden_gpolicy'] = [];
		$this->data['hidden_quantity'] = [];
		$this->data['common_days'] = [];
		$this->data['common_products'] = [];
		$this->data['common_gpolicy'] = [];
		$this->data['common_quantity'] = [];
		$this->data['existed_products'] = [];		
		
		
		$this->data['group_products'] = com_makelist ($this->Companymodel->getCompAssignProdWithDetails($comp_code),
										 'product_sku', 'product_name', 0 );
		$fetchedPolicy = $this->Compdeptprodmodel->getDeptPolicy($dept_id);
		if( $fetchedPolicy ) {
			$listingProds = array_column($this->data['products'],'product_sku');
			$fetchedPolicySkuIndexArr = com_makeArrIndexToField($fetchedPolicy, 'product_sku');
			$fetchedPolicyProdSku = array_keys($fetchedPolicySkuIndexArr);
			$this->data['common_products'] = array_intersect($fetchedPolicyProdSku, $listingProds);
			$this->data['hidden'] = array_diff($fetchedPolicyProdSku, $this->data['common_products']);
			foreach($this->data['common_products'] as $arrIndex => $comSku){
				$this->data['common_days'][$comSku] = $fetchedPolicySkuIndexArr[$comSku]['days_limit'];
				$this->data['common_quantity'][$comSku] = $fetchedPolicySkuIndexArr[$comSku]['qty_limit'];
				$this->data['common_gpolicy'][$comSku] = json_decode( $fetchedPolicySkuIndexArr[$comSku]['group_policy'] );
			}

			foreach($this->data['hidden'] as $arrIndex => $hiddSku){
				$this->data['hidden_days'][$hiddSku] = $fetchedPolicySkuIndexArr[$hiddSku]['days_limit'];
				$this->data['hidden_quantity'][$hiddSku] = $fetchedPolicySkuIndexArr[$hiddSku]['qty_limit'];
				$this->data['hidden_gpolicy'][$hiddSku] = json_decode( $fetchedPolicySkuIndexArr[$hiddSku]['group_policy'] );
			}
		}
		

		$this->data['dept_id'] = $dept_id;
		$param = [];
		$param[ 'select' ]  = 'name';
		$param[ 'result' ]  = 'row';
		$param[ 'where' ][ 'id' ]  = $dept_id;
		$this->data['dept_name'] = $this->Departmentmodel->get_all($param)[ 'name' ];
		$this->data['days'] = ['1' => 'Weekly', '2' => 'Monthly', '3' => 'Yearly'];

		//pagination configuration
        $config['html_container'] = 'product-tbl-div';
        $config['base_url']    = base_url().'company/ajax/department/getProducts/'.$dept_id;
        $config['per_page']    = $this->data['perpage'];
        $config['form_serialize'] = 'dept-product-allot';
        $this->data['pagination'] =  com_ajax_pagination($config);        
        if( !$company_config[ 'skip_policy' ] ){
			$this->data['product_allot_html'] =  $this->load->view('ajax/dept-product-allocation', $this->data, TRUE);
		} else {			
			$this->data['product_allot_html'] =  $this->load->view('ajax/dept-product-wo-policy-allocation', $this->data, TRUE);
		}
  		$this->data['content'] = $this->load->view('dept-product-allocation', $this->data, TRUE);
		$this->load->view($this->template['default'], $this->data);        
	}
}
