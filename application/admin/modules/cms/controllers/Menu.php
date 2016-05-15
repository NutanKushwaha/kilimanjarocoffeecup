<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    //**************************validation start***************************
    //function valid page block for add
    function valid_block($str) {
        $this->db->where('menu_alias', $str);
        $this->db->from('menu');
        $block_count = $this->db->count_all_results();
        if ($block_count != 0) {
            $this->form_validation->set_message('valid_menu', 'Menu Alias is already being used!');
            return false;
        }
        return true;
    }

    //function valid page block for edit
    function validBlock($str) {
        $this->db->where('menu_alias', $str);
        $this->db->where('block_id !=', $this->input->post('menu_id', true));
        $this->db->from('menu');
        $block_count = $this->db->count_all_results();
        if ($block_count != 0) {
            $this->form_validation->set_message('validMenu', 'Menu Alias is already being used!');
            return false;
        }
        return true;
    }

    //*************************************validation end**********************************************

    function index($offset = 0) {
        if (! $this->flexi_auth->is_privileged('View Site Menu')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Site Menu.</p>'); redirect('dashboard'); }

        $this->load->model('Menumodel');
        
        
        

//        if (!($this->aauth->isAdmin() || $this->aauth->isFrsUser())) {
//            $this->utility->accessDenied();
//            return;
//        }

        //Setup pagination
        $perpage = 20;
        $config['base_url'] = base_url() . "menu/index/";
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->Menumodel->countAll();
        $config['per_page'] = $perpage;
        $this->pagination->initialize($config);

        //list all block
        $menu = array();
        $menu = $this->Menumodel->listAll($offset, $perpage);

        //render view
        $inner = array();
        $inner['menu'] = $menu;
        $inner['pagination'] = $this->pagination->create_links();

        $page = array();
        $inner['mod_menu'] = 'layout/inc-menu-cont';
        $page['content'] = $this->load->view('menu-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    //add menu
    function add() {
        if (! $this->flexi_auth->is_privileged('Insert Site Menu')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Site Menu.</p>'); redirect('dashboard'); }

        $this->load->model('Menumodel');
        
                

        //Form Validations
        $this->form_validation->set_rules('menu_name', 'Menu Name', 'trim|required');
        $this->form_validation->set_rules('menu_alias', 'Menu Alias', 'trim|strtolower');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $page['content'] = $this->load->view('menu-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Menumodel->insertRecord();

            $this->session->set_flashdata('SUCCESS', 'menu_added');

            redirect('cms/menu/index/', 'location');
            exit();
        }
    }

    //function to edit record
    function edit($mid) {
        if (! $this->flexi_auth->is_privileged('Update Site Menu')){ 
            $this->session->set_flashdata('message', '<p class="error_msg">You do  not have privileges to view Update Site Menu.</p>'); 
            redirect('dashboard'); 
        }
                
        $this->load->model('Menumodel');

        //Get Menu Details
        $menu = array();
        $menu = $this->Menumodel->detail($mid);
        //print_r($menu); exit();
        if (!$menu) {
            $this->utility->show404();
            return;
        }
        //Form Validations
        $this->form_validation->set_rules('manu_name', 'Menu Name', 'trim');
        $this->form_validation->set_rules('menu_alias', 'Menu Alias', 'trim|strtolower');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['menu'] = $menu;
            $inner['mod_menu'] = 'layout/inc-menu-cont';
            $page['content'] = $this->load->view('menu-edit', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Menumodel->updateRecord($menu);

            $this->session->set_flashdata('SUCCESS', 'menu_updated');

            redirect('cms/menu/index/', 'location');
            exit();
        }
    }

    //function to enable record
    function enable($mid = FALSE) {
        
        
        $this->load->model('Menumodel');

        //Get Menu Details
        $menu_details = array();
        $menu_details = $this->Menumodel->detail($mid);
        if (!$menu_details) {
            $this->utility->show404();
            return;
        }

        $this->Menumodel->enableRecord($menu_details);

        $this->session->set_flashdata('SUCCESS', 'menu_updated');

        redirect('cms/menu/index/', 'location');
        exit();
    }

    //function to disable record
    function disable($pid) {
        
        
        $this->load->model('Menumodel');

        //Get Menu Details
        $menu_details = array();
        $menu_details = $this->Menumodel->detail($pid);
        if (!$menu_details) {
            $this->utility->show404();
            return;
        }

        $this->Menumodel->disableRecord($menu_details);

        $this->session->set_flashdata('SUCCESS', 'menu_updated');

        redirect('cms/menu/index/', 'location');
        exit();
    }

    //function to delete record
    function delete($mid) {
        if (! $this->flexi_auth->is_privileged('Delete Site Menu')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Site Menu.</p>'); redirect('dashboard'); }

        $this->load->model('Menumodel');

//        if (!($this->aauth->isAdmin() || $this->aauth->isFrsUser())) {
//            $this->utility->accessDenied();
//            return;
//        }


        //Get Menu Details
        $menu = array();
        $menu = $this->Menumodel->detail($mid);
        //print_r($menu); exit();
        if (!$menu) {
            $this->utility->show404();
            return;
        }

        $this->Menumodel->deleteRecord($menu);

        $this->session->set_flashdata('SUCCESS', 'menu_deleted');

        redirect("cms/menu/index/");
        exit();
    }

}

?>