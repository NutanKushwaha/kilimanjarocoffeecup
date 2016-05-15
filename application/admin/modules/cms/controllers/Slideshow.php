<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slideshow extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function index($offset = false) {
        if (! $this->flexi_auth->is_privileged('View Slideshows')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Slideshows.</p>'); 
            redirect('dashboard'); 
        }

        $this->load->model('Slideshowmodel');
        
        
        
        
        //Setup pagination
        $perpage = 20;
        $config['base_url'] = base_url() . "slideshow/index/";
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Slideshowmodel->countAll();
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        //list all slide show
        $slideshows = array();
        $slideshows = $this->Slideshowmodel->listAll($offset, $perpage);

        //render view
        $inner = array();
        $inner['mod_menu'] = 'layout/inc-menu-cont';
        $inner['slideshows'] = $slideshows;

        $page = array();
        $page['content'] = $this->load->view('slideshows/slideshow-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    function add() {
        if (! $this->flexi_auth->is_privileged('Insert Slideshows')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Slideshows.</p>'); 
            redirect('dashboard'); 
        }

        $this->load->model('Slideshowmodel');
        
        
        

        //validation
        //validation
        $this->form_validation->set_rules('slideshow_title', 'Slideshow', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['mod_menu'] = 'layout/inc-menu-cont';
            $page['content'] = $this->load->view('slideshows/slideshow-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Slideshowmodel->insertRecord();
            $this->session->set_flashdata('SUCCESS', 'slideshow_added');

            
redirect("cms/slideshow/index", "location");
            exit();
        }
    }

    //function edit slide image
    function edit($sid) {


        if (! $this->flexi_auth->is_privileged('Update Slideshows')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update Slideshows.</p>'); 
            redirect('dashboard'); 
        }

        $this->load->model('Slideshowmodel');
        $this->load->helper('text');
        
        

        //fetch slide show
        $slideshow = array();
        $slideshow = $this->Slideshowmodel->getDetail($sid);
        if (!$slideshow) {
            $this->utility->show404();
            return;
        }


        //validation check
        $this->form_validation->set_rules('slideshow_title', 'Slideshow', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //render view
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['slideshow'] = $slideshow;
            $inner['mod_menu'] = 'layout/inc-menu-cont';
            $page['content'] = $this->load->view('slideshows/slideshow-edit', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Slideshowmodel->updateRecord($slideshow);
            $this->session->set_flashdata('SUCCESS', 'slideshow_updated');

            
redirect("cms/slideshow/index/");
            exit();
        }
    }

    //function to enable record
    function enable($sid) {
        if (! $this->flexi_auth->is_privileged('Active Deactive Slideshows')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Active Deactive Slideshows.</p>'); 
            redirect('dashboard'); 
        }

        $this->load->model('Slideshowmodel');
        
        
    
        
        //fetch slide show
        $slideshow = array();
        $slideshow = $this->Slideshowmodel->getDetail($sid);
        if (!$slideshow) {
            $this->utility->show404();
            return;
        }

        $this->Slideshowmodel->enableRecord($slideshow);

        $this->session->set_flashdata('SUCCESS', 'slideshow_enable');

        
redirect("cms/slideshow/index/");
        exit();
    }

    //function to disable record
    function disable($sid) {
        if (! $this->flexi_auth->is_privileged('Active Deactive Slideshows')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Active Deactive Slideshows.</p>'); 
            redirect('dashboard'); 
        }

        $this->load->model('Slideshowmodel');
        
        
    

        ///fetch slide show
        $slideshow = array();
        $slideshow = $this->Slideshowmodel->getDetail($sid);
        if (!$slideshow) {
            $this->utility->show404();
            return;
        }

        $this->Slideshowmodel->disableRecord($slideshow);

        $this->session->set_flashdata('SUCCESS', 'slideshow_updated');

        redirect("/slideshow/index/");
        exit();
    }

    function delete($sid) {
        if (! $this->flexi_auth->is_privileged('Delete Slideshows')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Slideshows.</p>'); 
            redirect('dashboard'); 
        }

        $this->load->model('Slideshowmodel');
        
        

        ///fetch slide show
        $slideshow = array();
        $slideshow = $this->Slideshowmodel->getDetail($sid);
        if (!$slideshow) {
            $this->utility->show404();
            return;
        }

        $this->Slideshowmodel->deleteRecord($slideshow);

        $this->session->set_flashdata('SUCCESS', 'slideshow_deleted');

        redirect("/slideshow/index/");
        exit();
    }

}