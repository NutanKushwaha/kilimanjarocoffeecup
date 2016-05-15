<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends Adminajax_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Commonusermodel');
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->data = [];
        $this->data['user_type'] = $this->user_type;
    }

	function index( ) {
		$offset = com_gParam('offset', false , false); 
		$this->Commonusermodel->get_comp_user_accounts($offset);
        $output = [
                    'success' => 1,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                    'html' => $this->load->view('users/ajax/user-list', $this->data, TRUE),                    
                ];
        echo json_encode( $output );
        exit;
	}

    function getGroupUsers( ){
        $this->flexi_auth->sql_clear();
        $group = com_gParam( 'group', TRUE, '0' );
        $sql_select = 'uacc_id, uacc_username';
        if( $group ){
            $sql_where[$this->flexi_auth->db_column('user_group', 'id') ] = intval( $group );
        }        
        if( $this->user_type == CMP_ADMIN ){
            if( $group == 2 ) {
                $sql_where[$this->flexi_auth->db_column('user_acc', 'id') ] = $this->flexi_auth->get_user_custom_data('uacc_id');
            } else {
                $company_code = $this->flexi_auth->get_comp_admin_company_code();
                $sql_where[ 'upro_company' ] = $company_code;                
            }
        }        
        $users = $this->flexi_auth->get_users_array($sql_select, $sql_where);        
        $output = [];
        $output[ 'html' ] = com_makelistElem($users, 'uacc_id', 'uacc_username');
        $output[ 'success' ] = 1;
        echo json_encode( $output );
        exit;
    }
    
    function getCompGroupUsers( ){
		/* For company director only company need
		 * For company purchase	manager director need
		 * For company user purchase manager need
		 * function will called only by admin, comp admin, director
		 * */
		$ouput = [];
		$ouput[ 'html' ] = '';
		$ouput[ 'success' ] = true;
		$ouput[ 'required' ] = false;
		$company = $this->input->get( 'company' );
		$user_profile = $this->input->get( 'userProfile' );		
		if( in_array($user_profile, [7, 1]) ){
			$ouput[ 'required' ] = true;
			if( $user_profile == 1 ){
				$title = 'Register user';
				$req_user_groups = 7;
			} else if( $user_profile == 7 ) {
				$title = 'Council';
				$req_user_groups = 6;				
			}
			$opt = [];
			$opt[ 'select' ] = 'uacc_id, uacc_username, uacc_group_fk, upro_company';
			$opt[ 'from_tbl' ] = 'user_profiles';			
			$opt['join'][] = [	'tbl' => 'user_accounts', 
								'type' => 'inner',
								'cond' => 'upro_uacc_fk=uacc_id', 								
							];
			$opt[ 'where' ][ 'upro_company' ] = $company;
			$opt[ 'where' ][ 'uacc_group_fk' ] = $req_user_groups;
			if( $this->user_type == CMP_MD && $user_profile == 7 ){
				$opt[ 'where' ][ 'uacc_id' ] = $this->flexi_auth->get_user_id();
			} else if( $this->user_type == CMP_MD && $user_profile == 1 ){
				$opt[ 'where' ][ 'upro_approval_acc' ] = $this->flexi_auth->get_user_id();
			} else if( $this->user_type == CMP_PM && $user_profile == 1 ){ 
				$opt[ 'where' ][ 'uacc_id' ] = $this->flexi_auth->get_user_id();
			}
			$comp_users = $this->Companymodel->get_all( $opt );			
			if( $comp_users ){
				$comp_users = com_makelist( $comp_users, 'uacc_id', 'uacc_username', FALSE, 'Select' );
				$field_dd = form_dropdown( 'upro_approval_acc', $comp_users, '', ' class="form-control"');
			} else {
				//$title = strtolower( $title );
				$field_dd = 'Create '.$title.' , '.$title.' not found';
			}
			$html = '<td>'.$title.' *:</td><td>'.$field_dd.'</td>';
			$ouput[ 'html' ] = $html;
		}
		echo json_encode( $ouput );
		exit;
	}
}
