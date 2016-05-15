<?php
function getThis() {
    $data = & get_instance();
    return $data;
}

function ep($str) {
    echo "<pre>";
    print_r($str);
    exit;
}

function p() {
    echo "<pre>";
}

function curModName($object) {
    return $object->router->fetch_module();
}

function convertToAuthStr($index) {
    return 'aauth_'.$index;
    $object = getThis();
    return arrIndex($object->aauth->config_vars, $index);
}

function createUrl($url, $base_url = false) {
    if (!$base_url)
        $base_url = base_url();
    return $base_url . $url;
    //return base_url() . 'index.php/' . $url;
}

function tableFields($tableName, $blank = false) {
    $fields = getThis()->db->list_fields($tableName);
    $new_fields = array();
    foreach ($fields as $field):
        if (!$blank)
            $new_fields[$field] = $field;
        else
            $new_fields[$field] = null;
    endforeach;
    return $new_fields;
}

function rSF($tableName) {
    $fields = tableFields($tableName);
    $new_array = array();
    foreach ($fields as $field):
        if (gParam($field) === null)
            continue;
        $new_array[$field] = gParam($field);
    endforeach;
    return $new_array;
}

function getModelLabel($model, $label = false) {
    $data = $model::label();
    e($data);
}

function curUser($user_id = false) {
    return getThis()->aauth->get_user($user_id);
}

function curUsrPid() {
    return getThis()->session->userdata('pid');
}

function clearDbThis($thisdb) {
    $thisdb->db->ar_from = array();
    return $thisdb->db;
}

function getFranchise($id) {
    $result = getThis()->db->get_where('user_extra_detail', array('id' => $id));
    $result = $result->row();
    return $result;
}

function pageFranchise($fid) {
    $ci = & get_instance();
    $ci->db->select('page_id');
    $ci->db->where('user_id', $fid);
    $page = $ci->db->get('page');
    return $page->row_array();
}

function noticationMsg($className = null, $classFunc = null) {
    if (!$classFunc || !$className)
        return 'blank message';
    $msgArr = array(
        'user' => array(
            'index' => '',
            'add' => '',
            'edit' => '',
        ),
        'dashboard' => array(
            'index' => 'this is dashboard index',
        ),
    );
    return arrIndex(arrIndex($msgArr, $className), $classFunc);
}

function setDefault(&$key = null, $defVal = null, $temp = null) {
    if (isset($key) && !empty($key)) {
        if ($temp) {
            return $temp;
        }
        return $key;
    } else {
        return $defVal;
    }
}

function getJSarrayFromPHPArray($phpArray = array(), $form = false) {
    $color = array(
        '#1395BA',
        '#F16C20',
        '#0F5B78',
        '#ECAA38',
        '#0D3c55',
        '#EBC844',
        '#C02E1D',
        '#A2B86C',
    );
    $retHtml = null;
    $retHtmlArr = array();
    $index = 0;
    if ($form == 'LabelDataColor') {
        foreach ($phpArray as $k => $val) {
            $retHtmlArr[$index] = '{label: \'' . $k . '\' , data:\'' . $val . '\', color:\'' . ($color[($index % count($color))]) . '\'}';
            $index = $index + 1;
        }
    } else {
        $retHtmlArr[0] = '[\'Label\' , \'value\']';
        $index = 1;
        foreach ($phpArray as $k => $val) {
            $retHtmlArr[$index] = '[\'' . $k . '\' , ' . $val . ']';
            $index = $index + 1;
        }
    }
    $retHtml = '[' . implode(',', $retHtmlArr) . ']';
    return $retHtml;
}

function site_url1($str) {
    return str_replace('admin/', '', $str);
}

function getFranchise1() {
    $id = getThis()->aauth->get_user();
    $user_or_franchise = $id->pid;
    $franchise = false;
    while ($user_or_franchise != 1) {
        $franchise = getThis()->aauth->get_user($user_or_franchise);
        $user_or_franchise = $franchise->pid;
    }
    if (!$franchise)
        return false;
    getThis()->db->where('aauth_users.id', $franchise->id);
    getThis()->db->from('aauth_users');
    getThis()->db->join('user_extra_detail', 'aauth_users.id = user_extra_detail.id');
    $franchise = getThis()->db->get()->row();
    return $franchise;
}

function daysBettween($date1, $date2, $exclude_weekends, $type) {
    $days = number_of_working_days($date1, $date2, $exclude_weekends);
    switch ($type):
        case 'D':
        case 'Y':
            return $days;
            break;
        case 'W':
            $week = ($exclude_weekends) ? 7 : 5;
            return $days / $week;
            break;
    endswitch;
}

function number_of_working_days($from, $to, $exclude_weekeds = false) {
    if ($exclude_weekeds)
        $workingDays = array(1, 2, 3, 4, 5);# date format = N (1 = Monday, ...)
    else
        $workingDays = array(1, 2, 3, 4, 5, 6, 7);# date format = N (1 = Monday, ...)
    $from = new DateTime($from);
    $to = new DateTime($to);
    $to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);
    $days = 0;
    foreach ($periods as $period) {
        if (!in_array($period->format('N'), $workingDays))
            continue;
        $days++;
    }
    return $days;
}

function weeksBetween($date1, $date2, $exclude_weekends = 7) {
    if ($exclude_weekends != 7)
        $exclude_weekends = 5;
    $day = 24 * 3600;
    $from = strtotime($date1);
    $to = strtotime($date2) + $day;
    $diff = abs($to - $from);
    $weeks = floor($diff / $day / $exclude_weekends);
    $days = $diff / $day;
    return $weeks;
}

function monthsBetween($date1, $date2) {
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
    $year1 = date('Y', $date1);
    $year2 = date('Y', $date2);
    $month1 = date('m', $date1);
    $month2 = date('m', $date2);
    return (($year2 - $year1) * 12 + ($month2 - $month1));
}

function yearsBetween($date1, $date2) {
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
    $year1 = date('Y', $date1);
    $year2 = date('Y', $date2);
    return $year2 - $year1;
}

function IsFirstTimeLogin() {
    /*
    getThis()->db->select('first_time_login');
    getThis()->db->where('id', curUsrId());
    getThis()->db->from('aauth_users');
    $rs = getThis()->db->get()->row_array();
    getThis()->db->reset_query();
    return arrIndex($rs, 'first_time_login'); 
    */
    return 0;
}

function getUnitsTypes() {
    return array(
        's' => 'Shop',
        'f' => 'Flat'
    );
}

function addDate($strDate, $interval) {
    $temp = new DateTime($strDate);
    $temp->add(new DateInterval('P1Y'));

//    $temp = (array)$temp;
//    return arrIndex($temp,'date');
}

function getConfig($key) {
    getThis()->db->select('config_value');
    getThis()->db->where('config_key', $key);
    getThis()->db->from('config');
    $rs = getThis()->db->get()->row_array();
    getThis()->db->reset_query();
    return arrIndex($rs,'config_value');
}