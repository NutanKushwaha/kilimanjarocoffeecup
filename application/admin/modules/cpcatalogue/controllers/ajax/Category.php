<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category extends Adminajax_Controller {

	function __construct() {
		parent::__construct();
		$this->is_admin_protected = TRUE;
	}

	function updateSortOrder(){
		$sort_data = $this->input->post('page', true);
		foreach($sort_data as $key=>$val) {
			$update = array();
			$update['category_sort_order'] = $key+1;
			$this->db->where('category_id', $val);
			$this->db->update('category', $update);
		}
		//echo "Done";
        print_r($_POST);
	}
}
?>