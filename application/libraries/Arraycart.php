<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arraycart {

	var $CI;
	var $cart_contents	= array();


	public function __construct() {
		$this->CI =& get_instance();
		$cart_contents = $this->CI->cart->contents();
		foreach($cart_contents as $item) {
			for($i=1;$i<=$item['qty'];$i++) {
				$this->cart_contents[] = $item;
			}
		}
	}
	
	function deductQnty($pid) {
		foreach($this->cart_contents as $key=>$item) {
			if($pid==$item['id']) {
				unset($this->cart_contents[$key]);
				break;
			}
		}
	}
}
?>