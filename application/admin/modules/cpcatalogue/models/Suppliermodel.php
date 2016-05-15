<?php

class Suppliermodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDetails($sid) {
        $this->db->from('supplier');
        $this->db->where('supplier_id', intval($sid));
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            return $rs->row_array();
        }
        return FALSE;
    }

    function listAll() {
        $rs = $this->db->get('supplier');
        return $rs->result_array();
    }

    function listAllWithBrands() {
        return $this->db->select('jti.id,jti.brand_name,spl.supplier_name,spl.supplier_id')
						->from('supplier as spl')
						->join('supplier_brand as jti', 'jti.supplier_id=spl.supplier_id' , 'left')
						->get()
						->result_array();
    }

    function insertRecord() {
        $data = array();
        $data['supplier_name'] = $this->input->post('supplier_name', TRUE);

        return $this->db->insert('supplier', $data);
    }

    function updateRecord($sid) {
        $data = array();
        $data['supplier_name'] = $this->input->post('supplier_name', TRUE);

        $this->db->where('supplier_id', intval($sid));
        return $this->db->update('supplier', $data);
    }

    function deleteRecord($sid) {
        $this->db->where('supplier_id', intval($sid));
        $this->db->delete('supplier');
    }

    function get_supplier_brand_count(  $supplier_id ){
        return $this->db->select( 'count(id) as ttl' )
                ->from('supplier_brand')
                ->where( 'supplier_id', $supplier_id )
                ->get()
                ->row_array();
    }

    function get_supplier_brand_list( $supplier_id, $offset, $limit){
        return $this->db->from('supplier_brand')
                        ->where( 'supplier_id', $supplier_id )
                        ->limit($limit , $offset)
                        ->get()                        
                        ->result_array();
    }
    
    function count_brands($option = []){
		extract( $option );
		return $this->db->select('count(id) as ttl')
						->from('supplier_brand')
                        ->where( 'id != ', $brand_id )
                        ->where( 'supplier_id', $supplier_id )
                        ->where( 'brand_name', $brand_name )
                        ->get()
                        ->row_array();
	}
	
	function insert_brand($option = []){
		extract( $option );
		$data = [];
		$data[ 'supplier_id' ] 	= $supplier_id;
		$data[ 'brand_name' ] 	= $this->input->post( 'brand_name' );
		$this->db->insert('supplier_brand', $data);		
	}
	
	function get_brand_Details( $opt ){
		extract( $opt );
		return $this->db->from('supplier_brand')
                        ->where( 'id', $brand_id )
                        ->where( 'supplier_id', $supplier_id )
                        ->get()
                        ->row_array();		
	}
	
	function delete_brand( $brand_det ){
		$data  = [];
		$data[ 'id ' ]  = $brand_det[ 'id' ];
		$this->db->delete('supplier_brand',  $data);		
	}
	
	function update_brand( $opts ){
		extract( $opts );		
		$data = [];
		$data[ 'brand_name' ] = $brand_name;
		$this->db->where('id', $brand_id)
				->where('supplier_id', $supplier_id)
				->update('supplier_brand', $data);
	}
}
