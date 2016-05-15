<?php
$MCC_TINYBROWSER_PATH = $this->config->item('UPLOAD_PATH') . 'main_site';
$MCC_TINYBROWSER_URL = 'upload/main_site/useruploads/';
?>
<script type="text/javascript">
	var wysiwyg_elements = 'contents';
	var wysiwyg_theme = 'advanced';
    var TEMPLATE = '#page_template';
	var MCC_TAB = <?php echo $tab; ?>;
	var MCC_PAGE_ID = <?php echo $page_details['page_id']; ?>
</script>
<?php
global $MCC_MIN_JS_ARR, $MCC_JS_ARR, $MCC_MIN_CSS_ARR;
	$MCC_MIN_CSS_ARR[] = 'css/page.css';    
	$MCC_MIN_CSS_ARR[] = 'css/menutree.css';
	$MCC_MIN_CSS_ARR[] = 'js/colorbox/skin_4/colorbox.css';

	$MCC_JS_ARR[] = 'js/tinymce/tiny_mce.js';
	$MCC_JS_ARR[] = "js/tinymce/plugins/tinybrowser/tb_tinymce.js.php?MCC_PATH=$MCC_TINYBROWSER_PATH&MCC_URL=$MCC_TINYBROWSER_URL";
	$MCC_JS_ARR[] = 'js/colorbox/jquery.colorbox.js';
	
	$MCC_JS_ARR[] = 'js/site-editor.js';
	

	//$MCC_MIN_JS_ARR[] = 'js/jquery.ui.nestedSortable.js';
	//$MCC_MIN_JS_ARR[] = 'js/website/widgets.js';
	//$MCC_MIN_JS_ARR[] = 'js/website/video_overlay.js';
?>