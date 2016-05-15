<?php

class Compstoremodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->tbl_name = 'company_store';
        $this->tbl_pk_col = 'id';
        $this->tbl_alias = 'cpStore'; // company store
    }

    function get_list( $opt = [] ){
		$limit = 0;
		$offset = 0;
    	extract( $opt );
    	$param = [];
    	$param[ 'select' ] 	=	'store_name, is_active, company_code, id';
    	$param[ 'where' ][ 'company_code' ] =	$company_code;
    	if( $limit ){
			$param[ 'limit' ][ 'limit' ] 	=	$limit;
			$param[ 'limit' ][ 'offset' ] 	=	$offset;
		}
    	return $this->get_all( $param );
    }
    
    function count_list( $opt = [] ){
    	$param = [];
    	extract( $opt );    	
    	$param[ 'select' ] 	=	'count(store_name) as ttl';
    	$param[ 'result' ] 	=	'row';
    	$param[ 'where' ][ 'company_code' ] =	$company_code;
    	return $this->get_all( $param );
    }    

	function addStore($opt){
		$return_bool = false;
		$this->validation_rules = $this->form_rules;
		if($this->validate()) {
			extract( $opt );
			$param = [];
			$param[ 'company_code' ] 	= $company_code;
			$param[ 'is_active' ] 		= $this->input->post('is_active');
			$param[ 'store_name' ] 		= $this->input->post('store_name');
			com_changeNull($param[ 'is_active' ], 0);
			$this->insert( $param );
			$return_bool = true;
		}
		return $return_bool;
	}

    function updateStore( $opt ){
        $return_bool = false;
        $this->validation_rules = $this->form_rules;
        if($this->validate()) {
            extract( $opt );
            $param = [];
            $param[ 'where' ][ 'id' ]    = $store_id;
            $param[ 'where' ][ 'company_code' ]    = $company_code;
            $param[ 'data' ][ 'store_name' ]      = $this->input->post('store_name');            
            $this->update_record( $param );
            $return_bool = true;
        }
        return $return_bool;
    }

	function getDetail( $opt ){
		extract( $opt );
    	$param[ 'result' ] 	=	'row';
    	$param[ 'select' ] 	=	'store_name, is_active, company_code, id';
    	$param[ 'where' ][ 'company_code' ] =	$company_code;
    	$param[ 'where' ][ 'id' ] 			=	$store_id;
    	return $this->get_all( $param );
	}

    function enableRecord( $storeDet = [] ){
        $param = [];
        $param[ 'data' ][ 'is_active' ] = '1';
        $param[ 'where' ][ 'id' ] = $storeDet[ 'id' ];
        $this->update_record( $param );
    }

    function disableRecord( $storeDet = [] ){
        $param = [];
        $param[ 'data' ][ 'is_active' ] = '0';
        $param[ 'where' ][ 'id' ] = $storeDet[ 'id' ];
        $this->update_record( $param );
    }

    function deleteRecord( $storeDet = [] ){
        $param = [];        
        $param[ 'where' ][ 'id' ] = $storeDet[ 'id' ];
        $this->delete_record( $param );
    }
}
