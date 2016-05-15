<div id="yt_content" class="yt-content wrap">    
    <div class="yt-content-inner">
        <div class="container">
            <div class="row">
                <div class="columns-w">
                    <div class="global-site-notice demo-notice">
                        <div class="notice-inner"><p>This is a demo store. Any orders placed through this store will not be honored or fulfilled.</p></div>
                    </div>
                    <div class="account-login col-lg-12">
                        <div class="col-lg-12">
                            <div class="page-title">
                                <h1>Login or Create an Account</h1>
                            </div>
                        </div>
                        <?php //$this->load->view('inc-messages'); ?>
                        <div class="flexiResponse">
                            <?php //echo $this->session->flashdata('error'); ?>
                        </div>

                        <div class="clearfix"></div>
                        <div class="row">
                            
                        
                        <div class="col-lg-12">
                            <form action="<?php echo createUrl('customer/login'); ?>" method="post" role="form" class="">
                                <input type="hidden" value="" name="form_key">
                                <div class="col2-set">
                                    <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 col-1 new-users">
                                        <div class="content">
                                            <h2>New Customers</h2>
                                            <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 col-2 registered-users">
                                        <div class="content">
                                            <h2>Registered Customers</h2>
                                            <p>If you have an account with us, please log in.</p>
                                            <ul class="form-list">
                                                <li>
                                                    <label class="required" for="email"><em>*</em>Email Address</label>
                                                    <div class="input-box">
                                                        <input type="text" title="Email Address" class="input-text required-entry validate-email" id="email" value="" name="login_identity">
                                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                                    </div>
                                                </li>
                                                <li>
                                                    <label class="required" for="pass"><em>*</em>Password</label>
                                                    <div class="input-box">
                                                        <input type="password" title="Password" id="pass" class="input-text required-entry validate-password" name="login_password">
                                                    </div>
                                                </li>

                                            </ul>
                                            <p class="required">* Required Fields</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2-set">
                                    <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 col-1 new-users">
                                        <div class="buttons-set">
                                            <button onclick="window.location = '<?php echo base_url('customer/register'); ?>';" class="button" title="Create an Account" type="button"><span><span>Create an Account</span></span></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12  col-2 registered-users">
                                        <div class="buttons-set">
                                            <button id="send2" name="send" title="Login" class="button" type="submit"><span><span>Login</span></span></button>
                                            <a class="f-left" href="<?php echo createUrl('customer/register/forgot_password') ?>">Forgot Your Password?</a>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Hot categories home page v2 -->


        </div>
    </div>
</div>
<style type="text/css">
    .flexiResponse h1 , .flexiResponse .error_msg {
        color: red;
        font-size: 18px;
    }
    .flexiResponse .status_msg
    {
        color: green;
        font-size: 18px;
    }
</style>