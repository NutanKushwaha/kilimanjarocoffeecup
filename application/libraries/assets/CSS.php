<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class CSS extends Asset {

	private $resource = false;
	private $is_minified = true;
	private $is_file = true;

	function __construct($resource, $is_file = true) {
		$this->resource = $resource;
		$this->is_file = $is_file;
	}

	function setMinified($status) {
		$this->is_minified = $status;
	}

	function isMinified() {
		return $this->is_minified;
	}

	function isFile() {
		return $this->is_file;
	}

	function render() {
		$CI = & get_instance();
		$base_url = $CI->http->baseURL();
		
		if($this->isFile()) {
			if($this->isMinified()) {
				$resource_arr = $this->resource;
				$resource_arr = array_unique($resource_arr);
				$css = join(",", $resource_arr);
				echo '<link type="text/css" rel="stylesheet" href="' . $base_url . 'min/?f=' . $css . '" />'."\r\n";
			}else {
				echo '<link type="text/css" rel="stylesheet" href="' . $base_url . $this->resource . '" />'."\r\n";
			}
		}else {
			echo '<style type="text/css">'."\r\n";
			echo '<!--'."\r\n";
			echo $this->resource."\r\n" ;
			echo '-->'."\r\n";
			echo '</style>'."\r\n";
		}
	}

}

?>