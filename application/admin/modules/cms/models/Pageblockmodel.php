<?php

class PageBlockmodel extends CI_Model {

    protected $tblName;
    
    function __construct() {
        parent::__construct();
        $this->tblName = 'page_blocks';
    }

    //Get All Records
    function getRelateBlock($pageId) {
        if(!$pageId) return false;
        $blocks = $this->db->select($this->tblName.'.*, block.block_alias')
                            ->from($this->tblName)
                            ->join('block', $this->tblName.'.block_id = block.block_id')
                            ->where($this->tblName.'.page_id', $pageId)
                            ->get();
        if($blocks->num_rows()){
            return $blocks->result_array();
        }
        return array();
    }
    
    //Count All Records
    function countAll($pageId) {
        if(!$pageId) return false;;
        $this->db->from($this->tblName)
                ->where('page_id', $pageId);
        return $this->db->count_all_results();
    }
    
    //add record
    function insertRecord($data) {
        if(!$data) return false;        
        $this->db->insert($this->tblName, $data);
    }

    //function for delete record
    function deleteRecord($pageId = 0, $blockId = 0) {
        if(!$pageId || !$blockId){ return false;}
        $this->db->where('block_id', $blockId)
                ->where('page_id', $pageId)
                ->delete($this->tblName);
    }
    
    //function for delete record
    function deleteAllBlocks($pageId = 0) {
        if(!$pageId){ return false;}
        $this->db->where('page_id', $pageId)
                ->delete($this->tblName);
    }
}?>