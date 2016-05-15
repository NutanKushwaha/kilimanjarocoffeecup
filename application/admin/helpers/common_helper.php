<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('com_getThis')) {

	/* Return codeigniter instance */
	function com_getThis() {
	    $data = & get_instance();
	    return $data;
	}

	/* display last run query */
	function com_lquery($exit = false){
		com_e( com_getThis()->db->last_query(), $exit);
	}

	/* display passed param and exit on true pass */
	function com_e($params, $exit = 1) {		
	    echo "<pre>";
	    	print_r($params);
	    echo "</pre>";
	    if ($exit) exit;
	}

	/* return field value either from request or get fields*/
	function com_gParam($index = null,  $fromGET = false,  $default = null) {
        $CI = com_getThis();	    
	    if ($fromGET) {
	        $params = $CI->input->get($index);
	    }else {
                $params = $CI->input->get_post($index, true);
            }
	    $return = $params  ? $params  : $default;
	    return $return;
	}

	/* return array index or default value */
	function com_arrIndex($array, $index, $default = null) {
	    return (isset($array[$index]) && $array[$index]) ? $array[$index] : $default;
	}

	/* return & build html from nested array */
    function com_makeDropDownList($nested = array(), &$html = '', $selected = '') {
        foreach ($nested as $key => $value) {
            if(is_array($value) && sizeof($value) && !isset($value['text'])){
                $html .= '<optgroup label="'.str_repeat('&#160;',($value['depth'] * 2)).$key.'">';
                    com_makeDropDownList($value, $html, $selected);
                $html .= '</optgroup>';
            }else if(isset($value['text'])){
                $html .= '<option value="'.$key.'" '.( $key == $selected ? "selected" : "").'>'.str_repeat('&#160;',($value['depth'] * 2)).$value['text'].'</option>';
            }
        }
    }

    /* return and generate random password */
    function com_generate_random_password($length = 10) {

        $numbers = range('0', '9');
        $ucase_alphabets = range('A', 'Z');
        $scase_alphabets = range('a', 'z');
        $customer_chars = ['-', '_','.',','];
        $full_chars = array_merge($ucase_alphabets, $customer_chars, $scase_alphabets, $numbers);
        shuffle($full_chars);
        $password = '';
        while ($length--) {
            $password.=$full_chars[array_rand($full_chars)];
        }
        return $password;
    }	    

    /* return pagination via passed variable */
	function com_pagination($config = array('per_page' => '', 'base_url' => '',
	                                    'total_rows' => '', 'uri_segment' => '')){

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';        
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a><b>';
        $config['cur_tag_close'] = '</a></b></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';                
        $config['first_link']  = 'First';        
        $config['last_link'] = 'Last';

	    if((int)($config['per_page']) > 0 && !empty($config['uri_segment'])
	        && !empty($config['base_url']) && (int)($config['total_rows']) > 0){  

	        $config['base_url'] = base_url() .$config['base_url'];
	        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
	        $config['full_tag_close'] = '</ul>';

	        $CI = com_getThis();
	        $CI->pagination->initialize($config);
	        return $CI->pagination->create_links();
	    }
	    return false;
	}

	/* return ajax pagination via passed variable */
	function com_ajax_pagination($param = []) {
		
		$config['request_type'] = 'post';
		$config['cur_page'] = '';
		$config['total_rows'] = '';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';        
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a><b>';
        $config['cur_tag_close'] = '</a></b></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';                
        $config['first_link']  = 'First';        
        $config['last_link'] = 'Last';        
        $config['base_url']    = current_url();
        
        /* 	$config['per_page']    = '';
        	$config['html_container'] = 'prod-comp-list-table';
	       	$config['form_serialize'] = 'product-company-assign';
        */
        
        $config = array_replace($config, $param);
        $CI = com_getThis();
        $CI->ajax_pagination->initialize($config);
	 	return $CI->ajax_pagination->create_links();   
	}

	/* return & build html from resultset */
	function com_makelistElem($rstSet, $Key_index, $Key_text, $forSelect = true, $defaultSel = 'Select', $disabledOpt = False) {
		$result = '';	    
		if($forSelect){
	        $result .= '<option value="">'.$defaultSel.'</option>';
	    }	    
	    foreach($rstSet as $rstSetRow){	        
	        if(!array_key_exists($Key_index, $rstSetRow) 
	            or !array_key_exists($Key_text, $rstSetRow)){
	            continue;
	        }	        
	        $result .= '<option '.($disabledOpt ? 'disabled' : '').' value="'.$rstSetRow[$Key_index].'">'.ucfirst( $rstSetRow[$Key_text] ).'</option>';
	    }
	    return $result;		
	}

	/* return & build codeigniter dropdown array from resultset */
	function com_makelist($rstSet, $Key_index, $Key_text, $forSelect = true, $defaultSel = 'Select'){
	    $result = [];
	    if($forSelect){
	        $result[] = $defaultSel; 
	    }
	    foreach($rstSet as $rstSetRow){
	        /* confirm both index should exist */
	        if(!array_key_exists($Key_index, $rstSetRow) or !array_key_exists($Key_text, $rstSetRow)){
	            continue;
	        }
	        $result[$rstSetRow[$Key_index]] = ucfirst( $rstSetRow[$Key_text] );
	    }
	    return $result;
	}

	/* return & build array with custom index passed */
	function com_makeArrIndexToField($arr, $KeyIndex) {
	    $result = [];	    
	    foreach($arr as $arrIndex => $arrDet){	    	
	        if(!array_key_exists($KeyIndex, $arrDet)) {
	            continue;
	        }
	        $result[$arrDet[$KeyIndex]] = $arrDet;
	    }
	    return $result;
	}

	/* return & build array with custom fields array passed */
	function com_makeArrIndexToArrayFieldComb($arrStack, $keyArr, $isMulti = false, $makeArray = false, $defaultGlue = ":") {
	    $result = [];
	    /* get all keys */
	    $arrStackKeys = $isMulti &&  $arrStack ? array_keys( $arrStack[0] ) : array_keys( $arrStack );	    
	    /* get diff between passed keyStack with arrStackKeys */
	    $keysCheck = array_diff($keyArr, $arrStackKeys);
	    /* No difference */
	    if( !$keysCheck || $byPassCheck){

	    	$customkey = '';
	    	$custKIndex = 1;
    	 	$custKCount = count( $keyArr );

	    	foreach ($keyArr as $key => $value) {
	    		$keyGlue = $defaultGlue;
	    		if( $custKIndex == $custKCount ){
	    			$keyGlue = "";
	    		}
    			$customkey .= '{$arrStackDet["'.$value.'"]}'.$keyGlue;
    			$custKIndex++;
	    	}
	    	/* loop on result set and assign customer key index to result and assign value */
		    foreach($arrStack as $arrStackIndex => $arrStackDet) {
		    	eval("\$customDynKey= \"{$customkey}\";");
		    	if( $makeArray){
					$result[ $customDynKey ][] = $arrStackDet;
		    	}else{
		    		$result[ $customDynKey ] = $arrStackDet;
		    	}		        
		    }
	    }
	    return $result;
	}

	/* return login username */
	function com_logInName(){
	    return com_getThis()->session->userdata['flexi_auth_admin']['custom_user_data']['uacc_username'];
	}

	/* return login user picture */
	function com_logInPic( $is_resized = false, $resized_id = false ){
		$user_exist_path = false;		
		$user_pic = com_getThis()->session->userdata['flexi_auth_admin']['custom_user_data']['upro_image'];		
		if( $user_pic ){
			$user_img_path = com_getThis()->config->config['UPLOAD_USERS_IMG_PATH'].$user_pic;			
			if($is_resized){
				$user_img_path = com_getThis()->config->config['UPLOAD_USERS_RESIZE_IMG_PATH'].$resized_id.'/'.$user_pic;
			}
			if( file_exists($user_img_path) ){
				$user_exist_path = $user_pic;
			}
		}
	    return $user_exist_path;
	}

	/* return is user pic exist */
	function com_check_user_img_exist($user_image){
		$user_exist_path = false;

		$user_img_path = com_getThis()->config->config['UPLOAD_USERS_IMG_PATH'].$user_image;
		if( file_exists($user_img_path) && $user_image){
			$user_exist_path = true;
		}
		return $user_exist_path;
	}

	/* return loged user id */
	function com_curUsrId() {
	    return com_getThis()->session->userdata('id');
	}

	/* return is passed menu array is module */
	function  _menu_mod_check( $menu ) {		
		if( $menu['module_menu'] ){			
			if( (int)$menu['is_module_menu'] )
					return $menu;
		} else {		
			return $menu;
		}		
	}

	/* return menu */
	function com_getMenu() {
		$CI = com_getThis();
		$active_class_name = $CI->router->fetch_class();
		$active_class_method = $CI->router->fetch_method();
		$module_name = $CI->router->fetch_module();
		if( !$module_name or is_null($module_name) ){
			$module_name = '' ;
		}

		$user_goup = $CI->flexi_auth->get_user_group_id();
		
		if( empty($user_goup) && empty($module_name) ) {
			redirect('welcome/logout');
			exit();
		}
		$user_privileges = $CI->flexi_auth->get_all_privilegs();
		if( !$user_privileges ){
			$user_privileges = [ ''];
		}

		$sub_query = $CI->db->select('sum(is_module_menu)')
						->from('admin_inner_menu')
						->where('active', 1)
						->where('refer', $module_name)
						->get_compiled_select();


		$selected_menus = $CI->db->select('*, ('.$sub_query.') as module_menu')
								->from('admin_inner_menu')
								->where('active', 1)
								->where_in( 'privileges', $user_privileges)
								->where(' (refer= "'.$module_name.'" or refer = '.$user_goup.')' )	
								->order_by('menu_order')
								->get()->result_array();		


		$output[ 'active_class_name' ] 		=  $active_class_name;
		$output[ 'active_class_method' ] 	=  $active_class_method;
		$output[ 'filtered_menu' ] 		= array_filter($selected_menus , "_menu_mod_check" );
		return $output;
	}

	/* return make ajax req data from array */
	/* initial used from ajax pagination library */
	function com_make_ajax_req_data( $ajax_data = []){
		$data_html_json = "{";
		foreach ($ajax_data as $data_key => $data_value) {
			foreach ($data_value as $act_key => $act_data) {
				foreach ($act_data as $act_data_key => $act_data_value) {
					if( $act_key == 'js' ){
						$data_html_json .= "'".$act_data_key."':".$act_data_value.",";
					}else if( $act_key == 'str' ){
						$data_html_json .= "'".$act_data_key."':'".$act_data_value."',";
					}
				}
			}
		}
		$data_html_json .= "}";
		return $data_html_json;
	}

	/* return product image */
	function com_get_product_image( $product_img,$width ,$height){		
		$CI = com_getThis();
		if( empty( $product_img ) ){
			$product_img_url = $CI->config->config['PRODUCT_RESIZE_IMAGE_URL'].$width.'_'.$height.'/default_product.jpg';
		}else{
			$product_img_url = $CI->config->config['PRODUCT_IMAGE_URL'].$product_img;
		}		
	    $params = [	'image_url' => $product_img_url,
	                'image_path' => $CI->config->config['PRODUCT_IMAGE_PATH'].$product_img,
	                'resize_image_url' => $CI->config->item('PRODUCT_RESIZE_IMAGE_URL'),
	                'resize_image_path' => $CI->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
	                'width' => $width,
	                'height' => $height,                                        
	                'default_picture' => $CI->config->config['PRODUCT_RESIZE_IMAGE_URL'].$width.'_'.$height.'/default_product.jpg',
            		];
		return resize($params);
	}

	/* set reference passed variable to default value if is null */
	function com_changeNull(&$field , $defVal = 0){
		if(is_null($field)){
			$field = $defVal;
		}
	}

	/*  return stack array value by check passed key and specific value exist  */
	function com_searchArrKeyValPair($stack, $searchkey, $searchval){
		$tmp = [];
		foreach ($stack as $stackK => $stackV) {
			if( isset($stackV[ $searchkey ]) && $stackV[ $searchkey ] == $searchval ){
				$tmp[] = $stackV;
			}
		}
		return $tmp;
	}

	/* return number in float with fomat */
	function com_convertToDecimal($val = "", $decPosition = "0", $decPoint =".", $thousandSep = "," ){		
		return number_format($val, $decPosition, $decPoint, $thousandSep);
	}

	
	/* check  is product able to be add in cart or should be available */
	function com_company_and_get_pic( $params ){
		$CI = com_getThis();
		$comp_img = '';
		if( $CI->user_type == CMP_ADMIN || in_array($CI->user_type,[USER, CMP_MD, CMP_PM ] )){
			$company_det = com_getThis()->session->userdata('flexi_auth_admin');			
			if( in_array($CI->user_type,[USER, CMP_MD, CMP_PM ] ) ){
				$company_profile_code = $company_det[ 'custom_user_data' ][ 'upro_company' ];				
				$comp_logo = $CI->db->select( 'company_logo' )
							->from( 'company' )->where('company_code', $company_profile_code)
							->get()->row_array();							
			} else {
				$company_id = $company_det[ 'id' ];
				$comp_logo = $CI->db->select( 'company_logo' )
							->from( 'company' )->where('account_id', $company_id)
							->get()->row_array();			
			}
			if( $comp_logo && !empty( $comp_logo[ 'company_logo' ] ) ){
				$comp_img = $CI->config->item('COMPANY_LOGO_RESIZED_IMAGE_URL').'250_150/'.$comp_logo[ 'company_logo' ];
			}
		}
		return $comp_img;
	}
	
	/* check  is product able to be add in cart or should be available */
	function com_checkProductAvailability( $params ){
		$user_id = null;
		$cartRowId = null;
		$days_limit = null;
		$product_sku = null;
		$userAttrAssignment = null;
		extract( $params );		
		$output = [ 
						'query' => '',
						'userid' => $user_id,
						'success' => 0,
						'totalQty' => 0,
						'allowedQty' => 0,
					];
		if( $userAttrAssignment && is_array( $userAttrAssignment ) && $days_limit && $user_id && $product_sku){
			$userProdAssignDetail =	$userAttrAssignment[ 0 ];
			$days = com_makelist($days_limit, 'config_id', 'config_label', false);
			$checkDaysString = $days[ $userProdAssignDetail[ 'days_limit' ] ];
			$EndDate = "";
			$startDate = "";			
			if( $checkDaysString == "Weekly" ){
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				// $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$startDate 	=	date('Y-m-d 00:00:00',$monday);
				// $EndDate 	=	date('Y-m-d 00:00:00',$sunday);
			}else if ( $checkDaysString == "Monthly" ) {
				$startDate 	=	date('Y-m-01 00:00:00', strtotime( date( 'Y-m-d') ));
				// $EndDate 	=	date('Y-m-t 00:00:00', strtotime( date( 'Y-m-d') ));
			}else if ( $checkDaysString == "Yearly" ) {
				$startDate 	=	date('Y-m-d 00:00:00', strtotime('01/01'));
				// $EndDate  =	date('Y-m-d', strtotime('12/31'));
			}			
			if( $startDate ) {
				$EndDate = date('Y-m-d H:i:s');
				$CI = com_getThis();
				$occupiedTotal = 0;
				if( $cartRowId ){
					$cartDet = $CI->cart->get_item( $cartRowId );					
					$occupiedTotal = $cartDet['qty'];
				}
				$orderStockInReqTotal = 0;				
				$prodDaysQty = $CI->db->select(' sum(order_item_qty) as ttl, customer_id')
												->from('order as ord')
												->join('order_item as orditm', 'order_id=ord.id', 'inner')
												->where('customer_id = ', $user_id)
												->where('order_time <= ', $EndDate)
												->where('order_time >= ', $startDate)
												->where('product_ref = ', $product_sku)
												->group_by('customer_id')
												->get()->row_array();
				$orderStockInReqTotal += com_arrIndex($prodDaysQty, 'ttl', 0);
				
				$prodInlineReqQty = $CI->db->select(' sum(req_item_qty) as ttl, customer_id')
												->from('request as req')
												->join('request_item as reqitm', 'req_id=req.id', 'inner')
												->where('customer_id = ', $user_id)
												->where('status', 1)
												->where('req_time <= ', $EndDate)
												->where('req_time >= ', $startDate)
												->where('product_ref = ', $product_sku)
												->group_by('customer_id')
												->get()->row_array();
				$orderStockInReqTotal += com_arrIndex($prodInlineReqQty, 'ttl', 0);
				
				$prodStockQty = $CI->db->select(' sum(stock_qty) as ttl, user_id')
												->from('user_stock as stk')
												->where('user_id = ', $user_id)
												->where('issue_date_time <= ', $EndDate)
												->where('issue_date_time >= ', $startDate)
												->where('product_code = ', $product_sku)
												->group_by('user_id')
												->get()->row_array();
				$orderStockInReqTotal += com_arrIndex($prodStockQty, 'ttl', 0);				
				
				$occupiedTotal += intval( $orderStockInReqTotal );
				$allowedQty = 0;
				$withCalculation = FALSE;
				if( $occupiedTotal <= $userProdAssignDetail[ 'quantity' ] ){
					$allowedQty = $userProdAssignDetail[ 'quantity' ] - $orderStockInReqTotal;
					//$allowedQty = $userProdAssignDetail[ 'quantity' ] - $occupiedTotal;
					$withCalculation = TRUE;
				}
				$output[ 'query' ] 			= '';
				$output[ 'success'  ]		=	1;
				$output[ 'totalQty' ]		=	$prodDaysQty[ 'ttl' ];
				$output[ 'allowedQty' ]		=	$allowedQty;
				$output[ 'withCalculation']	=	$withCalculation;
			}
		}
		return $output;
	}

	/* create inner category accordian */
	function com_inner_accordian_category( $childrens ){		
		$childrenAccordHtml = '';
		if( $childrens ){
			foreach( $childrens as $childIndex => $childDetail ){
                if( $childDetail[ 'catCountForParent' ] ){
                    $childrenAccordHtml .= '<article 
                    							data-depth="'.$childDetail[ 'depth' ].'" 
                                    			style="border:1px solid'.$childDetail[ 'categ_color' ].'; 
                                        		padding-bottom:10px;" data-accordion 
                                        		class="accordion-level-'.$childDetail[ 'internal_parent' ].' 
                                        		dropdown-cate" 
                                        		style="color:#000;">
                                    		<button data-control 
                                    				style="color:rgb(242, 119, 51) !important; margin-bottom:10px;
                                    				text-align: left; margin-bottom: 10px; width: 80%; padding: 8px 10px;font-size: 14px; font-weight: 600;" 
                                    				class="accordion-level-'.$childDetail[ 'internal_parent' ].' even 
                                    				next-menu" >'.$childDetail[ 'category_text' ]
                                    				.($childDetail['catCountForParent'] ? '<span class="pull-right"><i><small>sub categories</small></i></span>' : '' )
                            				.'</button>
                                    		<span class="attr-set-list pull-right edit-setting">
                                    			<a  class="btn btn-info" 
                                    				href="cpcatalogue/category/edit/'.$childDetail[ 'category_id' ].'"> Edit</a>  
                                    			| <a class="btn btn-info" 
                                    				href="cpcatalogue/category/delete/'.$childDetail[ 'category_id' ].'" >Delete </a> 
                            				</span> 
                                    		<div data-content class="last-child-dropdown">
                                    			<ul class="mlevel-2">
                                    				<li>';
        			$childrenAccordHtml .=	com_inner_accordian_category( $childDetail[ 'children' ]  );
        			$childrenAccordHtml .= '   		</li>
        										</ul>
    										</div>
                                			</article>';
                }else{                	
                    $childrenAccordHtml .= '<article style="background:#ffffff;" class="odd" style="">
                                    			<div class="col-md-6" >
                                    				<i class="fa fa-angle-double-right"></i>
                                             		'.$childDetail[ 'category_text' ].'</div>
                                    			<div class="col-md-6">
                                        			<span class="pull-right edit-setting">
                                            			<a href="cpcatalogue/category/edit/'.$childDetail[ 'category_id' ].'"> Edit</a>  
                                        				| <a href="cpcatalogue/category/delete/'.$childDetail[ 'category_id' ].'" >Delete </a> 
                                        			</span>
                                    			</div>
                                    			<div class="clearfix"></div>
                                			</article> ';
                }
			}
		}
	 	return $childrenAccordHtml;
	}

	function com_comp_categ_accord( $categRel, $isChildParentId = 0){
		$catCompHtml = '';
		if( $categRel ) {
			foreach( $categRel as $catIndex => $catDet){
				if( $catDet['catCountForParent'] ){						
						$catCompHtml .= '<li class="accordion-group">
											<div class="accordion-heading">
                                    			<a 	class="accordion-toggle collapsed" 
                                    				data-toggle="collapse" 
                                    				data-parent="#accordion'.$catDet[ 'internal_parent' ].'" 
                                    				href="#collapse'.$catDet[ 'internal_parent' ].'">
                                      				<i class="icon-fixed-width fa fa-plus"></i> '.$catDet[ 'category_text' ].' 
                                    			</a>
                                  			</div>
                                  			<ul style="height: 0px;" id="collapse'.$catDet[ 'internal_parent' ].'" 
                                  				class="accordion-body collapse submenu">
                                  				'.com_comp_categ_accord($catDet[ 'children' ], $catDet[ 'internal_parent' ]).'
                                  			</ul>
                              			</li>';
				}else{
					if( $isChildParentId ){
						$catCompHtml .= '<li class="accordion-inner">
                                        	<a href="javascript:void();" class="">
                                            	<i class="icon-fixed-width fa fa-angle-double-right"></i> Categories product name 1  
                                        	</a>
                                		</li>';

					} else {
		            	$catCompHtml .= '<li class="accordion-group">
                                  			<div class="accordion-heading">
                                    			<a 	class="accordion-toggle collapsed" 
                                    				style="padding-left:2px;">'
                                    				.$catDet[ 'category_text' ].'</a>
                                  			</div>
                        				</li>';
					}
				}
			}
			return $catCompHtml;			
		}
	}

	function com_getDTFormat( $format = 'mdatetime' ){
		switch ($format) {
			case 'mdatetime':
					return date("Y-m-d H:i:s");
				break;
			
			default:
					return date("Y-m-d H:i:s");
				break;
		}		
	}

	function com_arrayToObject($arrVariable){
		if (is_array( $arrVariable )) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(__FUNCTION__, $arrVariable);
	 	}
		 else {
			// Return object
		 	return $arrVariable;
		 }
	}

	function com_objectToArray($objVariable){
		if (is_object( $objVariable )) {
			 // Gets the properties of the given object
			 // with get_object_vars function
		 	$d = get_object_vars( $objVariable );
		 }
		 
		 if (is_array( $objVariable )) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
	 		return array_map(__FUNCTION__, $objVariable);
		 }
		 else {
		 	// Return array
		 	return $objVariable;
		 }		
	}

	/**
	 * Array to CSV
	 *
	 * download == "" -> return CSV string
	 * download == "toto.csv" -> download file toto.csv
	 */	
	function com_array_to_csv($data_array,  $download = ""){
		if ($download != "")
		{	
			header('Content-Type: application/csv');
			header('Content-Disposition: attachement; filename="' . $download . '"');
		}		

		ob_start();
		$f = fopen('php://output', 'w') or show_error("Can't open php://output");
		$n = 0;		
		foreach ($data_array as $line)
		{
			$n++;
			if ( ! fputcsv($f, $line))
			{
				show_error("Can't write line $n: $line");
			}
		}
		fclose($f) or show_error("Can't close php://output");
		$str = ob_get_contents();
		ob_end_clean();

		if ($download == "")
		{
			return $str;	
		}
		else
		{	
			echo $str;
		}		
	}
	
	function com_get_theme_menu_color(){
		$CI = com_getThis();
		$user_goup = $CI->flexi_auth->get_user_group_id();
		$user_id = $CI->flexi_auth->get_user_id();
		$company_det = "";
		if( $user_goup == 2 ){
			$company_det = $CI->db->select( 'company_code, theme_menu_base, theme_menu_hover' )
								->from( 'company' )
								->where( 'account_id', $user_id)
								->get()->row_array();								
		} else if( in_array($user_goup, [1, 6, 7]) ){
			$company_det = $CI->db->select( 'company_code, theme_menu_base, theme_menu_hover' )
								->from( 'user_profiles' )
								->join( 'company', 'upro_company=company_code', 'left' )
								->where( 'upro_uacc_fk', $user_id)
								->get()->row_array();						
		}
		return 	$company_det;
	}
	
	function com_get_approval( $approval_id = "" ){
	
		$approval_name = "";
		$approval_id = intval( $approval_id );		
		if( $approval_id ){			
			$CI = com_getThis();	
			$approval_det = $CI->db->select( 'uacc_username' )->from( 'user_accounts' )
							->where( 'uacc_id', $approval_id)->get()->row_array();
			if( $approval_det && !empty( $approval_det[ 'uacc_username' ] ) ){
				$approval_name = $approval_det[ 'uacc_username' ];
			}						
		}
		return $approval_name;
	}
}
