<script type="text/javascript">
<?php if (MCC_GUARANTEE_POPUP_IMAGE) { ?>
        var MCC_GUARANTEE_POPUP_IMAGE = "<?php echo resize($this->config->item('SHOPPING_CART_URL') . MCC_GUARANTEE_POPUP_IMAGE, 200, 100, 'guaranatee_image'); ?>"
<?php } ?>

</script>


<?php
$CI->assets->addCSS('css/homepage.css');
$CI->assets->addHeadJS('js/website/homepage.js');
$CI->assets->addHeadJS('js/website/enquiry.js');

//$CI->assets->addHeadJS('js/slider/jquery-1.9.1.min.js');
//$CI->assets->addHeadJS('js/slider/jssor.player.ytiframe.js');
$CI->assets->addHeadJS('js/slider/jssor.core.js');
$CI->assets->addHeadJS('js/slider/jssor.utils.js');
$CI->assets->addHeadJS('js/slider/jssor.slider.js');
?>
