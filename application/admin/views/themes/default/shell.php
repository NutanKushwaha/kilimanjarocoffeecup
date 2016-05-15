<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>Welcome To Admin Panel</title>
        <base href="<?php echo base_url(); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <?php echo cms_meta_tags(); ?>
        <base href="<?php echo cms_base_url(); ?>" />
        <?php
        $this->load->view(THEME . "headers/global");

        echo cms_head();
        echo cms_css();
        echo cms_js();
        $this->load->view(THEME . 'layout/inc-before-head-close');
        ?>        
    <!--        
        <link type="text/css" href="http://crm.checksample.co.uk/cometchat/cometchatcss.php" rel="stylesheet" charset="utf-8">
        <script type="text/javascript" src="http://crm.checksample.co.uk/cometchat/cometchatjs.php" charset="utf-8"></script>
    -->
    </head>
    <body>
        <header id='header' class='pageTop'>
            <?php $this->load->view(THEME . 'layout/inc-header'); ?>
        </header>
        <section id='topmenu'>
            <?php $this->load->view(THEME . 'layout/inc-topmenu'); ?>
        </section>
        <section id='main-content'>
            <div class="container">
                <div class="content-main">
                    <?php echo $content; ?>
                    <div class="clearfix"></div>
                </div>
            </div>    
        </section>
        <section id='footer'>
            <?php $this->load->view(THEME . 'layout/inc-footer'); ?>
        </section>
        <?php $this->load->view(THEME . 'layout/inc-before-body-close'); ?>
    </body>
</html>