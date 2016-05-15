<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function ar($image_url, $width, $height, $photoset_id = '') {
	require_once APPPATH . 'third_party/phpthumb/ThumbLib.inc.php';

	$CI = & get_instance();
	 

	$image_folder = $CI->config->item('RESIZED_IMAGES_PATH') . "$photoset_id/";
	$resized_images_url = $CI->config->item('RESIZED_IMAGES_URL') . "$photoset_id/";
	$size_folder = $width."_".$height;
	$image_name = basename($image_url);


		
	if(!file_exists($image_folder.$size_folder)) {
		mkdir($image_folder.$size_folder, 0755, true);
	}

	if(file_exists($image_folder.$size_folder."/$image_name")) {
		return $resized_images_url.$size_folder."/$image_name";
	}

	try {
		$thumb = PhpThumbFactory::create($image_url);
		$thumb->adaptiveResize($width, $height)->save($image_folder.$size_folder."/$image_name");
	    return $resized_images_url.$size_folder."/$image_name";
	}catch (Exception $e) {
		
	}
	return '';
}

/* $params[ 	image_url,
				image_path,
				resize_image_url,
				resize_image_path,
				width,
				height,
				photoset_id,
				default_picture
			]
 */
function resize( $params = [] ) {
	$photoset_id = '';
	$default_picture = '';
	extract( $params );
	require_once APPPATH . 'third_party/phpthumb/ThumbLib.inc.php';
	if(file_exists( $image_path ) == FALSE || is_dir( $image_path ) ){
		return $default_picture;
	}
	$resized_images_url = $resize_image_url . "$photoset_id/";	
	$image_folder = $resize_image_path . "$photoset_id/";
	$resized_images_url = $resize_image_url . "$photoset_id/";
	/*
    	$resized_images_url = str_replace('/admin/','/',$resized_images_url);
	*/
	$size_folder = $width."_".$height;
	$image_name = basename($image_url);
	//	return $image_folder.$size_folder;
	if(!file_exists($image_folder.$size_folder)) {
		mkdir($image_folder.$size_folder, 0755, true);
	}
	if(file_exists($image_folder.$size_folder."/$image_name")) {
		return $resized_images_url.$size_folder."/$image_name";
	}
	try {
		$thumb = PhpThumbFactory::create($image_url, array('jpegQuality' => 90, 'zc' => 1));
		$thumb->resize($width, $height)->save($image_folder.$size_folder."/$image_name");		
    	return $resized_images_url.$size_folder."/$image_name";
	}catch (Exception $e) {
		return $default_picture;		
	}
	return '';
}

// --------------------------------------------------------------
/*
    Validation for image 
    $options should pass as field|value|field|value
    example required|1
*/
function valid_images($params) {
 	$CI = com_getThis();

    if(!empty($params)) {
        $sub_options = explode(',', $params);
        $cnt = count($sub_options);
        $sub_rules = array();
        if($cnt){
            for($index = 0; $index < ($cnt - 1); $index += 2) {
                $sub_rules[$sub_options[$index]] = $sub_options[$index + 1];
            }
        }
    }

    if(isset($sub_rules['require']) && !$sub_rules['require']) {
    	return TRUE;
    }

    if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0 || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
        $CI->form_validation->set_message('check_image', 'The Image field is required.');
        return FALSE;
    }
        
    $imginfo = @getimagesize($_FILES['image']['tmp_name']);

    if (!($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3 )) {
        $CI->form_validation->set_message('check_image', 'Only GIF, JPG and PNG Images are accepted');
        return FALSE;
    }
    return TRUE;
}
?>
