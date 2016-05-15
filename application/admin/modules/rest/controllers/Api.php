<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Api extends RESTAPI_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();        
        $this->param = [];
        self::init();
        /* 
        Important but for now commented
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
        */
    }

    function init(){
        $limit = intval($this->get('limit'));
        if($limit){
            $this->param['limit'] = $limit;
        }
        $offset = intval($this->get('offset'));
        if($offset){
            $this->param['offset'] = $offset;
        }        
    }
	
	/*==================================*/
    /*  Order function start         */
    /**
     * Create Orders
     */
	
    public function orders_post(){
        $this->load->model('order/Ordermodel', 'Ordermodel');
        if(!empty($this->request->body)){
			$output = $this->Ordermodel->insertFromXML($this->request->body);
            if( $output[ 'status' ] ){
                $message = [ 'message' => $output[ 'message' ] ];
                $this->set_response($message, REST_Controller::HTTP_CREATED); 
            }else{
                /**
                 * Error either format not convert succesfully or some internal logic error
                 */
                $message = [
                    'message' => $output[ 'message' ]
                ];
                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
            }
        }else{
            $message = [
                'message' => 'Sent data could not be found'
            ];
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
    }
    /**
     * Get Orders
	*/
	public function orders_get( ){
		$this->load->model('order/Ordermodel', 'Ordermodel');
		$param = [];
		$param = $this->param;
		$orderNum = $this->input->get( 'OrderNum' );
		
		if( !empty( $orderNum ) && !is_null( $orderNum ) ){
			$param['where'][] = [ '0' => 'order_num',
										'1'	=> $orderNum
									];
		}
		
		$orderEndDate = $this->input->get( 'OrderEndDate' );
		$orderStartDate = $this->input->get( 'OrderStartDate' );
		
		if( !empty( $orderStartDate ) && !is_null( $orderStartDate ) ){
			$orderStartDate = date("Y-m-d 00:00:00", strtotime( $orderStartDate ));
			$order_start_date_valid = 'order_num >= ';
			$param['where'][] = [ 	'0' => $order_start_date_valid,
									'1'	=> $orderStartDate
								];
		}
		
		if( $orderEndDate && !empty( $orderEndDate ) && !is_null( $orderEndDate ) ){
			$orderEndDate = date("Y-m-d 23:59:59", strtotime( $orderEndDate ));
			$param['where'][] = [ '0' => 'order_time <= ',
									'1'	=> $orderEndDate
								];
		} else if( !empty( $orderStartDate ) && !is_null( $orderStartDate ) ){
			$orderEndDate = date("Y-m-d 23:59:59", strtotime( $orderStartDate ));
			$param['where'][] = [ '0' => 'order_time <= ',
									'1'	=> $orderEndDate
								];			
		}
		$orders = $this->Ordermodel->getOrderWithDetailAsObjectForXml( $param );
		
		if (!empty($orders))
        {
            $this->set_response($orders, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Orders could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
	}


	private function orders_put( ){
		if(!empty($this->request->body)){
			$this->load->model('order/Ordermodel', 'Ordermodel');
			$orders_status = $this->Ordermodel->update_order_status($this->request->body);
			$status = $orders_status[ 'success' ];
			if ($status)
			{
                $message = [
                    'message' => $orders_status[ 'msg' ]
                ];				
				if( $status == 3 ) {
					$this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
				} else if( $status == 4 ) {
					$this->set_response($message, REST_Controller::HTTP_CREATED);  // OK (200) being the HTTP response code
				}                
            }else{
                /**
                 * Error either format not convert succesfully or some internal logic error
                 */
                $message = [
                    'message' => $orders_status[ 'msg' ]
                ];
                $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); 
            }
        } else {
            $message = [
                'message' => 'Sent data could not be found'
            ];
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
	}	
    /*==================================*/
    /*  Customer function start         */
    /**
     * Get Customers
	*/
    public function customers_get( ){
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->param['fields'] = 'company_code as CardCode, name as CardName, 
								company_type as CardType, group as GroupCode,
								phone1 as Phone1, contact_person as ContactPerson, pay_terms as PayTermsGrpCode,
								credit_balance as CreditLimit, price_list as PriceListNum, currency as Currency, 
								balance as balance
								';
		$customerCode = $this->input->get( 'CardCode' );
		if( !empty( $customerCode ) ){
			$this->param['where'][] = [ '0' => 'company_code',
										'1'	=> $customerCode
									];
		}
		
		$EndDate = $this->input->get( 'EndDate' );
		$StartDate = $this->input->get( 'StartDate' );
		
		if( !empty( $StartDate ) && !is_null( $StartDate ) ){
			//$StartDate = date("Y-m-d 00:00:00", strtotime( $StartDate ));
			$cust_start_date_valid = 'added_on >= ';
			$this->param['where'][] = [ 	'0' => $cust_start_date_valid, '1'	=> strtotime( $StartDate ) ];
		}
		
		if( $EndDate && !empty( $EndDate ) && !is_null( $EndDate ) ){
			
			//$orderEndDate = date("Y-m-d 23:59:59", strtotime( $EndDate ));
			$this->param['where'][] = [ '0' => 'added_on <= ',
									'1'	=> strtotime( $EndDate )
								];
		} else if( !empty( $StartDate ) && !is_null( $StartDate ) ){
			
			//$EndDate = date("Y-m-d 23:59:59", strtotime( $StartDate ));
			/*
				$this->param['where'][] = [ '0' => 'added_on <= ',
										'1'	=> strtotime( $EndDate )
									];
			*/
		}
        $companies = $this->Companymodel->getCompany([], $this->param);        
        if (!empty($companies))
        {
            $this->set_response($companies, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Customers could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
	/**
	 * get Customers address
	 * */
    public function customers_address_get( ){
        $this->load->model('company/Companymodel', 'Companymodel');
        
        $this->param['fields'] = 'company_code as CardCode, address_name as AddressName, 
								street as Street, zip_code as ZipCode,
								city as City, county as County, country as Country,
								address_type as AddressType, 
								address_name2 as AddressName2, address_name3 as AddressName3';
		$customerCode = $this->input->get( 'CardCode' );
		if( !empty( $customerCode ) ){
			$this->param['where'][] = [ '0' => 'company_code',
										'1'	=> $customerCode
									];
		}
        $companies_address = $this->Companymodel->getCompanyAddress($this->param);        
        if (!empty($companies_address))
        {
            $this->set_response($companies_address, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Customers could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
	/**
	 * get Customers contact employee
	 * */
    public function customers_employee_get( ){
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->param['fields'] = 	'company_code as CardCode, 
									title as Title, name as Name, 
									position as Position, address as Address,
									phone1 as Phone1, mobile as MobilePhone, email as E_Mail';
		$customerCode = $this->input->get( 'CardCode' );
		if( !empty( $customerCode ) ){
			$this->param['where'][] = [ '0' => 'company_code',
										'1'	=> $customerCode
									];
		}
        $companies_address = $this->Companymodel->getCompanyEmployee($this->param);
        if (!empty($companies_address))
        {
            $this->set_response($companies_address, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Customers address could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
    
    /**
     * Update/Create customers or customer
     */
    public function customers_post(){
        $this->load->model('company/Companymodel', 'Companymodel');
        if(!empty($this->request->body)){			
            if($this->Companymodel->insertFromXML($this->request->body)){
                $message = [
                    'message' => 'Customers succesfully created or updated'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED); 
            }else{
                /**
                 * Error either format not convert succesfully or some internal logic error
                 */
                $message = [
                    'message' => 'Server internal error'
                ];
                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
            }
        }else{
            $message = [
                'message' => 'Sent data could not be found'
            ];
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
    }

    /*  Customers function end          */
    /*==================================*/

    /*==================================*/
    /*  Product function start          */
    /**
     * Get products or product
     */
    public function products_get( ){
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $this->param['fields'] = 'product_sku as ItemCode, product_name as ItemName, 
								stock_level QuantityOnStock';
		$prod_sku = $this->input->get( 'ItemCode' );
		if( !empty( $prod_sku ) ){
			$this->param['where'][] = [ '0' => 'product_sku',
										'1'	=> $prod_sku
									];
		}		
        $products = $this->Productmodel->getProduct([], $this->param);
        if (!empty($products))
        {
            $this->set_response($products, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Products could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    /**
     * Update/Create products or product
     */
    public function products_post(){
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        if(!empty($this->request->body)){			
            if($this->Productmodel->insertFromXMLRecord($this->request->body)){
                $message = [
                    'message' => 'Products succesfully created or updated'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED); 
            }else{
                /**
                 * Error either format not convert succesfully or some internal logic error
                 */
                $message = [
                    'message' => 'Server internal error'
                ];
                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
            }
        }else{
            $message = [                
                'message' => 'Sent data could not be found'
            ];
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
    }

    /**
     * Delete products or product
     */

    /*public function products_delete(){
        echo "string";
        exit();
    }*/
    
    /**
     * Update/Create products or product group (category)
     */
    public function product_groups_post(){
        if(!empty($this->request->body)){
			$this->load->model('cpcatalogue/Categorymodel', 'Categorymodel');
            if($this->Categorymodel->insertFromXML($this->request->body)){
                $message = [
                    'message' => 'Category succesfully created or updated'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED); 
            }else{
                /**
                 * Error either format not convert succesfully or some internal logic error
                 */
                $message = [
                    'message' => 'Server internal error'
                ];
                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
            }
        }else{
            $message = [                
                'message' => 'Sent data could not be found'
            ];
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
    }
    /*  Product function end            */
    /*==================================*/

    /*==================================*/
    /*Product price list function start */
    /**
     * Update/Create products or product price list
     */
    public function special_prices_list_name_post(){
        if(!empty($this->request->body)){ 
            $this->load->model('cpcatalogue/Specialpricesmodel', 'Specialpricesmodel');
            if($this->Specialpricesmodel->insertListNameFromXML($this->request->body)){
                $message = [
                    'message' => 'Product special price succesfully created or updated'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED); 
            }else{
                /**
                 * Error either format not convert succesfully or some internal logic error
                 */
                $message = [
                    'message' => 'Server internal error'
                ];
                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
            }
        }else{
            $message = [                
                'message' => 'Sent data could not be found'
            ];
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
    }

    /*Product price list function end   */
    /*==================================*/


    /*==================================*/
    /*Special price list function start */
    /**
     * Update/Create Special price list
     */
    public function special_prices_post(){
        if(!empty($this->request->body)){			
            $this->load->model('cpcatalogue/Specialpricesmodel', 'Specialpricesmodel');
            if($this->Specialpricesmodel->insertFromXML($this->request->body)){
                $message = [
                    'message' => 'Products special price succesfully created or updated'
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED); 
            }else{
                /**
                 * Error either format not convert succesfully or some internal logic error
                 */
                $message = [
                    'message' => 'Server internal error'
                ];
                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
            }
        }else{
            $message = [                
                'message' => 'Sent data could not be found'
            ];
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
    }

    /*Special price list function end   */
    /*==================================*/

    private function users_get()
    {        
        // Users from a data store e.g. database
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retreival.
        // Usually a model is to be used for this.

        $user = NULL;

        if (!empty($users))
        {
            foreach ($users as $key => $value)
            {
                if (isset($value['id']) && $value['id'] === $id)
                {
                    $user = $value;
                }
            }
        }

        if (!empty($user))
        {
            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    private function users_post()
    {                
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    private function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
