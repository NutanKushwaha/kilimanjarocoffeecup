<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Banner {

    private $CI;
    private $page;
    private $module_alias = 'Banner';
    private $module_name = 'Banner';

    function __construct($params) {
        $this->CI = & get_instance();
        $this->page = $params['page'];
        $this->init();
    }

    function valid_image($str) {
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] == UPLOAD_ERR_OK) {

            $imginfo = @getimagesize($_FILES['image']['tmp_name']);
            if (!$imginfo) {
                $this->CI->form_validation->set_message('module', 'Only image files are allowed');
                return false;
            }

            if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
                $this->CI->form_validation->set_message('module', 'Only GIF, JPG and PNG Images are accepted.');
                return FALSE;
            }
        }

        return TRUE;
    }

    function init() {        
        $this->CI->form_validation->set_rules('image_v', 'Banner Image', 'trim|required|callback_module[banner, valid_image]');
        $this->CI->form_validation->set_rules('show_banner', 'Show Banner', 'trim|required');
    }

    function getName() {
        return $this->module_name;
    }

    function getAlias() {
        return $this->module_alias;
    }

    function addView() {
        return "add";
    }

    //function to edit record
    function editView() {
        $this->CI->load->library('form_validation');
        $this->CI->load->helper('form');
        $this->CI->load->model('Pagemodel');

        $banner = array();
        $banner = $this->CI->Pagemodel->getPageData($this->page, 'banner');

        $show_banner = array();
        $show_banner = $this->CI->Pagemodel->getPageData($this->page, 'show_banner');

        $banner_new_window = array();
        $banner_new_window = $this->CI->Pagemodel->getPageData($this->page, 'new_window');

        $banner_link = array();
        $banner_link = $this->CI->Pagemodel->getPageData($this->page, 'banner_url');

        $inner = array();
        $inner['page'] = $this->page;
        $inner['show_banner'] = $show_banner;
        $inner['banner'] = $banner;
        $inner['banner_link'] = $banner_link;
        $inner['banner_new_window'] = $banner_new_window;

        //include APPPATH.'/modules/cms/views/page_modules/banner/edit.php';

        return $this->CI->load->view('cms/page_modules/banner/edit', $inner, true);
    }

    function actionAdd() {
        echo "add";
    }

    function actionDelete() {
        $this->CI->load->model('Pagemodel');
        $page = $this->page;

        $banner = array();
        $banner = $this->CI->Pagemodel->getPageData($this->page, 'banner');

        //delete the previous pdf_image
        if ($banner && isset($banner['page_setting_value'])) {
            $path = $this->CI->config->item('PAGE_DATA_IMAGE_PATH');
            $filename = $path . $banner['page_setting_value'];
            if (file_exists($filename)) {
                @unlink($filename);
            }

            $this->CI->db->where('page_data_id', $banner['page_data_id']);
            $this->CI->db->delete('page_data');
        }

        $show_banner = array();
        $show_banner = $this->CI->Pagemodel->getPageData($this->page, 'show_banner');

        //delete the previos data if any
        $this->CI->db->where('page_data_id', $show_banner['page_data_id']);
        $this->CI->db->delete('page_data');

        $banner_link = array();
        $banner_link = $this->CI->Pagemodel->getPageData($this->page, 'banner_url');

        //delete the previos data if any
        $this->CI->db->where('page_data_id', $banner_link['page_data_id']);
        $this->CI->db->delete('page_data');

        $banner_new_window = array();
        $banner_new_window = $this->CI->Pagemodel->getPageData($this->page, 'new_window');

        //delete the previos data if any
        $this->CI->db->where('page_data_id', $banner_new_window['page_data_id']);
        $this->CI->db->delete('page_data');
    }

    function actionUpdate() {
        $this->CI->load->model('Pagemodel');
        $page = $this->page;

        $banner = array();
        $banner = $this->CI->Pagemodel->getPageData($this->page, 'banner');

        //Upload Image
        $config['upload_path'] = $this->CI->config->item('PAGE_DATA_IMAGE_PATH');
        $config['allowed_types'] = 'gif|jpg|png';
        $config['overwrite'] = FALSE;
        $this->CI->load->library('upload', $config);
        if (count($_FILES) > 0) {
            //Check for valid image upload
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if (!$this->CI->upload->do_upload('image')) {
                    show_error($this->CI->upload->display_errors('<p class="err">', '</p>'));
                    return FALSE;
                } else {
                    $upload_data = $this->CI->upload->data();
                    $banner_image = $upload_data['file_name'];

                    //delete the previous pdf_image
                    if ($banner && isset($banner['page_setting_value'])) {
                        $path = $this->CI->config->item('PAGE_DATA_IMAGE_PATH');
                        $filename = $path . $banner['page_setting_value'];
                        if (file_exists($filename)) {
                            @unlink($filename);
                        }

                        $this->CI->db->where('page_data_id', $banner['page_data_id']);
                        $this->CI->db->delete('page_data');
                    }

                    //insert new image
                    $data = array();
                    $data['page_setting'] = 'banner';
                    $data['module_name'] = $this->getAlias();
                    $data['page_setting_value'] = $banner_image;
                    $data['page_id'] = $page['page_id'];
                    $this->CI->db->insert('page_data', $data);
                }
            }
        }

        $show_banner = array();
        $show_banner = $this->CI->Pagemodel->getPageData($this->page, 'show_banner');

        //delete the previos data if any
        $this->CI->db->where('page_data_id', $show_banner['page_data_id']);
        $this->CI->db->delete('page_data');

        //insert new data
        $data = array();
        $data['page_setting'] = 'show_banner';
        $data['module_name'] = $this->getAlias();
        $data['page_setting_value'] = $this->CI->input->post('show_banner', true);
        $data['page_id'] = $page['page_id'];
        $this->CI->db->insert('page_data', $data);


        $banner_link = array();
        $banner_link = $this->CI->Pagemodel->getPageData($this->page, 'banner_url');

        //delete the previos data if any
        $this->CI->db->where('page_data_id', $banner_link['page_data_id']);
        $this->CI->db->delete('page_data');

        //insert new data
        $data = array();
        $data['module_name'] = $this->getAlias();
        $data['page_setting'] = 'banner_url';
        $data['page_setting_value'] = $this->CI->input->post('link', true);
        ;
        $data['page_id'] = $page['page_id'];
        $this->CI->db->insert('page_data', $data);

        $banner_new_window = array();
        $banner_new_window = $this->CI->Pagemodel->getPageData($this->page, 'new_window');

        //delete the previos data if any
        $this->CI->db->where('page_data_id', $banner_new_window['page_data_id']);
        $this->CI->db->delete('page_data');

        //insert new data
        $data = array();
        $data['module_name'] = $this->getAlias();
        $data['page_setting'] = 'new_window';
        $data['page_setting_value'] = $this->CI->input->post('new_window', true);
        ;
        $data['page_id'] = $page['page_id'];
        $this->CI->db->insert('page_data', $data);
    }

}

?>