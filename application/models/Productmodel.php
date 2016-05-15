<?php

class Productmodel extends Commonmodel {

    function __construct() {
        parent::__construct();        
    }

    /* Return product price as per allocated list */
    function getProductPriceFromPriceList( $priceList, $productSku ){
        $prodPrice = 0;
        if( $priceList ){
            $param = [];
            $param['result'] = 'row';
            $param['select'] = 'price';
            $param['where']['price_list'] = $priceList;
            $param['where']['product_sku'] = $productSku;
            $param['from_tbl'] = 'product_price_list';
            $prodPrice = $this->get_all( $param );
            if( !$prodPrice ){
                $prodPrice = 0;
            }else{
                $prodPrice = $prodPrice['price'];
            }
        }
        return $prodPrice;
    }
    //Function Get Details Of Product
    function details($pid, $main = false) {

        $this->db->from('product');
        $prodRef = intval( $pid );
        if( !$prodRef ){
            $this->db->where('product_sku', $pid);
        }else{
            $this->db->where('product_id', intval($pid));
        }
        if($main){
            $this->db->where('ref_product_id','0');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }
    
    function getOffsetIndex($pid, $main = false, $offset = 15) {
        if($main){
            $this->db->where('ref_product_id','0');
        }
        $query =    $this->db->select('CEIL(count(product_id)/'.$offset.') as offset')->from('product')
                            ->where('product.product_id < ', intval($pid))
                            ->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array('offset' => 0);
    }

    function checkProductExist($product_id){
        return $this->db->from('product')
                            ->where('product.product_id', intval($product_id))
                            ->get()->num_rows();
    }

    function getProduct($product = [], $param = []) {
        if( isset($param['fields']) && !empty($param['fields']) ){
            $this->db->select($param['fields']);
        }
        if( isset($param['offset']) && !empty($param['offset']) ){
            $this->db->offset($param['offset']);
        }        
        if( isset($param['limit']) && !empty($param['limit']) ){
            $this->db->limit($param['limit']);
        }
        if( isset($param['where']) && !empty($param['where']) ){
            foreach($param['where'] as $whereIndex){                
                $this->db->where($whereIndex[0], $whereIndex[1]);
            }            
        }        
        $this->db->from('product');
        if($product){
            $this->db->where('product_id !=', $product['product_id']);
        }
        $rs = $this->db->get();
        if( isset($param['result']) && !empty($param['result']) ){
            if($param['result'] == 'row'){
                return $rs->row_array();
            }else{
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
        if($result->num_rows()){
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

    function deleteAttributes($pid){

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

    function countAllProducts($cid = '') {
        if ($this->input->post('category') != '') {
            $this->db->from('product')                    
                    ->where('category_id', intval($cid))
                    ->where('ref_product_id', '0');
            return $this->db->count_all_results();
        } else if($this->input->post('prodName') != ''){
            $prod_text = $this->input->post('prodName');
			$this->db->from('product')
                    ->where('ref_product_id', '0')
					->where('product_name like "%'.$prod_text.'%" or product_alias like "%'.$prod_text.'%"');
			return $this->db->count_all_results();
        }else {
            $this->db->from('product')->where('ref_product_id', '0');
            return $this->db->count_all_results();
        }
    }

    //list all Product
    function listAllProducts($cid, $offset = FALSE, $limit = FALSE) {
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
		
        if ($this->input->post('category') != '') {
            $this->db->where('prod.category_id', intval($cid));
            
        }  else if( !empty($this->input->post('prodName'))){
            $prod_text = $this->input->post('prodName');
			$this->db->where('product_name like "%'.$prod_text.'%" or product_alias like "%'.$prod_text.'%"');			
        }
        return $this->db->get()->result_array();        
    }


    /**
     * Insert product from post data via api
     */
    function insertFromXMLRecord($data){
            $response = false;
            /*
                if(is_object($data[0])){
                    $data = $data[0]->GetByKeyResponse;
                }else if(is_array($data[0])){
                    $data = $data[0]['GetByKeyResponse'];
                }
            */
            $data = $data[0]['GetByKeyResponse'];
            if($data) {                
                $product_xml_fields = ['ItemCode', 'ItemName', 'QuantityOnStock', 'Mainsupplier', 'ItemsGroupCode'];
                $product_data_fields = ['ItemCode' => 'product_sku', 
                                    'ItemName' => 'product_name',
                                    'Mainsupplier' => 'supplier_id', 
                                    'ItemsGroupCode' => 'category_id',
                                    'QuantityOnStock' => 'stock_level', 
                                ];

                $pricelist_xml_fields = ['PriceList', 'Price', 'Currency'];
                $pricelist_data_fields = ['Price' => 'price',
                                    'Currency' => 'currency',
                                    'PriceList' => 'price_list',
                                ];
                if(!isset($data[0])){                    
                    $data = [ '0' => $data];
                }

                foreach($data as $data_index => $data_row){
                    $item_code = NULL;
                    $product_insert = [];
                    $product_price_list_insert = [];
                    $prod_xml_fields = ['stock_level' => '0',
                                        'category_id' => '0',
                                        'product_sku' => '',
                                        'product_name' => '',
                                        'supplier_id' => '0',                                        
                                        ];                                        

                    /*
                     *  Item Field
                     */
                    if(isset($data_row['BOM']['BO']['Items']['row']) && !is_null($data_row['BOM']['BO']['Items']['row']) ) {

                        foreach((array)$data_row['BOM']['BO']['Items']['row'] as $key => $val) {
                            #if(in_array($key, $product_xml_fields) && !is_object($val)){
                            if(in_array($key, $product_xml_fields) && !is_array($val)){
                                $prod_xml_fields[$product_data_fields[$key]] = $val;
                            }
                        }
                        $prod_xml_fields['attribute_set_id'] = 1;
                        
                        $item_code = $prod_xml_fields['product_sku'];                        
                        if(!empty($item_code)) {
                            $param['result'] = 'row';
                            $param['fields'] = 'product_added_on, product_updated_on, product_sku';
                            $param['where'] = [['product_sku' , $item_code]];
                            $existProduct = $this->getProduct('', $param);
                            if($existProduct){
                                $product_added_on = $existProduct['product_added_on'];
                                $product_updated_on = time();
                            }else{
                                $product_added_on = time();
                                $product_updated_on = 0;
                            }
                            $product_insert = [
                                                    'ref_product_id' => '0',
                                                    'product_type_id' => '1',
                                                    'attribute_set_id' => $prod_xml_fields['attribute_set_id'],
                                                    'product_sku' => $item_code,
                                                    'stock_level' => $prod_xml_fields['stock_level'],
                                                    'supplier_id' => $prod_xml_fields['supplier_id'],
                                                    'category_id' => $prod_xml_fields['category_id'],
                                                    'product_name' => $prod_xml_fields['product_name'],
                                                    'product_alias' => $this->_slug($prod_xml_fields['product_name']),
                                                    'product_sort_order' => $this->getOrder(),
                                                    'product_added_on' => $product_added_on,
                                                    'product_updated_on' => $product_updated_on,
                                                    /*
                                                        'product_description' => '',
                                                        'technical_detail' => '',
                                                        'product_meta_title' => '',
                                                        'product_meta_keywords' => '',
                                                        'product_meta_description' => '',
                                                    */
                                                ];                            
                            
                            if($existProduct){
                                $this->db->where('product_sku', $item_code)
                                        ->update('product', $product_insert);
                                $this->db->where('product_sku', $item_code)
                                        ->delete('product_price_list');
                            }else{
                                $this->db->insert('product', $product_insert);
                            }
                            $price_rows = $data_row['BOM']['BO']['Items_Prices']['row'];
                            foreach($price_rows as $key => $val){
                                $prodlist_xml_fields = [ 'product_sku' => '',
                                         'price_list' => '',
                                         'price' => '',
                                         'currency' => '',
                                        ];
                                $prodlist_xml_fields['product_sku'] = $item_code;
                                foreach((array)$val as $price_index => $price_data){
                                    if(in_array($price_index, $pricelist_xml_fields) && !is_array($price_data)){
                                        $prodlist_xml_fields[$pricelist_data_fields[$price_index]] = $price_data;
                                    }
                                }
                            $product_price_list_insert[] = $prodlist_xml_fields;
                            }
                        }
                        $this->db->insert_batch('product_price_list', $product_price_list_insert);
                        $response = true;
                    }                    
                }
        }
        return $response;
    }

    //Function Add Record
    function insertRecord() {
        $ref_product_id = 0;
        $attribute = $this->input->post('attribute', true);
        $attribute_key_id = array();
        if($attribute){
            $attribute_key_id = array_keys($attribute);
        }
        $name = $this->input->post('product_name', true);
        $sku = $this->input->post('product_sku', true);
        $price = $this->input->post('product_price', TRUE);
        $point = $this->input->post('product_point', true);
        $supplier = $this->input->post('supplier_id', true);
        $stock = $this->input->post('stock_level', true);
        $weight = $this->input->post('weight', true);
        $alias = $this->input->post('product_alias', TRUE);
        $ALL_IMAGES = $_FILES;
        foreach ($name as $key => $value) {
            $data = array();            
            if (isset($ALL_IMAGES['product_image']['name'][$key])) {
                $file_name = $ALL_IMAGES['product_image']['name'][$key];
                $db_file_name = "";
                if ($ALL_IMAGES['product_image']['error'][$key] == UPLOAD_ERR_OK 
                    && is_uploaded_file($ALL_IMAGES['product_image']['tmp_name'][$key])) {
                    $_FILES['product_image']['name']= $ALL_IMAGES['product_image']['name'][$key];
                    $_FILES['product_image']['type']= $ALL_IMAGES['product_image']['type'][$key];
                    $_FILES['product_image']['tmp_name']= $ALL_IMAGES['product_image']['tmp_name'][$key];
                    $_FILES['product_image']['error']= $ALL_IMAGES['product_image']['error'][$key];
                    $_FILES['product_image']['size']= $ALL_IMAGES['product_image']['size'][$key];
                    $current_time = time();
                    $db_file_name = $current_time.'-'.$file_name;
                    $config['overwrite'] = FALSE;
                    $config['allowed_types'] = 'jpg|jpeg|gif|png';
                    $config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH')  ;                    
                    $config['encrypt_name'] = TRUE;
                    /*
                    *Old Way
                    *$this->load->library('upload');
                    *$this->upload->initialize($config);
                    *New Way
                    *$this->load->library('upload', $config);
                    */
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('product_image')) {
                        show_error($this->upload->display_errors('<p class="err">', '</p>'));
                        return FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $data['product_image'] = $upload_data['file_name'];
                        $image_url = $this->config->item('PRODUCT_IMAGE_URL').$data['product_image'];
                        $resize_image_url = $this->config->item('PRODUCT_RESIZE_IMAGE_URL');
                        $resize_image_path = $this->config->item('PRODUCT_RESIZE_IMAGE_PATH');                
                        resize($image_url, $resize_image_url, $resize_image_path, 50, 50);
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
            $data['stock_level'] = floatval($stock[$key]);
            $data['weight'] = intval($weight[$key]);
            $data['product_type_id'] = $this->input->post('product_type_id', false);
            $data['attribute_set_id'] = $this->input->post('attribute_set_id', false);
            $data['product_description'] = $this->input->post('product_description', false);
            $data['product_meta_title'] = $this->input->post('product_meta_title', true,"");
            $data['product_meta_keywords'] = $this->input->post('product_meta_keywords', true);
            $data['product_meta_description'] = $this->input->post('product_meta_description', true);
            $data['technical_detail'] = $this->input->post('technical_detail', false);
            $data['is_featured'] = $this->input->post('is_featured', true, 0);
            $data['is_heavyduty'] = $this->input->post('is_heavyduty', true, 0);
            $data['product_added_on'] = time();
            $data['product_is_active'] = 1;
            if(empty($alias[$key])){
                $data['product_alias'] = $this->_slug($value);
            }else{
                $data['product_alias'] = url_title($alias[$key]);
            }
            $data['product_sort_order'] = $this->getOrder();
            $this->db->insert('product', $data);
            $prod_id = $this->db->insert_id();
        
            if($key){
                $ref_product_id = $prod_id;
            }
            if($attribute_key_id){
                $insert_attribute = array();
                foreach ($attribute_key_id as $attr_key => $value) {
                    
                    $insert_attribute[] = array('product_id' => $prod_id,
                                    'attribute_id' => $value,
                                    'attribute_value' => $attribute[$value][$key]
                                );
                }
                $this->db->insert_batch('product_attribute', $insert_attribute);
            }
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
        if($attribute){
            $attribute_key_id = array_keys($attribute);
        }

        $name = $this->input->post('product_name', true);        
        $sku = $this->input->post('product_sku', true);
        $price = $this->input->post('product_price', TRUE);
        $point = $this->input->post('product_point', true);
        $supplier = $this->input->post('supplier_id', true);
        $stock = $this->input->post('stock_level', true);
        $weight = $this->input->post('weight', true);
        $alias = $this->input->post('product_alias', TRUE);
        $category_id = $this->input->post('category_id', true);
        $product_type_id = $product['product_type_id'];
        $ALL_IMAGES = $_FILES;
        foreach ($name as $key => $value) {
            $data = array();
            if (isset($ALL_IMAGES['product_image']['name'][$key])) {
                $file_name = $ALL_IMAGES['product_image']['name'][$key];
                $db_file_name = "";
                if ($ALL_IMAGES['product_image']['error'][$key] == UPLOAD_ERR_OK 
                    && is_uploaded_file($ALL_IMAGES['product_image']['tmp_name'][$key])) {
                    $_FILES['product_image']['name']= $ALL_IMAGES['product_image']['name'][$key];
                    $_FILES['product_image']['type']= $ALL_IMAGES['product_image']['type'][$key];
                    $_FILES['product_image']['tmp_name']= $ALL_IMAGES['product_image']['tmp_name'][$key];
                    $_FILES['product_image']['error']= $ALL_IMAGES['product_image']['error'][$key];
                    $_FILES['product_image']['size']= $ALL_IMAGES['product_image']['size'][$key];
                    $current_time = time();
                    $db_file_name = $current_time.'-'.$file_name;
                    $config['overwrite'] = FALSE;
                    $config['allowed_types'] = 'jpg|jpeg|gif|png';
                    $config['upload_path'] = $this->config->item('PRODUCT_IMAGE_PATH')  ;
                    $config['file_name'] = $db_file_name;
                    /*
                    *Old Way
                    *$this->load->library('upload');
                    *$this->upload->initialize($config);
                    *New Way
                    *$this->load->library('upload', $config);
                    */
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('product_image')) {
                        show_error($this->upload->display_errors('<p class="err">', '</p>'));
                        return FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $data['product_image'] = $upload_data['file_name'];
                        $image_url = $this->config->item('PRODUCT_IMAGE_URL').$data['product_image'];
                        $resize_image_url = $this->config->item('PRODUCT_RESIZE_IMAGE_URL');
                        $resize_image_path = $this->config->item('PRODUCT_RESIZE_IMAGE_PATH');                
                        resize($image_url, $resize_image_url, $resize_image_path, 50, 50);
                    }
                }
            }

            if($key !== (int)$product['product_id']){
                $data['ref_product_id'] = $ref_product_id;
            }
            $data['category_id'] = $category_id;
            $data['product_name'] = $value;
            $data['product_sku'] = $sku[$key];
            $data['product_price'] = floatval($price[$key]);
            $data['product_point'] = floatval($point[$key]);
            $data['supplier_id'] = intval($supplier);
            $data['stock_level'] = floatval($stock[$key]);
            $data['weight'] = intval($weight[$key]);
            $data['product_type_id'] = $product_type_id;
            $data['product_description'] = $this->input->post('product_description', false);
            $data['product_meta_title'] = $this->input->post('product_meta_title', true,"");
            $data['product_meta_keywords'] = $this->input->post('product_meta_keywords', true);
            $data['product_meta_description'] = $this->input->post('product_meta_description', true);
            $data['technical_detail'] = $this->input->post('technical_detail', false);
            $data['is_featured'] = $this->input->post('is_featured', true, 0);
            $data['is_heavyduty'] = $this->input->post('is_heavyduty', true, 0);            
            $data['product_updated_on'] = time();
            $data['product_is_active'] = 1;
            if(empty($alias[$key])){
                $data['product_alias'] = $this->_slug($value);
            }else{
                $data['product_alias'] = url_title($alias[$key]);
            }

            
            if(!$this->checkProductExist($key)){
                $data['product_added_on'] = time();
                $data['product_sort_order'] = $this->getOrder();
                $this->db->insert('product', $data);
                $prod_id = $this->db->insert_id();
                $update = false;
            }else{
                $this->db->where('product_id', $key)
                        ->update('product', $data);
                $prod_id = $key;
                $update = true;
            }
            if($attribute_key_id){
                $insert_attribute = array();
                foreach ($attribute_key_id as $attr_key => $value) {
                    $insert_attribute[] = array('product_id' => $prod_id,
                                    'attribute_id' => $value,
                                    'attribute_value' => $attribute[$value][$key]
                                );
                }
                $this->db->where('product_id',$prod_id)->delete('product_attribute');
                $this->db->insert_batch('product_attribute', $insert_attribute);
            }
        }
    }


    function disableRecord($product, $action) {
        $data = array();
        //delete product
        $data['product_is_active'] = $action?'1':'0';
        $this->db->where('product_id', $product['product_id']);
        $this->db->update('product', $data);
    } 

    //Delete Product
    function deleteProduct($product) {
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
        */

        //delete product categories
        $this->db->where('product_id', $product['product_id']);
        $this->db->delete('product_category');

        //delete product attributes
        $this->db->where('product_id', $product['product_id']);
        $this->db->delete('product_attribute');

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
        $this->db->where('category_id', intval($cid));
        if ($pid)
            $this->db->where('product_id !=', intval($pid));
        $rs = $this->db->get('product');
        return $rs->result_array();
    }

    function getCurrentProducts($pid) {
        $this->db->select('related_product.*, product.product_name');
        $this->db->from('related_product');
        $this->db->where('related_product.product_id', intval($pid));
        $this->db->join('product', 'product.product_id = related_product.product_id');
        $rs = $this->db->get();
        return $rs->result_array();
        /* if ($rs->num_rows() > 0) {
          return $rs->result_array();
          } else {
          return $rs->row_array();
          } */
    }

    function checkAttrUnique($param){
        if($param['product_id']){
            $this->db->where('product_id != ', $param['product_id']);
        }
        if($param['sys']){
            $rs = $this->db->where($param['field'], $param['value'])->from('product')->get();
        }else{
            $search_val = explode('_', $param['field']);
            $rs = $this->db->where('attribute_id', $search_val[1])
                    ->where('attribute_id', $param['value'])
                    ->from('product_attribute')->get();
        }        
        if($rs->num_rows()){
            return false;
        }else{
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
		$this->db->where('id = ', $refId)
				->update('leadtime', $data);
	}
	
	function deleteleadtime($refId){
		$this->db->where('id ', $refId)
				->delete('leadtime');
	}
    */
	
	function listAllProdImages($offset = false, $limit = false){
		if ($offset)
            $this->db->offset($offset);
        if ($limit)
            $this->db->limit($limit);
		return $this->db->Select('product_image.* , product.product_name')
					->from('product')
					->join('product_image', 'product.product_id = product_image.product_id', 'left')
					->get()->result_array();
	}
	
	function countAllProdImages(){
		return $this->db->from('product')
					->join('product_image', 'product.product_id = product_image.product_id', 'left')
					->count_all_results();
	}

    function removeImage($product){

        $path = $this->config->item('PRODUCT_IMAGE_PATH');
        $filename = $path . $product['product_image'];
        if (file_exists($filename)) {
            @unlink($filename);
        }

        $data = array();
        $data['product_image'] = '';
        $this->db->where('product_id', $product['product_id'])->update('product', $data);
    }


    function prodAttributes( $prods ){
        $productAttrComb = [];
        
        if( $prods ){
            $this->load->model('cpcatalogue/Attributemodel');
            $this->load->model('cpcatalogue/Attrsetattroptionmodel', 'SetAttrOpt');
            $attribute_set_ids = array_unique(array_column($prods , 'attribute_set_id'));
            $opts = [ 'attribute_set_ids' => $attribute_set_ids ];
            $attrbSetAttrbDet = $this->Attributemodel->getAttrbDetailsViaSetIds($opts);
            $attrbSetAttrbDet = com_makeArrIndexToField($attrbSetAttrbDet, 'id');

            $attrbSetattr = []; /* Hold Attr Details */
            $attrbSetattrLabel = []; /* Hold Label */
            $attrbSetattrOth = []; /* Hold Other Attr */
            $attrbSetattrSys = []; /* Hold System Attr */
            foreach ($attrbSetAttrbDet as $attrbKey => $attrbDet) {
                if($attrbDet['is_sys'] == 1){
                    $attrbSetattrSys[$attrbDet['set_id']][] = $attrbDet['sys_label'];
                }else{
                    $attrbSetattrOth[$attrbDet['set_id']][] = $attrbDet['id'];
                }

                $attrbSetattr[$attrbDet['set_id']][$attrbDet['id']] = $attrbDet;
                $attrbSetattrLabel[$attrbDet['set_id']][$attrbDet['id']] = $attrbDet['label'];
            }
            
            $param = [];
            $param['where']['in'][0] = 'attribute_id';
            $param['where']['in'][1] = is_array($attrbSetAttrbDet) && $attrbSetAttrbDet ? array_keys($attrbSetAttrbDet) : [''];
            $attrOpts = $this->SetAttrOpt->get_all($param);

            foreach( $prods as $pIndex => $pRef ){
                $prodIds = [];
                $configProdDet = [];
                $configProdIds = [];

                /* Configurablae */
                if($pRef['product_type_id'] == '2'){
                    
                    $select = 'product_id,product_name';
                    if( isset($attrbSetattrSys[ $pRef['attribute_set_id'] ]) 
                        && $attrbSetattrSys[ $pRef['attribute_set_id']] ){
                        /* Add system variable to select */                    
                        $select .= ', '.implode( $attrbSetattrSys[ $pRef['attribute_set_id'] ] );
                    }
                    $configProdDet = $this->db->select($select)
                                                ->from('product')
                                                ->where('ref_product_id' , $pRef['product_id'] )->get()->result_array();
                    $configProdIds = array_column( $configProdDet , 'product_id' );

                    $prodIds = $configProdIds;
                }

                $curProdDet = $pRef;
                $prodIds[] = $pRef['product_id'];
                
                $opts = [];
                $opts['pRef'] = $pRef;
                $opts['pIds'] = $prodIds;
                $opts['attrOpts'] = $attrOpts;
                $opts['configProdDet'] = $configProdDet;
                $opts['setNonSysAttrbId'] = com_arrIndex($attrbSetattrOth, $pRef['attribute_set_id'] , '');            
                $opts['attrbSetattr'] = com_arrIndex($attrbSetattr, $pRef['attribute_set_id'] , []);
                $opts['attrbSetattrLabel'] = com_arrIndex($attrbSetattrLabel, $pRef['attribute_set_id'] , []);

                $prdAttrDetails = [ 'prdAttrLblOpts' => '',
                                    'attrbCountDetails' => '',
                                ];
                $this->_makeAttrComb($opts , $prdAttrDetails);
                $productAttrComb[ $pRef['product_id'] ] = $prdAttrDetails;                
            }
        }
        return $productAttrComb;
    }

    private function _makeAttrComb($opts, &$prdAttrDetails){
        
        extract( $opts );
        /* if Attr set: Non System Attrb Ids exist */
        $prodAttr = [];
        $prodAttrCount = [];
        $prodExistAttrId = [];
        $prodAttOptsAttr = []; /* It holds the exist product attributes  */
        $filteredProdAttrOpts = [];
        if( $setNonSysAttrbId ){
            $prodAttOpts = $this->db
                                ->from('product_attribute')
                                ->where_in('product_id', $pIds )
                                ->where_in('attribute_id', $setNonSysAttrbId )
                                ->get()->result_array();
            if( $prodAttOpts ) {
                foreach( $prodAttOpts as $attrIndex => $attrDet ){
                    if( $attrDet['attribute_value'] ){
                        $prodAttOptsAttr[ $attrDet['attribute_id'] ][] = $attrDet['attribute_value'];
                    }                    
                }
                $prodExistAttrId = array_keys( $prodAttOptsAttr );

                /* loop on existed product attr ids */
            
                foreach( $attrOpts as $optIndex => $optDet ){
                    if( in_array( $optDet['attribute_id'], $prodExistAttrId ) 
                        && in_array($optDet['id'],  $prodAttOptsAttr[ $optDet['attribute_id'] ] ) ){
                        $filteredProdAttrOpts[$optDet['attribute_id']][ $optDet['id'] ] = $optDet['option_text'];
                    }
                }                
            }
        }

        if( is_array($attrbSetattr) ) {
            ksort($attrbSetattr);
            $index = 0;
            foreach($attrbSetattr as $attrbId => $attrDet){
                if( !isset($prodAttrCount['user']) ){
                    $prodAttrCount['user'] = 0;
                    $prodAttrCount['product'] = 0;
                }
                $options = [];
                if( $attrDet['is_sys'] ){
                    if( $pRef['product_type_id'] == '2' ){ /* If sys attribute and configurable product */
                        /* From config products will select system value   */
                        /* We can assign product id and specific field value */
                        $options = array_column( $configProdDet, $attrDet['sys_label'], $attrDet['sys_label'] );
                    }
                    /*  Here If system attribute then pick system value*/
                    $options[ $pRef[$attrDet['sys_label']] ] = $pRef[$attrDet['sys_label']];
                } else if( in_array( $attrbId, $prodExistAttrId ) ) {
                    /* If product options available for particular attribute */                    
                    $options = $filteredProdAttrOpts[ $attrbId ];
                }                
                /* Drop Down Label */
                /* If option exist for a product */
                if( $options ){
                    $options['0'] = 'Select '.$attrDet['label'];
                    ksort($options);                    
                    /* Assign attrib options */
                    if( $attrDet['is_userrelated']){
                        $prodAttrCount['user'] += 1;
                        $prodAttr['user'][$attrbId]['label'] = $attrDet['label'];
                        $prodAttr['user'][$attrbId]['attrOpts'] = $options;
                    }else{
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
}
