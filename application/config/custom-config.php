<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['EMAIL_CONFIG'] = array(
    'mailtype' => 'html'
);

$config['UPLOAD_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/';
$config['UPLOAD_URL'] = $this->config['base_url'] . 'upload/';

$config['UPLOAD_USERS_IMG_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/users/images/';
$config['UPLOAD_USERS_IMG_URL'] = $this->config['base_url'] . 'upload/users/images/';

$config['UPLOAD_USERS_RESIZE_IMG_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/users/resized_images/';
$config['UPLOAD_USERS_RESIZE_IMG_URL'] = $this->config['base_url'] . 'upload/users/resized_images/';

$config['BLOCK_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/page/block_images/';
$config['BLOCK_URL'] = $this->config['base_url'] . 'upload/page/block_images/';

$config['RESIZED_IMAGES_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/images/resized/';
$config['RESIZED_IMAGES_URL'] = $this->config['base_url'] . 'images/resized/';

$config['IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'images/';
$config['IMAGE_URL'] = $config['UPLOAD_URL'] . 'images/';

$config['PAGE_DATA_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'page_data/images/';
$config['PAGE_DATA_IMAGE_URL'] = $config['UPLOAD_URL'] . 'page_data/images/';

$config['CLIENT_LOGO_PATH'] = $config['UPLOAD_PATH'] . 'clients/logos/';
$config['CLIENT_LOGO_URL'] = $config['UPLOAD_URL'] . 'clients/logos/';

$config['TEAM_URL'] = $config['UPLOAD_URL'] . 'team/';
$config['TEAM_BLACK_IMG_URL'] = $config['TEAM_URL'] . 'black_images/';
$config['TEAM_COLOR_IMG_URL'] = $config['TEAM_URL'] . 'color_images/';

$config['HOME_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'home-page/images/';
$config['HOME_VIDEO_PATH'] = $config['UPLOAD_PATH'] . 'home-page/videos/';
$config['ABOUTUS_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'about-us/images/';
$config['SHOWREEL_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'showreel/images/';
$config['SHOWREEL_VIDEO_PATH'] = $config['UPLOAD_PATH'] . 'showreel/videos/';

$config['TMP_PATH'] = $config['UPLOAD_PATH'] . 'tmp/';
$config['TMP_URL'] = $config['UPLOAD_URL'] . 'tmp/';

$config['HOME_IMAGE_URL'] = $config['UPLOAD_URL'] . 'home-page/images/';
$config['HOME_VIDEO_URL'] = $config['UPLOAD_URL'] . 'home-page/videos/';
$config['ABOUTUS_IMAGE_URL'] = $config['UPLOAD_URL'] . 'about-us/images/';
$config['SHOWREEL_IMAGE_URL'] = $config['UPLOAD_URL'] . 'showreel/images/';
$config['SHOWREEL_VIDEO_URL'] = $config['UPLOAD_URL'] . 'showreel/videos/';


$config['CASESTUDY_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'casestudy/images/';
$config['CASESTUDY_IMAGE_URL'] = $config['UPLOAD_URL'] . 'casestudy/images/';
$config['CASESTUDY_VIDEO_URL'] = $config['UPLOAD_URL'] . 'casestudy/videos/';

$config['PDF_FILE_PATH'] = $config['UPLOAD_PATH'] . 'pdfs/';
$config['PDF_FILE_URL'] = $config['UPLOAD_URL'] . 'pdfs/';

$config['CATEGORY_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'category/images/';
$config['CATEGORY_IMAGE_URL'] = $config['UPLOAD_URL'] . 'category/images/';

$config['CATEGORY_RESIZE_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'category/resized/images/';
$config['CATEGORY_RESIZE_IMAGE_URL'] = $config['UPLOAD_URL'] . 'category/resized/images/';

$config['PRODUCT_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'products/images/';
$config['PRODUCT_IMAGE_URL'] = $config['UPLOAD_URL'] . 'products/images/';

$config['PRODUCT_RESIZE_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'products/resized/images/';
$config['PRODUCT_RESIZE_IMAGE_URL'] = $config['UPLOAD_URL'] . 'products/resized/images/';

$config['SHOPPING_CART_PATH'] = $config['UPLOAD_PATH'] . 'shopping_cart/images/';
$config['SHOPPING_CART_URL'] = $config['UPLOAD_URL'] . 'shopping_cart/images/';

$config['HEADER_BACKGROUND_PATH'] = $config['UPLOAD_PATH'] . 'category/images/';
$config['HEADER_BACKGROUND_URL'] = $config['UPLOAD_URL'] . 'category/images/';

$config['SHOPPING_CART_FILE_PATH'] = $config['SHOPPING_CART_PATH'] . 'images/';
$config['SHOPPING_CART_FILE_URL'] = $config['SHOPPING_CART_URL'] . 'images/';

$config['PRODUCT_PROMOTION_PATH'] = $config['UPLOAD_PATH'] . 'product_promotion/';
$config['PRODUCT_PROMOTION_URL'] = $config['UPLOAD_URL'] . 'product_promotion/';

$config['MEALDEAL_PROMOTION_PATH'] = $config['UPLOAD_PATH'] . 'mealdeal_promotion/';
$config['MEALDEAL_PROMOTION_URL'] = $config['UPLOAD_URL'] . 'mealdeal_promotion/';

$config['BANNER_PATH'] = $config['UPLOAD_PATH'] . 'page/banner/';
$config['BANNER_URL'] = $config['UPLOAD_URL'] . 'page/banner/';

$config['MISC_PATH'] = $config['UPLOAD_PATH'] . 'misc/';
$config['MISC_URL'] = $config['UPLOAD_URL'] . 'misc/';

$config['EMAIL_CONFIG'] = array(
    'mailtype' => 'html',
    'protocol' => 'sendmail',
    'mailpath' => '/usr/sbin/sendmail -t -i'
);

/**
 * ===Events===
 * image
 */
$config['UPLOAD_PATH_EVENTS'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/events/';
$config['UPLOAD_URL_EVENTS'] = $this->config['base_url'] . 'upload/events/';
$config['UPLOAD_PATH_TICKETS'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/events/tickets';
$config['UPLOAD_URL_TICKETS'] = $this->config['base_url'] . 'upload/events/tickets';

/**
 * ===Venues===
 * image
 */
$config['UPLOAD_PATH_VENUES'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/venues/';
$config['UPLOAD_URL_VENUES'] = $this->config['base_url'] . 'upload/venues/';

$config['UPLOAD_PATH_USERS'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/users/';
$config['UPLOAD_URL_USERS'] = $this->config['base_url'] . 'upload/users/';

$config['SIDELINKS'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/sidelinks/images/';
$config['SIDELINKS_URL'] = $this->config['base_url'] . 'upload/sidelinks/images/';

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

$config['UPLOAD_URL_VIRCAB'] = $this->config['base_url']. 'upload/virtcab/';
$config['UPLOAD_URL_VIRCAB_IMG'] = $this->config['base_url']. 'upload/virtcab/img/';
$config['UPLOAD_URL_VIRCAB_DOC'] = $this->config['base_url'] . 'upload/virtcab/doc/';
$config['UPLOAD_URL_VIRCAB_MISC'] = $this->config['base_url'] . 'upload/virtcab/misc/';
$config['UPLOAD_URL_VIRCAB_VIDEO'] = $this->config['base_url'] . 'upload/virtcab/video/';


$config['UPLOAD_USERS_IMG_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/users/images/';
$config['UPLOAD_USERS_IMG_URL'] = $this->config['base_url'] . 'upload/users/images/';

$config['UPLOAD_USERS_RESIZE_IMG_PATH'] = str_replace('\\', '/', realpath(BASEPATH . '../')) . '/upload/users/resized_images/';
$config['UPLOAD_USERS_RESIZE_IMG_URL'] = $this->config['base_url'] . 'upload/users/resized_images/';

$config['SLIDESHOW_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'slideshow/';
$config['SLIDESHOW_IMAGE_URL'] = $config['UPLOAD_URL'] . 'slideshow/';




$config['TESTIMONIAL_IMAGE_PATH'] = $config['UPLOAD_PATH'] . 'testimonial/';
$config['TESTIMONIAL_IMAGE_URL'] = $config['UPLOAD_URL'] . 'testimonial/';