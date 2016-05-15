<?php

class Widgetmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //fetch the widget tyep details
    function getWidgetLocation($alias) {
        $this->db->where('widget_location_alias', $alias);
        $rs = $this->db->get('widget_location');
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return false;
    }

    function fetchPageWidgets($page, $location_id) {
        //$this->db->from('page_widget');
        //$this->db->join('widget_type', 'widget_type.widget_type_id = page_widget.widget_type_id');
        $this->db->from('widget');
        $this->db->join('widget_type', 'widget_type.widget_type_id = widget.widget_type_id');
        $this->db->join('page_widget', 'page_widget.widget_id = widget.widget_id');
        $this->db->where('page_widget.page_id', $page['page_id']);
        $this->db->where('widget_location_id', $location_id);
        $this->db->order_by('widget_sort_order', 'ASC');
        $rs = $this->db->get();
        return $rs->result_array();
    }

    //fetch the page widget filed data
    function fetchWidgetTextData($widget) {
        $this->db->select('widget_field_data.*, widget_field.widget_field_label, widget_field.widget_field_name');
        $this->db->from('widget_field_data');
        $this->db->join('widget_field', 'widget_field.widget_field_id = widget_field_data.widget_field_id');
        $this->db->where('widget_id', $widget['widget_id']);
        $this->db->where('widget_type_id', $widget['widget_type_id']);
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
    }

    function fetchWidgetFormData($widget) {
        $image_widget = array();
        $this->db->select('widget_field_data.*, widget_field.widget_field_label, widget_field.widget_field_name');
        $this->db->from('widget_field_data');
        $this->db->join('widget_field', 'widget_field.widget_field_id = widget_field_data.widget_field_id');
        $this->db->where('widget_id', $widget['widget_id']);
        $this->db->where('widget_type_id', $widget['widget_type_id']);
        $rs = $this->db->get();
        foreach ($rs->result_array() as $item) {

            $image_widget[$item['widget_field_name']] = $item['widget_field_data'];
        }
        return $image_widget;
    }

    function fetchWidgetImageData($widget) {
        $image_widget = array();
        $this->db->select('widget_field_data.*, widget_field.widget_field_label, widget_field.widget_field_name');
        $this->db->from('widget_field_data');
        $this->db->join('widget_field', 'widget_field.widget_field_id = widget_field_data.widget_field_id');
        $this->db->where('widget_id', $widget['widget_id']);
        $this->db->where('widget_type_id', $widget['widget_type_id']);
        $rs = $this->db->get();
        foreach ($rs->result_array() as $item) {

            $image_widget[$item['widget_field_name']] = $item['widget_field_data'];
        }
        return $image_widget;
    }

    function fetchWidgetCMSMenuData($widget) {
        $image_widget = array();
        $this->db->select('widget_field_data.*, widget_field.widget_field_label, widget_field.widget_field_name');
        $this->db->from('widget_field_data');
        $this->db->join('widget_field', 'widget_field.widget_field_id = widget_field_data.widget_field_id');
        $this->db->where('widget_id', $widget['widget_id']);
        $this->db->where('widget_type_id', $widget['widget_type_id']);
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
    }
    
    function fetchWidgetVideoData($widget) {
        $image_widget = array();
        $this->db->select('widget_field_data.*, widget_field.widget_field_label, widget_field.widget_field_name');
        $this->db->from('widget_field_data');
        $this->db->join('widget_field', 'widget_field.widget_field_id = widget_field_data.widget_field_id');
        $this->db->where('widget_id', $widget['widget_id']);
        $this->db->where('widget_type_id', $widget['widget_type_id']);
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            $row = $rs->row_array();
            $this->db->where('video_id', $row['widget_field_data']);
            $rs = $this->db->get('video');
            $row['video'] = $rs->row_array();
            return $row;
        }
        
        return false;
    }

    function fetchWidgetPageMenuData($widget) {
        $this->db->select('widget_field_data.*, widget_field.widget_field_label, widget_field.widget_field_name');
        $this->db->from('widget_field_data');
        $this->db->join('widget_field', 'widget_field.widget_field_id = widget_field_data.widget_field_id');
        $this->db->where('widget_id', $widget['widget_id']);
        $this->db->where('widget_type_id', $widget['widget_type_id']);
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            $menu_widget = $rs->row_array();
            $page_menu = $this->indentedActiveList($menu_widget['widget_field_data']);

            return $page_menu;
        }
    }

    //find the indented list of pages
    function indentedActiveList($parent, &$output = '') {
        $this->db->where('active', 1);
        $this->db->where('language_code', 'en');
        $this->db->where('parent_id', $parent);
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('page');
        foreach ($query->result_array() as $row) {
            if ($row['parent_id'] > 0) {
                $output .= '<ul class="sf-menu">' . "\r\n";
            }
            $output .= '<li class="">';
            $output .= '<a href="' . $row['page_uri'] . '">' . $row['page_title'] . "</a>";
            $output .= "\r\n";
            $this->indentedActiveList($row['page_id'], $output);
            $output .= "</li>\r\n";
            if ($row['parent_id'] > 0) {
                $output .= "</ul>\r\n";
            }
        }
        return $output;
    }

}

?>
