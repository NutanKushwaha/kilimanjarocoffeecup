<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Security extends CI_Security {

	public function init_csrf() {
		$this->_csrf_set_hash();
	}

}

?>