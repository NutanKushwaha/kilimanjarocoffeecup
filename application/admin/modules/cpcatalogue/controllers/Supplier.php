    <?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
        $this->load->model('Suppliermodel');                    
    }

    function check_brand_name($field_val, $other_param){
        list($supplier_id, $edit_record) = explode('|', $other_param);
        $options =  [];        
        $options[ 'supplier_id' ] 	=   $supplier_id;
        $options[ 'brand_id' ]		=   $edit_record;
        $options[ 'brand_name' ] =   $field_val;
        if ($this->Suppliermodel->count_brands( $options )[ 'ttl' ]) {
            $this->form_validation->set_message('check_brand_name', 'Brand name already occupied!');
            return false;
        }
        return true;
    }

    function index() {
        $this->session->set_flashdata('message', 
                    '<p class="error_msg">You do not have privileges to view View Supplier.</p>');
                redirect('dashboard'); 
        
        if (! $this->flexi_auth->is_privileged('View Supplier')){ 
                $this->session->set_flashdata('message', 
                    '<p class="error_msg">You do not have privileges to view View Supplier.</p>');
                redirect('dashboard'); 
        }

        
        $this->load->helper('text');        
        $supplier = array();
        $supplier = $this->Suppliermodel->listAll();

        $inner = array();
        $inner['supplier'] = $supplier;
        $inner['mod_menu'] = 'layout/inc-menu-catalog';
        $page = array();
        $page['content'] = $this->load->view('supplier/supplier-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    function add() {
        if (! $this->flexi_auth->is_privileged('Insert Supplier')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Supplier.</p>'); redirect('dashboard'); }

        $this->form_validation->set_rules('supplier_name', 'Supplier Name',
            'trim|required|is_unique[supplier.supplier_name]');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $page['content'] = $this->load->view('supplier/supplier-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Suppliermodel->insertRecord();
            $this->session->set_flashdata('SUCCESS', 'supplier_added');
            redirect('cpcatalogue/supplier');
            exit();
        }
    }

    function edit($sid = FALSE) {
        if (! $this->flexi_auth->is_privileged('Update Supplier')){ 
                $this->session->set_flashdata('message', 
                    '<p class="error_msg">You do not have privileges to view Update Supplier.</p>'); 
                redirect('dashboard'); 
        }
        
        $supplier = array();
        $supplier = $this->Suppliermodel->getDetails($sid);
        if (!$supplier) {
            $this->utility->show404();
            return;
        }

        $this->form_validation->set_rules('supplier_name', 'required');

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $inner['supplier'] = $supplier;
            $page = array();
            $page['content'] = $this->load->view('supplier/supplier-edit', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Suppliermodel->updateRecord($supplier['supplier_id']);

            $this->session->set_flashdata('SUCCESS', 'supplier_updated');
            redirect('cpcatalogue/supplier');
            exit();
        }
    }

    function delete($sid) {
        if (! $this->flexi_auth->is_privileged('Delete Supplier')){ 
                $this->session->set_flashdata('message', 
                    '<p class="error_msg">You do not have privileges to view Delete Supplier.</p>'); 
                redirect('dashboard'); 
            }        

        $supplier = array();
        $supplier = $this->Suppliermodel->getDetails($sid);
        if (!$supplier) {
            $this->utility->show_404();
            return;
        }

        $this->Suppliermodel->deleteRecord($supplier['supplier_id']);

        $this->session->set_flashdata('SUCCESS', 'supplier_deleted');
        redirect('cpcatalogue/supplier');
        exit();
    }

    function brands( $supplier_id, $brand_id = false ) {
        
        if (! $this->flexi_auth->is_privileged('Manage brands')){
                $this->session->set_flashdata('message',
                    '<p class="error_msg">You do not have privileges to manage Supplier.</p>'); 
                redirect('dashboard'); 
        }
        
        $supplier = array();
        $supplier = $this->Suppliermodel->getDetails( $supplier_id );
        if ( !$supplier ){
            $opt = [];
            $opt[ 'top_text' ]      = 'Supplier not found';
            $opt[ 'bottom_text' ]   = 'Supplier does not found';
            $this->utility->show404( $opt );
            return;
        }
        $brand_id = intval( $brand_id );
        $supplier_id = intval( $supplier_id );
        $check_store_name 	= $supplier_id.'|'.$brand_id;
        $this->form_validation->set_rules('brand_name',  'Brand Name', 'required|callback_check_brand_name['.$check_store_name.']');
		$brand_det = [];
		$brand_det[ 'brand_name' ] = '';
		if( $brand_id ){
			$opt = [];
			$opt[ 'brand_id' ] = intval($brand_id);
			$opt[ 'supplier_id' ] = intval($supplier_id);
			$brand_det = $this->Suppliermodel->get_brand_Details( $opt );
		}
		if( !$brand_det ){
			$brand_id = 0;
		}
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        if ($this->form_validation->run() ) {
			if(  !$brand_id ){
				$opt = [];
				$opt[ 'supplier_id' ] = $supplier_id;
				$this->Suppliermodel->insert_brand( $opt );
				$this->session->set_flashdata('SUCCESS', 'supplier_brand_added');
			} else {
				$opts = [];
				$opts[ 'brand_id' ] = $this->input->post( 'brand_id' );
				$opts[ 'brand_name' ] = $this->input->post( 'brand_name' );
				$opts[ 'supplier_id' ] = $this->input->post( 'supplier_id' );				
				$this->Suppliermodel->update_brand( $opts );				
				$this->session->set_flashdata('SUCCESS', 'supplier_brand_updated');
			}
            redirect('cpcatalogue/supplier/brands/'.$supplier_id, 'location');
            exit(); 
        }
        
        $inner = [];
        $inner['supplier_name'] 	= $supplier[ 'supplier_name' ];
        $inner[ 'supplier_id' ]     = $supplier[ 'supplier_id' ];
        $per_page = 1;
        $offset = com_gParam('offset', TRUE, 0);        
        ///Setup pagination        
        $config[ 'cur_page' ]       = $offset;
        $config['total_rows']       = $this->Suppliermodel->get_supplier_brand_count( $supplier[ 'supplier_id' ] )[ 'ttl' ];
        $config['html_container']   = 'store-view-div';
        $config['base_url']         = 'cpcatalogue/supplier/brands/'.$supplier[ 'supplier_id' ];
        $config['per_page']         = $per_page;
        $config['js_rebind']        = '';
        $config['request_type']     = 'get';
        $inner[ 'pagination' ]      =  com_ajax_pagination($config);
        $inner[ 'supplier_brand' ]  =  $this->Suppliermodel->get_supplier_brand_list( $supplier[ 'supplier_id' ], 
                                        $offset, $per_page );
        $inner[ 'supplier_brand_list' ]  =  $this->load->view('supplier/ajax/supplier-brand-index', $inner , true );
        if( $this->input->is_ajax_request() ){
			$output = [];
			$output[ 'success' ] 	= 1;
			$output[ 'html' ] 		= $inner[ 'supplier_brand_list' ];
			echo json_encode( $output );
			exit;
		}
        $inner[ 'brand_id' ] 			= $brand_id;
        $inner[ 'brand_det' ] 			= $brand_det;        
        $page = array();
        $page['content'] = $this->load->view('supplier/supplier-brand-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }
    
    function delete_brand($supplier_id,  $brand_id ){
		$brand = array();
		$opt = [];
		$opt[ 'brand_id' ] = intval($brand_id);
		$opt[ 'supplier_id' ] = intval($supplier_id);
        $brand = $this->Suppliermodel->get_brand_Details( $opt );
        if ( !$brand ){
            $opt = [];
            $opt[ 'top_text' ]      = 'Brand not found';
            $opt[ 'bottom_text' ]   = 'Brand does not found';
            $this->utility->show404( $opt );
            return;
        }        
        $this->Suppliermodel->delete_brand( $brand );
		$this->session->set_flashdata('SUCCESS', 'supplier_brand_deleted');
		redirect('cpcatalogue/supplier/brands/'.$supplier_id, 'location');        
	}
}
