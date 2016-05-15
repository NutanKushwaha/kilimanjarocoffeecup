<div id="recommend_box">
    <h2>Recommend a friend</h2>
    <form name="recommend_friend" method="post" id="recommend_friend" action="" enctype="multipart/form-data" >
        <input name="your_name" type="text" class="width_177 innput" id="your_name"  Placeholder="Your Name">
        <input name="friend_email" type="text" class="width_177 innput" id="friend_email"  Placeholder="Friends Email">
        <input name="friend_name" type="text" class="width_177 innput" id="friend_name" Placeholder="Friends Name" >
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



<?php
$customer = array();
$customer = $this->memberauth->checkAuth();
if ($customer == true) {
    ?>
    <div class="howitwork"><a href="http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/catalog" ><img src="images/how-does-it-work.png"></a></div> 
    <!--href="how-does-it-works"-->
    <div class="clear"></div> 
    <!--<div id="existing_customer">
      
            <p>
                <strong>Logged in as:</strong><br /><?php echo $customer['first_name'] . " " . $customer['last_name']; ?></p>
            <p><a href='http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/customer/order'>My Orders</a> | 
                <a href='http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/customer/logout'>Logout</a><br/>
            </p>
            <p><a href="http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/catalog">Click here to place your order.</a></p>-->
<?php } else {
    ?>   
    <!--<h2><img src="images/head-existing-customer.png" width="177" height="36"></h2>-->
<!--    <div class="clear"></div>
    <h2>Existing customer</h2>
    <p>Login here to your deli account</p>
    <form id="login_frm" name="login_frm" method="post" action="customer/login">
        <input name="email" type="text" class="width_177 innput" autocomplete="off" id="login_email" placeholder="EMAIL:" autocomplete="off">
        <input name="passwd" type="password" class="width_177 innput" autocomplete="off" id="login_passwd" placeholder="PASSWORD:" autocomplete="off">
        <span style=""><a href="customer/lostpassword" class="thickbox">FORGOT PASSWORD:</a></span>
        <br/>
        <input type="submit" value="Submit" class="recommends" style="text-align:center">
    </form>-->
<?php } ?>
