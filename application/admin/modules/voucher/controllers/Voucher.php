<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Voucher extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Vouchermodel');
        /*
        if (!$this->checkAccess('MANAGE_voucherS')) {
            show_error('You do not have permission to access this resource!');
        }
        */ 
    }

    // validation start for add******************************************

    function validDate() {
        $frm_date = $this->input->post('active_from', TRUE);
        $to_date = $this->input->post('active_to', TRUE);
        if ($to_date < $frm_date) {
            $this->form_validation->set_message('validDate', 'Active To date can not be less than Active From date.');
            return false;
        }
        return True;
    }


    //valid voucher title
    function valid_value($str, $otherFields) {
		$otherFields 	= 	explode("|", $otherFields);
		list($id)		=	$otherFields;
        $this->db->where('code', $str)
				->where('id !=', $id);
        $query = $this->db->get('voucher');        
        if ($query->num_rows() != 0) {
            $this->form_validation->set_message('valid_value', 'Voucher is already in use');
            return false;
        }

        return true;
    }

    //valid voucher title
    function validvoucher($str) {
        $this->db->where('title', $str);
        $this->db->where('id !=', $this->input->post('id', true));
        $query = $this->db->get('voucher');
        if ($query->num_rows() != 0) {
            $this->form_validation->set_message('validvoucher', 'Voucher is already existing!');
            return false;
        }
        return true;
    }

    //**************************************************validation end

    function index($offset = false) {
		$opt = [];
		if( $this->input->post( 'search_voucher' ) ){			
			$code =	$this->input->post( 'code', true );
			if( $code ){
				$opt[ 'code' ] = $code;
			}
			/*
			$active = $this->input->post( 'active');
			if( $active !== '' ){
				com_changeNull($active, 0);
				$opt[ 'active']  = $active;			
			}
			*/ 
		}
		$sortby = 'code';
		$direction = 'asc';        
        $perpage = 20;
        $config['base_url'] = base_url() . "voucher/index/";
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Vouchermodel->countAll( $opt );
        
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        //Get all voucher
        $voucher = array();
        $voucher = $this->Vouchermodel->listAll($offset, $perpage, $opt);
		
        if ($direction == 'desc') {
            $toggle_direction = 'asc';
        } else {
            $toggle_direction = 'desc';
        }

        //render view
        $inner = array();
        $inner['voucher'] = $voucher;        
        $inner['voucher_style'] = [ 'value' => 'Value', 'percentage' => 'Percentage' ];
        $inner['toggle_direction'] = $toggle_direction;
        $inner['sortby'] = $sortby;
        $inner['pagination'] = $this->pagination->create_links();

        $page = array();
        $page['content'] = $this->load->view('voucher-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    //function add
    function add() {		
        $voucher_type = array();
        $voucher_type['value'] = 'Value';
        $voucher_type['percentage'] = 'Percentage';
        
        //Form Validation
        $other_field = '0';
        $this->form_validation->set_rules('code', 'Voucher code', 'trim|required|min_length[3]|max_length[10]|callback_valid_value['.$other_field.']');
        //$this->form_validation->set_rules('description', 'Breif description', 'trim|required');
        $this->form_validation->set_rules('vstyle', 'Voucher action on', 'trim|required');
        $this->form_validation->set_rules('amount', 'Voucher amount', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('active_from', 'Active From', 'trim|required');
        $this->form_validation->set_rules('active_to', 'Active To', 'trim|required|callback_validDate');
        $min_o_value = 0;
        $voucher_cost = $this->input->post( 'amount' );
        if( $voucher_cost && $voucher_cost > 0){
			$min_o_value = $voucher_cost;
		}
        $this->form_validation->set_rules('min_order_value', 'Minimum Order Value', 'trim|required|greater_than['.$min_o_value.']');
        $this->form_validation->set_rules('use_time', 'One Time Use', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('user_style', 'User style', 'trim|required');
        
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();            
            $sql_select = 'uacc_id, uacc_username';
            $sql_where = [];
            $sql_where[ 'uacc_group_fk' ] = 4;
            $customers =  com_makelist(	$this->flexi_auth->get_users($sql_select, $sql_where)->result_array(), 
										'uacc_id', 'uacc_username', FALSE);
			asort( $customers );
			$inner['customers'] = $customers;
            $inner['voucher_type'] = $voucher_type;            
            $page['content'] = $this->load->view('voucher-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Vouchermodel->insertRecord();
            $this->session->set_flashdata('SUCCESS', 'voucher_added');
            redirect("voucher/index/");
            exit();
        }
    }

    function delete($cid) {
        //Get voucher Details
        $voucher = array();
        $voucher = $this->Vouchermodel->detail($cid);
        if (!$voucher) {
			$opt = [];
			$opt[ 'top_text' ] = '';
			$opt[ 'bottom_text' ] = '';
            $this->utility->show404();
            return;
        }
        //Delete voucher
        $this->Vouchermodel->deleteRecord($voucher);
        $this->session->set_flashdata('SUCCESS', 'voucher_deleted');
        redirect("voucher/index/");
        exit();
    }
    //function edit
    function edit($cid) {
        $voucher = array();
        $voucher = $this->Vouchermodel->detail($cid);
        if (!$voucher) {
            $this->utility->show404();
            return;
        }
        
        $voucher_type = array();
        $voucher_type['value'] = 'Value';
        $voucher_type['percentage'] = 'Percentage';
        
        //Form Validation
        $other_field = '0';        
       // $this->form_validation->set_rules('description', 'Breif description', 'trim|required');
        $this->form_validation->set_rules('vstyle', 'Voucher action on', 'trim|required');
        $this->form_validation->set_rules('amount', 'Voucher amount', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('active_from', 'Active From', 'trim|required');
        $this->form_validation->set_rules('active_to', 'Active To', 'trim|required|callback_validDate');
        $min_o_value = 0;
        $voucher_cost = $this->input->post( 'amount' );
        if( $voucher_cost && $voucher_cost > 0){
			$min_o_value = $voucher_cost;
		}
        $this->form_validation->set_rules('min_order_value', 'Minimum Order Value', 'trim|required|greater_than['.$min_o_value.']');
        $this->form_validation->set_rules('use_time', 'One Time Use', 'trim|required|greater_than[0]');        
        
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $customers = [ ];
            if ( $voucher[ 'user_style' ] == 'S' ){
				if($voucher[ 'customers' ]){
					$customers_id = explode(",", $voucher[ 'customers' ] );
					if( $customers_id && is_array( $customers_id ) ){
						$sql_select = [ 'distinct('.$this->flexi_auth->db_column('user_acc', 'username').')' ];						
						$sql_where[ 'uacc_group_fk' ] = 4;
						$this->flexi_auth->sql_select($sql_select);
						$this->flexi_auth->sql_where( $sql_where );
						$this->flexi_auth->sql_where_in('uacc_id', $customers_id );
						$customers = $this->flexi_auth->search_users_array(FALSE);
					}
				}
			}
			$inner[ 'customers' ] = $customers;
            $inner['voucher'] = $voucher;
            $inner['voucher_type'] = $voucher_type;
            $page['content'] = $this->load->view('voucher-edit', $inner, TRUE);            
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Vouchermodel->updateRecord($voucher);
            $this->session->set_flashdata('SUCCESS', 'voucher_updated');
            redirect("voucher/index/");
            exit();
        }
    }

    //enable voucher
    function enable($cid) {
        //get voucher detail
        $voucher = array();
        $voucher = $this->Vouchermodel->detail($cid);
        if (!$voucher) {
            $this->utility->show404();
            return;
        }

        $this->Vouchermodel->enableRecord($voucher);

        $this->session->set_flashdata('SUCCESS', 'voucher_updated');

        redirect("voucher/index/");
        exit();
    }

    //disable voucher
    function disable($cid) {
        //get voucher detail
        $voucher = array();
        $voucher = $this->Vouchermodel->detail($cid);
        if (!$voucher) {
            $this->utility->show404();
            return;
        }
        $this->Vouchermodel->disableRecord($voucher);        
        $this->session->set_flashdata('SUCCESS', 'voucher_updated');

        redirect("voucher/index/");
        exit();
    }

}
