<?php
/*
	id, code, description, vstyle, vtype, user_style, amount, 
	min_order_value, use_time, active_from, active_to, active, 
	added_on, customers
*/
class Vouchermodel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    //get details of voucher
    function detail($cid) {
        $rs = $this->db->where('id', $cid)->get('voucher');
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //list all voucher
    function listAll($offset = false, $limit = false, $where = []) {
        if ($offset) $this->db->offset($offset);
        if ($limit) $this->db->limit($limit);
		foreach( $where as $where_key => $where_val ){
			$this->db->where($where_key, $where_val);
		}
        $rs = $this->db->order_by( 'code', 'asc' )
						->get('voucher');
        return $rs->result_array();
    }

    //count all voucher
    function countAll($where = []) {
		foreach( $where as $where_key => $where_val ){
			$this->db->where($where_key, $where_val);
		}
        $this->db->from('voucher');
        return $this->db->count_all_results();
    }

    //function to insert record
    function insertRecord() {

        $data = array();
        $specific_cust = $this->input->post('customer', TRUE);
        if( isset( $specific_cust ) && !is_null( $specific_cust ) ){
			$customers = implode(", ", $specific_cust);
		}
        $data['code'] = $this->input->post('code', TRUE);
        $data['vtype'] = $this->input->post('vtype');
        $data['active'] = $this->input->post('active');
        $data['vstyle'] = $this->input->post('vstyle', TRUE);        
        $data['use_time'] = $this->input->post('use_time', TRUE);
        $data['use_time'] = $this->input->post('use_time', TRUE);
        $data['active_to'] = $this->input->post('active_to', TRUE);    
        $data['user_style'] = $this->input->post('user_style', TRUE);        
        $data['active_from'] = $this->input->post('active_from', TRUE);
        $data['description'] = $this->input->post('description', TRUE);
        
        $data['amount'] = floatval( $this->input->post('amount', TRUE) );
        $data['min_order_value'] = floatval( $this->input->post('min_order_value', TRUE) );
        if( $data['user_style'] == 'S' ){
			$data['customers'] = $customers;
		}
		com_changeNull($data[ 'active' ], 0);
		$data['vtype'] 		= 'basket';
        $data['added_on'] 	= com_getDTFormat();
        $this->db->insert('voucher', $data);
    }

    function updateRecord($voucher) {
		$data['active'] = $this->input->post('active', TRUE);
		$data['amount'] = $this->input->post('amount', TRUE);
		$data['vstyle'] = $this->input->post('vstyle', TRUE);
		$data['use_time'] = $this->input->post('use_time', TRUE);
		$data['active_to'] = $this->input->post('active_to', TRUE);
		$data['active_from'] = $this->input->post('active_from', TRUE);
        $data['description'] = $this->input->post('description', TRUE);
        $data['min_order_value'] = $this->input->post('min_order_value', TRUE);
        com_changeNull($data[ 'active' ], 0);        
        $this->db->where('id', $voucher['id'])
				->update('voucher', $data);
    }

    //function to enable record
    function enableRecord($voucher) {
        $data = array();
        $data['active'] = 1;
        $this->db->where('id', $voucher['id'])
				->update('voucher', $data);
    }

    //function to disable record
    function disableRecord($voucher) {
        $data = array();
        $data['active'] = 0;
        $this->db->where('id', $voucher['id']);
        $this->db->update('voucher', $data);
    }

    //delete record
    function deleteRecord($voucher) {
        $this->db->where('id', $voucher['id'])
				->delete('voucher');
    }

}

?>
