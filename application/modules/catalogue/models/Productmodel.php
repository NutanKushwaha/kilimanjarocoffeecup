<?php

class Productmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->tbl_name     = 'product';
        $this->tbl_pk_col   = 'product_id';
        $this->tbl_alias    = 'prod';        
    }

    function countAll($cid) {
        $query =  $this->db->where('category_id', $cid)
                    ->where('product_is_active', 1)
                    ->where('ref_product_id', 0)
                    ->get('product');
        return $query->num_rows();
    }

    function listAllByCategoryId($cid, $perpage = false, $pagination = false) {
        if ($perpage) {
            $this->db->limit($perpage);
        }
        if ($pagination) {
            $this->db->offset($pagination);
        }
        $this->db->where('category_id', $cid);
        $this->db->where('product_is_active', 1);
        $this->db->where('ref_product_id', 0);
        $query = $this->db->get('product');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    function details($pid, $main = false) {

        $this->db->from('product');
        $pid = html_escape($pid);
        if( ctype_digit( $pid ) ){
            $this->db->where('product_id', intval($pid));
        }else{
            $this->db->where('product_sku = "'.$pid.'"');
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

    function prodAttributes($prods) {
        $productAttrComb = [];

        if ($prods) {
            $this->load->model('catalogue/Attributemodel');
            $this->load->model('catalogue/Attrsetattroptionmodel', 'SetAttrOpt');

            $attribute_set_ids = array_unique(array_column($prods, 'attribute_set_id'));

            if (!$attribute_set_ids) {
                $attribute_set_ids = array('');
            }
            $param = [];
            $param['select'] = 'id,is_sys,label,sys_label,set_id,is_userrelated,required,is_config';
            $param['where']['in'][0] = 'set_id';
            $param['where']['in'][1] = $attribute_set_ids;
            $param['where']['asOptMark'] = '1';
            $param['where']['visible'] = '1';            
            $attrbSetAttrbDet = $this->Attributemodel->get_all($param);
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
                if($attrbDet['is_config'] == 1){
                    $attrbSetattrConf[$attrbDet['set_id']][] = $attrbDet['id'];
                }
                $attrbSetattr[$attrbDet['set_id']][$attrbDet['id']] = $attrbDet;
                $attrbSetattrLabel[$attrbDet['set_id']][$attrbDet['id']] = $attrbDet['label'];
            }

            $param = [];
            $param['where']['in'][0] = 'attribute_id';
            $param['where']['in'][1] = is_array($attrbSetAttrbDet) && $attrbSetAttrbDet ? array_keys($attrbSetAttrbDet) : array('');
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

                $curProdDet = $pRef;
                $prodIds[] = $pRef['product_id'];

                $opts = [];
                $opts['pRef'] = $pRef;
                $opts['pIds'] = $prodIds;
                $opts['attrOpts'] = $attrOpts;
                $opts['configProdDet'] = $configProdDet;
                $opts['attrbSetattrConf']   = com_arrIndex($attrbSetattrConf, $pRef['attribute_set_id'] , '');
                $opts['setNonSysAttrbId'] = com_arrIndex($attrbSetattrOth, $pRef['attribute_set_id'], '');
                $opts['attrbSetattr'] = com_arrIndex($attrbSetattr, $pRef['attribute_set_id'], []);
                $opts['attrbSetattrLabel'] = com_arrIndex($attrbSetattrLabel, $pRef['attribute_set_id'], []);                
                $prdAttrDetails = [ 
                                    'pIds' => $prodIds,
                                    'prdAttrLblOpts' => '',
                                    'configOptionComb' => '',
                                    'attrbCountDetails' => '',
                                    'attrbSetattrConf' => com_arrIndex($attrbSetattrConf, $pRef['attribute_set_id'] , []),
                                    'attrbSetattr' => com_arrIndex($attrbSetattr, $pRef['attribute_set_id'] , [])
                                ];                
                $this->_makeAttrComb($opts, $prdAttrDetails);
                $this->_makeConfigComb( $prdAttrDetails );
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

    function fetchByAlias($alias) {
        $this->db->select('*');
        $this->db->where('product_is_active', 1);
        $this->db->where('product_alias', $alias);
        $query = $this->db->get('product');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function fetchById($id) {
        $this->db->select('*');
        $this->db->where('product_is_active', 1);
        $this->db->where('product_id', $id);
        $query = $this->db->get('product');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function getAverageRating($product_id) {
        $this->db->select('avg(rating) as avgRating');
        $this->db->where('product_id', $product_id);
        $this->db->where('status', 1);
        $query = $this->db->get('review');
        return $query->row_array();
    }

    function getAlternateProducts($pid) {
        $CountDownsql = "(select concat((unix_timestamp(to_date)*1000),'-',price) from bg_front_special_price where (NOW() BETWEEN from_date AND to_date) and (bg_front_special_price.product_sku = bg_product.product_sku) and active = 1) as end_time";
        $this->db->select('*,(select avg(rating) from bg_review where product_id=bg_product.product_id and status = 1) as avgRating,'.$CountDownsql);
        $this->db->where('alternative_product.product_id', $pid);
        $this->db->where('product_is_active', '1');
        $this->db->join('product', 'product.product_sku = alternative_product.alt_product_id');
        $this->db->group_by('product.product_id');
        $query = $this->db->get('alternative_product');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    function getRelatedProducts($pid) {
        $CountDownsql = "(select concat((unix_timestamp(to_date)*1000),'-',price) from bg_front_special_price where (NOW() BETWEEN from_date AND to_date) and (bg_front_special_price.product_sku = bg_product.product_sku) and active = 1) as end_time";
        $this->db->select('*,(select avg(rating) from bg_review where product_id=bg_product.product_id and status = 1) as avgRating ,'.$CountDownsql);
        $this->db->where('related_product.product_id', $pid);
        $this->db->where('product_is_active', '1');
        $this->db->join('product', 'product.product_sku = related_product.rel_product_id');
        $this->db->group_by('product.product_id');
        $query = $this->db->get('related_product');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

    function getFeaturedProduct() {
        $ratingsql = "(select avg(rating) from bg_review where product_id=bg_product.product_id and status = 1) as avgRating";
        $CountDownsql = "(select concat((unix_timestamp(to_date)*1000),'-',price) from bg_front_special_price where (NOW() BETWEEN from_date AND to_date) and (bg_front_special_price.product_sku = bg_product.product_sku) and active = 1) as end_time";
        $this->db->select("*," . $ratingsql . ',' . $CountDownsql);
        $this->db->where('ref_product_id', '0');
        $this->db->where('is_featured', '1');
        $this->db->where('product_is_active', '1');
        $query = $this->db->get('product');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }
    
    function getProductImageGallery($sku)
    {
        $this->db->where('product_id',$sku);
        $query = $this->db->get('product_image');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
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
                            ->get()->result_array();
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
                    $options[$pRef[$attrDet['sys_label']]] = $pRef[$attrDet['sys_label']];
                } else if (in_array($attrbId, $prodExistAttrId)) {
                    /* If product options available for particular attribute */
                    $options = $filteredProdAttrOpts[$attrbId];
                }
                /* Drop Down Label */
                /* If option exist for a product */
                if ($options) {
                    $options['0'] = 'Select ' . $attrDet['label'];
                    ksort($options);
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

    function getProductImageBySku($pid) {
        $this->db->where('product_sku', $pid);
        $query = $this->db->get('product');
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result['product_image'];
        } else {
            return false;
        }
    }

    function get_special_price_by_sku($sku) {
        $this->db->select('*,(unix_timestamp(to_date)*1000) as end_time');
        $this->db->where('product_sku', $sku);
        $this->db->where('active', 1);
        $this->db->where("NOW() BETWEEN from_date AND to_date");
        $query = $this->db->get('front_special_price');
        if ($query->num_rows()) {
            //  e($query->row_array());
            return $query->row_array();
        } else {
            return false;
        }
    }
    /* check for config product with post variables */
    function getConfigProdFromAttr( $opt = [] ){
        extract( $opt );
        $attribute_keys = array_keys( $attribute );
        $sys_attr = [];
        if( $attribute_keys && $attribute ){        
            $param = [];
            $param[ 'select' ] = 'id, sys_label';
            $param[ 'from_tbl' ] = 'attributes_set_attributes';
            $param[ 'where' ][ 'is_sys' ] = '1';
            $param[ 'where' ][ 'in' ] = [ '0' => 'id', '1' => $attribute_keys];
            $sys_attr = $this->get_all( $param );
        }
        
        $param = [];
        $param[ 'select' ] = 'product_id';
        $param[ 'where'  ][ 'ref_product_id' ] = $product_id;
        $prods = $this->get_all( $param );
        $prods = com_makelist( $prods, 'product_id', 'product_id', false);
        $prods[ $product_id ] = $product_id;
        
        $param = [];
        $param[ 'select' ] =    'prod.product_id, attribute_id, attribute_value';
        $param[ 'result' ] =    'obj';
        $param['join'][  ] = [  'tbl' => $this->db->dbprefix( 'product_attribute' ).' as prodAtr', 
                                'cond' => 'prod.product_id=prodAtr.product_id', 
                                'type' => 'left',
                                'pass_prefix' => true
                            ];
        foreach ($sys_attr as $sAttrK => $sAttrV) {
            $param[ 'where' ][ $sAttrV[ 'sys_label' ] ] = $attribute[ $sAttrV[ 'id' ] ];
            unset( $attribute[ $sAttrV[ 'id' ] ] );
        }            
        $param[ 'where' ][ 'in_array' ][0][ 0 ] = 'prod.product_id';
        $param[ 'where' ][ 'in_array' ][0][ 1 ] = $prods;        
        $prodDetWithAttr = $this->get_all( $param );                
        $filtered_product_id = 0;
        if( $prodDetWithAttr ){
            if( $attribute ){
                /** Include path For PLINQ**/
                set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/plinq/Classes');
                /** PHPLinq_LinqToObjects */
                require_once 'PHPLinq/LinqToObjects.php';
                $prod_stack_for_filter = $prods;
                foreach ($attribute as $attKey => $attVal) {
					$filtered_prods = $prod_stack_for_filter;
					unset( $prod_stack_for_filter );
                    foreach ($filtered_prods as $prodKey => $prodVal) {
                        $searchProd = new stdClass();
                        $searchProd->product_id = $prodVal;
                        $searchProd->attribute_id = $attKey;
                        $searchProd->attribute_value = $attVal;                        
                        $filResultStatus = from('$filterResult')->in($prodDetWithAttr)
                                        ->contains( $searchProd );						
                        if( $filResultStatus ){
                            $filtered_product_id = $prodVal;
                            $prod_stack_for_filter[ $prodVal ] = $prodVal;
                        }
                    }
                }
            } else {
                $filtered_product_id = $prodDetWithAttr[ 0 ]->product_id;
            }
        }
        return $filtered_product_id;
    }

    function getProdAttribSetAndSetAtrributes( $prodId = null, $prodAttr = null){
        $param = [];
        $param[ 'select' ]  =   'sys_label, is_sys, attrSetAtt.id, label';
        $param['join'][]    =   [   'tbl' => $this->db->dbprefix( 'attributes_set_attributes' ).' as attrSetAtt', 
                                    'cond' => 'prod.attribute_set_id=attrSetAtt.set_id', 
                                    'type' => 'inner',
                                    'pass_prefix' => true
                                ];
        if( is_array( $prodAttr ) ){
            $param['where'][ 'in_array' ][ 0 ][ 0 ] = 'attrSetAtt.id';
            $param['where'][ 'in_array' ][ 0 ][ 1 ] = $prodAttr;
        }
        $param[ 'where'][ 'prod.product_id' ] = $prodId;
        return $this->get_all( $param ); 
    }

    function getProdWithAttrDetail( $prodId = null, $optParam = []){
        $param = [];
        $param[ 'where' ][ 'ref_product_id' ] = $prodId;
        $confIds = $this->get_all( $param );
        $confIds = com_makelist( $confIds, 'product_id', 'product_id', false);
        $confIds[ $prodId ] = $prodId;        
        $param = [];
        if( isset( $optParam[ 'where' ] ) ){
			$param[ 'where'  ] = $optParam[ 'where' ];
		}                
        $param[ 'select' ] =   'prod.product_id,
                                prodAttr.attribute_id,
                                prodAttr.attribute_value
                            ';                            
        $param['join'][]    =   [   'tbl' => $this->db->dbprefix( 'product_attribute' ).' as prodAttr', 
                                    'cond' => 'prod.product_id=prodAttr.product_id', 
                                    'type' => 'inner',
                                    'pass_prefix' => true
                                ];
        $param[ 'where' ][ 'in_array' ][ 0 ][ 0 ] = 'prod.product_id';
        $param[ 'where' ][ 'in_array' ][ 0 ][ 1 ] = $confIds;        
        return $this->get_all( $param );
    }

    private function _makeConfigComb(&$prodDet){
        $configAttrComb = [];
        $configDet = $prodDet[ 'attrbSetattrConf' ];
        if( $configDet && is_array($configDet) ){
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
        $prodDet[ 'configOptionComb' ] = $configAttrComb;
    }
}

?>
