<?php

class Sagepay_server_model extends CI_Model { 

	// Protected or private properties
	protected $_table;
	
	// Constructor
	public function __construct()
	{
		// Define the sagepay_table name
		$this->_table['sagepay_payments'] = 'sagepay_payments';
	}
	
	// --------------------------------------------------------------------
	
	function add_transaction($data = NULL)
	{
		$transaction['LastUpdated'] = date("Y-m-d H:i:s");
		
		$this->db->insert($this->_table['sagepay_payments'], $data);
					  
		if ($this->db->affected_rows() == '1')
		{
			return $this->db->insert_id();           
		} 
	
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	function update_transaction($data = NULL, $VendorTxCode = NULL)
	{
            
		if ($VendorTxCode == NULL || $data == NULL)
		{
			return FALSE;
		}
		
		$this->db->where('VendorTxCode', $VendorTxCode);
		$this->db->update($this->_table['sagepay_payments'], $data);
 
		if ($this->db->affected_rows() == '1')
		{
                        $udpateData['is_paid'] = 1;
                        $this->db->where('VendorTxCode', $VendorTxCode);
                        $this->db->update('order',$udpateData);
                        
			return TRUE;
		}
 /*
		return FALSE;
                */
            return true;
	}

	// --------------------------------------------------------------------
	
	function get_transaction($VendorTxCode = NULL, $VPSTxId = NULL)
	{
		$this->db->select('*');
		$this->db->where('VendorTxCode', $VendorTxCode);

		if ($VPSTxId) // when we initially need to validate the post we use this which is sent via a post from sagepay
		{
			$this->db->where('VPSTxId', $VPSTxId);
		}

		$query = $this->db->get($this->_table['sagepay_payments']); 

		if ($query->num_rows() == 1)
		{
			return $query->row();
		}

     	return FALSE;
	}

	// --------------------------------------------------------------------
	
	function get_security_key($VendorTxCode = NULL, $VPSTxId = NULL)
	{
		$this->db->select('SecurityKey');
		$this->db->where('VendorTxCode', $VendorTxCode);
		$this->db->where('VPSTxId', $VPSTxId);
		$query = $this->db->get($this->_table['sagepay_payments']); 
		
		if ($query->num_rows() == 1)
		{
			$row = $query->row();
			return $row->SecurityKey;
		}
		
     	return FALSE;
	}
	
	// --------------------------------------------------------------------
        // Custom function created by @Rav on 14 dec 2015
        function orderDetails($onum = NULL)
        {
            /*
            $this->db->select('order.order_id,order.customer_id,order.order_num,order.order_total,aauth_users.email,user_extra_detail.*');
            $this->db->where('order_id',$onum);
            $this->db->from('order');
            $this->db->join('aauth_users','order.customer_id=aauth_users.id','left');
            $this->db->join('user_extra_detail','order.customer_id=user_extra_detail.id','left');
            
            $res = $this->db->get();
            return $res->row_array();
            */
            
        }
        
        function updateOrderDetails($oid = NULL,$vendertx = NULL)
        {
            
            $data['VendorTxCode'] = $vendertx;
            $this->db->where('id',$oid);
            $this->db->update('order',$data);
             
             
        }
        
        // --------------------------------------------------------------------
}
?>