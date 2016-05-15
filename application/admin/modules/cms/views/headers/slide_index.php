<script>
     var MCC_SLIDESHOW_ID =  <?php echo $slideshow['slideshow_id'];?>
</script>
<?php
	global $MCC_MIN_JS_ARR, $MCC_JS_ARR, $MCC_MIN_CSS_ARR;

	$MCC_MIN_CSS_ARR[] = 'css/menutree.css';
	$MCC_JS_ARR[] = 'js/website/slide.js';
?>