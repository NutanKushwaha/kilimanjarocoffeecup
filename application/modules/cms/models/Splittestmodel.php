<?php

class Splittestmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

   //function get test
	 function getTest($page_id = false) {
		$this->db->from('split_test');
		$this->db->where('page_id', intval($page_id));
		$this->db->where('test_is_active', 1);
		$rs = $this->db->get();
		if($rs->num_rows() == 1) {
			return $rs->row_array();
		}
		return FALSE;
	}

	//function get Page version
	function getPageVersions($page_id) {
		$this->db->where('page_id', intval($page_id));
		$this->db->order_by('rotation_percentage', 'ASC');
		$rs = $this->db->get('page_version');
		if($rs && $rs->num_rows() > 0) {
			return $rs->result_array();
		}
		return FALSE;
	}

	//function get version details
	function getVersionDetails($vid) {
		$this->db->from('page_version');
		$this->db->join('mainsite_page_template', 'mainsite_page_template.template_id = page_version.template_id ');
		
		$this->db->where('page_version_id', intval($vid));
		$rs = $this->db->get();
		if($rs && $rs->num_rows() == 1) {
			return $rs->row_array();
		}
		return FALSE;

	}

	//function get version blocks
	function getVersionBlocks($vid) {
		$this->db->where('page_version_id', intval($vid));
		$rs = $this->db->get('block');

		return $rs->result_array();
	}

	//function last versions served
	function getMostRecentVersion($id) {
        //echo $id;
		$this->db->select('*');
		$this->db->where('split_test_id', $id);
		$this->db->order_by('version_viewed_on', 'DESC');
		$this->db->limit(1);
		$rs = $this->db->get('split_test_log');

        //print_r ($rs->row_array()); exit();
		if($rs && $rs->num_rows() == 1) return $rs->row_array();

		return FALSE;
	}

	function getVisitorVersionServed($test_id) {
		//fetch version id from cookie
		$cookie = $this->input->cookie('TEST'.$test_id, TRUE);

		if($cookie) {
			return $cookie;
		}

		return false;
	}

	function splitTestVersionSelected($test_id, $version_id) {
		//Generate UID for visitor
        $uid = md5(date("ymd-His-").rand(1000,9999));

        //store version id in cookie
		//set expiry of cookie to one year
		$cookie = array(
			'name'   => 'ST_TEST'.$test_id,
			'value'  => $uid.':'.$version_id,
			'expire' => '86500',
			'prefix' => 'ST_'
		);

		if($this->config->item('cookie_domain') != '') {
			$cookie['domain'] = $this->config->item('cookie_domain');
		}

		//$this->input->set_cookie($cookie);
		$status = setcookie($cookie['name'], $cookie['value'], time() + $cookie['expire']);
        if($status) return $uid;

        return FALSE;
	}


	function splitTestLog($uid, $vid, $st_id, $repeat_visit) {
        //fetch the split_test details
        $this->db->where('split_test_id', $st_id);
        $rs = $this->db->get('split_test');
		$split_test = $rs->row_array();


		//add entry to split_test_log table
		$data = array();
        $data['uid'] = $uid;
		$data['split_test_id'] = intval($st_id);
		$data['page_version_id'] = intval($vid);
		$data['repeat_visit'] = $repeat_visit;
		if(isset($_SERVER['REMOTE_ADDR'])) {
			$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
		} else {
			$data['ip_address'] = '';
		}

		$data['version_viewed_on'] = time();
		$data['conversion'] = 0;

		$this->db->insert('split_test_log', $data);

        $split_test_log_id = $this->db->insert_id();

        //set the session data
        $session_data = array();
		$session_data['SPLIT_TEST_LOG_ID'] = $split_test_log_id;
		$session_data['SPLIT_TEST_ID'] = $st_id;
        $session_data['SUCCESS_URL'] = $split_test['success_url'];

		$this->session->set_userdata($session_data);
	}

    function getSplitTest($page_id) {
		$repeat_visit = 0;
        $split_test = array();
        $version_details = false;

        $this->db->from('split_test');
        $this->db->where('page_id', intval($page_id));
        $this->db->where('test_is_active', 1);
        $rs = $this->db->get();
        if($rs->num_rows() == 1) return $rs->row_array();

        return false;
	}

    function getSplitTestVersion($split_test) {
        $versions = $this->Splittestmodel->getPageVersions($split_test['page_id']);
        if(!$versions) return false;
		
        //Check for previous version served if any
        $cookie_version_id = false;
        $update_all_rotation = false;
        $uid = 0;
        $rotation_left = 0;
        if(isset($_COOKIE["ST_TEST".$split_test['split_test_id']])) {
			$cookie_value = $_COOKIE["ST_TEST".$split_test['split_test_id']];
            $cookie_value_arr = explode(':', $cookie_value);
            $uid = $cookie_value_arr[0];
            $cookie_version_id = $cookie_value_arr[1];
        }


        $repeat_visit = 0;
        if($cookie_version_id) {
            $version_to_serve = $cookie_version_id;
            $repeat_visit = 1;

        }else {

           $versions_arr = array();
            foreach($versions as $version) {
                $versions_arr[] = $version['page_version_id'];
            }
			
            $version_served = $this->Splittestmodel->getMostRecentVersion($split_test['split_test_id']);

            if(!$version_served) {
                $version_to_serve = $versions_arr[0];

            }else {
                $index = array_search($version_served['page_version_id'], $versions_arr);

                //fetch the version details
                $version_served_details = array();
                $version_served_details = $this->Splittestmodel->getVersionDetails($version_served['page_version_id']);
                if($version_served_details['rotation_left'] > 0) {
                     $version_to_serve = $version_served_details['page_version_id'];
                 }else{
                     if($index == (count($versions_arr)-1)) {
                         $version_to_serve = $versions_arr[0];
                         $update_all_rotation = true;
                     }else{
                        $version_to_serve = $versions_arr[$index+1];
                     }
                 }
            }
        }

        $version_details = array();
        $version_details = $this->Splittestmodel->getVersionDetails($version_to_serve);

        if(!$version_details) return FALSE;

        $version_data = array();
        $version_data['version_details'] = $version_details;
        $version_data['update_all_rotation'] = $update_all_rotation;
        $version_data['repeat_visit'] = $repeat_visit;
        $version_data['uid'] = $uid;
	

        return $version_data;
	}

    //update the rotation
    function updateRotations($p_vid){
        $version_details = array();
        $version_details = $this->Splittestmodel->getVersionDetails($p_vid);
        if($version_details) {
            $update = array();
            $update['rotation_left'] = $version_details['rotation_left'] - 1;
            $this->db->where('page_version_id', $version_details['page_version_id']);
            $this->db->update('page_version', $update);
        }
    }

    //set Rotation left to orignal
    function setRotationLeftToOrignal($pid){
        $this->db->where('page_id', $pid);
        $rs = $this->db->get('page_version');
		foreach($rs->result_array() as $item) {
            $update = array();
            $update['rotation_left'] = $item['total_rotations'];
            $this->db->where('page_version_id', $item['page_version_id']);
            $this->db->update('page_version', $update);
        }
    }
}
?>