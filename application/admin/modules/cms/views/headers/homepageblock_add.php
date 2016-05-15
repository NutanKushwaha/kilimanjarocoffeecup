<script type="text/javascript">
	var wysiwyg_elements = 'block_contents';
	var wysiwyg_theme = 'advanced';
    var TEMPLATE = '#block_template';
	var MCC_TAB = '0';
</script>

<?php
	global $MCC_MIN_JS_ARR, $MCC_JS_ARR, $MCC_MIN_CSS_ARR, $MCC_CSS_ARR;

	$MCC_MIN_CSS_ARR[] = 'css/page.css';    
        
	$MCC_JS_ARR[] = 'js/tinymce/tiny_mce.js';
	$MCC_JS_ARR[] = 'js/tinymce/plugins/tinybrowser/tb_tinymce.js.php';
	$MCC_JS_ARR[] = 'js/site-editor.js';
	$MCC_MIN_JS_ARR[] = 'js/page.js';
?>
