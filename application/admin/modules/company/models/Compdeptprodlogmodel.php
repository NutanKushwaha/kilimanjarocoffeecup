<?php

class Compdeptprodlogmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->tbl_name = 'department_product_log';
        $this->tbl_pk_col = 'id';
        $this->tbl_alias = 'cdpLog'; // company department products Log
    }
}