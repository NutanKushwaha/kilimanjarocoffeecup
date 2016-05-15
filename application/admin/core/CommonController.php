<?php

class CommonController extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public $table;
    public $inner = array();
    public $index_view;
    public $page_view;
    public $controllername;

    private function loadView() {
        $inner = array();
        $page = array();
        $inner = $this->inner;        
        $inner['rows'] = $this->commonmodel->getAll($this->table);
        $page = array();
        $page['content'] = $this->load->view($this->index_view, $inner, TRUE);
        $this->load->view($this->page_view, $page);
    }

    function index() {
        $this->loadView();
    }
}
