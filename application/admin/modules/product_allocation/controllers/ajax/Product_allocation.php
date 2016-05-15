<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_allocation extends Adminajax_Controller {

	function __construct() {
        parent::__construct();
        $this->data = [];
        $this->data['perpage'] = 15;
    }

    /*
        Old use
    */
    function getCompDept( $comp_id ){
    	$this->load->model('company/Relatedcompdeptmodel', 'relatedmodel');
        $this->data['policy'] = $this->relatedmodel->getDeptList($comp_id);
        echo json_encode( [ 'html' =>  com_makelistElem($this->data['policy'], 'id', 'name', 1, 'Department') , 'success' => 1 ] );
        exit();
    }

    /* old use */
    function _prod_dept_user( $dept_id, $user_id = false){
        $this->load->model('Userprodallocationmodel');
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');

        /* 
            Product attribute options
         */
        $productAttr = [];
        /*
            POST products
         */
        $selected_prods = $this->input->post("products");
        /*
            Users product allocation fetched from db 
         */
        $user_prod_count = $this->Userprodallocationmodel->getDeptUserProdCount( );
        /*
            Product allocated to company department
        */
        $this->data['products'] = $this->Companymodel->getLoggedCmpDeptProdDet($dept_id);                

        /* Make Next Hidden Products 
           Calulcate Sum of Products as per POST           
        */

       /*
            check is user selected via post for products
        */
        if( $user_id) {
            if( !isset($selected_prods[$dept_id][$user_id]) ) {
                $this->load->model('Userprodallocationmodel');
                $selected_prods[$dept_id][$user_id] = com_makelist( $this->Userprodallocationmodel->getCompUserProdAlloc(), 
                                                                    'product_code', 'quantity' , 0);
            }

            $productAttr = $this->Productmodel->prodAttributes($this->data['products']);
            /*
                passed user id allocated to new variable
             */
            $current_dept_user_prod = $selected_prods[$dept_id][$user_id];
            $this->data['current_dept_user_prod'] = $current_dept_user_prod;
        }

        $hidden_prods = [];
        $prod_hidden_index = 0;
        
        /*
            If no products then empty array 
         */
        if( is_array($selected_prods) ) {
            $depts = array_keys( $selected_prods );            
            foreach($depts as $dep_key ) {
                foreach($selected_prods[$dep_key] as $user_key => $prod_detail ) {
                    foreach( $prod_detail as $prod_key => $prod_qty ) {
                        /* && $dep_key != $dept_id  */
                        if( !empty($prod_qty) ) {
                            $hidden_prods['products['.$dep_key.']['.$user_key.']['.$prod_key.']' ] = $prod_qty;
                            $prod_hidden_index++;
                        }                                            
                    }
                }
            }
            /*
                From post the db selected keys replaced , so that post remain exist
             */            
            if( isset($selected_prods[$dept_id]) ){
                $user_prod_count = array_replace($user_prod_count, $selected_prods[$dept_id]) ;
            }
        }else{
            $selected_prods = [];
        }        
                
        /* Make Sum Of Product Count From Filtered Post selection */
        $dept_user_prod_count = [];
        foreach( $user_prod_count as $users_key => $prod_detail ) {
                foreach ($prod_detail as $pkey => $psum) {
                    $dept_user_prod_count[$pkey] = $psum + com_arrIndex( $dept_user_prod_count, $pkey, 0 );
                }
        }

        $this->data['productAttr'] = $productAttr;
        $this->data['hidden_prods'] = $hidden_prods;
        $this->data['dept_user_prod_count'] = $dept_user_prod_count;
    }

    /*
        It called from bonus stock distribution view on department selection.
     */
    function getDeptUserProdDistCombView( ){
        
        /* POST Qtys */
        $qtys = com_gParam("quantity", FALSE, []);

        $this->load->model('company/Companymodel', 'Companymodel');
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $store_changed = $this->input->post( 'store_changed' );
        if( $store_changed ){
			$qtys = [];
		}
        $store_id = $this->input->post( 'company_store' );
        $dept_id = $this->input->post( 'department' );
        $products = $this->Companymodel->getLoggedCmpDeptProdDet( $dept_id );
        $dept_product_sku = array_column($products, 'product_sku');
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}                
        $hidden_qty = [];
        /* loop on days with dept id as reference */
        foreach ($qtys as $deptId => $prodDetail) {
            /* loop on days with dept id as reference */
            foreach ($prodDetail as $prodDetailSku => $userQtyDet) {
                /* loop on user days details with user id as key */
                foreach ($userQtyDet as $userQtyDetUserId => $qtyVal) {
                    if( isset( $qtys[$deptId][$prodDetailSku][$userQtyDetUserId] ) 
                        && $qtys[$deptId][$prodDetailSku][$userQtyDetUserId] ){
                        $hidden_qty["quantity[$deptId][$prodDetailSku][$userQtyDetUserId]"] = 
                                    $qtys[$deptId][$prodDetailSku][$userQtyDetUserId];
                    }
                }
            }
        }
        $param[ 'store_id'] = $store_id;
        $param[ 'company_code'] = $company_code;
        $param[ 'product_sku']  = $dept_product_sku;
        $company_product_stock = com_makelist($this->Companymodel->getCompanyStock( $param ), 'product_code', 'ttl', FALSE) ;
        
        $this->data['company_issued_stock'] = com_makelist($this->Companymodel->getCompanyIssuedStock( $param ), 'product_code', 'ttl', FALSE);
        $this->data['company_product_stock'] = $company_product_stock;
        $this->data['dept_id']      = $dept_id;
        $this->data['users']        = $this->Companymodel->getCompDeptUser();
        $this->data['products']     = $products;
        $this->data['hidden_qty']   = $hidden_qty;
        $this->data['store_changed']= $store_changed;
        $output = [
                    'success' => 1,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                    'html' => $this->load->view('ajax/user-bonus-product-distribution', $this->data, true),
                ];
        echo json_encode( $output );
        exit;        
    }


    /*
        It called from product allocation view on department selection.
     */
    function getDeptUserProdCombView( ){
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $this->load->model('Userprodallocationmodel');
        $this->load->model('company/Companymodel', 'Companymodel');
        $params = [];
        $params[ 'select'   ] = 'config_id, config_label';
        $params[ 'from_tbl' ] = 'other_common_config';
        $params[ 'where'    ][ 'type_ref' ] = 'days';
        $params[ 'order'    ][ 0 ]   = 'config_id';
        $periodDays = $this->Companymodel->get_all( $params );
        $period = com_makelist($periodDays, 'config_id', 'config_label', FALSE);
        $store_id = $this->input->post( 'company_store' );
        $dept_id = $this->input->post( 'department' );
        /* POST Days */
        $days = $this->input->post("days");
        /* POST Qtys */
        $qtys = $this->input->post("quantity");
        /* POST Products */
        $selected_prods = $this->input->post("product");
        /* POST User policy by pass */
        $user_prods_by_pass = $this->input->post("users_no_policy");
        //com_e($user_prods_by_pass, 1);
        /* check posted products for requested department */
        if( !isset( $selected_prods[ $dept_id ] ) ){
            $deptUsersProducts = $this->Userprodallocationmodel->getDeptUsersAllocProducts( $dept_id );
            if( $deptUsersProducts ){
                /* make array from selected products */
                $param = [  'days'  =>  $days,
                            'qtys'  =>  $qtys,
                            'selected_prods' => $selected_prods,
                            'deptUsersProducts' =>  $deptUsersProducts,
                            'user_prods_by_pass' =>  $user_prods_by_pass
                        ];
                $this->_makePostDayQtyProds( $param );
                /*  param variable extracted here to overwrite the previous variables,
                    in param we use pass by reference to preserve data from lossing during overwrite  */
                extract( $param );
            }
        }        
        $hidden_qty = [];
        $hidden_days = [];
        $hidden_prods = [];
        $hidden_by_pass = [];
        $deptUsers = [];
        /* Products post with dept id as a index */        
        if( $selected_prods ){
            /* loop on product department id as reference */
            foreach( $selected_prods as $deptId => $prodDetail) {
                /* loop on product details */
                foreach ($prodDetail as $prodDetailSku => $prodDetailAttrbs) {
                    /* loop on product attributes with attribute type as key */
                    foreach ($prodDetailAttrbs as $prodDetailAttrbsGrpType => $prodDetailAttrbsDetail) {
                        /* loop on product attribute detail */
                        foreach($prodDetailAttrbsDetail as $attributeId => $attributeVal ){
                            if( !is_array($attributeVal) ){
                                $hidden_prods["product[$deptId][$prodDetailSku][$prodDetailAttrbsGrpType][$attributeId]"] = $attributeVal;
                            }else{
                                foreach ($attributeVal as $userAttributeKey => $attributeValVal) {
                                    $hidden_prods["product[$deptId][$prodDetailSku][$prodDetailAttrbsGrpType][$attributeId][$userAttributeKey]"] = $attributeValVal;
                                }
                            }
                        }                        
                    }
                }
            }
            /* loop on days with dept id as reference */
            foreach ($days as $deptId => $prodDetail) {
                /* loop on days with dept id as reference */
                foreach ($prodDetail as $prodDetailSku => $userDaysDet) {
                    /* loop on user days details with user id as key */
                    foreach ($userDaysDet as $userDaysDetUserId => $daysVal) {
                        if( isset( $user_prods_by_pass[$deptId][$prodDetailSku][$userDaysDetUserId] ) &&  
                            $user_prods_by_pass[$deptId][$prodDetailSku][$userDaysDetUserId] ) {
                            $hidden_by_pass["users_no_policy[$deptId][$prodDetailSku][$userDaysDetUserId]"] = 1;
                        }
                        $hidden_days["days[$deptId][$prodDetailSku][$userDaysDetUserId]"] = $daysVal;
                        if( isset( $qtys[$deptId][$prodDetailSku][$userDaysDetUserId] ) ){
                            $hidden_qty["quantity[$deptId][$prodDetailSku][$userDaysDetUserId]"] = 
                                        $qtys[$deptId][$prodDetailSku][$userDaysDetUserId];
                        }
                    }
                }
            }
        }
        /* Product allocated to department retrieved from Db. */
        $this->data['period'] = $period;
        $this->data['dept_id'] = $dept_id;
        $this->data['hidden_qty'] = $hidden_qty;
        $this->data['hidden_days'] = $hidden_days;
        $this->data['hidden_prods'] = $hidden_prods;
        $this->data['hidden_by_pass'] = $hidden_by_pass;
        $this->data['users'] = $this->Companymodel->getCompDeptUser();
        $this->data['products'] = $this->Companymodel->getLoggedCmpDeptProdDet( $dept_id );
        $this->data['prodAttr'] = $this->Productmodel->prodAttributes( $this->data['products'] );        
        $output = [
                    'success' => 1,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                    'html' => $this->load->view('ajax/user-product-allocation', $this->data, true),
                ];
        echo json_encode( $output );
        exit;
    }
    
    /* 
        inner function to make posted data array 
        array ex
        $days = [ 'department id' => [ 'product sku' => [ 'user id' => 'days value' ] ] ];
        $qtys = [ 'department id' => [ 'product sku' => [ 'user id' => 'qtys value' ] ] ];
        $products = [ 'department id' => [ 'product sku' => [   'product' => [ 'attribute id' => 'attribute value',]
                                                                'user' => [ 'attribute id' => [ 'user id' => 'attribute value'  ]
                                                            ]  ] ];
        passed via reference array
        $days,          $qtys,          $selected_prods,        $deptUsersProducts
    */
    private function _makePostDayQtyProds( &$postedAndDbdata ){
        
        extract( $postedAndDbdata, EXTR_REFS);
        foreach ($deptUsersProducts as $deptUsersProductsArrkey => $userDet) {
            /* make qtys */
            $qtys[ $userDet['department_id'] ][ $userDet[ 'product_code' ] ][ $userDet[ 'user_id' ] ] = $userDet[ 'quantity' ];
            /* make days */
            $days[ $userDet['department_id'] ][ $userDet[ 'product_code' ] ][ $userDet[ 'user_id' ] ] = $userDet[ 'days_limit' ];
            /* make no policy */
            $user_prods_by_pass[ $userDet['department_id'] ][ $userDet[ 'product_code' ] ][ $userDet[ 'user_id' ] ] = $userDet[ 'is_by_pass' ];
            /* make prods */
            if( $userDet['is_user_related'] ) {
                $selected_prods[ $userDet['department_id'] ]
                                [ $userDet[ 'product_code' ] ][ 'user' ][ $userDet[ 'attribute_id' ] ]
                                [ $userDet[ 'user_id' ] ] = $userDet[ 'attribute_value' ];
            } else {
                $selected_prods[ $userDet['department_id'] ]
                                [ $userDet[ 'product_code' ] ][ 'product' ][ $userDet[ 'attribute_id' ] ] = $userDet[ 'attribute_value' ];                
            }
        }
    }

    /*
        It called from product allocation on user click
        Old implementaion part
    */
    private function getUserDeptProd() {
        
        $dept_id = $this->input->post( 'department' );
        $user_id = $this->input->post( 'selected_user' );
        $this->data['dept_id'] = $dept_id;
        $this->data['user_id'] = $user_id;

        $this->_prod_dept_user( $dept_id, $user_id);        
        
        $output = [
                    'success' => 1,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                    'html' => $this->load->view('ajax/allocation-user-prod-table', $this->data, true),
                ];

        echo json_encode( $output );
        exit;
    }
    

    /*
        it provide the user details 
        worked in company user product allocation
        Old implementaion part
     */
    private function getDeptUser( ){
        $this->load->model('company/Companymodel', 'Companymodel');
        $users = $this->Companymodel->getCompDeptUser();

        $dept_id = $this->input->post( 'department' );
        $this->_prod_dept_user( $dept_id );        


        $user_list_html = '';
        foreach ($users as $user_det) {
            $user_list_html .= '<li data-userid="'.$user_det['uacc_id'].'" 
                                    class="list-group-item ">'.ucfirst( $user_det['uacc_username'] ).'</li>';
        }
        $user_list_html = '<ul id="users-comp-list" class="list-group"  >'.$user_list_html.'</ul>';

        $user_list_html .= ' <script>
                                $(document).ready(function() {
                                    $("#users-comp-list li").on("click", function (e) {
                                        $("#users-comp-list li").removeClass("active");
                                        $(this).addClass("active");
                                        $("#selected_user").val( $(this).attr("data-userid") );
                                        getDeptUserProducts();
                                    })                        
                                });
                            </script>';

            
        $output = [ 'success' => 1, 
                    'user_html' => $user_list_html, 
                    'prod_html' => form_hidden($this->data['hidden_prods']).'<div class="alert alert-danger"> Please select user. </div>', 
                    'csrf_hash' => $this->security->get_csrf_hash()];
        echo json_encode( $output );
        exit;        
    }

    /*
        It provide product to company view selection 
        and send the product with pagination
     */
    function getCompProd(){
		
		if( $this->input->post('ajax', true) ){
			$form_data = [];
			parse_str($this->input->post("form_data"), $form_data);
			$offset = $this->input->post("offset");
			$comp_id = $form_data['selected_company'];
			$this->data['exact_match'] = com_arrIndex($form_data, 'exact_match', 0);
			$this->data['search_product'] = trim($form_data['search_product']);
			$this->data['selected_prods'] = com_arrIndex($form_data , 'products', []);
            $comp_id = com_arrIndex($form_data , 'selected_company',0);
			$config['cur_page'] = $offset;
		}else{
			$this->data['exact_match'] = '';
			$this->data['search_product'] = '';			
			$selected_prods = $this->input->post("products");
            $comp_id = com_gParam('selected_company',0, 0);
			$offset = com_gParam('offset', 0, 0);

            if( !isset($selected_prods[$comp_id]) ) {
                $this->load->model('company/Companymodel', 'Companymodel');
                $selected_prods[$comp_id] = array_column($this->Companymodel->getCompAssignProd($comp_id), 'product_code');             
            }
            $this->data['assigned_prods'] = 0;

            if( !is_array($selected_prods) ) {
                $selected_prods = [];
            }

            $this->data['selected_prods'] = $selected_prods;
		}
        
        $keywords = '';
        $this->data['comp_id'] = $comp_id;
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $search_param = [];
        if( !empty( $this->data['search_product'] ) ){
			$search_param[ 'exact_match' ]  = $this->data['exact_match'];
			$search_param[ 'prodSkuName' ]	= $this->data['search_product'];			
		}
        $config['total_rows'] = $this->Productmodel->countAllProducts( $search_param );		
        $search_param[ 'offset' ] = $offset;
        $search_param[ 'limit' ] = $this->data['perpage'];
        $this->data['products'] = $this->Productmodel->listAllProducts( $search_param );
		
        //pagination configuration
        $config['html_container'] = 'related-product-table';
        $config['base_url']    = current_url();
        $config['per_page']    = $this->data['perpage'];
        $config['form_serialize'] = 'comp-product-allot';

        $this->data['selected_posted_sku'] = [];
        $this->data['pagination'] =  com_ajax_pagination($config);
        
        $hidden_sku = [];        
        $selected_posted_sku = [];
        $companies = array_keys( $this->data['selected_prods']);
        foreach($companies as $comp_key ){
            $existed_sku = [];
            if($comp_key == $comp_id){
                $existed_sku = array_column($this->data['products'], 'product_sku');
            }            
            foreach ($this->data['selected_prods'][$comp_key] as $prod_key) {

                if(in_array($prod_key, $existed_sku)){
                    $selected_posted_sku[] = $prod_key;
                }else{
                    $hidden_sku[]['products['.$comp_key.'][]'] = $prod_key;                    
                }                
            }
        }
        $this->data['hidden_sku'] = $hidden_sku;
        $this->data['selected_posted_sku'] = $selected_posted_sku;
        
        $output = [
        			'html' => $this->load->view('ajax/allocation-prod-table', $this->data, true),
        			'success' => 1,
					'csrf_hash' => $this->security->get_csrf_hash(),
    			];

        echo json_encode( $output );
        exit;
    }
}
