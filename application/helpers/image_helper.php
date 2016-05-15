<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function ar($image_url, $width, $height, $photoset_id = '') {
	require_once APPPATH . 'third_party/phpthumb/ThumbLib.inc.php';

	$CI = & get_instance();
	 

	$image_folder = $CI->config->item('RESIZED_IMAGES_PATH') . "$photoset_id/";
	$resized_images_url = $CI->config->item('RESIZED_IMAGES_URL') . "$photoset_id/";
	$size_folder = $width."_".$height;
	$image_name = basename($image_url);

	//return $image_folder.$size_folder;

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
//$image_url, $resize_image_url, $resize_image_path, $width, $height, $photoset_id = '',$default_picture = ''	
	$photoset_id = '';
	$default_picture = '';

	extract( $params );	
	require_once APPPATH . 'third_party/phpthumb/ThumbLib.inc.php';
	log_message( 'debug', 'Product Image '.$image_path );
	if(file_exists( $image_path ) == FALSE OR is_dir( $image_path ) ){
		return $default_picture;
	}
	log_message( 'debug', 'Product Image processed '.$image_path );
	$resized_images_url = $resize_image_url . "$photoset_id/";
	
	$image_folder = $resize_image_path . "$photoset_id/";
	$resized_images_url = $resize_image_url . "$photoset_id/";
	/*
    	$resized_images_url = str_replace('/admin/','/',$resized_images_url);
	*/
	$size_folder = $width."_".$height;
	$image_name = basename($image_url);

//	return $image_folder.$size_folder;			
	$image_folder = $image_folder.$size_folder;
	if(!file_exists( $image_folder )) {		
		log_message( 'debug', 'Product Image Folder processed '.$image_folder);
		mkdir($image_folder, 0755, true);
	} else {
		log_message( 'debug', 'Product Image Folder Found '.$image_folder);
	}

	log_message( 'debug', 'Product Image with Folder '.$image_folder.'/'.$image_name);
	if(file_exists($image_folder.'/'.$image_name)) {
		$resized_image_url = $resized_images_url.$size_folder."$image_name";
		log_message( 'debug', 'File Found Image '.$resized_image_url );
		return $image_url;
	} else {
		$resized_image_url = $image_folder.'/'.$image_name;
		log_message( 'debug', 'File Not Found Image '.$resized_image_url );
	}

	try {
		log_message( 'debug', 'thumb create from file '.$image_url );
		$thumb = PhpThumbFactory::create($image_url, array('jpegQuality' => 90, 'zc' => 1));
		$thumb->resize($width, $height)->save($image_folder."/$image_name");		
    	$image_url = $resized_images_url.$size_folder."/$image_name";
		log_message( 'debug', 'File Create Image '.$image_url );
		return $image_url;
	}catch (Exception $e) {
		log_message( 'debug', 'File Create Error '.$e->getMessage() );
		return $default_picture;
		
	}
	return '';
}
?>
