<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends Adminajax_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('Productmodel');
	}
	
	function index ( ){
	    //list all Products
	    $perpage = 15;
    	$search_param  =   [];
    	$search_param[ 'limit' ]    =  $perpage;
    	$search_param[ 'offset' ]   =  com_gParam( 'offset', true, 0 );
    	$search_param[ 'category' ] =  com_gParam( 'category', true, 0 );
    	$search_param[ 'prodName' ] =  com_gParam( 'prodName', true, "" );    	
    	if( $search_param[ 'prodName' ] ){
			$search_param[ 'prodName' ] = preg_replace('/\s+/', ' ',strtolower( $search_param[ 'prodName' ] ) );
    	}
	    //Setup pagination	    
	    $config['cur_page']         =   $search_param[ 'offset' ];
	    $config['request_type']     =   'get';
	    $config['per_page']         =   $perpage;
	    $config['html_container']   =   'products-list-div';
	    $config['base_url']         =    base_url().'cpcatalogue/ajax/product/';	    
	    $config['total_rows']       =   $this->Productmodel->countAllProducts( $search_param );
	    $config['additional_param'][]['js'] = ['category' => "$('#category').val()"];
	    $config['additional_param'][]['js'] = ['prodName' => "$('#prodName').val()"];
	    //render view
	    $inner = [];
	    $inner['pagination'] = com_ajax_pagination($config);
	    $inner[ 'products' ] = $this->Productmodel->listAllProducts( $search_param );	    
        $output = [
                    'success' => 1,
                    'html' => $this->load->view('products/ajax/products-list', $inner, TRUE),
                ];
        echo json_encode( $output );
        exit;
	}
}