<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
	CREATE TABLE `bg_user_product_allocation_attributes_details` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `policy_ref` bigint(20) unsigned NOT NULL,
	 `is_user_related` tinyint(1) unsigned default 0,
	 `attribute_id` int(11) unsigned NOT NULL,
	 `attribute_value` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'it holds value for system attributes and ids for other attributes',
	 PRIMARY KEY (`id`),
	 KEY `policy_ref` (`policy_ref`),
	 CONSTRAINT `user_product_policy_key` FOREIGN KEY (`policy_ref`) REFERENCES `bg_user_product_allocation` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='contains user product allocation product attribute details'
*/
class Userprodattrallocationmodel extends Commonmodel{

	function __construct()
	{
		# code...
        parent::__construct();
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'user_product_allocation_attributes_details';
        $this->tbl_alias = 'upaad';
        $this->tbl_pk_col = 'id';        
	}	
}