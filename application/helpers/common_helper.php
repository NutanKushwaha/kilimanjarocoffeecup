<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('com_getThis')) {

    /* Return codeigniter instance */

    function com_getThis() {
        $data = & get_instance();
        return $data;
    }

    /* display last run query */

    function com_lquery($exit = false) {
        com_e(com_getThis()->db->last_query(), $exit);
    }

    /* display passed param and exit on true pass */

    function com_e($params, $exit = 1) {

        echo "<pre>";
        print_r($params);
        echo "</pre>";

        if ($exit)
            exit;
    }

    /* return field value either from request or get fields */

    function com_gParam($index = null, $fromGET = false, $default = null) {
        $CI = com_getThis();
        if ($fromGET) {
            $params = $CI->input->get($index);
        } else {
            $params = $CI->input->get_post($index, true);
        }
        $return = $params ? $params : $default;
        return $return;
    }

    /* return array index or default value */

    function com_arrIndex($array, $index, $default = null) {
        return (isset($array[$index]) && $array[$index]) ? $array[$index] : $default;
    }

    /* return & build html from nested array */

    function com_makeDropDownList($nested = array(), &$html = '', $selected = '') {
        foreach ($nested as $key => $value) {
            if (is_array($value) && sizeof($value) && !isset($value['text'])) {
                $html .= '<optgroup label="' . str_repeat('&#160;', ($value['depth'] * 2)) . $key . '">';
                makeDropDownList($value, $html, $selected);
                $html .= '</optgroup>';
            } else if (isset($value['text'])) {
                $html .= '<option value="' . $key . '" ' . ( $key == $selected ? "selected" : "") . '>' . str_repeat('&#160;', ($value['depth'] * 2)) . $value['text'] . '</option>';
            }
        }
    }

    /* return and generate random password */

    function com_generate_random_password($length = 10) {

        $numbers = range('0', '9');
        $ucase_alphabets = range('A', 'Z');
        $scase_alphabets = range('a', 'z');
        $customer_chars = ['-', '_', '.', ','];
        $full_chars = array_merge($ucase_alphabets, $customer_chars, $scase_alphabets, $numbers);
        shuffle($full_chars);
        $password = '';
        while ($length--) {
            $password.=$full_chars[array_rand($full_chars)];
        }
        return $password;
    }

    /* return pagination via passed variable */

    function com_pagination($config = array('per_page' => '', 'base_url' => '',
        'total_rows' => '', 'uri_segment' => '')) {

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a><b>';
        $config['cur_tag_close'] = '</a></b></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';

        if ((int) ($config['per_page']) > 0 && !empty($config['uri_segment']) && !empty($config['base_url']) && (int) ($config['total_rows']) > 0) {

            $config['base_url'] = base_url() . $config['base_url'];
            $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
            $config['full_tag_close'] = '</ul>';

            $CI = com_getThis();
            $CI->pagination->initialize($config);
            return $CI->pagination->create_links();
        }
        return false;
    }

    /* return ajax pagination via passed variable */

    function com_ajax_pagination($param = []) {

        $config['cur_page'] = '';
        $config['total_rows'] = '';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a><b>';
        $config['cur_tag_close'] = '</a></b></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['base_url'] = current_url();

        /* 	$config['per_page']    = '';
          $config['html_container'] = 'prod-comp-list-table';
          $config['form_serialize'] = 'product-company-assign';
         */

        $config = array_replace($config, $param);
        $CI = com_getThis();
        $CI->ajax_pagination->initialize($config);
        return $CI->ajax_pagination->create_links();
    }

    /* return & build html from resultset */

    function com_makelistElem($rstSet, $Key_index, $Key_text, $forSelect = true, $defaultSel = 'Select') {
        $result = '';
        if ($forSelect) {
            $result .= '<option value="">' . $defaultSel . '</option>';
        }
        foreach ($rstSet as $rstSetRow) {
            /*
             * Need to check "if" working.
             */
            if (!array_key_exists($Key_index, $rstSetRow)
                    or ! array_key_exists($Key_text, $rstSetRow)) {
                continue;
            }
            $result .= '<option value="' . $rstSetRow[$Key_index] . '">' . $rstSetRow[$Key_text] . '</option>';
        }
        return $result;
    }

    /* return & build codeigniter dropdown array from resultset */

    function com_makelist($rstSet, $Key_index, $Key_text, $forSelect = true, $defaultSel = 'Select') {
        $result = [];
        if ($forSelect) {
            $result[] = $defaultSel;
        }
        foreach ($rstSet as $rstSetRow) {
            /* confirm both index should exist */
            if (!array_key_exists($Key_index, $rstSetRow) or ! array_key_exists($Key_text, $rstSetRow)) {
                continue;
            }
            $result[$rstSetRow[$Key_index]] = $rstSetRow[$Key_text];
        }
        return $result;
    }

    /* return & build array with custom index passed */

    function com_makeArrIndexToField($arr, $KeyIndex) {
        $result = [];
        foreach ($arr as $arrIndex => $arrDet) {
            if (!array_key_exists($KeyIndex, $arrDet)) {
                continue;
            }
            $result[$arrDet[$KeyIndex]] = $arrDet;
        }
        return $result;
    }

    /* return & build array with custom fields array passed */

    function com_makeArrIndexToArrayFieldComb($arrStack, $keyArr, $isMulti = fale, $makeArray = false, $defaultGlue = ":") {
        $result = [];
        /* get all keys */
        $arrStackKeys = $isMulti && $arrStack ? array_keys($arrStack[0]) : array_keys($arrStack);
        /* get diff between passed keyStack with arrStackKeys */
        $keysCheck = array_diff($keyArr, $arrStackKeys);
        /* No difference */
        if (!$keysCheck) {

            $customkey = '';
            $custKIndex = 1;
            $custKCount = count($keyArr);

            foreach ($keyArr as $key => $value) {
                $keyGlue = $defaultGlue;
                if ($custKIndex == $custKCount) {
                    $keyGlue = "";
                }
                $customkey .= '{$arrStackDet["' . $value . '"]}' . $keyGlue;
                $custKIndex++;
            }
            /* loop on result set and assign customer key index to result and assign value */
            foreach ($arrStack as $arrStackIndex => $arrStackDet) {
                eval("\$customDynKey= \"{$customkey}\";");
                if ($makeArray) {
                    $result[$customDynKey][] = $arrStackDet;
                } else {
                    $result[$customDynKey] = $arrStackDet;
                }
            }
        }
        return $result;
    }

    /* return login username */

    function com_logInName() {
        return com_getThis()->session->userdata['flexi_auth']['custom_user_data']['uacc_username'];
    }

    /* return login user picture */

    function com_logInPic($is_resized = false, $resized_id = false) {
        $user_exist_path = false;
        $user_pic = com_getThis()->session->userdata['flexi_auth']['custom_user_data']['upro_image'];
        if ($user_pic) {
            $user_img_path = com_getThis()->config->config['UPLOAD_USERS_IMG_PATH'] . $user_pic;
            if ($is_resized) {
                $user_img_path = com_getThis()->config->config['UPLOAD_USERS_RESIZE_IMG_PATH'] . $resized_id . '/' . $user_pic;
            }
            if (file_exists($user_img_path)) {
                $user_exist_path = $user_pic;
            }
        }
        return $user_exist_path;
    }

    /* return is user pic exist */

    function com_check_user_img_exist($user_image) {
        $user_exist_path = false;

        $user_img_path = com_getThis()->config->config['UPLOAD_USERS_IMG_PATH'] . $user_image;
        if (file_exists($user_img_path) && $user_image) {
            $user_exist_path = true;
        }
        return $user_exist_path;
    }

    /* return loged user id */

    function com_curUsrId() {
        return com_getThis()->session->userdata('id');
    }

    /* return is passed menu array is module */

    function _menu_mod_check($menu) {
        if ($menu['module_menu']) {
            if ((int) $menu['is_module_menu'])
                return $menu;
        } else {
            return $menu;
        }
    }

    /* return menu */

    function com_getMenu() {
        $CI = com_getThis();
        $module_name = $CI->router->fetch_module();
        if (!$module_name or is_null($module_name)) {
            $module_name = '';
        }

        $user_goup = $CI->flexi_auth->get_user_group();

        if (empty($user_goup) && empty($module_name)) {
            redirect('welcome/logout');
            exit();
        }
        $user_privileges = $CI->flexi_auth->get_all_privilegs();
        if (!$user_privileges) {
            $user_privileges = [ ''];
        }

        $sub_query = $CI->db->select('sum(is_module_menu)')
                ->from('admin_inner_menu')
                ->where('refer', $module_name)
                ->get_compiled_select();

        $selected_menus = $CI->db->select('*, (' . $sub_query . ') as module_menu')
                        ->from('admin_inner_menu')
                        ->where('refer', $module_name)
                        ->or_where('refer', $user_goup)
                        ->where('active', 1)
                        ->where_in('privileges', $user_privileges)
                        ->order_by('menu_order')
                        ->get()->result_array();


        $filtered_menu = array_filter($selected_menus, "_menu_mod_check");

        return $filtered_menu;
    }

    /* return make ajax req data from array */
    /* initial used from ajax pagination library */

    function com_make_ajax_req_data($ajax_data = []) {
        $data_html_json = "{";
        foreach ($ajax_data as $data_key => $data_value) {
            foreach ($data_value as $act_key => $act_data) {
                foreach ($act_data as $act_data_key => $act_data_value) {
                    if ($act_key == 'js') {
                        $data_html_json .= "'" . $act_data_key . "':" . $act_data_value . ",";
                    } else if ($act_key == 'str') {
                        $data_html_json .= "'" . $act_data_key . "':'" . $act_data_value . "',";
                    }
                }
            }
        }
        $data_html_json .= "}";
        return $data_html_json;
    }

    /* return product image */

    function com_get_image($imageURL = 'PRODUCT_IMAGE_URL', $imagePATH = 'PRODUCT_IMAGE_PATH', $resizeUrl = "PRODUCT_RESIZE_IMAGE_URL", $resizePath = 'PRODUCT_RESIZE_IMAGE_PATH', $product_img, $width, $height, $default_img = '') {
        $CI = com_getThis();
        if (!$product_img) {
            if ($default_img) {
                $product_img = $default_img;
            } else {
                $product_img = 'default.jpg';
            }
        }
        if (file_exists($CI->config->config[$imagePATH] . $product_img)) {

            $image_url = $CI->config->config[$imageURL] . $product_img;
        } else {

            $image_url = $CI->config->config[$imageURL];
        }
        //$image_url = $CI->config->config[$imageURL].$product_img;
        $resize_image_url = $CI->config->config[$resizeUrl];
        $resize_image_path = $CI->config->config[$resizePath];
        $default_picture = $CI->config->config['UPLOAD_URL'] . 'default.jpg';
        if ($default_img) {
            
            $default_picture = $CI->config->config['UPLOAD_URL'] . $default_img;
        }
        $photoset_id = '';
        $params = [ 'image_url' => $image_url,
            'image_path' => $CI->config->config[$imagePATH] . $product_img,
            'resize_image_url' => $resize_image_url,
            'resize_image_path' => $resize_image_path,
            'width' => $width,
            'height' => $height,
            'photoset_id' => $photoset_id,
            'default_picture' => $default_picture
        ];
        return resize($params);
    }

    /* set reference passed variable to default value if is null */

    function com_changeNull(&$field, $defVal = 0) {
        if (is_null($field)) {
            $field = $defVal;
        }
    }

    /*  return stack array value by check passed key and specific value exist  */

    function com_searchArrKeyValPair($stack, $searchkey, $searchval) {
        $tmp = [];
        foreach ($stack as $stackK => $stackV) {
            if (isset($stackV[$searchkey]) && $stackV[$searchkey] == $searchval) {
                $tmp[] = $stackV;
            }
        }
        return $tmp;
    }

    function com_arrayToObject($arrVariable){
        if (is_array( $arrVariable )) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return (object) array_map(__FUNCTION__, $arrVariable);
        }
         else {
            // Return object
            return $arrVariable;
         }
    }

    function com_objectToArray($objVariable){
        if (is_object( $objVariable )) {
             // Gets the properties of the given object
             // with get_object_vars function
            $d = get_object_vars( $objVariable );
         }
         
         if (is_array( $objVariable )) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $objVariable);
         }
         else {
            // Return array
            return $objVariable;
         }      
    }
}
