<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends Admin_Controller {

    private $rules;
    private $page;
    private $inner;

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
        $this->page = $this->inner = array();

        $this->load->model('CategoryModel');

        $this->inner['mod_menu'] = 'layout/inc-menu-catalog';
    }

    //**************************************validation start*********************
    function valid_category($str) {
        $this->db->where('category', $str);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('valid_category', 'Category Already Existing!');
            return false;
        }

        return true;
    }

    function check_category($str) {
        $this->db->where('category', $str);
        $this->db->where('category_id !=', $this->input->post('category_id'));
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('check_category', 'Category Already Existing!');
            return false;
        }
        return true;
    }

    function valid_categoryalias($str) {
        $this->db->where('category_alias', $str);
        $this->db->from('category');
        $category_count = $this->db->count_all_results();
        if ($category_count != 0) {
            $this->form_validation->set_message('valid_categoryalias', 'Category Alias is already being used!');
            return false;
        }
        return true;
    }

    function valid_category_e($str) {
        $this->db->where('category_alias', $str);
        $this->db->where('category_id !=', $this->input->post('category_id', true));
        $this->db->from('category');
        $category_count = $this->db->count_all_results();
        if ($category_count != 0) {
            $this->form_validation->set_message('valid_category_e', 'Category Alias is already being used!');
            return false;
        }
        return true;
    }

    //validation for image thumbnail
    function _valid_images($str) {
        if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
            $this->form_validation->set_message('valid_images', 'The Category Image field is required.');
            return FALSE;
        }

        $imginfo = @getimagesize($_FILES['image']['tmp_name']);

        if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
            $this->form_validation->set_message('valid_images', 'Only GIF, JPG and PNG Images are accepted');
            return FALSE;
        }
        return TRUE;
    }

    //function for edit valid image
    function check_image($str, $param) {
        return valid_images($param);
    }

    //*************************************validation End********************************
    //function index
    function index() {
        if (!$this->flexi_auth->is_privileged('View Categories')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view View Categories.</p>');
            redirect('dashboard');
        }


        //Fetch pagetree
        $categorytree = '';
        //$categorytree = $this->CategoryModel->categoriesTree(0);        
        $categRel = $this->CategoryModel->categoriesAccordian(0);        
        $condition = array();
        $condition['where'] = array('parent_id' => 0);
        $categories = array();
        $relation = array('rlt_col' => 'parent_id', 'rlt_fld' => 'category_id');
        $categories = $this->CategoryModel->indentedList($condition, $relation);
        //print_r($categories); exit();
        //render view        
        $this->inner['categories'] = $categories;
        $this->inner['categoryAcord'] = $categRel;
        $this->page['content'] = $this->load->view('categories/category-index', $this->inner, TRUE);
        $this->load->view($this->template['default'], $this->page);
    }

    //function add
    function add() {
        // forfully stopping adding any new category.   
        $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to add Category.</p>');
        redirect('dashboard');

        if (!$this->flexi_auth->is_privileged('Insert Category')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Insert Category.</p>');
            redirect('dashboard');
        }
        //validation check
        $this->rules[] = array('field' => 'checkbox', 'label' => 'Checkbox', 'rules' => 'trim');
        $this->rules[] = array('field' => 'help_link', 'label' => 'Help Link', 'rules' => 'trim');
        $this->rules[] = array('field' => 'category_desc', 'label' => 'Category Overview', 'rules' => 'trim');
        $this->rules[] = array('field' => 'category', 'label' => 'Category Name', 'rules' => 'trim|required');
        $this->rules[] = array('field' => 'parent_id', 'label' => 'Parent Category', 'rules' => 'trim|numeric');
        $this->rules[] = array('field' => 'image', 'label' => 'Category Image', 'rules' => 'trim|callback_check_image[require,0]');
        $this->rules[] = array('field' => 'category_alias', 'label' => 'Category Alias', 'rules' => 'trim|is_unique[category.category_alias]');


        $param = array();
        $isValid = $this->CategoryModel->add($this->rules, $param);
        if ($isValid == FALSE) {
            $this->editor();
            $parent = array();
            $parent['0'] = 'Root';
            $condition = array();
            $condition['where'] = array('parent_id' => 0);
            $relation = array('rlt_col' => 'parent_id', 'rlt_fld' => 'category_id');
            $categories = $this->CategoryModel->indentedList($condition, $relation);
            foreach ($categories as $row) {
                $parent[$row['category_id']] = str_repeat('&nbsp;', ($row['depth']) * 8) . $row['category'];
            }

            $this->inner['parent'] = $parent;
            $this->page['content'] = $this->load->view('categories/category-add', $this->inner, TRUE);
            $this->load->view($this->template['default'], $this->page);
        } else {

            $this->session->set_flashdata('SUCCESS', 'category_added');
            redirect("cpcatalogue/category/index", 'location');
            exit();
        }
    }

    //function edit
    function edit($cid = false) {
        if (!$this->flexi_auth->is_privileged('Update Category')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Update Category.</p>');
            redirect('dashboard');
        }

        //get category detail 
        $current_category = array();
        $current_category = $this->CategoryModel->get_by_pk($cid, False);
		if( !$current_category ){
			$opt = [];
			$opt[ 'top_text' ] = 'Category not exist';
			$opt[ 'bottom_text' ] = 'Category does not exist';
			$this->utility->show404( $opt );
			return;
		}
        //validation check
        $this->rules[] = array('field' => 'checkbox', 'label' => 'Checkbox', 'rules' => 'trim');
        $this->rules[] = array('field' => 'help_link', 'label' => 'Help Link', 'rules' => 'trim');
        $this->rules[] = array('field' => 'category_alias', 'label' => 'Category Alias', 'rules' => 'trim');
        $this->rules[] = array('field' => 'category_desc', 'label' => 'Category Overview', 'rules' => 'trim');
        $this->rules[] = array('field' => 'category', 'label' => 'Category Name', 'rules' => 'trim|required');
        $this->rules[] = array('field' => 'parent_id', 'label' => 'Parent Category', 'rules' => 'trim|numeric');
        $this->rules[] = array('field' => 'c_active', 'label' => 'Active / De-Active', 'rules' => 'trim|numeric');
        $this->rules[] = array('field' => 'image', 'label' => 'Category Image', 'rules' => 'trim|callback_check_image[require,0]');


        $parent = array();
        $parent['0'] = 'Root';

        $condition = array();
        $condition['where'] = array('parent_id' => 0);
        $relation = array('rlt_col' => 'parent_id', 'rlt_fld' => 'category_id');
        $categories = $this->CategoryModel->indentedList($condition, $relation);

        foreach ($categories as $row) {
            $parent[$row['category_id']] = str_repeat('&nbsp;', ($row['depth']) * 8) . $row['category'];
        }

        $result = $this->CategoryModel->updateRecord($this->rules, $current_category);
        if ($result == FALSE) {
            $this->editor();

            $this->inner['current_category'] = $current_category;
            $this->inner['parent'] = $parent;
            $this->page['content'] = $this->load->view('categories/category-edit', $this->inner, TRUE);
            $this->load->view($this->template['default'], $this->page);
        } else {
            $this->session->set_flashdata('SUCCESS', 'category_updated');
            redirect("cpcatalogue/category/index", 'location');
            exit();
        }
    }

    //function delete
    function delete($cid) {
        if (!$this->flexi_auth->is_privileged('Delete Category')) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view Delete Category.</p>');
            redirect('dashboard');
        }

        //get category detail 
        $current_category = array();
        $current_category = $this->CategoryModel->getdetails($cid);
        if (!$current_category) {
            $this->utility->show404();
            return;
        }

        //Validation Check
        $this->form_validation->set_rules('category_id', 'Category Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        $interested_in = array();
        $interested_in[''] = 'Select';
        $categories = $this->CategoryModel->getCategory($current_category);
        foreach ($categories as $row) {
            $interested_in[$row['category_id']] = $row['category'];
        }


        if ($this->form_validation->run() == FALSE) {
            $this->inner['current_category'] = $current_category;
            $this->inner['interested_in'] = $interested_in;
            $this->page['content'] = $this->load->view('categories/category-delete', $this->inner, TRUE);
            $this->load->view($this->template['default'], $this->page);
        } else {
            $this->CategoryModel->deleteRecord($current_category);
            $this->session->set_flashdata('SUCCESS', 'category_deleted');
            redirect("cpcatalogue/category/index");
            exit();
        }
    }

    //function delete
    function category_delete($cid = false) {
        $this->load->model('CategoryModel');
        
        

        //get category detail 
        $current_category = array();
        $current_category = $this->CategoryModel->getdetails($cid);
        if (!$current_category) {
            $this->utility->show404();
            return;
        }

        $this->CategoryModel->deleteCategory($current_category);

        $this->session->set_flashdata('SUCCESS', 'category_deleted');
        redirect("cpcatalogue/category/index", 'location');
        exit();
    }

    //function to enable category
    function enable($cid = false) {
        
        
        $this->load->model('CategoryModel');


        //get category detail 
        $category = array();
        $category = $this->CategoryModel->getdetails($cid);
        if (!$category) {
            $this->utility->show404();
            return;
        }



        $this->CategoryModel->enableRecord($category);

        $this->session->set_flashdata('SUCCESS', 'category_updated');

        redirect('cpcatalogue/category/index/');
        exit();
    }

    //function to disable record
    function disable($cid = false) {
        
        
        $this->load->model('CategoryModel');


        //get category detail 
        $category = array();
        $category = $this->CategoryModel->getdetails($cid);
        if (!$category) {
            $this->utility->show404();
            return;
        }


        $this->CategoryModel->disableRecord($category);

        $this->session->set_flashdata('SUCCESS', 'category_updated');

        redirect('cpcatalogue/category/index/');
        exit();
    }

    function attributeset($cid = false) {
        
        
        $this->load->model('CategoryModel');
        //get category detail 
        $category = array();
        $category = $this->CategoryModel->getdetails($cid);
        if (!$category) {
            $this->utility->show404();
            return;
        }

        //Validation Check
        $this->form_validation->set_rules('attribute_set_id', 'Attribute Set Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<li>', '</li>');

        if ($this->form_validation->run() == FALSE) {
            $page = $inner = array();
            $inner['mod_menu'] = 'layout/inc-menu-catalog';
            $this->load->model('AttributesetModel');
            $inner['attr_list'] = com_makelist($this->AttributesetModel->get_all(), 'id', 'set_name');
            $inner['cid'] = $cid;
            $inner['attribute_set_id'] = !empty($category['attribute_set_id']) ? $category['attribute_set_id'] : '';
            $page['content'] = $this->load->view('categories/sub-page/attribute-set', $inner, TRUE);
            $this->load->view($this->template['default'], $page);
        } else {
            $data['attribute_set_id'] = $this->input->post('attribute_set_id', true);
            $this->db->where('category_id', $cid)
                    ->update('category', $data);
            $this->session->set_flashdata('SUCCESS', 'category_updated');
            redirect("cpcatalogue/category/index", 'location');
            exit();
        }
    }

    /*
      function copy(){
      
      

      $this->load->model('CategoryModel');
      //$this->utility->show404();

      //Validation Check
      $this->form_validation->set_rules('main_category_id', 'From category', 'trim|required');
      $this->form_validation->set_rules('destination_category_id', 'From category', 'trim|required');

      $this->form_validation->set_error_delimiters('<li>', '</li>');

      if ($this->form_validation->run() == FALSE) {
      $main_category_list = array();
      $main_category_list = $this->CategoryModel->indentedListTwo(0);

      $dest_category_list = array();
      $dest_category_list = $this->CategoryModel->indentedListTwo(0);

      $inner = array();
      $page = array();
      $inner['main_category_list'] = $main_category_list;
      $inner['dest_category_list'] = $dest_category_list;
      $page['content'] = $this->load->view('categories/copy-category', $inner, TRUE);
      $this->load->view($this->template['default'], $page);
      } else {
      $this->CategoryModel->deleteRecord($current_category);
      $this->session->set_flashdata('SUCCESS', 'category_deleted');
      redirect("cpcatalogue/category/index");
      exit();
      }

      }
     */
}

?>
