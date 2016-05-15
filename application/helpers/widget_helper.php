<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function load_widget($alias = false) {
    $CI = & get_instance();
    $page = $CI->getPage();

    $CI->load->model('cms/Widgetmodel');

//fetch the widget location details
    $widget_location = array();
    $widget_location = $CI->Widgetmodel->getWidgetLocation($alias);
    if (!$widget_location)
        return false;


    $widgets = array();
    $widgets = $CI->Widgetmodel->fetchPageWidgets($page, $widget_location['widget_location_id']);
    foreach ($widgets as $widget) {
        switch ($widget['widget_type_alias']) {
            case 'text':
                $widgets_text = $CI->Widgetmodel->fetchWidgetTextData($widget);
                if ($widgets_text) {
                    $inner = array();
                    $inner['widget'] = $widget;
                    $inner['widget_location'] = $widget_location;
                    $inner['widgets_text'] = $widgets_text;
                    $CI->load->view("themes/" . THEME . "/cms/widgets/text", $inner);
                }

                break;
            case 'image':
                $widget_image = $CI->Widgetmodel->fetchWidgetImageData($widget);
                if ($widget_image) {
                    $inner = array();
                    $inner['widget'] = $widget;
                    $inner['widget_location'] = $widget_location;
                    $inner['widget_image'] = $widget_image;
                    $CI->load->view("themes/" . THEME . "/cms/widgets/image", $inner);
                }

                break;
            case 'form':
                $form_widget = $CI->Widgetmodel->fetchWidgetFormData($widget);
                if ($form_widget) {
                    $CI->load->library('widgets/formwidget');
                    echo $CI->formwidget->loadForm($form_widget['form_id'], $widget, $form_widget);
                }
                break;
            case 'cms_menu':
                $widget_cmsmenu = $CI->Widgetmodel->fetchWidgetCMSMenuData($widget);

                $inner = array();
                $inner['widget'] = $widget;
                $inner['widget_location'] = $widget_location;
                $inner['widget_cmsmenu'] = $widget_cmsmenu;
                $CI->load->view("themes/" . THEME . "/cms/widgets/cms_menu", $inner);
                //echo $CI->html->menu($widget_cmsmenu);

                break;
            case 'page_menu':
                $widget_pagemenu = $CI->Widgetmodel->fetchWidgetPageMenuData($widget);

                $inner = array();
                $inner['widget'] = $widget;
                $inner['widget_location'] = $widget_location;
                $inner['widget_pagemenu'] = $widget_pagemenu;
                $CI->load->view("themes/" . THEME . "/cms/widgets/page_menu", $inner);

                break;
            case 'postcode_finder':
                $widget_pagemenu = $CI->Widgetmodel->fetchWidgetPageMenuData($widget);

                $inner = array();
                $inner['widget'] = $widget;
                $inner['widget_location'] = $widget_location;
                $inner['widget_pagemenu'] = $widget_pagemenu;
                $CI->load->view("themes/" . THEME . "/cms/widgets/postcode_finder", $inner);

                break;
            case 'video_widget':
                $widget_video = $CI->Widgetmodel->fetchWidgetVideoData($widget);
                $inner = array();
                $inner['widget'] = $widget;
                $inner['widget_location'] = $widget_location;
                $inner['widget_video'] = $widget_video;
                $CI->load->view("themes/" . THEME . "/cms/widgets/video_widget", $inner);

                break;
        }
    }
//return include APPPATH . '/modules/cms/views/left-colm-widgets.php';
}

function load_form($alias) {
    $CI = & get_instance();
    $page = $CI->getPage();

//fetch the widget location details
    $form = array();
    $CI->db->where('form_alias', $alias);
    $rs = $CI->db->get('form');
    if ($rs->num_rows() == 1) {
        $form = $rs->row_array();
        return $CI->dwsforms->loadForm($form['form_alias']);
    }
}

?>