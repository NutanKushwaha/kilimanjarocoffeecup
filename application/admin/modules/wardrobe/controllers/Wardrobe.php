<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class wardrobe extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->data = [];        
    }

    function index() {      
        if (! $this->flexi_auth->is_privileged('View Wardrobe')){
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Wardrobe.</p>'); 
            redirect('dashboard'); 
        }        
        $this->data['INFO'] = (! isset($inner['INFO'])) ? 
                            $this->session->flashdata('message') : $this->data['INFO'];                
        if( $this->user_type == USER ){
            $this->load->model('Commonusermodel');
            $users_assigned_dept = json_decode( $this->flexi_auth->get_user_custom_data('upro_department') );
            if( !is_array($users_assigned_dept) or is_null($users_assigned_dept)){
                $users_assigned_dept = [];
            }
            $company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
            $opt = [];
            $opt[ 'result' ] = 'row';
            $opt[ 'select' ] = 'cat_suffix';
            $opt[ 'from_tbl' ] = 'company';
            $opt[ 'where' ][ 'company_code' ] = $company_code;
            $this->data['cat_suffix'] = $this->Commonusermodel->get_all( $opt )[ 'cat_suffix' ];
            $this->data['selected_dept'] = com_gParam('deptId', FALSE, 0);
            $this->data['user_dept'] = $this->Commonusermodel->getUserAssignedDept( $users_assigned_dept );
            $this->data['user_dept']['0'] = 'Select Department';        
            $this->data['products'] = [];
            $this->data['category_dept'] = $this->data['selected_dept'];
            $this->data['current_offset'] = com_gParam('offset', FALSE, 0);
            if( $users_assigned_dept ) {
                $this->load->model('product_allocation/Userprodallocationmodel',  'Userprodallocationmodel');
                $perpage = 16;
                $param = [];
                $param['limit'] = [ 'offset' => $this->data['current_offset'], 
                                    'limit' => $perpage];
                $param['dept_id'] = $this->data['selected_dept'] ? $this->data['selected_dept'] : 
                                    current(array_keys($this->data['user_dept']));
                $this->data['products'] = $this->Userprodallocationmodel->getLoginUserProdAlloc($param);
                $config['total_rows'] = $this->Userprodallocationmodel
                                            ->getLoginUserProdCount(['dept_id' => $param['dept_id']]);
                $this->data['selected_dept'] = $param['dept_id'];
                $this->data['category_dept'] = $this->data['selected_dept'];
                $config['request_type']= 'get';
                $config['per_page']    = $perpage;
                $config['base_url']    = 'wardrobe/ajax/wardrobe/getUserProd/';
                $config['html_container'] = 'prod-list-table';
                $config['additional_param'][]['js'] = ['dept_id' => "$('#department').val()"];
                $this->data['pagination'] = com_ajax_pagination($config);
            }            
            ksort($this->data['user_dept']);
            $this->data['error_message'] = 'No product assign to department  ';
            $this->data['user_type'] = $this->user_type;
            $this->data['table_prod_view'] = $this->load->view('ajax/user-allocated-product', $this->data, true);
            $this->data['content'] = $this->load->view('wardrobe-product/user-wardrobe', $this->data, true);
            $this->load->view($this->template['default'], $this->data);
        }else if( in_array( $this->user_type, [CMP_ADMIN, CMP_MD, CMP_PM ] )  ){
			$company_code = "";
			if( $this->user_type == CMP_ADMIN ){
				$company_code = $this->flexi_auth->get_comp_admin_company_code();
			} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
				$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			}
			$this->load->model('company/Companymodel', 'Companymodel');
            $opt = [];
            $opt[ 'result' ] = 'row';
            $opt[ 'select' ] = 'cat_suffix';
            $opt[ 'from_tbl' ] = 'company';
            $opt[ 'where' ][ 'company_code' ] = $company_code;
            $this->data['cat_suffix'] = $this->Companymodel->get_all( $opt )[ 'cat_suffix' ];
            
            $category_dept = com_gParam('category_dept' , 0, 0);
            $search_param = [];
            $this->data['category_dept']    = $category_dept;
            $search_param['category']       = $category_dept;
            $search_param['comp_code']      = $company_code;
            $search_param['company_code']   = $company_code;
            
            $offset     =   com_gParam('offset' , 0, 0);
            $perpage    =   16;     
            $config['cur_page']         =   $offset;
            $config['request_type']     =   'get';
            $config['per_page']         =   $perpage;
            $config['js_rebind']        =   '';
            $config['html_container']   =   'prod-list-table';
            $config['base_url']         =    base_url().'wardrobe/ajax/wardrobe/getCompProd/';            
            $config['total_rows']       =    $this->Companymodel->getCompAssignProdCount( $search_param )[ 'ttl' ];            
            $config['additional_param'][]['js'] = ['category' => "$('#category').val()"];
            $config['additional_param'][]['js'] = ['prodName' => "$('#prodName').val()"];
            $this->data['pagination']   =   com_ajax_pagination($config);
            $this->data['prodName']     =   '';
            $this->data['cat_selected'] =   '';
            $this->data['categories']   =   $this->Companymodel->getCompAssignProdCat( $search_param );
            //list all Products
            $this->data['products'] = [];
            $search_param[ 'offset' ]   =   $offset;
            $search_param[ 'limit' ]    =   $perpage;            
            $this->data['current_offset'] = 0;
            $this->data['products'] = $this->Companymodel->getLoginCompProdAlloc( $search_param );            
            $this->data['error_message'] = 'Product does not found.';
            $this->data['cartContents']  = $this->cart->contents();
            $this->load->model('cart/Cartmodel', 'Cartmodel');
            extract ($this->Cartmodel->getCartVariables());            
            $this->data['cartTotal']  =  $cartTotal;
            $this->data['itemTotal']  =  $itemTotal;
            $this->data['mini_cart_view'] = $this->load->view('ajax/comp-mini-cart', $this->data, true);
            /*
            $this->data['products_list_view' ] = $this->load->view('ajax/comp-prod-list', $this->data, true);
            $this->data['content'] = $this->load->view('wardrobe-product/comp-wardrobe', $this->data, true);
            $this->load->view($this->template['without_menu'], $this->data);
            */
            $this->data['user_type'] = $this->user_type;
            $this->data['table_prod_view'] = $this->load->view('ajax/user-allocated-product', $this->data, true);
            $this->data['content'] = $this->load->view('wardrobe-product/comp-wardrobe', $this->data, true);
            $this->load->view($this->template['without_menu'], $this->data);
        }
    }

    function view_product($p_alias = false, $category_dept = false, $offset = 0) {
        if (! $this->flexi_auth->is_privileged('View Wardrobe')){
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Wardrobe.</p>'); 
            redirect('dashboard'); 
        }
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        if(!$p_alias)
        {
            $this->session->set_flashdata('MESSAGE', 'Product does not found.');
            redirect('dashboard');
            return false;
        }
        /* product main details fetched */
        $product = $this->Productmodel->details($p_alias);    
        if (!$product) {
            com_e( "View implementing pending", 0 );
            $this->utility->show404();
            return;
        }
        $inner = array();        
        $product_main_details = $product;
        $this->_getProductPriceAsPerCompPriceList( $product_main_details );
        $inner['detail'] = $product_main_details;
        $inner['product_main_details'] = $product_main_details;
        $isSimple        = TRUE;
        $inner['parent'] = false;
        if ($product['ref_product_id'] == 0 && $product['product_type_id']==2) {
            $isSimple       = FALSE;
            $inner['parent']= true;            
        }
        /* get product attributes as per assign attribute set id */
        $prodids = [ $product_main_details['product_id'] => $product_main_details];
        $product_attr_details = $this->Productmodel->prodAttributes($prodids, $isSimple);
                        
        $inner['product_attr_details'] = $product_attr_details;
        $p_img_url = $this->config->item('PRODUCT_IMAGE_URL').$product['product_image'];
        $r_img_url =  $this->config->item('PRODUCT_IMAGE_URL').'default_product.jpg';    
        if( empty( $product['product_image'] ) ){
            $p_img_url = $r_img_url;
        }
        $params = [
                        'image_url' => $p_img_url,
                        'image_path' => $this->config->item('PRODUCT_IMAGE_PATH').$product['product_image'],
                        'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
                        'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
                        'width' => 600,
                        'height' => 600,
                        'default_picture' => $r_img_url,
                    ];	
        $inner[ 'prodAttrb' ] = $product_attr_details[ $product_main_details['product_id'] ][ 'prdAttrLblOpts' ];
        $inner[ 'product_image_url' ] = resize( $params);
        $inner[ 'offset' ]          =   $offset;
        $inner[ 'isSimple' ]        =   $isSimple;
        $inner[ 'category_dept' ]   =   $category_dept;
        $page = array();                
        $page['content'] = $this->load->view('wardrobe-product/comp-product-detail', $inner, TRUE);
        $this->load->view($this->template['without_menu'], $page);        
    }

    function cuser_view_product( ) {
        if (! $this->flexi_auth->is_privileged('View Wardrobe')){
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Wardrobe.</p>'); 
            redirect('dashboard'); 
        }
        $page = array();
        $inner = array();
        $deptId = $this->input->post('deptId');
        $offset = $this->input->post('offset');
        $productRef = $this->input->post('product_ref');
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        /* product main details fetched */
        $product_main_details = $this->Productmodel->details( $productRef );				
        if( !$product_main_details ){
            $this->session->set_flashdata('MESSAGE', 'Product does not found.');
            redirect('dashboard');
            return false;
        }
        $qtyFromProdCheck = false;
        $parent     = false;
        $isSimple   = TRUE;
        if ($product_main_details['ref_product_id'] == 0 
            && $product_main_details['product_type_id'] == 2) {
            $isSimple   = FALSE;
            $parent     = true;            
        }        
        /* get product attributes as per assign attribute set id */
        $prodids = [ $product_main_details['product_id'] => $product_main_details ];
        $prodAttr = $this->Productmodel->prodAttributes( $prodids, $isSimple);
        $this->_getProductPriceAsPerCompPriceList( $product_main_details );
        $this->load->model('product_allocation/Userprodallocationmodel',  'Userprodallocationmodel');
        $this->load->model( 'Othrcommonconfigmodel');
        /*
            $userAttrAssignment it contains attribute selection for user, for now pending
        */
        $user_id = $this->flexi_auth->get_user_custom_data( 'uacc_id' );
        $userAttrAssignment = $this->Userprodallocationmodel->getDeptUserProductDetail( $deptId, $user_id, $product_main_details['product_sku'] );        
        $days_limit = $this->Othrcommonconfigmodel->getDetailWithType('days');
        $cartAssocDet = $this->session->cartAssocDet;
        $prodAlreadyAddedCartRowId = 0;
        if( $cartAssocDet ){
                if( isset(  $cartAssocDet[ $product_main_details['product_sku'] ] ) ){
                    $prodAlreadyAddedCartRowId =  $cartAssocDet[ $product_main_details['product_sku'] ];
                }
        }
        $params = [
                    'user_id' => $user_id,
                    'days_limit' => $days_limit,
                    'cartRowId' => $prodAlreadyAddedCartRowId,
                    'userAttrAssignment' => $userAttrAssignment, 
                    'product_sku' => $product_main_details['product_sku'],
        ];
        $comp_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
        $comp_det = $this->db->select( 'skip_policy' )->from('company')->where( 'company_code', $comp_code)->get()->row_array();
        $comp_skip_policy = com_arrIndex($comp_det, 'skip_policy', FALSE);
                
        $is_by_pass_policy = false;
        $days_limit = com_makelist($days_limit, 'config_id', 'config_label');
        $assignedQty = isset( $userAttrAssignment[ 0 ]['quantity']) ? $userAttrAssignment[ 0 ]['quantity']  :'N/A';
        $assignedDay = isset( $userAttrAssignment[ 0 ]['days_limit']) ? $days_limit [$userAttrAssignment[ 0 ]['days_limit']]  :'N/A';
        if ( isset( $userAttrAssignment[ 0 ]['is_by_pass']) && $userAttrAssignment[ 0 ]['is_by_pass'] 
			|| $comp_skip_policy ){
            $userAttrAssignment[0]['quantity'] = $assignedQty = 10000;
            $is_by_pass_policy = true;
        }

        $extraMessage = '';
        if( !$is_by_pass_policy ){
            $prodAvailCheckDet = com_checkProductAvailability( $params );                    
            if( $prodAvailCheckDet && $prodAvailCheckDet[ 'success' ] ) {
                $userAttrAssignment[0]['quantity'] = $prodAvailCheckDet[ 'allowedQty' ];
                if( $prodAvailCheckDet[ 'allowedQty' ] == 0 && $prodAvailCheckDet[ 'withCalculation' ]){
                    $extraMessage = 'You have reach allowed quantity limit for this product';
                } else if ( $prodAvailCheckDet[ 'allowedQty' ] == 0) {
                    $extraMessage = 'Product quantity limit not set';
                }
            }
        }
        
        $inner = [
                'parent'    => $parent,
                'deptId'    => $deptId,
                'offset'    => $offset,
                'isSimple'  => $isSimple,
                'assignedQty'   => $assignedQty,
                'assignedDay'   => $assignedDay,
                'days_limit'    => $days_limit,
                'extraMessage'  => $extraMessage,
                'product_attr_details'  => $prodAttr,
                'userAssignments'       => $userAttrAssignment,
                'is_by_pass_policy'     =>  $is_by_pass_policy,
                'product_main_details'  => $product_main_details,
                'prodAttrb'             => $prodAttr[ $product_main_details['product_id'] ][ 'prdAttrLblOpts' ], 
                'product_image_url' => com_get_product_image($product_main_details['product_image'], 600, 600),                    
        ];        
        $page['content'] = $this->load->view('wardrobe-product/user-product-detail', $inner, TRUE);
        $this->load->view($this->template['without_menu'], $page);
    }


        /* check product price as per company related prices */
    private function _getProductPriceAsPerCompPriceList( &$productDetRef ){
        $this->load->model('company/Companymodel', 'Companymodel');
        if ( in_array($this->user_type, [USER, CMP_PM, CMP_MD]) ) {
            $userCompanyCode = $this->flexi_auth->get_user_custom_data( 'upro_company');
        }else if ( $this->user_type == CMP_ADMIN ) {
            $userCompanyCode = $this->flexi_auth->get_comp_admin_company_code();        
        }        
        $companyDet = $this->Companymodel->getCompanyDetailFromCompanyCode( $userCompanyCode );        
        $companyPriceListNum = $companyDet[ 'price_list' ];
        $productPriceListPrice = $this->Productmodel->getProductPriceFromPriceList( $companyPriceListNum, $productDetRef['product_sku']);
        if( $productPriceListPrice ){
            $productDetRef['product_price'] = $productPriceListPrice;
        }
    }

    /* Add to cart functionality */
    function addToCart( ){
        if (! $this->flexi_auth->is_privileged('View Wardrobe')){
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Wardrobe.</p>'); 
            redirect('dashboard'); 
        }                
        $this->load->model('cart/Cartmodel', 'Cartmodel');
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        /* Default output */
        $output = [];
        $output['status'] = 0;        
        $output['cartRowId'] = 0; 
        $output['totalCart'] = '';
        $output['mincarthtml'] = '';
        $output['message'] = 'Product does not found';
        $addDet = [ ];   
        $isSimple       = com_gParam(   'simple', 0, 0 );     
        $quantity       = com_gParam(   'qty', 0, 0 );
        $product_id     = com_gParam(   'product_id', 0, 0 );
        $product_sku    = com_gParam(   'product_sku', 0, "" );
        $attribute      = com_gParam(   'attribute', 0, [] );
        $category_dept  = com_gParam(   'category_dept', 0, '' );
        $quantity = intval( $quantity );
        /* check product sku post */
        if( $product_id && $product_sku ){
            /* check quantity post and must be > 0 */
            if( $quantity ){
                $this->load->model('cpcatalogue/Productmodel', 'Prod');
                $productDetails = $this->Prod->details( $product_sku );                
                /* check is prod exist */
                if( $productDetails ){
					$parent_sku = $productDetails['product_sku'];					
                    if( $productDetails['product_type_id'] == 2 ){
                        $opt = [];
                        $opt[ 'attribute' ] = $attribute;
                        $opt[ 'product_id' ] = $product_id;
                        $opt[ 'product_sku' ] = $product_sku;
                        $opt[ 'productDetails' ] = $productDetails;
                        $product_id = $this->Productmodel->getConfigProdFromAttr( $opt );                        
                        $productDetails = $this->Prod->details( $product_id );                        
                    }                    
                    $this->_getProductPriceAsPerCompPriceList( $productDetails );
                    $prodDet = [
									'is_sap' => $productDetails['is_sap'], 
									'parent_sku' => $parent_sku,
									'product_id' => $productDetails['product_id'],
                                    'category_id' => $productDetails['category_id'],
                                    'product_sku' => $productDetails['product_sku'],
                                    'product_name' => $productDetails['product_name'],                                    
                                    'product_imgae' => $productDetails['product_image'],
                                    'product_alias' => $productDetails['product_alias'],
                                    'product_point' => $productDetails['product_point'],
                                    'product_price' => $productDetails['product_price'],
                                    'product_type_id' => $productDetails['product_type_id'],
                                    'product_description' => $productDetails['product_description'],
                            ];                    
                    $addDet[ 'product' ] = $prodDet;
                    $addDet['product_sku'] = $product_sku;
                    $addDet[ 'quantity' ] = $quantity;
                    $addDet[ 'attribute' ] = $attribute;
                    $addDet[ 'category_dept' ] = $category_dept;                    
                    $out = $this->Cartmodel->addToCart( $addDet );                    
                    if( $out['success'] ){
                        $output['message'] = $out['message'];
                        $output['cartRowId'] = $out['cartRowId'];
                    }else{
                        $output['message'] = $out['message'];
                    }
                } else {
                    if( !$isSimple ){
                        $output['message'] = 'Product attribute does not match';
                    }
                }
            }else{
                $output['message'] = 'Quantity cannot be zero or null';
            }
        }
        if( ! $this->input->is_ajax_request() ){
            $this->session->set_flashdata('message', $output['message']);
            redirect('wardrobe');
            exit;
        }
        extract( $this->Cartmodel->getCartVariables() );
        $cartContents = $this->cart->contents();
        if ( $this->user_type == USER ) {
            $output['wardrobecart'] = $this->load->view( 'ajax/user-mini-cart', [ 'cartContents' => $cartContents ] , TRUE);
        }
        $output['cartTotal'] = $cartTotal;
        $output['itemTotal'] = $itemTotal;
        if( $this->input->is_ajax_request() ){
            echo json_encode( $output );
            exit();
        }        
    }    
}
