<script type="text/javascript">
var wysiwyg_elements = 'block_contents';
var TEMPLATE = '#block_template';
</script>
<?php
	global $MCC_MIN_JS_ARR, $MCC_JS_ARR, $MCC_MIN_CSS_ARR, $MCC_CSS_ARR;

	$MCC_MIN_CSS_ARR[] = 'css/page.css';
   
	$MCC_MIN_JS_ARR[] = 'js/tinymce/tiny_mce.js';
	$MCC_MIN_JS_ARR[] = 'js/tinymce/plugins/tinybrowser/tb_tinymce.js.php';

	$MCC_MIN_JS_ARR[] = 'js/codemirror/lib/codemirror.js';
	$MCC_MIN_JS_ARR[] = 'js/codemirror/lib/util/formatting.js';
	$MCC_MIN_JS_ARR[] = 'js/codemirror/mode/css/css.js';
	$MCC_MIN_JS_ARR[] = 'js/codemirror/mode/xml/xml.js';
	$MCC_MIN_JS_ARR[] = 'js/codemirror/mode/javascript/javascript.js';
	$MCC_MIN_JS_ARR[] = 'js/codemirror/mode/htmlmixed/htmlmixed.js';
	$MCC_MIN_CSS_ARR[] = 'js/codemirror/lib/codemirror.css';

	$MCC_MIN_JS_ARR[] = 'js/yetii.js';
	$MCC_MIN_JS_ARR[] = 'js/site-editor.js';
	$MCC_MIN_JS_ARR[] = 'js/jstree/jquery.jstree.js';
	$MCC_MIN_JS_ARR[] = 'js/site-pagetree.js';
	$MCC_MIN_JS_ARR[] = 'js/template.js';

?>
