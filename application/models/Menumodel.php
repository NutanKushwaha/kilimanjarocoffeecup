<?php

class Menumodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDetails($mid) {
        $this->db->where('menu_id', intval($mid));
        $rs = $this->db->get('menu');
        if ($rs->num_rows() == 1)
            return $rs->row_array();
        return false;
    }

    function getDetailsByAlias($alias) {
        $this->db->where('menu_alias', $alias);
        $rs = $this->db->get('menu');
        if ($rs->num_rows() == 1)
            return $rs->row_array();

        return false;
    }

    function getMenuByAlias($alias) {
        $this->db->from('menu');
        $this->db->join('menu_item', 'menu_item.menu_id = menu.menu_id');
        $this->db->join('page', 'page.page_id = menu_item.page_id', 'LEFT OUTER');
        $this->db->order_by('menu_sort_order', 'ASC');
        $this->db->where('menu_alias', $alias);
        $rs = $this->db->get();
        return $rs->result_array();
    }

    /* function ulList($parent, $output = '') {
      $class = '';
      if ($parent == 0) {
      $class = "last";
      }

      $this->db->where('menu_item.parent_id', $parent);
      $this->db->join('page', 'page.page_id = menu_item.page_id', 'LEFT OUTER');
      $this->db->join('menu', 'menu_item.menu_id = menu.menu_id');
      $this->db->where('menu_item.menu_id', 2);
      $this->db->order_by('menu_sort_order', 'ASC');
      $query = $this->db->get('menu_item');
      if ($query->num_rows() > 0) {
      if ($parent == 0) {
      $output .= '<ul class="sf-menu">' . "\r\n";

      //$output .= '<li rel="0">'."\r\n";
      } else {
      $output .= "<ul>\r\n";
      }

      foreach ($query->result_array() as $row) {
      if ($row['url'] != '' && $row['is_placeholder'] == 0) {
      $output .= '<li class ="' . $class . '"><a href="' . $row['url'] . '">' . $row['menu_item_name'] . "</a>\r\n";
      } elseif ($row['url'] == '' && $row['is_placeholder'] == 1) {
      $output .= '<li class ="' . $class . '"><a href="javascript:void(0)">' . $row['menu_item_name'] . "</a>\r\n";
      } else {
      $output .= '<li class ="' . $class . '"><a href="' . $row['page_alias'] . '">' . $row['menu_item_name'] . "</a>\r\n";
      }
      $output = $this->ulList($row['menu_item_id'], $output);
      $output .= "</li>\r\n";
      }
      $output .= "</ul>\r\n";
      }
      return $output;
      } */

    function categoriesMenu($categories, $parent = 0, &$output = '') {
        //return '';
        $this->db->where('parent_id', $parent);
        $query = $this->db->get('category');

        if ($query->num_rows() > 0) {
            if ($parent == 0) {
                $output .= '<ul class="sf-menu sf-vertical">' . "\r\n";
            } else {
                $output .= "<ul>\r\n";
            }
            foreach ($query->result_array() as $row) {
                $active_class = '';

                $path = explode('.', $row['category_path']);
                $path = array_slice($path, 1);
                $prefix_arr = array();
                foreach ($path as $path_id) {
                    $prefix_arr[] = $categories[$path_id];
                }
                $prefix = '';
                if (count($prefix_arr) > 0) {
                    $prefix = implode(':', $prefix_arr) . ':';
                }

                $output .= '<li class="' . $active_class . '"><a href="category/c/' . $prefix . $row['category_alias'] . '">' . $row['category_name'] . "</a>\r\n";
                $output = $this->categoriesMenu($categories, $row['category_id'], $output);
                $output .= "</li>\r\n";
            }
            $output .= "</ul>\r\n";
        }
        return $output;
    }

    function menu($params) {
        
        if (!isset($params['menu_alias'])) return false;

        $menu = $this->getDetailsByAlias($params['menu_alias']);
        if (!$menu)  return false;

        $params['menu_id'] = $menu['menu_id'];

        //Fetch root menu items
        $this->db->select('menu_item.*, page.page_uri');
        $this->db->from('menu_item');
//        $this->db->join('menu', 'menu_item.menu_id = menu.menu_id');
        $this->db->join('page', 'page.page_id = menu_item.menu_item', 'LEFT OUTER');
        $this->db->where('menu_item.menu_id', $params['menu_id']);
        $this->db->order_by('menu_item.parent_id', 'ASC');
        $this->db->order_by('menu_sort_order', 'ASC');
        $rs = $this->db->get();
        if ($rs->num_rows() == 0)
            return false;
        $rows = $rs->result_array();

        $menu_items = array();
        foreach ($rows as $menu) {
            $menu_items[$menu['parent_id']][] = $menu;
        }

        $params['first_menu_id'] = $rows[0]['menu_item_id'];
        $tmp = array_pop($rows);
        $params['last_menu_id'] = $tmp['menu_item_id'];

        $menu = $this->_menu($params, $menu_items, 0);

        return str_replace('{MENU}', $menu, $params['ul_format']);
    }

    function _menu($params, $menu_items, $parent_id, $output = '') {
        $CI = & get_instance();


        if ($parent_id == 0) {
            //$output .= '<ul class="'.$params['ul_class'].'">' . "\r\n";
        } else {
            $output .= "<ul class='dropdown-menu multi-level'>\r\n";
        }

        foreach ($menu_items[$parent_id] as $row) {

            //li tag class
            $li_class_arr = array();
            $li_class = '';
            if ($row['menu_item_id'] == $params['first_menu_id']) {
                $li_class_arr[] = "first";
            }
            if ($row['menu_item_id'] == $params['last_menu_id']) {
                $li_class_arr[] = "last";
            }
            if (isset($params['list_class'])) {
                $li_class_arr[] = $params['list_class'];
            }
            if ($parent_id == 0) {
                $li_class_arr[] = "root_menu";
            }
            /* if($row['page_id'] != 0) {
              $cpage = $CI->getPage();
              if($cpage && $row['page_id'] == $cpage['page_id']) {
              $li_class_arr[] = "current";
              }
              } */
            $li_class = trim(join(' ', $li_class_arr));

            //anchor tag class
            $a_class_arr = array();
            $a_class = '';
            if (isset($params['anchor_class'])) {
                $a_class_arr[] = $params['anchor_class'];
            }
            /* if($row['page_id'] != 0) {
              $cpage = $CI->getPage();
              if($cpage && $row['page_id'] == $cpage['page_id']) {
              $a_class_arr[] = "current";
              }
              } */

            $a_class = trim(join(' ', $a_class_arr));

            $href = '';
            switch ($row['menu_item_type']) {
                case 'url':
                    if ($row['menu_item'] == 'products' && $this->session->userdata('PRICE_MODE') == '') {
                        $a_class = 'mode_popup';
                        $href = 'mode';
                    } else {
                        $href = $row['menu_item'];
                    }

                    $match = array('{base_url}', '{base_url_ssl}', '{base_url_nossl}');
                    $replace = array($CI->http->baseURL(), $CI->http->baseURLSSL(), $CI->http->baseURLNoSSL());
                    $href = str_replace($match, $replace, $href);
                    
                    break;
                case 'page':
                    $href = $row['page_uri'];
                    break;
                case 'placeholder':
                    $href = 'javascript:void(0)';
                    break;
            }
            if ($parent_id == 0) {
                $link = $params['level_1_format'];
            } else {
                $link = $params['level_2_format'];
            }

            //Additional
            $new_window = '';
            if ($row['new_window'] == 1) {
                $new_window = ' target="_blank"';
            }

            $match = array('{HREF}', '{ADDITIONAL}', '{CLASSES}', '{LINK_NAME}');
            $replace = array($href, $new_window, $a_class, $row['menu_item_name']);
            $replace[0] = base_url().$replace[0];
            $output .= '<li class="' . $li_class . '">';
            
            
            $output .= str_replace($match, $replace, $link);
            $output .= "\r\n";
 
            if (!empty($menu_items[$row['menu_item_id']])) {
                $output = $this->_menu($params, $menu_items, $row['menu_item_id'], $output);
            }
//            e($output);    
            $output .= "</li>\r\n";
        }
       
       

        if ($parent_id > 0) {
            $output .= "</ul>\r\n";
        }

        return $output;
    }

    /* function _menu($params, $parent_id = 0, $output = '') {
      $CI = & get_instance();
      $this->db->from('menu_item');
      $this->db->join('menu', 'menu_item.menu_id = menu.menu_id');
      $this->db->join('page', 'page.page_id = menu_item.menu_item', 'LEFT OUTER');
      $this->db->where('menu_item.parent_id', $parent_id);
      $this->db->where('menu_item.menu_id', $params['menu_id']);
      $this->db->order_by('menu_sort_order', 'ASC');
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
      if ($parent_id == 0) {
      //$output .= '<ul class="'.$params['ul_class'].'">' . "\r\n";
      } else {
      $output .= "<ul>\r\n";
      }

      foreach ($query->result_array() as $row) {

      //li tag class
      $li_class_arr = array();
      $li_class = '';
      if ($row['menu_item_id'] == $params['first_menu_id']) {
      $li_class_arr[] = "first";
      }
      if ($row['menu_item_id'] == $params['last_menu_id']) {
      $li_class_arr[] = "last";
      }
      if (isset($params['list_class'])) {
      $li_class_arr[] = $params['list_class'];
      }
      if ($parent_id == 0) {
      $li_class_arr[] = "root_menu";
      }

      $li_class = trim(join(' ', $li_class_arr));

      //anchor tag class
      $a_class_arr = array();
      $a_class = '';
      if (isset($params['anchor_class'])) {
      $a_class_arr[] = $params['anchor_class'];
      }


      $a_class = trim(join(' ', $a_class_arr));

      $href = '';
      switch ($row['menu_item_type']) {
      case 'url':
      if ($row['menu_item'] == 'products' && $this->session->userdata('PRICE_MODE') == '') {
      $a_class = 'mode_popup';
      $href = 'mode';
      } else {
      $href = $row['menu_item'];
      }

      $match = array('{base_url}', '{base_url_ssl}', '{base_url_nossl}');
      $replace = array($CI->http->baseURL(), $CI->http->baseURLSSL(), $CI->http->baseURLNoSSL());
      $href = str_replace($match, $replace, $href);
      break;
      case 'page':
      $href = $row['page_uri'];
      break;
      case 'placeholder':
      $href = 'javascript:void(0)';
      break;
      }

      if ($parent_id == 0) {
      $link = $params['level_1_format'];
      } else {
      $link = $params['level_2_format'];
      }

      //Additional
      $new_window = '';
      if ($row['new_window'] == 1) {
      $new_window = ' target="_blank"';
      }

      $match = array('{HREF}', '{ADDITIONAL}', '{CLASSES}', '{LINK_NAME}');
      $replace = array($href, $new_window, $a_class, $row['menu_item_name']);

      $output .= '<li class="' . $li_class . '">';
      $output .= str_replace($match, $replace, $link);
      $output .= "\r\n";

      $output = $this->_menu($params, $row['menu_item_id'], $output);

      $output .= "</li>\r\n";
      }

      if ($parent_id > 0) {
      $output .= "</ul>\r\n";
      }
      }
      return $output;
      } */
}

?>