<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('cms_meta_tags')) {

    function cms_meta_tags() {
        $CI = & get_instance();
        return $CI->getMeta();
    }

    function cms_browser_title() {
        $CI = & get_instance();
        //return $CI->getBrowserTitle();
    }

    function cms_meta_description() {
        $CI = & get_instance();
        //return $CI->getMetaDescription();
    }

    function cms_meta_keywords() {
        $CI = & get_instance();
        //return $CI->getMetaKeywords();
    }

    function cms_before_head_close() {
        $CI = & get_instance();
        return $CI->getBeforeHeadClose();
    }

    function cms_before_body_close() {
        $CI = & get_instance();
        return $CI->getBeforeBodyClose();
    }

    function cms_head() {
        $CI = & get_instance();
        return $CI->loadHeaders();
    }

    function cms_css($css = false) {
        $CI = & get_instance();
        return $CI->getCSS();
    }

    function cms_js($js = false) {
        $CI = & get_instance();
        return $CI->getJS($js);
    }

    function cms_base_url() {
        $CI = & get_instance();
        return $CI->baseURL();
    }

    function cms_base_url_ssl() {
        $CI = & get_instance();
        return $CI->baseURLSSL();
    }

    function cms_base_url_nossl() {
        $CI = & get_instance();
        return $CI->baseURLNoSSL();
    }

    function cms_footer() {
        $CI = & get_instance();
        return $CI->footer();
    }

    function cms_menu($params) {
        $CI = & get_instance();
        return $CI->menu($params);
    }

    function cms_base_url_with_index() {
        $CI = & get_instance();
        return $CI->baseURL().'index.php';
    }

    function cms_base_url_without_slash(){
        $CI = & get_instance();
        return rtrim( $CI->baseURL() ,"/");        
    }
    
    function compileBlockTemplate($block) {
        $CI = & get_instance();

        //No template, use default
        if ($block['layout_template'] == '') {
            return "themes/" . THEME . "/blocks/default";
        }

        $filename = "{$block['block_id']}.php";
        $filepath = APPPATH . "views/themes/" . THEME . "/compiled/blocks/$filename";
        $mtime = 1;
        if (file_exists($filepath)) {
            $mtime = filemtime($filepath);
        }

        //$mtime = $block['update_time'];
        //Template exists in cache, return cache
        if (file_exists($filepath) && $mtime >= $block['updated_on']) {
            return "themes/" . THEME . "/compiled/blocks/$filename";
        }

        //Store template in cache
        file_put_contents($filepath, $block['layout_template']);

        return "themes/" . THEME . "/compiled/blocks/$filename";
    }

    function getBlockFromTemplate($block) {
        
    }    
    
    function cms_uktous_date($date) {
        //dd/mm/YYYY
        $arr = explode('/', $date);
        return "{$arr[2]}-{$arr[1]}-{$arr[0]}";
    }

    function cms_ustouk_date($date) {
        //YYYY-mm-dd
        $arr = explode('-', $date);
        return "{$arr[2]}/{$arr[1]}/{$arr[0]}";
    }

    function getblock() {
        $ci = & get_instance();
        $ci->db->from('block');
        $global_block = $ci->db->get();
        return $global_block->result_array();
    }

    function cms_getcompany(){
        $ci = & get_instance();
        
      $company =  $ci->session->userdata('company');
      if(isset($company)){
         $conpanyname = $company['company_name'];
            return $conpanyname;
      }
      return FALSE;
    }
}


/* End of file cms_helper.php */
/* Location: ./system/helpers/number_helper.php */