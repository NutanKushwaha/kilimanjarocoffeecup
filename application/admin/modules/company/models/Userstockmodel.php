<?php

class Userstockmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->tbl_name = 'user_stock';
        $this->tbl_pk_col = 'id';
        $this->tbl_alias = 'usrStock'; // company department products
    }

    function getProdIssueLog( $opt ){
    	extract( $opt );        	
    	$param = [];
    	$param[ 'select' ] = 'request_ref, user_id, stock_qty, uacc_username, bonus_given, issue_date_time';
		$param['join'][] = 	['tbl' => $this->db->dbprefix('user_accounts'). ' as jt1',
                            'cond' => "jt1.uacc_id=user_id",
                            'type' => 'left'
                          	];    	
    	$param[ 'where' ][ 'product_code' ] = $product_code;
    	$param[ 'where' ][ 'company_code' ] = $company_code;
    	return $this->get_all( $param );
    }

    function issue_user_bonus_stock( $params ){
        extract( $params );
        $issue_time = '';
        $user_product_bonus_distribution = [];
        $store_id = $this->input->post('company_store');
        com_changeNull($store_id, 0);
        /* put all post qtys in one variable */
        $posted_quantity = $this->input->post('quantity');
        /* dept keys which are posted */
        $deptKeys = array_keys( $posted_quantity );        
        /* loop on department keys */
        foreach ($deptKeys as $deptKeysIndex => $deptKey) {
            /* assign specific product user comb to deptUserQtys */
            $deptUserQtys = $posted_quantity[$deptKey];
            /* loop on dept deptUserQtys */
            foreach($deptUserQtys as $deptUserDayProdSku => $deptUserQtyUserDet){                
                $userIndex = 0;
                /* loop on dept deptUserQtyUserDet */
                foreach ($deptUserQtyUserDet as $userId => $userQty) {
                    if( $userQty ){
                        /* check is custome key exist in db result set */                    
                        $user_product_bonus_distribution[ ] = [
                                                            'user_id'           => $userId,
                                                            'request_ref'       => "",
                                                            'company_code'      => $company_code,
                                                            'product_code'      => $deptUserDayProdSku,
                                                            'store_id'      	=> $store_id,
                                                            'stock_qty'         => $userQty,
                                                            'issue_date_time'   => &$issue_time,
                                                            'bonus_given'       => '1',
                        ];
                    }
                }
            }
        }

        if( $user_product_bonus_distribution ) {
            $issue_time = com_getDTFormat( 'mdatetime' );
            $this->insert_bulk( $user_product_bonus_distribution );
        }
    }

    function get_count( $opt = [] ){
        $param = [];
        extract( $opt );
        $param[ 'select' ] = 'count(user_id) as ttl';
        $param[ 'result' ] = 'row';
        $param[ 'where'  ][ 'user_id' ] = $user_id;
        $param[ 'where'  ][ 'bonus_given' ] = '1';
        return $this->get_all( $param );
    }

    function get_list( $opt = [] ){
        $param = [];
        extract( $opt );
        $param[ 'select' ] = 'product_code, stock_qty, issue_date_time, product_name';
        $param[ 'join' ][ ] = [
                                'tbl' => $this->db->dbprefix('product').' as jt1', 
                                'cond' => 'jt1.product_sku=product_code', 
                                'type' => 'inner',
                                'pass_prefix' => true
                            ];
        $param[ 'limit' ][ 'offset' ]   = $offset;
        $param[ 'limit' ][ 'limit'  ]    = $limit;
        $param[ 'where'  ][ 'user_id' ] = $user_id;
        $param[ 'where'  ][ 'bonus_given' ] = '1';
        return $this->get_all( $param );
    }
}
