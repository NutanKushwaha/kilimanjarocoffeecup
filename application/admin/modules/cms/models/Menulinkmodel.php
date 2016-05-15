<?php

class Menulinkmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //Get menu details
    function details($pid) {
        $this->db->from('menu_item');
        $this->db->where('menu_item_id', intval($pid));
        $rs = $this->db->get();
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        return FALSE;
    }

    //Count All Records
    function countAll() {
        $this->db->from('page');
        return $this->db->count_all_results();
    }

    //List All Records
    function getAll($menu_id) {
        $this->db->where('menu_id', $menu_id);
        $this->db->order_by('menu_sort_order', 'ASC');
        $rs = $this->db->get('menu_item');
        return $rs->result_array();
    }

    //function indented list
    function indentedLists($menu_id, &$output = array()) {
        $this->db->where('menu_id', $menu_id);
        $this->db->where('page_id', 0);
        $query = $this->db->get('menu_item');
        return $query->result_array();
        foreach ($query->result_array() as $row) {
            $output[] = $row;
            $this->indentedList($row['menu_item_id'], false, $output);
        }
        return $output;
    }

    function indentedList($menu_id, $exclude = false, $parent_id = 0, &$output = array()) {
        $this->db->where('menu_id', $menu_id);
        $this->db->where('parent_id', $parent_id);
        if ($exclude) {
            $this->db->where('menu_item_id !=', $exclude);
        }
        $this->db->order_by('menu_sort_order', 'ASC');
        $query = $this->db->get('menu_item');
        foreach ($query->result_array() as $row) {
            $output[] = $row;
            $this->indentedList($menu_id, $exclude, $row['menu_item_id'], $output);
        }
        return $output;
    }

    //add record
    function insertRecord($menu_detail) {
        $data = array();
        $data['menu_item_name'] = $this->input->post('menu_item_name', TRUE);
        $data['new_window'] = $this->input->post('new_window', TRUE);
        /* If new_window is null, it set its value to 0 */
        com_changeNull($data['new_window'], 0);
        $data['menu_id'] = $menu_detail['menu_id'];
        $data['parent_id'] = $this->input->post('parent_id', TRUE);
        /* If new_window is null, it set its value to 0 */
        com_changeNull($data['parent_id'], 0);
        switch ($this->input->post('menu_item_type', TRUE)) {
            case "page":
                $data['menu_item'] = $this->input->post('page_id', TRUE);
                break;
            case "url":
                $data['menu_item'] = $this->input->post('url', TRUE);
                break;
            default:
                $data['menu_item'] = 'placeholder';
        }
		$data['menu_item_type'] = $this->input->post('menu_item_type', TRUE);
        $order = $this->getOrder($menu_detail['menu_id']);
        $data['menu_sort_order'] = $order;

        if ($this->input->post('parent_id', true) == 0) {
            $data['menu_item_level'] = 0;
            $data['menu_item_path'] = 0;
        } else {
            $parent_menu = $this->details($this->input->post('parent_id', true));
            $data['menu_item_level'] = $parent_menu['menu_item_level'] + 1;
            $data['menu_item_path'] = $parent_menu['menu_item_path'] . '.' . $this->input->post('parent_id', true);
        }
        $this->db->insert('menu_item', $data);
    }

    function updateRecord($menu_item) {
        $data = array();
        $data['menu_item_name'] = $this->input->post('menu_item_name', TRUE);
        $data['menu_item_type'] = $this->input->post('menu_item_type', TRUE);
        $data['new_window'] = $this->input->post('new_window', TRUE);
        com_changeNull($data['new_window'], 0);  
        $data['parent_id'] = $this->input->post('parent_id', TRUE);
        com_changeNull($data['parent_id'], 0);  
        switch ($this->input->post('menu_item_type', TRUE)) {
            case "page":
                $data['menu_item'] = $this->input->post('page_id', TRUE);
                break;
            case "url":
                $data['menu_item'] = $this->input->post('url', TRUE);
                break;
            default:
                $data['menu_item'] = '';
        }

        if ($this->input->post('parent_id', true) == 0) {
            $data['menu_item_level'] = 0;
            $data['menu_item_path'] = 0;
        } else {
            $parent_menu = $this->details($this->input->post('parent_id', true));
            $data['menu_item_level'] = $parent_menu['menu_item_level'] + 1;
            $data['menu_item_path'] = $parent_menu['menu_item_path'] . '.' . $this->input->post('parent_id', true);
        }

        $this->db->where('menu_item_id', $menu_item['menu_item_id']);
        $this->db->update('menu_item', $data);
    }

    function deleteRecord($menu_item) {
		
		//Fetch all child pages
		$this->db->where('parent_id', $menu_item['menu_item_id']);
		$rs = $this->db->get('menu_item');
		$child = $rs->result_array();
		if (!empty($child)) {
			foreach ($child as $item) {
				$data = array();
				$data['parent_id'] = $menu_item['parent_id'];

				$this->db->where('menu_item_id', $item['menu_item_id']);
				$this->db->update('menu_item', $data);
			}
		}
		
        $this->db->where('menu_item_id', $menu_item['menu_item_id']);
        $this->db->delete('menu_item');
    }

    //get sort order of menu
    function getOrder($mid) {
        $this->db->select_max('menu_sort_order');
        $this->db->where('menu_id', $mid);
        $query = $this->db->get('menu_item');
        $sort_order = $query->row_array();
        return $sort_order['menu_sort_order'] + 1;
    }

    //function create page tree
    function menuTree($parent, $mid, $output = '') {
        $this->db->where('parent_id', $parent);
        $this->db->where('menu_id', $mid);
        $this->db->order_by('menu_sort_order', 'ASC');
        $query = $this->db->get('menu_item');
        if ($query->num_rows() > 0) {
            if ($parent == 0) {
                $output .= '<ul id="treeroot">' . "\r\n";
            } else {
                $output .= "<ul>\r\n";
            }
            foreach ($query->result_array() as $row) {
                $edit_href = '';
                $del_href = 'menu_item/delete/' . $row['menu_item_id'];

                if ($row['url'] != '') {
                    $edit_href = 'menu_item/editurl/' . $row['menu_item_id'];
                    //$output .= '<li rel="'.$row['menu_item_id'].'"><a href="menu_item/editurl/'.$row['menu_item_id'].'">'.$row['menu_item_name']."</a>\r\n";
                } elseif ($row['is_placeholder'] == 1) {
                    $edit_href = 'menu_item/edit_placeholder/' . $row['menu_item_id'];
                    //$output .= '<li rel="'.$row['menu_item_id'].'"><a href="menu_item/edit_placeholder/'.$row['menu_item_id'].'">'.$row['menu_item_name']."</a>\r\n";
                } else {
                    $edit_href = 'menu_item/edit/' . $row['menu_item_id'];
                    //$output .= '<li rel="'.$row['menu_item_id'].'"><a href="menu_item/edit/'.$row['menu_item_id'].'">'.$row['menu_item_name']."</a>\r\n";
                }

                $output .= '<li rel="' . $row['menu_item_id'] . '"><a href="' . $edit_href . '">' . $row['menu_item_name'] . "</a> - <a href=\"" . $edit_href . "\">Edit</a> | <a href=\"" . $del_href . "\" onclick=\"return confirm('Are you sure you want to Delete this Link ?');\">Delete</a>\r\n";

                $output = $this->menuTree($row['menu_item_id'], $row['menu_id'], $output);
                $output .= "</li>\r\n";
            }
            $output .= "</ul>\r\n";
        }
        return $output;
    }

    function menuItemTree($parent, $mid, $output = '') {
        $this->db->where('parent_id', $parent);
        $this->db->where('menu_id', $mid);
        $this->db->order_by('menu_sort_order', 'ASC');
        $query = $this->db->get('menu_item');
        if ($query->num_rows() > 0) {
            if ($parent == 0) {
                $output .= '<ul id="menutree">' . "\r\n";
            } else {
                $output .= "<ul>\r\n";
            }
            foreach ($query->result_array() as $row) {
                $edit_href = '';
                $del_href = 'cms/menu_item/delete/' . $row['menu_item_id'];

                $edit_href = 'cms/menu_item/edit/' . $row['menu_item_id'];

                $output .= '<li id="menu_' . $row['menu_item_id'] . '"><div class="menu_item"><a href="' . $edit_href . '">' . $row['menu_item_name'] . "</a></div><div class=\"menu_item_options\"><a href=\"" . $edit_href . "\">Edit</a> | <a href=\"" . $del_href . "\" onclick=\"return confirm('Are you sure you want to Delete this Link ?');\">Delete</a></div><div style=\"clear:both\"></div>";

                $output = $this->menuItemTree($row['menu_item_id'], $row['menu_id'], $output);
                $output .= "</li>\r\n";
            }
            $output .= "</ul>\r\n";
        }
        return $output;
    }

}

?>