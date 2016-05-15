<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification {

    private $CI;
    private $id;
    private $user_ip;
    private $user_id;
    private $creator_id;
    private $source_class;
    private $source_action;
    private $activity_time;
    private $extra_values;
    private $read;
    private $loggedUserData;
    private $rule_tbl;
    private $notification_tbl;
    private $user_group_tbl;

    public function __construct() {
        $this->CI = & get_instance();
        $this->rule_tbl = 'notification_rules';
        $this->notification_tbl = 'user_notification';
        $this->user_group_tbl = 'aauth_user_to_group';

        $this->CI->load->database();
        $this->id = 'id';
        $this->user_ip = 'user_ip';
        $this->user_id = 'user_id';
        $this->creator_id = 'creator_id';
        $this->source_class = 'source_class';
        $this->source_action = 'source_action';
        $this->activity_time = 'activity_time';
        $this->extra_values = 'extra_values';
        $this->read = 'read';

        $this->loggedUserData = $this->CI->session->userdata;
    }

    function notify($param = array()) {
        if (!isset($this->loggedUserData['loggedin']) || !$this->loggedUserData['loggedin']) {
            return false;
        }
        $localRules = $this->getRule($param['class'], $param['method'], $param['filter']);

        if (!$localRules['active']) {
            return;
        }
        $insertArray = array();
        $assigneIds = !empty($assigneIds) ? explode(',', $param['assigne_id']) : '';
        $notifyIds = !empty($localRules['assigne']) ? explode(',', $localRules['assigne']) : '';
        $notifyGrp = array();
        if (empty($assigneIds)) {
            if (!empty($localRules['grp']) && $localRules['grp'] == 'allgrp') {
                $assigneIds = $this->getUsersWithGrpIds(explode(',', $localRules['assigne']));
                $notifyIds = $assigneIds;
            } else {
                return false;
            }
        }

        $param['rule_id'] = $localRules['id'];
        foreach ($assigneIds as $forId) {
            if (in_array($forId, $notifyIds)) {
                $data = array();
                $data[$this->read] = '0';
                $data[$this->user_id] = $forId;
                $data[$this->creator_id] = $this->loggedUserData['id'];
                $data[$this->activity_time] = time();
                $data[$this->user_ip] = $this->loggedUserData['ip_address'];
                $data[$this->extra_values] = json_encode($param);
                $data[$this->source_class] = $this->CI->router->fetch_class();
                $data[$this->source_action] = $this->CI->router->fetch_method();
                $insertArray[] = $data;
            }
        }

        if (count($insertArray)) {
            $this->CI->db->insert_batch($this->notification_tbl, $insertArray);
        }
        self::triggerMail($insertArray);
        return true;
    }

    function getNotification() {
        $this->CI->load->library('parser');
        $msgString = '<li>No notification for you</li>';
        $notificationHtml = '';
        $rstSet = $this->CI->db->select('*')
                ->from('user_notification')
                ->where($this->user_id, $this->loggedUserData['id'])
                ->order_by('activity_time', 'desc')
                ->get();
        if ($rstSet->num_rows()) {
            $rule = $this->getAllRule();
            $rstSetArr = $rstSet->result_array();
            $msgString = '';

            foreach ($rstSetArr as $notifyKey => $notifyArr) {
                $notification_extra_param = json_decode($notifyArr['extra_values'], true);
                $notification_extra_param['name'] = $this->loggedUserData['name'];
                $upperKeyNotArr = array();
                foreach ($notification_extra_param as $nkey => $nval) {
                    $upperKeyNotArr[strtoupper($nkey)] = $nval;
                }
                $oldMsgString = $this->CI->parser->parse_string($rule[$notification_extra_param['rule_id']]['msg'], $upperKeyNotArr, TRUE);
                $oldMsgString = '<p>' . $oldMsgString . '<br><span style="color: #2AABE4;font-weight: 700;">' . date('jS M. Y', $notifyArr['activity_time']) . '</span></p>';
                $msgString .= '<li>' . $oldMsgString . '</li>';
            }
        }
        $notificationHtml = '<ul>' . $msgString . '</ul>';
        return $notificationHtml;
    }

    function getRule($className = null, $methodName = null, $filter = null) {
        $rst = $this->CI->db->Select('*')
                ->from($this->rule_tbl)
                ->where('class', $className)
                ->where('action', $methodName)
                ->where('filter', strtolower($filter))
                ->get();
        if ($rst->num_rows()) {
            return $rst->row_array();
        }
        return array();
    }

    function getAllRule() {
        $rst = $this->CI->db->Select('*')
                        ->from($this->rule_tbl)->get();
        if ($rst->num_rows()) {
            $rstSet = $rst->result_array();
            $data = array();
            foreach ($rstSet as $rstK => $rstVal) {
                $data[$rstVal['id']] = $rstVal;
            }
            return $data;
        }
        return array();
    }

    function getUsersWithGrpIds($grpIds = array()) {
        $rstSet = $this->CI->db->select('*')
                ->from($this->user_group_tbl)
                ->where_in('group_id', $grpIds)
                ->get();
        if ($rstSet->num_rows()) {
            $rstArr = $rstSet->result_array();
            $userArr = array();
            foreach ($rstArr as $rstArrKeyVal) {
                $userArr[] = $rstArrKeyVal['user_id'];
            }
            return $userArr;
        }
        return array();
    }

    private function triggerMail($configs) {
        ini_set('diplay_errors', 'On');
        foreach ($configs as $config):
            $data = json_decode(arrIndex($config, 'extra_values'), true);
            if (arrIndex($data, 'class') != 'virtcab') {
                continue;
            }
//            $ci = & get_instance();
//            $ci->load->library('parser');
//            $ci->load->library('email');
//            $ci->load->model('commonmodel');
//            $to_user = $ci->commonmodel->getByPk(arrIndex($data, 'assigne_id'), 'aauth_users');
//            $to_email = arrIndex($to_user, 'email');
//            $to_name = arrIndex($to_user, 'name');
//            $form_user =  $ci->commonmodel->getByPk(arrIndex($data, 'creator_id'), 'aauth_users');
//            $form_name = arrIndex($form_user,'name');
//            $emailData = array();
//            $emailData['DATE'] = date("jS F, Y");
//            $emailData['to_name'];
//            $emailData['to_email'];
//            $emailData['from_name'];            
//            $emailBody = $ci->parser->parse('user/emails/user-information', $emailData, TRUE);            
//            $msg_body = $emailBody;
//            $ci->email->initialize($ci->config->item('EMAIL_CONFIG'));
//            $ci->email->from('test@testmail.com', 'The Creative Station'); // change it to yours
//            $ci->email->to('test@testmail.com'); // change it to yours
//            $ci->email->subject('Virtual Cabinet Notification');
//            $ci->email->message($msg_body);
//            if ($ci->email->send()) {
//                e('email sent');
//            } else {
//                e('not sended');
//            }
        endforeach;
    }

}
