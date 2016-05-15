<?php

class Slideshowmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //Get slide show details
    function getDetail($sid) {
        $this->db->where('slideshow_id', intval($sid));
        $rs = $this->db->get('slideshow');
        if ($rs->num_rows() == 1)
            return $rs->row_array();

        return FALSE;
    }

    //Count All Records
    function countAll() {
        $this->db->from('slideshow');
        return $this->db->count_all_results();
    }

    //function list all slide show
    function listAll($select = false) {
        if($select) {
           $this->db->where('active', 1); 
        }
        $query = $this->db->get('slideshow');
        return $query->result_array();
    }
    
    //function insert record
    function insertRecord() {
        $data = array();
        $data['slideshow_title'] = $this->input->post('slideshow_title', TRUE);
        if ($this->input->post('slideshow_alias', TRUE) == '') {
            $data['slideshow_alias'] = $this->_slug($this->input->post('slideshow_title', TRUE));
        } else {
            $data['slideshow_alias'] = $this->input->post('slideshow_alias', TRUE);
        }
        $data['active'] = 1;
        $data['slideshow_added'] = time();

        $this->db->insert('slideshow', $data);
    }

    //function update record
    function updateRecord($slideshow) {
        $data = array();
        $data['slideshow_title'] = $this->input->post('slideshow_title', TRUE);
        if ($this->input->post('slideshow_alias', TRUE) == '') {
            $data['slideshow_alias'] = $slideshow['slideshow_alias'];
        } else {
            $data['slideshow_alias'] = $this->input->post('slideshow_alias', TRUE);
        }

        $this->db->where('slideshow_id', $slideshow['slideshow_id']);
        $this->db->update('slideshow', $data);
    }

    //enable slideshow
    function enableRecord($slideshow) {
        $data = array();

        $data['active'] = 1;

        $this->db->where('slideshow_id', $slideshow['slideshow_id']);
        $this->db->update('slideshow', $data);
        return;
    }

    //disable slideshow
    function disableRecord($slideshow) {
        $data = array();
        $data['active'] = 0;
        $this->db->where('slideshow_id', $slideshow['slideshow_id']);
        $this->db->update('slideshow', $data);
        return;
    }

    //function delete slideshow
    function deleteRecord($slideshow) {
        $this->db->where('slideshow_id', $slideshow['slideshow_id']);
        $this->db->delete('slideshow');
    }

    //function slug
    function _slug($sname) {
        $slideshow_title = ($sname) ? $sname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $slideshow_title;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);
        $this->db->limit(1);
        $this->db->where('slideshow_alias', $slug);
        $rs = $this->db->get('slideshow');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('slideshow_alias', $alt_slug);
                $rs = $this->db->get('slideshow');
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