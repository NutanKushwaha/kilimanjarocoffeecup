<?php

class Translatemodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //Get page details
    function detail($pid) {
        $this->db->from('page');
        $this->db->where('page.page_id', intval($pid));
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //Count All Records
    function countAll() {
        $this->db->from('page');
        return $this->db->count_all_results();
    }

    //List All Records
    function listAll($page, $offset = FALSE, $limit = FALSE) {
        if ($offset) $this->db->offset($offset);
        if ($limit) $this->db->limit($limit);

        $this->db->from('page');
        $this->db->join('language', 'language.language_code = page.language_code', 'LEFT');
        $this->db->where('translation_of', $page['page_id']);
        $this->db->where('page.language_code != ', 'en');
        $rs = $this->db->get();
        return $rs->result_array();
    }

    //List All languages
    function listAllLanguages() {
        $this->db->from('language');
        //$this->db->join('page', 'page.language_code = language.language_code');
        //$this->db->where('page_uri', $page['page_uri']);
        $this->db->where('language_code != ', 'en');
        $rs = $this->db->get();
        return $rs->result_array();
    }

    //List All pages
    function listAllPage($page, $offset = FALSE, $limit = FALSE) {
        if ($offset) $this->db->offset($offset);
        if ($limit) $this->db->limit($limit);

        $this->db->from('page');
        $this->db->where('language_code != ', 'en');
        $this->db->where('page_uri', $page['page_uri']);
        $rs = $this->db->get();
        return $rs->result_array();
    }

    //add record
    function insertRecord($lang, $page_details, $blocks) {
        $data = array();
        $data['parent_id'] = $page_details['parent_id'];
        $data['page_type_id'] = $page_details['page_type_id'];
        $data['translation_of'] = $page_details['page_id'];
        $data['page_title'] = $page_details['page_title'];
        $data['browser_title'] = $page_details['browser_title'];
        $data['page_uri'] = $page_details['page_uri'];
        $data['language_code'] = $lang;
		$data['page_contents'] = $page_details['page_contents'];
        $data['meta_keywords'] = $page_details['meta_keywords'];
        $data['meta_description'] = $page_details['meta_description'];
        $data['before_head_close'] = $page_details['before_head_close'];
        $data['before_body_close'] = $page_details['before_body_close'];
        $data['template_id'] = $page_details['template_id'];
        $data['sort_order'] = $this->getSortOrder($page_details['parent_id']);
        $data['include_in_search'] = $page_details['include_in_search'];
        $data['include_in_sitemap'] = $page_details['include_in_sitemap'];
        $data['priority'] = $page_details['priority'];
        $data['active'] = $page_details['active'];
        $data['do_not_delete'] = 0;
        if ($page_details['parent_id'] == 0) {
            $data['level'] = 0;
            $data['path'] = 0;
        } else {
            $parent_category = $this->detail($page_details['parent_id']);
            $data['level'] = $parent_category['level'] + 1;
            $data['path'] = $parent_category['path'] . '.' . $page_details['parent_id'];
        }
        $this->db->insert('page', $data);
        $page_id = $this->db->insert_id();

        //insert block
        foreach ($blocks as $item) {
            if ($item['page_version_id'] == 0) {
                $block = array();
                $block['page_id'] = $page_id;
                $block['block_title'] = $item['block_title'];
                $block['block_alias'] = $item['block_alias'];
                $block['block_image'] = '';
                $block['block_contents'] = $item['block_contents'];
                $block['block_template'] = $item['block_template'];
                $block['is_main'] = $item['is_main'];
                $this->db->insert('block', $block);
				
				$block_id = $this->db->insert_id();
				
				// if block template then make a file og block content of name based on page_id and block_id
				if ($block['block_template']) {
					$block_template = $block['block_template'];
					$file_name = $page_id . '_' .$block_id. '.php';					
                    $status = file_put_contents(    APPPATH."../views/" . THEME . "/blocks/".$file_name, $block_template);
					if (!$status) {
                        show_error('<p class="err">The system was unable to copy Block Template  for Page!</p>');
                        return FALSE;
                    }
				}
            }
        }
    }

    //function for delete record
    function deleteRecord($page_details) {

        //fetch  all the child of the page and set all child as root
        $this->db->where('parent_id', $page_details['page_id']);
        $rs = $this->db->get('page');
        $child = $rs->result_array();
        if (count($child > 0)) {
            foreach ($child as $item) {

                $data = array();
                $data['parent_id'] = '0';
                $data['level'] = 0;
                $data['path'] = 0;

                $this->db->where('page_id', $item['page_id']);
                $this->db->update('page', $data);
            }
        }

        $this->db->where('page_id', $page_details['page_id']);
        $this->db->delete('page');
        return;
    }

    function removeBanner($page) {
        //delete the  image
        $path = $this->config->item('PAGE_BANNER_PATH');
        $filename = $path . $page['banner_image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }

        $data = array();

        $data['banner_image'] = '';

        $this->db->where('page_id', $page['page_id']);
        $this->db->update('page', $data);
    }

    //function to get sort order
    function getSortOrder($pid) {
        $this->db->select_max('sort_order');
        $this->db->where('parent_id', intval($pid));
        $query = $this->db->get('page');
        $sort_order = $query->row_array();
        return $sort_order['sort_order'] + 1;
    }

    //function for page alias
    function _slug($pname, $lang) {
        //print_r($lang); exit();
        $page_name = ($pname) ? $pname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $page_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);
        $this->db->limit(1);
        $this->db->where('page_alias', $slug);
        $this->db->where('language_code', $lang);
        $rs = $this->db->get('page');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('language_code', $lang);
                $this->db->where('page_alias', $alt_slug);
                $rs = $this->db->get('page');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }

    //function for Company video alias
    function _slugvideo($vname) {
        //print_r($lang); exit();
        $video_name = ($vname) ? $vname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $video_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);
        $this->db->limit(1);
        $this->db->where('video_alias', $slug);
        $rs = $this->db->get('company_video');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('video_alias', $alt_slug);
                $rs = $this->db->get('company_video');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }

}

?>