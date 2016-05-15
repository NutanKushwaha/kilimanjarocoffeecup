<?php

/**
 * user_notification
 * 
 * protected $id; 
 * protected $user_id; 
 * protected $source_class; 
 * protected $source_action; 
 * protected $user_ip; 
 * protected $activity_timeprotected $
 * 
 * Store the ip as a INT(11) UNSIGNED, 
 * then use the INET_ATON and INET_NTOA 
 * functions to store/retrieve the ip address.
 */
class UserNotificationmodel extends CI_Model {

    protected $table_name; 
    
    public $id; 
    public $user_id; 
    public $user_ip; 
    public $source_class; 
    public $source_action;     
    public $activity_time;

    function __construct() {
        parent::__construct();
        
        $this->table_name= 'user_notification';
        
        $this->id = 'id'; 
        $this->user_id = 'user_id'; 
        $this->user_ip = 'user_ip';
        $this->source_class = 'source_class';
        $this->source_action = 'source_action';        
        $this->activity_time = 'activity_time';
    }

    function insertRecord($data = array()){
        $this->db->insert($this->table_name, $data);
    }
    
    function fetchByID($aid) {
        return FALSE;
    }
    
    // Not in use
    function getEncryptIp($ipAddr = '0.0.0.0'){
        if(!$ipAddr){
            return '0.0.0.0';
        }
        $sql  = 'SELECT INET_NTOA('.$ipAddr.')';
        $ipResult = $this->db->query($sql);
        return $ipResult;
    }

    // Not in use
    function getDecryptIp($ipAddr = '0.0.0.0'){
        if(!$ipAddr){
            return '0.0.0.0';
        }
        $sql  = 'SELECT INET_NTOA('.$ipAddr.')';
        $ipResult = $this->db->query($sql);
        return $ipResult;
    }
}

?>