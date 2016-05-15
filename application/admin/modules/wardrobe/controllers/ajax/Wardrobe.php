<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class wardrobe extends Adminajax_Controller {

    function __construct() {

        parent::__construct();        
        $this->data = [];        
    }
    /* return products assigned to company */
    function getCompProd(){
        $this->load->model('company/Companymodel', 'Companymodel');
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
        $offset     =   com_gParam( 'offset', true, 0 );
        $perpage    =   16;
        $search_param  =   [];
        $search_param[ 'limit' ]        =   $perpage;
        $search_param[ 'offset' ]       =   $offset;
        $search_param['comp_code']      =   $company_code;
        $search_param['company_code']   =   $company_code;
        $search_param['category']       =   com_gParam('category' , true, 0) ;        
        $config['js_rebind']        =   '';
        $config['request_type']     =   'get';
        $config['per_page']         =   $perpage;
        $config['html_container']   =   'prod-list-table';
        $config['cur_page']         =   $search_param[ 'offset' ];
        $config['base_url']         =   base_url().'wardrobe/ajax/wardrobe/getCompProd/';
        $config['total_rows']       =    $this->Companymodel->getCompAssignProdCount( $search_param )[ 'ttl' ];
        $config['additional_param'][]['js'] = ['category' => "$('#category').val()"];
        $config['additional_param'][]['js'] = ['prodName' => "$('#prodName').val()"];
        $this->data['pagination']   =   com_ajax_pagination($config);
        //list all Products        
        $search_param[ 'offset' ]   =   $offset;
        $search_param[ 'limit' ]    =   $perpage;
        $this->data['products'] = [];        
        $this->data['current_offset'] = $offset;
        $this->data['category_dept'] = $search_param['category'];
        $this->data['products'] = $this->Companymodel->getLoginCompProdAlloc( $search_param );        
        $this->data['current_offset'] = $search_param[ 'offset' ];
        $this->data['error_message'] = 'Product does not found.';
        $this->data['user_type'] = $this->user_type;
        // 'csrf_hash' => $this->security->get_csrf_hash(),
        $output = [
                    'success' => 1,
                    'html' => $this->load->view('ajax/user-allocated-product', $this->data, true),
                ];        
        echo json_encode( $output );
        exit;
    }

    /* return products assigned to user as per department */
    function getUserProd(){
    	$offset = $this->input->get('offset');
		$users_assigned_dept = $this->input->get('dept_id');
        $this->data['products'] = [];
        $this->data['pagination'] = '';
        $users_assigned_dept = (int)$users_assigned_dept;
        $this->data['category_dept'] = $users_assigned_dept;
        if( $users_assigned_dept) {
            $this->load->model('product_allocation/Userprodallocationmodel',  'Userprodallocationmodel');
            $perpage = 16;
            $param = [];
            $param['dept_id'] = $users_assigned_dept;
            $param['limit'] = ['offset' => $offset, 'limit' => $perpage];
            $this->data['products'] = $this->Userprodallocationmodel->getLoginUserProdAlloc($param);            
            $config['total_rows'] = $this->Userprodallocationmodel->getLoginUserProdCount(['dept_id' => $param['dept_id']]);
            $config['cur_page'] = $offset;
            $config['request_type']= 'get';
            $config['per_page']    = $perpage;
            $config['additional_param'][]['js'] = ['dept_id' => "$('#department').val()"];
            $config['base_url']    = base_url().'wardrobe/ajax/wardrobe/getUserProd/';
            $config['html_container'] = 'prod-list-table';            
            
            $this->data['pagination'] = com_ajax_pagination($config);
        }
        $this->data['user_type'] = $this->user_type;
        $this->data['current_offset'] = $offset;
        $this->data['error_message'] = 'No product assign to department  ';
    	$this->data['table_prod_view'] = $this->load->view('ajax/user-allocated-product', $this->data, true);

    	$output = [
					'success' => 1,
					'html' => $this->data['table_prod_view'],
    				];
    	echo json_encode($output);
    	exit;
    }

    /* check product price as per company related prices */
    private function _getProductPriceAsPerCompPriceList( &$productDetRef ){
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $userCompanyCode = $this->flexi_auth->get_user_custom_data( 'upro_company');
        $companyDet = $this->Companymodel->getCompanyDetailFromCompanyCode( $userCompanyCode );
        $companyPriceListNum = $companyDet[ 'price_list' ];
        $productPriceListPrice = $this->Productmodel->getProductPriceFromPriceList( $companyPriceListNum, $productDetRef['product_sku']);
        if( $productPriceListPrice ){
            $productDetRef['product_price'] = $productPriceListPrice;
        }
    }

    /*  provide products for shoping with pagination 
        Used earlier not shift to without ajax controller 
        action cuser_view_product
     */
    private function getProductViewForShop( ){
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $output = [
                'success' => 0,
                'html' => "No Product Details",
        ];
        $deptId = $this->input->get('deptId');
        $productRef = $this->input->get('product_ref');
        /* product main details fetched */        
        $product_main_details = $this->Productmodel->details( $productRef );        
        if( $product_main_details ) {
            $qtyFromProdCheck = false;
            /* get product attributes as per assign attribute set id */
            $prodids = [ $product_main_details['product_id'] => $product_main_details ];
            $prodAttr = $this->Productmodel->prodAttributes( $prodids );            
            $this->_getProductPriceAsPerCompPriceList( $product_main_details );
            $this->load->model('product_allocation/Userprodallocationmodel',  'Userprodallocationmodel');
            $this->load->model( 'Othrcommonconfigmodel');
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
            $prodAvailCheckDet = com_checkProductAvailability( $params );            
            $extraMessage = '';
            if( $prodAvailCheckDet && $prodAvailCheckDet[ 'success' ] ) {
                $userAttrAssignment[0]['quantity'] = $prodAvailCheckDet[ 'allowedQty' ];
                if( $prodAvailCheckDet[ 'allowedQty' ] == 0 && $prodAvailCheckDet[ 'withCalculation' ]){
                    $extraMessage = 'You have reach allowed quantity limit for this product';
                } else if ( $prodAvailCheckDet[ 'allowedQty' ] == 0) {
                    $extraMessage = 'Product quantity limit not set';
                }
            }
            
            if( !$extraMessage && $product_main_details[ 'product_type_id' ] == 2 ){
                $extraMessage = "Its config product pending";
            }
            $prodSelection = [
                                'deptId' => $deptId,
                                'days_limit' => $days_limit,
                                'userAssignments' => $userAttrAssignment,
                                'product_main_details' => $product_main_details,
                                'prodAttrb' => $prodAttr[ $product_main_details['product_id'] ][ 'prdAttrLblOpts' ], 
                            ];
            $output = [
                    'success' => 1,
                    'extraMessage' => $extraMessage,
                    'prodName' => $product_main_details['product_name'],
                    'prodImage' => com_get_product_image($product_main_details['product_image'], 250, 250),
                    'prodDesc' => $product_main_details['product_description'],
                    'prodPrice' => $product_main_details['product_price'],
                    'prodPoint' => $product_main_details['product_point'],
                    'prodAttrHtml' => $this->load->view('ajax/add-product-cart', $prodSelection , true),
            ];            
        }
        echo json_encode($output);
        exit;        
    }

    /* Add to cart functionality */
    function addToCart( ){
        $this->load->model('cart/Cartmodel', 'Cartmodel');
        /* Default output */
        $output = [];
        $output['status'] = 0;        
        $output['cartRowId'] = 0; 
        $output['totalCart'] = '';
        $output['mincarthtml'] = '';
        $output['message'] = 'Product does not found';
        $addDet = [ ];        
        if ( $this->user_type == USER ) {
            /* postparam */
            $postParams = [];
            parse_str($this->input->post( 'form-data' ), $postParams);
            $quantity       =   com_arrIndex($postParams, 'quantity', 0 );
            $product_id     =   com_arrIndex($postParams, 'product_id', 0 );
            $product_sku    =   com_arrIndex($postParams, 'product_sku', 0 );
            $attribute      =   com_arrIndex($postParams, 'attribute', [] );
            $category_dept  =   com_arrIndex($postParams, 'category_dept', '' );
        }else if ( $this->user_type == CMP_ADMIN ) {
            $quantity       = com_gParam(   'qty', 0, 0 );
            $product_id     = com_gParam(   'product_id', 0, 0 );
            $product_sku    = com_gParam(   'product_sku', 0, "" );
            $attribute      = com_gParam(   'attribute', 0, [] );
            $category_dept  = com_gParam(   'category_dept', 0, '' );
        }
        $quantity = intval( $quantity );
        /* check product sku post */
        if( $product_id && $product_sku ){
            /* check quantity post and must be > 0 */
            if( $quantity ){
                $this->load->model('cpcatalogue/Productmodel', 'Prod');
                $productDetails = $this->Prod->details( $product_sku );
                /* check is prod exist */
                if( $productDetails ){
                    $this->_getProductPriceAsPerCompPriceList( $productDetails );
                    $prodDet = [    'product_id' => $productDetails['product_id'],
                                    'category_id' => $productDetails['category_id'],
                                    'product_sku' => $productDetails['product_sku'],
                                    'product_name' => $productDetails['product_name'],                                    
                                    'product_alias' => $productDetails['product_alias'],
                                    'product_point' => $productDetails['product_point'],
                                    'product_price' => $productDetails['product_price'],
                                    'product_type_id' => $productDetails['product_type_id'],
                                    'product_description' => $productDetails['product_description'],
                            ];                    
                    $addDet[ 'product' ] = $prodDet;                    
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
                }
            }else{
                $output['message'] = 'Quantity cannot be zero or null';
            }
        }
        if ( $this->user_type == CMP_ADMIN ) {
            $this->session->set_flashdata('MESSAGE', $output['message']);
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

    /* configProdOption */
    function confProdAttr(){
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $form_data = [];
        parse_str($this->input->get("form_data"), $form_data);        
        $next_attr = $this->input->get("next_attr");
        $holdOnlyFilledAttributes = array_filter( $form_data[ 'attribute' ] );
        $holdOnlyFilledAttributes[ $next_attr  ] = '';        
        $prodAttrDet = $this->Productmodel->getProdAttribSetAndSetAtrributes(  $form_data[ 'product_id' ],
                                                                array_keys( $holdOnlyFilledAttributes ));		
        $nextAttrDet = '';
        $param = [];        
        foreach($prodAttrDet as $selAttrIndex => $selAttrDet){			
            if( $selAttrDet[ 'id' ] ==  $next_attr){
                $nextAttrDet = $selAttrDet;
                unset( $holdOnlyFilledAttributes[ $next_attr ] );
            } else if( $selAttrDet[ 'is_sys' ] ){
                $param[ 'where' ][ $selAttrDet[ 'sys_label' ] ] = $holdOnlyFilledAttributes[ $selAttrDet[ 'id' ] ];
                unset( $holdOnlyFilledAttributes[ $selAttrDet[ 'id' ] ] );
            }
        }
        $prodWithAttrDet = $this->Productmodel->getProdWithAttrDetail($form_data[ 'product_id' ], $param);        
        $prodWithAttrDetObj = com_arrayToObject( $prodWithAttrDet );
        $prodWithAttrDetObj = ( array )$prodWithAttrDetObj;        
        /** Include path For PLINQ**/
        set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/plinq/Classes');
        /** PHPLinq_LinqToObjects */
        require_once 'PHPLinq/LinqToObjects.php';
        $filteredProdIds = [];
        $distinctProdIds = com_makelist($prodWithAttrDet, 'product_id', 'product_id', FALSE );        
        foreach($holdOnlyFilledAttributes as $leftOthrAttrIndex => $leftOthrAttrVal){
                $filteredProdIds = [];
                foreach ($distinctProdIds as $prodKind => $prodKVal) {
                    $searchProd = new stdClass();
                    $searchProd->product_id = $prodKVal;
                    $searchProd->attribute_id = $leftOthrAttrIndex;
                    $searchProd->attribute_value = $leftOthrAttrVal;					
                    $filResultStatus = from('$filterResult')->in($prodWithAttrDetObj)
                                    ->contains( $searchProd );
                    if( $filResultStatus ){
                        $filteredProdIds[ $prodKVal ] = $prodKVal;
                    }
                }
                $distinctProdIds = $filteredProdIds;
        }        
        $nextAttrOptsHtml = '';
        if( $next_attr ){
            $holdAllAttrWithNextAttr = from('$arrDet')->in($prodWithAttrDetObj)
                                            ->where('$arrDet => $arrDet->attribute_id == "'.$next_attr.'"')                                            
                                            ->select('$arrDet');			
            $distinctNextAttrOpts = [];
            foreach( $distinctProdIds as $stackProdK => $stackProdV){
                $holdFilteredAttrWithProd = from( '$arrDet' )
                                            ->in($holdAllAttrWithNextAttr)
                                            ->where('$arrDet => $arrDet->product_id == "'.$stackProdV.'"')
                                            ->select('$arrDet');				
                if( $holdFilteredAttrWithProd ){
                    foreach($holdFilteredAttrWithProd as $holdStackIndex => $holdStackDet ){
                        $distinctNextAttrOpts[ ] = $holdStackDet->attribute_value;
                    }
                }
            }            
            if( $distinctNextAttrOpts ){
                $param = [];
                $param[ 'select' ]      = 'id,option_text';
                $param[ 'from_tbl' ]    = 'attributes_set_attributes_option';
                $param[ 'where'][ 'in' ][ 0 ] = 'id';
                $param[ 'where'][ 'in' ][ 1 ] = $distinctNextAttrOpts;
                $attribOpt = $this->Productmodel->get_all( $param );                
                $nextAttrOptsHtml = com_makelistElem($attribOpt, 'id', 'option_text', TRUE, 'Select '.$nextAttrDet[ 'label' ]);                
            }
        }
        $ouput = [
                    'success'   => 1,
                    'html'      => $nextAttrOptsHtml
                ];
        echo json_encode( $ouput );
        exit();
    }
}
