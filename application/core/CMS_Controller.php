<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_Controller extends CI_Controller {
    private $rules;
    private $page;
    private $inner;

    private $module_path = '';
    protected $user_type = false;
    protected $user_name = '';
    protected $user_id = false;
    protected $member_data = false;
    protected $shellFile;
    protected $default;
    protected $socialdashboard;
            
    protected $isLogged;
    function __construct() {
        parent::__construct();
        $this->load->library('encrypt');
        // To load the CI benchmark and memory usage profiler - set 1==1.
        if (1==0) {
            $sections = array(
                'benchmarks' => TRUE, 'memory_usage' => TRUE, 
                'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
                'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => FALSE
            ); 
            $this->output->set_profiler_sections($sections);
            $this->output->enable_profiler(TRUE);
        }

        /*
            IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
            It is used as a global that is accessible via both models and both libraries, 
            without it, flexi auth will not work.
        */
        $this->auth = new stdClass;
        $this->load->library('flexi_auth');

        $this->module_path = realpath(APPPATH . '/views/' . $this->router->directory . '../');
        $this->load->vars(array('CI' => $this));

        $this->isLogged = $this->flexi_auth->is_logged_in();
        if($this->isLogged){
           $this->user_type = $this->flexi_auth->get_user_group();
        }
        else{
         redirect('customer/login');   
        }

        $this->page['CI'] =& get_instance();
        $this->inner['CI'] =& get_instance();

        $this->shellFile = THEME . 'shell';
        $this->template['default'] = THEME . 'templates/default-with-menu';
        $this->template['without_menu'] = THEME . 'templates/default-without-menu';
    }

    function isLogged() {
        //  mcce($this->session, 1);
        if (!$this->isLogged) {
            redirect("/");
        }
        
        return true;
    }    
	
	function setPage($page) {
		$this->page = $page;
	}

	function getPage() {
		return $this->page;
	}

    function baseURL() {
        return base_url();
    }
}

/* End of file cms.php */
/* Location: ./application/libraries/cms.php */