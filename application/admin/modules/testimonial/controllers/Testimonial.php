<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonial extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('Testimonialmodel');
	}
	
	function index($offset = 0) {
		$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Testimonials.</p>'); 
            redirect('dashboard');

		//set pagination
		$perpage = 7;
		$config['base_url'] = base_url()."testimonial/index/";
		$config['uri_segment'] = 3;
		$config['total_rows'] = $this->Testimonialmodel->countAll();
		$config['display_pages'] = FALSE; 
		$config['per_page'] = $perpage; 
		$this->pagination->initialize($config);
		
		//render view
		$testimonials = array();
		$testimonials = $this->Testimonialmodel->listAll();
		
		
		//render view
		$inner = array();
		$inner['testimonials'] = $testimonials;
		$inner['pagination'] = $this->pagination->create_links();
		
		$page = array();
		$page['content'] = $this->load->view('testimonial-index', $inner, TRUE);
		$this->load->view($this->template['default'], $page);
		
	}
	
	//Function Add testimonial
	function add(){				
		$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Testimonials.</p>'); 
            redirect('dashboard');

		//validation check
		$this->form_validation->set_rules('name', 'Testimonial Name', 'trim|required');
		//$this->form_validation->set_rules('url_alias', 'Testimonial Name', 'trim');
		$this->form_validation->set_rules('testimonial', 'Testimonial', 'trim|required');
		
		
		$this->form_validation->set_error_delimiters('<li>', '</li>');

		if ($this->form_validation->run() == FALSE) {			
			$inner = array();
			$page = array();
			$page['content'] = $this->load->view('testimonial-add', $inner, TRUE);
			$this->load->view($this->template['default'], $page);
	   }
	  else {
			$this->Testimonialmodel->insertRecord();
			
			$this->session->set_flashdata('SUCCESS', 'testimonial_added');
			
			redirect("/testimonial/index/");
			exit();
	 	}
	}
	
	//Function Add testimonial
	function edit($pid){
		$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Testimonials.</p>'); 
            redirect('dashboard');

		//get testimonial detail
		$testimonial = array();
		$testimonial = $this->Testimonialmodel->getDetails($pid);
		if(!$testimonial){
			$this->utility->show404();
            return;
		}
		
		///validation check
		$this->form_validation->set_rules('name', 'Testimonial Name', 'trim|required');
		//$this->form_validation->set_rules('url_alias', 'Testimonial Name', 'trim');
		$this->form_validation->set_rules('testimonial', 'Testimonial', 'trim|required');
		

		$this->form_validation->set_error_delimiters('<li>', '</li>');

		if ($this->form_validation->run() == FALSE) {			
			$inner = array();
			$inner['testimonial'] = $testimonial;
			$page = array();
			$page['content'] = $this->load->view('testimonial-edit', $inner, TRUE);
			$this->load->view($this->template['default'], $page);
	   }
	  else {
			$this->Testimonialmodel->updateRecord($testimonial);
			
			$this->session->set_flashdata('SUCCESS', 'testimonial_updated');
			
			redirect("/testimonial/index/");
			exit();
	 	}
	}
	
	function delete($pid) {
		$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Testimonials.</p>'); 
            redirect('dashboard');
		
		//get testimonial detail
		$testimonial = array();
		$testimonial = $this->Testimonialmodel->getDetails($pid);
		if(!$testimonial){
			$this->utility->show404();
            return;
		}
		$this->Testimonialmodel->deletePortfolio($testimonial);
		$this->session->set_flashdata('SUCCESS', 'testimonial_deleted');
		redirect('/testimonial/index/');
		exit();
	}
}
?>