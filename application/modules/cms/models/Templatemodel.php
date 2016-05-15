<?php
class Templatemodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	function fetchByID($id, $template_type = CMS_PAGE_TEMPLATE_TYPE) {
		$this->db->where('template_id', intval($id));
		$this->db->where('template_type_id', $template_type);
		$rs = $this->db->get('template');
		if($rs && $rs->num_rows() == 1) return $rs->row_array();

		return FALSE;
	}

	function fetchByAlias($alias) {
		$this->db->where('template_alias', $alias);
		$this->db->where('template_id', 2);
		$rs = $this->db->get('page_template');
		if($rs && $rs->num_rows() == 1) return $rs->row_array();

		return FALSE;
	}

	function listAll($template_type_id = false) {
		if($template_type_id) {
			$this->db->where('template_type_id', $template_type_id);
		}
		$rs = $this->db->get('template');

		return $rs->result_array();
	}

    function listSubTemplates($template_type_id, $mobile, $tid) {
        $this->db->where('parent_id', $tid);
        $this->db->where('template_type_id', $template_type_id);
        if($mobile) {
            $this->db->where('template_ua_target !=', UA_TARGET_DESKTOP);
        }else {
            $this->db->where('template_ua_target !=', UA_TARGET_MOBILE);
        }
        $rs = $this->db->get('template');
        if($rs) return $rs->result_array();

        return false;
    }

	function parseTemplate($tid) {
		$template = $this->fetchByID($tid);
		if(!$template) return FALSE;

		$tpl = $template['template_contents'];

		//Replace Templates
		$matches= array();
		preg_match_all('/\{cms:template:([a-zA-Z\_]+)\}/i', $tpl, $matches);
		$modtagCount= count($matches[1]);
		for ($i= 0; $i < $modtagCount; $i++) {
			$replace = $matches[0][$i];
			$tpl_alias =  $matches[1][$i];
			$sub_template = $this->fetchByAlias($tpl_alias, SUB_TEMPLATE_TYPE);
			if($sub_template) {
				$tpl = str_replace($replace, $sub_template['template_contents'], $tpl);
			}
		}

		return $tpl;
	}
}
?>
