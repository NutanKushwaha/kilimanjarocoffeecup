<!--<div class="productss">
    <h2>New Customer</h2>
    <h3>Register here to use secure deli.</h3>
    <form name="register_frm" id="register_frm" method="post" action="customer/register">
        <input name="email" autocomplete="off" id="reg_email" type="text" class="width_177 innput" placeholder="EMAIL:"  >
        <input name="passwd" type="password" class="width_177 innput" autocomplete="off" id="reg_passwd" placeholder="PASSWORD:"  >
        <input type="checkbox" name="news_subscription" id="news_subscription" value="1">&nbsp&nbsp&nbsp&nbsp;Subscribe to our newsletter
        <input value="Create a account" class="recommends" style="text-align:center" type="submit">
    </form>
</div>-->
<script>
    $(document).ready(function() {
//        $('#error_div').hide();
        $('.green_btn_login').click(function() {
            var result = true;
            var email = $('#email').val(),
                    passd = $('#passwd').val();
            $.ajax({
                type: "POST",
                url: MCC_BASE_URL + 'customer/register/check_email',
                data: {reg_email: email}
            })
                    .done(function(data) {
                data = JSON.parse(data);
                if (data.status) {
//                    result = false;
                    alert(data.message);
                }
//                if (result) {
//                    $.ajax({
//                        type: "POST",
//                        url: MCC_BASE_URL + 'customer/register/index/1',
//                        data: {email: email, passwd: passd}
//                    })
//                            .done(function(msg) {
//                        $('#error_div').html(msg);
//                        alert("Data Saved: " + msg);
//                    });
//                }
            });
            if (result) {
                $.ajax({
                    type: "POST",
                    url: MCC_BASE_URL + 'customer/register/index/1',
                    data: {email: email, passwd: passd}
                })
                        .done(function(msg) {
                    $('#error_div').html(msg);
                    alert("Data Saved: " + msg);                    
                    $('h1').each(function() {
                        console.log(this.attr('value'));
                    });
                });
            }
            return false;
        });
    });
</script>
<?php
$customer = array();
$customer = $this->memberauth->checkAuth();
$redirecturl = "http://" . $customer['company_url_alias'] . ".desktopdeli.co.uk/homepage";
?>
<div class="productss">
    <h1>Customer Register</h1>
    <div id="error_div">
    </div>
    <form id="" name="popup_login_frm" method="post" action="customer/register/form">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
            <tr>
                <th><label for="email" style="font-size: 20px; text-align: right;">Email</label></th>
                <td><input name="email" type="text" class="width_70 loginput" id="email" autocomplete="off" /></td>
            </tr>
            <tr>
                <th><label for="password" style="font-size: 20px; text-align: right;">Password</label></th>
                <td><input name="passwd" type="password" class="width_70 loginput" id="passwd" autocomplete="off" /></td>
            </tr>
            <tr>
                <th></th>
                <td>        
                    <input type="checkbox" style="" name="news_subscription" id="news_subscription" value="1">
                    &nbsp&nbsp&nbsp&nbsp;<span>Subscribe to our newsletter</span>
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td><input type="submit" class="green_btn_login" value="Submit" />
            </tr>
        </table>
    </form>
</div>