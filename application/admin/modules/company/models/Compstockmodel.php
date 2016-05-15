<?php

class Compstockmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->tbl_name = 'company_stock';
        $this->tbl_pk_col = 'id';
        $this->tbl_alias = 'cpStock'; // company department products
    }
}