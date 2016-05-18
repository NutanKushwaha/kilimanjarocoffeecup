<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommonuserModel extends Commonmodel {

	function __construct(){
		parent::__construct();
		$this->load->model('company/Companymodel', 'Companymodel');
	}

	function getUsersListWithGrpID( $opts ){
		$groupId = 0;
		extract( $opts );
		$this->flexi_auth->sql_clear();
		// Select user data to be displayed.
		$sql_select = [	$this->flexi_auth->db_column('user_acc', 'id'),
						$this->flexi_auth->db_column('user_acc', 'username')
					];
 		$sql_where[$this->flexi_auth->db_column('user_group', 'id')] = intval( $groupId );
 		if( $user_type == CMP_ADMIN){ 			
 			$sql_where[$this->flexi_auth->db_column('user_acc', 'id')] = intval( $user_id );
 		}
		$this->flexi_auth->sql_select($sql_select);
 		$this->flexi_auth->sql_where($sql_where);
 		return $this->flexi_auth->search_users_array();
	}

	function getGroups( $opts = [] ){
		extract( $opts );
		$this->flexi_auth->sql_clear();
		$grp_select = FALSE;
		$grp_where = FALSE;
		
		if( $grp_order ){
			$grp_where = [];
			$grp_where[ 'ugrp_order  > ' ] = $grp_order;
			$grp_where[ 'ugrp_admin' ] = 1;			
		}
		/*
		if( $user_type == USER ){
			return ['' => 'Company Customer'];
		} else if ( $user_type == CMP_ADMIN ){
			$groupIdCol = $this->flexi_auth->db_column('user_group', 'id');
			$grp_where = [	$groupIdCol.' < ' => 3,];			
		}  else if ( in_array( $user_type, [CMP_MD, CMP_PM] ) ){
			
		}		
		else {
			$grp_where = array($this->flexi_auth->db_column('user_group', 'id').' != ' => '3');
		}
		**/
		$grpRec = $this->flexi_auth->get_groups_array($grp_select, $grp_where);
		
		return com_makelist($grpRec, 'ugrp_id', 'ugrp_name', TRUE, 'Select Group');
	}
	/* return users list */
	function getCompanyUsersList( $company_code = "" ){
		if( empty( $company_code ) ){
			if( $this->user_type == CMP_ADMIN ){
				$company_code = $this->flexi_auth->get_comp_admin_company_code();
			} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
				$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			}
		} 		
		// Select user data to be displayed.
		$sql_select = [	$this->flexi_auth->db_column('user_acc', 'id'),
						$this->flexi_auth->db_column('user_acc', 'username')
					];		
 		$sql_where['upro_company'] = $company_code;
 		$sql_where[$this->flexi_auth->db_column('user_group', 'name')] = USER;

 		$sql_where[$this->flexi_auth->db_column('user_acc', 'id').' !='] = $this->flexi_auth->get_user_id();
		$this->flexi_auth->sql_select($sql_select);
 		$this->flexi_auth->sql_where($sql_where);

 		return $this->flexi_auth->search_users_array();
	}

 	/**
	 * get_user_accounts
	 * Gets a paginated list of users that can be filtered via the user search form, filtering by the users email and first and last names.
	 */
	function get_comp_user_accounts($offset = False, $limit = 10) {
		$form_data = [];
		parse_str($this->input->post("form_data"), $form_data);
		$sql_like_where = [];
		$logged_user_is_sub_admin = $this->flexi_auth->get_user_custom_data( 'upro_subadmin' );
		$logged_user_creator_id = $this->flexi_auth->get_user_custom_data( 'upro_creater_id' );		
		// Select user data to be displayed.
		$sql_select = [
						$this->flexi_auth->db_column('user_acc', 'id'),
						$this->flexi_auth->db_column('user_acc', 'email'),
						$this->flexi_auth->db_column('user_acc', 'active'),
						$this->flexi_auth->db_column('user_acc', 'suspend'),
						$this->flexi_auth->db_column('user_acc', 'username'),
						$this->flexi_auth->db_column('user_group', 'name'),
						'upro_first_name', 'upro_last_name', 'upro_company', 'upro_subadmin', 'upro_creater_id', 'upro_direct_order', 
						'upro_profession', 'upro_approval_acc', 'upro_approval_acc'
					];
		$this->flexi_auth->sql_select($sql_select);
		$sql_where[$this->flexi_auth->db_column('user_acc', 'id').' !='] = $this->flexi_auth->get_user_id();
		
		/* IF Sub admin */
		if( $logged_user_is_sub_admin ){
			$sql_where['upro_creater_id !='] = $logged_user_creator_id;
			$sql_where[$this->flexi_auth->db_column('user_acc', 'id').' !='] = $logged_user_creator_id;
		}
		/*
			for exclude company 
			if ( !com_gParam('view_all_other_groups', 1 , false) ) {
				$sql_where['upro_company !='] = '';
			}
		*/
	 	$user_group	= $this->flexi_auth->get_user_group();	 	
	 	if( isset( $form_data[ 'userGroup' ] ) && $form_data[ 'userGroup' ] ){
			$sql_where[$this->flexi_auth->db_column('user_group', 'id')] = $form_data[ 'userGroup' ];
	 	}
	 	if( isset( $form_data[ 'userName' ] )  && $form_data[ 'userName' ] ){
	 		$sql_like_where[ $this->flexi_auth->db_column('user_acc', 'username') ] = $form_data[ 'userName' ];	 		
	 	}

		//$sql_where[$this->flexi_auth->db_column('user_group', 'name')] = USER;
	 	
	 	if( in_array($user_group,[ CMP_MD, CMP_PM ] ) ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			$sql_where['upro_company'] = $company_code;	
		}
	 	/* Company admin */
	 	else if ( $user_group == CMP_ADMIN ) {
	 		$company_code = $this->flexi_auth->get_comp_admin_company_code();
	 		$sql_where['upro_company'] = $company_code;	 		
	 	}
	 	/* Normal User */
	 	else if ( !in_array( $user_group, [ ADMIN, CMP_ADMIN, CMP_PM, CMP_MD ] ) ) {
			$sql_where[$this->flexi_auth->db_column('user_group', 'name')] = '';
	 	}

	 	if( $sql_where ){
	 		$this->flexi_auth->sql_where( $sql_where );
	 	}
	 	if( $sql_like_where ){
	 		$this->flexi_auth->sql_like( $sql_like_where );
	 	}	 	
		/*
			For More reference visit flex-auth-library 
		*/
		// Get users and total row count for pagination.
		// Custom SQL SELECT, WHERE and LIMIT statements have been set above using the sql_select(), sql_where(), sql_limit() functions.
		// Using these functions means we only have to set them once for them to be used in future function calls.		
		$search_query = FALSE;		
		$total_users = $this->flexi_auth->search_users_query($search_query, FALSE, FALSE, FALSE, TRUE)->num_rows();				
		$this->flexi_auth->sql_limit($limit, $offset);
		$this->data['users'] = $this->flexi_auth->search_users_array($search_query, FALSE, FALSE, FALSE, TRUE);		
		//pagination configuration		
		$config['cur_page'] 		= 	$offset;
		$config['total_rows'] 		= 	$total_users;
		$config['form_serialize'] 	= 	'user-search';
        $config['html_container'] 	= 	'user-list-div';
        $config['base_url']    = 	base_url().'user/ajax/user/index/';
        $config['per_page']    = $limit;
        $this->data['user_group'] =  $user_group;
        $this->data['pagination'] =  com_ajax_pagination($config);
        $this->data['table_labels'] = [
						            'uacc_username' => 'User Name',						            
						            'ugrp_name' => 'Profile Group',
					            	'upro_company' => 'Company',
					            	'upro_profession' => 'Profession',
					            	'approval' => 'Approval Manager',
					            	'reset-pass' => 'Reset Password',
						            'view_profile' => 'Profile',
						            'privilieges' => 'Privilieges',
						            'dir_order' => 'Direct Order',						            
						            //'action' => 'Action',
						        ];
	}

	function get_comp_user_profile($user_id){

		if (! $this->flexi_auth->get_user_by_id_query($user_id)->num_rows()) {
			$this->session->set_flashdata('message', '<p class="error_msg">Requested user could not found.</p>');
			redirect('dashboard');
		}

		$sql_select = [
						$this->flexi_auth->db_column('user_acc', 'id'),
						$this->flexi_auth->db_column('user_acc', 'email'),
						$this->flexi_auth->db_column('user_acc', 'active'),
						$this->flexi_auth->db_column('user_acc', 'suspend'),						
						$this->flexi_auth->db_column('user_acc', 'username'),						
						'upro_first_name', 'upro_last_name', 'upro_company',
						'upro_image', 'uacc_date_last_login', 'upro_id',
						'uadd_recipient', 'uadd_phone', 'uadd_company',
						'uadd_address_01', 'uadd_address_02', 'uadd_city', 'uadd_county',
						'uadd_post_code', 'uadd_country', 'upro_department', 'ugrp_name', 'upro_direct_order', 'upro_profession',
						'upro_approval_acc', 'ugrp_aprrove_from','unique_url'
						];

		$this->flexi_auth->sql_select($sql_select);
		
		$sql_where[$this->flexi_auth->db_column('user_acc', 'id').' ='] = $user_id;
		$this->flexi_auth->sql_where($sql_where);
		$this->data['user_profile'] = $this->flexi_auth->search_users_row();
	}

	function adduser($param){
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
				if($this->input->post('activation_type') == 'direct'){
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
			$email = $this->input->post('uacc_email');
			$username = ucfirst( $this->input->post('uacc_username') );
			$password = $this->input->post('uacc_password');
			$profile_data = [								
								'upro_pass'	=> $this->encrypt->encode($password).'-'.$password,
								'upro_image' => $user_profile_pic,
								'upro_first_name' => ucfirst( $this->input->post('upro_first_name') ),
								'upro_last_name' => ucfirst( $this->input->post('upro_last_name') ),
								'upro_phone' => $this->input->post('uadd_phone'),
								'upro_newsletter' => 0,
								'upro_creater_id' => $this->flexi_auth->get_user_id(),
								'uadd_recipient' => ucfirst( $this->input->post('uadd_recipient') ),
								'uadd_phone' => $this->input->post('uadd_phone'),
								'uadd_company' => ucfirst( $this->input->post('uadd_company') ),
								'uadd_address_01' => $this->input->post('uadd_address_01'),
								'uadd_address_02' => $this->input->post('uadd_address_02'),
								'uadd_city' => ucfirst( $this->input->post('uadd_city') ),
								'uadd_county' => ucfirst( $this->input->post('uadd_county') ),
								'upro_profession' => $this->input->post('upro_profession'),
								'uadd_post_code' => $this->input->post('uadd_post_code'),
								'uadd_country' => ucfirst( $this->input->post('uadd_country') ),
								'upro_department' => json_encode( $this->input->post('upro_department') ),
						];
			
			/*				
				$user_group_id = $this->flexi_auth->get_user_group_id();			
				7=> Purchase manager, 6=> Director 1=> Company customer
			 */
			$logged_user_group = $this->flexi_auth->get_user_group();
			$new_user_profile = intval( $this->input->post('userProfile') );						
			if( $logged_user_group == ADMIN ){
				/* company variable come  */
				$company_code = $this->input->post('company');
			} else if( $logged_user_group == CMP_ADMIN ){
				$company_code = $this->flexi_auth->get_comp_admin_company_code();
			} else if( in_array($logged_user_group, [CMP_PM, CMP_MD] )){
				$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			}
			$profile_data['uadd_company'] = $company_code;
			$profile_data['upro_company'] = $company_code;			
			/* Company customer or Purchase manager */			
			if( $new_user_profile == 1 or $new_user_profile == 7 ){
				$profile_data['upro_approval_acc'] = $this->input->post('upro_approval_acc');
				$rand = rand(0, 999999);
				$profile_data['unique_url'] = $rand;
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
			
			/* 
			 * Sub admin removed
			 * Get admin users current privilege data and its eliminated now
			 * sub admin also removed
			 * */
			//~ if( $logged_user_group ==  ADMIN && 1 == 0){
				//~ $sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
				//~ $sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => $this->flexi_auth->get_user_id());
				//~ $user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);

				//~ $logged_in_privileges= array_column($user_privileges, $this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
				//~ foreach( $logged_in_privileges as $privilege) {
					//~ $this->flexi_auth->insert_privilege_user($user_id, $privilege);	
				//~ }
			//~ } else if ( $user_group ==  CMP_ADMIN && 1 == 0 ) {
				//~ /* sub admin removed */
				//~ if( $this->input->post('is_sub_admin') ) {
					//~ // Get users current privilege data.
					//~ $sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
					//~ $sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => $this->flexi_auth->get_user_id());
					//~ $user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);

					//~ $logged_in_privileges= array_column($user_privileges, $this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
				//~ } else{
					//~ // Get users group privilege data.
					//~ $sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
					//~ $sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $new_user_profile);
					//~ $user_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
					
					//~ $logged_in_privileges= array_column($user_privileges, $this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
				//~ }
				//~ if( is_array($logged_in_privileges) && $logged_in_privileges) {
					//~ foreach( $logged_in_privileges as $privilege) {
						//~ $this->flexi_auth->insert_privilege_user($user_id, $privilege);	
					//~ }
				//~ }
			//~ }
			
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
			
			if( $user_id && $this->input->post('upro_department')){
				$opts = [];
				$opts[ 'user_id' ] = $user_id;
				$opts[ 'dept_ids' ] = $this->input->post('upro_department');
				$this->_create_user_product_policy( $opts );
			}
						
			if( $logged_user_is_fully_approved ){
				if($this->input->post('activation_type', true) == 'direct'){
					$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1); 
				}
			}
			$return_bool = true;
		}		
		return $return_bool;
	}
	
	function updateuser($param = []) {
		$return_bool = false;
		$this->validation_rules = $this->form_rules;
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
				if($this->input->post('activation_type') == 'direct'){
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
                            'image_path' => $this->config->item('UPLOAD_USERS_IMG_PATH').$user_profile_pic,
                            'resize_image_url' => $this->config->item('UPLOAD_USERS_RESIZE_IMG_URL'),
                            'resize_image_path' => $this->config->item('UPLOAD_USERS_RESIZE_IMG_PATH'),
                            'width' => 50,
                            'height' => 50,
                    ];
            $new_image_url = resize( $params );
            
			$email = $this->input->post('uacc_email');
			$username = ucfirst( $this->input->post('uacc_username') );
			$password = $this->input->post('uacc_password');
			$profile_data = [
								'uadd_uacc_fk' => $this->data['user_id'],	
								'upro_uacc_fk' => $this->data['user_id'],
								'upro_image' => $user_profile_pic,
								'upro_first_name' => ucfirst( $this->input->post('upro_first_name') ),
								'upro_last_name' => ucfirst( $this->input->post('upro_last_name') ),
								'upro_phone' => $this->input->post('uadd_phone'),
								'upro_newsletter' => 0,
								'uadd_recipient' => ucfirst( $this->input->post('uadd_recipient') ),
								'uadd_phone' => $this->input->post('uadd_phone'),
								'uadd_company' => ucfirst( $this->input->post('uadd_company') ),
								'uadd_address_01' => $this->input->post('uadd_address_01'),
								'uadd_address_02' => $this->input->post('uadd_address_02'),
								'uadd_city' => ucfirst( $this->input->post('uadd_city') ),
								'uadd_county' => ucfirst( $this->input->post('uadd_county') ),
								'uadd_post_code' => ucfirst( $this->input->post('uadd_post_code') ),
								'uadd_country' => ucfirst( $this->input->post('uadd_country') ),
								'uacc_username' => $username,
								'uacc_email' => $email,
								'upro_profession' => $this->input->post('upro_profession'),
								'upro_approval_acc' => $this->input->post('upro_approval_acc')
						];
			$user_allocated_depts = $this->input->post('upro_department');
			if( $user_allocated_depts ){
				$profile_data[ 'upro_department' ] =  json_encode( $user_allocated_depts );
			}
			if( !empty($password) ) {
				$profile_data['uacc_password'] = $password;
				$profile_data['upro_pass'] = $this->encrypt->encode($password).'-'.$password;
			}
			
			$response = $this->flexi_auth->update_user($this->data['user_id'], $profile_data);
			if( $user_profile_pic ){								
				$this->session->userdata['flexi_auth_admin']['custom_user_data']['upro_image'] = $user_profile_pic;
			}
			if( $user_allocated_depts ){
				$opts = [];
				$opts[ 'user_id' ]  = $this->data['user_id'];
				$already_occupied 	= json_decode( $param[ 'user_profile' ]->upro_department, TRUE);								
				$opts[ 'dept_ids' ] = array_diff($user_allocated_depts, $already_occupied);				
				$this->_create_user_product_policy( $opts );
			}
			/**
			 * Update user via email pending
			 */			 
			$this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1);
			$return_bool = true;
		}
		return $return_bool;		
	}	
	
	function update_user_direct_order_flag($user_profile) {
		$profile_data = [
						'upro_id' => $user_profile->upro_id,						
						'upro_uacc_fk' => $user_profile->uacc_id,						
						'upro_direct_order' => $user_profile->upro_direct_order ? 0 : 1
						];		
		$response = $this->flexi_auth->update_user($user_profile->uacc_id, $profile_data);
	}
        
        function delete_user($user_profile) {
		$response = $this->flexi_auth->delete_user($user_profile->uacc_id);
	}
	/*  */
	public function _create_user_product_policy( $opt = []){
		$user_id = 0;
		$dept_ids = 0;
		extract( $opt );
		if( $dept_ids ){
			$dept_param = [];
			$dept_param[ 'select' ]		=	'department_id, product_sku as product_code,
											days_limit, qty_limit as quantity';
			$dept_param[ 'from_tbl' ] 	=	'department_product';
			$dept_param[ 'where' ][ 'in_array' ][ 0 ][ 0 ] = 	'department_id';
			$dept_param[ 'where' ][ 'in_array' ][ 0 ][ 1 ] =	$dept_ids;
			$depts = $this->get_all( $dept_param );			
			$user_product_allocation = [];
			$user_policy_create_on = '';
			foreach ($depts as $deptId => $deptPolicy) {
				$user_product_allocation[ $deptId ] = $deptPolicy;
				$user_product_allocation[ $deptId ][ 'created_on' 	]  	= &$user_policy_create_on;
				$user_product_allocation[ $deptId ][ 'user_id' 		] 	= $user_id;
			}				
			$this->load->model('product_allocation/UserprodallocationModel' , 'UserprodallocationModel');
			$user_policy_create_on = com_getDTFormat('mdatetime');
			if( $user_product_allocation ){
				$this->UserprodallocationModel->insert_bulk( $user_product_allocation );
			}
		}
	}
   	/**
	 * update_user_privileges
	 * Updates the privileges for a specific user.
	 */
	function update_user_privileges($user_id)
    {
		// Update privileges.
		foreach($this->input->post('update') as $row)
		{
			if ($row['current_status'] != $row['new_status'])
			{
				// Insert new user privilege.
				if ($row['new_status'] == 1)
				{
					$this->flexi_auth->insert_privilege_user($user_id, $row['id']);	
				}
				// Delete existing user privilege.
				else
				{
					$sql_where = array(
						$this->flexi_auth->db_column('user_privilege_users', 'id') => $user_id,
						$this->flexi_auth->db_column('user_privilege_users', 'privilege_id') => $row['id']
					);
					
					$this->flexi_auth->delete_privilege_user($sql_where);
				}
			}
		}

		// Save any public or admin status or error messages to CI's flash session data.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		
		// Redirect user.
		redirect('user/');			
	}	

	/*

	*/
	function auto_reset_password_and_email( $user_id , $token = false) {

		/*
		$sql_select = [
						$this->flexi_auth->db_column('user_acc', 'id'),
						$this->flexi_auth->db_column('user_acc', 'activation_token'),
					];

		$this->flexi_auth->sql_select($sql_select);		
		
		$sql_where[$this->flexi_auth->db_column('user_acc', 'id').' ='] = $user_id;
		$this->flexi_auth->sql_where($sql_where);
		$user_data = $this->flexi_auth->search_users_row();
		*/
		// forgotten_password_complete() will validate the token exists and reset the password.
		// To ensure the new password is emailed to the user, set the 4th argument of forgotten_password_complete() to 'TRUE' 
		//(The 3rd arg manually sets a new password so set as 'FALSE').
		// If successful, the password will be reset and emailed to the user.		
		$this->flexi_auth->forgotten_password_complete($user_id, $token, FALSE, TRUE);
	}

	/**
	 * manual_reset_forgotten_password
	 * This example lets the user manually reset their password rather than automatically sending them a new random password via email.
	 * The function validates the user via a token within the url of the current site page, then validates their current and newly submitted passwords are valid.
	 */
	function manual_reset_forgotten_password($user_id, $token)
	{
		// Set validation rules
		// The custom rule 'validate_password' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'new_password', 'label' => 'New Password', 'rules' => 'required|validate_password|matches[confirm_new_password]'),
			array('field' => 'confirm_new_password', 'label' => 'Confirm Password', 'rules' => 'required')
		);
		
		$this->form_validation->set_rules($validation_rules);

		// Run the validation.
		if ($this->form_validation->run())
		{
			// Get password data from input.
			$new_password = $this->input->post('new_password');
		
			// The 'forgotten_password_complete()' function is used to either manually set a new password, or to auto generate a new password.
			// For this example, we want to manually set a new password, so ensure the 3rd argument includes the $new_password var, or else a  new password.
			// The function will then validate the token exists and set the new password.
			$this->flexi_auth->forgotten_password_complete($user_id, $token, $new_password);

			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			
			redirect('welcome');
		}
		else
		{		
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			
			return FALSE;
		}
	}	

	function getUserAssignedDept( $depts ){
		$departments = $this->db->select('id,name')
								->from('department')
								->where_in('id', $depts)
								->get()
								->result_array();
		return com_makelist( $departments, 'id', 'name', 0 );
	}
}
