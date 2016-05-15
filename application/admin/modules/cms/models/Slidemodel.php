<?php

class Slidemodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //Get homepageimage detail
    function detail($sid) {
        $this->db->where('slideshow_image_id', intval($sid));
        $rs = $this->db->get('slideshow_image');
        if ($rs->num_rows() == 1)
            return $rs->row_array();

        return FALSE;
    }

    function listAll() {
        $query = $this->db->get('slideshow');
        return $query->result_array();
    }
    
    //get sort order
    function getOrder($slideshow) {
        $this->db->select_max('sort_order');
        $this->db->where('slideshow_id', $slideshow['slideshow_id']);
        $query = $this->db->get('slideshow_image');
        $sort_order = $query->row_array();
        return $sort_order['sort_order'] + 1;
    }

    //Upload  slideshow images
    function uploadImages($slideshow) {
        $data = array();
        $data['slideshow_id'] = $slideshow['slideshow_id'];
        $data['alt'] = $this->input->post('alt', TRUE);
        $data['img_title'] = $this->input->post('img_title',true);
        $data['img_desc'] = $this->input->post('img_desc',true);
        $data['link'] = prep_url($this->input->post('link', TRUE));
        $data['image_active'] = $this->input->post('image_active', TRUE);
        $data['new_window'] = $_POST['new_window'];
        $data['slide_uploaded'] = time();
        $data['sort_order'] = $this->getOrder($slideshow);
        
        //upload image
        $config = array();
        $config['upload_path'] = $this->config->item('SLIDESHOW_IMAGE_PATH');
        $config['allowed_types'] = '*';
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);

        $img_counter = 0;
        if (count($_FILES) > 0) {
            //Check for valid image upload
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if (!$this->upload->do_upload('image')) {
                    echo($this->upload->display_errors('<p class="err">', '</p>'));
                    return FALSE;
                } else {
                    $upload_data = $this->upload->data();

                    $data['slideshow_image'] = $upload_data['file_name'];
                }
            }
        }


        $this->db->insert('slideshow_image', $data);
    }

    //function update slide images
    function updateRecord($slideshowimage) {
        $data = array();
        $data['alt'] = $this->input->post('alt', TRUE);
        $data['img_title'] = $this->input->post('img_title',true);
        $data['img_desc'] = $this->input->post('img_desc',true);
        $data['link'] = prep_url($this->input->post('link', TRUE));
       // $data['image_active'] = $this->input->post('image_active', TRUE);
        $data['new_window'] = $_POST['new_window'];

        //Upload Image
        $config = array();
        $config['upload_path'] = $this->config->item('SLIDESHOW_IMAGE_PATH');
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
                    $data['slideshow_image'] = $upload_data['file_name'];

                    //delete the  image
                    $path = $this->config->item('SLIDESHOW_IMAGE_PATH');
                    $filename = $path . $slideshowimage['slideshow_image'];
                    if (file_exists($filename)) {
                        @unlink($filename);
                    }
                }
            }
        }
   
        $this->db->where('slideshow_image_id', $slideshowimage['slideshow_image_id']);
        $this->db->update('slideshow_image', $data);
    }

    //function delete slide image
    function deleteRecord($slideshowimage) {
        //delete the  image
        $path = $this->config->item('SLIDESHOW_IMAGE_PATH');
        $filename = $path . $slideshowimage['slideshow_image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }
        $this->db->where('slideshow_image_id', $slideshowimage['slideshow_image_id']);
        $this->db->delete('slideshow_image');
    }
    
    //enable slideshow
    function enableRecord($slideshowimage) {
        $data = array();

        $data['image_active'] = 1;

        $this->db->where('slideshow_image_id', $slideshowimage['slideshow_image_id']);
        $this->db->update('slideshow_image', $data);
        return;
    }

    //disable slideshow
    function disableRecord($slideshowimage) {
        $data = array();
        $data['image_active'] = 0;
        $this->db->where('slideshow_image_id', $slideshowimage['slideshow_image_id']);
        $this->db->update('slideshow_image', $data);
        return;
    }
    
    //function slidetree
    function slideTree($parent, $sid, $output = '') {
        $this->db->order_by('sort_order', 'ASC');
        $this->db->where('slideshow_id', intval($sid));
        $query = $this->db->get('slideshow_image');
        if ($query->num_rows() > 0) {
            if ($parent == 0) {
                $output .= '<ul id="menutree">' . "\r\n";
            } else {
                $output .= "<ul>\r\n";
            }
            foreach ($query->result_array() as $row) {
                $del_href = 'cms/slide/delete/' . $row['slideshow_image_id'];
                $edit_href = 'cms/slide/edit/' . $row['slideshow_image_id'];
                if($row['image_active'] == 1) {
                    $link_href = 'cms/slide/disable/' . $row['slideshow_image_id'];
                    $link_name = 'Disable';
                }else{
                  $link_href = 'cms/slide/enable/' . $row['slideshow_image_id'];
                  $link_name = 'Enable';
                }
                
                

                $output .= '<li id="menu_' . $row['slideshow_image_id'] . '"><div class="menu_item">' . '<img src="' . $this->config->item('SLIDESHOW_IMAGE_URL') . $row['slideshow_image'] . '" border="0" width="200px" />' . "</div><div class=\"menu_item_options\"><a href=\"" . $link_href . "\" onclick=\"return confirm('Are you sure you want to Enable/Disable this slide image?');\">".$link_name."</a> | <a href=\"" . $edit_href . "\">Edit</a> | <a href=\"" . $del_href . "\" onclick=\"return confirm('Are you sure you want to Delete this Homepage Slide Image ?');\">Delete</a></div><div style=\"clear:both\"></div> ";
                $output .= "</li>\r\n";
            }
            $output .= "</ul>\r\n";
        }
        return $output;
    }

}

?>