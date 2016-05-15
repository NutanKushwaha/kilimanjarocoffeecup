<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CMS_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('Common_auth_model');
        $this->load->model('Commonusermodel');
        $this->data = null;
    }

    function index() {
        if($this->isLogged){
            redirect('dashboard');
        }
        if ($this->input->post('login_user')){
            if(!$this->Common_auth_model->login()){
                // Get any status message that may have been set.
                $this->data['ERROR'] = (! isset($this->data['ERROR'])) ? 
                                        $this->session->flashdata('message') : $this->data['ERROR'];
            }else{
                redirect('dashboard');
            }               
        }else{
            $this->data['INFO'] = (! isset($this->data['INFO'])) ? 
                                        $this->session->flashdata('message') : $this->data['INFO'];
        }
        $this->load->view(THEME . 'login', $this->data);    
    }

    /**
     * logout
     * This example logs the user out of all sessions on all computers they may be logged into.
     * In this demo, this page is accessed via a link on the demo header once a user is logged in.
     */
    function logout(){

        if($this->isLogged){         
            // By setting the logout functions argument as 'TRUE', all browser sessions are logged out.
            $this->flexi_auth->logout(TRUE);
            
            // Set a message to the CI flashdata so that it is available after the page redirect.
            $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
        }
        
        redirect('/');
    }

    function lostpasswd(  ) {
        if($this->isLogged){
            redirect('dashboard');
        }
        $this->data = [];
        $userIdentity =  $this->input->post( 'login_identity' );
        //validation check        
        $rules[] = array('field' => 'login_identity',
            'label' => 'Username | Email',
            'rules' => 'trim|required');
        $this->Commonusermodel->validation_rules = $rules;
        if ($this->Commonusermodel->validate() && !$this->flexi_auth->identity_available($userIdentity)) {
            //$this->Commonusermodel->auto_reset_password_and_email( $userIdentity );
            $this->flexi_auth->forgotten_password($userIdentity);
            // Set a message to the CI flashdata so that it is available after the page redirect.            
            $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
            redirect(base_url());
            exit();
        }
        $this->load->view(THEME.'welcome/user-forgot-pass', $this->data);
    }

    /**
     * manual_reset_forgotten_password
     * This is step 2 (The last step) of an example of allowing users to reset a forgotten password manually. 
     * See the auto_reset_forgotten_password() function below for an example of directly emailing the user a new randomised password.
     * In this demo, this page is accessed via a link in the 'views/includes/email/forgot_password.tpl.php' email template, which must be set to 'auth/manual_reset_forgotten_password/...'.
     */
    function manual_reset_forgotten_password($user_id = FALSE, $token = FALSE)
    {
        //com_e( func_get_args() );
        // If the 'Change Forgotten Password' form has been submitted, then update the users password.
        if ($this->input->post('change_forgotten_password')) 
        {
            $this->Commonusermodel->manual_reset_forgotten_password($user_id, $token);
        }
        
        // Get any status message that may have been set.
        $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : 
        $this->data['message'];             
        $this->load->view(THEME.'welcome/user-forgot-pass-update', $this->data);    
    }    
    
	/**
	 * activate_account
	 * User account activation via email.
	 * The default setup of this demo requires that new account registrations must be authenticated via email before the account is activated.
	 * In this demo, this page is accessed via an activation link in the 'views/includes/email/activate_account.tpl.php' email template.
	 */ 
	function activate_account($user_id, $token = FALSE)
	{		
		$search_user_where[ 'uacc_id' ] = $user_id;
		$user_det = $this->flexi_auth->search_users_query(FALSE, TRUE, "uacc_id, uacc_activation_token, uacc_active, uacc_suspend", $search_user_where, FALSE)->row_array();
		if( $user_det && $user_det['uacc_active']){
			$this->session->set_flashdata('message', 'Your account successfully activated.');
		} else {
			// The 3rd activate_user() parameter verifies whether to check '$token' matches the stored database value.
			// This should always be set to TRUE for users verifying their account via email.
			// Only set this variable to FALSE in an admin environment to allow activation of accounts without requiring the activation token.
			$this->flexi_auth->activate_user($user_id, $token, TRUE);
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		}
		redirect('welcome');
	}    
}
