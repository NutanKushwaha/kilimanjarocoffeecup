<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slide extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    //**********************validation start**************
    function valid_image($str) {
        if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
            $this->form_validation->set_message('valid_image', ' Image Field is required.');
            return FALSE;
        }
        $imginfo = @getimagesize($_FILES['image']['tmp_name']);

        if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
            $this->form_validation->set_message('valid_image', 'Only GIF, JPG and PNG images are accepted');
            return FALSE;
        }
        return TRUE;
    }

    function validImage($str) {
         if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

            $imginfo = @getimagesize($_FILES['image']['tmp_name']);
            if (!$imginfo) {
                $this->form_validation->set_message('validImage', 'Only image files are allowed');
                return false;
            }

            if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
                $this->form_validation->set_message('validImage', 'Only GIF, JPG and PNG Images are accepted.');
                return FALSE;
            }
        }
        return TRUE;
    }

    //***************validation end****************

    function index($sid = false) {
        if (! $this->flexi_auth->is_privileged('View Slideshows - Slides')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Slideshows - Slides.</p>'); redirect('dashboard'); }

        $this->load->model('Slideshowmodel');
        $this->load->model('Slidemodel');
        
        
        


        //fetch slide show
        $slideshow = array();
        $slideshow = $this->Slideshowmodel->getDetail($sid);
        if (!$slideshow) {
            $this->utility->show404();
            return;
        }

        //Fetch producttree
        $slidetree = '';
        $slidetree = $this->Slidemodel->slideTree(0, $slideshow['slideshow_id']);

        //render view
        $inner = array();
        $inner['slidetree'] = $slidetree;
        $inner['slideshow'] = $slideshow;
        $inner['mod_menu'] = 'layout/inc-menu-cont';
        $page = array();
        $page['content'] = $this->load->view('slideshows/slides/listing', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    //function add slides
    function add($sid = false) {
        if (! $this->flexi_auth->is_privileged('Insert Slideshows - Slides')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Slideshows - Slides.</p>'); redirect('dashboard'); }

        $this->load->model('Slideshowmodel');
        $this->load->model('Slidemodel');
        
        


        
        //fetch the Slideshow details
        $slideshow = array();
        $slideshow = $this->Slideshowmodel->getDetail($sid);
        if (!$slideshow) {
            $this->utility->show404();
            return;
        }

        //validation
        $this->form_validation->set_rules('v_image', 'Image', 'trim|required|callback_valid_image');
        $this->form_validation->set_rules('link', 'Slide link', 'trim');
        $this->form_validation->set_rules('alt', 'Slide Alt', 'trim');
        $this->form_validation->set_rules('new_window', 'New Window', 'trim');

        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $inner['slideshow'] = $slideshow;

            $page = array();
            $page['content'] = $this->load->view('slideshowimage-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Slidemodel->uploadImages($slideshow);

            $this->session->set_flashdata('SUCCESS', 'slide_added');

            redirect("cms/slide/index/{$slideshow['slideshow_id']}", "location");
            exit();
        }
    }

    //function edit slide image
    function edit($image_id = false) {
        if (! $this->flexi_auth->is_privileged('Update Slideshows - Slides')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update Slideshows - Slides.</p>'); redirect('dashboard'); }

        $this->load->model('Slidemodel');
        $this->load->helper('text');
        
        

        
        //fetch the Slide details
        $slideshowimage = array();
        $slideshowimage = $this->Slidemodel->detail($image_id);
        if (!$slideshowimage) {
            $this->utility->show404();
            return;
        }

        //validation check
        $this->form_validation->set_rules('v_image', 'Image', 'trim|required|callback_validImage');
        $this->form_validation->set_rules('link', 'Slide link', 'trim');
        $this->form_validation->set_rules('alt', 'Slide Alt', 'trim');
         $this->form_validation->set_rules('new_window', 'New Window', 'trim');
         $this->form_validation->set_rules('img_desc', 'slideshow image description', 'max_length[250]');
        
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //render view
        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['slideshowimage'] = $slideshowimage;
            $page['content'] = $this->load->view('slideshowimage-edit', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Slidemodel->updateRecord($slideshowimage);

            $this->session->set_flashdata('SUCCESS', 'slide_updated');

            redirect("cms/slide/index/{$slideshowimage['slideshow_id']}");
            exit();
        }
    }
    
    //function delete slide
    function delete($image_id = false) {
        if (! $this->flexi_auth->is_privileged('Delete Slideshows - Slides')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Slideshows - Slides.</p>'); redirect('dashboard'); }

        $this->load->model('Slidemodel');
        
        
        


        //fetch the Slide details
        $slideshowimage = array();
        $slideshowimage = $this->Slidemodel->detail($image_id);
        if (!$slideshowimage) {
            $this->utility->show404();
            return;
        }

        $this->Slidemodel->deleteRecord($slideshowimage);

        $this->session->set_flashdata('SUCCESS', 'slide_deleted');

        redirect("cms/slide/index/{$slideshowimage['slideshow_id']}");
        exit();
    }
    
     //function to enable record
    function enable($image_id = false) {
        $this->load->model('Slidemodel');
        
        
        


        //fetch the Slide details
        $slideshowimage = array();
        $slideshowimage = $this->Slidemodel->detail($image_id);
        if (!$slideshowimage) {
            $this->utility->show404();
            return;
        }

        $this->Slidemodel->enableRecord($slideshowimage);

        $this->session->set_flashdata('SUCCESS', 'slide_enable');

        redirect("cms/slide/index/{$slideshowimage['slideshow_id']}");
        exit();
    }

    //function to disable record
    function disable($image_id = false) {
        $this->load->model('Slidemodel');
        
        
        


       //fetch the Slide details
        $slideshowimage = array();
        $slideshowimage = $this->Slidemodel->detail($image_id);
        if (!$slideshowimage) {
            $this->utility->show404();
            return;
        }

        $this->Slidemodel->disableRecord($slideshowimage);

        $this->session->set_flashdata('SUCCESS', 'slide_disable');

        redirect("cms/slide/index/{$slideshowimage['slideshow_id']}");
        exit();
    }

    //update the speaker sort order
    function updateorder() {

        $sortOrder = $this->input->post('debugStr', TRUE);


        if ($sortOrder) {
            $sortOrder = trim($sortOrder);
            $sortOrder = trim($sortOrder, ',');
            //file_put_contents('facelube.txt',serialize($sortOrder));
            $chunks = explode(',', $sortOrder);
            $counter = 1;
            foreach ($chunks as $id) {
                $data = array();
                $data['slideshow_sort_order'] = $counter;
                $this->db->where('slideshoe_image_id', intval($id));
                $this->db->update('slideshow_image', $data);
                $counter++;
            }
        }
    }

}

?>