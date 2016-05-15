<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <!-- BASICS -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>Kilimanjaro - Online Coffee Shopping Site</title>
        <?php
        $this->load->view("themes/" . THEME . "/headers/global.php");
        echo $CI->assets->renderHead();
        ?>
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="js/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>
        <div id="yt_wrapper">
            <div id="yt_header" class="yt-header wrap header2-wrap">
                <?php $this->load->view("themes/" . THEME . "/layout/inc-header"); ?>
                <?php $this->load->view("themes/" . THEME . "/layout/inc-topsearch"); ?>
            </div>
        </div>
        <div class="yt-content wrap" id="yt_content">        	
            <div class="yt-content-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Change Forgotten Password</h2>
                        </div>
                        <div class="col-lg-6">
                            <?php
                            $this->load->view('inc-messages');
                            $form_attributes = ['class' => 'password-update', 'id' => 'password-update'];
                            echo form_open(current_url(), $form_attributes);
                            ?>
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <?= form_password('new_password', '', ' class="form-control" placeholder="***********" id="new_password" '); ?>
                            </div>
                            <div class="form-group">
                                <label for="confirm_new_password">Confirm New Password:</label>
                                <?= form_password('confirm_new_password', '', ' class="form-control" placeholder="***********" id="confirm_new_password"'); ?>
                            </div>                            
                            <?=
                            form_submit('change_forgotten_password', 'Submit!', ['data-toggle' => "tooltip",
                                'class' => "col-lg-3 btn btn-primary pull-left"]);
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
        </div>
        <div class="yt-content wrap" id="yt_content">        	
            <div class="yt-footer wrap" id="yt_footer">
                <?php $this->load->view("themes/" . THEME . "/layout/inc-footer"); ?>
            </div>
            <a title="Go to Top" href="#" id="yt-totop"></a>
        </div>
        <?php echo $CI->assets->renderFooter(); ?>

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
