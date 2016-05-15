<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_allocation extends Admin_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model('Commonusermodel');
        $this->load->model('company/Companymodel', 'Companymodel');
        $this->data = [];
    }

    function company( ) {

        if (! $this->flexi_auth->is_privileged('View Company Product Allocation')){ 
        	$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to View Product Allocation.</p>'); 
        	redirect('dashboard'); 
    	}

        if( $this->input->post('products') ) {            
            $this->Companymodel->productAssign(  );
            $this->session->set_flashdata('SUCCESS', 'comp_prod_alloc');
            redirect('product_allocation/company');
            exit();
        }
        /*  set internally company_list */
        $this->Companymodel->getCompanyList();
    	$this->data['content'] = $this->load->view('company-product-allocation' , $this->data, true);
        $this->load->view($this->template['without_menu'], $this->data);
    }

    function user( ) {
        if (! $this->flexi_auth->is_privileged('View User Product Allocation')){
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to View Product Allocation.</p>'); 
            redirect('dashboard'); 
        }

        //com_e( $this->input->post() );
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}

        $this->load->model('company/Departmentmodel', 'Departmentmodel');
        $this->data['departments'] = com_makelist( $this->Departmentmodel->getCompanyDept( $company_code ) , 'id', 'name', 1, 'Select Kit');

        if( $this->input->post('product') ) {
            $this->load->model('Userprodallocationmodel');
            $param = [];
            $param['company_code'] = $company_code;
            $this->Userprodallocationmodel->update_user_product_allocation( $param );

            $this->session->set_flashdata('SUCCESS', 'Product succesfully allocated to user');
            $this->data['SUCCESS'] = $this->session->flashdata('SUCCESS');            
        }
        //com_e( $this->data , 0);
        $this->data['content'] = $this->load->view('user-product-allocation' , $this->data, true);
        $this->load->view($this->template['without_menu'], $this->data);
    }

    function user_bonus_stock( ) {
        if (! $this->flexi_auth->is_privileged('Bonus stock distribution')){
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to distribute bonus stock.</p>'); 
            redirect('dashboard'); 
        }

        //com_e( $this->input->post() );
        $company_code = "";
        if( $this->user_type == CMP_ADMIN ){
			$company_code = $this->flexi_auth->get_comp_admin_company_code();
		} else if( $this->user_type == CMP_MD || $this->user_type == CMP_PM ) {
			$company_code = $this->flexi_auth->get_user_custom_data( 'upro_company' );
		}
		$this->load->model('company/Compstoremodel', 'Companystoremodel');
		$opt = [];
		$opt[ 'company_code' ] = $company_code;
		$this->data['company_stores'] = $this->Companystoremodel->get_list( $opt );						
		$this->data['company_stores'] = com_makelist($this->data['company_stores'], 'id', 'store_name', 0);
		$this->data['company_stores'][ "0" ] = 'Main store';
		$this->data['store_changed'] = 0;
		ksort( $this->data['company_stores'] );
        $this->load->model('company/Departmentmodel', 'Departmentmodel');
        $this->data['departments'] = com_makelist( $this->Departmentmodel->getCompanyDept( $company_code ) , 'id', 'name', 1, 'Select Kit');

        if( $this->input->post('quantity') ) {			
            $this->load->model('company/Userstockmodel', 'Userstockmodel');
            $param = [];
            $param['company_code'] = $company_code;
            $this->Userstockmodel->issue_user_bonus_stock( $param );
            $this->session->set_flashdata('SUCCESS', 'Bonus product succesfully distributed to user');
            $this->data['SUCCESS'] = $this->session->flashdata('SUCCESS');            
        }
        //com_e( $this->data , 0);
        $this->data['content'] = $this->load->view('user-bonus-product-distribution' , $this->data, true);
        $this->load->view($this->template['without_menu'], $this->data);        
    }
}
