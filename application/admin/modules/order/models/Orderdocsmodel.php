<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
	CREATE TABLE `bg_order_docs` (
	 `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
	 `order_num` varchar(255) NOT NULL,
	 `order_status` varchar(255) NOT NULL DEFAULT '',
	 `doc_name` varchar(255) NOT NULL DEFAULT '',
	 `doc_info` varchar(255) NOT NULL DEFAULT '',
	 `track_num` varchar(255) NOT NULL DEFAULT '',
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1
*/

class OrderdocsModel extends Commonmodel {
	
	private $data;
    function __construct() {
        parent::__construct();        
        $this->set_attributes();
	}

	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'order_docs';
        $this->tbl_pk_col = 'id';

        $this->tbl_alias = 'ord_docs';
	}
}
