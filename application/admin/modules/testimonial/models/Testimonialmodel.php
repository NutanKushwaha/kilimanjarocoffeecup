<?php

class Testimonialmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDetails($pid) {
        $this->db->where('testimonial_id', intval($pid));
        $rs = $this->db->get('testimonial');
        if ($rs->num_rows() == 1)
            return $rs->row_array();
        return false;
    }

    function countAll() {
        $this->db->from('testimonial');
        return $this->db->count_all_results();
    }

    function listAll($offset = false, $limit = false) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $this->db->from('testimonial');
        $rs = $this->db->get();
        return $rs->result_array();
    }

    //Function Add testimonial
    function insertRecord() {
        $data = array();
        $data['name'] = $this->input->post('name', true);
        /* if ($this->input->post('url_alias', TRUE) == '') {
          $data['url_alias'] = $this->_slug($this->input->post('name', TRUE));
          } else {
          $data['url_alias'] = $this->_slug($this->input->post('name', TRUE));
          } */
        $data['image'] = self::uploadImage();
        $data['testimonial'] = $this->input->post('testimonial', true);
        $data['testimonial_added_on'] = time();

        $this->db->insert('testimonial', $data);
    }

    //Function update  testimonial
    function updateRecord($testimonial) {
        $data = array();
        $data['name'] = $this->input->post('name', true);
        /* if ($this->input->post('url_alias', TRUE) == '') {
          $data['url_alias'] = $this->_slug($this->input->post('name', TRUE));

          } else {
          $data['url_alias'] = $testimonial['url_alias'];
          } */
        if ($_FILES['image']['name'] != '') {
            $data['image'] = self::uploadImage();
        } else {
            $data['image'] = $this->input->post('image1');
        }
        $data['testimonial'] = $this->input->post('testimonial', true);
//        $data['testimonial_added_on'] = time();

        $this->db->where('testimonial_id', $testimonial['testimonial_id']);
        $this->db->update('testimonial', $data);
    }

    //Delete testimonial
    function deletePortfolio($testimonial) {

        $this->db->where('testimonial_id', $testimonial['testimonial_id']);
        $this->db->delete('testimonial');
        return;
    }

    function _slug($tname) {
        $testimonial_name = ($tname) ? $tname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $testimonial_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);
        $this->db->limit(1);
        $this->db->where('url_alias', $slug);
        $rs = $this->db->get('testimonial');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('url_alias', $alt_slug);
                $rs = $this->db->get('testimonial');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }

    function uploadImage() {
        $config['upload_path'] = $this->config->item('TESTIMONIAL_IMAGE_PATH');
        $config['allowed_types'] = 'gif|jpg|png';
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);
        if (count($_FILES) > 0) {
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if (!$this->upload->do_upload('image')) {
                    show_error($this->upload->display_errors('<p class="err">', '</p>'));
                    return FALSE;
                } else {
                    $upload_data = $this->upload->data();
                    return $upload_data['file_name'];
                }
            }
        }
        return false;
    }

}

?>