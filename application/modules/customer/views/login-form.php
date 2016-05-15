<div class="col-lg-6">
    <h1>Login here</h1>
    <form action="<?php echo createUrl('customer/login'); ?>" method="post" role="form" class="">
        <div class="form-group">
            <input type="text" name="email1" class="form-control br-round" id="email" placeholder="Email"/>
            <div class="validation"></div>
        </div>
        <div class="form-group">
            <input type="password" class="form-control br-round" name="password1" id="password" placeholder="password" />
            <div class="validation"></div>
        </div>
        <button name="" type="submit" class="btn btn-primary subbmint">Login</button>
        <h3 class="login_success">You Are Logged In Now</h3>
    </form>
</div>


