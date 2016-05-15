<?php

class Pagemodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getOrganization($alias) {
        $r = $this->db->select('t1.upro_id as user_id, t1.unique_url as unique_code, t1.upro_approval_acc as conpany_id, t2.uacc_username as company_name')
                ->from('user_profiles t1')
                ->join('user_accounts t2', 't2.uacc_id=t1.upro_approval_acc', 'left')
                ->where('t2.uacc_group_fk', 2)
                ->where('t1.unique_url', $alias)
                ->get('user_profiles')
                ->row();
        return $r;
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
        $sql .= " FROM " . $this->db->dbprefix('page') . " AS p1 ";
        for ($i = 1; $i < $level; $i++) {
            $j = $i + 1;
            $sql .= " LEFT OUTER JOIN " . $this->db->dbprefix('page') . " AS p$j ON p$i.page_id = p$j.parent_id ";
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

    function getDetailsForPreview($alias, $lang = 'en') {
        $this->db->from('page');
        $this->db->join('page_type', 'page_type.page_type_id = page.page_type_id ');
        $this->db->join('page_template', 'page_template.template_id = page.template_id ');
        $this->db->where('page_uri', $alias);
        $this->db->where('language_code', $lang);
        $this->db->where('active', 1);
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

    function getPageModuleSettings($page, $module_name) {
        $this->db->where('page_id', $page['page_id']);
        $this->db->where('module_name', $module_name);

        $rs = $this->db->get('page_data');
        if ($rs && $rs->num_rows() > 0) {
            $module = new stdClass;
            $module->module_name = $module_name;
            foreach ($rs->result_array() as $row) {
                $module->row['page_setting'] = $row['page_setting_value'];
            }
            return $module;
        }
        return FALSE;
    }

    function listAll() {
        $rs = $this->db->get('page');

        return $rs->result_array();
    }

    function getMainBlock($page_id) {
        //print_r($page_id); exit();
        $this->db->where('page_id', $page_id);
        $this->db->where('is_main', 1);
        $rs = $this->db->get('block');

        return $rs->row_array();
    }

    function getPageBlocks($page_id) {
        $this->db->from('block');
        //$this->db->join('block_layout', 'block_layout.block_layout_id = block.block_layout_id');
        $this->db->join('page_blocks', 'page_blocks.block_id = block.block_id');
        $this->db->where('page_blocks.page_id', $page_id);
        $this->db->where('block_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $rs = $this->db->get();

        return $rs->result_array();
    }

    function getGlobalUnassignBlocks($block = array()) {
        $sql = 'Select * from ' . $this->db->dbprefix('block') . ' as blk'
                . ' where blk.block_id '
                . ' not in (Select distinct block_id from ' . $this->db->dbprefix('page_blocks') . ')'
                . ' and block_active = 1 ';
        if ($block) {
            $sql .= ' and blk.block_alias in (' . implode(',', $block) . ') ';
        }
        $sql .= 'order by sort_order asc';
        $rs = $this->db->query($sql);
        $global_blocks = $rs->result_array();

        $output = array();
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

    function getChildPages($page) {
        $sub_pages = array();
        $this->db->order_by('sort_order', 'ASC');
        $this->db->where('parent_id', intval($page['page_id']));
        $rs = $this->db->get('page');
        if ($rs->num_rows() > 0) {
            $sub_pages = $rs->result_array();
        } else {
            if ($page['parent_id'] > 0) {
                $this->db->order_by('sort_order', 'ASC');
                $this->db->where('parent_id', $page['parent_id']);
                $rs = $this->db->get('page');
                $sub_pages = $rs->result_array();
            }
        }
        return $sub_pages;
    }

    function getGlobalBlocks() {
        $this->db->where('page_id', 0);
        $rs = $this->db->get('block');
        return $rs->result_array();
    }

    function compiledBlocks_b($page) {
        $output = array();

        //Page Blocks
        $blocks = $this->getPageBlocks($page['page_id']);
        foreach ($blocks as $block) {

            $block['block_image_url'] = $this->config->item('BLOCK_URL') . $block['block_image'];
            $block['block_image_path'] = $this->config->item('BLOCK_PATH') . $block['block_image'];
            //$compiled_tpl = $this->load->view(compileBlockTemplate($block), $block, true);
            if (isset($block['char_limit']) && $block['char_limit']) {
                $contentLength = strlen($block['block_contents']);
                $continueStr = '';
                if ($contentLength > $block['char_limit']) {
                    $continueStr = '...';
                }
                $block['block_contents'] = substr($block['block_contents'], 0, $block['char_limit']);
                $block['block_contents'] .= $continueStr;
            }
            $template = $block['block_template'];
            unset($block['block_template']);
            unset($block['block_image']);
            $block_keys = array_keys($block);
            $block_values = array_values($block);
            $output[$block['block_alias']] = str_replace($block_keys, $block_values, $template);
        }
        return $output;
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

    function compiledPage($page, $inner = array()) {
        //Fetch template details
        $this->db->where('template_id', $page['template_id']);
        $rs = $this->db->get('page_template');
        if ($rs->num_rows() != 1)
            return false;

        $row = $rs->row_array();
        $tpl = $row['template_contents'];

        $compiled_tpl = $this->compileTemplate($tpl, $inner);

        return $compiled_tpl;
    }

    function compileTemplate($tpl, $data = false) {
        //  print_R($tpl); exit();
        if ($data) {
            extract($data);
        }
        ob_start();
        echo eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $tpl['template_alias'])));
        $buffer = ob_get_contents();
        //var_dump($buffer); exit();
        @ob_end_clean();
        return $buffer;
    }

    function ulList($parent, $output = '') {
        $this->db->where('parent_id', $parent);
        $this->db->where('active', 1);
        $this->db->join('block', 'page.page_id = block.page_id');
        $this->db->where('show_in_menu', 1);
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('page');
        if ($query->num_rows() > 0) {
            if ($parent == 0) {
                $output .= '<ul class="sf-menu">' . "\r\n";
                //$output .= '<li rel="0">'."\r\n";
            } else {
                $output .= "<ul>\r\n";
            }
            foreach ($query->result_array() as $row) {
                if ($row['page_type'] == 'Link') {
                    $output .= '<li rel="' . $row['page_id'] . '"><a href="' . $row['block_contents'] . '">' . $row['menu_title'] . "</a>\r\n";
                } else {
                    $output .= '<li rel="' . $row['page_id'] . '"><a href="' . base_url() . $row['page_alias'] . '.html">' . $row['menu_title'] . "</a>\r\n";
                    $output = $this->ulList($row['page_id'], $output);
                    $output .= "</li>\r\n";
                }
            }
            $output .= "</ul>\r\n";
        }
        return $output;
    }

    function shortContent(&$block = null) {
        if (isset($block['char_limit']) && $block['char_limit']) {
            $contentLength = strlen($block['block_contents']);
            $continueStr = '';
            if ($contentLength > $block['char_limit']) {
                $continueStr = '...';
            }
            $block['block_contents'] = substr($block['block_contents'], 0, $block['char_limit']);
            $block['block_contents'] .= $continueStr;
        }
    }

}

?>
