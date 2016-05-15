<?php

class Customermodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getUserInformation() {
        $userDetails = $this->flexi_auth->get_user_custom_data();
        $userAccId = "uacc_id";
        $user_id = $userDetails[$userAccId];
        $this->db->select('user_profiles.upro_image,user_profiles.upro_first_name,user_profiles.upro_last_name,user_accounts.uacc_email,user_accounts.uacc_username,user_accounts.uacc_id');
        $this->db->where('user_accounts.uacc_id', $user_id);
        $this->db->join('user_profiles', 'user_profiles.upro_uacc_fk = user_accounts.uacc_id');
        $query = $this->db->get('user_accounts');
        return $query->row_array();
    }

    function getAddressByType($addressType = "s") {
        $userDetails = $this->flexi_auth->get_user_custom_data();
        $userAccId = "uacc_id";
        $user_id = $userDetails[$userAccId];
        $this->db->where('uadd_uacc_fk', $user_id);
        $this->db->where('address_type', $addressType);
        $query = $this->db->get('user_address');
        return array('num_rows' => $query->num_rows(), 'result' => $query->row_array());
    }

    function getGuestUserInfo($id) {
        $this->db->select('user_accounts.uacc_email,user_accounts.uacc_email,user_accounts.uacc_id,user_profiles.upro_first_name,user_profiles.upro_last_name,user_profiles.upro_phone');
        $this->db->where('uacc_id', $id);
        $this->db->join('user_profiles', 'user_profiles.upro_uacc_fk = user_accounts.uacc_id');
        $query = $this->db->get('user_accounts');
        return $query->row_array();
    }

    function getUserAccountByEmail($email) {
        $this->db->select('user_accounts.uacc_email,user_accounts.uacc_email,user_accounts.uacc_id,user_profiles.upro_first_name,user_profiles.upro_last_name,user_profiles.upro_phone');
        $this->db->where('uacc_email', $email);
        $this->db->where('uacc_group_fk', '4');
        $this->db->join('user_profiles', 'user_profiles.upro_uacc_fk = user_accounts.uacc_id');
        $query = $this->db->get('user_accounts');
        return $query->row_array();
    }

    function saveShipingAndBillingAddress() {
        $shippingAndBillingInput = $this->input->post();
        $BillData = array();
        $ShipData = array();
        $this->isLogged = $this->flexi_auth->is_logged_in();
        if ($this->isLogged) {
            $userDetails = $this->flexi_auth->get_user_custom_data();
            $user_id = $userDetails["uacc_id"];
            $BillData['uadd_uacc_fk'] = $user_id;
            $ShipData['uadd_uacc_fk'] = $user_id;
        } else {
            $BillData['uadd_uacc_fk'] = $this->session->userdata('guest_user_id');
            $ShipData['uadd_uacc_fk'] = $this->session->userdata('guest_user_id');
        }
        //uadd_uacc_fk
        if ($this->input->post('save_shipping') != null) {

            $ShipData['uadd_alias'] = self::auto_increment_alias($shippingAndBillingInput['uadd_recipient'] . ' ' . $shippingAndBillingInput['uadd_address_01']);
            $ShipData['uadd_recipient'] = $shippingAndBillingInput['uadd_recipient'];
            $ShipData['uadd_phone'] = $shippingAndBillingInput['uadd_phone'];
            $ShipData['uadd_company'] = $shippingAndBillingInput['uadd_company'];
            $ShipData['uadd_address_01'] = $shippingAndBillingInput['uadd_address_01'];
            $ShipData['uadd_address_02'] = $shippingAndBillingInput['uadd_address_02'];
            $ShipData['uadd_city'] = $shippingAndBillingInput['uadd_city'];
            $ShipData['uadd_county'] = $shippingAndBillingInput['uadd_county'];
            $ShipData['uadd_post_code'] = $shippingAndBillingInput['uadd_post_code'];
            $ShipData['uadd_country'] = $shippingAndBillingInput['uadd_country'];
            $this->db->insert('user_address', $ShipData);
        }
        if ($this->input->post('save_billing') != null) {

            $BillData['uadd_alias'] = self::auto_increment_alias($shippingAndBillingInput['billing_uadd_recipient'] . ' ' . $shippingAndBillingInput['billing_uadd_address_01']);
            $BillData['uadd_recipient'] = $shippingAndBillingInput['billing_uadd_recipient'];
            $BillData['uadd_phone'] = $shippingAndBillingInput['billing_uadd_phone'];
            $BillData['uadd_company'] = $shippingAndBillingInput['billing_uadd_company'];
            $BillData['uadd_address_01'] = $shippingAndBillingInput['billing_uadd_address_01'];
            $BillData['uadd_address_02'] = $shippingAndBillingInput['billing_uadd_address_02'];
            $BillData['uadd_city'] = $shippingAndBillingInput['billing_uadd_city'];
            $BillData['uadd_county'] = $shippingAndBillingInput['billing_uadd_county'];
            $BillData['uadd_post_code'] = $shippingAndBillingInput['billing_uadd_post_code'];
            $BillData['uadd_country'] = $shippingAndBillingInput['billing_uadd_country'];
            $this->db->insert('user_address', $BillData);
        }
    }

    function auto_increment_alias($alias) {
        $this->isLogged = $this->flexi_auth->is_logged_in();
        if ($this->isLogged) {
            $userDetails = $this->flexi_auth->get_user_custom_data();
            $user_id = $userDetails["uacc_id"];
        } else {
            $user_id = $this->session->userdata('guest_user_id');
        }
        $auto = self::getIteratedValue($user_id, $alias);
        return $auto;
    }

    static $count = 1;

    function getIteratedValue($user_id, $alias) {
        $this->db->where('uadd_uacc_fk', $user_id);
        $this->db->where('uadd_alias', $alias);
        $query = $this->db->get('user_address');
        if ($query->num_rows() > 0) {
            $exp = explode("-", $alias);
            if (isset($exp[1])) {

                $increment = $exp[1] + self::$count;
                $exp[1] = $increment;
                return($this->getIteratedValue($user_id, implode('-', $exp)));
            } else {

                $exp[1] = self::$count;
                return($this->getIteratedValue($user_id, implode('-', $exp)));
            }
        } else {
            return($alias);
        }
    }

    function sendVerificationEmail() {
        $userIdentity = $this->input->post('email');
        $this->flexi_auth->forgotten_password($userIdentity);
    }

    function ContactUsQuery() {
        $this->load->library('email');
        $this->load->helper('email');
        $output = [];
        $output['success'] = FALSE;
        $output['message'] = 'Sent to enquiry email is not valid, please fill valid email in site setting.';
        if (valid_email(MCC_EMAIL_ENQUIRY)) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $subject = $this->input->post('subject');
            $comment = $this->input->post('comment');

            /* -----------Contact Us Query log----------- */
            $Contactdata = array();
            $Contactdata['name'] = $name;
            $Contactdata['email'] = $email;
            $Contactdata['subject'] = $subject;
            $Contactdata['description'] = $comment;
            $this->db->insert('contact', $Contactdata);
            /* -----------END Contact Us Query log----------- */

            $this->email->clear();
            $this->email->to(MCC_EMAIL_ENQUIRY);
            $this->email->from(MCC_EMAIL_NOREPLY);
            $this->email->subject('Contact us Page reuqested by ' . $name);
            $this->email->message('Hi admin , Here is the info ' . $name . ' has requested.<br>' . $comment);
            $this->email->send();
            $output['success'] = TRUE;
            $output['message'] = 'Your Query has been Submitted!!';
        }
        return $output;
    }

}

?>
