<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Group extends Admin_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->model();
    }

    function manage_groups(){
		// Check user has privileges to view user groups, 
		// else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View User Groups'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user groups.</p>');
			redirect('auth_admin');		
		}
		// If 'Manage User Group' form has been submitted and user has privileges, delete user groups.
		if ($this->input->post('delete_group') && $this->flexi_auth->is_privileged('Delete User Groups')) 
		{
			com_e( 'Delete pending' );
			/*
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->manage_user_groups();
			*/
		}

		// Define the group data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = [
				$this->flexi_auth->db_column('user_group', 'id'),
				$this->flexi_auth->db_column('user_group', 'name'),
				$this->flexi_auth->db_column('user_group', 'description'),
				$this->flexi_auth->db_column('user_group', 'admin')
			];
		$this->data['user_groups'] = $this->flexi_auth->get_groups_array($sql_select);
        $this->data['table_labels'] = [
						            'name' => 'Group Name',
						            'description' => 'Description',
						            'privileges' => 'User Group Privileges',
						            'action' => 'Delete',
						        ];
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		$this->data['content'] = $this->load->view('group/group-index', $this->data, TRUE);
		$this->load->view($this->template['default'], $this->data);
    }

    function insert_group() {

		// Check user has privileges to insert user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Insert User Groups') ) {
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to insert new user groups.</p>');
			redirect('auth_admin/manage_user_groups');
		}

		// If 'Add User Group' form has been submitted, insert the new user group.
		if ($this->input->post('insert_user_group')) {
			com_e( 'Insert put on hold' );
			/*
				$this->load->model('demo_auth_admin_model');
				$this->demo_auth_admin_model->insert_user_group();
			*/
		}
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		$this->data['content'] = $this->load->view('group/group-add-update', $this->data, TRUE);
		$this->load->view($this->template['default'], $this->data);	
    }

    function update_privileges( ){
		// Check user has privileges to update group privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update group privileges.</p>');
			redirect('auth_admin/manage_user_accounts');		
		}

		// If 'Update Group Privilege' form has been submitted, update the privileges of the user group.
		if ($this->input->post('update_group_privilege')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->update_group_privileges($group_id);
		}
		
		// Get data for the current user group.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
                
		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
		// Get data for the current privilege group.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $group_id);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
		// For the purposes of the example demo view, create an array of ids for all the privileges that have been assigned to a privilege group.
		// The array can then be used within the view to check whether the group has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['group_privileges'] = array();
		foreach($group_privileges as $privilege)
		{
			$this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
        $this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];
                
		$this->load->view('demo/admin_examples/user_group_privileges_update_view', $this->data);		
    }
}