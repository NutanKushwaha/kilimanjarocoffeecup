<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    //**************************validation start*********************************************************
    function valid_pageuri($str) {
        $this->db->where('page_uri', $str);
        //$this->db->where('language_code', $this->config->item('DEFAULT_LANG'));
        $this->db->from('page');
        $page_count = $this->db->count_all_results();
        if ($page_count != 0) {
            $this->form_validation->set_message('valid_pageuri', 'Page URI is already being used!');
            return false;
        }
        return true;
    }

    function valid_pageuri_e($str) {
        $this->db->where('page_uri', $str);
        $this->db->where('page_id !=', $this->input->post('page_id', true));
        $this->db->where('language_code', $this->input->post('language_code', true));
        $this->db->from('page');
        $page_count = $this->db->count_all_results();
        if ($page_count != 0) {
            $this->form_validation->set_message('valid_pageuri_e', 'Page URI is already being used!');
            return false;
        }
        return true;
    }

    //custom validation for module
    function module($str, $module_function) {
        $module_function = explode(',', $module_function);
        $module = trim($module_function[0]);
        $function = trim($module_function['1']);
        if ($module) {
            return $this->$module->$function($str);
        }
        return true;
    }

    //*************************************validation end**********************************************

    function index($offset = 0) {
        if (! $this->flexi_auth->is_privileged('View Page')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Page.</p>'); redirect('dashboard'); }

        $this->load->model('Pagemodel');
        $this->load->helper('text');



        //Fetch pagetree
        $pagetree = '';
        $pagetree = $this->Pagemodel->pageItemTree($this->ids);
//        e($this->ids);
        //Get all pages
        $pages = array();
        $pages = $this->Pagemodel->listAllpages(0);

        //render view
        $inner = array();
        $inner['pages'] = $pages;
        $inner['pagetree'] = $pagetree;
        $inner['mod_menu'] = 'layout/inc-menu-cont';

        $page = array();
        $page['content'] = $this->load->view('page-index', $inner, TRUE);
        $this->load->view($this->template['default'], $page);
    }

    //Function Add
    function add() {
        if (! $this->flexi_auth->is_privileged('Insert Page')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Page.</p>'); redirect('dashboard'); }

        $path = '../../js/ckfinder';
        $width = '698px';
        $this->editor($path, $width);

        $this->load->model('Pagemodel');
        $this->load->model('Blockmodel');
        
        
        $this->load->model('PageBlockmodel');

        //fetch the old page for parent
        $parent = array();
        $parent['0'] = 'Root';
        $pages = $this->Pagemodel->indentedActiveList(0);
        foreach ($pages as $row) {
            $parent[$row['page_id']] = str_repeat('&nbsp;', ($row['level']) * 8) . $row['page_title'];
        }

        //Get all blocks
        $globlblocks = array();
        $globlblocks = $this->Blockmodel->listAll(0);

        //Form Validations
        $this->form_validation->set_rules('page_status', 'Page Status', 'trim|required');
        $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
        $this->form_validation->set_rules('parent_id', 'Parent', 'trim');
        $this->form_validation->set_rules('page_uri', 'Page URI', 'trim|strtolower|callback_valid_pageuri');
        $this->form_validation->set_rules('browser_title', 'Browser Title', 'trim');
        $this->form_validation->set_rules('page_template', 'Page Template', 'trim|required');
        // $this->form_validation->set_rules('contents', 'Contents', 'trim');
        $this->form_validation->set_rules('description', 'Contents', 'trim');
        $this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'trim');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim');
        $this->form_validation->set_rules('before_head_close', 'Addtional Header Contents', 'trim');
        $this->form_validation->set_rules('before_body_close', 'Addtional Footer Contents', 'trim');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        //fetch page template
        $page_templates = array();
        $page_templates[''] = 'Select';
        $template = $this->Pagemodel->listAllTemplate();
        foreach ($template as $row) {
            $page_templates[$row['template_id']] = $row['template_name'];
        }

        $inner = array();
        $page = array();

        $globlblocksArr = array();
        foreach ($globlblocks as $key => $val) {
            $globlblocksArr[$val['block_id']] = $val['block_alias'];
        }
        $inner['globlblocks'] = $globlblocksArr;
        if ($this->form_validation->run() == FALSE) {
            $inner['parent'] = $parent;
            $inner['page_templates'] = $page_templates;
            $inner['mod_menu'] = 'layout/inc-menu-cont';

            $page['content'] = $this->load->view('page-add', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $page_id = $this->Pagemodel->insertRecord($this->config->item('DEFAULT_LANG'));
            $selectedBlocks = $this->input->post('blockadd', TRUE);
            $this->PageBlockmodel->deleteAllBlocks($page_id);
            foreach ($selectedBlocks as $key => $kval) {
                $data = array();
                $data['page_id'] = $page_id;
                $data['block_id'] = $kval;
                $this->PageBlockmodel->insertRecord($data);
            }

            $this->session->set_flashdata('SUCCESS', 'page_added');
            redirect('cms/page/index/', 'location');
            exit();
        }
    }

    //function to edit record
    function edit($pid = false, $target = 1, $tab = 0) {
        
        if (! $this->flexi_auth->is_privileged('Update Page')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update Page.</p>'); redirect('dashboard'); }

//         e("called");
//                 e($_POST);
        $path = '../../../../js/ckfinder';
        $width = '698px';
        $this->editor($path, $width);

        $this->load->model('Pagemodel');
        $this->load->model('Blockmodel');
        $this->load->model('PageBlockmodel');

        
        
        

        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);

        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        //fetch the old page for parent
        $parent = array();
        $parent['0'] = 'Root';
        $pages = $this->Pagemodel->indentedActiveList(0, $page_details['page_id']);
        foreach ($pages as $row) {
            $parent[$row['page_id']] = str_repeat('&nbsp;', ($row['level']) * 8) . $row['page_title'];
        }

        //Get all blocks
        $globlblocks = array();
        $globlblocks = $this->Blockmodel->listAll(0);

        $width = '698px';
        $this->editor($path, $width);


        //Form Validations
        $this->form_validation->set_rules('page_status', 'Page Status', 'trim|required');
        $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
        $this->form_validation->set_rules('parent_id', 'Parent', 'trim');
        $this->form_validation->set_rules('page_uri', 'Page URI', 'trim|strtolower|callback_valid_pageuri_e');
        $this->form_validation->set_rules('browser_title', 'Browser Title', 'trim');
        $this->form_validation->set_rules('page_template', 'Page Template', 'trim|required');
          $this->form_validation->set_rules('contents', 'Contents', 'trim');
        $this->form_validation->set_rules('description', 'Contents', 'trim');
        $this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'trim');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim');
        $this->form_validation->set_rules('before_head_close', 'Addtional Header Contents', 'trim');
        $this->form_validation->set_rules('before_body_close', 'Addtional Footer Contents', 'trim');
        
        $this->form_validation->set_error_delimiters('<li>', '</li>');
//echo "here-----"; exit;        
        //fetch page template
        $page_templates = array();
        $page_templates[''] = 'Select';
        $template = $this->Pagemodel->listAllTemplate();
        foreach ($template as $row) {
            $page_templates[$row['template_id']] = $row['template_name'];
        }


        //Admin module
        $modules = array();
//         if ($page_details['admin_modules']) {
//             //$this->module = $page_details['admin_module'];
//             $modules = explode(',', $page_details['admin_modules']);
// //        echo "<pre>";
// //        print_r($page_details); 
// // echo "------------------------------------------------------------------------------------------------------";
// // print_r($this->config->item('PAGE_DATA_IMAGE_URL').$page_details['page_setting_value']);
// //        exit;
       
//             foreach ($modules as $page_module) {
//                 $this->load->library("page_modules/" . $page_module, array('page' => $page_details));
//             }
//         }

        $inner = array();
        $page = array();
        $inner['globlblocks'] = null;
        $globlblocksArr = array();
        foreach ($globlblocks as $key => $val) {
            $globlblocksArr[$val['block_id']] = $val['block_alias'];
        }
        $inner['globlblocks'] = $globlblocksArr;
        $inner['selectedBlocks'] = array();
        $selectedBlocks = $this->PageBlockmodel->getRelateBlock($page_details['page_id']);
        
        if ($this->form_validation->run() == FALSE) {
            $inner['page_details'] = $page_details;
            $inner['page_templates'] = $page_templates;
            $inner['parent'] = $parent;
            $inner['tab'] = $tab;
            $inner['target'] = $target;
            $inner['modules'] = $modules;
            $inner['mod_menu'] = 'layout/inc-menu-cont';
            $inner['selectedBlocks'] = $selectedBlocks;
            if ($page_details['user_id'] == 0) {
                $page['content'] = $this->load->view('page-edit', $inner, TRUE);
            } else {
                $page['content'] = $this->load->view('franchisee', $inner, TRUE);
            }
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Pagemodel->updateRecord($page_details);
            $selectedBlocks = $this->input->post('blockadd', TRUE);
            $page_id = $page_details['page_id'];
            $this->PageBlockmodel->deleteAllBlocks($page_id);
            foreach ($selectedBlocks as $key => $kval) {
                $data = array();
                $data['page_id'] = $page_id;
                $data['block_id'] = $kval;
                $this->PageBlockmodel->insertRecord($data);
            }
            if ($modules) {
                foreach ($modules as $module) {
                    $this->$module->actionUpdate();
                }
            }

            $this->session->set_flashdata('SUCCESS', 'page_updated');

            $previous_page = $this->session->userdata('PREVIOUS_PAGE');
            if ($this->input->post('button', TRUE) == 'Save and close') {
                if ($target == 1) {
                    redirect("cms/translate/index/$previous_page", 'location');
                    exit();
                }
                if ($target == 2) {
                    redirect('cms/page/index/', 'location');
                    exit();
                }
            }

            if ($this->input->post('button', TRUE) == 'Save') {
                if ($target == 1) {
                    redirect("cms/page/edit/{$page_details['page_id']}/$target", 'location');
                    exit();
                }
                if ($target == 2) {
                    redirect("cms/page/edit/{$page_details['page_id']}/$target", 'location');
                    exit();
                }
            }
        }
    }

    //function duplicate
    function duplicate($pid = false) {
        
        
        $this->load->model('Pagemodel');
        $this->load->model('Translatemodel');
        $this->load->model('Blockmodel');



        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        //fetch the old page for parent
        $parent = array();
        $parent['0'] = '---Root---';
        $pages = $this->Pagemodel->indentedActiveList(0);
        foreach ($pages as $row) {
            $parent[$row['page_id']] = str_repeat('&nbsp;', ($row['level']) * 8) . $row['page_title'];
        }

        $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
        $this->form_validation->set_rules('page_uri', 'Page URI', 'trim|strtolower|callback_valid_pageuri');

        //fetch the block of the page
        $blocks = array();
        $blocks = $this->Blockmodel->fetchAllBlocks($page_details['page_id']);

        if ($this->form_validation->run() == FALSE) {
            $inner = array();
            $page = array();
            $inner['page_details'] = $page_details;
            $inner['parent'] = $parent;
            $inner['mod_menu'] = 'layout/inc-menu-cont';
            $page['content'] = $this->load->view('page-duplicate', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $this->Pagemodel->duplicateRecord($page_details, $blocks);

            $this->session->set_flashdata('SUCCESS', 'page_duplicated');

            redirect('/cms/page/index/', 'location');
            exit();
        }
    }

    //function to enable record
    function enable($pid) {
        
        
        $this->load->model('Pagemodel');

        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        //print_r($page_details); exit();
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        $this->Pagemodel->enableRecord($page_details);

        $this->session->set_flashdata('SUCCESS', 'page_updated');

        redirect('cms/page/index/', 'location');
        exit();
    }

    //function to disable record
    function disable($pid) {
        
        
        $this->load->model('Pagemodel');

        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        $this->Pagemodel->disableRecord($page_details);

        $this->session->set_flashdata('SUCCESS', 'page_updated');

        redirect('cms/page/index/', 'location');
        exit();
    }

    function enable_widget($pid = false, $wid = false, $target = FALSE) {
        $this->load->model('Pagemodel');
        $this->load->model('Widgetmodel');

        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        //fetch the widget
        $widget = array();
        $widget = $this->Widgetmodel->getDetails($wid);
        if (!$widget) {
            $this->utility->show404();
            return;
        }

        $this->Pagemodel->enableWidget($page_details, $widget);

        $this->session->set_flashdata('SUCCESS', 'page_updated');

        redirect("cms/page/edit/$pid/$target/3");
        exit();
    }

    function disable_widget($pid = false, $wid = false, $target = FALSE) {
        $this->load->model('Pagemodel');
        $this->load->model('Widgetmodel');

        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        //fetch the widget
        $widget = array();
        $widget = $this->Widgetmodel->getDetails($wid);
        if (!$widget) {
            $this->utility->show404();
            return;
        }

        $this->Pagemodel->disableWidget($page_details, $widget);

        $this->session->set_flashdata('SUCCESS', 'page_updated');

        redirect("cms/page/edit/$pid/$target/3");
        exit();
    }

    //function to delete record
    function delete($pid) {
        if (! $this->flexi_auth->is_privileged('Delete Page')){ $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Page.</p>'); redirect('dashboard'); }

        
        
        $this->load->model('Pagemodel');


        //Get Page Details
        $page_details = array();
        $page_details = $this->Pagemodel->detail($pid);
        if (!$page_details) {
            $this->utility->show404();
            return;
        }

        if ($page_details['do_not_delete'] == 1) {
            redirect('cms/page/index/', 'location');
            exit();
        }


        //delete from page setting
        if ($page_details['admin_modules']) {
            $modules = explode(',', $page_details['admin_modules']);
            foreach ($modules as $module) {
                $module = trim($module);
                $this->load->library("page_modules/" . $module, array('page' => $page_details));
                $this->$module->actionDelete();
            }
        }


        $this->Pagemodel->deleteRecord($page_details);


        $this->session->set_flashdata('SUCCESS', 'page_deleted');

        redirect('cms/page/index/', 'location');
        exit();
    }

    /*
    function editor($path, $width) {
        //Loading Library For Ckeditor
        $this->load->library('ckeditor');
        $this->load->library('ckFinder');
        //configure base path of ckeditor folder 
        $this->ckeditor->basePath = base_url() . 'js/ckeditor/';

        $this->ckeditor->config['toolbar'] = 'Full';
        $this->ckeditor->config['language'] = 'en';
        $this->ckeditor->config['width'] = $width;
        //configure ckfinder with ckeditor config 
        $this->ckfinder->SetupCKEditor($this->ckeditor, $path);
    }*/

}
?>

