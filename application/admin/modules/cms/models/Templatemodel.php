<?php

class Templatemodel extends CI_Model{
	
	function _construct(){
		parent::__construct();
	}
	
	//Get template details
	function getDetails($tid) {
		$this->db->select('*');
		$this->db->from('page_template');
		$this->db->where('template_id', intval($tid));
		$rs = $this->db->get();
		if($rs->num_rows() == 1) return $rs->row_array();

		return FALSE;
	}
	
	//count all record
	function countAll() {
        $this->db->from('page_template');
        return $this->db->count_all_results();
    }
	
	//list all Record
	function listAll($offset = false, $limit = false) {
		if($offset) $this->db->offset($offset);
		if($limit) $this->db->limit($limit);
		
		$rs = $this->db->get('page_template');
		return $rs->result_array();		
		
	}
	
	//insert record
	function insertRecord() {
		$data = array();
		
		$data['template_name'] = $this->input->post('template_name', TRUE);
		if($this->input->post('template_alias', TRUE)) {
			$data['template_alias'] = $this->input->post('template_alias', TRUE);
		}else{
			$data['template_alias'] = $this->_slug($this->input->post('template_name', TRUE));
		}
		$data['template_contents'] = $this->input->post('template_contents', FALSE);
		
		$file_path = '../application/views/' . THEME . "/templates/".$data['template_alias'].'.php';
		$status = file_put_contents($file_path, $data['template_contents']);
        if (!$status) {
            show_error('<p class="err">The system was unable to copy Block Template  for Page!</p>');
            return FALSE;
        }
		
		$this->db->insert('page_template', $data);
		
		
	}
	
	//update record
	function updateRecord($template) {
		$data = array();
		
		$data['template_name'] = $this->input->post('template_name', TRUE);
		
		//template alias
		if($this->input->post('template_alias', TRUE)) {
			$data['template_alias'] = $this->input->post('template_alias', TRUE);
		}else{
			$data['template_alias'] = $this->_slug($this->input->post('template_name', TRUE));
		}
		
		$data['template_contents'] = $this->input->post('template_contents', FALSE);
		
		//unlink the previous file
		$old_file 	=	APPPATH.'../views/'. THEME . "/templates/".$template['template_alias'].'.php';
		if (file_exists($old_file)) {
			@unlink($old_file);
		}
		
		//put the content in the new file
		$file_path = APPPATH.'../views/' . THEME . "/templates/".$data['template_alias'].'.php';
		$status = file_put_contents($file_path, $data['template_contents']);
        if (!$status) {
            show_error('<p class="err">The system was unable to copy Block Template  for Page!</p>');
            return FALSE;
        }
		
		$this->db->where('template_id', $template['template_id']);
		$this->db->update('page_template', $data);
	}
	
	//delete records
	function deleteRecord($template) {
		//unlink the previous file		
		$old_file 	=	APPPATH.'../views/'. THEME . "/templates/".$template['template_alias'].'.php';
		if (file_exists($old_file)) {
			@unlink($old_file);
		}
		
		$this->db->where('template_id', $template['template_id']);
		$this->db->delete('page_template');
	}
	
	
	//function for page alias
    function _slug($tname) {
        $template_name = ($tname) ? $tname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $template_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        $slug = url_title($slug, 'dash', true);

        $this->db->limit(1);
        $this->db->where('template_alias', $slug);
        $rs = $this->db->get('page_template');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('template_alias', $alt_slug);
                $rs = $this->db->get('page_template');
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
