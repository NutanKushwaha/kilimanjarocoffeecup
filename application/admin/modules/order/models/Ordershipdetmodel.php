<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
	CREATE TABLE `bg_order_ship_detail` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	 `order_id` int(10) NOT NULL,
	 `order_email` varchar(255) DEFAULT '',
	 `first_name` varchar(255) DEFAULT '',
	 `last_name` varchar(255) DEFAULT '',
	 `address_1` varchar(255) DEFAULT '',
	 `address_2` varchar(255) DEFAULT '',
	 `city` varchar(255) DEFAULT '',
	 `county` varchar(255) DEFAULT '',
	 `postcode` varchar(255) NOT NULL DEFAULT '',
	 `country` varchar(255) DEFAULT '',
	 `phone` varchar(255) DEFAULT '',
	 `billing_address1` varchar(255) DEFAULT '',
	 `billing_address2` varchar(255) DEFAULT '',
	 `billing_city` varchar(255) DEFAULT '',
	 `billing_county` varchar(255) DEFAULT '',
	 `billing_zipcode` varchar(255) DEFAULT '',
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDb  DEFAULT CHARSET=utf8
*/
class OrderShipDetModel extends Commonmodel {

	private $data;
    function __construct() {
        parent::__construct();        
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'order_ship_detail';
        $this->tbl_pk_col = 'id';

        $this->tbl_alias = 'ordShip';
	}
}