<?php

class Categorymodel extends Commonmodel {

    function __construct() {
        parent::__construct();
    }

    function CountCategoriesAccordingToParentId($pid) {
        $this->db->where('c_active', 1);
        $this->db->where('parent_id', $pid);
        $query = $this->db->get('category');
        return $query->num_rows();
    }

    function fetchByAlias($c_alias) {
        $this->db->where('category_alias', $c_alias);
        $this->db->where('c_active', 1);
        $query = $this->db->get('category');
        if ($query->num_rows() == 1) {
            return $query->row_array();
        }
        return false;
    }

    function listAllCategoriesAccordingToParentId($pid) {
        $this->db->where('c_active', 1);
        $this->db->where('parent_id', $pid);
        $query = $this->db->get('category');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    function getTopCategoriesforHomepage($limit = 10) {
        $this->db->limit($limit);
        $query = $this->db->get('category');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }
    /*-------------- search treee---------------*/
    function categoriesSearchTree($parent, $output = '', $dept = '') {
        $this->db->where('parent_id', $parent);
        $this->db->where('c_active', 1);
        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');
        $results = $query->result_array();
        foreach ($results as $key => $value) {
            $this->option .= "<option value='" . $value['category_id'] . "' >" . $value['category'] . "</option>";
            $this->menus .= $this->getSearchCatChilds($value['category_id']);
        }
        return $this->option;
    }
     function getSearchCatChilds($id) {
        $results = self::getChilds($id);
        
        foreach ($results as $key => $value) {
            $this->option .= "<option  value='" . $value['category_alias'] . "'>--" . $value['category'] . "</option>";
            $newresults = self::getChilds($value['category_id']);
            foreach ($newresults as $key1 => $value1) {
                $this->option .= "<option value='" . $value1['category_alias'] . "'>---" . $value1['category'] . "</option>";
            }
        }
    }
    /*--------------END search treee---------------*/
    function categoriesTree($parent, $output = '', $dept = '') {

        $this->db->where('parent_id', $parent);
        $this->db->where('c_active', 1);

        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');


        $results = $query->result_array();


        $this->menus .= '<ul id="mega-1" class="sm-megamenu-hover sm_megamenu_menu sm_megamenu_menu_black mega-menu">';

        foreach ($results as $key => $value) {
            $this->menus .= '<li class=" other-toggle sm_megamenu_lv1 sm_megamenu_nodrop parent">';
            $this->menus .= "<a href='" . createUrl('catalogue/index/'.$value['category_alias']) . "' class='sm_megamenu_head sm_megamenu_nodrop '><span class='sm_megamenu_icon sm_megamenu_nodesc'>	<span class='sm_megamenu_title'>" . $value['category'] . "</span></span></a>";
            $this->menus .= $this->getCatChilds($value['category_id']);
            $this->menus .= '</li>';
        }
        $this->menus .= '</ul>';

        return $this->menus;
//        return $output;
    }

    function getCatChilds($id) {
        $results = self::getChilds($id);
        if (!empty($results)) {
            $this->menus .= '<ul>';
            foreach ($results as $key => $value) {
                $this->menus .= "<li><a  href='" . createUrl('catalogue/index/' . $value['category_alias']) . "'>" . $value['category'] . "</a>";
                $this->menus .= '<ul>';
                $newresults = self::getChilds($value['category_id']);
                foreach ($newresults as $key1 => $value1) {
                    $this->menus .= "<li><a href='" . createUrl('catalogue/index/' . $value1['category_alias']) . "'>" . $value1['category'] . "</a></li>";
                }
                $this->menus .= '</ul>';
                $this->menus .= "</li>";
            }
            $this->menus .= '</ul>';
        }
    }

    public function getChilds($id) {
        $this->db->where('category.parent_id',$id);
        $this->db->where('category.c_active', 1);
        $query = $this->db->get('category');
        
//        e($query->result_array());
        return $query->result_array();
    }
}
