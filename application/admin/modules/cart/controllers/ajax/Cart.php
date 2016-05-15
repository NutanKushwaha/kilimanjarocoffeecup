<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cart extends Adminajax_Controller {

    function __construct() {
        parent::__construct();        
        $this->load->model( 'Cartmodel' );
    }

    function getWardrobeMiniCart(){
        $output = [];
        
        /* get cart variable */        
        extract( $this->Cartmodel->getCartVariables() );
        $output['cartTotal'] = com_convertToDecimal( $cartTotal, '2');
        $output['itemTotal'] = $itemTotal;

        /* cart contents */
        $innerData[ 'cartTotal' ]       =   $output['cartTotal'];
        $innerData[ 'itemTotal' ]       =   $output['itemTotal'];
        $innerData[ 'cartContents' ]    =   $this->cart->contents();
        $miniCartView = 'wardrobe/ajax/user-mini-cart';
        if( in_array( $this->user_type, [CMP_ADMIN, CMP_PM, CMP_MD])  ){
            $miniCartView = 'wardrobe/ajax/comp-mini-cart';
        }
        $output['wardrobecart'] = $this->load->view(    $miniCartView, 
                                                        $innerData, 
                                                        TRUE);
        echo json_encode( $output );
        exit();
    }

    function removeCartItem( ){
        $success = false;
        $rowId = $this->input->get( 'rowId' );
        if( $rowId ){
            $this->Cartmodel->removeItemCart( $rowId );
            $success = TRUE;
        }        
        extract( $this->Cartmodel->getCartVariables() );
        $output = [ 'success' => $success, 'cartTotal' => $cartTotal, 'itemTotal' => $itemTotal ];
        echo json_encode(  $output );
        exit;
    }

    function updateCartItem( ){
        $success = false;
        $qty = $this->input->get( 'qty' );
        $rowId = $this->input->get( 'rowId' );
        if( $rowId ){
            $updateCart['qty']   = $qty;
            $updateCart['rowid'] = $rowId;
            $success = $this->cart->update( $updateCart );
        }        
        extract( $this->Cartmodel->getCartVariables() );
        $cartRow = $this->cart->get_item( $rowId );
        $output = [ 'success' => $success,                 
                    'cartTotal' => $cartTotal, 
                    'itemSubTotal' => $cartRow['subtotal'], 
                ];
        echo json_encode(  $output );
        exit;        
    }    
}
