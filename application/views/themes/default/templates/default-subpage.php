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
        <link rel="prerender prefetch" href="<?= cms_base_url_with_index(); ?>">
        <link rel="dns-prefetch" href="<?= cms_base_url_without_slash(); ?>">
        <link rel="icon" href="../../favicon.ico">
        <title>Kilimanjaro - Online Coffee Shopping Site</title>
        <?php
        $this->load->view("themes/" . THEME . "/headers/global.php");
        echo $CI->assets->renderHead();
        ?>
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo createUrl('') ?>js/ie-emulation-modes-warning.js"></script>

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
                <?php // $this->load->view("themes/" . THEME . "/layout/inc-topsearch"); ?>
            </div>
        </div>
<!--        <div class="yt-header-bottom">
            <div class="container">
                <div class="row" id="product-listing-page">

                    <?php // $this->load->view("themes/" . THEME . "/layout/inc-left-category"); ?>

                    <?php //$this->load->view("themes/" . THEME . "/layout/inc-slideshow"); ?>

                </div>
            </div>
        </div>-->
        
        <div class="yt-content wrap" id="yt_content">        	
            <div class="yt-content-inner">
            <div class="clearfix"></div>
                <div class="container pad-top30 pad-bot30">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php $this->load->view("themes/" . THEME . '/messages/inc-messages'); ?>
                        </div>
                    </div>
                <?php echo $contents; ?>
                    <?php
                    $blockHtml = '';
                    if (isset($compiledblocks) && $compiledblocks && is_array($compiledblocks)) {
                        foreach ($compiledblocks as $blockK => $blockDet) {
                            $blockHtml .= '<div class="row">'
                                    . $blockDet
                                    . '</div>';
                        }
                        echo $blockHtml;
                    }
                    ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="yt-content wrap" style="margin-top: -12px;" id="yt_content">        	
            <div class="yt-footer wrap" id="yt_footer">
                <?php $this->load->view("themes/" . THEME . "/layout/inc-footer"); ?>
            </div>

        </div>
        <?php echo $CI->assets->renderFooter(); ?>

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo createUrl('') ?>js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>