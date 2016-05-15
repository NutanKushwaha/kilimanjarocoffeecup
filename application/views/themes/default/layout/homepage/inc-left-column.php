<div id="contact_box">
    <h2>Contact Us</h2>
    <form name="postcode_finder" id="postcode_finder" method="post" action="">
        <input name="postcode" type="text" class="width_181 innput" id="postcode" placeholder="ENTER YOUR POSTCODE *">
        <input type="submit" value="Submit" class="contact_us">
    </form>
</div> 

<!--<div id="guarantee_box">
    <h2><a href="<?php echo $this->config->item('SHOPPING_CART_URL') . MCC_GUARANTEE_POPUP_IMAGE; ?>" class="guarantee_popup_link"><img src="images/head-guarantee.png" width="95" height="19"></a></h2>
    <a href="<?php echo $this->config->item('SHOPPING_CART_URL') . MCC_GUARANTEE_POPUP_IMAGE; ?>" class="guarantee_popup_link"><?php echo $global_guarantee_box; ?></a>
</div>-->
<?php
$customer = array();
$customer = $this->memberauth->checkAuth();
if ($customer == true) {
    ?>
    <div class="lefthome">
        <a href="http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/catalog" >
            <img src="images/placeorder2.png" alt="" />
        </a>
    </div>
<?php } ?>
<div id="video"> <?php echo $global_video; ?></div>