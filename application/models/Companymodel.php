<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyModel extends Commonmodel{

	function __construct()
	{
		# code...
        parent::__construct();
		$this->set_attributes();        
	}

	protected function set_attributes(){

        $this->tbl_name = 'company';
        $this->tbl_pk_col = 'id';
        $this->tbl_alias = 'comp_det';
		$this->tbl_cols['id'] = 'id';
		$this->tbl_cols['company_code'] = 'company_code';
		$this->tbl_cols['added_on'] = 'added_on';
		$this->tbl_cols['updated_on'] = 'updated_on';
		$this->tbl_cols['name'] = 'name';
		$this->tbl_cols['company_type'] = 'company_type';
		$this->tbl_cols['group'] = 'group';
		$this->tbl_cols['phone1'] = 'phone1';
		$this->tbl_cols['phone2'] = 'phone2';
		$this->tbl_cols['contact_person'] = 'contact_person';
		$this->tbl_cols['pay_terms'] = 'pay_terms';
		$this->tbl_cols['credit_limit'] = 'credit_limit';
		$this->tbl_cols['price_list'] = 'price_list';
		$this->tbl_cols['currency'] = 'currency';
		$this->tbl_cols['email_address'] = 'email_address';
		$this->tbl_cols['credit_balance'] = 'credit_balance';
		$this->tbl_cols['balance'] = 'balance';
	}

	/*
	 	Insert or allocate posted products to Company.
	 */
	function productAssign( ) {
        
        $products = $this->input->post('products') ;
        $insert_data = [];
        $unique_comp = array_keys($products);
        foreach ($products as $comp_key => $prod_det) {
        	foreach ($prod_det as $p_index => $p_key) {
        		$insert_data[] = [    						
    						'product_code' => $p_key,
    						'company_code' => $comp_key,
    					];
        	}    		
        }

        foreach ($unique_comp as $comp_key) {
        	$del = [];
        	$del['company_code'] = $comp_key;
        	$this->db->delete( 'company_prod_assign', $del);
        }
        return $this->db->insert_batch( 'company_prod_assign', $insert_data);
	}

	function getCompDeptUser( $deptId = 0){
		if($deptId){
			$department_id = $deptId;
		}else{
			$department_id = $this->input->post('department');
		}
		$sql_select = [ $this->flexi_auth->db_column('user_acc', 'id'),
						$this->flexi_auth->db_column('user_acc', 'username')];
		$sql_where['upro_company'] = $this->flexi_auth->get_comp_admin_company_code();		
		$sql_like['upro_department'] = '"'.$department_id.'"';

		$this->flexi_auth->sql_select($sql_select);
		$this->flexi_auth->sql_where($sql_where);
		$this->flexi_auth->sql_like($sql_like);
		return $this->flexi_auth->search_users_array();		
	}

	function getCompAssignProd($comp_code ){
		return	$this->db->select('product_code')
				->from('company_prod_assign')
				->where('company_code' , $comp_code)				
				->get()
				->result_array();

	}

	function getCompAssignProdCount($comp_code){
		return	$this->db->select('count(id) as ttl')
						->from('company_prod_assign')
						->where('company_code' , $comp_code)
						->get()
						->row_array();
	}

	function getCompAssignProdWithDetails($comp_code, $offset = 0, $limit = 0){
		if($limit) $this->db->limit($limit);
		if($offset) $this->db->offset($offset);
		return	$this->db->select('prod.product_name, prod.product_sku')
					->from('company_prod_assign as assign')
					->join('product as prod', 'prod.product_sku=assign.product_code', 'left')
					->where('company_code' , $comp_code)
					->get()
					->result_array();

	}

	/*
	 	Return product details as per company and department allocation
	 */
	function getLoggedCmpDeptProdDet($dept_id ){
		

        $company_code = $this->flexi_auth->get_comp_admin_company_code();

        $param = [];
        $param['select'] = 'prd.product_id, prd.product_name, prd.product_sku, prd.ref_product_id, prd.weight, 
        					prd.product_type_id,dp.days_limit,dp.qty_limit,dp.group_policy,
        					prd.attribute_set_id';
        $param['from_tbl'] = 'department_product';
        $param['from_tbl_alias'] = 'dp';
		$param['join'][] = 	['tbl' => $this->db->dbprefix('product'). ' as prd',
                            'cond' => "dp.product_sku=prd.product_sku",
                            'type' => 'inner',
                            'pass_prefix' => 1
                          	];		
		$param['where']['department_id'] = $dept_id;

		return	$this->get_all($param);
	}

    function getOffsetIndex($cid, $offset = 20) {
        
        $query =    $this->db->select('CEIL(count(id)/'.$offset.') as offset')->from('company')
                            ->where('id < ', intval($cid))
                            ->get();
        if ($query->num_rows()) {
            return $query->row_array();
        }
        return array('offset' => 0);
    }

	function insertFromXML($data) {
		$retBool = false;
		$data = $data[0]['GetByKeyResponse'];
		if($data) {
	        $customer_xml_fields = ['CardCode', 'CardName', 'CardType', 'GroupCode', 'Phone1', 'Phone2',
	        						'ContactPerson', 'PayTermsGrpCode', 'CreditLimit', 'PriceListNum',
	        						'Currency', 'EmailAddress', 'u_creditbalance', 'balance'
	        						];
	        $customer_data_fields = [
	        						'CardCode' => $this->tbl_cols['company_code'], 
	                            	'CardName' => $this->tbl_cols['name'],
		                            'CardType' => $this->tbl_cols['company_type'], 
		                            'GroupCode' => $this->tbl_cols['group'], 
		                            'Phone1' => $this->tbl_cols['phone1'], 
									'Phone2' => $this->tbl_cols['phone2'],
									'ContactPerson' => $this->tbl_cols['contact_person'],
									'PayTermsGrpCode' => $this->tbl_cols['pay_terms'],
									'CreditLimit' => $this->tbl_cols['credit_limit'],
									'PriceListNum' => $this->tbl_cols['price_list'],
	        						'Currency' => $this->tbl_cols['currency'], 
	        						'EmailAddress' => $this->tbl_cols['email_address'], 
	        						'u_creditbalance' => $this->tbl_cols['credit_balance'], 
	        						'balance' => $this->tbl_cols['balance'], 
	                        		];

	        $customer_addr_xml_fields = ['BPCode', 'AddressName', 'Street', 'ZipCode', 'City', 'County',
	        						'Country', 'AddressType', 'AddressName2', 'AddressName3'
	        						];
	        $customer_addr_data_fields = [
									'BPCode' => 'company_code',
									'AddressName' => 'address_name',
									'Street' => 'street',
									'ZipCode' => 'zip_code',
									'City' => 'city',
									'County' => 'county',
	        						'Country' => 'country',
	        						'AddressType' => 'address_type',
	        						'AddressName2' => 'address_name2',
	        						'AddressName3' => 'address_name3',
	                        		];

	        $customer_emp_xml_fields = ['CardCode', 'Name', 'Position', 'Address', 'Phone1', 'Phone2',
	        							'MobilePhone', 'Fax', 'E_Mail', 'Active', 
	        							'Title', 'FirstName', 'MiddleName', 'LastName'
										];
	        $customer_emp_data_fields = [
	        							'CardCode'=> 'company_code',
	        							'Name' => 'name',
	        							'Position' => 'position',
	        							'Address' => 'address',
	        							'Phone1' => 'phone1',
	        							'Phone2' => 'phone2',
	        							'MobilePhone' => 'mobile',
	        							'Fax' => 'fax',
	        							'E_Mail' => 'email',
	        							'Active' => 'active',
	        							'Title' => 'title',
	        							'FirstName' => 'first_name',
	        							'MiddleName' => 'middle_name',
	        							'LastName' => 'last_name',
										];
			
            $customer_insert = [];
            $customer_emp_insert = [];
            $customer_address_insert = [];
            
            $customer_xml_data_fields = [ 	'company_code'=> '',
											'added_on'=> '',
											'updated_on'=> '',
											'name'=> '',
											'company_type'=> '',
											'group'=> '',
											'phone1'=> '',
											'phone2'=> '',
											'contact_person'=> '',
											'pay_terms'=> '',
											'credit_limit'=> '',
											'price_list'=> '',
											'currency'=> '',
											'email_address'=> '',
											'credit_balance'=> '0.00',
											'balance'=> '0.00',
                            		];

				/*	
					Customer Field
				*/			
				if( isset($data['BOM']['BO']['BusinessPartners']['row']) 
					&& !is_null($data['BOM']['BO']['BusinessPartners']['row']) ) {
					$company_added_on = $company_updated_on = 0;
					foreach($data['BOM']['BO']['BusinessPartners']['row'] as $key => $val){
						if(!is_array($val) && in_array($key, $customer_xml_fields) && !empty($val)){
							$customer_xml_data_fields[$customer_data_fields[$key]] = $val;
						}
					}

					$company_code = null;
					if($customer_xml_data_fields['company_code']){
						$company_code = trim($customer_xml_data_fields['company_code']);
	                    $param['result'] = 'row';
	                    $param['fields'] = 'company_code,added_on,updated_on,account_id';
	                    $param['where'] = [['company_code' , $company_code]];
	                    $existCompany = $this->getCompany('', $param);
                        if($existCompany){
                        	$comp_exist = $existCompany;
                            $company_added_on = $existCompany['added_on'];
                            $company_updated_on = time();
                        }else{
                        	$comp_exist = false;
                            $company_added_on = time();
                            $company_updated_on = 0;
                        }
                      	$customer_xml_data_fields['added_on'] = $company_added_on;
                      	$customer_xml_data_fields['updated_on'] = $company_updated_on;
                      	$customer_xml_data_fields['comp_exist'] = $comp_exist;
                      	
						$customer_insert = $customer_xml_data_fields;
						$cust_address = &$data['BOM']['BO']['BPAddresses']['row'];
						if(!isset($cust_address[0])) {
							$cust_address = [ '0' => $cust_address];
						}

						foreach($cust_address as $key => $val){
	        				$customer_addr_xml_data_fields = [
											'company_code'=> '',
											'address_name'=> '',
											'street'=> '',
											'zip_code'=> '',
											'city'=> '',
											'county'=> '',
											'country'=> '',
											'address_type'=> '',
											'address_name2'=> '',
											'address_name3'=> '',
		                        		];

							foreach($val as $addr_index => $addr_data){
								if(!is_array($addr_data) && !empty($addr_data)
									&& in_array($addr_index, $customer_addr_xml_fields)){
									$customer_addr_xml_data_fields[$customer_addr_data_fields[$addr_index]] = $addr_data;
								}
	                        }
	                        $customer_address_insert[] = $customer_addr_xml_data_fields;
						}

						$employe_det = &$data['BOM']['BO']['ContactEmployees']['row'];

						if(!isset($employe_det[0])) {
							$employe_det = [ '0' => $employe_det];
						}

						foreach($employe_det as $key => $val){
							$customer_emp_xml_data_fields = [
														'company_code'=> '',
														'title'=> '',
														'name'=> '',
														'first_name'=> '',
														'middle_name'=> '',
														'last_name'=> '',
														'position'=> '',
														'address'=> '',
														'phone1'=> '',
														'phone2'=> '',
														'mobile'=> '',
														'fax'=> '',
														'email'=> '',
														'active' => '',
													];

							$customer_emp_xml_data_fields['active'] = 0;
							
							foreach($val as $contactIndex => $contactDet) {
								if(!is_array($contactDet) && !empty($contactDet)
									&& in_array($contactIndex, $customer_emp_xml_fields)){
									if($contactIndex == 'Active'){
										$contactDet = $contactDet == 'tYES' ? 1 : 0;
									}									
									$customer_emp_xml_data_fields[$customer_emp_data_fields[$contactIndex]] = $contactDet;
								}
							}
							
							$customer_emp_insert[] = $customer_emp_xml_data_fields;
						}

						/*
						* Insert and update company
						*/						
						$this->insert_update_company($customer_insert);

                        if($existCompany){
                        	/*
                        	* Delete company address
                        	*/
                            $this->db->where('company_code', $company_code)
                                	->delete('company_address');
                    		/*
                        	* Delete company contact employee
                        	*/
							$this->db->where('company_code', $company_code)
                			 		->delete('company_customer');
                        }

                    	/*
                    	* Insert address and contact employee
                    	*/
                        $this->db->insert_batch('company_address', $customer_address_insert);                    	
                    	$this->db->insert_batch('company_customer', $customer_emp_insert);                    	
						$retBool = true;
					}
				}
		}
		return $retBool;
	}

    function getCompany($company = [], $param = []) {
        if( isset($param['fields']) && !empty($param['fields']) ){
            $this->db->select($param['fields']);
        }
        if( isset($param['offset']) && !empty($param['offset']) ){
            $this->db->offset($param['offset']);
        }        
        if( isset($param['limit']) && !empty($param['limit']) ){
            $this->db->limit($param['limit']);
        }
        if( isset($param['where']) && !empty($param['where']) ){
            foreach($param['where'] as $whereIndex){                
                $this->db->where($whereIndex[0], $whereIndex[1]);
            }            
        }        
        $this->db->from('company');
        if($company){
            $this->db->where('id !=', $company['id']);
        }
        $rs = $this->db->get();
        if( isset($param['result']) && !empty($param['result']) ){
            if($param['result'] == 'row'){
                return $rs->row_array();
            }else{
                return $rs->result_array();
            }
        }
        return $rs->result_array();
    }

    /*
    * Insert and Update company in login system and other reference tables
    */
    private function insert_update_company($companies) {
    	$this->flexi_auth->change_config_setting('auto_increment_username', 1);
    	$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0);
    	
    	if(!isset($companies[0])){
    		$companies = [ 0 => $companies];
    	}

    	foreach($companies as $company) {
    		
    		if(empty($company['name'])) {
    			$company['name'] = $company['company_code'];
    		}
    		if(empty($company['email_address'])) {
    			$company['email_address'] = $company['company_code'];
    		}
    		/**
    		* Check either name or email should exist
    		*/
    		if(!empty($company['company_code']) or !empty($company['company_code'])) {

    			if($company['company_type'] !== 'cCustomer'){
    				unset($company['comp_exist']);
    				$this->insert($company);
    				continue;
    			}
				/**
				* If comp exist if account_id exist then go for update 
				*/
    			if($company['comp_exist'] && $company['comp_exist']['account_id']) {
					$profile_data = [
										'upro_uacc_fk' => $company['comp_exist']['account_id'],										
										'upro_first_name' => $company['name'],
										'upro_last_name' => '',
										'upro_phone' => $company['phone1'],
										'upro_newsletter' => 0,
									];

					$this->flexi_auth->update_custom_user_data(FALSE, FALSE, $profile_data);
					unset($company['comp_exist']);
					$param = [];
					$param['data'] = $company;
					$param['where']['company_code'] = $company['company_code'];
					$this->update_record($param);

    			} else { 
    				/**
    				* If company exist but account id does not exist means have no system reference 
    				* it will be deleted
    				*/
    				if($company['comp_exist']) {
    					$param = [];    					
    					$param['where']['company_code'] = $company['company_code'];
    					$this->delete_record($param);
    				}
    				unset($company['comp_exist']);
    				$email = $company['email_address'];
					$username = $company['name'];
					$password = generate_random_password();
					$profile_data = [
										'upro_first_name' => $company['name'],
										'upro_last_name' => '',
										'upro_phone' => $company['phone1'],
										'upro_newsletter' => 0,
								];	

					$response = $this->flexi_auth->insert_user($email, $username, $password, $profile_data, 1, FALSE);
					if($response){
						$company['account_id'] = $response;
						$company['account_password'] = $this->encryption->encrypt($password).'-'.$password;
						$this->insert($company);
					}
    			}
    		}
    	}
    	
    	$this->flexi_auth->change_config_setting('auto_increment_username', 0);
    	$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1);    	
    }

    /*
    * Not in use
    * Insert and Update customer in login system and other reference tables
    */
    private function insert_update_customer($comp_customers) {
    	$this->load->model('Companycustomermodel');
    	$this->flexi_auth->change_config_setting('auto_increment_username', 1);
    	$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0);
    	
    	foreach($comp_customers as $customer){
    		if(empty($customer['first_name'])){
    			$customer['first_name'] = $customer['name'];
    		}

    		if(!empty($customer['email'])){
    			$param = [];
    			$param['result'] = 'row';
    			$param['where']['email'] = $customer['email'];
    			$existcustomer = $this->Companycustomermodel->get_all($param);
				
    			if($existcustomer && isset($existcustomer['account_id']) && $existcustomer['account_id']){
					$profile_data = array(
						'upro_uacc_fk' => $existcustomer['account_id'],
						'upro_company' => $customer['company_code'],
						'upro_first_name' => $customer['first_name'],
						'upro_last_name' => $customer['last_name'],
						'upro_phone' => $customer['phone1'],
						'upro_newsletter' => 0,
					);					
					$this->flexi_auth->update_custom_user_data(FALSE, FALSE, $profile_data);
					$param = [];
					$param['data'] = $customer;
					$param['where']['email'] = $customer['email'];
					$this->Companycustomermodel->update_record($param);
    			}else{
    				$email = $customer['email'];
					$username = $customer['name'];
					$password = generate_random_password();
					$profile_data = [									
									'upro_company' => $customer['company_code'],
									'upro_first_name' => $customer['first_name'],
									'upro_last_name' => $customer['last_name'],
									'upro_phone' => $customer['phone1'],
									'upro_newsletter' => 0,
								];
					$response = $this->flexi_auth->insert_user($email, $username, $password, $profile_data, 1, FALSE);					
					if($response){
						$customer['account_id'] = $response;
						$this->Companycustomermodel->insert($customer);
					}
    			}
    		}
    	}
    	
    	$this->flexi_auth->change_config_setting('auto_increment_username', 0);
    	$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1);
    }

    function listAll(){
 		$param = [];
 		$param['select'] = 'id, company_code, name, email_address, contact_person';
 		$param['result'] = 'all';
 		$param['where']['company_type'] = 'cCustomer';
    	return $this->get_all($param);
    }

    function getCompanyProfile($company_id){

    	$company = [];
 		$this->get_by_pk($company_id, true);

	    $param = [];
	    $param['result'] = 'row';
    	$param['select'] = "$this->tbl_alias.*,    				
	                        (jt1.uacc_active) AS account_active,
	                        (jt1.uacc_suspend) AS account_suspend
                          ";

    	$param['join'][] = ['tbl' => $this->db->dbprefix('user_accounts'). ' as jt1',
                            'cond' => "jt1.uacc_id=account_id",
                            'type' => 'left',
                            'pass_prefix' => 0
                          ];
    	$param['where'] = [ "$this->tbl_alias.id" => $company_id];

    	$company['details'] = $this->get_all($param);

    	$company_code = $company['details']['company_code'];

		$this->load->model('Companyaddressmodel');
    	$param = [];
    	$param['where'] = [ "company_code" => $company_code];
		$company['addresses'] = $this->Companyaddressmodel->get_all($param);

		$this->load->model('Companycustomermodel');
    	$param = [];
    	$param['where'] = [ "company_code" => $company_code ];
		$company['employees'] = $this->Companycustomermodel->get_all($param);

		return $company;
    }

    function getCompanyList() {

    	$param = [];
    	$param['select'] = "company_code, name";
    	$this->data['company_list'] = $this->get_all($param); 
    	/*
    		return com_makelist($this->get_all($param), 'company_code' ,'name' );
		*/
    }

	function getLoggedCmpDept( ){
		
		$this->load->model('Departmentmodel');        
		return com_makelist( $this->Departmentmodel->getCompanyDept(), 'id', 'name', 0);

	}

	function getCompanyDetailFromCompanyCode( $companyCode ){		
        $param = [];
        $param[ 'result' ] = 'row';
        $param[ 'where' ][ 'company_code' ] = $companyCode;
        return $this->get_all( $param );
	}
}