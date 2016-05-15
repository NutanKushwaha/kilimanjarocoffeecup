<?php

class Pagemodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function breadcrumbs($pid) {
        $this->db->where('page_id', $pid);
        $rs = $this->db->get('page');
        $page = $rs->row_array();
        $level = ($page ? $page['level'] : 0) + 1;
        $sql = "SELECT ";
        for ($i = 1; $i <= $level; $i++) {
            $sql .= "p$i.page_title as page$i, p$i.page_uri as page_uri$i,";
        }

        $sql = rtrim($sql, ',');
        $sql .= " FROM ".$this->db->dbprefix('page')." AS p1 ";
        for ($i = 1; $i < $level; $i++) {
            $j = $i + 1;
            $sql .= " LEFT OUTER JOIN ".$this->db->dbprefix('page')." AS p$j ON p$i.page_id = p$j.parent_id ";
        }
        $sql .= " WHERE p$level.page_id=$pid";

        $rs = $this->db->query($sql);
        //echo $sql;
        $row = $rs->row_array();


        $crumbs = array();
        $chunks = array_chunk($row, 2);
        foreach ($chunks as $chunk) {
            $crumbs[] = '<a href="' . $chunk[1] . '">' . $chunk[0] . '</a>';
        }

        return $crumbs;
    }

    //get all languages
    function getAllLanguages($page, $lang) {
        $this->db->join('language', 'language.language_code = page.language_code');
        $this->db->where('page_uri', $page['page_uri']);
        $this->db->where('page.language_code !=', $lang);
        $this->db->order_by('language', 'ASC');
        $rs = $this->db->get('page');

        return $rs->result_array();
    }

    //function get page details
    function getDetails($alias, $lang = 'en') {
        $this->db->from('page');
        $this->db->join('page_type', 'page_type.page_type_id = page.page_type_id ');
        $this->db->join('page_template', 'page_template.template_id = page.template_id ');
        $this->db->where('page_uri', $alias);
        $this->db->where('language_code', $lang);
        $this->db->where('active', 1);
        $this->db->where('page_status !=', 'Draft');
        $rs = $this->db->get();
        if ($rs->num_rows() != 1) {
            return FALSE;
            if ($lang != 'en') {
                $this->db->from('page');
                $this->db->where('page_uri', $alias);
                $this->db->where('language_code', 'en');
                $this->db->where('active', 1);
                $rs = $this->db->get();
                if ($rs->num_rows() != 1)
                    return false;
            }else {
                return false;
            }
        }


        $page = $rs->row_array();
        return $page;
    }

    //Get page details
    function detail($pid) {
        $this->db->from('page');
        $this->db->join('page_type', 'page_type.page_type_id = page.page_type_id');
        $this->db->join('page_template', 'page_template.template_id = page.template_id');
        $this->db->where('page.page_id', intval($pid));
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //Get page details
    function getPageData($page, $page_setting) {
        $this->db->where('page_id', $page['page_id']);
        $this->db->where('page_setting', $page_setting);
        $rs = $this->db->get('page_data');
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //Get page version  details
    function getPageDetail($pid) {
        $this->db->from('page');
        $this->db->join('block', 'block.page_id = page.page_id');
        $this->db->where('is_main', 1);
        $this->db->where('page.page_id', intval($pid));

        $rs = $this->db->get();
        if ($rs->num_rows() != 0) {
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
    function listAll($offset = FALSE, $limit = FALSE) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $this->db->order_by('page_title', 'ASC');
        $rs = $this->db->get('page');
        return $rs->result_array();
    }

    //List CMS Template
    function listAllTemplate() {
        $this->db->order_by('template_name', 'ASC');
        $rs = $this->db->get('page_template');
        return $rs->result_array();
    }

    //List All Records
    function listAllLanguage($offset = FALSE, $limit = FALSE) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $rs = $this->db->get('language');
        return $rs->result_array();
    }

    //get language
    function getLanguage($lang_code) {
        $this->db->where('language_code', $lang_code);
        $rs = $this->db->get('language');
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //function to list all pages in indented
    function listAllIndented() {
        $this->db->where('level', 0);
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('page');
        return $query->result_array();
    }

    //function to list all pages in indented
    function listAllpages($parent, &$output = array()) {
        $this->db->where('parent_id', $parent);
        $this->db->where('user_id', 0);
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('page');
        foreach ($query->result_array() as $row) {
            $output[] = $row;
            $this->indentedActiveList($row['page_id'], false, $output);
        }
        return $output;
    }

    //find the indented list of pages
    function indentedActiveList($parent, $exclude = false, &$output = array()) {
        $this->db->where('user_id', 0);
        $this->db->where('language_code', 'en');
        $this->db->where('parent_id', $parent);
        if ($exclude) {
            $this->db->where('page_id !=', $exclude);
        }
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('page');
        foreach ($query->result_array() as $row) {
            $output[] = $row;
            $this->indentedActiveList($row['page_id'], $exclude, $output);
        }
        return $output;
    }



    function pageItemTree($ids = array()) {
        $this->db->join('language', 'language.language_code = page.language_code', 'LEFT');
        if (count($ids)) {
            $this->db->where_in('page.user_id', $ids);
        } else {
            $this->db->where('page.user_id', 0);
        }
        $this->db->where('page.language_code', 'en');
        $this->db->order_by('parent_id', 'ASC');
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('page');
        $pages_arr = $query->result_array();
        $pages = array();
        foreach ($pages_arr as $page) {
            $pages[$page['parent_id']][] = $page;
        }
        if ($pages) {
            return $this->_pageItemTree($pages, 0);
        }
    }

    //function page link tree
    function _pageItemTree($pages, $parent, $output = '') {
        if ($parent == 0) {
            $output .= '<ul id="pagetree">' . "\r\n";
        } else {
            $output .= "<ul>\r\n";
        }
        foreach ($pages[$parent] as $row) {
            //link edit
            $edit_href = 'cms/page/edit/' . $row['page_id'] . '/2';

            //language
            $language = ' <span style="padding-left: 20px; color: #bbb">' . $row['page_uri'] . ' ';
            if ($row['language_code'] != 'en') {
                $language .= ' (' . $row['language'] . ')';
            }
            $language .= '</span>';

            //link delete
            $del_href = 'cms/page/delete/' . $row['page_id'];

            //link blockd
            $block_href = 'cms/block/index/' . $row['page_id'];

            //link duplicate page
            $duplicate_href = 'cms/page/duplicate/' . $row['page_id'];

            //link translate page
            $translate_href = 'cms/translate/index/' . $row['page_id'];

            //link split versions
            $splitversion_href = 'page_version/index/' . $row['page_id'];

            //Widgets Link
            $widgets_href = 'cms/widgets/index/' . $row['page_id'];                        
            //Preview Link
            $preview_href = $this->config->item('site_url').'cms/preview/index/'.
                            $this->encrypt->encode(time(), "P43v1311").'/'.$row['page_uri'];

            //links
            $style = '';
            $links = array();

            if ($row['do_not_delete'] == 1) {
                $style = "color: #d81c54;";
                $links[] = anchor($block_href, 'Blocks');
                $links[] = anchor($widgets_href, 'Widgets');
                $links[] = anchor($edit_href, 'Edit');
            } else {
                //$links[] = anchor($duplicate_href, 'Duplicate Page');
                //$links[] = anchor($translate_href, 'Translate');
                //$links[] = anchor($splitversion_href, 'Split Versions');
                //$links[] = anchor($block_href, 'Blocks');
                //$links[] = anchor($widgets_href, 'Widgets');                
                //$links[] = anchor($preview_href, 'Preview', ' target="_blank" ');
                $links[] = anchor($edit_href, 'Edit');
                $links[] = anchor($del_href, 'Delete', ' onclick="return confirm(\'Are you sure you want to Delete this Link ?\');"');
            }

            $links_str = join(' | ', $links);



            //$output .= '<li id="page_' . $row['page_id'] . '"><div class="page_item"><div class="page_item_name"><a href="' . $edit_href . '">' . $row['page_title'] . "</a></div>  " . $language . " <div class=\"page_item_options\"><a href=\"" . $translate_href . "\">Translate</a> | <a href=\"" . $duplicate_href . "\">Duplicate Page</a> | <a href=\"" . $block_href . "\">Manage Blocks</a> | <a href=\"" . $edit_href . "\">Edit</a> | <a href=\"" . $del_href . "\" onclick=\"return confirm('Are you sure you want to Delete this Link ?');\">Delete</a> </div></div>";
            //$output .= '<li id="page_' . $row['page_id'] . '"><div class="page_item"><div class="page_item_name"><a href="' . $edit_href . '" style="' . $style . '">' . $row['page_title'] . "</a></div>  " . $language . " <div class=\"page_item_options\">$splitversion_link $translate_link $duplicate_link <a href=\"" . $block_href . "\">Manage Blocks</a> | <a href=\"" . $edit_href . "\">Edit</a> $del_link </div></div>";
            $output .= '<li id="page_' . $row['page_id'] . '"><div class="page_item"><div class="page_item_name"><a href="' . $edit_href . '" style="' . $style . '">' . $row['page_title'] . "</a></div>  " . $language . " <div class=\"page_item_options\">$links_str</div></div>";
            if (!empty($pages[$row['page_id']])) {
                $output = $this->_pageItemTree($pages, $row['page_id'], $output);
            }
            $output .= "</li>\r\n";
        }
        $output .= "</ul>\r\n";
        return $output;
    }

    //add record
    function insertRecord($lang, $userType) {
        $parent = false;
        if ($this->input->post('parent_id', true) > 0) {
            $parent = $this->detail($this->input->post('parent_id', true));
        }

        $data = array();
        $data['page_title'] = $this->input->post('page_title', TRUE);
        $data['page_status'] = $this->input->post('page_status', TRUE);
        $data['parent_id'] = $this->input->post('parent_id', TRUE);
        com_changeNull( $data['parent_id'] );
        $data['page_type_id'] = 1;
        if ($this->input->post('browser_title', TRUE) == '') {
            $data['browser_title'] = $this->input->post('page_title', TRUE);
        } else {
            $data['browser_title'] = $this->input->post('browser_title', TRUE);
        }

        if ($this->input->post('page_uri', TRUE) == '') {
            $data['page_uri'] = $this->_slug($parent, $this->input->post('page_title', TRUE));
        } else {
            $data['page_uri'] = $this->input->post('page_uri', TRUE);
        }

        $data['language_code'] = $lang;
        $data['template_id'] = $this->input->post('page_template', TRUE);
        //$cont = $this->input->post('contents', FALSE);
        //$data['page_contents'] = str_replace('<img src"admin/', '<img src"', $cont);
        $data['page_contents'] = $this->input->post('description');
        $data['meta_keywords'] = $this->input->post('meta_keywords', TRUE);
        $data['meta_description'] = $this->input->post('meta_description', TRUE);
        $data['before_head_close'] = $this->input->post('before_head_close', FALSE);
        $data['before_body_close'] = $this->input->post('before_body_close', FALSE);
        if ($this->input->post('sort_order', TRUE) == '') {
            $data['sort_order'] = $this->getSortOrder($this->input->post('parent_id', TRUE));
        } else {
            $data['sort_order'] = $this->input->post('sort_order', TRUE);
        }
        $data['include_in_sitemap'] = 0;
        $data['include_in_search'] = 0;
        $data['active'] = 1;
        $data['priority'] = 1;
        if ($this->input->post('parent_id', true) == 0) {
            $data['level'] = 0;
            $data['path'] = 0;
        } else {
            $parent_category = $this->detail($this->input->post('parent_id', true));
            $data['level'] = $parent_category['level'] + 1;
            $data['path'] = $parent_category['path'] . '.' . $this->input->post('parent_id', true);
        }
        $data['user_id'] = $this->flexi_auth->get_user_custom_data( 'uacc_id' );
        $data['user_id'] = 0;
        $status = $this->db->insert('page', $data);
        return $this->db->insert_id();
        if (!$status)
            return false;
    }

    //function update Record
    function updateRecord($page_details) {
        $parent = false;
        if ($this->input->post('parent_id', true) > 0) {
            $parent = $this->detail($this->input->post('parent_id', true));
        }

        $data = array();
        $data['page_title'] = $this->input->post('page_title', TRUE);
        $data['page_status'] = $this->input->post('page_status', TRUE);
        $data['parent_id'] =  $this->input->post('parent_id' , TRUE );
        com_changeNull($data['parent_id'], 0);
        if ($this->input->post('browser_title', TRUE) == '') {
            $data['browser_title'] = $this->input->post('page_title', TRUE);
        } else {
            $data['browser_title'] = $this->input->post('browser_title', TRUE);
        }

        if (( $data['parent_id'] != $page_details['parent_id']) || ($this->input->post('page_uri', TRUE) == '')) {
            $data['page_uri'] = $page_details['page_uri'];
        } else {
            $data['page_uri'] = $this->input->post('page_uri', TRUE);
        }
        //$cont = $this->input->post('contents', FALSE);
        //$data['page_contents'] = $cont;        
        $data['page_contents'] = $this->input->post('contents');
        $data['meta_keywords'] = $this->input->post('meta_keywords', TRUE);
        $data['meta_description'] = $this->input->post('meta_description', TRUE);

        $data['before_head_close'] = $this->input->post('before_head_close', FALSE);
        $data['before_body_close'] = $this->input->post('before_body_close', FALSE);
        if ($this->input->post('page_template', TRUE) == '') {
            $data['template_id'] = $page_details['template_id'];
        } else {
            $data['template_id'] = $this->input->post('page_template', TRUE);
        }

        if ($this->input->post('parent_id', true) == 0) {
            $data['level'] = 0;
            $data['path'] = 0;
        } else {
            $parent_category = $this->detail($this->input->post('parent_id', true));
            $data['level'] = $parent_category['level'] + 1;
            $data['path'] = $parent_category['path'] . '.' . $this->input->post('parent_id', true);
        }       
        $this->db->where('page_id', $page_details['page_id']);        
        $this->db->update('page', $data);
        //save_is_active
        $page_save = array();
        $page_save['save_is_active'] = 0;
        $this->db->where('page_id', $page_details['page_id']);
        $this->db->update('page_save_log', $page_save);

        //save data to wb_page_save_log table
        $save_log = array();
        $save_log['page_id'] = $page_details['page_id'];
        $save_log['page_data'] = base64_encode(serialize($page_details));
        $save_log['block_data'] = '';
        $save_log['page_save_time'] = time();
        $save_log['save_is_active'] = 1;
        $status = $this->db->insert('page_save_log', $save_log);
        return $status;
    }

    //dulicate the records record
    function duplicateRecord($page_detail, $blocks) {
        $parent = false;
        if ($this->input->post('parent_id', true) > 0) {
            $parent = $this->detail($this->input->post('parent_id', true));
        }

        $data = array();
        $data['page_title'] = $this->input->post('page_title', TRUE);
        $data['parent_id'] = $this->input->post('parent_id', TRUE);
        $data['page_type_id'] = $page_detail['page_type_id'];
        $data['browser_title'] = $page_detail['browser_title'];
        $data['page_type_id'] = 1;
        if ($this->input->post('page_uri', TRUE) == '') {
            $data['page_uri'] = $this->_slug($parent, $this->input->post('page_title', TRUE));
        } else {
            $data['page_uri'] = $this->_slug($parent, $this->input->post('page_uri', TRUE));
        }
        $data['template_id'] = $page_detail['template_id'];
        $data['language_code'] = $page_detail['language_code'];
        $data['page_contents'] = $page_detail['page_contents'];
        $data['meta_keywords'] = $page_detail['meta_keywords'];
        $data['meta_description'] = $page_detail['meta_description'];
        $data['before_head_close'] = $page_detail['before_head_close'];
        $data['before_body_close'] = $page_detail['before_body_close'];
        $data['sort_order'] = $this->getSortOrder($page_detail['parent_id']);
        $data['include_in_search'] = $page_detail['include_in_search'];
        $data['include_in_sitemap'] = $page_detail['include_in_sitemap'];
        $data['priority'] = $page_detail['priority'];
        $data['do_not_delete'] = 0;
        $data['active'] = $page_detail['active'];
        if ($page_detail['parent_id'] == 0) {
            $data['level'] = 0;
            $data['path'] = 0;
        } else {
            $parent_category = $this->detail($page_detail['parent_id']);
            $data['level'] = $parent_category['level'] + 1;
            $data['path'] = $parent_category['path'] . '.' . $page_detail['parent_id'];
        }

        $status = $this->db->insert('page', $data);
        if (!$status)
            return false;

        //add main bloak
        $page_id = $this->db->insert_id();


        //Duplicate Blocks
        foreach ($blocks as $item) {
            if ($item['page_version_id'] == 0) {
                $block = array();
                $block['page_id'] = $page_id;
                $block['block_title'] = $item['block_title'];
                $block['block_alias'] = $item['block_alias'];
                $block['block_image'] = '';
                //copy the block images ***********todo*******

                $block['block_contents'] = $item['block_contents'];
                $block['is_main'] = $item['is_main'];
                $block['updated_on'] = time();
                $status = $this->db->insert('block', $block);
                if (!$status) {
                    return false;
                }
                $block_id = $this->db->insert_id();
            }
        }

        //duplicate the translations of this page
        $page_translations = array();
        $page_translations = $this->Translatemodel->listAll($page_detail);
        if ($page_translations) {
            foreach ($page_translations as $translation) {
                $translate_data = array();
                $translate_data['translation_of'] = $page_id;
                $translate_data['parent_id'] = $this->input->post('parent_id', true);
                $translate_data['page_title'] = $data['page_title'];
                $translate_data['page_uri'] = $data['page_uri'];
                $translate_data['page_type_id'] = $data['page_type_id'];
                $translate_data['template_id'] = $translation['template_id'];
                $translate_data['browser_title'] = $translation['browser_title'];
                $translate_data['language_code'] = $translation['language_code'];
                $translate_data['page_contents'] = $translation['page_contents'];
                $translate_data['meta_keywords'] = $translation['meta_keywords'];
                $translate_data['meta_description'] = $translation['meta_description'];
                $translate_data['before_head_close'] = $translation['before_head_close'];
                $translate_data['before_body_close'] = $translation['before_body_close'];
                $translate_data['sort_order'] = $this->getTranSortOrder($translate_data['translation_of']);
                $translate_data['include_in_search'] = $translation['include_in_search'];
                $translate_data['priority'] = $translation['priority'];
                $translate_data['active'] = $translation['active'];
                $translate_data['do_not_delete'] = $translation['do_not_delete'];
                $translate_data['include_in_sitemap'] = $translation['include_in_sitemap'];
                if ($translation['parent_id'] == 0) {
                    $translate_data['level'] = 0;
                    $translate_data['path'] = 0;
                } else {
                    $parent_category = $this->detail($translation['parent_id']);
                    $translate_data['level'] = $parent_category['level'] + 1;
                    $translate_data['path'] = $parent_category['path'] . '.' . $translation['parent_id'];
                }
                $this->db->insert('page', $translate_data);

                $translation_page_id = $this->db->insert_id();

                //fetch the bllocks of ttranslation
                $translation_blocks = array();
                $translation_blocks = $this->Blockmodel->fetchAllBlocks($translation['page_id']);

                foreach ($translation_blocks as $item) {
                    if ($item['page_version_id'] == 0) {
                        $block = array();
                        $block['page_id'] = $translation_page_id;
                        $block['block_title'] = $item['block_title'];
                        $block['block_alias'] = $item['block_alias'];
                        $block['block_image'] = '';
                        //copy block Image todo section**********************

                        $block['block_contents'] = $item['block_contents'];
                        $block['updated_on'] = time();
                        $block['is_main'] = $item['is_main'];
                        $status = $this->db->insert('block', $block);
                        $translation_block_id = $this->db->insert_id();
                    }
                }
            }
        }
    }

    function franchisee($page_detail, $userid) {
        $parent = false;
        $data = array();
        $data['page_title'] = 'Demo';
        $data['user_id'] = $userid;
        $data['parent_id'] = 0;
        $data['page_type_id'] = $page_detail['page_type_id'];
        $data['browser_title'] = 'Demo';
        $data['page_type_id'] = 1;
        $data['page_uri'] = $this->_slug($parent, $userid);
        $data['template_id'] = $page_detail['template_id'];
        $data['language_code'] = $page_detail['language_code'];
        $data['page_contents'] = $page_detail['page_contents'];
        $data['meta_keywords'] = $page_detail['meta_keywords'];
        $data['meta_description'] = $page_detail['meta_description'];
        $data['before_head_close'] = $page_detail['before_head_close'];
        $data['before_body_close'] = $page_detail['before_body_close'];
        $data['sort_order'] = 0;
        $data['include_in_search'] = $page_detail['include_in_search'];
        $data['include_in_sitemap'] = $page_detail['include_in_sitemap'];
        $data['priority'] = $page_detail['priority'];
        $data['do_not_delete'] = 0;
        $data['active'] = $page_detail['active'];
        if ($page_detail['parent_id'] == 0) {
            $data['level'] = 0;
            $data['path'] = 0;
        } else {
            $parent_category = $this->detail($page_detail['parent_id']);
            $data['level'] = $parent_category['level'] + 1;
            $data['path'] = $parent_category['path'] . '.' . $page_detail['parent_id'];
        }

        $status = $this->db->insert('page', $data);
        if (!$status)
            return false;
    }

    //function to get sort order
    function getSortOrder($pid) {
        $this->db->select_max('sort_order');
        $this->db->where('parent_id', intval($pid));
        $query = $this->db->get('page');
        $sort_order = $query->row_array();
        return $sort_order['sort_order'] + 1;
    }

    //function to get sort order for translations
    function getTranSortOrder($pid) {
        $this->db->select_max('sort_order');
        $this->db->where('page_id', intval($pid));
        $this->db->where('page.language_code != ', 'en');
        $query = $this->db->get('page');
        $sort_order = $query->row_array();
        return $sort_order['sort_order'] + 1;
    }

    function generateBlockViewFile($page_id, $block_id, $block_template) {
        $block_template = trim($block_template);
        if ($block_template == '')
            return true;

        $file_name = $page_id . '_' . $block_id . '.php';        
        $status = file_put_contents(    APPPATH."../views/" . THEME . "/blocks/".$file_name, $block_template);
        if (!$status) {
            show_error('<p class="err">The system was unable to copy Block Template  for Page!</p>');
            return FALSE;
        }
    }

    //enable page
    function enableRecord($page_details) {
        $data = array();

        $data['active'] = 1;

        $this->db->where('page_id', $page_details['page_id']);
        $this->db->update('page', $data);
        return;
    }

    //disable page
    function disableRecord($page_details) {
        $data = array();

        $data['active'] = 0;

        $this->db->where('page_id', $page_details['page_id']);
        $this->db->update('page', $data);
        return;
    }

    function enableWidget($page_details, $widget) {
        $data = array();
        $data['page_id'] = $page_details['page_id'];
        $data['widget_id'] = $widget['widget_id'];
        $sort_order = $this->getWidgetSortOrder($page_details['page_id']);
        $data['sort_order'] = $sort_order;
        $this->db->insert('page_widget', $data);

        //setting for the widget
        $class = $widget['widget_class'];
        $this->load->library("widget/$class");
        $this->$class->init($widget);
        $this->$class->install($page_details['page_id']);
    }

    function disableWidget($page_details, $widget) {
        //delete widgets setting
        $class = $widget['widget_class'];
        $this->load->library("widget/$class");
        $this->$class->init($widget);
        $this->$class->uninstall($page_details);

        $this->db->where('page_id', $page_details['page_id']);
        $this->db->where('widget_id', $widget['widget_id']);
        $this->db->delete('page_widget');
    }

    //function to get sort order
    function getWidgetSortOrder($pid) {
        $this->db->select_max('sort_order');
        $this->db->where('page_id', intval($pid));
        $query = $this->db->get('page_widget');
        $sort_order = $query->row_array();
        return $sort_order['sort_order'] + 1;
    }

    //update the child pages when we delete the parent page
    function _updateChildPages($page_id, $lang_code, $parent_id = false) {
        $parent = false;
        $parent = $this->detail($parent_id);

        //Fetch all child pages
        $this->db->where('parent_id', $page_id);
        $this->db->where('language_code', $lang_code);
        $rs = $this->db->get('page');
        if ($rs->num_rows() == 1) {
            foreach ($rs->result_array() as $child) {
                //Update the URI, Level, Path
                $data = array();
                //$data['page_uri'] = $this->_slugEdit($parent, $child['page_title'], $child['language_code']);
                $data['parent_id'] = $parent_id;
                if ($parent_id == 0) {
                    $data['level'] = 0;
                    $data['path'] = 0;
                } else {
                    $data['level'] = $parent['level'] + 1;
                    $data['path'] = $parent['path'] . '.' . $parent['parent_id'];
                }
                $this->db->where('page_id', $child['page_id']);
                $this->db->update('page', $data);

                //Fetch the translations
                $page_translations = array();
                $page_translations = $this->Translatemodel->listAll($child);
                if ($page_translations) {
                    foreach ($page_translations as $translation) {
                        $translations_uri = array();
                        //$translations_uri['page_uri'] = $this->_slugDuplicate($page_uri, $translation['page_id'], $translation['language_code']);
                        //$translations_uri['page_uri'] = $this->_slugEdit($parent, $translation['page_title'], $translation['language_code']);
                        $translations_uri['parent_id'] = $parent_id;
                        if ($parent_id == 0) {
                            $translations_uri['level'] = 0;
                            $translations_uri['path'] = 0;
                        } else {

                            $translations_uri['level'] = $parent['level'] + 1;
                            $translations_uri['path'] = $parent['path'] . '.' . $parent['parent_id'];
                        }

                        $this->db->where('page_id', $translation['page_id']);
                        $this->db->update('page', $translations_uri);
                    }
                }

                $this->_updateChildPages($child['page_id'], $child['language_code'], $child['page_id']);
            }
        }
    }

    //function for delete record
    function deleteRecord($page_details) {
        $this->load->model('Translatemodel');
        $this->load->model('Blockmodel');

        //delete the translation and its blocks
        if ($page_details['language_code'] == 'en') {
            //update the child pages
            $this->_updateChildPages($page_details['page_id'], $page_details['language_code'], $page_details['parent_id']);

            //fetch the translations
            $page_translations = array();
            $page_translations = $this->Translatemodel->listAll($page_details);
            if ($page_translations) {
                foreach ($page_translations as $translation) {
                    //fetch the bllocks of ttranslation
                    $translation_blocks = array();
                    $translation_blocks = $this->Blockmodel->fetchAllBlocks($translation['page_id']);
                    foreach ($translation_blocks as $item) {
                        /* $file_name = $translation['page_id'] . '_' .$item['block_id']. '.php';
                          if (file_exists("../application/views/themes/" . THEME . "/blocks/" . $file_name)) {
                          @unlink("../application/views/themes/" . THEME . "/blocks/" . $file_name);
                          } */

                        //unink the block images
                        $path = $this->config->item('BLOCK_IMAGE_PATH');
                        $filename = $path . $item['block_image'];
                        if (file_exists($filename)) {
                            @unlink($filename);
                        }
                    }

                    //delete the block
                    $this->db->where('page_id', $translation['page_id']);
                    $this->db->delete('block');

                    //delete the translation
                    $this->db->where('page_id', $translation['page_id']);
                    $this->db->delete('page');
                }
            }
        }


        //fetch the bllocks
        $blocks = array();
        $blocks = $this->Blockmodel->fetchAllBlocks($page_details['page_id']);
        foreach ($blocks as $item) {
            /* $file_name = $page_details['page_id'] . '_' .$item['block_id']. '.php';
              if (file_exists("../application/views/themes/" . THEME . "/blocks/" . $file_name)) {
              @unlink("../application/views/themes/" . THEME . "/blocks/" . $file_name);
              } */

            //unink the block images
            $path = $this->config->item('BLOCK_IMAGE_PATH');
            $filename = $path . $item['block_image'];
            if (file_exists($filename)) {
                @unlink($filename);
            }
        }

        //delete the block of this page
        $this->db->where('page_id', $page_details['page_id']);
        $this->db->delete('block');


        $this->db->where('page_id', $page_details['page_id']);
        $this->db->delete('page');
    }

    //function for page alias
    function _slugDuplicate($pname, $pid, $lang) {
        //print_r($lang); exit();
        $page_name = ($pname) ? $pname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $page_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);
        $this->db->limit(1);
        $this->db->where('page_uri', $slug);
        $this->db->where('page_id !=', $pid);
        $this->db->where('language_code', $lang);
        $rs = $this->db->get('page');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('language_code', $lang);
                $this->db->where('page_id !=', $pid);
                $this->db->where('page_uri', $alt_slug);
                $rs = $this->db->get('page');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }

    //function for page alias
    function _slug($parent, $pname) {
        $page_name = ($pname) ? $pname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $page_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        $slug = url_title($slug, 'dash', true);
        if ($parent) {
            $slug = $parent['page_uri'] . '/' . $slug;
        }


        $this->db->limit(1);
        $this->db->where('page_uri', $slug);
        $rs = $this->db->get('page');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('page_uri', $alt_slug);
                $rs = $this->db->get('page');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }

        return $slug;
    }

    //function for page alias
    function _slugEdit($parent, $pname, $lang) {
        $page_name = ($pname) ? $pname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $page_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        $slug = url_title($slug, 'dash', true);
        if ($parent) {
            $slug = $parent['page_uri'] . '/' . $slug;
        }


        $this->db->limit(1);
        $this->db->where('page_uri', $slug);
        $this->db->where('language_code', $lang);
        $rs = $this->db->get('page');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('page_uri', $alt_slug);
                $this->db->where('language_code', $lang);
                $rs = $this->db->get('page');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }

        return $slug;
    }

    function compiledBlocks($page) {

        $output = array();
        $this->db->from('block');
        $this->db->join('page_blocks', 'page_blocks.block_id=block.block_id');
        $this->db->where('page_blocks.page_id', $page['page_id']);
        $rs = $this->db->get();
        $global_blocks = $rs->result_array();
       
        foreach ($global_blocks as $block) {
            $this->shortContent($block);
            $block['img_url'] = $this->config->item('BLOCK_URL') . $block['block_image'];
            $block['img_path'] = $this->config->item('BLOCK_PATH') . $block['block_image'];            
            $file_name = 'global_' . $block['block_alias'] . '.php';

            if (file_exists("application/views/themes/" . THEME . "/blocks/" . $file_name)) {
                $compiled_tpl = $this->load->view("themes/" . THEME . "/blocks/$file_name", $block, true);
            } else {
                $compiled_tpl = $this->load->view("themes/" . THEME . "/blocks/default" . ".php", $block, true);
            }            
            $output[$block['block_alias']] = $compiled_tpl;
        }

        return $output;
    }
}

?>

