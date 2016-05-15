<script type="text/javascript">
    //var MCC_TAB = "<?php //echo $tab;  ?>";
    var MCC_PRODUCT_ID = "<?php echo $product['product_id']; ?>";
    var MCC_TAB_CURRENT_URL = "<?php echo current_url(); ?>";
    var wysiwyg_elements = 'product_description,technical_detail';
    var wysiwyg_theme = 'advanced';
</script>
<?php
global $MCC_MIN_JS_ARR, $MCC_JS_ARR, $MCC_MIN_CSS_ARR, $MCC_CSS_ARR;

$MCC_MIN_CSS_ARR[] = 'css/page.css';
$MCC_MIN_JS_ARR[] = 'js/tinymce/tiny_mce.js';


//$MCC_MIN_JS_ARR[] = 'js/tab.js';
$MCC_JS_ARR[] = 'js/tinymce/plugins/tinybrowser/tb_tinymce.js.php';
$MCC_MIN_JS_ARR[] = 'js/site-editor.js';
$MCC_MIN_JS_ARR[] = 'js/website/product_edit.js';
//$MCC_MIN_JS_ARR[] = 'js/website/related-products.js';
?>