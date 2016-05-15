<?php
/*
CREATE TABLE `bg_category` (
 `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
 `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 `category_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 `category_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 `category_desc` text COLLATE utf8_unicode_ci,
 `help_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `category_sort_order` int(10) unsigned NOT NULL DEFAULT '0',
 `depth` int(10) unsigned NOT NULL DEFAULT '0',
 `c_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
 `attribute_sort` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 `attribute_order` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 `attribute_post_order` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ',
 `is_fulltext` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
 PRIMARY KEY (`category_id`),
 KEY `parent_id` (`parent_id`),
 KEY `category_alias` (`category_alias`), 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
*/

class CategoryModel extends Commonmodel {

    private $data;
    function __construct() {
        parent::__construct();
        $this->data = array();

        $this->categ_color = array(
                                "0" => "#aaaaaa",
                                "1" => "#dddddd", 
                                "2" => "#cccccc", 
                                "3" => "#bbbbbb", 
                                "4" => "#aaaaaa" , 
                                "5" => "#ffffff",
                            );
        $this->tbl_name = 'category';
        $this->tbl_pk_col = 'category_id';
        $this->tbl_main_txt = 'category';

        $this->tbl_cols['depth'] = 'depth';
        $this->tbl_cols['is_fulltext'] = 'is_fulltext';
        $this->tbl_cols['c_active'] = 'c_active';
        $this->tbl_cols['parent_id'] = 'parent_id';
        $this->tbl_cols['help_link'] = 'help_link';

        $this->tbl_cols['attribute_sort'] = 'attribute_sort';
        $this->tbl_cols['attribute_order'] = 'attribute_order';
        $this->tbl_cols['attribute_post_order'] = 'attribute_post_order';

        $this->tbl_cols['category'] = 'category';
        $this->tbl_cols['category_id'] = 'category_id';
        $this->tbl_cols['category_desc'] = 'category_desc';
        $this->tbl_cols['category_alias'] = 'category_alias';
        $this->tbl_cols['category_image'] = 'category_image';
        $this->tbl_cols['category_sort_order'] = 'category_sort_order';
    }

    function insertFromXML($data) {		
        $response = false;
        //com_e( $data );
        if ($data) {
            $product_xml_fields = ['ItmsGrpCod', 'ItmsGrpNam'];
            $product_data_fields = ['ItmsGrpCod' => 'category_code',
									'ItmsGrpNam' => 'category',									
									];
            
            if ( !isset($data[ 'ItemGroups' ][0]) ) {
				$data_items = $data[ 'ItemGroups' ];
				unset( $data[ 'ItemGroups' ] );
                $data[ 'ItemGroups' ] = [ '0' => $data_items];
            }            

            foreach ($data[ 'ItemGroups' ] as $item_index => $item_detail) {
                $item_code = $item_detail['ItmsGrpCod'];
                $item_name = $item_detail['ItmsGrpNam'];
                $category_def_insert = [
					'category' => $item_name,
					'c_active' => '1',
					'category_code' => $item_code,
				];
                /*
                 *  Item Field
                 */
                if( $item_code && $item_name){
					$category = $category_def_insert;
					$param['result'] = 'row';
					$param['fields'] = 'category_code, category_id';
					$param['where']['category_code'] = $item_code;
					$existCategory = $this->get_all($param);
					if ($existCategory) {
						$category_id = $existCategory[ 'category_id' ];
						$prod_data = [];
						$prod_data[ 'data' ] = $category_def_insert; 
						$prod_data[ 'where' ][ 'category_id' ] = $category_id; 
						$this->update_record( $prod_data );
					} else {
						$condition = array();
						$condition['result'] = 'row';
						$condition['max'] = 'category_sort_order';
						$condition['where'] = array('parent_id' => 0);
						$order = $this->get_all($condition);
						$sort_order = intval($order['category_sort_order'] );
						$category_def_insert = [
							'category' => $item_name,
							'c_active' => '1',
							'category_code' => $item_code,
							'category_sort_order' => $sort_order,
							'category_alias' => $this->slug($item_name, $this->tbl_cols['category_alias']),
						];
						$category_id = $this->insert( $category_def_insert );
						unset( $category_def_insert );
					}
					if( $category_id ){
						$prod_data = [];
						$prod_data[ 'category_id' ] = $category_id;
						$this->db->where('category_code', $item_code)
								->update('product', $prod_data);
					}					
				}
            }
            $response = true; 
        }
        
        return $response;
    }

    //get detail of category
    function getdetails($cid) {
        $this->db->where('category_id', $cid);
        $query = $this->db->get('category');
        if ($query->num_rows() == 1) {
            return $query->row_array();
        }
        return false;
    }

    //List Primary category
    function getPrimaryCategory() {
        $this->db->where('c_active', 1);
        $query = $this->db->get('category');
        return $query->result_array();
    }

    //create indented list
    function indentedList1($parent, &$output = array()) {

        $this->db->where('parent_id', $parent);
        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');
        foreach ($query->result_array() as $row) {
            $output[] = $row;
            //$this->indentedList($row['category_id'], $output);
            //print_r($this->indentedList($row['category_id'], $output)); exit();            
        }
        return $output;
    }

    function indentedListTwo($parent = 0, &$output = array()) {

        $this->db->where('parent_id', $parent);
        $this->db->where('depth !=', 5);
        $this->db->where('c_active', 1);
        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');
        foreach ($query->result_array() as $row) {
            $output[] = $row;
            $this->indentedListTwo($row['category_id'], $output);
        }
        return $output;
    }

    //list all category
    function getCategory($current_category) {
        $this->db->where('c_active', 1);
        $this->db->where('category_id !=', $current_category['category_id']);
        $this->db->where('parent_id !=', $current_category['category_id']);
        $query = $this->db->get('category');
        return $query->result_array();
    }

    function add($rules = array(), $param){
        $this->validation_rules = $rules;        
        if($this->validate($rules)){
            
            $config = array();
            $config['upload_path'] = $this->config->item('CATEGORY_IMAGE_PATH');
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;            
            $file_uploaded = $this->upload_file($config);
            if($file_uploaded === FALSE){
                return FALSE;
            }

            $this->data['category_image'] = $file_uploaded;
            if(empty($this->data['category_image']) ){
                $this->data['category_image'] = "";
            }else{
                $params = [     'image_url' => $this->config->item('CATEGORY_IMAGE_URL').$data['category_image'],
                                'image_path' => $this->config->item('CATEGORY_IMAGE_PATH').$data['category_image'],
                                'resize_image_url' => $this->config->item('CATEGORY_RESIZE_IMAGE_URL'),
                                'resize_image_path' => $this->config->item('CATEGORY_RESIZE_IMAGE_PATH'),
                                'width' => 50,
                                'height' => 50,
                        ];
                $new_image_url = resize( $params );
            }

            $alias = $this->input->post('category_alias', TRUE);
            $this->data['parent_id'] = $this->input->post('parent_id', true, 0);
            $this->data['category'] = ucfirst($this->input->post('category', true));
            
            if ($alias == '') {
                $alias = $this->slug($this->data['category'], $this->tbl_cols['category_alias']);
            } else {
                $alias = $this->input->post('category_alias', TRUE);
                $alias = url_title($alias, '-', TRUE);
            }

            $this->data['c_active'] = '1';
            $this->data['category_alias'] = $alias;
            $this->data['help_link'] = $this->input->post('help_link', TRUE);
            $this->data['category_desc'] = $this->input->post('category_desc', true);
            $is_fulltext = $this->input->post('checkbox', true);
            if($is_fulltext == '')
            {
                $this->data['is_fulltext'] = '0';
            }
            $condition = array();
            $condition['result'] = 'row';
            $condition['max'] = 'category_sort_order';
            $condition['where'] = array('parent_id' => $this->data['parent_id']);
            $order = $this->get_all($condition);
            $this->data['category_sort_order'] = intval($order['category_sort_order'] );
            if ($this->data['parent_id'] == 0) {
                $depth = 0;
            } else {
                $condition = array();
                $parent_category = $this->get_by_pk($this->data['parent_id'],FALSE,'depth');
                $depth = $parent_category['depth'] + 1;
            }
            $this->data['depth'] = $depth;            
            return $this->insert($this->data);
        }else{
            return false;
        }
    }



    //update record //$category
    function updateRecord($rules = array(), $category) {
        $this->validation_rules = $rules;
        //com_e($_FILES);
        if($this->validate($rules)){
            $parent = false;
            if ($this->input->post('parent_id', true) > 0) {
                $parent = $this->getdetails($this->input->post('parent_id', true));
            }

            $data = array();
            $data['parent_id'] = $this->input->post('parent_id', true, 0);
            $data['category'] = ucfirst($this->input->post('category', true));

            if ($this->input->post('category_alias', TRUE) == '') {
                $data['category_alias'] = $category['category_alias'];
            } else {
                $url = $this->input->post('category_alias', TRUE);
                $data['category_alias'] = url_title($url, '-', TRUE);
            }
            if ($this->input->post('parent_id', true) == 0) {
                $data['depth'] = 0;
            } else {
                $parent_category = $this->getdetails($this->input->post('parent_id', true, 0));
                $data['depth'] = $parent_category['depth'] + 1;
            }
            $data['help_link'] = $this->input->post('help_link');
            $data['category_desc'] = $this->input->post('category_desc', true);
            
            $data['is_fulltext'] = $this->input->post('checkbox', true);
            if($data['is_fulltext']=='')
            {
                $data['is_fulltext'] = '0';
            }
            $data['c_active'] = $this->input->post('c_active', true);
            if($data['c_active']=='')
            {
                $data['c_active'] = '0';
            }            
            
            //Upload Image
            if (count($_FILES) > 0) {
                //Check For Vaild Image Upload                
                if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {

                    $config = array();
                    $category_image_path  = $this->config->item('CATEGORY_IMAGE_PATH');
                    $config['upload_path'] = $category_image_path;
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['encrypt_name'] = TRUE;
                    $config['overwrite'] = FALSE;
                    $file_uploaded = $this->upload_file($config);

                    if (!$file_uploaded) {
                        show_error($this->upload->display_errors('<p class="err">', '</p>'));
                        return FALSE;
                    } else {

                        $data['category_image'] = $file_uploaded;
                        $params = [     'image_url' => $this->config->item('CATEGORY_IMAGE_URL').$data['category_image'],
                                        'image_path' => $category_image_path.$data['category_image'],
                                        'resize_image_url' => $this->config->item('CATEGORY_RESIZE_IMAGE_URL'),
                                        'resize_image_path' => $this->config->item('CATEGORY_RESIZE_IMAGE_PATH'),
                                        'width' => 50,
                                        'height' => 50,                                        
                                ];                        
                        $new_image_url = resize( $params );
                        $path = $category_image_path;
                        $filename = $path . $category['category_image'];
                        if (file_exists($filename)) {
                            @unlink($filename);
                        }
                    }
                }
            }
            
            $this->db->where('category_id', $category['category_id']);
            $this->db->update('category', $data);   
            return true;
        }else{
            return false;
        }
    }

    function listAllCategories($category) {
        $this->db->from('category');
        $this->db->where('language_code != ', 'en');
        $this->db->where('category_alias', $category['category_alias']);
        $rs = $this->db->get();
        return $rs->result_array();
    }

    private function countCategForParent($parent = 0){
        return $this->db->where('parent_id', $parent)->get('category')->num_rows();
    }

    private function categSubAccordian($parent, $internal_accord, &$output, &$dummyChild){
        $this->db->where('parent_id', $parent);
        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');        
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $row) {
                $depth = $row['depth'];
                $category_text = $row['category'];
                $category_id = $row['category_id'];
                $categColor = $this->categ_color[ $depth ];
                $catCountForParent = $this->countCategForParent($row['category_id']);                
                $dummyChild[ $internal_accord ]  = array(
                                    'depth' => $depth,
                                    'children' => null,
                                    'categ_color' => $categColor,
                                    'category_id' => $category_id,
                                    'category_text' => $category_text,
                                    'internal_parent' => $internal_accord,
                                    'catCountForParent' => $catCountForParent,
                            );
                if( $catCountForParent ){
                    $output .= '<article data-depth="'.$depth.'" 
                                        style="border:1px solid'.$categColor.'; 
                                        padding-bottom:10px;" data-accordion 
                                        class="accordion-level-'.$internal_accord.' 
                                        dropdown-cate" style="color:#000;">
                                    <button data-control style="color:rgb(242, 119, 51) !important; margin-bottom:10px;" 
                                    class="accordion-level-'.$internal_accord.' even 
                                    next-menu" >'.$category_text.'</button>
                                    <span class="attr-set-list pull-right edit-setting">
                                    <a class="btn btn-info" href="cpcatalogue/category/edit/'.$category_id.'"> Edit</a>  
                                    | <a class="btn btn-info" href="cpcatalogue/category/delete/'.$category_id.'" >Delete </a> </span> 
                                    <div data-content class="last-child-dropdown"><ul class="mlevel-2"><li>
                                ';
                    //$internal_accord++;                    
                    $this->categSubAccordian($row['category_id'], $internal_accord, $output, $dummyChild[ $internal_accord ]['children']);
                    $output .= '   </li> </ul></div>
                                </article>';
                }else{
                    $output .= '<article style="background:#ffffff;" class="odd" style="">
                                    <div class="col-md-6" ><i class="fa fa-angle-double-right"></i>
                                             '.$category_text.'</div>
                                    <div class="col-md-6">
                                        <span class="pull-right edit-setting">
                                            <a href="cpcatalogue/category/edit/'.$category_id.'"> Edit</a>  
                                        | <a href="cpcatalogue/category/delete/'.$category_id.'" >Delete </a> 
                                        </span>
                                    </div>
                                    <div class="clearfix"></div>
                                </article> ';
                }
                $internal_accord++;
            }
        }
    }

    function categoriesAccordian($parent, $output = ''){
        $categRel = [];
        $this->db->where('parent_id', $parent);
        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');        
        $output = '';
        if ($query->num_rows() > 0) {
            $internal_parent = 1;
            $internal_accord = 1;
            foreach ($query->result_array() as $key => $row) {
                $depth = $row['depth'];
                $category_text = $row['category'];
                $category_id = $row['category_id'];
                $categ_color = $this->categ_color[$row['depth']];
                $catCountForParent = $this->countCategForParent($row['category_id']);
                $categRel[ $internal_parent ] = array(
                                                        'depth' => $depth,
                                                        'children' => array(),
                                                        'categ_color' => $categ_color,
                                                        'category_id' => $category_id,
                                                        'category_text' => $category_text,
                                                        'internal_parent' => $internal_parent,
                                                        'catCountForParent' => $catCountForParent,
                                                    );
                $output .= '<section style="border:1px solid '.$categ_color.';" 
                                        class="col-md-12 head-accordion-container-dropdown 
                                                accordion-level-parent-'.$internal_parent.'" 
                                        data-accordion>
                                <button '.($catCountForParent?'data-control':'')
                                        .' class="even dropdown-cate" style="color:rgb(242, 119, 51); ">'
                                        .$row['category'].'</button>
                                <span class="attr-set-list pull-right edit-setting">
                                <a class="btn btn-info" href="cpcatalogue/category/edit/'.$category_id.'"> Edit</a>  
                                | <a class="btn btn-info" href="cpcatalogue/category/delete/'.$category_id.'" >Delete </a> </span> 
                                <div data-content><ul class="mlevel-1"><li>
                            ';                    
                $this->categSubAccordian($category_id, $internal_accord, $output, 
                                                $categRel[ $internal_parent ]['children']);
                $output .= '    </li></ul></div>
                            </section>';
                $internal_parent++;
            }            
        }        
        return $categRel;
    }
    /* Not is use */
    private function categoriesTree($parent, $output = '') {
        $this->db->where('parent_id', $parent);
        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            if ($parent == 0) {
                $output .= '<ul id="pagetree">' . "\r\n";
            } else {
                $output .= "<ul>\r\n";
            }
            foreach ($query->result_array() as $row) {
                $attributes_href = 'cpcatalogue/category/attributeset/' . $row['category_id'];
				$sorder_href = 'cpcatalogue/attributes/manageAttrSort/' . $row['category_id'];				
                $edit_href = 'cpcatalogue/category/edit/' . $row['category_id'];
                $var = $row['c_active'] == 1 ? 'disable' : 'enable';
                //link delete
                $ed_href = 'cpcatalogue/category/' . $var . '/' . $row['category_id'];                
                //link delete
                $del_href = 'cpcatalogue/category/delete/' . $row['category_id'];
                //link blockd
                $product_href = 'cpcatalogue/product/index/' . $row['category_id'];
				$furthur_opt = '<div class="page_item_options">
											<a href="'. $ed_href .'">'. ucfirst($var).'</a> 										
										|	<a href="'. $attributes_href.'">Manage Attributes</a> 
										| 	<a href="'. $edit_href . '">Edit</a> 
										| 	<a href="'. $del_href . '" onclick="return confirm(\'Are you sure you want to Delete this Category ?\');">Delete</a> 
								</div>';
				if($this->checkIsParent($row['category_id'])){
					$furthur_opt = '<div class="page_item_options">
											<a href="'. $ed_href .'">'. ucfirst($var).'</a> 																				
										| 	<a href="'. $edit_href . '">Edit</a> 										
								</div>';
				}
                $output .= '<li id="page_' . $row['category_id'] . '">
							<div class="page_item">
								<div class="page_item_name">
									<a href="' . $product_href . '">'. $row['category'] . '</a>
								</div>'.$furthur_opt.'</div>';
                $output = $this->categoriesTree($row['category_id'], $output);
                $output .= "</li>\r\n";
            }
            $output .= "</ul>\r\n";
        }
        return $output;
    }

    function categoriesTreeForProduct($parent, $prev_cat = array(), $output = '') {

        $this->db->where('parent_id', $parent);
        $this->db->order_by('category_sort_order', 'ASC');
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            if ($parent == 0) {
                $output .= '<ul id="pagetree">' . "\r\n";
            } else {
                $output .= "<ul>\r\n";
            }
            foreach ($query->result_array() as $row) {
                //print_r($row['category_id']); continue;


                $output .= '<li style="list-style-type:none"><div class="page_item"><div class="page_item_name">' . form_radio('category_id[]', $row['category_id'], (in_array($row['category_id'], $prev_cat)) ? true : false, ' class="chk_product"') . ' ' . $row['category'] . '<br />' . "</div> <div class=\"page_item_options\"> </div></div>";

                $output = $this->categoriesTreeForProduct($row['category_id'], $prev_cat, $output);

                $output .= "</li>\r\n";
            }
            $output .= "</ul>\r\n";
        }


        return $output;
    }

    function getProdCategories($product_id = 0) {
        $this->db->from('product_category');
        $this->db->where('product_id', intval($product_id));
        $rs = $this->db->get();
        $categories = array();
        foreach ($rs->result_array() as $cat) {
            $categories[] = $cat['category_id'];
        }
        return $categories;
    }

    //Function Delete Record
    function deleteRecord($current_category) {
        //delete the  image
        $path = $this->config->item('CATEGORY_PATH');
        $filename = $path . $current_category['category_image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }
        $data = array();
        $category_id = $this->input->post('category_id', TRUE);
        $category_data = array();
        $category_data['parent_id'] = $category_id;
        $this->db->where('parent_id', $current_category['category_id']);
        $this->db->update('category', $category_data);

        $this->db->where('category_id', $current_category['category_id']);
        $query = $this->db->get('product_category');
        $products = $query->result_array();

        foreach ($products as $row) {
            $data['category_id'] = $this->input->post('category_id', TRUE);
            $this->db->where('category_id', $row['category_id']);
            $this->db->update('product', $data);
        }

        $this->db->where('category_id', $current_category['category_id']);
        $this->db->delete('category');
    }

    //Function Delete Record
    function deleteCategory($current_category) {
        //delete the  image
        $path = $this->config->item('CATEGORY_PATH');
        $filename = $path . $current_category['category_image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }

        //delete product table
        $this->db->where('category_id', $current_category['category_id']);
        $this->db->delete('product');

        //delete category table
        $this->db->where('category_id', $current_category['category_id']);
        $this->db->delete('category');
    }

    //function slug
    /*
    function _slug($cname) {
        $category_name = ($cname) ? $cname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`');

        $slug = $category_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);
        $this->db->limit(1);
        $this->db->where('category_alias', $slug);
        $rs = $this->db->get('category');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('category_alias', $alt_slug);
                $rs = $this->db->get('category');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }
    */

     //enable record
    function enableRecord($category) {
        $data = array();

        $data['c_active'] = 1;
        $this->db->where('category_id', $category['category_id']);
        $this->db->update('category', $data);
    }

    //disable record
    function disableRecord($category) {
        $data = array();

        $data['c_active'] = 0;
        $this->db->where('category_id', $category['category_id']);
        $this->db->update('category', $data);
    }

	function checkProduct($categoryId){
		$catcount = $this->db->select('count(product_id) as counts')
						->where('category_id', $categoryId)
						->get('product_category')
						->row_array();
		return $catcount['counts'];
	}
	
	function checkIsParent($categoryId){
		$catcount = $this->db->select('count(category_id) as counts')
						->where('parent_id', $categoryId)
						->get('category')
						->row_array();
		return $catcount['counts'];
	}
	
	function updateCategInternalDet($data = array(), $categId){
		$this->db->where('category_id', $categId);
		$this->db->update('category', $data);
	}		
}

?>
