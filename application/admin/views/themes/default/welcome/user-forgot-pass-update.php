<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Welcome To Bodyguard Admin Panel</title>
        <base href="<?php echo base_url(); ?>" />
        <link rel="prerender prefetch" href="<?= cms_base_url_with_index(); ?>">
        <link rel="dns-prefetch" href="<?= cms_base_url_without_slash(); ?>">
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
                        <h2>Change Forgotten Password</h2>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $this->load->view(THEME . 'messages/inc-messages');
                            $form_attributes = ['class' => 'password-update', 'id' => 'password-update'];
                            echo form_open(current_url(), $form_attributes); 
                        ?>
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <?= form_password('new_password', '', ' class="form-control" placeholder="***********" id="new_password" ');  ?>
                            </div>
                            <div class="form-group">
                                <label for="confirm_new_password">Confirm New Password:</label>
                                <?= form_password('confirm_new_password', '', ' class="form-control" placeholder="***********" id="confirm_new_password"');  ?>
                            </div>                            
                            <?= form_submit('change_forgotten_password', 'Submit!' , ['data-toggle'=> "tooltip" , 
                                    'class'=>"col-lg-3 btn btn-primary pull-left"] );                
                            ?> 
                        <?= form_close(); ?>                        
                    </div>
                    <div class="col-lg-6">
                        <div class="innerRight">
                            <small>
                                <strong>For this demo, the following validation settings have been defined:</strong><br/>
                                Password length must be more than <?= $this->flexi_auth->min_password_length(); ?> characters in length.<br/>Only alpha-numeric, dashes, underscores, periods and comma characters are allowed.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer"><?php $this->load->view(THEME .'layout/inc-footer'); ?></div>
    </body>
</html>