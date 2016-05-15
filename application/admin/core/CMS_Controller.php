<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CMS_Controller extends CI_Controller {

    private $rules;
    private $page;
    private $inner;

    private $module_path = '';
    public $user_type = false;
    protected $user_name = '';
    protected $user_id = false;
    protected $member_data = false;
    protected $shellFile;
    protected $default;
    protected $socialdashboard;
            
    protected $isLogged;
    function __construct() {
        parent::__construct();

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

    function loadHead() {
        $file_name = $this->router->class . '_' . $this->router->method;
        $file_path = $this->module_path . "/views/headers/$file_name.php";
        if (file_exists($file_path)) {
            return $this->load->view("headers/" . $file_name, '', true);
        }
        return '';
    }

    function getMeta() {
        $file_name = $this->router->class . '_' . $this->router->method;
        $file_path = $this->module_path . "/views/meta/$file_name.php";
        if (file_exists($file_path)) {
            return $this->load->view("meta/" . $file_name, '', true);
        }

        return '';
    }

    function getCSS() {
        //Minified
        global $MCC_MIN_CSS_ARR;
        $MCC_MIN_CSS_ARR = array_unique($MCC_MIN_CSS_ARR);
        if (count($MCC_MIN_CSS_ARR) > 0) {
            $css = join(",", $MCC_MIN_CSS_ARR);
            //echo '<link type="text/css" rel="stylesheet" href="'.$this->baseURL().'min/?f='.$css.'" />
            //';
            foreach ($MCC_MIN_CSS_ARR as $css) {
                echo '<link type="text/css" rel="stylesheet" href="' . $this->baseURL() . $css . '" />';
            }
        }

        global $MCC_CSS_ARR;
        if(is_array($MCC_CSS_ARR)){
            $MCC_CSS_ARR = array_unique($MCC_CSS_ARR);
            if (count($MCC_CSS_ARR) > 0) {
                foreach ($MCC_CSS_ARR as $css) {
                    echo '<link type="text/css" rel="stylesheet" href="' . $this->baseURL() . $css . '" />
    				';
                }
            }
        }
    }

    function getJS() {
        //Minified
        global $MCC_MIN_JS_ARR;
        $js_arr = array();
        foreach ($MCC_MIN_JS_ARR as $temp) {
            if (is_array($temp) && count($temp) == 2) {
                if ($this->CI->agent->is_mobile) {
                    $js_arr[] = $temp[1];
                } else {
                    $js_arr[] = $temp[0];
                }
            } else {
                $js_arr[] = $temp;
            }
        }
        $js_arr = array_unique($js_arr);
        if (count($js_arr) > 0) {
            /* $js = join(",", $js_arr);
              echo '<script type="text/javascript" src="'.$this->baseURL().'min/?f='.$js.'"></script>
              '; */

            foreach ($js_arr as $js) {
                echo '<script type="text/javascript" src="' . $this->baseURL() . $js . '"></script>';
            }
        }

        global $MCC_JS_ARR;
        if($MCC_JS_ARR){
            $js_arr = array();
            foreach ($MCC_JS_ARR as $temp) {
                if (is_array($temp) && count($temp) == 2) {
                    if ($this->CI->agent->is_mobile) {
                        $js_arr[] = $temp[1];
                    } else {
                        $js_arr[] = $temp[0];
                    }
                } else {
                    $js_arr[] = $temp;
                }
            }
        }
        $js_arr = array_unique($js_arr);
        $js_arr = array_unique($js_arr);
        if (count($js_arr) > 0) {
            foreach ($js_arr as $js) {
                echo '<script type="text/javascript" src="' . $this->baseURL() . $js . '"></script>';
            }
        }
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

    function baseURLNonSSL() {
        $url = $this->getBaseURL();
        return str_replace('https://', 'http://', $url);
    }
    
    function editor($width = '', $path = '../../js/ckfinder') {
        //Loading Library For Ckeditor
        $this->load->library('Ckeditor');
        $this->load->library('CKFinder');
        //configure base path of ckeditor folder 
        $this->ckeditor->basePath = base_url() . 'js/ckeditor/';

        $this->ckeditor->config['toolbar'] = 'Full';
        $this->ckeditor->config['language'] = 'en';
        $this->ckeditor->config['width'] = $width;
        //configure ckfinder with ckeditor config 
        $this->ckfinder->SetupCKEditor($this->ckeditor, $path);
    }
}

?>
