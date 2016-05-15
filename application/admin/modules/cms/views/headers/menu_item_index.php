<script>
     var MCC_MENU_ID =  <?php echo $menu_detail['menu_id'];?>
</script>
<?php
	global $MCC_MIN_JS_ARR, $MCC_JS_ARR, $MCC_MIN_CSS_ARR;
	$MCC_MIN_CSS_ARR[] = 'css/menutree.css';
	
	$MCC_JS_ARR[] = 'js/menu-v2.js';

?>
