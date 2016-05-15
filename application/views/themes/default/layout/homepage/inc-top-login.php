<div id="existing_customer">
    <?php
    $customer = array();
    $customer = $this->memberauth->checkAuth();
    if ($customer == true) {
        ?>
        <p>
            <strong>Logged in as:</strong><br /><?php echo $customer['first_name'] . " " . $customer['last_name']; ?></p>
        <p><a href='http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/customer/order'>My Orders</a> | 
            <a href='http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/customer/logout'>Logout</a><br/>
        </p>
        <p><a href="http://<?php echo $customer['company_url_alias']; ?>.desktopdeli.co.uk/catalog">Click here to place your order.</a></p>
    <?php } else { ?>
        <form id="login_frm" name="login_frm" method="post" action="customer/login">
            <input class="linput" name="email" type="text" autocomplete="on" id="login_email" placeholder="EMAIL:" autocomplete="off"> 
            <input class="linput" name="passwd" type="password"  autocomplete="on" id="login_passwd" placeholder="PASSWORD:" autocomplete="off"> 
            <input class="lbutton" type="submit" value="Login" />
            <div class="clear"></div>
            <span><a href="customer/lostpassword" class="lforgot">FORGOT PASSWORD:</a></span>
        </form>
    <?php } ?>
</div>

<!--<input type="text" placeholder="EMAIL:" name="user"/>
<input type="password" placeholder="PASSWORD:" name="pass" />
<input class="lbutton" type="submit" value="Login" />
<div class="clear"></div>
<span>
    <a class="lforgot" href="customer/lostpassword">FORGOT PASSWORD:</a>
</span>-->