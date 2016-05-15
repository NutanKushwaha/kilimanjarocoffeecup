<!DOCTYPE html>
<html xml:lang="en" lang="en">
    <head>
        <link rel="prerender prefetch" href="<?= cms_base_url_with_index(); ?>">
        <link rel="dns-prefetch" href="<?= cms_base_url_without_slash(); ?>">
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>Welcome To Admin Panel</title>
        <base href="<?php echo base_url(); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <?php echo cms_meta_tags(); ?>
        <base href="<?php echo cms_base_url(); ?>" />
        <?php
        $this->load->view(THEME . "headers/global");
        echo $CI->assets->renderHead();
        echo cms_head();
        echo cms_css();
        echo cms_js();
        ?>
        <?php $this->load->view(THEME . 'layout/inc-before-head-close'); ?>
        <!--Le HTML5 shim, for IE6-8 support of HTML5 elements 
        [if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
         <!-- Modal -->
          <div class="modal fade" id="comMsgModal" role="dialog">
            <div class="modal-dialog">            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">                  
                  <h4 class="modal-title" id="comMsgModalTitle"></h4>
                </div>
                <div class="modal-body" id="comMsgModalBody"></div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>              
            </div>
          </div>          
        </div>    
        <header id='header' class='pageTop'>
            <?php $this->load->view(THEME . 'layout/inc-header'); ?>
        </header>
        <section id='main-content'>
            <div class="container">
                <div class="content-main">
                    <div class="row content-bg">
                        <div class="col-sm-12">
                            <?php $this->load->view(THEME . 'messages/inc-messages'); ?>
                        </div>
                        <?php echo $content; ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="modal fade" id="msg-pop-model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" id="msg-pop-model-title"></h4>
                                  </div>
                                  <div class="modal-body" id="msg-pop-model-msg">
                                   
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>
                                </div>

                              </div>
                    </div>                    
                </div>
            </div>
        </section>
        <section id='footer'>
            <?php $this->load->view(THEME . 'layout/inc-footer'); ?>
        </section>
        <?php 
            $this->load->view(THEME . 'layout/inc-before-body-close'); 
            echo $CI->assets->renderFooter();
        ?>
    </body>
</html>