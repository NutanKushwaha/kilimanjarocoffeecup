<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
	CREATE TABLE `bg_order_item` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `order_id` int(10) unsigned NOT NULL,
	 `product_ref` varchar(20) NOT NULL,
	 `order_item_name` varchar(255) NOT NULL DEFAULT '',
	 `order_item_desc` varchar(255) NOT NULL DEFAULT '',
	 `order_item_options` text,
	 `order_item_qty` int(10) unsigned NOT NULL DEFAULT '0',
	 `order_item_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
	 PRIMARY KEY (`id`),
	 KEY `order_id` (`order_id`) USING BTREE
	) ENGINE=Innodb  DEFAULT CHARSET=utf8
*/

class OrderitemModel extends Commonmodel {
	private $data;
    function __construct() {
        parent::__construct();        
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'order_item';
        $this->tbl_pk_col = 'id';

        $this->tbl_alias = 'ord_item';
	}
}