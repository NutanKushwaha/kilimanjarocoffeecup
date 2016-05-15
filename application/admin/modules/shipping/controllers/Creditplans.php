<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Creditplans extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->is_admin_protected = TRUE;
	}

	function index($offset = false) {

		$this->load->model('Creditplansmodel');
		
		$this->load->helper('text');
		

		//Setup pagination
		$perpage = 25;
		$config['base_url'] = base_url() . "settings/creditplans/index/";
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->Creditplansmodel->countAll();
		$config['per_page'] = $perpage;
		$this->pagination->initialize($config);

		//Get all credit plans
		$credit_plans = array();
		$credit_plans = $this->Creditplansmodel->listAll($offset, $perpage);

		//render view
		$inner = array();
		$inner['credit_plans'] = $credit_plans;
		$inner['pagination'] = $this->pagination->create_links();

		$page = array();
		$page['content'] = $this->load->view('credit-plan/credit-plans-index', $inner, TRUE);
		$this->load->view($this->shellFile, $page);
	}

	//function add
	function add() {

		
		
		$this->load->model('Creditplansmodel');

		//Form Validation
		$this->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required');
		$this->form_validation->set_rules('credits', 'Credits', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_error_delimiters('<li>', '</li>');

		if ($this->form_validation->run() == FALSE) {
			$inner = array();
			$page['content'] = $this->load->view('credit-plan/credit-plans-add', $inner, TRUE);
			$this->load->view($this->shellFile, $page);
		} else {
			$this->Creditplansmodel->insertRecord();


			$this->session->set_flashdata('SUCCESS', 'credit_plan_added');

			redirect("settings/creditplans/index");
			exit();
		}
	}

	//function edit
	function edit($cid) {
		
		
		$this->load->model('Creditplansmodel');
		$credit_plan = array();
		$credit_plan = $this->Creditplansmodel->detail($cid);
		if (!$credit_plan) {
			$this->utility->show404();
			return;
		}

		//Form Validation
		$this->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required');
		$this->form_validation->set_rules('credits', 'Credits', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_error_delimiters('<li>', '</li>');

		if ($this->form_validation->run() == FALSE) {
			$inner = array();
			$inner['credit_plan']=$credit_plan;
			$page = array();
			$page['content'] = $this->load->view('credit-plan/credit-plans-edit', $inner, TRUE);
			$this->load->view($this->shellFile, $page);
		} else {
			$this->Creditplansmodel->updateRecord($credit_plan['credit_plan_id']);

			$this->session->set_flashdata('SUCCESS', 'credit_plan_updated');
			redirect("settings/creditplans/index");
			exit();
		}
	}

	function delete($cid) {
		
		
		$this->load->model('Creditplansmodel');

		$credit_plan = array();
		$credit_plan = $this->Creditplansmodel->detail($cid);
		if (!$credit_plan) {
			$this->utility->show404();
			return;
		}

		//Delete Credit plan
		$this->Creditplansmodel->deleteRecord($credit_plan['credit_plan_id']);
		$this->session->set_flashdata('SUCCESS', 'credit_plan_deleted');
		redirect("settings/creditplans/index");
		exit();
	}



}

?>