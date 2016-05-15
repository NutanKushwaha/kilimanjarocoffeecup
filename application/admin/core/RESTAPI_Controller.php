<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class RESTAPI_Controller extends REST_Controller {

    function __construct() {
        parent::__construct();
    }
}