<!DOCTYPE htmlyy>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Welcome To Bodyguard Admin Panel</title>
        <base href="<?php echo base_url(); ?>" />
        <!-- Stylesheet -->
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" />
        <!--[if lte IE 6]>
        <link rel="stylesheet" type="text/css" href="css/css_iehacks.css" />
        <![endif]-->
        <!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="css/css_ie7hacks.css" />
        <![endif]-->

        <!-- Meta Tags -->
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="pageTop">
            <div class="container">
                <a href="<?php echo base_url(); ?>"><img src="images/logo.png" class="logo" alt="" border="0" /></a>
            </div>
        </div>
        <div class="container">
            <div class="content-area">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Control panel login</h1>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                        $this->load->view(THEME . 'messages/inc-messages');
                        echo form_open(current_url());
                        ?>
                            <div class="form-group">
                                <label for="username" class="control-label">Email</label>
                                <input type="text" class="form-control" id="username" name="login_identity" value="" required="" title="Please enter you username" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <input type="password" class="form-control" id="password" name="login_password" value="" required="" title="Please enter your password" placeholder='Password'>
                            </div>
                            <div id="loginErrorMsg" class="alert alert-error hide">Wrong username og password</div>
                            <input type="submit" name="login_user" id="submit" 
                                    value="Login" class="btn btn-primary btn-block">                            
                        <?= form_close(); ?>                        
                    </div>
                    <div class="col-lg-6">
                        <div class="innerRight">
                            <h2>Authentication Required</h2>
                            <p>In order to gain access to the control panel please authenticate yourself.</p>
                            <p>If you don't have an account, please contact the site Administrator.</p>
                            <h2>Forgotten your password?</h2>
                            <p>If you lost your password or other needed account details, please use the <a href=<?= base_url("welcome/lostpasswd/"); ?>>retreive account</a> section of the website.</p>
                            <p><small>Your IP is being logged for security reasons.</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer"><?php $this->load->view(THEME .'layout/inc-footer'); ?></div>
    </body>
</html>