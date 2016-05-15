<?php
class Settingsmodel extends CI_Model {

    private $settings = array();

    function __construct() {
        parent::__construct();
    }

    function get($key) {
        if(array_key_exists($key, $this->settings)) return $this->settings[$key];

        $this->db->where('setting_key', $key);
        $rs = $this->db->get('setting');
        if($rs->num_rows() == 1) {
            $row = $rs->row_array();
            $this->settings[$key] = $row['setting_value'];
            return $row['setting_value'];
        }

        return FALSE;
    }
}
?>