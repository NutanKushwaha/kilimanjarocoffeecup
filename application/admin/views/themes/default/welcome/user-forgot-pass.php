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
                <div class="col-lg-12">
                    <h2>Retrieve Password</h2>
                </div>
                <div class="col-lg-12">
                    <?php 
                        $this->load->view(THEME . 'messages/inc-messages');                            
                    ?>
                    <p>Input your Username in the box below and we will send your password .</p>
                    <?php
                        $form_attributes = ['class' => 'password-lost', 'id' => 'password-lost', 'required' => true];
                        echo form_open(current_url(), $form_attributes); 
                    ?>
                        <div class="form-group">
                            <label for="login_identity">Username/Email:</label>
                            <?= form_input('login_identity', '', 
                            		' class="form-control"  id="login_identity" ');  ?>
                    		<small>Fields marked with <span style="color:red">*</span> are required.</small>
                        </div>
                        <?= form_submit('request_forgotten_password', 'Submit!' , ['data-toggle'=> "tooltip" , 
                                'class'=>"col-lg-3 btn btn-primary pull-left"
                                ] );
                        ?> 
                    <?= form_close(); ?>                        
                </div>
            </div>
        </div>
        <script>
        		$(function() {
				  	$('#password-lost').submit(function(){
					    $("input[type='submit']", this)
					      .val("Please Wait...")
					      .attr('disabled', 'disabled');
					    return true;
					  });
				});
        </script>
        <div class="footer"><?php $this->load->view(THEME .'layout/inc-footer'); ?></div>
    </body>
</html>