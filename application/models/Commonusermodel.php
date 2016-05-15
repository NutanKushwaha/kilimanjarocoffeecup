<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CommonuserModel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->load->model('Companymodel');
    }

    /**
     * get_user_accounts
     * Gets a paginated list of users that can be filtered via the user search form, filtering by the users email and first and last names.
     */
    function get_comp_user_accounts($offset = False, $limit = 10) {

        $logged_user_is_sub_admin = $this->flexi_auth->get_user_custom_data('upro_subadmin');
        $logged_user_creator_id = $this->flexi_auth->get_user_custom_data('upro_creater_id');

        // Select user data to be displayed.
        $sql_select = [
            $this->flexi_auth->db_column('user_acc', 'id'),
            $this->flexi_auth->db_column('user_acc', 'email'),
            $this->flexi_auth->db_column('user_acc', 'active'),
            $this->flexi_auth->db_column('user_acc', 'suspend'),
            $this->flexi_auth->db_column('user_acc', 'username'),
            $this->flexi_auth->db_column('user_group', 'name'),
            'upro_first_name', 'upro_last_name', 'upro_company', 'upro_subadmin', 'upro_creater_id'
        ];

        $this->flexi_auth->sql_select($sql_select);
        $sql_where[$this->flexi_auth->db_column('user_acc', 'id') . ' !='] = $this->flexi_auth->get_user_id();

        /* IF Sub admin */
        if ($logged_user_is_sub_admin) {
            $sql_where['upro_creater_id !='] = $logged_user_creator_id;
            $sql_where[$this->flexi_auth->db_column('user_acc', 'id') . ' !='] = $logged_user_creator_id;
        }

        /*
          for exclude company
          if ( !com_gParam('view_all_other_groups', 1 , false) ) {
          $sql_where['upro_company !='] = '';
          }
         */

        $user_group = $this->flexi_auth->get_user_group();
        /* Super admin */
        if ($user_group == ADMIN) {
            
        }
        /* Company admin */ else if ($user_group == CMP_ADMIN) {
            $company_code = $this->flexi_auth->get_comp_admin_company_code();
            $sql_where['upro_company'] = $company_code;
            $sql_where[$this->flexi_auth->db_column('user_group', 'name')] = USER;
        }
        /* Normal User */ else {
            $sql_where[$this->flexi_auth->db_column('user_group', 'name')] = '';
        }

        $this->flexi_auth->sql_where($sql_where);

        /*
          For More reference visit flex-auth-library
         */

        // Get users and total row count for pagination.
        // Custom SQL SELECT, WHERE and LIMIT statements have been set above using the sql_select(), sql_where(), sql_limit() functions.
        // Using these functions means we only have to set them once for them to be used in future function calls.
        $pagination_url = base_url() . 'user/index/';
        $config['uri_segment'] = 3;
        $search_query = FALSE;
        $total_users = $this->flexi_auth->search_users_query($search_query)->num_rows();

        //com_e( $this->db->last_query() );

        $this->flexi_auth->sql_limit($limit, $offset);
        $this->data['users'] = $this->flexi_auth->search_users_array($search_query);

        $config['base_url'] = $pagination_url;
        $config['total_rows'] = $total_users;
        $config['per_page'] = $limit;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a><b>';
        $config['cur_tag_close'] = '</a></b></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';

        $this->pagination->initialize($config);

        // Make search query and pagination data available to view.
        $this->data['search_query'] = $search_query; // Populates search input field in view.
        $this->data['pagination']['links'] = $this->pagination->create_links();
        $this->data['pagination']['total_users'] = $total_users;

        $this->data['table_labels'] = [
            'uacc_username' => 'User Name',
            'ugrp_name' => 'Group',
            'upro_company' => 'Company',
            'reset-pass' => 'Reset Password',
            'view_profile' => 'Profile',
            'privilieges' => 'Privilieges',
            'action' => 'Action',
        ];
    }

    function get_comp_user_profile($user_id) {

        if (!$this->flexi_auth->get_user_by_id_query($user_id)->num_rows()) {
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
            'upro_image', 'uacc_date_last_login',
            'uadd_recipient', 'uadd_phone', 'uadd_company',
            'uadd_address_01', 'uadd_address_02', 'uadd_city', 'uadd_county',
            'uadd_post_code', 'uadd_country', 'upro_department'
        ];

        $this->flexi_auth->sql_select($sql_select);

        $sql_where[$this->flexi_auth->db_column('user_acc', 'id') . ' ='] = $user_id;
        $this->flexi_auth->sql_where($sql_where);
        $this->data['user_profile'] = $this->flexi_auth->search_users_row();
    }

    function adduser($param) {
        $guest_order = false;
        extract($param);
        $return_bool = false;
        $this->validation_rules = $this->form_rules;
        /*
          com_e( $this->validation_rules , 0);
          com_e( $this->input->post() );
         */
        if ($this->validate()) {

            /*
              $instant_active = TRUE;
              $this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0);
             */


            $instant_active = false;
            $this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1);

            $config = [];
            $config['upload_path'] = $this->config->item('UPLOAD_USERS_IMG_PATH');
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $user_profile_pic = $this->upload_file($config);

            if ($user_profile_pic === FALSE) {
                return FALSE;
            }

            $image_url = $this->config->item('UPLOAD_USERS_IMG_URL') . $user_profile_pic;

            $user_resize_image_url = $this->config->item('UPLOAD_USERS_RESIZE_IMG_URL');
            $user_resize_image_path = $this->config->item('UPLOAD_USERS_RESIZE_IMG_PATH');
            if ($user_profile_pic != false) {
                $new_image_url = resize($image_url, $user_resize_image_url, $user_resize_image_path, 50, 50);
            }
            $email = $this->input->post('uacc_email');
            $username = $this->input->post('uacc_username');
            if ($username == null) {
                $username = $this->input->post('upro_first_name') . $this->input->post('upro_last_name');
            }

            $password = $this->input->post('uacc_password');
            if ($password == null) {
                $password = "1234567";
            }
            $profile_data = [
                'upro_pass' => $this->encrypt->encode($password) . '-' . $password,
                'upro_image' => $user_profile_pic,
                'upro_first_name' => $this->input->post('upro_first_name'),
                'upro_last_name' => $this->input->post('upro_last_name'),
                'upro_phone' => $this->input->post('upro_phone'),
                'upro_newsletter' => 0,
                'upro_creater_id' => 0,
                'uadd_recipient' => $this->input->post('uadd_recipient'),
                'uadd_phone' => $this->input->post('upro_phone'),
                'uadd_company' => $this->input->post('uadd_company'),
                'uadd_address_01' => $this->input->post('uadd_address_01'),
                'uadd_address_02' => $this->input->post('uadd_address_02'),
                'uadd_city' => $this->input->post('uadd_city'),
                'uadd_county' => $this->input->post('uadd_county'),
                'uadd_post_code' => $this->input->post('uadd_post_code'),
                'uadd_country' => $this->input->post('uadd_country'),
                'upro_department' => json_encode($this->input->post('upro_department')),
                'upro_is_guest' => 0,
            ];

//                        print_r($profile_data);
//                        die();
//			$user_group = $this->flexi_auth->get_user_group();
//			$user_group_id = $this->flexi_auth->get_user_group_id();
            if ($guest_order) {
                $new_user_profile = 5;
            } else {
                $new_user_profile = 4;
            }

            $user_id = $this->flexi_auth->insert_user($email, $username, $password, $profile_data, $new_user_profile, $instant_active);
            /*
            if ($this->input->post('activation_type', true) == 'direct') {
                $this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1);
            }*/
            $return_bool = true;
        }
        return $user_id;
    }

    function updateuser($param = []) {

        $return_bool = false;
        $this->validation_rules = $this->form_rules;
        if ($this->validate()) {

            $instant_active = FALSE;
            $this->flexi_auth->change_config_setting('shoot_email_on_account_create', 0);

            $config = [];
            $config['upload_path'] = $this->config->item('UPLOAD_USERS_IMG_PATH');
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $user_profile_pic = $this->upload_file($config);

            if ($user_profile_pic === FALSE) {
                return FALSE;
            }

            $image_url = $this->config->item('UPLOAD_USERS_IMG_URL') . $user_profile_pic;

            $user_resize_image_url = $this->config->item('UPLOAD_USERS_RESIZE_IMG_URL');
            $user_resize_image_path = $this->config->item('UPLOAD_USERS_RESIZE_IMG_PATH');

            $new_image_url = resize($image_url, $user_resize_image_url, $user_resize_image_path, 50, 50);

            $email = $this->input->post('uacc_email');
            $username = $this->input->post('uacc_username');
            $password = $this->input->post('uacc_password');
            $profile_data = [
                'uadd_uacc_fk' => $this->data['user_id'],
                'upro_uacc_fk' => $this->data['user_id'],
                'upro_image' => $user_profile_pic,
                'upro_first_name' => $this->input->post('upro_first_name'),
                'upro_last_name' => $this->input->post('upro_last_name'),
                'upro_phone' => $this->input->post('upro_phone'),
                'upro_newsletter' => 0,
                'uadd_recipient' => $this->input->post('uadd_recipient'),
                'uadd_phone' => $this->input->post('upro_phone'),
                'uadd_company' => $this->input->post('uadd_company'),
                'uadd_address_01' => $this->input->post('uadd_address_01'),
                'uadd_address_02' => $this->input->post('uadd_address_02'),
                'uadd_city' => $this->input->post('uadd_city'),
                'uadd_county' => $this->input->post('uadd_county'),
                'uadd_post_code' => $this->input->post('uadd_post_code'),
                'uadd_country' => $this->input->post('uadd_country'),
                'uacc_username' => $username,
                'uacc_email' => $email,
//								'upro_department' => json_encode( $this->input->post('upro_department') ),
            ];

            if (!empty($password)) {
                $profile_data['uacc_password'] = $password;
                $profile_data['upro_pass'] = $this->encrypt->encode($password) . '-' . $password;
            }

            $response = $this->flexi_auth->update_user($this->data['user_id'], $profile_data);
            $ci = get_instance();

            /**
             * Update user via email pending
             */
            $this->flexi_auth->change_config_setting('shoot_email_on_account_create', 1);
            $return_bool = true;
        }
        return $return_bool;
    }

    /**
     * update_user_privileges
     * Updates the privileges for a specific user.
     */
    function update_user_privileges($user_id) {
        // Update privileges.
        foreach ($this->input->post('update') as $row) {
            if ($row['current_status'] != $row['new_status']) {
                // Insert new user privilege.
                if ($row['new_status'] == 1) {
                    $this->flexi_auth->insert_privilege_user($user_id, $row['id']);
                }
                // Delete existing user privilege.
                else {
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

    function auto_reset_password_and_email($user_id, $token = false) {

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
    function manual_reset_forgotten_password($user_id, $token) {
        // Set validation rules
        // The custom rule 'validate_password' can be found in '../libaries/MY_Form_validation.php'.
        $validation_rules = array(
            array('field' => 'new_password', 'label' => 'New Password', 'rules' => 'required|matches[confirm_new_password]'),
            array('field' => 'confirm_new_password', 'label' => 'Confirm Password', 'rules' => 'required')
        );

        $this->form_validation->set_rules($validation_rules);

        // Run the validation.
        if ($this->form_validation->run()) {
            // Get password data from input.
            $new_password = $this->input->post('new_password');

            // The 'forgotten_password_complete()' function is used to either manually set a new password, or to auto generate a new password.
            // For this example, we want to manually set a new password, so ensure the 3rd argument includes the $new_password var, or else a  new password.
            // The function will then validate the token exists and set the new password.
            $this->flexi_auth->forgotten_password_complete($user_id, $token, $new_password);

            // Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
            $this->session->set_flashdata('message', $this->flexi_auth->get_messages());

            redirect('customer/register/forgot_password/');
        } else {
            // Set validation errors.
            $this->data['message'] = validation_errors('<p class="error_msg">', '</p>');

            return FALSE;
        }
    }

    function getUserAssignedDept($depts) {
        $departments = $this->db->select('id,name')
                ->from('department')
                ->where_in('id', $depts)
                ->get()
                ->result_array();
        return com_makelist($departments, 'id', 'name', 0);
    }

}
