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
            $insertCartData['product_sku'] = $product['product_sku'];
            $insertCartData['qty'] = $quantity;
            $options['category_dept'] = $category_dept;
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
                $output['message'] = 'Product add to cart successfully';
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

    public function removeItemCart( $rowId ){
    	$itemDetail = $this->cart->get_item( $rowId );
        if( $itemDetail ){
            $success = $this->cart->remove( $rowId );
            $skuStack = array_keys( $this->session->cartAssocDet , $rowId );
            /* Check is skuStack filled */
            if( $skuStack ){
            	/* get first index */
            	$productSku = $skuStack[ 0 ];
            	$cartAssocDet = $this->session->cartAssocDet;
            	unset( $cartAssocDet[ $productSku ] );
            	$this->session->set_userdata('cartAssocDet', $cartAssocDet );
            }
        }
    }

    public function emptyCart(){
        $this->cart->destroy( );
        $this->session->unset_userdata( 'cartAssocDet' ); 
        $this->session->unset_userdata( 'cartReorderRef' );
    }

    /* 
        $params = [ 'orderDetail' ];
    */
    function reorderFromSavedOrder( $params = [] ){
        extract( $params );
        $policyOverMsg = '';
        $output = [];        
        $output['status'] = FALSE;
        $output[ 'message' ] = 'Data is insufficent';
        if( is_array( $orderDetail ) && $orderDetail ){
            $product_sku = '';
            $product_name = '';
            $this->load->model('product_allocation/Userprodallocationmodel',  'Userprodallocationmodel');
            $this->load->model( 'Othrcommonconfigmodel');
            $ordersCartIds = [];            
            $user_id = $this->flexi_auth->get_user_custom_data( 'uacc_id' );
            foreach ($orderDetail as $orderIndex => $orderItem) {
                $orderQty = $orderItem[ 'order_item_qty' ];
                $orderOptions = json_decode( $orderItem[ 'order_item_options' ] , true);
                if( $orderOptions ){
                    if( isset( $orderOptions[ 'product' ] ) ){
                        $product_sku = $orderOptions[ 'product' ][ 'product_sku' ];
                        $product_name = $orderOptions[ 'product' ][ 'product_name' ];
                        $userAttrAssignment = $this->Userprodallocationmodel
                                                    ->getDeptUserProductDetail( $orderOptions['deptId'], 
                                                            $user_id,  $product_sku);
                        $days_limit = $this->Othrcommonconfigmodel->getDetailWithType('days');
                        $cartAssocDet = $this->session->cartAssocDet;
                        $prodAlreadyAddedCartRowId = 0;
                        if( $cartAssocDet ){
                                if( isset(  $cartAssocDet[ $product_sku ] ) ){
                                    $prodAlreadyAddedCartRowId =  $cartAssocDet[ $product_sku ];
                                }                    
                        }                        
                        $params = [
                                    'user_id' => $user_id,
                                    'days_limit' => $days_limit,
                                    'product_sku' => $product_sku,
                                    'cartRowId' => $prodAlreadyAddedCartRowId,
                                    'userAttrAssignment' => $userAttrAssignment,
                                ];                        
                        $prodAvailCheckDet = com_checkProductAvailability( $params );                        
                        if( $prodAvailCheckDet && $prodAvailCheckDet[ 'success' ] ) {
                            $allowedQty = $prodAvailCheckDet[ 'allowedQty' ];                            
                            if( ( $allowedQty - $orderQty ) < 0 ){
                                $orderQty = 0;
                            }
                        }                        
                    }
                }                
                if( !$orderQty ){
                    $policyOverMsg .= ' Your selection for product '.$product_name.' exceed 
                                        so did not included in cart.<br/>'; 
                    continue;
                }
                /* Insert to cart */
                $insertCartData = [];
                /* product sku used as id*/
                $orderOptions[ 'reorder' ] = $orderItem[ 'order_num' ];
                $insertCartData['options'] = $orderOptions;

                $insertCartData['qty'] = $orderQty;
                $insertCartData['id'] = uniqid( 'ITEM' );
                $insertCartData['name'] = $orderItem[ 'order_item_name' ];
                $insertCartData['price'] = $orderItem[ 'order_item_price' ];
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
            if( $policyOverMsg ){
                $output['message'] .= '<br/>'.$policyOverMsg;
            }
        }
        return $output;
    }
}
