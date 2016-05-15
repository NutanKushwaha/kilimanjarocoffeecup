<?php
class Homeblockmodel extends CI_Model {

	function __construct() {
        // Call the Model constructor
        parent::__construct();
    }


	//Get page details
	function getPageDetail($pid = 1) {
		$this->db->from('page');
		//$this->db->join('cash_for_cars', 'cash_for_cars.page_id = page.page_id', 'left');
		//$this->db->join('page_type', 'page_type.page_type_id = page.page_type_id', 'left');
		//$this->db->where('cash_for_cars.is_main', 1);
		//$this->db->where('page_version_id', 0);
		$this->db->where('page.page_id', intval($pid));
		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
			return $rs->row_array();
		}
		return FALSE;
	}

	//Get block Detial
	function getDetails($bid) {
		$this->db->join('page','page.page_id = cash_for_cars.page_id');
		$this->db->where('cash_for_cars.block_id', $bid);
		$rs = $this->db->get('cash_for_cars');
		if($rs->num_rows() == 1) return $rs->row_array();

		return FALSE;
	}

    //list all block
	function fetchAllBlocks($pid) {
		$this->db->where('page_id', $pid);

		$this->db->order_by('block_sort_order', 'ASC');
		$query = $this->db->get('cash_for_cars');
        return $query->result_array();
	}

    //list all block
	function fetchBlockForSplittest($pid) {
		$this->db->where('page_id', intval($pid));
		$this->db->where('page_version_id', 0);
		$this->db->order_by('block_sort_order', 'ASC');
		$query = $this->db->get('cash_for_cars');
        return $query->result_array();
	}


	//count all block
	function countAll($pid) {
		$this->db->where('page_id', $pid);
		$this->db->where('page_version_id', 0);
		$this->db->from('cash_for_cars');
		return $this->db->count_all_results();
	}

	//list all block
	function listAll($pid, $offset = FALSE, $limit = FALSE) {
		if($offset) $this->db->offset($offset);
		if($limit) $this->db->limit($limit);

		$this->db->where('page_id', $pid);
		$this->db->where('page_version_id', 0);
		$this->db->order_by('block_sort_order', 'ASC');
		$query = $this->db->get('cash_for_cars');
        return $query->result_array();
	}

	//function insert record
	function insertRecord($pages) {
		$data = array();
		$data['page_id'] = $pages['page_id'];
		$order = $this->getOrder(intval($pages['page_id']));
		$data['block_sort_order'] = $order;

		$data['block_title'] = $this->input->post('block_title', TRUE);
		$data['block_contents'] = $this->input->post('block_contents', FALSE);
		$data['readmore_url'] = $this->input->post("readmore_url", TRUE);

		 //upload image
        $config['upload_path'] = $this->config->item('HOMEPAGEBLOCK_IMAGE_PATH');
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

		$this->db->insert('cash_for_cars', $data);


	}

	//get sort order
	function getOrder($page_id) {
		$this->db->where('page_id', intval($page_id));
		$this->db->select_max('block_sort_order');
		$query = $this->db->get('cash_for_cars');
		$rs = $query->row_array();
		if (empty($rs)) {
			return 1;
		} else {
			$postion = $rs['block_sort_order'] + 1;
			return $postion;
		}
	}

	//function update record
	function updateRecord($block, $page){
		$data = array();
		$data['block_title'] = $this->input->post('block_title', TRUE);
		$data['block_contents'] = $this->input->post('block_contents', FALSE);
		$data['readmore_url'] = $this->input->post('readmore_url', FALSE);

		//Upload block image
        $config['upload_path'] = $this->config->item('HOMEPAGEBLOCK_IMAGE_PATH');
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



                    //Resizing Image
                    $config = array();
                    $config['image_library'] = 'GD2';
                    $config['source_image'] = $this->config->item('HOMEPAGEBLOCK_IMAGE_PATH') . $data['block_image'];

                    $path = $this->config->item('HOMEPAGEBLOCK_IMAGE_PATH');
                    $filename = $path . $block['block_image'];
                    if (file_exists($filename)) {
                        @unlink($filename);
                    }

                }
            }
        }


		$this->db->where('block_id', $block['block_id']);
		$this->db->update('cash_for_cars', $data);
	}


	//function delete page block
	function deleteRecord($block) {
		$file_name = str_ireplace('/', '_', $block['page_uri']).'.php';
		if (file_exists("../application/views/themes/".THEME."/homepageblocks/".$file_name)) {
			@unlink("../application/views/themes/".THEME."/homepageblocks/".$file_name);
		}
		$path = $this->config->item('HOMEPAGEBLOCK_IMAGE_PATH');
		$filename = $path . $block['block_image'];
		if (file_exists($filename)) {
			@unlink($filename);
		}
		//delete the entry form the product image table
		$this->db->where('block_id', $block['block_id']);
		$this->db->delete('cash_for_cars');
	}
}
?>
