<?php

class Dealermodel extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function getActiveDealer(){
		$this->db->select('*');
		$this->db->from('dealer');
		$this->db->join('active_dealer', 'active_dealer.DealerId = dealer.DealerId');
		$query = $this->db->get();
		//print_r ($query->num_rows()); exit();
         return $query->result_array();
	}
	
	
	//list all collection
	function listAll($offset = FALSE, $limit = FALSE) {
		if($offset) $this->db->offset($offset);
		if($limit) $this->db->offset($limit);
		
		$query = $this->db->get('dealer');
        return $query->result_array();
	}
	
	function updateActiveDealer(){
		//truncate Active_dealer
		$this->db->truncate('active_dealer'); 
			
		//update the Active_dealer table 
		foreach($this->input->post('dealer_id', true) as $item){
			$data = array();
			$data['DealerID'] = $item;
			$this->db->insert('active_dealer',$data);
		}
		return;
	}
}
?>