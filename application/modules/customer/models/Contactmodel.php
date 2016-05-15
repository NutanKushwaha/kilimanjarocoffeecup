<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contactmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function listAllAddress($userId) {
        $this->db->where('uadd_uacc_fk', $userId);
        $query = $this->db->get('user_address');

        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    function getAddressById($addressId) {
        $userDetails = $this->flexi_auth->get_user_custom_data();
        $user_id = $userDetails["uacc_id"];

        $this->db->where('uadd_uacc_fk', $user_id);
        $this->db->where('uadd_id', $addressId);
        $query = $this->db->get('user_address');
        return array('num_rows' => $query->num_rows(), 'result' => $query->row_array());
    }

    function InsertAddress($addressId) {
        $userDetails = $this->flexi_auth->get_user_custom_data();
        $user_id = $userDetails["uacc_id"];
        $input = $this->input->post();
        self::updateAddressType($user_id, $input['address_type']);
        $data = array();
        $data['uadd_uacc_fk'] = $user_id;
        $data['uadd_recipient'] = $input['uadd_recipient'];
        $data['uadd_phone'] = $input['uadd_phone'];
        $data['uadd_company'] = $input['uadd_company'];
        $data['uadd_address_01'] = $input['uadd_address_01'];
        $data['uadd_address_02'] = $input['uadd_address_02'];
        $data['uadd_city'] = $input['uadd_city'];
        $data['uadd_county'] = $input['uadd_county'];
        $data['uadd_post_code'] = $input['uadd_post_code'];
        $data['uadd_country'] = $input['uadd_country'];
        $data['address_type'] = $input['address_type'];
        $data['uadd_alias'] = $input['alias'];

        $this->db->insert('user_address', $data);
    }

    function updateAddressType($userId, $addressType) {
        $this->db->where('uadd_uacc_fk', $userId);
        $this->db->where('address_type', $addressType);
        $this->db->update('user_address', array('address_type' => ""));
    }

    function UpdateAddress($addressId) {
        $userDetails = $this->flexi_auth->get_user_custom_data();
        $user_id = $userDetails["uacc_id"];
        $input = $this->input->post();
        self::updateAddressType($user_id, $input['address_type']);
        $data = array();
        $data['uadd_recipient'] = $input['uadd_recipient'];
        $data['uadd_phone'] = $input['uadd_phone'];
        $data['uadd_company'] = $input['uadd_company'];
        $data['uadd_address_01'] = $input['uadd_address_01'];
        $data['uadd_address_02'] = $input['uadd_address_02'];
        $data['uadd_city'] = $input['uadd_city'];
        $data['uadd_county'] = $input['uadd_county'];
        $data['uadd_post_code'] = $input['uadd_post_code'];
        $data['uadd_country'] = $input['uadd_country'];
        $data['address_type'] = $input['address_type'];
        $this->db->where('uadd_uacc_fk', $user_id);
        $this->db->where('uadd_id', $addressId);
        $this->db->update('user_address', $data);
    }

}
