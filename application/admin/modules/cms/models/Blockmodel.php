<?php

class Blockmodel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    //Get block Detial
    function getDetails($bid) {
        $this->db->join('page', 'page.page_id = block.page_id');
        $this->db->where('block_id', $bid);
        $rs = $this->db->get('block');
        if ($rs->num_rows() == 1)
            return $rs->row_array();

        return FALSE;
    }

    //list all block
    function fetchAllBlocks($pid) {
        $this->db->where('page_id', $pid);
        $this->db->order_by('block_id', 'ASC');
        $query = $this->db->get('block');
        return $query->result_array();
    }

    //list all block
    function fetchBlockForSplittest($pid) {
        $this->db->where('page_id', intval($pid));
        $this->db->where('page_version_id', 0);
        $this->db->order_by('block_id', 'ASC');
        $query = $this->db->get('block');
        return $query->result_array();
    }

    //list all block
    function fetchBlockLayouts($offset = FALSE, $limit = FALSE) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $this->db->order_by('block_layout_id', 'ASC');
        $query = $this->db->get('block_layout');
        return $query->result_array();
    }

    //count all block
    function countAll($pid) {
        $this->db->where('page_id', $pid);
        $this->db->where('page_version_id', 0);
        $this->db->where('is_main', 0);
        $this->db->from('block');
        return $this->db->count_all_results();
    }

    //list all block
    function listAll($pid, $offset = FALSE, $limit = FALSE) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $this->db->where('page_id', $pid);
        $this->db->where('is_main', 0);
        $this->db->where('page_version_id', 0);
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('block');
        return $query->result_array();
    }

    //function insert record
    function insertRecord($page_details) {
        $data = array();
        $data['page_id'] = $page_details['page_id'];
        $data['block_layout_id'] = $this->input->post('block_layout_id', TRUE);
        $data['block_title'] = $this->input->post('block_title', TRUE);
        if ($this->input->post('block_alias', TRUE) == "") {
            $data['block_alias'] = url_title($this->input->post('block_title', TRUE), '_', TRUE);
        } else {
            $data['block_alias'] = strtolower($this->input->post('block_alias', TRUE));
        }
        $data['block_contents'] = $this->input->post('block_contents', FALSE);
        $data['alt'] = $this->input->post('alt', TRUE);
        $data['link'] = $this->input->post('link', TRUE);
        $data['new_window'] = $this->input->post('new_window', TRUE);
        $data['block_image'] = '';

        //upload image
        $config['upload_path'] = $this->config->item('BLOCK_IMAGE_PATH');
        $config['allowed_types'] = '*';
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);
        if (count($_FILES) > 0) {
            //Check for valid image upload
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {

                if (!$this->upload->do_upload('image')) {
                    show_error($this->upload->display_errors('<p class="err">', '</p>'));
                    return FALSE;
                } else {
                    $upload_data = $this->upload->data();
                    $data['block_image'] = $upload_data['file_name'];
                }
            }
        }
        $data['updated_on'] = time();
        $this->db->insert('block', $data);
        $block_id = $this->db->insert_id();
    }

    //function update record
    function updateRecord($block) {
        $data = array();
        $data['block_layout_id'] = $this->input->post('block_layout_id', TRUE);
        $data['block_title'] = $this->input->post('block_title', TRUE);
        if ($this->input->post('block_alias', TRUE) == "") {
            $data['block_alias'] = url_title($this->input->post('block_title', TRUE), '_', TRUE);
        } else {
            $data['block_alias'] = strtolower($this->input->post('block_alias', TRUE));
        }
        $data['block_contents'] = $this->input->post('block_contents', FALSE);
        $data['alt'] = $this->input->post('alt', TRUE);
        $data['link'] = $this->input->post('link', TRUE);
        $data['new_window'] = $this->input->post('new_window', TRUE);

        //Upload block image
        $config['upload_path'] = $this->config->item('BLOCK_IMAGE_PATH');
        $config['allowed_types'] = '*';
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);
        if (count($_FILES) > 0) {
            //Check for valid image upload
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if (!$this->upload->do_upload('image')) {
                    show_error($this->upload->display_errors('<p class="err">', '</p>'));
                    return FALSE;
                } else {
                    $upload_data = $this->upload->data();
                    $data['block_image'] = $upload_data['file_name'];

                    $path = $this->config->item('BLOCK_IMAGE_PATH');
                    $filename = $path . $block['block_image'];
                    if (file_exists($filename)) {
                        @unlink($filename);
                    }
                }
            }
        }
        $data['updated_on'] = time();
        $this->db->where('block_id', $block['block_id']);
        $this->db->update('block', $data);
    }

    //function delete page block
    function deleteRecord($block) {
        $path = $this->config->item('BLOCK_IMAGE_PATH');
        $filename = $path . $block['block_image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }
        //delete the entry form the product image table
        $this->db->where('block_id', $block['block_id']);
        $this->db->delete('block');
    }

    //function disable page block
    function disableRecord($block) {
        $data = array();
        $data['block_active'] = 0;
        $this->db->where('block_id', $block['block_id']);
        $this->db->update('block', $data);
    }
    
    //function disable page block
    function enableRecord($block) {
        $data = array();
        $data['block_active'] = 1;
        $this->db->where('block_id', $block['block_id']);
        $this->db->update('block', $data);
    }

}

?>
