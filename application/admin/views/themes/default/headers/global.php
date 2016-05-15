<script type="text/javascript">    
    var MCC_BASE_URL = "<?php echo cms_base_url(); ?>";
    var MCC_SITE_URL = "<?php echo $this->config->item('site_url'); ?>";
</script>
<!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet"> -->
<link href="css/google-font-open-sans.css" rel="stylesheet">


<?php
global $MCC_MIN_JS_ARR, $MCC_JS_ARR, $MCC_MIN_CSS_ARR;
/*
    $MCC_MIN_CSS_ARR[] = 'css/bootstrap.css';
    $MCC_MIN_CSS_ARR[] = 'css/bootstrap-multiselect.css';
    $MCC_MIN_CSS_ARR[] = 'css/font-awesome.css';
    $MCC_MIN_CSS_ARR[] = 'css/style.css';
*/
$CI->assets->addCSS('css/bootstrap.css');
$CI->assets->addCSS('css/bootstrap-multiselect.css');
$CI->assets->addCSS('css/font-awesome.css');
$CI->assets->addCSS('css/style.css');
/*
array_unshift($MCC_MIN_JS_ARR, 'js/jquery-1.10.2.min.js');
$MCC_MIN_JS_ARR[] = 'js/bootstrap.min.js';
$MCC_MIN_JS_ARR[] = 'js/bootstrap-multiselect.js';
$MCC_MIN_JS_ARR[] = 'js/daterangepicker/daterangepicker.js';
$MCC_MIN_JS_ARR[] = 'js/app.js';
$MCC_MIN_JS_ARR[] = 'js/editor.js';
$MCC_MIN_JS_ARR[] = 'js/bootbox.js';
$MCC_MIN_JS_ARR[] = 'js/bPopup.js';
//$MCC_MIN_JS_ARR[] = 'js/editor/tinymc' . 'e/tinymce.dev.js';
//$MCC_MIN_JS_ARR[] = 'js/editor/tinymce/plugins/table/plugin.dev.js';
//$MCC_MIN_JS_ARR[] = 'js/editor/tinymce/plugins/paste/plugin.dev.js';
//$MCC_MIN_JS_ARR[] = 'js/editor/tinymce/plugins/spellchecker/plugin.dev.js';
//$MCC_MIN_JS_ARR[] = 'js/editor/tinymce.setting.js';
$MCC_MIN_JS_ARR[] = 'js/html5lightbox/html5lightbox.js';
$MCC_MIN_JS_ARR[] = 'js/bootstrap.min.js';
//$MCC_MIN_JS_ARR[] = 'js/commonEditor.js';
*/
$CI->assets->addHeadJS('js/jquery-1.10.2.min.js');
$CI->assets->addFooterJS('js/bootstrap-multiselect.js');
$CI->assets->addFooterJS('js/app.js');
$CI->assets->addFooterJS('js/editor.js');
$CI->assets->addFooterJS('js/bPopup.js');
$CI->assets->addFooterJS('js/html5lightbox/html5lightbox.js');
$CI->assets->addFooterJS('js/bootstrap.min.js');
/* Include jquery Float Bar Chart Files  */
/*
    $MCC_MIN_JS_ARR[] = 'js/ex/jquery.flot.js';
    $MCC_MIN_JS_ARR[] = 'js/ex/jquery.flot.orderBars.js';
    $MCC_MIN_JS_ARR[] = 'js/ex/vertical.js';
    $MCC_MIN_JS_ARR[] = 'js/ex/stacked-vertical.js';
    $MCC_MIN_JS_ARR[] = 'js/ex/App.js';
*/    
$CI->assets->addFooterJS('js/ex/jquery.flot.js');
$CI->assets->addFooterJS('js/ex/jquery.flot.orderBars.js');
$CI->assets->addFooterJS('js/ex/vertical.js');
$CI->assets->addFooterJS('js/ex/App.js');
$CI->assets->addFooterJS('js/ex/stacked-vertical.js');    

/* amchart bar chart files */
/*
    $MCC_MIN_JS_ARR[] = 'js/ex/amcharts/amcharts.js';
    $MCC_MIN_JS_ARR[] = 'js/ex/amcharts/serial.js';
    $MCC_MIN_JS_ARR[] = 'js/ex/amcharts/none.js';
*/  
$CI->assets->addHeadJS('js/ex/amcharts/amcharts.js');

    $MCC_MIN_JS_ARR[] = 'js/ex/amcharts/serial.js';
$CI->assets->addHeadJS('js/ex/amcharts/pie.js');
/* Accordion files */
/*
    $MCC_MIN_JS_ARR[] = 'js/ex/jquery.accordion.js';
    $MCC_MIN_CSS_ARR[] = 'css/ex/jquery.accordion.css';
*/
$CI->assets->addFooterJS('js/ex/jquery.accordion.js');
$CI->assets->addCSS('js/ex/amcharts/none.js');

/* Star Rating */
/*
    $MCC_MIN_JS_ARR[] = 'js/ex/star-rating.js';
    $MCC_MIN_CSS_ARR[] = 'css/ex/star-rating.css';
 *
 */
$CI->assets->addFooterJS('js/ex/star-rating.js');
$CI->assets->addCSS('css/ex/star-rating.css');
?>


<!-- /* Include Style Sheet Float Bar Chart Files */  -->
<link rel="stylesheet" href="css/ex/jquery-ui-1.9.2.custom.css" type="text/css" />		
<link rel="stylesheet" href="css/ex/App.css" type="text/css" />
<link rel="stylesheet" href="css/ex/custom.css" type="text/css" /> 
