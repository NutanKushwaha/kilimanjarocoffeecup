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


	function company_add_logo( $comp ){
		$output = [];
		$output[ 'status'] 	= FALSE;
		$output[ 'msg' ] 	= "";
        //Upload Image
        $config['upload_path'] 	= $this->config->item('COMPANY_LOGO_IMAGE_PATH');
		$config['allowed_types']= 'gif|jpg|png';
		$config['encrypt_name'] = TRUE;
        $config['overwrite'] 	= TRUE;
        $this->load->library('upload');			
        if (count($_FILES) > 0) {
			$this->upload->initialize( $config );
            //Check for valid image upload
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if (!$this->upload->do_upload('image')) {
					$output[ 'status'] 	= FALSE;
					$output[ 'msg' ] 	= $this->upload->display_errors();
                } else {
					$company_logo = "";
                    $upload_data = $this->upload->data();
                    $company_logo = $upload_data['file_name'];
                    $logo_dim = [									
									0 => [ 'width' => '150', 'height' => '75' ],
									1 => [ 'width' => '200', 'height' => '100' ], 
									2 => [ 'width' => '50', 'height' => '50' ],
									3 => [ 'width' => '250', 'height' => '150' ],
								];
					foreach($logo_dim as $ind => $dim){
						$params = [
								'image_url' => $this->config->item('COMPANY_LOGO_IMAGE_URL') . $company_logo,
								'image_path' => $this->config->item('COMPANY_LOGO_IMAGE_PATH') . $company_logo,
								'resize_image_url' => $this->config->item('COMPANY_LOGO_RESIZED_IMAGE_URL'),
								'resize_image_path' => $this->config->item('COMPANY_LOGO_RESIZED_IMAGE_PATH'),
								'width' => $dim[ 'width' ],
								'height' => $dim[ 'height' ],
								];
						$new_image_url = resize($params);
					}
					$comp_logo = [];
					$comp_logo[ 'company_logo' ] = $company_logo;
					$this->db->where('id', $comp[ 'id' ])
							->update('company', $comp_logo);
					$output[ 'status'] 	= TRUE;
					$output[ 'msg' ] 	= "Upload success";
                }
            }
        }
        return $output;
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
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
		$sql_where['upro_company'] = $company_code;
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

	/* 
		for logged user return product
	*/
	function getLoginCompProdAlloc( $opt = [] ) {
		extract( $opt );
		$param = [];
		$param['select'] = 'jt1.product_name,jt1.product_sku,jt1.product_price,
							jt1.product_point,jt1.product_image';
		$param['from_tbl'] = 'company_prod_assign';
		$param['join'][] = 	['tbl' => $this->db->dbprefix('product'). ' as jt1',
                            'cond' => "jt1.product_sku=product_code",
                            'type' => 'inner'
                          	];
        $param['where'][ 'jt1.product_is_active' ] = 1;
		$param['where'][ 'company_code' ] = $company_code;
		if( isset($limit) && $limit ){
			$param['limit'] = [ 'limit' => $limit, 'offset' => $offset ];
		}
		if( isset( $category )  ){
			$category = intval( $category );
			if( $category ) {
				$param['join'][] = 	['tbl' => $this->db->dbprefix('category'). ' as jt2',
		                            	'cond' => "jt1.category_id=jt2.category_id",
		                            	'type' => 'inner'
		                      		];
				$param['where'][ 'jt2.category_id' ] = $category;
			}
		}
		return $this->get_all($param);
	}

	function getCompAssignProdCount( $opts = array()){		
		extract( $opts );
		$this->db->select('count(company_code) as ttl')
				->join($this->db->dbprefix( 'product' ).' as prod',
					'prod.product_sku=assign.product_code', 'left', FALSE);
		if( isset($category) ) {
			$category = intval( $category );
			if( $category ){
				$this->db->join($this->db->dbprefix( 'category' ).' as catg', 'catg.category_id=prod.category_id', 'left', FALSE)
						->where( 'catg.category_id',  $category);
			}
		}
		$this->db->from('company_prod_assign as assign')
						->where( 'prod.product_is_active', 1)						
						->where('company_code' , $comp_code);
		if( isset( $search_product ) && !empty($search_product) ){
			if( $exact_match ){
				$this->db->where('product_sku', $search_product)
						->or_where('product_name', $search_product);
			} else {
				$this->db->where(' (product_name like "%' . $search_product 
								. '%" or product_sku like "%' . $search_product . '%") ');
			}
		}
		return	$this->db->get()->row_array();
	}

	function getCompAssignProdCat( $opts = array() ){
		extract( $opts );
		return	$this->db->select('distinct(catg.category_id), catg.category, catg.category_alias')
					->from('company_prod_assign as assign')
					->join('product as prod', 'prod.product_sku=assign.product_code', 'left')
					->join('category as catg', 'catg.category_id=prod.category_id', 'left')
					->where('company_code' , $comp_code)
					->where('c_active' , 1)
					->order_by('category')
					->get()
					->result_array();
	}

	function getCompAssignProdWithDetails($comp_code, $offset = 0, $limit = 0, $params = [] ){		
		if($limit) $this->db->limit($limit);
		if($offset) $this->db->offset($offset);
		$this->db->select('prod.product_name, prod.product_sku')
				->from('company_prod_assign as assign')
				->join('product as prod', 'prod.product_sku=assign.product_code', 'left')
				->where('company_code' , $comp_code);
		if( isset( $params[ 'search_product' ] ) && $params[ 'search_product' ] 
			&& !empty( $params[ 'search_product' ] ) ){
			if( $params[ 'exact_match' ] ){
				$this->db->where('product_sku', $params[ 'search_product' ])
						->or_where('product_name', $params[ 'search_product' ]);
			} else {
				$this->db->where(' (product_name like "%' . $params[ 'search_product' ] . '%" or product_sku like "%' . $params[ 'search_product' ] . '%") ');						
			}
		}
		
		return	$this->db->get()->result_array();

	}

	/*
	 	Return product details as per company and department allocation
	 */
	function getLoggedCmpDeptProdDet($dept_id ){
		        
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
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
		if($data) {
	        $customer_xml_fields = ['CardCode', 'CardName', 'CardType', 'Phone1', 'Phone2',
	        						'CntctPrsn', 'ListNum', 'Currency', 'E_Mail', 'Balance'
	        						];
	        $customer_data_fields = [
	        						'CardCode' => $this->tbl_cols['company_code'], 
	                            	'CardName' => $this->tbl_cols['name'],
		                            'CardType' => $this->tbl_cols['company_type'], 		                            
		                            'Phone1' => $this->tbl_cols['phone1'],
		                            'Phone2' => $this->tbl_cols['phone2'],
									'CntctPrsn' => $this->tbl_cols['contact_person'],																		
									'ListNum' => $this->tbl_cols['price_list'],
	        						'Currency' => $this->tbl_cols['currency'], 
	        						'E_Mail' => $this->tbl_cols['email_address'], 	        						
	        						'Balance' => $this->tbl_cols['balance'], 
	                        		];

	        $customer_addr_xml_fields = [ 'Street', 'ZipCode', 'City', 'County',
	        						'Country', 'AdresType'
	        						];
	        $customer_addr_data_fields = [
									'BPCode' => 'company_code',
									'AddressName' => 'address_name',
									'Street' => 'street',
									'ZipCode' => 'zip_code',
									'City' => 'city',
									'County' => 'county',
	        						'Country' => 'country',
	        						'AdresType' => 'address_type',
	                        		];

	        $customer_emp_xml_fields = ['CardCode', 'Name', 'Tel1', 'E_MailL', 'Active', 
	        							'FirstName', 'LastName', 'Position', 'Address', 'Tel2', 'Title', 'Fax', 'MiddleName'
										];
	        $customer_emp_data_fields = [
	        							'CardCode'=> 'company_code',
	        							'Name' => 'name',	        							
	        							'Tel1' => 'phone1',	        							
	        							'E_MailL'=> 'email',
	        							'Active' => 'active',	        							
	        							'FirstName' => 'first_name',	        							
	        							'LastName' => 'last_name',
	        							'Position'	=> 'position',
	        							'Address'	=> 'address',
	        							'Tel2'	=> 'phone2',
	        							'Title'	=> 'title',
	        							'Fax'	=> 'fax',
	        							'MiddleName'	=> 'middle_name',
										];
			
            $customer_insert = [];
            $customer_emp_insert = [];
            $customer_address_insert = [];
			/*	
				Customer Field
			*/
			$customer_detail = [];
			$customer_address = [];
			$customer_contact = [];
			if( !isset( $data['BusinessPartners'][ 0 ] ) ){
				 $businessPartners = $data['BusinessPartners'];
				 unset( $data['BusinessPartners'] );
				 $data['BusinessPartners'][ 0 ] = $businessPartners;
			}
			foreach($data['BusinessPartners'] as $key => $partner){				
				$customer_card_code = 	$partner[ 'CardCode' ];				
				if( $customer_card_code ){
					foreach($customer_xml_fields as $fieldK => $fieldText ){
						if(  isset( $partner[ $fieldText ] ) && !is_array( $partner[ $fieldText ] )  ){
							$customer_detail[ $customer_card_code ][ $customer_data_fields[ $fieldText ] ] = $partner[ $fieldText ];
						}
					}
					
					$address_index = 0;
					foreach($partner[ 'Addresses' ] as $addrK => $addrDet ){
						if( is_array( $addrDet ) ){
							$customer_address[ $customer_card_code ][ $address_index ][ 'company_code' ] = $customer_card_code;
							$customer_address[ $customer_card_code ][ $address_index ][ 'address_name' ] = $partner[ 'CardName' ];
							foreach($customer_addr_xml_fields as $addFieldK => $addFieldText ){
								if(  isset( $addrDet[ $addFieldText ] ) && !is_array( $addrDet[ $addFieldText ] )  ){
									$customer_address[ $customer_card_code ][ $address_index ]
										[ $customer_addr_data_fields[ $addFieldText ] ] = $addrDet[ $addFieldText ];
								}								
							}
						}
						$address_index++;
					}					
					if( !isset( $partner[ 'ContactEmployees' ][ 0 ] ) ){
						$contact_employee = $partner[ 'ContactEmployees' ];
						unset( $partner[ 'ContactEmployees' ] );
						$partner[ 'ContactEmployees' ][ 0 ] = $contact_employee;
					}					
					$customer_index = 0;
					foreach($partner[ 'ContactEmployees' ] as $empK => $empDet ){
						if( is_array( $empDet ) ){
							foreach($customer_emp_xml_fields as $empFieldK => $empFieldText ){
								if( isset( $empDet[ $empFieldText ] )  && !is_array( $empDet[ $empFieldText ] )  ){
									$customer_contact[ $customer_card_code ][ $customer_index ]
									[ $customer_emp_data_fields[ $empFieldText ] ] = $empDet[ $empFieldText ];
								}								
							}
						}
						$customer_index++;
					}
				}
			}

			if( $customer_detail ){
            $customer_xml_data_default_fields = 
								[ 	'company_code'=> '',		'added_on'=> '',
									'updated_on'=> '',			'name'=> '',
									'company_type'=> '',		'group'=> '0',
									'phone1'=> '',				'phone2'=> '',
									'contact_person'=> '',		'pay_terms'=> '',
									'credit_limit'=> '0.00',	'price_list'=> '',
									'currency'=> '',			'email_address'=> '',
									'credit_balance'=> '0.00',	'balance'=> '0.00',
								];
				foreach( $customer_detail as $customer_code => $customer_detail ){
					$customer_insert = [];
					$customer_emp_insert = [];
					$customer_address_insert = [];
					$company_added_on = $company_updated_on = 0;					
					$company_code = trim($customer_code);
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
					$customer_xml_data_fields = array_replace($customer_xml_data_default_fields, $customer_detail);
					$customer_xml_data_fields['added_on'] = $company_added_on;
					$customer_xml_data_fields['updated_on'] = $company_updated_on;
					$customer_xml_data_fields['comp_exist'] = $comp_exist;					
					$customer_insert[ $company_code ] = $customer_xml_data_fields;
										
					$customer_addr_xml_data_default_fields = [
							'company_code'=> '',	'address_name'=> '',
							'street'=> '',			'zip_code'=> '',
							'city'=> '',			'county'=> '',
							'country'=> '',			'address_type'=> '',
							'address_name2'=> '',	'address_name3'=> '',
					];
					if( isset($customer_address[ $company_code ] ) ){
						if( !isset( $customer_address[ $company_code ][ 0 ] ) ){
							$customer_address[ $company_code ][ 0 ] = $customer_address[ $company_code ];
						}
						foreach($customer_address as $customer_code => $address_details ){							
							if( is_array( $address_details ) ){
								foreach($address_details as $addInd => $addDet  ){
									$customer_addr_xml_data_fields = array_replace($customer_addr_xml_data_default_fields, $addDet);									
									$customer_address_insert[ $company_code ][ ] = $customer_addr_xml_data_fields;
								}
							}
						}
					}
					
					$customer_emp_xml_data_default_fields = [
						'company_code'=> '',			'title'=> '',
						'name'=> '',					'first_name'=> '',
						'middle_name'=> '',				'last_name'=> '',
						'position'=> '',				'address'=> '',
						'phone1'=> '',					'phone2'=> '',
						'mobile'=> '',					'fax'=> '',
						'email'=> '',					'active' => '1',
					];
					
					if( isset($customer_contact[ $company_code ] ) ){
						if( !isset( $customer_contact[ $company_code ][ 0 ] ) ){
							$customer_contact[ $company_code ][ 0 ] = $customer_contact[ $company_code ];
						}
						foreach($customer_contact as $customer_code => $customer_details ){
							if( is_array( $customer_details ) ){
								foreach($customer_details as $cntInd => $cntDet ){
									$cnt_active = false;
									if( isset( $cntDet[ 'active' ] ) && strtolower($cntDet[ 'active' ]) == 'tyes' ){
										$cnt_active = true;
									}
									$cntDet[ 'active' ] = $cnt_active;
									$customer_emp_xml_data_fields = array_replace($customer_emp_xml_data_default_fields, $cntDet);
									$customer_emp_insert[ $company_code ][ ] = $customer_emp_xml_data_fields;
								}
							}
						}
					}					
				}
			}					
			$retBool = true;
			foreach( $customer_insert as $customer_code => $customer_det ){
				/*
				* Insert and update company
				*/
				$this->insert_update_company( $customer_det );
				if($existCompany){
					/*
					* Delete company address
					*/
					$this->db->where('company_code', $customer_code)
							->delete('company_address');
					/*
					* Delete company contact employee
					*/
					$this->db->where('company_code', $customer_code)
							->delete('company_customer');
				}
				/*
				* Insert address and contact employee
				*/
				$this->db->insert_batch('company_address', $customer_address_insert[ $customer_code ]);
				$this->db->insert_batch('company_customer', $customer_emp_insert[ $customer_code ]);
			}		
		}
		return $retBool;
	}


	function insertCompany($data) {		
		// $retBool = false;
		// if($data) {
		// 		//echo "<pre>";
		// 	//print_r($_POST); exit;

	 //        // $customer_xml_fields = ['CardCode', 'CardName', 'CardType', 'Phone1', 'Phone2',
	 //        // 						'CntctPrsn', 'ListNum', 'Currency', 'E_Mail', 'Balance'
	 //        // 						];
	 //        $customer_data_fields = [
	 //        						'CardCode' => $_POST['company_code'], 
	 //                            	'CardName' => $_POST['upro_first_name'],
		//                             'CardType' => 'cCustomer', 		                            
		//                             'Phone1' => $_POST['uadd_phone'],
		//                             //'Phone2' => $_POST['phone2'],
		// 							'CntctPrsn' => $_POST['upro_first_name'],																		
		// 							//'ListNum' => $this->tbl_cols['price_list'],
	 //        						//'Currency' => $_POST['currency'], 
	 //        						'E_Mail' => $_POST['uacc_email'], 	        						
	 //        						//'Balance' => $this->tbl_cols['balance'], 
	 //                        		];




$return_bool = false;
		$this->validation_rules = $this->form_rules;
		/*
		com_e( $this->validation_rules , 0);
		com_e( $this->input->post() );
		*/	
		if($this->validate()) {			
			/*
				$instant_active = TRUE;
				$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0); 
			*/			
			/* Get approval from if available basically group mentioned then realted person will find out */
			$logged_user_get_approval_from = $this->flexi_auth->get_user_custom_data( 'ugrp_aprrove_from' );
			/* Fully approval for create user straight */
			$logged_user_is_fully_approved = $this->flexi_auth->get_user_custom_data( 'ugrp_fully_approved' );
			
			$instant_active = FALSE;
			$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0); 
			
			if( $logged_user_is_fully_approved ){
				$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1);
				if($_POST['activation_type'] == 'direct'){
					$instant_active = TRUE;
					$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0); 
				}
			}
			
			$config = [];
            $config['upload_path'] = $this->config->item('UPLOAD_USERS_IMG_PATH');
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $user_profile_pic = $this->upload_file($config);
            if($user_profile_pic === FALSE){
                return FALSE;
            }
            $params = [     'image_url' => $this->config->item('UPLOAD_USERS_IMG_URL').$user_profile_pic,
                            'image_path' => $this->config->item('CATEGORY_IMAGE_PATH').$user_profile_pic,
                            'resize_image_url' => $this->config->item('UPLOAD_USERS_RESIZE_IMG_URL'),
                            'resize_image_path' => $this->config->item('UPLOAD_USERS_RESIZE_IMG_PATH'),
                            'width' => 50,
                            'height' => 50,
                    ];
            $new_image_url = resize( $params );
			$email = $_POST['uacc_email'];
			$username = ucfirst( $_POST['uacc_username'] );
			$password = $_POST['uacc_password'];
			$profile_data = [								
								'upro_pass'	=> $this->encrypt->encode($password).'-'.$password,
								'upro_image' => $user_profile_pic,
								'upro_first_name' => ucfirst( $_POST['upro_first_name'] ),
								'upro_last_name' => ucfirst( $_POST['upro_last_name'] ),
								'upro_phone' => $this->input->$_POST['uadd_phone'],
								'upro_newsletter' => 0,
								'upro_creater_id' => $this->flexi_auth->get_user_id(),
								'uadd_recipient' => ucfirst( $_POST['uadd_recipient'] ),
								'uadd_phone' => $_POST['uadd_phone'],
								'uadd_company' => ucfirst( $_POST['company_code'] ),
								'uadd_address_01' => $_POST['uadd_address_01'],
								'uadd_address_02' => $_POST['uadd_address_02'],
								'uadd_city' => ucfirst( $_POST['uadd_city'] ),
								'uadd_county' => ucfirst( $_POST['uadd_county'] ),
								'upro_profession' => $_POST['upro_profession'],
								'uadd_post_code' => $_POST['uadd_post_code'],
								'uadd_country' => ucfirst( $_POST['uadd_country'] )
								//'upro_department' => json_encode( $_POST['upro_department'] ),
						];
			
			/*				
				$user_group_id = $this->flexi_auth->get_user_group_id();			
				7=> Purchase manager, 6=> Director 1=> Company customer
			 */
			$logged_user_group = $this->flexi_auth->get_user_group();
			$new_user_profile = 2;						
			
				/* company variable come  */
				$company_code = $_POST['company_code'];
			
			$profile_data['uadd_company'] = $company_code;
			$profile_data['upro_company'] = $company_code;			
			/* Company customer or Purchase manager */			
			if( $new_user_profile == 2 or $new_user_profile == 7 ){
				$profile_data['upro_approval_acc'] = 3;
			} else if( $new_user_profile == 6 ){
				/* Company director */
				$opt = [];
				$opt[ 'result' ] = 'row';
				$opt[ 'select' ] = 'account_id';
				$opt[ 'from_tbl' ] = 'company';
				$opt[ 'where' ][ 'company_code' ] = $company_code;
				$comp_details = $this->get_all( $opt );				
				$profile_data['upro_approval_acc'] = $comp_details[ 'account_id' ];
			}			
			/* 2016/04/03 12:13:55	
			 * User admin personal user eliminated for now 
			 * $new_user_profile = $user_group_id;
			 * $profile_data['upro_subadmin']  = 1;
			 * admin user removed so now only company user			 
			 * */

			
			$tmp = [ $email, $username, $password, $profile_data, $new_user_profile, $instant_active ];
			$user_id = $this->flexi_auth->insert_user($email, $username, $password, $profile_data, $new_user_profile, $instant_active);
			
			// Get users group privilege data.
			$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
			$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $new_user_profile);
			$user_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
			
			$logged_in_privileges= array_column($user_privileges, $this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
			if( is_array($logged_in_privileges) && $logged_in_privileges) {
				foreach( $logged_in_privileges as $privilege) {
					$this->flexi_auth->insert_privilege_user($user_id, $privilege);	
				}
			}
			
			// if( $user_id && $this->input->post('upro_department')){
			// 	$opts = [];
			// 	$opts[ 'user_id' ] = $user_id;
			// 	$opts[ 'dept_ids' ] = $this->input->post('upro_department');
			// 	$this->_create_user_product_policy( $opts );
			// }
						
			if( $logged_user_is_fully_approved ){
				if($this->input->post('activation_type', true) == 'direct'){
					$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1); 
				}
			}
				$company['name'] = $company_code;
				$company['company_code'] = $company_code;
				$company['email_address'] =$email = $_POST['uacc_email'];
				$company['account_password'] = $this->encrypt->encode($password).'-'.$password;
				$company['phone1'] = $_POST['uadd_phone'];
				$company['company_type'] = 'cCustomer';
				$company['account_id'] = $user_id;
				$company['contact_person'] = $username;

				$this->insert_update_company($company);

			$return_bool = true;
		}		
		return $return_bool;





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

	function getCompanyAddress($param = []){
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
        $this->db->from('company_address');        
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
	
	function getCompanyEmployee($param = []){
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
        $this->db->from('company_customer');
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
    	// 		if($company['comp_exist'] && $company['comp_exist']['account_id']) {
					// $profile_data = [
					// 					'upro_uacc_fk' => $company['comp_exist']['account_id'],										
					// 					'upro_first_name' => $company['name'],
					// 					'upro_last_name' => '',
					// 					'upro_phone' => $company['phone1'],
					// 					'upro_newsletter' => 0,
					// 				];

					// $this->flexi_auth->update_custom_user_data(FALSE, FALSE, $profile_data);
					// unset($company['comp_exist']);
					// $param = [];
					// $param['data'] = $company;
					// $param['where']['company_code'] = $company['company_code'];
					// $this->update_record($param);

    	// 		} else { 
    	 			/**
    				* If company exist but account id does not exist means have no system reference 
    				* it will be deleted
    				*/
    				// if($company['comp_exist']) {
    				// 	$param = [];    					
    				// 	$param['where']['company_code'] = $company['company_code'];
    				// 	$this->delete_record($param);
    				// }
    				//unset($company['comp_exist']);
    				$company['name'] = html_escape(preg_replace('/\s+/', '', $company['name']));
    				$email = $company['email_address'];
					$username = $company['name'];
					//$password = $company['account_password'];
					$profile_data = [
										'upro_first_name' => $company['name'],
										'upro_last_name' => '',
										'upro_phone' => $company['phone1'],
										'upro_newsletter' => 0,
								];	

					//$response = $this->flexi_auth->insert_user($email, $username, $password, $profile_data, 2, FALSE);
					//if($response){7
						//$company['contact_person'] = $company['contact_person'];
						$company['email_address'] = $company['email_address'];		
						$company['account_id'] = $company['account_id'];
						$company['group'] = 0;						
						$this->insert($company);						
					//}
    			//}
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
					$customer['name'] = html_escape(preg_replace('/\s+/', '', $customer['name']));
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

    function getCompanyProfile($company_id, $checkExist = false){

    	$company = [];
 		$company = $this->get_by_pk($company_id, false);
		if( !$company ){
			return false;
		}
		if( $checkExist ){
			if( $company ){
				return $company;
			}
		}
		
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

    function getCompanyList( $opt = [] ) {
		extract( $opt );
    	$param = [];
    	$param['select'] = "company_code, name ,account_id";
    	if( isset( $company_code ) && !empty( $company_code ) ){
			$param[ 'where' ][ 'company_code' ] = $company_code;
		}
    	$this->data['company_list'] = $this->get_all($param); 
    	/*
    		return com_makelist($this->get_all($param), 'company_code' ,'name' );
		*/
    }

	function getLoggedCmpDept( $comp_code = null ){		
		$this->load->model('company/Departmentmodel', 'Departmentmodel');
		return com_makelist( $this->Departmentmodel->getCompanyDept( $comp_code ), 'id', 'name', 0);

	}

	function getCompanyDetailFromCompanyCode( $companyCode ){
        $param = [];
        $param[ 'result' ] = 'row';
        $param[ 'where' ][ 'company_code' ] = $companyCode;
        return $this->get_all( $param );
	}

	function getCompanyStock( $opts = []){
		extract( $opts );
        $param = [];
        $param[ 'result' ] = com_arrIndex($opts, 'result', 'result' ) ;
        $param[ 'select' ] = 'sum( stock_qty) as ttl, product_code, product_name, product_image';
        $param[ 'from_tbl' ] = 'company_stock';
     	$param[	'join'	][	] = [
     								'tbl' => $this->db->dbprefix('product'). ' as jt1',
                            		'cond' => "jt1.product_sku=product_code",
                            		'type' => 'inner',
                            		'pass_prefix' => 0
                          		];
		$param[ 'where' ][ 'store_id' ] = $store_id;
      	$param[ 'where' ][ 'company_code' ] = $company_code;
      	$param[ 'group' ] = 'product_code';
      	if( isset( $opts[ 'product_sku' ] ) && $opts[ 'product_sku' ] && is_array( $opts[ 'product_sku' ] ) ){
      		$param[ 'where' ][ 'in' ][ '0' ] = 'product_code';
      		$param[ 'where' ][ 'in' ][ '1' ] = $opts[ 'product_sku' ];
      	}
        return $this->get_all( $param );
	}

	function getCompanyStoreStock( $opts = []){
		extract( $opts );
		$startDate 	=	date('Y-m-d 00:00:00', strtotime('01/01'));		
		$endDate 	= 	date('Y-m-d H:i:s');
		$stock_result = $this->db->query('call company_stock(?,?,?,?)',
										array('startDate'=>$startDate, 
											'endDate'=>$endDate, 
											'company_code'=>$company_code, 
											'prefix'=>$this->db->dbprefix, 
											));
		mysqli_next_result($this->db->conn_id);			
		return $stock_result->result_array();
	}

	function getCompanyStoreUserIssuedStock( $opts = []){
		extract( $opts );
		$startDate 	=	date('Y-m-d 00:00:00', strtotime('01/01'));		
		$endDate 	= 	date('Y-m-d H:i:s');
		$stock_result = $this->db->query('call company_user_issued_stock(?,?,?,?)',
										array('startDate'=>$startDate, 
											'endDate'=>$endDate, 
											'company_code'=>$company_code, 
											'prefix'=>$this->db->dbprefix, 
											));
		mysqli_next_result($this->db->conn_id);			
		return $stock_result->result_array();
	}
	
	function getLyearCompanyStoreStockCarryForward( $opts = []){
		extract( $opts );
		$last_year = date('Y') - 1;
		$startDate 	=	date('Y-m-d 00:00:00', mktime(0, 0, 0, 1, 1, $last_year));
		$endDate 	= 	date('Y-m-d H:i:s', mktime(23, 59, 59, 12, 31, $last_year));
		$stock_result = $this->db->query('call company_stock_carry_forward(?,?,?,?)',
										array('startDate'=>$startDate, 
											'endDate'=>$endDate, 
											'company_code'=>$company_code, 
											'prefix'=>$this->db->dbprefix, 
											));
		mysqli_next_result($this->db->conn_id);			
		return $stock_result->result_array();
	}
	
	function getCompanyStoreStockExchange( $opts = []){
		extract( $opts );
		$startDate 	=	date('Y-m-d 00:00:00', strtotime('01/01'));
		$endDate 	= 	date('Y-m-d H:i:s');
		$stock_result = $this->db->query('call company_stock_exchange(?,?,?,?)',
										array('startDate'=>$startDate, 
											'endDate'=>$endDate, 
											'company_code'=>$company_code, 
											'prefix'=>$this->db->dbprefix, 
											));
		mysqli_next_result($this->db->conn_id);			
		return $stock_result->result_array();
	}
			
	function getCompanyIssuedStock( $opts = []){
		extract( $opts );
        $param = [];
        $param[ 'result' ] = com_arrIndex($opts, 'result', 'result' ) ;
        $param[ 'select' ] = 'sum( stock_qty) as ttl, product_code';
        $param[ 'from_tbl' ] = 'user_stock';
        $param[ 'where' ][ 'store_id' ] = $store_id;
      	$param[ 'where' ][ 'company_code' ] = $company_code;
      	$param[ 'group' ] = 'product_code';
      	if( isset( $opts[ 'product_sku' ] ) && $opts[ 'product_sku' ] && is_array( $opts[ 'product_sku' ] ) ){
      		$param[ 'where' ][ 'in' ][ '0' ] = 'product_code';
      		$param[ 'where' ][ 'in' ][ '1' ] = $opts[ 'product_sku' ];
      	}
        return $this->get_all( $param );
	}

	function getLyearCompanySingleStoreStockCarryForward( $opts = []){
		extract( $opts );
		$last_year = date('Y') - 1;
		$startDate 	=	date('Y-m-d 00:00:00', mktime(0, 0, 0, 1, 1, $last_year));
		$endDate 	= 	date('Y-m-d H:i:s', mktime(23, 59, 59, 12, 31, $last_year));		
        $param = [];
        $param[ 'result' ] = com_arrIndex($opts, 'result', 'result' ) ;
        $param[ 'select' ] = 'sum( stock_qty) as ttl, product_code';
        $param[ 'from_tbl' ] = 'company_stock_carry_forward';
        $param[ 'where' ][ 'store_id' ] = $store_id;
      	$param[ 'where' ][ 'company_code' ] = $company_code;
      	$param[ 'where' ][ 'period_from >= ' ] = $startDate;
      	$param[ 'where' ][ 'period_to <= ' ] = $endDate;
      	if( isset( $opts[ 'product_sku' ] ) && $opts[ 'product_sku' ] && is_array( $opts[ 'product_sku' ] ) ){
      		$param[ 'where' ][ 'in' ][ '0' ] = 'product_code';
      		$param[ 'where' ][ 'in' ][ '1' ] = $opts[ 'product_sku' ];
      	}
        return $this->get_all( $param );
	}

	function getCompanySingleStoreStockExchange( $opts = []){
		extract( $opts );		
		$startDate 	=	date('Y-m-d 00:00:00', strtotime('01/01'));
		$endDate 	= 	date('Y-m-d H:i:s');
        $param = [];
        $param[ 'result' ] = com_arrIndex($opts, 'result', 'result' );
        $param[ 'select' ] = 'sum( qty) as ttl, product_code, is_debit';
        $param[ 'from_tbl' ] = 'company_store_stock_exchange';
        $param[ 'where' ][ 'store_id' ] = $store_id;
      	$param[ 'where' ][ 'company_code' ] = $company_code;
      	$param[ 'where' ][ 'exchange_d_time >= ' ] = $startDate;
      	$param[ 'where' ][ 'exchange_d_time <= ' ] = $endDate;
      	$param[ 'group' ] = 'product_code, is_debit'; 
      	if( isset( $opts[ 'product_sku' ] ) && $opts[ 'product_sku' ] && is_array( $opts[ 'product_sku' ] ) ){
      		$param[ 'where' ][ 'in' ][ '0' ] = 'product_code';
      		$param[ 'where' ][ 'in' ][ '1' ] = $opts[ 'product_sku' ];
      	}
        return $this->get_all( $param );
	}
	
	function getStockRequests( $opts = []){
		extract( $opts );
        $param = [];
        $param[ 'result' ] = com_arrIndex($opts, 'result', 'result' ) ;
        $param[ 'select' ] = 'sum( stock_qty) as ttl, product_code, product_name, product_image';
        $param[ 'from_tbl' ] = 'company_stock';
     	$param[	'join'	][	] = [
     								'tbl' => $this->db->dbprefix('product'). ' as jt1',
                            		'cond' => "jt1.product_sku=product_code",
                            		'type' => 'inner',
                            		'pass_prefix' => 0
                          		];
      	$param[ 'where' ][ 'company_code' ] = $company_code;
      	$param[ 'group' ] = 'product_code';
        return $this->get_all( $param );
	}
	
	function updateCompanyRequestOrderMark($company_id, $comapny_request_mark){
		$opt = [];
		$opt[ 'data' ][ 'make_req_order' ] = $comapny_request_mark == 'active' ? 1: 0;
		$opt[ 'where' ][ 'id' ] = $company_id;
		$this->update_record( $opt );
		
	}
	
	function add_exchange_stock( $opt ){
		extract(  $opt );
		$qty = $this->input->post( 'qty' );
		$product_code = $this->input->post( 'product_code' );
		$from_store = $this->input->post( 'from_store' );
		com_changeNull( $from_store, 0);
		$to_store = $this->input->post( 'to_store' );
		com_changeNull( $to_store, 0);
		$this->load->model( 'Compstoreexchangemodel' );
		$exchangeTime = com_getDTFormat( 'mdatetime' );
		$related_id = 0;
		$data = [];
		$data[ 'qty' ] 			= $qty;
		$data[ 'related_id' ] 	= $related_id;
		$data[ 'is_debit' 	] 	= 1;
		$data[ 'store_id' 	] 	= $from_store;
		$data[ 'company_code' ] = $company_code;
		$data[ 'product_code' ] = $product_code;
		$data[ 'exchange_d_time' ] = $exchangeTime;
		$related_id = $this->Compstoreexchangemodel->insert( $data );
		$data = [];
		$data[ 'qty' ] 			= $qty;
		$data[ 'related_id' ] 	= $related_id;
		$data[ 'is_debit' 	] 	= 0;
		$data[ 'store_id' 	] 	= $to_store;
		$data[ 'company_code' ] = $company_code;
		$data[ 'product_code' ] = $product_code;
		$data[ 'exchange_d_time' ] = $exchangeTime;
		$this->Compstoreexchangemodel->insert( $data );
	}
	
	function company_update_config( $compDetail ){
		$output = [];
		$output[ 'status'] 	= TRUE;
		$output[ 'msg' ] 	= "Config updated successfully";
		$data = [];
		$data[ 'where' ][ 'id' ] = $compDetail[ 'id' ];
		$data[ 'data' ][ 'skip_policy' ] = $this->input->post( 'skip_policy' );
		$data[ 'data' ][ 'manage_stock' ] = $this->input->post( 'manage_stock' );
                $data[ 'data' ][ 'cat_suffix' ] = $this->input->post( 'cat_suffix' );
                com_changeNull($data[ 'data' ][ 'cat_suffix' ], 0);
		com_changeNull($data[ 'data' ][ 'skip_policy' ], 0);
		com_changeNull($data[ 'data' ][ 'manage_stock' ], 0);
		
		
		$data[ 'data' ][ 'theme_menu_base' ] = $this->input->post( 'menu-base-color' );
		$data[ 'data' ][ 'theme_menu_hover' ] = $this->input->post( 'menu-hover-color' );
		$this->update_record( $data );
		return $output;
	}
}
