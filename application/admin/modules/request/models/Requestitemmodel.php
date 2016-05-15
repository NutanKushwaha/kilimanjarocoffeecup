<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
	CREATE TABLE `bg_request_item` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `req_id` int(10) unsigned NOT NULL,
	 `product_ref` varchar(20) NOT NULL,
	 `req_item_qty` int(10) unsigned NOT NULL DEFAULT '0',
	 `req_item_name` varchar(255) NOT NULL DEFAULT '',
	 `req_item_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
	 `req_item_desc` varchar(255) NOT NULL DEFAULT ' ',
	 `req_item_options` text NOT NULL,
	 PRIMARY KEY (`id`),
	 KEY `req_id` (`req_id`) USING BTREE,
	 CONSTRAINT `req_item_details` FOREIGN KEY (`req_id`) REFERENCES `bg_request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
*/

class RequestitemModel extends Commonmodel {
	private $data;
    function __construct() {
        parent::__construct();        
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'request_item';
        $this->tbl_pk_col = 'id';

        $this->tbl_alias = 'req_item';
	}
}