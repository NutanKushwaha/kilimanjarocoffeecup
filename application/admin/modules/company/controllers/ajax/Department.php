<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
* 
*/
class Department extends Adminajax_Controller
{
	private $rules;
	private $page;
	private $inner;

	function __construct(){
		parent::__construct();
		if(!$this->input->is_ajax_request()){
			redirect('');
			exit;
		}
		$this->is_admin_protected = TRUE;
		$this->data = [];
		$this->data['perpage'] = 10;
	}

	function getProducts( $dept_id ){
		$this->load->model('Companymodel');
		parse_str($this->input->post("form_data"), $form_data);
		$this->data['exact_match'] = com_arrIndex($form_data, 'exact_match', 0);
		$this->data['search_product'] = trim($form_data['search_product']);		
		$selectedProducts = com_arrIndex($form_data , 'products',[]);
		$form_data['gpolicy'] = com_arrIndex($form_data , 'gpolicy',[]);
		$offset = $this->input->post('offset');
		com_changeNull($offset, 0);
		$config['cur_page'] = $offset;		
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
	 	$this->data['dept_id'] = $dept_id;
		$this->data['skip_policy'] = $company_config[ 'skip_policy' ];
		$oths = [];
		$oths[ 'exact_match' ] = $this->data['exact_match'];
		$oths[ 'search_product' ] = $this->data['search_product'];
		$this->data['products'] = $this->Companymodel->getCompAssignProdWithDetails(
										$comp_code,
										$offset, 
										$this->data['perpage'], $oths);
		$searchP = [];
		$searchP = $oths;
		$searchP[ 'comp_code' ] = $comp_code;
        $config['total_rows'] = $this->Companymodel->getCompAssignProdCount( $searchP )['ttl'];
        
        $this->data['fetched_products'] = array_column($this->data['products'], 'product_sku');
        $this->data['group_products'] = '';
        
		if( !$company_config[ 'skip_policy' ] ){
			$this->data['group_products'] = com_makelist(	$this->Companymodel->getCompAssignProdWithDetails($comp_code), 
												'product_sku', 'product_name' , 0);        					
		}
		$this->data['existed_products'] = $selectedProducts;
		$this->data['existed_days'] = [];
		$this->data['existed_quantity'] = [];
		$this->data['existed_gpolicy'] = [];

		$this->data['common_days'] = [];
		$this->data['common_quantity'] = [];
		$this->data['common_gpolicy'] = [];
		
		$this->data['hidden_days'] = [];
		$this->data['hidden_quantity'] = [];
		$this->data['hidden_gpolicy'] = [];
				
		if( !$company_config[ 'skip_policy' ] ){
			foreach( $this->data['existed_products'] as $arrIndex => $prodSku){
				$this->data['existed_days'][$prodSku] = $form_data['days'][$prodSku];
				$this->data['existed_quantity'][$prodSku] = $form_data['quantity'][$prodSku];
				$this->data['existed_gpolicy'][$prodSku] = com_arrIndex($form_data['gpolicy'],$prodSku, '' );
			}
		}

		$this->data['common_products'] = array_intersect($this->data['existed_products'], $this->data['fetched_products']);
		$this->data['hidden'] = array_diff($selectedProducts, $this->data['common_products']);

		if( !$company_config[ 'skip_policy' ] ){
			$common_prod_sku = $this->data['hidden'];
			//com_e( $common_prod_sku , 0);			
			$fld = ['days', 'gpolicy', 'quantity'];
			foreach ($fld as $key) {
				if( $common_prod_sku && is_array( $common_prod_sku ) && sizeof( $common_prod_sku ) ) {
					foreach($common_prod_sku as $sku){
						$this->data['hidden_'.$key][$sku]  = $this->data['existed_'.$key][$sku];
					}
				} else {
					$this->data['hidden_'.$key] = array();
				}
			}
			
			$common_prod_sku = $this->data['common_products'];
			//com_e( $common_prod_sku , 0);
			foreach ($fld as $key) {
				foreach($common_prod_sku as $sku){
					$this->data['common_'.$key][$sku] = $this->data['existed_'.$key][$sku];
				}
			}
		}
		
		$this->data['days'] = ['1' => 'Weekly', '2' => 'Monthly', '3' => 'Yearly'];
        $config['html_container'] = 'product-tbl-div';
        $config['base_url']    = base_url().'company/ajax/department/getProducts/'.$dept_id;
        $config['per_page']    = $this->data['perpage'];
        $config['form_serialize'] = 'dept-product-allot';
        $this->data['pagination'] =  com_ajax_pagination($config);
        if( !$company_config[ 'skip_policy' ] ){
			$sub_view_dept_policy = 'ajax/dept-product-allocation';
		} else {
			$sub_view_dept_policy = 'ajax/dept-product-wo-policy-allocation';			
		}		
        $output = [
                    'success' => 1,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                    'html' => $this->load->view($sub_view_dept_policy, $this->data, TRUE),
                ];

        echo json_encode( $output );
        exit;
	}
}
