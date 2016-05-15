<?php

class Checkoutmodel extends CI_Model {
    //order status
    public static $STATUS = array(
        0 => 'cancelled',
        1 => 'Success',        
    );
    
    function __construct() {
        parent::__construct();
    }
    
    function addBooking($customer, $order_total) {
        $customer = (array) $customer;
        extract($this->Cartmodel->variables());
        foreach ($this->cart->contents() as $item) {
            $cart = $item;
        }
        $this->load->helper('string');
        $booking = array();
        $booking['customer_id'] = arrIndex($customer, 'id');
//        $booking['cart'] = base64_encode(serialize($this->cart->contents()));
        $booking['unique_id'] = date("ymd-His-") . rand(1000, 9999);
        $booking['event_id'] = arrIndex($cart, 'id');
        $booking['ctn'] = arrIndex($cart, 'qty');
        $booking['booking_total'] = $order_total;
        $booking['booking_status'] = 'pending';
        $booking['created'] = time();


        //insert into database
        $this->db->insert('eventbooking_bookings', $booking);
        $booking_id = $this->db->insert_id();

        $ticket = array();
        for ($i = 0; $i < $booking['ctn']; $i++) {
            foreach ($booking as $row) {
                $ticket[$i] = $booking;
            }
        }

        $no = 1;
        $ticket_data = array();
        foreach ($ticket as $row) {
            $ticket_data['booking_id'] = $booking_id;
            $ticket_data['event_id'] = $row['event_id'];
            $ticket_data['ticket_id'] = $row['unique_id'] . '-' . $no;
            $this->db->insert('eventbooking_bookings_tickets', $ticket_data);
            $no++;
        }

        $response = array();
        $response = $data;
        $response['booking_id'] = $booking_id;
        $response['booking'] = $booking;
        return $response;
    }


    function detail($bid) {
//        $this->db->select('*');
        $this->db->from('eventbooking_bookings');
        $this->db->join('eventbooking_events', 'eventbooking_events.event_id = eventbooking_bookings.event_id', 'left');
        $this->db->join('user_extra_detail', 'eventbooking_events.user_id = user_extra_detail.id', 'left');
        $this->db->where('eventbooking_bookings.unique_id', $bid);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row_array();
        }
        return false;
    }

    function updateBooking($id, $update = array()) {
        $this->db->where('unique_id', $id);
        $this->db->update('eventbooking_bookings', $update);
        return true;
    }

    function fetchDetails($onum) {
        $this->load->library('email');
        $this->load->library('parser');


        $data = array();
        $data['booking_status'] = 'confirmed';
        $this->db->where('unique_id', $onum);
        $this->db->update('eventbooking_bookings', $data);

        $user_id = $this->aauth->get_user()->id;

        $this->db->select('eventbooking_bookings.*,customer.*');
        $this->db->from('eventbooking_bookings');
        $this->db->join('customer', 'customer.auth_user_id=eventbooking_bookings.customer_id');
        $this->db->where('customer.auth_user_id', $user_id);
        $this->db->where('eventbooking_bookings.unique_id', $onum);
        $query = $this->db->get();
        $booking = $query->row_array();

        $this->db->select('eventbooking_events.*,eventbooking_event_type.*,aauth_users.*');
        $this->db->from('eventbooking_bookings');
        $this->db->join('eventbooking_events', 'eventbooking_events.event_id=eventbooking_bookings.event_id', 'left');
        $this->db->join('eventbooking_event_type', 'eventbooking_events.event_type_id=eventbooking_event_type.event_type_id', 'left');
        $this->db->join('aauth_users', 'aauth_users.id=eventbooking_events.user_id', 'left');
        $this->db->where('eventbooking_bookings.unique_id', $onum);
        $fquery = $this->db->get();
        $frachisee = $fquery->row_array();
//         e($frachisee);
        //Send out email to store owner
        $order_details = array();
        $order_details['booking'] = $booking;
        $order_details['frachisee'] = $frachisee;

        $emailData = array();
        $emailData['DATE'] = date("jS F, Y");
        $emailData['FIRSTNAME'] = arrIndex($booking, 'first_name');
        $emailData['LASTNAME'] = arrIndex($booking, 'last_name');
        $emailData['ADDRESS'] = arrIndex($booking, 'delivery_address1');
        $emailData['ADDRESS2'] = arrIndex($booking, 'delivery_address2');
        $emailData['CITY'] = arrIndex($booking, 'delivery_city');
        $emailData['STATE'] = arrIndex($booking, 'delivery_state');
        $emailData['POSTCODE'] = arrIndex($booking, 'delivery_zipcode');
        $emailData['PHONE'] = arrIndex($booking, 'delivery_phone');
        $emailData['EMAIL'] = arrIndex($booking, 'email');

        $emailData['BTNO'] = $booking['unique_id'];
        $emailData['CTN'] = $booking['ctn'];
        $emailData['TOTAL'] = $booking['booking_total'];

        $emailData['EVENT'] = $frachisee['event_title'];
        $emailData['EVENTYPE'] = $frachisee['event_type'];
        $emailData['EVENTSTART'] = $frachisee['event_start_ts'];
        $emailData['EVENTEND'] = $frachisee['event_end_ts'];
        $emailData['EVENTIMG'] = $frachisee['event_img'];

        $emailBody = $this->parser->parse('emails/admin-order-email', $emailData, TRUE);
        $this->email->initialize($this->config->item('EMAIL_CONFIG'));
        $this->email->from(MCC_EMAIL_NOREPLY, MCC_EMAIL_FROM);
        $this->email->to($frachisee['email']);
        $this->email->subject('New Ticket Booked');
        $this->email->message($emailBody);
        $this->email->send();

//        echo $this->email->print_debugger();
//        $emailData = array();
//        $emailData['DATE'] = date("jS F, Y");
//        $emailData['BASE_URL'] = base_url();
//        $emailData['DATA'] = $order_details;
        $emailBody = $this->parser->parse('emails/customer-order-details', $emailData, TRUE);
        $this->email->initialize($this->config->item('EMAIL_CONFIG'));
        $this->email->from(MCC_EMAIL_NOREPLY, MCC_EMAIL_FROM);
        $this->email->to($booking['email']);
        $this->email->subject('Ticket Booked Successfully');
        $this->email->message($emailBody);
        $this->email->send();

        return $booking;
    }

}

?>
