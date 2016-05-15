<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['EMAIL_CONFIG'] = array(
    'mailtype' => 'html'
);

//get path of various files upload in website
$config['SYS_IMG'] = 'images/system/';

$config['DEFAULT_LANG'] = 'en';

$config['UPLOAD_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/';
$config['UPLOAD_URL'] = $this->config['site_url'] . 'upload/';

$config['UPLOAD_USERS_IMG_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/users/images/';
$config['UPLOAD_USERS_IMG_URL'] = $this->config['site_url'] . 'upload/users/images/';

$config['UPLOAD_USERS_RESIZE_IMG_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/users/resized_images/';
$config['UPLOAD_USERS_RESIZE_IMG_URL'] = $this->config['site_url'] . 'upload/users/resized_images/';

$config['PAGE_PATH'] = $config['UPLOAD_PATH'] . 'page/';
$config['PAGE_URL'] = $config['UPLOAD_URL'] . 'page/';

$config['PAGE_DATA_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'page_data/images/';
$config['PAGE_DATA_IMAGE_URL'] = $config['UPLOAD_URL'] . 'page_data/images/';

//path for the widget data
$config['WIDGET_PATH'] = $config['UPLOAD_PATH'] . 'widget_data/';
$config['WIDGET_URL'] = $config['UPLOAD_URL'] . 'widget_data/';

$config['BLOCK_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'page/block_images/';
$config['BLOCK_IMAGE_URL'] = $config['UPLOAD_URL'] . 'page/block_images/';

$config['CATEGORY_BANNER_PATH'] = $config['UPLOAD_PATH'] . 'category/banner/';
$config['CATEGORY_BANNER_URL'] = $config['UPLOAD_URL'] . 'category/banner/';

$config['CATEGORY_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'category/images/';
$config['CATEGORY_IMAGE_URL'] = $config['UPLOAD_URL'] . 'category/images/';

$config['CATEGORY_RESIZE_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'category/resized/images/';
$config['CATEGORY_RESIZE_IMAGE_URL'] = $config['UPLOAD_URL'] . 'category/resized/images/';

$config['BANNER_PATH'] = $config['UPLOAD_PATH'] . 'page/banner/';
$config['BANNER_URL'] = $config['UPLOAD_URL'] . 'page/banner/';

$config['DOCUMENT_PATH'] = $config['UPLOAD_PATH'] . 'products/documents/';
$config['DOCUMENT_URL'] = $config['UPLOAD_URL'] . 'products/documents/';


$config['SLIDESHOW_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'slideshow/';
$config['SLIDESHOW_IMAGE_URL'] = $config['UPLOAD_URL'] . 'slideshow/';

$config['CSV_PATH'] = $config['UPLOAD_PATH'] . 'import/';
$config['CSV_URL'] = $config['UPLOAD_URL'] . 'import/';

$config['HOME_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'home-page/images/';
$config['HOME_IMAGE_URL'] = $config['UPLOAD_URL'] . 'home-page/images/';

$config['IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'images/';
$config['IMAGE_URL'] = $config['UPLOAD_URL'] . 'images/';

$config['HEADER_BACKGROUND_PATH'] = $config['UPLOAD_PATH'] . 'category/images/';
$config['HEADER_BACKGROUND_URL'] = $config['UPLOAD_URL'] . 'category/images/';


$config['VIRT_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'virtcab/img/';
$config['VIRT_IMAGE_URL'] = $config['UPLOAD_URL'] . 'virtcab/img/';

$config['PDF_FILE_PATH'] = $config['UPLOAD_PATH'] . 'pdfs/';
$config['PDF_FILE_URL'] = $config['UPLOAD_URL'] . 'pdfs/';


$config['PRODUCT_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'products/images/';
$config['PRODUCT_IMAGE_URL'] = $config['UPLOAD_URL'] . 'products/images/';

$config['PRODUCT_RESIZE_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'products/resized/images/';
$config['PRODUCT_RESIZE_IMAGE_URL'] = $config['UPLOAD_URL'] . 'products/resized/images/';

$config['PRODUCT_PROMOTION_PATH'] = $config['UPLOAD_PATH'] . 'product_promotion/';
$config['PRODUCT_PROMOTION_URL'] = $config['UPLOAD_URL'] . 'product_promotion/';

$config['EXPORT_CSV_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/exports/';

$config['MISC_PATH'] = $config['UPLOAD_PATH'] . 'misc/';
$config['MISC_URL'] = $config['UPLOAD_URL'] . 'misc/';

/**
 * Virtual Cab
 * img
 * doc
 * videos
 */
$config['UPLOAD_PATH_VIRCAB'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/virtcab/';
$config['UPLOAD_PATH_VIRCAB_IMG'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/virtcab/img/';
$config['UPLOAD_PATH_VIRCAB_DOC'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/virtcab/doc/';
$config['UPLOAD_PATH_VIRCAB_MISC'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/virtcab/misc/';
$config['UPLOAD_PATH_VIRCAB_VIDEO'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/virtcab/video/';

$config['UPLOAD_URL_VIRCAB'] = $this->config['site_url'] . 'upload/virtcab/';
$config['UPLOAD_URL_VIRCAB_IMG'] = $this->config['site_url'] . 'upload/virtcab/img/';
$config['UPLOAD_URL_VIRCAB_DOC'] = $this->config['site_url'] . 'upload/virtcab/doc/';
$config['UPLOAD_URL_VIRCAB_MISC'] = $this->config['site_url'] . 'upload/virtcab/misc/';
$config['UPLOAD_URL_VIRCAB_VIDEO'] = $this->config['site_url'] . 'upload/virtcab/video/';

/**
 * ===Events===
 * image
 */
$config['UPLOAD_PATH_EVENTS'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/events/';
$config['UPLOAD_URL_EVENTS'] = $this->config['site_url'] . 'upload/events/';
$config['UPLOAD_PATH_TICKETS'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/events/tickets';
$config['UPLOAD_URL_TICKETS'] = $this->config['site_url'] . 'upload/events/';

/**
 * ===Venues===
 * image
 */
$config['RESIZED_IMAGES_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/products/resized/';
$config['RESIZED_IMAGES_URL'] = $this->config['site_url'] . '/upload/products/resized/';

$config['COMPANY_LOGO_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'company_logo/';
$config['COMPANY_LOGO_IMAGE_URL'] = $config['UPLOAD_URL'] . 'company_logo/';

$config['COMPANY_LOGO_RESIZED_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'company_logo/resized/';
$config['COMPANY_LOGO_RESIZED_IMAGE_URL'] = $config['UPLOAD_URL'] . 'company_logo/resized/';

$config['SLIDESHOW_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'slideshow/';
$config['SLIDESHOW_IMAGE_URL'] = $config['UPLOAD_URL'] . 'slideshow/';

$config['XML_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/xml/';

$config['TESTIMONIAL_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'testimonial/';
$config['TESTIMONIAL_IMAGE_URL'] = $config['UPLOAD_URL'] . 'testimonial/';

$config['PROD_CSV_PATH'] = $config['UPLOAD_PATH'] . 'csv/product/';
$config['PROD_CSV_URL'] = $config['UPLOAD_URL'] . 'csv/product/';

$config['USER_CSV_PATH'] = $config['UPLOAD_PATH'] . 'csv/users/';

$config['PROD_TIMG_PATH'] = $config['UPLOAD_PATH'] . 'products/temp_images/';
$config['PROD_TIMG_URL'] = $config['UPLOAD_URL'] . 'products/temp_images/';

$config['IMPORT_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'import/';
$config['IMPORT_IMAGE_URL'] = $config['UPLOAD_URL'] . 'import/';
