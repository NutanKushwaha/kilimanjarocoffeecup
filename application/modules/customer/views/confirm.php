<?php $this->load->view('inc-messages'); ?>
<div class="account-login">
    <div class="col-lg-12">
        <div class="page-title">
            <h1>Login or Create an Account</h1>
        </div>
    </div>
    <div class="clearfix"></div>
    
    <div class="col2-set">

        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 col-1 new-users">
            <div class="content">
                <h2>New Customers</h2>
                <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 col-2 registered-users">


            <div class="content">
                <h2> Checkout</h2>
            </div>
            <form class="" role="form" method="post" action="<?php echo createUrl('customer/confirm');  ?>">
                <div class="form-group">
					<label>
						<input id="role_guest" type="radio" value="guest" class="" name="role" checked=""><span>Checkout as guest</span>
					</label>
                </div>
                <div class="form-group">
					<label>
						<input id="role_user" type="radio" value="user" name="role" class=""><span>Login</span>
					</label>
                </div>
                <div class="buttons-set">
                    <button name="" type="submit" class="button subbmint">Submit</button>
                    
                </div>
                
                
            </form>
            <button name="" type="back" class="button backbtn" onclick="history.back();">Back</button>
        </div>

    </div>
</div>

<!--<div class="col-lg-6">
    <h1>Checkout</h1>
    <form action="<?php //echo createUrl('customer/confirm');  ?>" method="post" role="form" class="">
        <div class="form-group">
            <input type="radio" name="role" class="" value="guest" /><span>Checkout as guest</span>
        </div>
        <div class="form-group">
            <input type="radio" class="" name="role"  value="user"/><span>Login</span>
        </div>
        <button name="" type="submit" class="btn btn-primary subbmint">Submit</button>
        
    </form>
</div>-->


