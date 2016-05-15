<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends Admin_Controller
{
    
    function __construct() {
        parent::__construct();
        $this->data = null;
        $this->load->model('Cartmodel');
    }

    //View cart
    function index() {        
        extract( $this->Cartmodel->getCartVariables() );
        $this->data['user_type'] = $this->user_type;
        $this->data['cartTotal'] = $cartTotal;
        $this->data['itemTotal'] = $itemTotal;
        $this->data['cartContents'] = $this->cart->contents();
        $this->data['content'] = $this->load->view('cart-index', $this->data, true);
        $this->load->view($this->template['default'], $this->data);
    }
}
