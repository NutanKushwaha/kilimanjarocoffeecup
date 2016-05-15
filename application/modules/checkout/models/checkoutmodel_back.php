<?php

class Checkoutmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function addOrder($customer,$order_total) {

        extract($this->Cartmodel->variables());

        $this->load->helper('string');
        $order = array();
        $order['customer_id'] = $customer['customer_id'];
        $order['cart'] = base64_encode(serialize($this->cart->contents()));
        $order['order_num'] = date("ymd-His-") . rand(1000, 9999);
        $order['cart_total'] = $cart_total;
        $order['order_time'] = time();
        $order['confirmed'] = 0;
       // $order['discount'] = $discount;
        $order['shipping'] = MCC_SHIPPING;
        $order['order_total'] = $order_total;

        $order['status_updated_on'] = time();
        $order['status'] = 'New';

        //insert into database
        $this->db->insert('order', $order);
        $order_id = $this->db->insert_id();

        $data = array();
        $data['order_id'] = $order_id;
        $data['b_title'] = $this->input->post('b_title', TRUE);
        $data['b_first_name'] = $this->input->post('b_first_name');
        $data['b_last_name'] = $this->input->post('b_last_name');
        $data['b_address1'] = $this->input->post('b_address1');
        $data['b_address2'] = $this->input->post('b_address2');
        $data['b_city'] = $this->input->post('b_city');
        $data['b_county'] = $this->input->post('b_county');
        $data['b_postcode'] = $this->input->post('b_postcode');
        $data['b_phone'] = $this->input->post('b_phone');
        $data['email'] = $this->input->post('email');

        $data['s_title'] = $this->input->post('s_title');
        $data['s_first_name'] = $this->input->post('s_first_name');
        $data['s_last_name'] = $this->input->post('s_last_name');
        $data['s_address1'] = $this->input->post('s_address1');
        $data['s_address2'] = $this->input->post('s_address2');
        $data['s_city'] = $this->input->post('s_city');
        $data['s_county'] = $this->input->post('s_county');
        $data['s_postcode'] = $this->input->post('s_postcode');
        $data['s_phone'] = $this->input->post('s_phone');

        //print_r($data); exit();
        $this->db->insert('order_detail', $data);
        $order_id = $this->db->insert_id();

        foreach ($this->cart->contents() as $item) {

            $order_item = array();
            $order_item['product_id'] = $item['id'];
            $order_item['order_id'] = $order_id;
            $order_item['order_item_name'] = $item['name'];
            $order_item['order_item_qty'] = $item['qty'];
            $order_item['order_item_price'] = $item['price'];
            $order_item['order_item_options'] = base64_encode(serialize($item['options']));
           
            $status = $this->db->insert('order_item', $order_item);
            if (!$status) {
                $this->db->where('order_id', $order_id);
                $this->db->delete('order');

                $this->db->where('order_id', $order_id);
                $this->db->delete('order_item');
                return FALSE;
            }
        }
      
        //delete item from cart
        $this->cart->destroy();


        $response = array();
        $response['order_id'] = $order_id;
        $response['order'] = $order;
        $response['order_detail'] = $data;
        return $response;
    }

    function orderConfirmed($sorder) {
//       echo "<pre>";
//        print_r($sorder); exit;
	       $this->load->library('parser');
        if ($sorder['confirmed'] == 1)
            
          
        //    return;

        //Confirm the order
        $data = array();
        $data['confirmed'] = 1;
        $this->db->where('order_id', $sorder['order_detail']['order_id']);
        $this->db->update('order', $data);
    
        $cart = unserialize(base64_decode($sorder['order']['cart']));

        //Send out email to store owner
        $order_details = array();
        $order_details['order'] = $sorder['order_detail'];
        $order_details['cart_contents'] = $cart;

        $emailData = array();
        $emailData['DATE'] = date("jS F, Y");
        $emailData['S_FIRSTNAME'] = $sorder['order_detail']['s_first_name'];
        $emailData['S_LASTNAME'] = $sorder['order_detail']['s_last_name'];
        $emailData['S_STREET'] = $sorder['order_detail']['s_address1'];
        $emailData['S_STREET2'] = $sorder['order_detail']['s_address2'];
        $emailData['S_CITY'] = $sorder['order_detail']['s_city'];
        $emailData['S_COUNTY'] = $sorder['order_detail']['s_county'];
        $emailData['S_POSTCODE'] = $sorder['order_detail']['s_postcode'];
        $emailData['S_PHONE'] = $sorder['order_detail']['s_phone'];
        $emailData['EMAIL'] = $sorder['order_detail']['email'];

        $emailData['B_FIRSTNAME'] = $sorder['order_detail']['b_first_name'];
        $emailData['B_LASTNAME'] = $sorder['order_detail']['b_last_name'];
        $emailData['B_STREET'] = $sorder['order_detail']['b_address1'];
        $emailData['B_STREET2'] = $sorder['order_detail']['b_address2'];
        $emailData['B_CITY'] = $sorder['order_detail']['b_city'];
        $emailData['B_COUNTY'] = $sorder['order_detail']['b_county'];
        $emailData['B_POSTCODE'] = $sorder['order_detail']['b_postcode'];
        $emailData['BASE_URL'] = base_url();
        $emailData['DATA'] = $order_details;


        $emailBody = $this->parser->parse('emails/admin-order-email', $emailData, TRUE);
		
        log_message($this->log_level,'email body.here');
        log_message($this->log_level,'email body.'.$emailBody);

        $this->email->initialize($this->config->item('EMAIL_CONFIG'));
        $this->email->from(MCC_EMAIL_NOREPLY, MCC_EMAIL_FROM);
        $this->email->to(MCC_EMAIL_ADMIN);

        //$this->email->bcc('js_thind@hotmail.com');
        $this->email->subject('New Order Placed');
        $this->email->message($emailBody);
         $this->email->send();
		//print_R($status); exit();

        //Send out email to customer
        $emailData = array();
        $emailData['DATE'] = date("jS F, Y");
        $emailData['BASE_URL'] = base_url();
        $emailData['DATA'] = $order_details;

        $emailBody = $this->parser->parse('emails/customer-order-details', $emailData, TRUE);
        //echo $emailBody;
        $this->email->initialize($this->config->item('EMAIL_CONFIG'));
        $this->email->from(MCC_EMAIL_NOREPLY, MCC_EMAIL_FROM);
        $this->email->to($sorder['order_detail']['email']);
        $this->email->subject('Order Placed Successfully');
        $this->email->message($emailBody);
        if($this->email->send()){
            echo "okay";
        }else{
            echo "not";
        }
        
    }

}

?>
