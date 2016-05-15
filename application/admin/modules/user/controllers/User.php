<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Commonusermodel');
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->data = [];
        $this->data['user_type'] = $this->user_type;
    }

	function index( $offset = false) {

		if (! $this->flexi_auth->is_privileged('View Users')) {
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');			
			redirect('dashboard');
		}		
		$param = [];
		$param[ 'select' ] = 'ugrp_id, ugrp_name';
		$param[ 'from_tbl' ] = 'user_groups';
		$param[ 'where' ][ 'ugrp_admin != ' ] = 0;
		$param[ 'where' ][ 'ugrp_order > ' ] = $this->flexi_auth->get_user_custom_data( 'ugrp_order' );
		$param[ 'order' ] = [ 0 => 'ugrp_order', '1' => 'asc' ];
		$usersGroups = $this->Commonusermodel->get_all( $param );
		
		$this->data[ 'user_groups' ] = com_makelist($usersGroups, 'ugrp_id', 'ugrp_name', TRUE , 'Select Group' );
		$this->Commonusermodel->get_comp_user_accounts($offset);			
		
		$this->data['INFO'] = (! isset($this->data['INFO'])) ? 
                            $this->session->flashdata('message') : $this->data['INFO'];		
        $this->data['user_list_view'] = $this->load->view('users/ajax/user-list', $this->data, TRUE);;
		$this->data['content'] = $this->load->view('users/user-index', $this->data, TRUE);
		$this->load->view($this->template['default'], $this->data);
	}

	function add() {
		
		if (! $this->flexi_auth->is_privileged('Insert Users')){
			$this->session->set_flashdata('message','<p class="error_msg">You do not have privileges to view Insert Users.</p>'); 
			redirect('dashboard'); 
		}
		$company_code = '';
		if( in_array( $this->user_type, [ CMP_MD, CMP_PM ] )){
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
			$this->data['company'] = $company_code;
		} else if ( $this->user_type == CMP_ADMIN ) {
			$company_code = $this->flexi_auth->get_comp_admin_company_code( );
			$this->data['company'] = $company_code;
		}
		
		$this->load->model('company/Departmentmodel', 'Departmentmodel');
		$opt = [];
		$opt[ 'company_code' ] = $company_code;
		$this->Companymodel->getCompanyList( $opt );
		
		$dept['select'] = 'id, name, company_code';
		$dbFetchDepts = $this->Departmentmodel->get_all();
		$compDepts = [];
		foreach($dbFetchDepts as $index => $deptDetail){
			$compDepts[ $deptDetail['company_code'] ][ $deptDetail['id'] ] = $deptDetail['name'];
		}
		$this->data['comp_depts'] = json_encode($compDepts);
		$this->data['depts'] = $this->Departmentmodel->get_all();
		$this->data['company_list'] = com_makelist($this->data['company_list'], 'company_code' ,'name' , 1, 'Select Company');
		$this->data['related_dept'] = [ ];
		
		$this->form_rules[] = ['field' => 'uacc_password', 'label' => 'User Password', 
									'rules' => 'trim|required|min_length[5]|max_length[15]'];
		$this->form_rules[] = ['field' => 'uacc_username', 'label' => 'User Name', 
									'rules' => 'trim|required|alpha_numeric|is_unique[user_accounts.uacc_username]'];
		$this->form_rules[] = ['field' => 'uacc_email', 'label' => 'Email', 
									'rules' => 'trim|required|valid_email|max_length[75]|is_unique[user_accounts.uacc_email]'];
		$this->form_rules[] = ['field' => 'upro_first_name', 'label' => 'First Name', 
									'rules' => 'trim|max_length[50]|required'];
		$this->form_rules[] = ['field' => 'upro_last_name', 'label' => 'Last Name', 
									'rules' => 'trim|max_length[50]|required'];		
		$this->form_rules[] = ['field' => 'uadd_recipient', 'label' => 'Address Receipient ', 
									'rules' => 'trim|required|max_length[75]'];
		$this->form_rules[] = ['field' => 'uadd_phone', 'label' => 'Phone Number', 
									'rules' => 'trim|numeric'];
		$this->form_rules[] = ['field' => 'uadd_address_01', 'label' => 'Address 1', 
									'rules' => 'trim|max_length[50]'];
		$this->form_rules[] = ['field' => 'uadd_address_02', 'label' => 'Address 2', 
									'rules' => 'trim|max_length[50]'];
		$this->form_rules[] = ['field' => 'uadd_city', 'label' => 'City', 
									'rules' => 'trim|max_length[30]'];
		$this->form_rules[] = ['field' => 'uadd_county', 'label' => 'County', 
									'rules' => 'trim|max_length[30]'];
		$this->form_rules[] = ['field' => 'uadd_post_code', 'label' => 'Post Code', 
									'rules' => 'trim|max_length[10]'];
		$this->form_rules[] = ['field' => 'uadd_country', 'label' => 'Country', 
									'rules' => 'trim|max_length[30]'];
		/* which user is going to create */
		$new_user_profile = intval( $this->input->post('userProfile') );
		// if( $new_user_profile == USER ) {
		// 	$this->form_rules[] = ['field' => 'upro_department[]', 'label' => 'Kits', 
		// 						'rules' => 'required'];
		// }		
		/* Company customer or Purchase manager */
		if( $new_user_profile == 1 or $new_user_profile == 7 ){
			$approval_label = $new_user_profile == 1 ? 'Purchase manager' : 'Director';
			$rules = 'trim|max_length[50]';
			if( $new_user_profile == 1 ){
				$rules = 'trim|max_length[50]|required';
			}
			$this->form_rules[] = ['field' => 'upro_profession', 'label' => 'Profession', 
									'rules' => $rules];
			//$this->form_rules[] = ['field' => 'upro_approval_acc', 'label' => $approval_label,  'rules' => 'trim|required'];
		}
		
		if($this->Commonusermodel->adduser([])) {
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());			
			redirect('user');
		}else {
			$group_order = $this->flexi_auth->get_user_custom_data( 'ugrp_order' );
			$group_where_sql = [];			
			$group_where_sql[ 'ugrp_order > ' ] = intval( $group_order );
			$group_where_sql[ 'ugrp_order != '] = '2';
			$group_where_sql[ 'ugrp_admin != '] = '0';
			$groups = com_makeArrIndexToField( $this->flexi_auth->get_groups_query(False, $group_where_sql)->result_array(), 'ugrp_order');
			ksort( $groups );
			$this->data['is_submit'] = is_null( $this->input->post( 'useradd' ) ) ? 0 : 1;
			$this->data[ 'groups' ] = com_makelist( $groups, 'ugrp_id', 'ugrp_name', TRUE, 'Select');
			$this->data[ 'upro_department' ] =$this->input->post( 'upro_department' );
			$this->data['content'] = $this->load->view('users/user-add', $this->data, TRUE);
		}
		$this->load->view($this->template['default'], $this->data);
	}

	function edit_profile( ) {
		$user_id = $this->flexi_auth->get_user_id();
		$this->Commonusermodel->get_comp_user_profile( $user_id );
		$this->data['profile'] = false;
		$this->data['myProfile'] = true;
		$this->data['user_id'] = $this->data['user_profile']->uacc_id;		
		$this->form_rules[] = ['field' => 'uacc_password', 'label' => 'User Password', 
									'rules' => 'trim|min_length[5]|max_length[15]'];
		$this->form_rules[] = ['field' => 'uacc_username', 'label' => 'User Name', 
									'rules' => 'trim|required|alpha_numeric|identity_available['.$user_id.']'];
		$this->form_rules[] = ['field' => 'uacc_email', 'label' => 'Email', 
									'rules' => 'trim|required|valid_email|max_length[75]|identity_available['.$user_id.']'];
		$this->form_rules[] = ['field' => 'upro_first_name', 'label' => 'First Name', 
									'rules' => 'trim|max_length[50]|required'];
		$this->form_rules[] = ['field' => 'upro_last_name', 'label' => 'Last Name', 
									'rules' => 'trim|max_length[50]|required'];
		$this->form_rules[] = ['field' => 'uadd_recipient', 'label' => 'Address Receipient ', 
									'rules' => 'trim|required|max_length[75]'];
		$this->form_rules[] = ['field' => 'uadd_phone', 'label' => 'Phone Number', 
									'rules' => 'trim|numeric'];
		$this->form_rules[] = ['field' => 'uadd_address_01', 'label' => 'Address 1', 
									'rules' => 'trim|max_length[50]'];
		$this->form_rules[] = ['field' => 'uadd_address_02', 'label' => 'Address 2', 
									'rules' => 'trim|max_length[50]'];
		$this->form_rules[] = ['field' => 'uadd_city', 'label' => 'City', 
									'rules' => 'trim|max_length[30]'];
		$this->form_rules[] = ['field' => 'uadd_county', 'label' => 'County', 
									'rules' => 'trim|max_length[30]'];
		$this->form_rules[] = ['field' => 'uadd_post_code', 'label' => 'Post Code', 
									'rules' => 'trim|max_length[10]'];
		$this->form_rules[] = ['field' => 'uadd_country', 'label' => 'Country', 
									'rules' => 'trim|max_length[30]'];
		$params[ 'edit_my_profile' ] = true;
		$params[ 'user_profile' ] = $this->data['user_profile'];
		if( $this->data['user_profile']->ugrp_aprrove_from && $this->data['user_profile']->ugrp_name == CMP_PM ){
			$company_code = $this->data['user_profile']->upro_company;
			$grp_approval_from = $this->data['user_profile']->ugrp_aprrove_from;
			$opt = [];
			$opt[ 'result' ] = 'row';
			$opt[ 'select' ] = 'upro_uacc_fk, CONCAT(upro_first_name, " " , upro_last_name) as name , uacc_group_fk';
			$opt[ 'from_tbl' ] = 'user_profiles';				
			$opt['join'][] = array(	'tbl' => 'user_accounts', 
									'cond' => 'upro_uacc_fk=uacc_id', 
									'type' => 'inner',										
								);
			$opt[ 'where' ][ 'upro_company' ] = $company_code;
			$opt[ 'where' ][ 'uacc_group_fk' ] = $grp_approval_from;
			$opt[ 'where' ][ 'upro_uacc_fk' ] = $this->data['user_profile']->upro_approval_acc;
			$approvers = $this->Commonusermodel->get_all( $opt );
			$this->data[ 'approvers' ] = "";
			if( $approvers ){
				$this->data[ 'approvers' ] = $approvers[ 'name' ];
			}
		}		
		if($this->Commonusermodel->updateuser( $params )) {
			// Save any public status or error messages 
			//	(Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			redirect('dashboard/profile');
		}else {
			$this->data['user_company'] = '';
			if( $this->data[ 'user_profile' ]->upro_company && !empty( $this->data[ 'user_profile' ]->upro_company ) ){
				$opt = [  ];
				$opt[ 'result' ] = 'row';
				$opt[ 'select' ] = 'name';
				$opt[ 'from_tbl' ] = 'company';
				$opt[ 'where' ][ 'company_code' ] = $this->data[ 'user_profile' ]->upro_company;
				$comp = $this->Commonusermodel->get_all( $opt );
				$this->data['user_company'] = $comp[ 'name' ];
			}			
			$this->data[ 'user_type' ] = $this->user_type;
			$this->data['content'] = $this->load->view('users/user-edit', $this->data, TRUE);
		}
		$this->load->view($this->template['default'], $this->data);
	}

	function view($user_id) {
		
		/* privilieges */
		if (! $this->flexi_auth->is_privileged('View User Profile')){ 
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View User Profile.</p>'); 
			redirect('dashboard'); 
		}
		/* user profile */
		$this->Commonusermodel->get_comp_user_profile($user_id);	

		if( !isset( $this->data['user_profile'] ) ){
			$this->session->set_flashdata('message', "User could not found");
			redirect('user');
		}
		
		$this->data['profile'] = FALSE;
		$comp_code = $this->data['user_profile']->upro_company;
		$this->data['dept_list'] = $this->Companymodel->getLoggedCmpDept( $comp_code );
		
		/* Edit mode */
		if(com_gParam('edit', '1')){
			
			if (! $this->flexi_auth->is_privileged('Update User')){
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update User.</p>'); 
				redirect('dashboard'); 
			}
			
			$this->data['user_id'] = $this->data['user_profile']->uacc_id;
			
			$this->form_rules[] = ['field' => 'uacc_password', 'label' => 'User Password', 
										'rules' => 'trim|min_length[5]|max_length[15]'];
			$this->form_rules[] = ['field' => 'uacc_username', 'label' => 'User Name', 
										'rules' => 'trim|required|identity_available['.$user_id.']'];
			$this->form_rules[] = ['field' => 'uacc_email', 'label' => 'Email', 
										'rules' => 'trim|required|valid_email|max_length[75]|identity_available['.$user_id.']'];
			$this->form_rules[] = ['field' => 'upro_first_name', 'label' => 'First Name', 
										'rules' => 'trim|max_length[50]|required'];
			$this->form_rules[] = ['field' => 'upro_profession', 'label' => 'Profession', 
									'rules' => 'trim|max_length[50]'];
			if( $this->data['user_profile']->ugrp_name == USER ){				
				$this->form_rules[] = ['field' => 'upro_profession', 'label' => 'Profession', 
										'rules' => 'trim|max_length[50]|required'];
			}
			$this->form_rules[] = ['field' => 'upro_last_name', 'label' => 'Last Name', 
										'rules' => 'trim|max_length[50]|required'];
			$this->form_rules[] = ['field' => 'uadd_recipient', 'label' => 'Address Receipient ', 
										'rules' => 'trim|required|max_length[75]'];
			$this->form_rules[] = ['field' => 'uadd_phone', 'label' => 'Phone Number', 
										'rules' => 'trim|numeric'];
			$this->form_rules[] = ['field' => 'uadd_address_01', 'label' => 'Address 1', 
										'rules' => 'trim|max_length[50]'];
			$this->form_rules[] = ['field' => 'uadd_address_02', 'label' => 'Address 2', 
										'rules' => 'trim|max_length[50]'];
			$this->form_rules[] = ['field' => 'uadd_city', 'label' => 'City', 
										'rules' => 'trim|max_length[30]'];
			$this->form_rules[] = ['field' => 'uadd_county', 'label' => 'County', 
										'rules' => 'trim|max_length[30]'];
			$this->form_rules[] = ['field' => 'uadd_post_code', 'label' => 'Post Code', 
										'rules' => 'trim|max_length[10]'];
			$this->form_rules[] = ['field' => 'uadd_country', 'label' => 'Country', 
										'rules' => 'trim|max_length[30]'];
			if( in_array($this->user_type, [CMP_ADMIN, CMP_MD, CMP_PM] )  && $this->data['user_profile']->ugrp_name == USER ) {
				$this->form_rules[] = ['field' => 'upro_department[]', 'label' => 'Kit', 
									'rules' => 'required'];				
			}
			if( $this->data['user_profile']->ugrp_name == CMP_PM or $this->data['user_profile']->ugrp_name == USER ){				
				$company_code = $this->data['user_profile']->upro_company;
				$grp_approval_from = $this->data['user_profile']->ugrp_aprrove_from;
				$opt = [];
				$opt[ 'select' ] = 'upro_uacc_fk, CONCAT(upro_first_name, " " , upro_last_name) as name , uacc_group_fk';
				$opt[ 'from_tbl' ] = 'user_profiles';				
				$opt['join'][] = array(	'tbl' => 'user_accounts', 
										'cond' => 'upro_uacc_fk=uacc_id', 
										'type' => 'inner',										
									);
				$opt[ 'where' ][ 'upro_company' ] = $company_code;
				$opt[ 'where' ][ 'uacc_group_fk' ] = $grp_approval_from;
				$approvers = $this->Commonusermodel->get_all( $opt );				
				$this->data[ 'approvers' ] = [];
				if( $approvers ){
					$approvers = com_makelist( $approvers, 'upro_uacc_fk', 'name', false );
					$this->data[ 'approvers' ] = $approvers;
				}				
				$this->data[ 'approver_title' ] = $this->data['user_profile']->ugrp_name == CMP_PM ? 'Director' : 'Purchase manager';
				$this->form_rules[] = ['field' => 'upro_approval_acc', 'label' => 'Directors', 
											'rules' => 'trim|required'];
			}
			$params = [];
			$params[ 'user_profile' ] = $this->data['user_profile'];
			
			if($this->Commonusermodel->updateuser( $params )) {
				if( $this->data['user_id']) {
					$this->load->model('company/Companymodel', 'Companymodel');
					$company_up_data = [];
					$company_up_data[ 'data' ][ 'name' ] = ucfirst( $this->input->post('upro_first_name') ).' '.ucfirst( $this->input->post('upro_last_name') );
					$company_up_data[ 'where' ][ 'account_id' ] = $this->data['user_id'];
					$this->Companymodel->update_record( $company_up_data );
				}
				// Save any public status or error messages 
				//	(Whilst suppressing any admin messages) to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
				redirect('user');
			} else {				
				$this->data[ 'user_company' ] = '';
				if( !empty( $this->data[ 'user_profile' ]->upro_company ) ){
					$company_code = $this->data[ 'user_profile' ]->upro_company;
					$opt = [];
					$opt[ 'result' ] = 'row';
					$opt[ 'select' ] = 'account_id, name';
					$opt[ 'from_tbl' ] = 'company';
					$opt[ 'where' ][ 'company_code' ] = $company_code;
					$this->data[ 'user_company' ] = $this->Commonusermodel->get_all( $opt )[ 'name' ];
				}
				if( $this->data['user_profile']->ugrp_name == USER ) {
					$users_assigned_dept = json_decode( $this->data['user_profile']->upro_department );
					if( !is_array($users_assigned_dept) or is_null($users_assigned_dept)){
						$users_assigned_dept = [];
					}
					$this->data['user_profile']->upro_department = $users_assigned_dept;
				}
				$this->data[ 'upro_department' ] =$this->input->post( 'upro_department' );				
				$this->data['related_dept'] = $this->data[ 'dept_list' ];
				$this->data['content'] = $this->load->view('users/user-edit', $this->data, TRUE);
			}
		} else {
			$this->data[ 'user_company' ] = '';
			if( !empty( $this->data[ 'user_profile' ]->upro_company ) ){
				$company_code = $this->data[ 'user_profile' ]->upro_company;
				$opt = [];
				$opt[ 'result' ] = 'row';
				$opt[ 'select' ] = 'account_id, name';
				$opt[ 'from_tbl' ] = 'company';
				$opt[ 'where' ][ 'company_code' ] = $company_code;
				$this->data[ 'user_company' ] = $this->Commonusermodel->get_all( $opt )[ 'name' ];
			}
			
			$this->data[ 'user_approval_title' ] = '';
			$this->data[ 'user_approval_name' ] = '';
			if( !empty( $this->data[ 'user_profile' ]->upro_approval_acc ) && $this->data[ 'user_profile' ]->upro_approval_acc ){
				$this->data[ 'user_approval_title' ] = $this->data[ 'user_profile' ]->ugrp_name == USER ? 
				'Purchase manager' : 'Director';	
				$opt = [];
				$opt[ 'result' ] = 'row';
				$opt[ 'select' ] = 'upro_first_name, upro_last_name';
				$opt[ 'from_tbl' ] = 'user_profiles';
				$opt[ 'where' ][ 'upro_uacc_fk' ] = $this->data[ 'user_profile' ]->upro_approval_acc;
				$approver_name = $this->Commonusermodel->get_all( $opt );
				$this->data[ 'user_approval_name' ]  = ucfirst( $approver_name[ 'upro_first_name' ] ). ' ' . $approver_name[ 'upro_last_name' ];
			}
			
			if( $this->data['user_profile']->ugrp_name == USER ) {
				$users_assigned_dept = json_decode($this->data['user_profile']->upro_department);
				if( !is_array($users_assigned_dept) or is_null($users_assigned_dept)){
					$users_assigned_dept = [];
				}
				$this->data['user_dept'] = array_intersect_key($this->data['dept_list'], array_flip( $users_assigned_dept) );
			}			

			
			$this->data['content'] = $this->load->view('users/user-profile', $this->data, TRUE);
		}

		$this->load->view($this->template['default'], $this->data);
	}

	function update_privilieges( $user_id ) {
		if (! $this->flexi_auth->is_privileged('View User Priviliges')) {
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View User Priviliges.</p>'); 
				redirect('dashboard'); 
			}
		
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update User Priviliges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('user');		
		}

		// If 'Update User Privilege' form has been submitted, update the user privileges.
		if ( $this->input->post('update_user_privilege') ) {
			if (! $this->flexi_auth->is_privileged('Update User Priviliges')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update User Priviliges.</p>'); redirect('dashboard'); }

			$this->Commonusermodel->update_user_privileges($user_id);
		}

		// Get users profile data.
		$sql_select = array(
			'upro_uacc_fk', 
			'upro_first_name', 
			'upro_last_name',
			$this->flexi_auth->db_column('user_acc', 'group_id'),
			$this->flexi_auth->db_column('user_group', 'name')
        );
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array($sql_select, $sql_where);		

		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
		// Get user groups current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $this->data['user'][$this->flexi_auth->db_column('user_acc', 'group_id')]);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);

        $this->data['group_privileges'] = array();
        foreach($group_privileges as $privilege)
        {
            $this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
        }
                        
		// Get users current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => $user_id);
		$user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);
				
		// For the purposes of the example demo view, create an array of ids for all the users assigned privileges.
		// The array can then be used within the view to check whether the user has a specific privilege, 
		// this data allows us to then format form input values accordingly. 
		$this->data['user_privileges'] = array();
		foreach($user_privileges as $privilege)
		{
			$this->data['user_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_users', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
		$this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];

      	$this->data['content'] =  $this->load->view('users/user-priviliges', $this->data, true);

  		/*
	      	com_e( 'ALL Privileges' , 0);
	      	com_e($this->data['privileges'], 0);

	      	com_e( 'GROUP Privileges' , 0);
	      	com_e($group_privileges, 0);

	      	com_e( 'USER Privileges' , 0);
	      	com_e($user_privileges);
		*/
		
      	$this->load->view($this->template['default'], $this->data);
		//$this->load->view('demo/admin_examples/user_privileges_update_view', $this->data);
	}

	function suspend() {
		if (! $this->flexi_auth->is_privileged('Suspend User')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Suspend User.</p>'); redirect('dashboard'); }

		com_e('Working pending', 1);
	}

	function delete() {
		if (! $this->flexi_auth->is_privileged('Delete User')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete User.</p>'); redirect('dashboard'); }

	}

	function activate_email( $user_id ){
		$this->flexi_auth->resend_activation_token( $user_id );		
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		redirect('user');
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Account Activation
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * activate_account
	 * User account activation via email.
	 * The default setup of this demo requires that new account registrations must be authenticated via email before the account is activated.
	 * In this demo, this page is accessed via an activation link in the 'views/includes/email/activate_account.tpl.php' email template.
	 */ 
	function activate_account($user_id, $token = FALSE)
	{
		// The 3rd activate_user() parameter verifies whether to check '$token' matches the stored database value.
		// This should always be set to TRUE for users verifying their account via email.
		// Only set this variable to FALSE in an admin environment to allow activation of accounts without requiring the activation token.
		$this->flexi_auth->activate_user($user_id, $token, TRUE);

		// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

		redirect('user');
	}


	function forgot_password($user_id) {
		$this->flexi_auth->forgotten_password($user_id);
		redirect('user');	
	}

	/**
	 * auto_reset_forgotten_password
	 * This is an example of automatically reseting a users password as a randomised string that is then emailed to the user. 
	 * See the manual_reset_forgotten_password() function above for the manual method of changing a forgotten password.
	 * In this demo, this page is accessed via a link in the 'views/includes/email/forgot_password.tpl.php' email template,
	 * which must be set to 'auth/auto_reset_forgotten_password/...'.
	 */
	function auto_reset_password($user_id = FALSE)
	{
		$this->Commonusermodel->auto_reset_password_and_email($user_id);
		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		
		redirect('user');
	}
	
	function update_direct_order_flag( $user_id ) {
		$this->Commonusermodel->get_comp_user_profile($user_id);
		if( !isset( $this->data['user_profile'] ) ){
			$this->session->set_flashdata('message', "User could not found");
		} else {			
			$this->session->set_flashdata('message', "User direct order flag updated successfully");
			$this->Commonusermodel->update_user_direct_order_flag( $this->data['user_profile'] );
		}
		redirect('user');
	}
}
