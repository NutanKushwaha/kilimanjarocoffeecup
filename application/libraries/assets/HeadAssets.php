<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require "JS.php";
class HeadAssets {

	private $js_resources = array();
	private $css_resources = array();

	private $h_js_scripts = array();
	private $h_js_resources = array();
	private $h_js_resources_min = array();
	private $f_js_resources_min = array();
	private $f_js_resources = array();
	private $h_css_styles = array();
	private $h_css_resources = array();
	private $h_css_resources_min = array();

	function __construct() {
		log_message('debug', "Assets Class Initialized");
	}

	function _addJS($order, $resource, $is_file, $minify, $header) {
		$order = intval($order);
		$resources = array();
		if(array_key_exists($order, $this->js_resources)) {
			$resources = $this->js_resources[$order];
		}

		$js = new JS($resource, $is_file);
		$js->setMinified($minify);
		$js->setInHead($header);

		$resources[] = $js;

		$this->js_resources[$order] = $resources;
	}

	function addJS($resource, $order = 100) {
		$this->_addJS($order, $resource, true, false, true);
	}

	function addJSMinified($resource, $order = 100) {
		if(!is_array($resource)) {
			$resource = array($resource);
		}
		$this->_addJS($order, $resource, true, true, true);
	}

	function addJSInline($resource, $order = 100) {
		$this->_addJS($order, $resource, false, false, true);
	}

	function addCSS($order, $resource, $is_file = true, $minify = true) {
		if (!$is_file) {
			$this->h_css_styles[] = $resource;
			return;
		}

		if ($is_file && $minify) {
			$this->h_css_resources_min[] = $resource;
			return;
		}

		$this->h_css_resources[] = $resource;
	}

	function renderHead() {
		$CI = & get_instance();
		$base_url = $CI->http->baseURL();
		
		//Minified
		$MCC_MIN_CSS_ARR = array_unique($this->h_css_resources_min);
		if (count($MCC_MIN_CSS_ARR) > 0) {
			$css = join(",", $MCC_MIN_CSS_ARR);
			echo '<link type="text/css" rel="stylesheet" href="' . $base_url . 'min/?f=' . $css . '" />
			';
		}

		//Normal CSS Files
		$MCC_CSS_ARR = array_unique($this->h_css_resources);
		foreach ($MCC_CSS_ARR as $css) {
			echo '<link type="text/css" rel="stylesheet" href="' . $base_url . $css . '" />
				';
		}

		//Inline CSS STyles
		$MCC_CSS_ARR = $this->h_css_styles;
		foreach ($MCC_CSS_ARR as $css) {
			echo "$css\r\n" ;
		}



		//Minified JS
		$MCC_MIN_JS_ARR = array_unique($this->h_js_resources_min);
		if (count($MCC_MIN_JS_ARR) > 0) {
			$js = join(",", $MCC_MIN_JS_ARR);
			echo '<script type="text/javascript" src="' . $base_url . 'min/?f=' . $js . '"></script>
			';
		}

		//Normal JS Files
		$MCC_JS_ARR = array_unique($this->h_js_resources);
		foreach ($MCC_JS_ARR as $js) {
			echo '<script type="text/javascript" src="' . $base_url . $js . '"></script>
				';
		}

		//Inline JS Scripts
		$MCC_JS_ARR = $this->h_js_scripts;
		foreach ($MCC_JS_ARR as $js) {
			echo "$js\r\n" ;
		}
	}

	function renderFooter() {
		$CI = & get_instance();
		$base_url = $CI->http->baseURL();

		//Minified JS
		$MCC_MIN_JS_ARR = array_unique($this->f_js_resources_min);
		if (count($MCC_MIN_JS_ARR) > 0) {
			$js = join(",", $MCC_MIN_JS_ARR);
			echo '<script type="text/javascript" src="' . $base_url . 'min/?f=' . $js . '"></script>
			';
		}
		
		//Normal JS Files
		$MCC_JS_ARR = array_unique($this->f_js_resources);
		foreach ($MCC_JS_ARR as $js) {
			echo '<script type="text/javascript" src="' . $base_url . $js . '"></script>
				';
		}
	}
}

?>