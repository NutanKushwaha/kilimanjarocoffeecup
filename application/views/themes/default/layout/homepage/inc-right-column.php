<div style="margin-top:20px">
    <h2>Recommend a friend</h2>
    <form name="recommend_friend" method="post" id="recommend_friend" action="" enctype="multipart/form-data" >
        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
        <input name="your_name" type="text" class="width_177 innput" id="your_name" autocomplete="off" Placeholder="Your Name">
        <input name="friend_email" type="text" class="width_177 innput" id="friend_email"  autocomplete="off" Placeholder="Friends Email">
        <input name="friend_name" type="text" class="width_177 innput" id="friend_name" autocomplete="off"  Placeholder="Friends Name" >
        <input type="submit" class="recommends" value="Recommend" >
    </form>
</div>
<div class="clear"></div>
<div>
    <h2>New Customer</h2>
    <h3>Register here to use secure deli.</h3>
    <form name="register_frm" id="register_frm" method="post" action="customer/register">
        <input name="email" autocomplete="off" id="reg_email" type="text" class="width_177 innput" placeholder="EMAIL:"  >
        <input name="passwd" type="password" class="width_177 innput" autocomplete="off" id="reg_passwd" placeholder="PASSWORD:"  >
        <input type="checkbox" name="news_subscription" id="news_subscription" value="1">&nbsp&nbsp&nbsp&nbsp;Subscribe to our newsletter
        <input value="Create a account" class="recommends" style="text-align:center" type="submit">
    </form>
</div> 

<div class="howitwork">
    <a href="how-does-it-works"><img src="images/doeswork.png" ></a>
</div>

<!--<div id="existing_customer">-->
<?php
//	$customer = array();
//	$customer = $this->memberauth->checkAuth();
//	if ($customer == true) {
?>
<!--		<p>
                <strong>Logged in as:</strong><br /><?php echo $customer['first_name'] . " " . $customer['last_name']; ?></p>
                <p><a href='http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/customer/order'>My Orders</a> | 
                <a href='http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/customer/logout'>Logout</a><br/>
                </p>-->
<?php // } else { 	?>
<!--		<h2><img src="images/head-existing-customer.png" width="177" height="36"></h2>
                <form id="login_frm" name="login_frm" method="post" action="customer/login">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
                                <tr>
                                        <td width="70%"><?php //echo form_dropdown('location_id', $LOCATIONS, set_value('location_id'), 'id="location_id" class="width_96"');      ?></td>
                                </tr>
                                <tr>
                                        <td><input type="text" name="delivery_date" id="delivery_date" readonly value="<?php //echo date('d/m/Y');      ?>" class="width_80 delivery_date"/></td>
                                </tr>
                                <tr>
                                        <td><input name="email" type="text" class="width_181" autocomplete="off" id="login_email" placeholder="EMAIL:" autocomplete="off"></td>
                                </tr>
                                <tr>
                                        <td><input name="passwd" type="password" class="width_181" autocomplete="off" id="login_passwd" placeholder="PASSWORD:" autocomplete="off"></td>
                                </tr>
                                <tr>
                                        <td valign="top" style="padding-top:4px"><span style="float:left"><a href="customer/lostpassword" class="thickbox">FORGOT PASSWORD:</a></span>
                                                <input type="image" src="images/btn-submit.png" width="59" height="26" style="float:right"></td>
                                </tr>
                        </table>
                </form>-->
<?php // } ?>
</div>

<?php
//$customer = $this->memberauth->checkAuth();
//if ($customer == false) {
?>

<?php
// } ?>