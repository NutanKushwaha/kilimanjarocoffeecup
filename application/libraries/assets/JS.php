<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class JS extends Asset {

	private $resource = false;
	private $is_minified = true;
	private $is_file = true;
	private $in_head = false;

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

	function setInHead($header) {
		$this->in_head = $header;
	}

	function inHead() {
		return $this->in_head;
	}

	function render() {
		$CI = & get_instance();
		$base_url = $CI->http->baseURL();

		if($this->isFile()) {
			if($this->isMinified()) {
				$resource_arr = $this->resource;
				$resource_arr = array_unique($resource_arr);
				$js = join(",", $resource_arr);
				echo '<script type="text/javascript" src="' . $base_url . 'min/?f=' . $js . '"></script>'."\r\n";
			}else {
				echo '<script type="text/javascript" src="' . $base_url . $this->resource . '"></script>'."\r\n";
			}
		}else {
			echo '<script type="text/javascript">'."\r\n";
			echo '//<![CDATA['."\r\n";
			echo $this->resource."\r\n" ;
			echo '//]]>'."\r\n";
			echo '</script>'."\r\n";
		}
	}

}

?>