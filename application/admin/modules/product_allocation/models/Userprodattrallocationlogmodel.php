<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
	CREATE TABLE `bg_user_product_allocation_log` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `user_id` int(10) unsigned NOT NULL,
	 `product_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	 `days_limit` tinyint(1) unsigned NOT NULL DEFAULT '1',
	 `quantity` int(11) NOT NULL DEFAULT '0',
	 `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	 `attributes_detail` text COLLATE utf8_unicode_ci COMMENT 'hold all attribute detail',
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
*/
class Userprodattrallocationlogmodel extends Commonmodel{

	function __construct()
	{
		# code...
        parent::__construct();
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'user_product_allocation_log';
        $this->tbl_alias = 'upalg';
        $this->tbl_pk_col = 'id';        
	}	
}