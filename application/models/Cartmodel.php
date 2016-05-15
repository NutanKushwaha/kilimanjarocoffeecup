<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartModel extends Commonmodel
{
    
    function __construct() {
        parent::__construct();
    }
    
    function addToCart($params = []) {
        extract($params);
        $options = [];
        $output = ['success' => false, 'message' => 'Data is not comlpete'];
        $insertCartData = [];
        $options = [];
        if ($quantity && $product) {
        	$this->__checkIsProdExist( $product );
            if ($attribute) {
                $this->load->model('cpcatalogue/Attributemodel');
                $this->load->model('cpcatalogue/Attrsetattroptionmodel', 'Attrboptval');
                
                /* All attribute keys */
                $attribute_ids = array_keys($attribute);
                $opts = ['attribute_ids' => $attribute_ids];
                
                /* Post attribute details */
                $attributeDetails = $this->Attributemodel->getAttrbDetailsViaIds($opts);                
                $attrIndex = 0;
                foreach ($attributeDetails as $attrDetStackInd => $attrDet) {
                    $options['attributes'][$attrIndex]['id'] = $attrDet['id'];
                    $options['attributes'][$attrIndex]['label'] = $attrDet['label'];
                    if ($attrDet['is_sys']) {
                        $options['attributes'][$attrIndex]['value'] = $attribute[$attrDet['id']];
                    } 
                    else {
                        $optDet = $this->Attrboptval->getAttrOptDet($attribute[$attrDet['id']]);
                        $options['attributes'][$attrIndex]['value'] = $optDet['option_text'];
                    }
                    $attrIndex++;
                }
            }
            $options['product'] = $product;            
            /* product sku used as id*/
            $insertCartData['id'] = uniqid( 'ITEM' );
            $insertCartData['name'] = $product['product_name'];
            $insertCartData['price'] = $product['product_price'];
            if(isset( $product['normal_price']))
            {
                $insertCartData['normal_price'] = $product['normal_price'];
            }
            $insertCartData['product_sku'] = $product['product_sku'];
            $insertCartData['qty'] = $quantity;
            $insertCartData['options'] = $options;
            $this->cart->product_name_rules = '\w+ \'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\\,\-\.\:';            
            $cartRowId = $this->cart->insert($insertCartData);
            
            if ( $cartRowId ) {
            	/* cartAssociateData for make unique product index for manupulation */     	 	
            	$cartAssocDet = $this->session->cartAssocDet;
            	/* If not exist */
            	if( !$cartAssocDet ){
            		$cartAssocDet = [ $product['product_sku'] => $cartRowId ];
            	}else{
            		/* If exist */
            		$cartAssocDet[ $product['product_sku'] ] = $cartRowId;
            	}
            	$this->session->set_userdata('cartAssocDet', $cartAssocDet );
            	$output['status'] = TRUE;
            	$output['cartRowId'] = $cartRowId;            	
                $output['message'] = 'Product added to cart successfully';
            }
        }
        return $output;
    }
    
    /* Return cart variables */
    function getCartVariables() {
    	$variables = [];
        $variables['cartTotal'] = $this->cart->total();
        $variables['itemTotal'] = $this->cart->total_items();        
        return $variables;
    }

    private function __checkIsProdExist( $product ){
    	$cartAssocDet = $this->session->cartAssocDet;
    	if( $cartAssocDet ){
    		if( isset( $cartAssocDet[ $product[ 'product_sku' ] ] ) ){
    			$this->removeItemCart( $cartAssocDet[ $product[ 'product_sku' ] ] );
    		}
    	}
    }
    
    public function updateCart()
    {
		$ouput = [];
		$ouput[ 'status'] = false;
		$ouput[ 'msg' 	] = 'Order could not updated!';
		
		$qty = $this->input->post('qty');        
        if ( is_array($qty) &&  $qty ) {
            foreach($cartAssocDet = $this->session->cartAssocDet as $key=>$value) {
				if( isset( $qty[$value]  ) ){
					$data = $this->cart->update( array( 'rowid' => $value,
														'qty' => $qty[$value]
													)
												);					
				}
            }
			$ouput[ 'status'] = true;
			$ouput[ 'msg' 	] = 'Order updated successfully.';
        }
		$coupon_details  = $this->session->userdata( 'coupon' );		
		if( $coupon_details ){
			$cart_total = $this->cart->total();
			$coupon_details[ 'min_order_value' ] = floatval( $coupon_details[ 'min_order_value' ] );			
			if( $cart_total >= $coupon_details[ 'min_order_value' ] ){
				if( $coupon_details[ 'vstyle' ] == 'value' ){
					$coupon_discount_value	= $coupon_details[ 'amount' ];
				} else if( $coupon_details[ 'vstyle' ] == 'percentage' ){
					$percentage_discount_value = round( floatval( ($cart_total * $coupon_details[ 'amount' ])/100 ), 2 );					
					$coupon_discount_value	= $percentage_discount_value;
				}
				$this->session->set_userdata( 'coupon_discounted_amount', $coupon_discount_value);
				$ouput[ 'status'] = true;
				$ouput[ 'msg' 	] = 'Coupon applied and order updated successfully';
			} else {
				$this->session->unset_userdata( 'coupon' );
				$this->session->unset_userdata( 'coupon_discounted_amount' );				
				$ouput[ 'status'] = false;
				$ouput[ 'msg' 	] = 'Minimum order value should be '.$coupon_details[ 'min_order_value' ];
			}
		}
        return $ouput;
    }
    
    public function removeItemCart( $rowId ){
    	$itemDetail = $this->cart->get_item( $rowId );
        if( $itemDetail ){
            $success = $this->cart->remove( $rowId );
            $cartTotal = $this->cart->total();
            $totalCartItems = $this->cart->total_items();
            $coupon = $this->session->userdata( 'coupon' );
            if( $coupon && 
				( ( floatval($coupon[ 'min_order_value' ]) < floatval($cartTotal) ) 
					|| !$totalCartItems )  ){
				$this->session->unset_userdata( 'coupon' );
				$this->session->unset_userdata( 'coupon_discounted_amount' );
			}
            $skuStack = array_keys( $this->session->cartAssocDet , $rowId );
            /* Check is skuStack filled */
            if( $skuStack ){
            	/* get first index */
            	$productSku = $skuStack[ 0 ];
            	$cartAssocDet = $this->session->cartAssocDet;
            	unset( $cartAssocDet[ $productSku ] );
            	$this->session->set_userdata('cartAssocDet', $cartAssocDet );
                return true;
            }
            return false;
        }
    }

    public function emptyCart(){
        $this->cart->destroy( );
		$this->session->unset_userdata( 'coupon' );
		$this->session->unset_userdata( 'coupon_discounted_amount' );
        $this->session->unset_userdata( 'cartAssocDet' ); 
        $this->session->unset_userdata( 'cartReorderRef' );
    }

    /* 
        $params = [ 'orderDetail' ];
    */
    function reorderFromSavedOrder( $params = [] ){
        extract( $params );
        $output = [];
        $output['status'] = FALSE;
        $output[ 'message' ] = 'Data is insufficent';
        if( is_array( $orderDetail ) && $orderDetail ){
            $ordersCartIds = [];
            foreach ($orderDetail as $orderIndex => $orderItem) {
                /* Insert to cart */
                $insertCartData = [];
                /* product sku used as id*/
                $insertCartData['id'] = uniqid( 'ITEM' );
                $insertCartData['qty'] = $orderItem[ 'order_item_qty' ];
                $insertCartData['name'] = $orderItem[ 'order_item_name' ];
                $insertCartData['price'] = $orderItem[ 'order_item_price' ];                
                $orderOptions = json_decode( $orderItem[ 'order_item_options' ] , true);
                $orderOptions[ 'reorder' ] = $orderItem[ 'order_num' ];
                $insertCartData['options'] = $orderOptions;
                $cartRowId = $this->cart->insert($insertCartData);
                if ( $cartRowId ) {
                    /* cartAssociateData for make unique product index for manupulation */          
                    $cartAssocDet = $this->session->cartAssocDet;
                    /* If not exist */
                    if( !$cartAssocDet ){
                        $cartAssocDet = [ $orderItem['product_ref'] => $cartRowId ];
                    }else{
                        /* If exist */
                        $cartAssocDet[ $orderItem['product_ref'] ] = $cartRowId;
                    }
                    $this->session->set_userdata('cartAssocDet', $cartAssocDet );
                }
                $ordersCartIds[ ] = $cartRowId;
            }
            $order_num = $orderDetail[0]['order_num'];
            /* cartReorderRef for check about order alredy added  */
            $cartReorderRef = $this->session->cartReorderRef;
            /* If not exist */
            if( !$cartReorderRef ){                
                $cartReorderRef = [ $order_num => $ordersCartIds ];
            }else{
                /* If exist */
                $cartReorderRef[ $order_num ] = $ordersCartIds;
            }
            $this->session->set_userdata('cartReorderRef', $cartReorderRef );
            $output['status'] = TRUE;
            $output['message'] = 'Product add to cart successfully';            
        }
        return $output;
    }
    function clear_basket()
    {
        $this->cart->destroy();
    }
    
    function get_coupon( $coupon ){
		$curr_date = date('Y-m-d H:i:s', time() );
		$sql = 'SELECT * FROM '.$this->db->dbprefix( 'voucher' ).
				' WHERE code = "'.$coupon.'" AND active = 1 AND "'.$curr_date.'" BETWEEN active_from and active_to';		
		return $this->db->query( $sql )->row_array();		
	}
	
	function get_coupon_used_count( $coupon_id, $customer_id ){
		$opt = [];
		
		$opt[ 'result' 	] = 'num';
		$opt[ 'from_tbl'] = 'voucher_used';
		$opt[ 'where' 	][	'voucher_id'	] = $coupon_id;
		if( $customer_id ){
			$opt[ 'where' ][ 'customer_id'	] = $customer_id;
		}
		return $this->get_all( $opt );		
	}
}
