<?php

class Productmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->tbl_name = 'product';
        $this->tbl_pk_col = 'product_id';
        $this->tbl_alias = 'prod';
    }

    /* Return product price as per allocated list */

    function getProductPriceFromPriceList($priceList, $productSku) {
        $prodPrice = 0;
        if ($priceList) {
            $param = [];
            $param['result'] = 'row';
            $param['select'] = 'price';
            $param['where']['price_list'] = $priceList;
            $param['where']['product_sku'] = $productSku;
            $param['from_tbl'] = 'product_price_list';
            $prodPrice = $this->get_all($param);
            if (!$prodPrice) {
                $prodPrice = 0;
            } else {
                $prodPrice = $prodPrice['price'];
            }
        }
        return $prodPrice;
    }

    //Function Get Details Of Product
    function details($pid, $main = false) {

        $this->db->from('product');
        $pid = html_escape($pid);
        if (ctype_digit($pid)) {
            $this->db->where('product_id', intval($pid));
        } else {
            $this->db->where('product_sku = "' . $pid . '"');
        }

        if ($main) {
            $this->db->where('ref_product_id', '0');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }

    function getOffsetIndex($pid, $main = false, $offset = 15) {
        if ($main) {
            $this->db->where('ref_product_id', '0');
        }
        $query = $this->db->select('CEIL(count(product_id)/' . $offset . ') as offset')->from('product')
                ->where('product.product_id < ', intval($pid))
                ->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array('offset' => 0);
    }

    function checkProductExist($product_id) {
        return $this->db->from('product')
                        ->where('product.product_id', intval($product_id))
                        ->get()->num_rows();
    }

    function checkProductBySku($product_sku) {
         $prod_det = $this->db->select('product_id')
							->from('product')
							->where('product.product_sku', $product_sku)
							->get()->row_array();
		$prod_id = false;
		if( $prod_det ){
			$prod_id = $prod_det[ 'product_id' ];
		}
		return $prod_id;
    }

    function getProduct($product = [], $param = []) {
        if (isset($param['fields']) && !empty($param['fields'])) {
            $this->db->select($param['fields']);
        }
        if (isset($param['offset']) && !empty($param['offset'])) {
            $this->db->offset($param['offset']);
        }
        if (isset($param['limit']) && !empty($param['limit'])) {
            $this->db->limit($param['limit']);
        }
        if (isset($param['where']) && !empty($param['where'])) {
            foreach ($param['where'] as $whereIndex) {
                $this->db->where($whereIndex[0], $whereIndex[1]);
            }
        }
        $this->db->from('product')
                ->where('product_is_active', '1');
        if ($product) {
            $this->db->where('product_id !=', $product['product_id']);
        }
        $rs = $this->db->get();
        if (isset($param['result']) && !empty($param['result'])) {
            if ($param['result'] == 'row') {
                return $rs->row_array();
            } else {
                return $rs->result_array();
            }
        }
        return $rs->result_array();
    }

    function fetchConfigProduct($pid) {
        $output = array();
        $products = array();
        $config_products = array();
        $config_products[] = $pid;
        $result = $this->db->from('product')->where('ref_product_id', $pid)->or_where('product_id', $pid)->get();
        if ($result->num_rows()) {
            $products = $result->result_array();
            foreach ($products as $key => $value) {
                $output[$value['product_id']] = $value;
                $config_products[] = $value['product_id'];
            }
        }
        $prod_attr = $this->db->from('product_attribute')->where_in('product_id', $config_products)->get()->result_array();
        foreach ($prod_attr as $key => $value) {
            $output[$value['product_id']]['attribute'][$value['attribute_id']] = $value['attribute_value'];
        }
        return $output;
    }

    function fetchAttributes($sid) {
        $query = $this->db->select('attributes_set_attributes.*')
                ->from('attributes_set_attributes')
                ->join('attributes_set', 'attributes_set_attributes.set_id = attributes_set.id')
                ->where('set_id', intval($sid))
                ->get();
        return $query->result_array();
    }

    function deleteAttributes($pid) {

        $this->db->where('product_id', $pid)
                ->delete('product_attribute');
    }

    function fetchAttributeValues($pid) {
        $output = array();
        $this->db->where('product_id', $pid);
        $rs = $this->db->get('product_attribute');

        foreach ($rs->result_array() as $row) {
            $output[$row['attribute_id']] = $row['attribute_value'];
            $output['postfix'][$row['attribute_id']] = $row['attribute_postfix'];
        }
        return $output;
    }

    function countAllProducts($param = []) {
        $category = '';
        $prodName = '';
        extract($param);
        $this->db->from('product')
                ->where('ref_product_id', '0')
                ->where('product_is_active', '1');
        if (!empty($category) && $category) {
            $this->db->where('category_id', intval($category));
        }
        if (!empty($prodName)) {
            $this->db->where(' (product_name like "%' . $prodName . '%" or product_alias like "%' . $prodName . '%") ');
        }
        if (!empty($prodSkuName)) {
			if( !$exact_match ){
				$this->db->where(' (product_name like "%' . $prodSkuName . '%" or product_sku like "%' . $prodSkuName . '%") ');
			} else {				
				$this->db->where('product_name', $prodSkuName)
						->or_where('product_sku', $prodSkuName);
			}
        }
        return $this->db->count_all_results();
    }

    //list all Product
    function listAllProducts($param = []) {
        $category = '';
        $prodName = '';
        extract($param);
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);

        $this->db->select('prod.product_id, prod.product_sku, prod.product_name, set_name, prod.product_is_active, 
                                 prod.product_type_id, cat.category')
                ->from('product as prod')
                ->join('attributes_set', 'prod.attribute_set_id=attributes_set.id', 'left')
                ->join('category as cat', 'cat.category_id=prod.category_id', 'inner')
                ->where('prod.ref_product_id', '0');
                //->where('product_is_active', '1');
        if (!empty($category) && $category) {
            $this->db->where('prod.category_id', intval($category));
        }
        if (!empty($prodName)) {
            $this->db->where(' (product_name like "%' . $prodName . '%" or product_alias like "%' . $prodName . '%") ');
        }
        if (!empty($prodSkuName)) {
			if( !$exact_match ){
				$this->db->where(' (product_name like "%' . $prodSkuName . '%" or product_sku like "%' . $prodSkuName . '%") ');
			} else {				
				$this->db->where('product_name', $prodSkuName)
						->or_where('product_sku', $prodSkuName);
			}
        }        
        return $this->db->get()->result_array();
    }

    /**
     * Insert product from post data via api
     */
    function insertFromXMLRecord($data) {
        $response = false;
        //com_e( $data );
        if ($data) {
            $product_xml_fields = ['ItemCode', 'ItemName', 'SuppCatNum', 'ItmsGrpCod'];
            $product_data_fields = ['ItemCode' => 'product_sku',
									'ItemName' => 'product_name',
									'SuppCatNum' => 'supplier_code',
									'ItmsGrpCod' => 'category_code',
									];

            $pricelist_xml_fields = ['PriceList', 'Price', 'Currency'];
            $pricelist_data_fields = ['Price' => 'price',
                'Currency' => 'currency',
                'PriceList' => 'price_list',
            ];
            
            if ( !isset($data[ 'Items' ][0]) ) {
				$data_items = $data[ 'Items' ];
				unset( $data[ 'Items' ] );
                $data[ 'Items' ] = [ '0' => $data_items];
            }

            foreach ($data[ 'Items' ] as $item_index => $item_detail) {
                $item_code = $item_detail['ItemCode'];
                /*
                 *  Item Field
                 */                
                if( $item_code ){
					$product_insert = [];
					$product_price_list_insert = [];
					$prod_xml_fields = [
						'stock_level' 		=> '0',
						'category_code' 	=> '0',
						'category_id' 		=> '0',
						'supplier_id' 		=> '0',
						'product_sku' 		=> '',
						'product_name' 		=> '',
						'supplier_code' 	=> '0',
						'attribute_set_id' 	=> 1,
						'ref_product_id' 	=> '0',
						'product_type_id' 	=> '1',						
					];
					$product_insert = $prod_xml_fields;
					foreach($product_xml_fields as $stIndex => $stDet ){
						if( isset( $item_detail[ $stDet ] ) && !is_array( $item_detail[ $stDet ] ) ){
							$product_insert[ $product_data_fields[ $stDet ] ] = $item_detail[ $stDet ];
						}
					}
					
					if( $product_insert && !empty( $product_insert[ 'product_sku' ] ) && !empty( $product_insert[ 'product_name' ] ) ){
                        $param['result'] = 'row';
                        $param['fields'] = 'product_added_on, product_updated_on, product_sku';
                        $param['where'] = [['product_sku', $item_code]];
                        $existProduct = $this->getProduct('', $param);
                        if ($existProduct) {
                            $product_added_on = $existProduct['product_added_on'];
                            $product_updated_on = time();
                        } else {
                            $product_added_on = time();
                            $product_updated_on = 0;
                        }
                        $product_insert[ 'product_alias' ] = $this->_slug($product_insert['product_name']);
                        $product_insert[ 'product_sort_order' ] = $this->getOrder();
                        $product_insert[ 'product_added_on' ] 	= $product_added_on;
                        $product_insert[ 'product_updated_on' ] = $product_updated_on;
						
						if( $product_insert[ 'supplier_code'] && !empty( $product_insert[ 'supplier_code'] ) ){
							$supplier = $this->db->select('id')
										->from( 'supplier' )
										->where('supplier_code',  $product_insert[ 'supplier_code'])
										->get()->row_array();
							if( $supplier ){
								$product_insert[ 'supplier_id' ] = $supplier[ 'id' ];
							}
						}
						
						if( $product_insert[ 'category_code'] && !empty( $product_insert[ 'category_code'] ) ){
							$category = $this->db->select('category_id')
										->from( 'category' )
										->where('category_code',  $product_insert[ 'category_code'])
										->get()->row_array();
							if( $category ){
								$product_insert[ 'category_id' ] = $category[ 'category_id' ];
							}
						}
						$product_insert[ 'is_sap' ] = 1;
                        if ($existProduct) {
                            $this->db->where('product_sku', $item_code)
                                    ->update('product', $product_insert);
                            $this->db->where('product_sku', $item_code)
                                    ->delete('product_price_list');
                        } else {
                            $this->db->insert('product', $product_insert);
                        }
                        $prodlist_xml_fields = [
							'price' => '',
							'currency' => '',
							'price_list' => '',
							'product_sku' => $item_code,
						];
						
						foreach ($item_detail[ 'PriceList' ] as $xmlKey => $xmlDet) {
							if( is_array( $xmlDet ) ){
								 $product_price_list_insert[ $xmlKey ] = $prodlist_xml_fields;
								foreach( $pricelist_xml_fields as $plIndex => $plDetail ){
									if( isset( $xmlDet[ $plDetail ] ) && !is_array( $xmlDet[ $plDetail ] ) ){
										$product_price_list_insert[ $xmlKey ][ $pricelist_data_fields[ $plDetail ] ] 
										= $xmlDet[ $plDetail ];
									}
								}
							}
						}
                    $this->db->insert_batch('product_price_list', $product_price_list_insert);
					}
				}
            }
            $response = true; 
        }
        
        return $response;
    }

    //Function Add Record
    function insertRecord() {
        $ref_product_id = 0;
        $attribute = $this->input->post('attribute', true);
        $attribute_key_id = array();
        if ($attribute) {
            $attribute_key_id = array_keys($attribute);
        }
        $name = $this->input->post('product_name', true);
        $sku = $this->input->post('product_sku', true);
        $price = $this->input->post('product_price', TRUE);
        $point = $this->input->post('product_point', true);
        $supplier = $this->input->post('supplier_id', true);
        $brand = $this->input->post('brand_id', true);
        $stock = $this->input->post('stock_level', true);
        $weight = $this->input->post('weight', true);
        $alias = $this->input->post('product_alias', TRUE);
        $new_to = $this->input->post('new_to', TRUE);
        $new_from = $this->input->post('new_from', TRUE);
        $ALL_IMAGES = $_FILES;
        $pCount = 0;
        foreach ($name as $key => $value) {
            $data = array();
            if (isset($ALL_IMAGES['product_image']['name'][$key])) {
                $file_name = $ALL_IMAGES['product_image']['name'][$key];
                $db_file_name = "";
                if ($ALL_IMAGES['product_image']['error'][$key] == UPLOAD_ERR_OK && is_uploaded_file($ALL_IMAGES['product_image']['tmp_name'][$key])) {
                    $_FILES['product_image']['name'] = $ALL_IMAGES['product_image']['name'][$key];
                    $_FILES['product_image']['type'] = $ALL_IMAGES['product_image']['type'][$key];
                    $_FILES['product_image']['tmp_name'] = $ALL_IMAGES['product_image']['tmp_name'][$key];
                    $_FILES['product_image']['error'] = $ALL_IMAGES['product_image']['error'][$key];
                    $_FILES['product_image']['size'] = $ALL_IMAGES['product_image']['size'][$key];
                    $current_time = time();
                    $db_file_name = $current_time . '-' . $file_name;
                    $config['overwrite'] = FALSE;
                    $config['allowed_types'] = 'jpg|jpeg|gif|png';
                    $config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH');
                    $config['encrypt_name'] = TRUE;
                    /*
                     * Old Way
                     * $this->load->library('upload');
                     * $this->upload->initialize($config);
                     * New Way
                     * $this->load->library('upload', $config);
                     */

                    
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('product_image')) {
                        show_error($this->upload->display_errors('<p class="err">', '</p>'));
                        return FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $data['product_image'] = $upload_data['file_name'];
                        $params = [ 'image_url' => $this->config->item('PRODUCT_IMAGE_URL') . $data['product_image'],
                            'image_path' => $this->config->item('PRODUCT_IMAGE_PATH') . $data['product_image'],
                            'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
                            'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
                            'width' => 50,
                            'height' => 50,
                        ];
                        $new_image_url = resize($params);
                    }
                }
            }
            $data['ref_product_id'] = $ref_product_id;
            $data['category_id'] = $this->input->post('category_id', true);
            $data['product_name'] = $value;
            $data['product_sku'] = $sku[$key];
            $data['product_price'] = floatval($price[$key]);
            $data['product_point'] = floatval($point[$key]);
            $data['supplier_id'] = intval($supplier);
            $data['brand_id'] = intval($brand);
            $data['stock_level'] = 1000;  /// by defaul
            $data['weight'] = intval($weight[$key]);
            $data['product_type_id'] = $this->input->post('product_type_id', false);
            $data['attribute_set_id'] = $this->input->post('attribute_set_id', false);
            $data['product_description'] = $this->input->post('product_description', false);
            $data['product_meta_title'] = $this->input->post('product_meta_title', true, "");
            $data['product_meta_keywords'] = $this->input->post('product_meta_keywords', true);
            $data['product_meta_description'] = $this->input->post('product_meta_description', true);
            $data['technical_detail'] = $this->input->post('technical_detail', false);
            $data['is_featured'] = $this->input->post('is_featured', true, 0);
            $data['is_heavyduty'] = $this->input->post('is_heavyduty', true, 0);
            $data['product_added_on'] = time();
            $data['product_is_active'] = 1;
            com_changeNull($new_to, '0000-00-00 00:00:00');
            com_changeNull($new_from, '0000-00-00 00:00:00');
            $data['new_to'] = $new_to;
            $data['new_from'] = $new_from;
            if ($data['new_to'] != '0000-00-00 00:00:00') {
                $data['new_to'] = date('Y-m-d h:i:s', strtotime($data['new_to']));
            }
            if ($data['new_from'] != '0000-00-00 00:00:00') {
                $data['new_from'] = date('Y-m-d h:i:s', strtotime($data['new_from']));
            }
            if (empty($alias[$key])) {
                $data['product_alias'] = $this->_slug($value);
            } else {
                $data['product_alias'] = url_title($alias[$key]);
            }
            $data['product_sort_order'] = $this->getOrder();
            $this->db->insert('product', $data);
            $prod_id = $this->db->insert_id();
            if ($pCount) {
                $ref_product_id = $prod_id;
            }
            if ($attribute_key_id) {
                $insert_attribute = array();
                foreach ($attribute_key_id as $attr_key => $value) {

                    $insert_attribute[] = array('product_id' => $prod_id,
                        'attribute_id' => $value,
                        'attribute_value' => $attribute[$value][$key]
                    );
                }
                $this->db->insert_batch('product_attribute', $insert_attribute);
            }
            $pCount++;
        }
        return true;
    }

    //get sort order of category
    function getOrder() {
        $this->db->select_max('product_sort_order');
        $query = $this->db->get('product');
        $sort_order = $query->row_array();
        return $sort_order['product_sort_order'] + 1;
    }

    //Function Update Product
    function updateRecord($product) {
        $ref_product_id = $product['product_id'];
        $attribute = $this->input->post('attribute', true);
        $attribute_key_id = array();
        if ($attribute) {
            $attribute_key_id = array_keys($attribute);
        }

        $name = $this->input->post('product_name', true);
        $sku = $this->input->post('product_sku', true);
        $price = $this->input->post('product_price', TRUE);
        $point = $this->input->post('product_point', true);
        $supplier = $this->input->post('supplier_id', true);
        $brand = $this->input->post('brand_id', true);
        $stock = $this->input->post('stock_level', true);
        $weight = $this->input->post('weight', true);
        $alias = $this->input->post('product_alias', TRUE);
        $category_id = $this->input->post('category_id', true);
        $product_type_id = $product['product_type_id'];
        $new_to = $this->input->post('new_to', TRUE);
        $new_from = $this->input->post('new_from', TRUE);

        $ALL_IMAGES = $_FILES;
        foreach ($name as $key => $value) {
            $data = array();
            if (isset($ALL_IMAGES['product_image']['name'][$key])) {
                $file_name = $ALL_IMAGES['product_image']['name'][$key];
                $db_file_name = "";
                if ($ALL_IMAGES['product_image']['error'][$key] == UPLOAD_ERR_OK && is_uploaded_file($ALL_IMAGES['product_image']['tmp_name'][$key])) {
                    $_FILES['product_image']['name'] = $ALL_IMAGES['product_image']['name'][$key];
                    $_FILES['product_image']['type'] = $ALL_IMAGES['product_image']['type'][$key];
                    $_FILES['product_image']['tmp_name'] = $ALL_IMAGES['product_image']['tmp_name'][$key];
                    $_FILES['product_image']['error'] = $ALL_IMAGES['product_image']['error'][$key];
                    $_FILES['product_image']['size'] = $ALL_IMAGES['product_image']['size'][$key];
                    $current_time = time();
                    $db_file_name = $current_time . '-' . $file_name;
                    $config['overwrite'] = FALSE;
                    $config['allowed_types'] = 'jpg|jpeg|gif|png';
                    $config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH');
                    $config['file_name'] = $db_file_name;
                    /*
                     * Old Way
                     * $this->load->library('upload');
                     * $this->upload->initialize($config);
                     * New Way
                     * $this->load->library('upload', $config);
                     */
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('product_image')) {
                        show_error($this->upload->display_errors('<p class="err">', '</p>'));
                        return FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $data['product_image'] = $upload_data['file_name'];
                        $params = [ 'image_url' => $this->config->item('PRODUCT_IMAGE_URL') . $data['product_image'],
                            'image_path' => $this->config->item('PRODUCT_IMAGE_PATH') . $data['product_image'],
                            'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
                            'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
                            'width' => 50,
                            'height' => 50,
                        ];
                        $new_image_url = resize($params);
                    }
                } else {
                    
                }
            }
            if ($key !== (int) $product['product_id']) {
                $data['ref_product_id'] = $ref_product_id;
            }
            $data['category_id'] = $category_id;
            $data['product_name'] = $value;
            $data['product_sku'] = $sku[$key];
            $data['brand_id'] = intval($brand);
            $data['supplier_id'] = intval($supplier);            
            $data['product_price'] = floatval($price[$key]);
            $data['product_point'] = floatval($point[$key]);
            $data['stock_level'] = floatval($stock[$key]);
            $data['weight'] = intval($weight[$key]);
            $data['product_type_id'] = $product_type_id;
            $data['product_description'] = $this->input->post('product_description', false);
            $data['product_meta_title'] = $this->input->post('product_meta_title', true, "");
            $data['product_meta_keywords'] = $this->input->post('product_meta_keywords', true);
            $data['product_meta_description'] = $this->input->post('product_meta_description', true);
            $data['technical_detail'] = $this->input->post('technical_detail', false);
            $data['is_featured'] = $this->input->post('is_featured', true, 0);
            $data['is_heavyduty'] = $this->input->post('is_heavyduty', true, 0);
            $data['product_updated_on'] = time();
            $data['product_is_active'] = 1;
            com_changeNull($new_to, '0000-00-00 00:00:00');
            com_changeNull($new_from, '0000-00-00 00:00:00');
            $data['new_to'] = $new_to;
            $data['new_from'] = $new_from;
            if ($data['new_to'] != '0000-00-00 00:00:00') {
                $data['new_to'] = date('Y-m-d h:i:s', strtotime($data['new_to']));
            }
            if ($data['new_from'] != '0000-00-00 00:00:00') {
                $data['new_from'] = date('Y-m-d h:i:s', strtotime($data['new_from']));
            }
            if (empty($alias[$key])) {
                $data['product_alias'] = $this->_slug($value);
            } else {
                $data['product_alias'] = url_title($alias[$key]);
            }


            if (!$this->checkProductExist($key)) {
                $data['product_added_on'] = time();
                $data['product_sort_order'] = $this->getOrder();
                $this->db->insert('product', $data);
                $prod_id = $this->db->insert_id();
                $update = false;
            } else {
                $this->db->where('product_id', $key)
                        ->update('product', $data);
                $prod_id = $key;
                $update = true;
            }
            if ($attribute_key_id) {
                $insert_attribute = array();
                foreach ($attribute_key_id as $attr_key => $value) {
                    $insert_attribute[] = array('product_id' => $prod_id,
                        'attribute_id' => $value,
                        'attribute_value' => $attribute[$value][$key]
                    );
                }
                $this->db->where('product_id', $prod_id)->delete('product_attribute');
                $this->db->insert_batch('product_attribute', $insert_attribute);
            }
        }
    }

    function disableRecord($product, $action) {
        $data = array();
        //delete product
        $data['product_is_active'] = $action ? '1' : '0';
        $this->db->where('product_id', $product['product_id']);
        $this->db->update('product', $data);
    }

    //Delete Product
    function deleteProduct($product) {
		$this->load->model( 'Imagesmodel' );
        $images = array();
        $images = $this->Imagesmodel->listAll($product['product_id']);
        foreach ($images as $image) {
            //delete the  image
            $path = $this->config->item('PRODUCT_IMAGE_PATH');
            $filename = $path . $image['image'];
            if (file_exists($filename)) {
                @unlink($filename);
            }
        }
		
        //delete product alternative products
        $this->db->where('product_id', $product['product_id']);
        $this->db->delete('alternative_product');

        //delete product related products
        $this->db->where('product_id', $product['product_id']);
        $this->db->delete('related_product');

        //delete product images
        $this->db->where('product_id', $product['product_id']);
        $this->db->delete('product_image');

        /*
			//delete product options
			$this->db->where('product_id', $product['product_id']);
			$this->db->delete('options');

			//delete product option rows
			$this->db->where('product_id', $product['product_id']);
			$this->db->delete('option_rows');

			//delete product categories
			$this->db->where('product_id', $product['product_id']);
			$this->db->delete('product_category');
         */

        //delete product attributes
        $this->db->where('product_id', $product['product_id']);
        $this->db->delete('product_attribute');

		//delete config products
		if( $product[ 'product_type_id' ] == 2){			
			$this->db->where_in('ref_product_id', $product['product_id']);
			$this->db->delete('product');				
		}

        //delete product
        $this->db->where('product_id', $product['product_id']);
        $this->db->delete('product');
    }

    //Function _Slug
    function _slug($pname) {
        $product_name = ($pname) ? $pname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`', '#');

        $slug = $product_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);
        $this->db->limit(1);
        $this->db->where('product_alias', $slug);
        $rs = $this->db->get('product');
        if ($rs->num_rows() > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";
                $this->db->limit(1);
                $this->db->where('product_alias', $alt_slug);
                $rs = $this->db->get('product');
                if ($rs->num_rows() > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }

    function getRelatedProducts($cid, $pid) {
        $this->db->where('category_id', intval($cid))
                ->where('product_is_active', '1');
        if ($pid)
            $this->db->where('product_id !=', intval($pid));
        $rs = $this->db->get('product');
        return $rs->result_array();
    }

    function getCurrentProducts($pid) {
        $this->db->select('related_product.*, product.product_name');
        $this->db->from('related_product');
        $this->db->where('related_product.product_id', intval($pid));
        $this->db->join('product', 'product.product_id = related_product.product_id')
                ->where('product_is_active', '1');
        $rs = $this->db->get();
        return $rs->result_array();
        /* if ($rs->num_rows() > 0) {
          return $rs->result_array();
          } else {
          return $rs->row_array();
          } */
    }

    function checkAttrUnique($param) {
        if ($param['product_id']) {
            $this->db->where('product_id != ', $param['product_id']);
        }
        if ($param['sys']) {
            $rs = $this->db->where($param['field'], $param['value'])->from('product')->get();
        } else {
            $search_val = explode('_', $param['field']);
            $rs = $this->db->where('attribute_id', $search_val[1])
                            ->where('attribute_id', $param['value'])
                            ->from('product_attribute')->get();
        }
        if ($rs->num_rows()) {
            return false;
        } else {
            return true;
        }
    }

    /*
      function insertLeadTime(){
      $data = array();
      $data['leadlabel'] = $this->input->post('leadTimeText', true);
      $data['product_id'] = $this->input->post('prodid', true);
      $this->db->insert('leadtime', $data);
      }
      function listAllLeadTime($prodId){
      return $this->db->select('*')->where('product_id', $prodId)->get('leadtime')->result_array();
      }
      function getLeadTime($refId){
      return $this->db->select('*')->where('id', $refId)->get('leadtime')->row_array();
      }
      function activeLeadTime($refId, $update){
      $data = array();
      $data['Selected'] = ($update ? 1 : 0);
      $this->db->where('id = ', $refId)->update('leadtime', $data);
      }
      function deleteleadtime($refId){
      $this->db->where('id ', $refId)->delete('leadtime');
      }
     */

    function listAllProdImages($offset = false, $limit = false) {
        if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);
        return $this->db->Select('product_image.* , product.product_name')
                        ->from('product')
                        ->join('product_image', 'product.product_id = product_image.product_id', 'left')
                        ->get()->result_array();
    }

    function countAllProdImages() {
        return $this->db->from('product')
                        ->join('product_image', 'product.product_id = product_image.product_id', 'left')
                        ->count_all_results();
    }

    function removeImage($product) {
        $path = $this->config->item('PRODUCT_IMAGE_PATH');
        $filename = $path . $product['product_image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }
        $data = array();
        $data['product_image'] = '';
        $this->db->where('product_id', $product['product_id'])->update('product', $data);
    }

    /* Function make drop down, it gather all products options in single one */

    function prodAttributes($prods, $isSimple = false) {
        $productAttrComb = [];
        if ($prods) {
            $this->load->model('cpcatalogue/Attributemodel');
            $this->load->model('cpcatalogue/Attrsetattroptionmodel', 'SetAttrOpt');
            $attribute_set_ids = array_unique(array_column($prods, 'attribute_set_id'));
            $opts = [ 'attribute_set_ids' => $attribute_set_ids];
            $attrbSetAttrbDet = $this->Attributemodel->getAttrbDetailsViaSetIds($opts);
            $attrbSetAttrbDet = com_makeArrIndexToField($attrbSetAttrbDet, 'id');

            $attrbSetattr = []; /* Hold Attr Details */
            $attrbSetattrLabel = []; /* Hold Label */
            $attrbSetattrOth = []; /* Hold Other Attr */
            $attrbSetattrSys = []; /* Hold System Attr */
            $attrbSetattrConf = []; /* Hold Config Attr */
            foreach ($attrbSetAttrbDet as $attrbKey => $attrbDet) {
                if ($attrbDet['is_sys'] == 1) {
                    $attrbSetattrSys[$attrbDet['set_id']][] = $attrbDet['sys_label'];
                } else {
                    $attrbSetattrOth[$attrbDet['set_id']][] = $attrbDet['id'];
                }
                if ($attrbDet['is_config'] == 1) {
                    $attrbSetattrConf[$attrbDet['set_id']][] = $attrbDet['id'];
                }
                $attrbSetattr[$attrbDet['set_id']][$attrbDet['id']] = $attrbDet;
                $attrbSetattrLabel[$attrbDet['set_id']][$attrbDet['id']] = $attrbDet['label'];
            }
            $param = [];
            $param['where']['in'][0] = 'attribute_id';
            $param['where']['in'][1] = is_array($attrbSetAttrbDet) && $attrbSetAttrbDet ?
                    array_keys($attrbSetAttrbDet) : [''];
            $attrOpts = $this->SetAttrOpt->get_all($param);
            foreach ($prods as $pIndex => $pRef) {
                $prodIds = [];
                $configProdDet = [];
                $configProdIds = [];
                /* Configurablae */
                if ($pRef['product_type_id'] == '2') {
                    $select = 'product_id,product_name';
                    if (isset($attrbSetattrSys[$pRef['attribute_set_id']]) && $attrbSetattrSys[$pRef['attribute_set_id']]) {
                        /* Add system variable to select */
                        $select .= ', ' . implode($attrbSetattrSys[$pRef['attribute_set_id']]);
                    }
                    $configProdDet = $this->db->select($select)
                                    ->from('product')
                                    ->where('ref_product_id', $pRef['product_id'])->get()->result_array();
                    $configProdIds = array_column($configProdDet, 'product_id');
                    $prodIds = $configProdIds;
                }
                sort($prodIds);
                $curProdDet = $pRef;
                $prodIds[] = $pRef['product_id'];
                $opts = [];
                $opts['pRef'] = $pRef;
                $opts['pIds'] = $prodIds;
                $opts['isSimple'] = $isSimple;
                $opts['attrOpts'] = $attrOpts;
                $opts['configProdDet'] = $configProdDet;
                $opts['attrbSetattrConf'] = com_arrIndex($attrbSetattrConf, $pRef['attribute_set_id'], '');
                $opts['setNonSysAttrbId'] = com_arrIndex($attrbSetattrOth, $pRef['attribute_set_id'], '');
                $opts['attrbSetattr'] = com_arrIndex($attrbSetattr, $pRef['attribute_set_id'], []);
                $opts['attrbSetattrLabel'] = com_arrIndex($attrbSetattrLabel, $pRef['attribute_set_id'], []);
                $prdAttrDetails = [
                    'pIds' => $prodIds,
                    'prdAttrLblOpts' => '',
                    'configOptionComb' => '',
                    'attrbCountDetails' => '',
                    'attrbSetattrConf' => com_arrIndex($attrbSetattrConf, $pRef['attribute_set_id'], []),
                    'attrbSetattr' => com_arrIndex($attrbSetattr, $pRef['attribute_set_id'], [])
                ];
                $this->_makeAttrComb($opts, $prdAttrDetails);
                $this->_makeConfigComb($prdAttrDetails);
                $attr_set_attr_conf_det = $prdAttrDetails[ 'attrbSetattrConf' ];
                $attr_set_attr_conf_det_opts = $prdAttrDetails[ 'prdAttrLblOpts' ];
                $user_related_attr_opts_keys = [];
                $prod_related_attr_opts_keys = [];
				
				if( isset( $attr_set_attr_conf_det_opts[ 'user' ] ) ){
					$user_related_attr_opts_keys = array_keys( $attr_set_attr_conf_det_opts[ 'user' ] );
				}
				if( isset( $attr_set_attr_conf_det_opts[ 'product' ] ) ){
					$prod_related_attr_opts_keys = array_keys( $attr_set_attr_conf_det_opts[ 'product' ] );
				}
				if( $attr_set_attr_conf_det ) {
					$exist_conf_attr = [];
					foreach( $attr_set_attr_conf_det as $st_ind => $st_attr ){
						if( in_array($st_attr, $user_related_attr_opts_keys) 
						|| in_array($st_attr, $prod_related_attr_opts_keys) 
						){						
							$exist_conf_attr[ ] = $st_attr;
						}
					}
					$prdAttrDetails[ 'attrbSetattrConf' ] = $exist_conf_attr;
				}
                $productAttrComb[$pRef['product_id']] = $prdAttrDetails;
            }
        }
        return $productAttrComb;
    }

    private function _makeAttrComb($opts, &$prdAttrDetails) {
        extract($opts);
        /* if Attr set: Non System Attrb Ids exist */
        $prodAttr = [];
        $prodAttrCount = [];
        $prodExistAttrId = [];
        $prodAttOptsAttr = []; /* It holds the exist product attributes  */
        $filteredProdAttrOpts = [];
        if ($setNonSysAttrbId) {
            $prodAttOpts = $this->db
                    ->from('product_attribute')
                    ->where_in('product_id', $pIds)
                    ->where_in('attribute_id', $setNonSysAttrbId)
                    ->order_by('attribute_id,product_id')
                    ->get()
                    ->result_array();
            if ($prodAttOpts) {
                foreach ($prodAttOpts as $attrIndex => $attrDet) {
                    if ($attrDet['attribute_value']) {
                        $prodAttOptsAttr[$attrDet['attribute_id']][] = $attrDet['attribute_value'];
                    }
                }
                $prodExistAttrId = array_keys($prodAttOptsAttr);

                /* loop on existed product attr ids */

                foreach ($attrOpts as $optIndex => $optDet) {
                    if (in_array($optDet['attribute_id'], $prodExistAttrId) && in_array($optDet['id'], $prodAttOptsAttr[$optDet['attribute_id']])) {
                        $filteredProdAttrOpts[$optDet['attribute_id']][$optDet['id']] = $optDet['option_text'];
                    }
                }
            }
        }

        if (is_array($attrbSetattr)) {
            ksort($attrbSetattr);
            $index = 0;
            foreach ($attrbSetattr as $attrbId => $attrDet) {
                if (!isset($prodAttrCount['user'])) {
                    $prodAttrCount['user'] = 0;
                    $prodAttrCount['product'] = 0;
                }
                $options = [];
                if ($attrDet['is_sys']) {
                    if ($pRef['product_type_id'] == '2') { /* If sys attribute and configurable product */
                        /* From config products will select system value   */
                        /* We can assign product id and specific field value */
                        $options = array_column($configProdDet, $attrDet['sys_label'], $attrDet['sys_label']);
                    }
                    /*  Here If system attribute then pick system value */
                    if ($isSimple) {
                        /* if product is normal */
                        $options = $pRef[$attrDet['sys_label']];
                    } else {
                        $options[$pRef[$attrDet['sys_label']]] = $pRef[$attrDet['sys_label']];
                    }
                } else if (in_array($attrbId, $prodExistAttrId)) {
                    /* If product options available for particular attribute */
                    $options = $filteredProdAttrOpts[$attrbId];
                }
                /* Drop Down Label */
                /* If option exist for a product */
                if ($options) {
                    if ($pRef['product_type_id'] == '2') {
                        $options['0'] = 'Select ' . $attrDet['label'];
                        ksort($options);
                    }
                    /* Assign attrib options */
                    if ($attrDet['is_userrelated']) {
                        $prodAttrCount['user'] += 1;
                        $prodAttr['user'][$attrbId]['label'] = $attrDet['label'];
                        $prodAttr['user'][$attrbId]['attrOpts'] = $options;
                    } else {
                        $prodAttrCount['product'] += 1;
                        $prodAttr['product'][$attrbId]['label'] = $attrDet['label'];
                        $prodAttr['product'][$attrbId]['attrOpts'] = $options;
                    }
                    $index++;
                }
            }
        }

        $prdAttrDetails['prdAttrLblOpts'] = $prodAttr;
        $prdAttrDetails['attrbCountDetails'] = $prodAttrCount;
    }

    private function _makeConfigComb(&$prodDet) {
        $configAttrComb = [];
        $configDet = $prodDet['attrbSetattrConf'];
        if ($configDet && is_array($configDet)) {
            $ttlIndex = sizeof($configDet);
            for( $configStIndex = 0; $configStIndex < ( $ttlIndex - 1 ); $configStIndex++  ){
				$cnfAttr = $configDet[ $configStIndex ];
				$availableVal = [];
				if (isset($prodDet['prdAttrLblOpts']['product'][$cnfAttr]['attrOpts'])) {
					$availableVal = $prodDet['prdAttrLblOpts']['product'][$cnfAttr]['attrOpts'];
				} else if (isset($prodDet['prdAttrLblOpts']['user'][$cnfAttr]['attrOpts'])) {
					$availableVal = $prodDet['prdAttrLblOpts']['user'][$cnfAttr]['attrOpts'];
				}
								
				if( is_array( $availableVal ) && sizeof( $availableVal ) > 1 ){
					$nextCAttr = 0;
					for( $checkNextAttr = ($configStIndex+1); $checkNextAttr < $ttlIndex; $checkNextAttr++ ){
						$newCnfAttr = $configDet[ $checkNextAttr ];
						if (isset($prodDet['prdAttrLblOpts']['product'][$newCnfAttr]['attrOpts'])) {
							$nextCAttr = $newCnfAttr;
						} else if (isset($prodDet['prdAttrLblOpts']['user'][$newCnfAttr]['attrOpts'])) {
							$nextCAttr = $newCnfAttr;
						}
						if( $nextCAttr ){
							break;
						}
					}
					if( $nextCAttr ){
						$configAttrComb[$cnfAttr]['next-attr'] = $nextCAttr;
					}
				}
			}			
		}		
        $prodDet['configOptionComb'] = $configAttrComb;
    }

    /* check for config product with post variables */

    function getConfigProdFromAttr($opt = []) {
        extract($opt);
        $attribute_keys = array_keys($attribute);
        $sys_attr = [];
        if ($attribute_keys && $attribute) {
            $param = [];
            $param['select'] = 'id, sys_label';
            $param['from_tbl'] = 'attributes_set_attributes';
            $param['where']['is_sys'] = '1';
            $param['where']['in'] = [ '0' => 'id', '1' => $attribute_keys];
            $sys_attr = $this->get_all($param);
        }

        $param = [];
        $param['select'] = 'product_id';
        $param['where']['ref_product_id'] = $product_id;
        $prods = $this->get_all($param);
        $prods = com_makelist($prods, 'product_id', 'product_id', false);
        $prods[$product_id] = $product_id;

        $param = [];
        $param['select'] = 'prod.product_id, attribute_id, attribute_value';
        $param['result'] = 'obj';
        $param['join'][] = [ 'tbl' => $this->db->dbprefix('product_attribute') . ' as prodAtr',
            'cond' => 'prod.product_id=prodAtr.product_id',
            'type' => 'left',
            'pass_prefix' => true
        ];
        foreach ($sys_attr as $sAttrK => $sAttrV) {
            $param['where'][$sAttrV['sys_label']] = $attribute[$sAttrV['id']];
            unset($attribute[$sAttrV['id']]);
        }
        $param['where']['in_array'][0][0] = 'prod.product_id';
        $param['where']['in_array'][0][1] = $prods;
        $prodDetWithAttr = $this->get_all($param);


        $filtered_product_id = 0;
        if ($attribute) {
            /** Include path For PLINQ* */
            set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/plinq/Classes');
            /** PHPLinq_LinqToObjects */
            require_once 'PHPLinq/LinqToObjects.php';
            $prod_stack_for_filter = $prods;
            foreach ($attribute as $attKey => $attVal) {
				$filtered_prods = $prod_stack_for_filter;
				unset( $prod_stack_for_filter );
                foreach ($prods as $prodKey => $prodVal) {
                    $searchProd = new stdClass();
                    $searchProd->product_id = $prodVal;
                    $searchProd->attribute_id = $attKey;
                    $searchProd->attribute_value = $attVal;
                    $filResultStatus = from('$filterResult')->in($prodDetWithAttr)
                            ->contains($searchProd);
                    if ($filResultStatus) {
						$filtered_product_id = $prodVal;
						$prod_stack_for_filter[ $prodVal ] = $prodVal;                        
                    }
                }
            }
        } else {
            $filtered_product_id = $prodDetWithAttr[0]->product_id;
        }
        return $filtered_product_id;
    }

    function getProdAttribSetAndSetAtrributes($prodId = null, $prodAttr = null) {		
        if ($prodAttr && is_array($prodAttr)) {
            $param = [];
            $param['select'] = 'sys_label, is_sys, attrSetAtt.id, label';
            $param['join'][] = [ 'tbl' => $this->db->dbprefix('attributes_set_attributes') . ' as attrSetAtt',
                'cond' => 'prod.attribute_set_id=attrSetAtt.set_id',
                'type' => 'inner',
                'pass_prefix' => true
            ];
            if (is_array($prodAttr)) {
                $param['where']['in_array'][0][0] = 'attrSetAtt.id';
                $param['where']['in_array'][0][1] = $prodAttr;
            }
            $param['where']['prod.product_id'] = $prodId;
            return $this->get_all($param);
        }
        return array();
    }

    function getProdWithAttrDetail($prodId = null, $optParam = []) {
        $param = [];
        $param['where']['ref_product_id'] = $prodId;
        $confIds = $this->get_all($param);
        $confIds = com_makelist($confIds, 'product_id', 'product_id', false);
        $confIds[$prodId] = $prodId;
        $param = [];
        if( isset( $optParam['where'] ) ){
			$param['where'] = $optParam['where'];	
		}        
        /* $param[ 'select' ] =   'prod.product_id, prod.product_name, 
          prod.product_sku, prod.category_id,
          prod.product_image, prod.product_alias,
          prod.product_point, prod.product_price,
          prodAttr.attribute_id,
          prodAttr.attribute_value
          '; */
        $param['select'] = 'prod.product_id,
                                prodAttr.attribute_id,
                                prodAttr.attribute_value
                            ';
        $param['join'][] = [ 'tbl' => $this->db->dbprefix('product_attribute') . ' as prodAttr',
            'cond' => 'prod.product_id=prodAttr.product_id',
            'type' => 'inner',
            'pass_prefix' => true
        ];
        $param['where']['in_array'][0][0] = 'prod.product_id';
        $param['where']['in_array'][0][1] = $confIds;
        return $this->get_all($param);
    }
}
