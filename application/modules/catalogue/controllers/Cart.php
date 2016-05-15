<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cart extends Front_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Cartmodel');
        $this->load->model('Productmodel');
        $this->data = [];
    }

	function find_coupon(){
		/* Active and is available as per date and Name checked here */
		$coupon = $this->Cartmodel->get_coupon( $this->input->post('coupon') );		
		$return_success = false;
		if( $coupon ){
			$curr_user_id = $this->flexi_auth->get_user_id();
			$coupon_customer = [];
			if( $coupon[ 'user_style' ]  == 'S' && !empty(  $coupon[ 'customers' ] ) ){
				$customers = explode(',', $coupon[ 'customers' ]);
				if( in_array($curr_user_id , $customers) ){
					$return_success	 = true;
					$coupon_customer = $curr_user_id;
				}
			} else  if( $coupon[ 'user_style' ]  == 'A' ){ /* By all user */
					$coupon_customer = $curr_user_id;
					$return_success	 = true;
			} else if( $coupon[ 'user_style' ]  == 'N' ){ /* By specific user */				
					$coupon_customer = '';
					$return_success	 = true;				
			}
			if( $return_success ){
				$coupon_used_status = $this->Cartmodel->get_coupon_used_count( $coupon[ 'id' ], $coupon_customer);				
				$return_success = false;
				if( $coupon[ 'use_time' ] > $coupon_used_status ){
					$return_success = true;
					$this->session->set_userdata('coupon', $coupon);
				}
			}
		}
		if( !$return_success ){
			$this->form_validation->set_message('find_coupon', 'Coupon is not available.');
		}
		return $return_success;
		
	}
	
    function index() {
        $this->load->library('form_validation');
        
        $inner = array();
        $inner['donationType'] = array('' => "", 'round' => "Round off amount", "insert" => "Enter a different amount");
		$coupon_tried = $this->input->post( 'coupon_applied' );
		$coupon = [];
		$inner['cart'] = $this->cart->contents();
    	$this->form_validation->set_rules('qty[]', 'Quantity required', 'trim|required');
		if( $coupon_tried == 'Apply coupon' ){
			$this->form_validation->set_rules('coupon', 'Coupon required', 'trim|required|callback_find_coupon');
		}
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		if ($this->form_validation->run()) {
			if ($this->input->post('qty')) {
				$updateCart = $this->Cartmodel->updateCart();
				$this->session->set_flashdata('message', $updateCart[ 'msg' ]);
				redirect('catalogue/cart');
			}
		}
		
        $inner['logged_user_id'] = $this->flexi_auth->get_user_id();
        $inner['coupon_details'] = $this->session->userdata('coupon' );
        $inner['coupon_discounted_amount'] = $this->session->userdata('coupon_discounted_amount' );
        self::updateDonationAmount();
        $inner['INFO'] = (!isset($inner['INFO'])) ? $this->session->flashdata('message') : $inner['INFO'];
        //echo total_after_calcualtion();
        $shell['contents'] = $this->load->view("cart", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/cartSubpage", $shell);
    }

  

    function updateDonationAmount()
    {
        if($this->session->userdata("donation")!=null && $this->session->userdata("donationMode")=="round")
        {
            $this->session->set_userdata(array('donationAmount'=>round( ceil($this->cart->total()) - $this->cart->total()  ,2)));
            if($this->session->userdata('donationAmount')==0)
            {
                 self::uns_charity();
                 $this->session->set_flashdata('message', 'Round Off for this amount is not possible.');
            }
        }
    }
    
    function getUserProd() {
        $offset = $this->input->get('offset');
        $users_assigned_dept = $this->input->get('dept_id');
        $this->data['user_dept_prod'] = [];
        $this->data['pagination'] = '';
        $users_assigned_dept = (int) $users_assigned_dept;
        if ($users_assigned_dept) {
            $this->load->model('Userprodallocationmodel');
            $perpage = 9;
            $param = [];
            $param['dept_id'] = $users_assigned_dept;
            $param['limit'] = ['offset' => $offset, 'limit' => $perpage];
            $this->data['user_dept_prod'] = $this->Userprodallocationmodel->getLoginUserProdAlloc($param);

            $config['total_rows'] = $this->Userprodallocationmodel->getLoginUserProdCount(['dept_id' => $param['dept_id']]);
            $config['cur_page'] = $offset;
            $config['request_type'] = 'get';
            $config['per_page'] = $perpage;
            $config['additional_param'][]['js'] = ['dept_id' => "$('#department').val()"];
            $config['base_url'] = base_url() . 'wardrobe/ajax/wardrobe/getUserProd/';
            $config['html_container'] = 'prod-list-table';

            $this->data['pagination'] = com_ajax_pagination($config);
        }
        $this->data['table_prod_view'] = $this->load->view('ajax/user-allocated-product', $this->data, true);

        $output = [
            'success' => 1,
            'html' => $this->data['table_prod_view'],
        ];
        echo json_encode($output);
        exit;
    }

    private function _getProductPriceAsPerCompPriceList(&$productDetRef) {
        $this->load->model('Companymodel');
        $userCompanyCode = $this->flexi_auth->get_user_custom_data('upro_company');
        $companyDet = $this->Companymodel->getCompanyDetailFromCompanyCode($userCompanyCode);
        $companyPriceListNum = $companyDet['price_list'];
        $productPriceListPrice = $this->Productmodel->getProductPriceFromPriceList($companyPriceListNum, $productDetRef['product_sku']);
        if ($productPriceListPrice) {
            $productDetRef['product_price'] = $productPriceListPrice;
        }
    }

    function clear_coupon(){
        
        $this->session->unset_userdata('coupon');
        $this->session->unset_userdata("coupon_discounted_amount");
    }
    
    function charity_process() {
        //e($this->session->all_userdata());
        self::uns_charity();
        $donationMode = $this->input->post("mode");
        $coupon_details = $this->session->userdata('coupon' );
        $array = array('donation' => 1, 'donationMode' => $donationMode);
        $array['donationAmount'] = 0;
        $total = $this->cart->total();
        if( $coupon_details){
            
            if ($coupon_details['vstyle'] == "value") {
                
                $total = $total - $coupon_details['amount'];
            } elseif ($coupon_details['vstyle'] == "percentage") {
                $total = $total - ($total * $coupon_details['amount'] / 100);
            }
        }
        
        if ($donationMode == "round") {
            
            $array['donationAmount'] = round( ceil($total) - $total  ,2);
              if( $array['donationAmount']=="0")  
              {
                  echo json_encode(array("response" => false,'msg' => "Round off for this amount is not possible."));
                  return true;
              }
        } elseif ($donationMode == "insert") {
            if (round($this->input->post("newAmount")) <= 0) {
                echo json_encode(array("response" => false, 'msg' => "Please Enter amount greater than 0"));
                return true;
            }

            $array['donationAmount'] =  round($this->input->post("newAmount"), 2);
        }
        else{
            return false;
        }
        
        $this->session->set_userdata($array);
        echo json_encode(array("response" => true, 'msg' => "Thankyou for your donation", 'totalAmount' => $total + $array['donationAmount']));
    }

    function uns_charity() {
        $this->session->unset_userdata('donation');
        $this->session->unset_userdata('donationMode');
        $this->session->unset_userdata('donationAmount');
    }

    function all_userdata() {
        e($this->session->all_userdata());
    }

    
    function  qwerty(){
        $this->load->model('Settingsmodel');
        $this->Settingsmodel->getAllConfigSec();

    }

    function getProductViewForShop() {
        $output = [
            'success' => 0,
            'html' => "No Product Details",
        ];
        $deptId = $this->input->get('deptId');
        $productRef = $this->input->get('product_ref');

        /* product main details fetched */
        $product_main_details = $this->Productmodel->details($productRef);

        if ($product_main_details) {
            /* get product attributes as per assign attribute set id */
            $prodids = [ $product_main_details['product_id'] => $product_main_details];
            $prodAttr = $this->Productmodel->prodAttributes($prodids);
            $this->_getProductPriceAsPerCompPriceList($product_main_details);
            $this->load->model('Userprodallocationmodel');
            $this->load->model('Othrcommonconfigmodel');

            $user_id = $this->flexi_auth->get_user_custom_data('uacc_id');
            $userAttrAssignment = $this->Userprodallocationmodel->getDeptUserProductDetail($deptId, $user_id, $product_main_details['product_sku']);
            $days_limit = $this->Othrcommonconfigmodel->getDetailWithType('days');
            $prodSelection = [ 'userAssignments' => $userAttrAssignment,
                'prodAttrb' => $prodAttr[$product_main_details['product_id']]['prdAttrLblOpts'],
                'product_main_details' => $product_main_details,
                'days_limit' => $days_limit,
            ];
            $output = [
                'success' => 1,
                'prodName' => $product_main_details['product_name'],
                'prodImage' => com_get_product_image($product_main_details['product_image'], 250, 250),
                'prodDesc' => $product_main_details['product_description'],
                'prodPrice' => $product_main_details['product_price'],
                'prodPoint' => $product_main_details['product_point'],
                'prodAttrHtml' => $this->load->view('ajax/add-product-cart', $prodSelection, true),
            ];
        }

        echo json_encode($output);
        exit;
    }

    function addToCart() {
        $out = [];
        $out['success'] = false;
        $out['message'] = 'Data is not proper';
        $this->load->model('Cartmodel');
        $this->load->model('catalogue/Attributemodel');
        $this->load->model('catalogue/Attrsetattroptionmodel');
        		
        /* Default output */
        $output = [];
        $output['status'] = 0;
        $output['cartRowId'] = 0;
        $output['totalCart'] = '';
        $output['mincarthtml'] = '';
        $output['message'] = 'Product could not found';
        /* postparam */
        $postParams = [];
        parse_str($this->input->post('form-data'), $postParams);        
        /* check product sku post */
        if (isset($postParams['product_id']) && isset($postParams['product_sku'])) {
            /* check quantity post and must be > 0 */
            if (isset($postParams['quantity']) && $postParams['quantity']) {
                $this->load->model('Productmodel', 'Prod');
                $productDetails = $this->Prod->details($postParams['product_sku']);
                /* check is prod exist */
                if ($productDetails) {
					$parent_sku = $productDetails['product_sku'];
//                    if ($productDetails['product_type_id'] == 2) {
//                        $opt = [];
//                        $opt['attribute'] = $postParams['attribute'];
//                        $opt['product_id'] = $postParams['product_id'];
//                        $opt['product_sku'] = $postParams['product_sku'];
//                        $opt['productDetails'] = $productDetails;
//                        $product_id = $this->Prod->getConfigProdFromAttr($opt);
//                        $productDetails = $this->Prod->details($product_id);                        
//                    }
                    if ($productDetails) {
                        //$this->_getProductPriceAsPerCompPriceList($productDetails);
                        $prodDet = array(
							'is_sap' => $productDetails['is_sap'], 
							'parent_sku' => $parent_sku, 
							'product_id' => $productDetails['product_id'],
                            'category_id' => $productDetails['category_id'],
                            'product_sku' => $productDetails['product_sku'],
                            'product_name' => $productDetails['product_name'],
                            'product_alias' => $productDetails['product_alias'],
                            'product_point' => $productDetails['product_point'],
                            'product_price' => $productDetails['product_price'],
                            'product_type_id' => $productDetails['product_type_id'],
                            
                        );
                        /* -------------get Special price if exists-------------- */
                        $specialPrice = $this->Productmodel->get_special_price_by_sku($productDetails['product_sku']);
                        if ($specialPrice) {
                            $prodDet['product_price'] = $specialPrice['price'];
                            $prodDet['normal_price'] = $productDetails['product_price'];
                        }
                        $addDet = [];
                        $addDet['product'] = $prodDet;
                        $addDet['attribute'] = com_arrIndex($postParams, 'attribute', []);
                        $addDet['quantity'] = intval($postParams['quantity']);                        
                        $out = $this->Cartmodel->addToCart($addDet);
                    }
                    if ($out['success']) {
                        $output['message'] = $out['message'];
                        $output['cartRowId'] = $out['cartRowId'];
                    } else {
                        $output['message'] = $out['message'];
                    }
                }
            } else {
                $output['message'] = 'Quantity cannot be zero or null';
            }
        }
        extract($this->Cartmodel->getCartVariables());
        $cartContents = $this->cart->contents();
        $output['wardrobecart'] = $this->load->view('ajax/mini-cart', [ 'cartContents' => $cartContents], TRUE);
        $output['cartTotal'] = $cartTotal;
        $output['itemTotal'] = count($this->cart->contents());
        $output['html'] = $this->load->view('ajax-cart', array(), true);
        $output['itemcount'] = $this->cart->total_items();        
        echo json_encode($output);
        exit();
    }

    function removeCartItem($rowid = false, $ajax = false) {
        $this->load->model('Cartmodel');
        $success = false;
        if (!$rowid) {
            redirect('catalogue/cart');
        }
        if ($ajax) {
            $success = $this->Cartmodel->removeItemCart($rowid);
            $array['html'] = $this->load->view('ajax-cart', array(), true);
            $array['itemcount'] = count($this->cart->contents());
            echo json_encode($array);
            return true;
        }
        if ($rowid) {
            $success = $this->Cartmodel->removeItemCart($rowid);
            $this->session->set_flashdata('message', 'Product Delete From Cart!');
            redirect('catalogue/cart');
        }
    }

  function shippingSetUp(){
//      com_e($_SESSION);
        $this->load->model('OrderShipDetModel');
        
        if(!isset($_SESSION['CheckoutAddress'])){
            redirect('customer/shipping_details');
        }

        if(isset($_POST) && !empty($_POST)){
      $re = "/service_type_[0-9]/"; 
      $keys = array_keys($_POST);    
      $return  =  preg_grep($re,$keys);

      $return_key = key($return);
        $shipping_charges =$_POST['service_shipping_'. key($return)] ;


              $_SESSION['CheckoutAddress']['service_type'] = $_POST[$return[$return_key]];
                $inner['all_shipping'] = $this->OrderShipDetModel->fedExSelectShipping($_SESSION['CheckoutAddress']);
               if($inner['all_shipping']['status']=='ok'){
                $this->session->set_userdata(array('shipping_name' => $_SESSION['CheckoutAddress']['service_type']));
                $this->session->set_userdata(array('shipping_charges' => $shipping_charges));
                $this->session->set_userdata(array('pdf_res' => $inner['all_shipping']));

                redirect('catalogue/cart/process');
            }elseif ($inner['all_shipping']['status']=='Error') {
                # code...
            }else{
                if($inner['all_shipping']['status']=='Exp'){

                }
            }
        }else{
                $all_errors = array('WARNING','FAILURE','ERROR');
                if($_SESSION['CheckoutAddress']['uadd_city'] == 'Los Angeles'){
                    $this->session->set_userdata(array('shipping_name' => 'custom_shipping'));
                    $this->session->set_userdata(array('shipping_charges' => 2));
                    redirect('catalogue/cart/process');
                }else{
                  $inner['all_shipping'] = $this->OrderShipDetModel->fedExShippingRates($_SESSION['CheckoutAddress']);
                }
        }

        if(isset($inner['all_shipping']['HighestSeverity']) && in_array($inner['all_shipping']['HighestSeverity'], $all_errors)){
            
            $shell['contents'] = $this->load->view("all-error-shipping", $inner, true);
           
        }else{

            $shell['contents'] = $this->load->view("all-shipping", $inner, true);
        }

        $this->load->view("themes/" . THEME . "/templates/cartSubpage", $shell);
    }

    function process() {
        $this->isLogged = $this->flexi_auth->is_logged_in();
        if (!$this->isLogged && $this->session->userdata('guest_user_id') == null) {

            redirect('customer/confirm');
            return false;
        }
        
        if ($this->cart->total_items() == 0) {
            $this->session->set_flashdata('message', 'No item found in cart .');
            redirect('catalogue');
            return false;
        }
       if ($this->session->userdata('shipping_charges') == null) {
            
                redirect('customer/shipping_details');
            
        }

        $this->load->model('Ordermodel');
        $insert_order_id = $this->Ordermodel->addOrder();
        $this->session->set_userdata(array('last_order' => $insert_order_id));
        redirect(createUrl('paypal/Payments_pro/Set_express_checkout/' . $insert_order_id));
        //redirect('sagepay_server_example/make_payment/' . $insert_order_id);
    }

    function clear_cart() {
        $this->load->model('Cartmodel');
        $this->Cartmodel->clear_basket();
        $this->session->set_flashdata('message', 'All item has been deleted from cart.');
        redirect('catalogue/cart');
    }
    function orderemail($onum)
    {
      $this->load->model('customer/Ordermodel','ORD');
        $orderDetail = $this->ORD->getOrderDetail($onum);

        $orderItems = $this->ORD->listOrderItems($orderDetail['id']);
        $inner = array();
        $shell = array();
        $inner['order'] = $orderDetail;
        $inner['order_items'] = $orderItems;
        $shell['contents'] = $this->load->view("order-success", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }
}
