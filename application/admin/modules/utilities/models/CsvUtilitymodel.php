<?php

class CsvUtilitymodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->product_fields = [];        
        $this->csv_data_report = [];
        $this->product_parent_code = [];
        $this->product_parent_type = [];
        $this->attribute_set_detail = [];
        $this->product_supplier_code = [];
        $this->load->model('CsvProductValidationmodel');
        $this->load->model('cpcatalogue/Productmodel', 'Productmodel');
        $this->load->model('cpcatalogue/Attributesetmodel', 'Attributesetmodel');
    }

    public $error = "";

    function readFile($csv) {
        $this->load->library('csv_reader');
        $csvfile = $this->csv_reader->parse_file($csv);
        return $csvfile;
    }

    function CsvOpertations($csvfile) {
        $iterator = 1;
        $this->product_fields = $this->db->list_fields( 'product' );        
        foreach ($csvfile as $product) {
			if( isset( $product['attribute_set_id'] ) && !empty( $product['attribute_set_id'] ) && 
			$this->Attributesetmodel->getAttributeSet( $product['attribute_set_id'] ) ){
				if( isset( $product['category_name'] ) && !empty( $product['category_name'] )  && !isset( $product['category_id'] ) ){
					$cat_name = trim( $product['category_name'] );
					$cat_det = $this->db->select( 'category_id' )
									->from( 'category' )
									->where( 'category',  $cat_name)->get()->row_array();
					if( $cat_det && isset( $cat_det[ 'category_id' ] )  && $cat_det[ 'category_id' ] ){
						$product['category_id'] = $cat_det[ 'category_id' ];
					}
				}
				if( isset( $product['category_id'] ) && !empty( $product['category_id'] ) ){
					if( !empty( $product['sku'] ) ){
						$new_prod_sku = $this->Productmodel->checkProductBySku($product['sku']);
						if ( $new_prod_sku ) {
							$product_status = 'Product already exist';
						} else {
							if( !isset( $this->attribute_set_detail[ $product['attribute_set_id'] ] ) ){
								$set_detail = [];
								$attribute_details = $this->Productmodel->fetchAttributes( $product['attribute_set_id'] );								
								foreach( $attribute_details as $attrSt => $attrDet ){
									if( $attrDet[ 'is_sys' ] ){
										$set_detail[ 'sys' ][ $attrDet[ 'label' ] ] = $attrDet[ 'sys_label' ];
									} else {
										$set_detail[ 'oth' ][ $attrDet[ 'id' ] ] = $attrDet[ 'label' ];
										$attribute_opt = $this->db->select( 'id, option_text' )
															->from( 'attributes_set_attributes_option' )->where('attribute_id',  $attrDet[ 'id' ])
															->get()->result_array();									
										$set_detail[ 'oth_opt' ][ $attrDet[ 'id' ] ] = com_makelist($attribute_opt, 'option_text', 'id', false);
									}								
								}							
								$this->attribute_set_detail[ $product['attribute_set_id'] ] = $set_detail;								
								unset( $set_detail );
								unset( $attribute_details );
							}
							$new_prod_id = 0;
							$product_to_insert = [];
							$product_attr_to_insert = [];
							foreach( $this->attribute_set_detail[ $product['attribute_set_id'] ][ 'sys' ] as $label => $sys_field ){
								$label = strtolower( $label );
								if( isset( $product[ strtolower( $label ) ] ) ){
									$product_to_insert[ $sys_field ] = $product[ $label ];
								}
							}
							foreach( $this->attribute_set_detail[ $product['attribute_set_id'] ][ 'oth' ] as $attribute_id => $attr_label ){
								$attr_label = strtolower( $attr_label );
								if( isset( $product[ $attr_label  ] ) ){
									if( isset( $this->attribute_set_detail[ $product['attribute_set_id'] ]
												[ 'oth_opt' ][ $attribute_id ][ $product[ $attr_label ] ] ) 
										&& !empty( $product[ $attr_label ] )
									 ){
										$product_attr_to_insert[ $attribute_id ][ 'product_id' ] 	= &$new_prod_id;
										$product_attr_to_insert[ $attribute_id ][ 'attribute_id' ] 	= $attribute_id;
										$product_attr_to_insert[ $attribute_id ][ 'attribute_value' ] = $this->attribute_set_detail[ $product['attribute_set_id'] ]
																						[ 'oth_opt' ][ $attribute_id ][ $product[ $attr_label ] ];
									}
									
								}
							}							
							foreach( $product as $label => $value ){
								if( in_array($label, $this->product_fields) ){
									$product_to_insert[ $label ] = $value;
								}
							}
							$parent_prod_id = "0";
							$parent_prod_sku = "";
							if( !isset( $product[ 'ref_product_code' ] ) ){
								com_e( $product );
							}
							if( $product[ 'ref_product_code' ] !== $product[ 'sku' ] && !empty( $product[ 'ref_product_code' ] ) ){
								if( isset( $this->product_parent_code[ $product[ 'ref_product_code' ] ] ) ){
									$parent_prod_id  = $this->product_parent_code[ $product[ 'ref_product_code' ] ];
									$parent_prod_sku = $product[ 'ref_product_code' ];
									$product_to_insert[ 'product_type_id' ] = 2;
								} else {
									$parent_prod_id = $this->Productmodel->checkProductBySku($product[ 'ref_product_code' ]);								
									$parent_prod_sku = $product[ 'ref_product_code' ];
									$this->product_parent_code[ $product[ 'ref_product_code' ] ] = $parent_prod_id;
									$product_to_insert[ 'product_type_id' ] = 2;
								}
							} else if ( empty( $product[ 'ref_product_code' ] ) || ( $product[ 'sku' ] == $product[ 'ref_product_code' ]  ) ) {
								$product_to_insert[ 'product_type_id' ] = 1;
							}
							$product_to_insert[ 'ref_product_id' ] = $parent_prod_id;
							$product_to_insert[ 'ref_product_sku' ] = $parent_prod_sku;							
							if( ($parent_prod_sku && $parent_prod_id) ||  ( $product[ 'ref_product_code' ] == $product[ 'sku' ])){
								if(  isset( $product[ 'supplier_code' ] ) && 
									$product[ 'supplier_code' ] && !empty( $product[ 'supplier_code' ] ) ){
									$supplier_id = 0;
									if( isset( $this->product_supplier_code[ $product[ 'supplier_code' ] ] ) ){
										$supplier_id = $this->product_supplier_code[ $product[ 'supplier_code' ] ];
									} else {
										$supplier_code	= $this->db->select( 'supplier_id' )
															->from( 'supplier' )
															->where('supplier_code', $product[ 'supplier_code' ])
															->get()->row_array();									
										if( $supplier_code ){
											$supplier_id = $supplier_code[ 'supplier_id' ];	
										} else if( isset( $product[ 'supplier_name' ] ) && !empty( $product[ 'supplier_name' ] ) ){
											$supplier_data = [];
											$supplier_data[ 'supplier_name' ] = $product[ 'supplier_name' ];
											$supplier_data[ 'supplier_code' ] = $product[ 'supplier_code' ];
											$supplier_id = $this->db->insert('supplier', $supplier_data);
										}
									}
									if( $supplier_id ){
										$product_to_insert[ 'supplier_id' ] = $supplier_id;
										$this->product_supplier_code[ $product[ 'supplier_code' ] ] = $supplier_id;
									}
								}
								if( $product_to_insert[ 'product_type_id' ] == 2 && $parent_prod_id){
									if( !isset(  $this->product_parent_type[ $parent_prod_id ] ) ){
										$update_config = [];
										$update_config[ 'product_type_id' ] = 2;
										$this->db->where('product_id', $parent_prod_id)
												->update('product', $update_config);
										$this->product_parent_type[ $parent_prod_id ] = 2;
									}
								}
								if( $product_to_insert ){
									$product_to_insert[ 'product_price' ] = floatval( $product_to_insert[ 'product_price' ] );
									if( empty( $product_to_insert[ 'product_price' ] ) ){
										$product_to_insert[ 'product_price' ] = 0.00;
									}
									$product_to_insert[ 'stock_level' ] = intval( $product_to_insert[ 'stock_level' ] );
									com_changeNull($product_to_insert[ 'product_price' ], 0);									
									$product_to_insert[ 'product_added_on' ] 	= time();
									$product_to_insert[ 'product_alias' ] 		= $this->Productmodel->_slug( $product_to_insert[ 'product_name' ] );									
									$new_prod_id = $this->db->insert('product', $product_to_insert);
									
									if( $new_prod_id && isset( $product_attr_to_insert ) && sizeof( $product_attr_to_insert ) ){										
										$new_prod_id = $this->db->insert_id();										
										$this->db->insert_batch('product_attribute', $product_attr_to_insert);
										$product_status = "Insert product and attribute successfully";
									} if( $new_prod_id ) {
										$product_status = "Insert product successfully";
									} else {
										$product_status = "Internal error during save";
									}									
								}								
							} else {
								$product_status = "Parent sku is not found";
							}
						}
					} else {
						$product_status = 'Product Sku is not filled';
					}
				} else {
					$product_status = 'Category is not filled';
				}
			} else {
				$product_status = 'Attribute Set is not filled';
			}
			if( !isset( $product[ 'name' ] )  ){
				com_e( $product	 );
			}
			$this->csv_data_report[ ] =[
										'Product_Name' 	=> $product[ 'name' ],
										'Product_Sku' 	=> $product[ 'sku' 	],
										'Parent_Sku' 	=> $product[ 'ref_product_code'	],
										'status' 		=> $product_status,
									];
            $iterator++;
        }        
        com_array_to_csv($this->csv_data_report, "product_upload_report.csv");
    }
    
    function userUpload( $csvfile ){		
        $iterator = 1;  
        foreach ($csvfile as $users) {			
			$return_bool = false;				
			$instant_active = FALSE;
			$user_profile_pic = "";
			$email = $users[ 'uacc_email'];
			$username = ucfirst( $users[ 'uacc_username' ] );
			$already_user = $this->flexi_auth->get_user_by_identity($email)->row_array();
			if( !$already_user ) {
				$password = $users[ 'uacc_password' ];
				$activation_type = $users[ 'activation_type' ];
				if($activation_type == 'direct'){
					$instant_active = TRUE;
					$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0); 
				}
				
				$dept = '';
				if( isset( $users[ 'urpo_department'] ) && !empty( $users[ 'urpo_department'] )  ){
					$users[ 'urpo_department'] = explode(",", $users[ 'urpo_department']);
					$dept = json_encode( $users[ 'urpo_department'] );
				}
				$profile_data = [
									'upro_pass'	=> $this->encrypt->encode($password).'-'.$password,
									'upro_image' => $user_profile_pic,
									'upro_first_name' => ucfirst( strtolower( $users['upro_first_name'] ) ),
									'upro_last_name' => ucfirst( strtolower($users['upro_last_name'] ) ),
									'upro_phone' => "",
									'upro_newsletter' => 0,
									'upro_creater_id' => $this->flexi_auth->get_user_id(),
									'uadd_recipient' => ucfirst( $users['uadd_recipient'] ),
									'uadd_phone' => "",
									'uadd_company' => "",
									'uadd_address_01' => "",
									'uadd_address_02' => "",
									'uadd_city' => "",
									'uadd_county' => "",
									'uadd_post_code' => "",
									'uadd_country' => "",
									'upro_department' => $dept,
							];
						$user_group = $users[ 'source_grp' ];					
						$new_user_profile = 1;
						/*
						if( $user_group == ADMIN){
							if ( $this->input->post('userProfile') == "comp") {
								$profile_data['uadd_company'] = $this->input->post('company');
								$profile_data['upro_company'] = $this->input->post('company');					
							}else{
								$new_user_profile = $user_group_id;
								$profile_data['upro_subadmin']  = 1;
							}
						}else 
						*/
						if ($user_group == CMP_ADMIN ){
							$user_group_id = 1;
							$company_code = $users[ 'company_code' ];
							$profile_data['uadd_company'] = $company_code;
							$profile_data['upro_company'] = $company_code;
						}
						$check = array( $email, $username, $password, $profile_data, $new_user_profile, $instant_active );					
						$user_id = $this->flexi_auth->insert_user($email, $username, $password, $profile_data, $new_user_profile, $instant_active);
						if( $user_group ==  ADMIN ){
							// Get users current privilege data.
							$sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
							$sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => $this->flexi_auth->get_user_id());
							$user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);

							$logged_in_privileges= array_column($user_privileges, $this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
							foreach( $logged_in_privileges as $privilege) {
								$this->flexi_auth->insert_privilege_user($user_id, $privilege);	
							}
						}
						else if ( $user_group ==  CMP_ADMIN ) {
							
							if( isset( $users['is_sub_admin'] ) ) {
								// Get users current privilege data.
								$sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
								$sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => 2);
								$user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);

								$logged_in_privileges= array_column($user_privileges, $this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
							}else{
								// Get users group privilege data.
								$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
								$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => '1');
								$user_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
								
								$logged_in_privileges= array_column($user_privileges, 
									$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
							}
							if( is_array($logged_in_privileges) && $logged_in_privileges) {
								foreach( $logged_in_privileges as $privilege) {
									$this->flexi_auth->insert_privilege_user($user_id, $privilege);	
								}
							}
						}			
						if( $user_id && $users[ 'urpo_department'] 
							&& !empty( $users[ 'urpo_department'] ) && is_array( $users[ 'urpo_department'] ) ){
							$opts = [];
							$opts[ 'user_id' ] = $user_id;
							$opts[ 'dept_ids' ] = $users[ 'urpo_department'];
							$this->load->model( 'Commonusermodel' );
							$this->Commonusermodel->_create_user_product_policy( $opts );
						}			

						if($this->input->post('activation_type', true) == 'direct'){
							$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1); 
						}
						$return_bool = true;
			} else {
				$user_id = $already_user[ 'uacc_id' ];
				$password = "";
				$return_bool = 'Already exist';
			}
				$this->csv_data_report[ ] =[
											'Id' 		=> $user_id,
											'User_Name' => $username,
											'Password' 	=> $password,											
											'Status' 	=> $return_bool,
										];
            $iterator++;
        }
        com_array_to_csv($this->csv_data_report, "users_upload_report.csv");
	}
}
