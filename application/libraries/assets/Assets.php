<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once "Asset.php";
require_once "JS.php";
require_once "CSS.php";

class Assets {

    private $js_resources = array();
    private $css_resources = array();
    private $output = '';

    function __construct() {
        log_message('debug', "Assets Class Initialized");
    }

    function loadFromFile($view, $params = array()) {
        $CI = & get_instance();

        $this->output .= $CI->load->view($view, $params, true);
    }

    function _addJS($order, $resource, $is_file, $minify, $header) {
        $order = intval($order);
        $resources = array();
        if (array_key_exists($order, $this->js_resources)) {
            $resources = $this->js_resources[$order];
        }

        $js = new JS($resource, $is_file);
        $js->setMinified($minify);
        $js->setInHead($header);

        $resources[] = $js;

        $this->js_resources[$order] = $resources;
    }

    function addHeadJS($resource, $order = 100) {
        $this->_addJS($order, $resource, true, false, true);
    }

    function addHeadJSMinified($resource, $order = 100) {
        if (!is_array($resource)) {
            $resource = array($resource);
        }
        $this->_addJS($order, $resource, true, true, true);
    }

    function addHeadJSInline($resource, $order = 100) {
        $this->_addJS($order, $resource, false, false, true);
    }

    function addFooterJS($resource, $order = 100) {
        $this->_addJS($order, $resource, true, false, false);
    }

    function addFooterJSMinified($resource, $order = 100) {
        if (!is_array($resource)) {
            $resource = array($resource);
        }
        $this->_addJS($order, $resource, true, true, false);
    }

    function addFooterJSInline($resource, $order = 100) {
        $this->_addJS($order, $resource, false, false, false);
    }

    function _addCSS($order, $resource, $is_file, $minify) {
        $order = intval($order);
        $resources = array();
        if (array_key_exists($order, $this->css_resources)) {
            $resources = $this->css_resources[$order];
        }

        $css = new CSS($resource, $is_file);
        $css->setMinified($minify);

        $resources[] = $css;

        $this->css_resources[$order] = $resources;
    }
    function addJSscript(){
        
    }

    function addCSS($resource, $order = 100) {
        $this->_addCSS($order, $resource, true, false);
    }

    function addCSSMinified($resource, $order = 100) {
        if (!is_array($resource)) {
            $resource = array($resource);
        }
        $this->_addCSS($order, $resource, true, true);
    }

    function addCSSInline($resource, $order = 100) {
        $this->_addCSS($order, $resource, false, false);
    }

    function renderHead() {
        $CI = & get_instance();
        $base_url = $CI->http->baseURL();

        $css_resources = $this->css_resources;
        ksort($css_resources);

        foreach ($css_resources as $css_arr) {
            foreach ($css_arr as $css) {
                $css->render();
            }
        }

        $js_resources = $this->js_resources;
        ksort($js_resources);

        foreach ($js_resources as $js_arr) {
            foreach ($js_arr as $js) {
                if ($js->inHead()) {
                    $js->render();
                }
            }
        }

        return $this->output;
    }

    function renderFooter() {
        $CI = & get_instance();
        $base_url = $CI->http->baseURL();

        $js_resources = $this->js_resources;
        ksort($js_resources);

        foreach ($js_resources as $js_arr) {
            foreach ($js_arr as $js) {
                if (!$js->inHead()) {
                    $js->render();
                }
            }
        }
    }

}

?>