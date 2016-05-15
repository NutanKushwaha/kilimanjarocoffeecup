<?php

function isLogged() {
    $CI = & get_instance();
    $currentClass = $CI->router->fetch_class();
    if (!isset($CI->session->userdata['loggedin']) && $currentClass != 'welcome') {
        redirect();
    }
}

function e($str, $exit = true) {
    echo "<pre>";
    print_r($str);
    if ($exit)
        exit;
}

function p() {
    echo "<pre>";
}

function gParam($index = null, $return = null) {
    $params = $_REQUEST;
    $return = isset($params[$index]) ? $params[$index] : $return;
    return $return;
}

function gAParams($exit = true) {
    e($_REQUEST, $exit);
}

function arrIndex($array, $index, $return = null) {
    return isset($array[$index]) ? $array[$index] : $return;
}

function curModName($object) {
    return $object->router->fetch_module();
}

function com_convertToDecimal($val = "", $decPosition = "0", $decPoint = ".", $thousandSep = ",") {
    return number_format($val, $decPosition, $decPoint, $thousandSep);
}

function getThis() {
    $data = & get_instance();
    return $data;
}

function convertToAuthStr($index) {
    $object = getThis();
    return arrIndex($object->aauth->config_vars, $index);
}

function createUrl($url) {
    return base_url() . $url;
    //return base_url() . 'index.php/' . $url;
}

function checkFile($config, $filename) {
    if (is_file($config . $filename)) {
        return $config . $filename;
    } else {
        return getThis()->config->Item('UPLOAD_URL') . 'default.jpg';
    }
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
        if (!gParam($field))
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

function curUsrId() {
    if (getThis()->aauth->isCustomer()):
        return getThis()->session->userdata('applicant_id');
    else:
        return getThis()->session->userdata('id');
    endif;
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

function latestNews($limit) {
    $CI = & get_instance();
    $CI->db->order_by('news_date', 'DESC');
    $CI->db->limit($limit);
    $query = $CI->db->get('news');
    return $query->result_array();
}

function latestFeed($numrows = 5) {
    $output = feed($numrows);
    return $output;
}

function feed($fid = NULL) {
    $inner = $page = array();
    $param['noofrows'] = $fid;
    $inner['output'] = call($param);
    return $inner;
//        $page['content'] = $this->load->view('franchiseorders', $inner, TRUE);
//        $this->load->view($this->default, $page);
}

function call($params = array()) {
    $params = http_build_query($params);
    $ch = curl_init();
//        echo $this->url . $url;
//        die;        
    curl_setopt($ch, CURLOPT_URL, 'http://news.thecreationstation.co.uk/test/');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
//        return $server_output;
    return json_decode($server_output, 1);
}

function getCompany($id) {
    getThis()->db->select('*')
            ->from('units')
            ->join('aauth_users', 'units.company_id=aauth_users.id');

    $result = getThis()->db->get();
    //  echo getThis()->db->last_query();
    $result = $result->row();
    return $result;
}

function getAttributes() {
    return [];
    $results = getThis()
            ->db
            ->from('units_attributes')
            ->where('searchable', 1)
            ->get()
            ->result_array();
    return $results;
}

function getAttributesValue($id) {
    $result = getThis()->db->where('dropdown_id', $id)->get('units_attributes_dropdown')->result_array();
    return $result;
}

function lQ() {
    e(getThis()->db->last_query());
}

function total_after_calcualtion()
{
    $CI = &get_instance();
    $total = $CI->cart->total();
    $coupon_details = $CI->session->userdata('coupon');
    if ($coupon_details) {
        if ($coupon_details['vstyle'] == "value") {
            $total = $total - $coupon_details['amount'];
        } elseif ($coupon_details['vstyle'] == "percentage") {
            $total = $total - ($total * $coupon_details['amount'] / 100);
        }
    }
    return round(($total + $CI->session->userdata('donationAmount')), 2);
}
