<?php

class Imagesmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDetails($image_id) {
        $this->db->where('product_image_id', intval($image_id));
        $rs = $this->db->get('product_image');
        if ($rs->num_rows() > 0) {
            return $rs->row_array();
        }
        return array();
    }

	function getDetailsViaArray($image_ids) {
        $this->db->where_in('product_image_id', $image_ids);
        $rs = $this->db->get('product_image');
        if ($rs->num_rows() > 0) {
            return $rs->result_array();
        }
        return array();
    }
    
    function listAll($pid) {

        $this->db->where('product_id', $pid);
        $rs = $this->db->get('product_image');

        return $rs->result_array();
    }

    
    /*
    function setMainImage($image) {
        $this->db->where('product_id', $image['product_id']);
        $this->db->set('is_main_image', 0);
        $this->db->update('product_image');

        $this->db->where('product_image_id', $image['product_image_id']);
        $this->db->set('is_main_image',1);
        $this->db->update('product_image');
    }*/

    function insertRecord($product) {
        $this->db->where('product_id', $product['product_id']);
        $this->db->from('product_image');
        $img_count = $this->db->count_all_results();

        $data = array();
        $data['product_id'] = $product['product_sku'];
        $data['image_title'] = $this->input->post('image_title', TRUE);
        $data['is_main_image'] = $img_count ? 0 : 1;
        if(empty($data['image_title'])){
			$data['image_title'] = $img_count.'_'.$product['product_name'];
			$config['file_name'] = $img_count.'_'.$product['product_name'];
			$data['is_main_image'] = 1;
		}
        

        //Upload Image
        $config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH');
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
                    $data['image'] = $upload_data['file_name'];
                }
            }
        }

        $this->db->insert('product_image', $data);        
    }

    function updateRecord($image) {
        $data = array();
        $data['image_title'] = $this->input->post('image_title', TRUE);

        //Upload Image
        $config = array();
        $config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH');
        $config['allowed_types'] = '*';
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);

        if (count($_FILES) > 0) {
            //Check For Vaild Image Upload
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if (!$this->upload->do_upload('image')) {
                    show_error($this->upload->display_errors('<p class="err">', '</p>'));
                    return FALSE;
                } else {
                    //$data = array();
                    $upload_data = $this->upload->data();
                    $data['image'] = $upload_data['file_name'];

                    $path = $this->config->item('PRODUCT_IMAGE_PATH');
                    $filename = $path . $image['image'];
                    if (file_exists($filename)) {
                        @unlink($filename);
                    }
                }
            }
        }

        $this->db->where('product_image_id', $image['product_image_id']);
        $this->db->update('product_image', $data);
    }

    function deleteImage($image) {
        //delete the  image
        $path = $this->config->item('PRODUCT_IMAGE_PATH');
        $filename = $path . $image['image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }

        //delete product table
        $this->db->where('product_image_id', $image['product_image_id']);
        $this->db->delete('product_image');
    }


    function deleteImageViaArr($imageArr) {
        //delete the  image
        $path = $this->config->item('PRODUCT_IMAGE_PATH');
        $image_id = array();
        foreach($imageArr as $imgDet){
			$image_id[] = $imgDet['product_image_id'];
			$filename = $path . $imgDet['image'];
			if (file_exists($filename)) {
				@unlink($filename);
			}
		}
        //delete product table
        $this->db->where_in('product_image_id', $image_id);
        $this->db->delete('product_image');
    }
    
	function applyImgToAll($product) {
        $this->db->where('product_id', $product['product_id']);
        $this->db->from('product_image');
        $img_count = $this->db->count_all_results();

        $data = array();
        $data['product_id'] = $product['product_id'];
        $data['image_title'] = $this->input->post('image_title', TRUE);
        $data['is_main_image'] = $img_count ? 0 : 1;
        if(empty($data['image_title'])){
			$data['image_title'] = $img_count.'_'.$product['product_name'];
			$config['file_name'] = $img_count.'_'.$product['product_name'];
			$data['is_main_image'] = 1;
		}
        

        //Upload Image
        $config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH');
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
                    $data['image'] = $upload_data['file_name'];
                }
            }
        }

        $this->db->insert('product_image', $data);        
    }
}

?>
