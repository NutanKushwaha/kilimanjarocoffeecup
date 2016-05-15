<?php

class Newsmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //function get news details
    function getDetails($alias) {
        $this->db->from('news');
        $this->db->where('url_alias', $alias);
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //function recent news
    function listLatestNews($limit) {

        $this->db->order_by('news_date', 'DESC');
        $this->db->limit($limit);
        $rs = $this->db->get('news');

        return $rs->result_array();
    }

    //Count All case studies
    function countAll() {
        $this->db->from('news');
        return $this->db->count_all_results();
    }

    function listAll($offset = false, $limit = false) {

        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $this->db->order_by('news_date', 'DESC');
        $query = $this->db->get('news');
        return $query->result_array();
    }

}

?>
