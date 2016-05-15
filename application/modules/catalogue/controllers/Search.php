<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search extends Front_Controller {
    function __construct() {
        parent::__construct();
    }
    function index()
    {        
        $limit = 12;
        $cat = $this->input->get('cat');
        $q = $this->input->get('q');
        $this->load->model('Searchmodel');
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $config['total_rows'] = $this->Searchmodel->countSearchResult($q,$cat);        
        $config['base_url'] = base_url() . "catalogue/search/?cat=".$cat.'&q='.$q;
        $config['page_query_string'] = TRUE;
        $config['per_page'] = $limit;
        $this->pagination->initialize($config);        
        $str = $this->input->get('q');
        $inner = array();
        $shell = array();
        $products = $this->Searchmodel->SearchResult($offset,$limit);        
        $inner['pagination'] = $this->pagination->create_links();
        $inner['products'] = $products;
        if($str)
        {
        //e($products);
        }
        $shell['contents'] = $this->load->view("search", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);        
    }
}
