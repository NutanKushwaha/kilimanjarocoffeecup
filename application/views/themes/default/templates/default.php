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
        <link rel="prerender prefetch" href="<?= cms_base_url_with_index(); ?>">
        <link rel="dns-prefetch" href="<?= cms_base_url_without_slash(); ?>">

        <title>Kilimanjaro - Online Coffee Shopping Site</title>
        <?php
        $this->load->view("themes/" . THEME . "/headers/global.php");
        
        echo $CI->assets->renderHead();
        ?>

        <style type="text/css">
            body{	
                font-size:12px;	
            }body{
                font-family:Open Sans, serif !important;
            }.yt-menu-nav > #nav > li > a > span,.language-currency ul li a,.sm-serachbox-pro .sm-searbox-content,.sm-serachbox-pro .jqTransformInputWrapper input.input-text,#custom_popular_search .sm-searchbox-popular-title,.banner-policy a,.yt-header-bottom .more-wrap .more-view,.toolbar .select-inner ul li a.selected,.category-products .item .product-name a,.yt-product-detail .tab-product-detail .yt-tab-navi > li > a,
            .yt-product-detail .tab-product-detail .yt-tab-navi > li > a:hover,.sm_quickview_handler,.yt-tab-listing .respl-tabs-wrap ul li.respl-tab .respl-tab-label,.yt-tab-listing .title-text,.block-title span,.spotlight .block-title,.spotlight4,.footer-links-w,.toolbar,.sm-categories .cat-title,.breadcrumbs,#narrow-by-list dt,.yt-product-detail .product-name h1,.yt-product-detail .short-description h2,.postTitle h2 a,#yt_wrapper h1.page-title, #yt_wrapper .page-title h1{
                color:#444444 ;
            }
            body.sm_market{	
                color:#666666 ;			
            }
            body.sm_market{	
                background-color:#FFFFFF ;
            }
            a{	
                color:#666666 ;	
            }
            a:focus,
            a:hover{	
                color:#F4A137 ;	
            }
            /*titleColor  hover*/
            .more-wrap .more-view:hover,
            .postTitle h2 a:hover,
            .category-products .item .product-name a:hover,
            .yt-tab-listing .respl-tabs-wrap ul li.respl-tab .respl-tab-label:hover,
            .yt-product-detail .tab-product-detail .yt-tab-navi > li > a:hover{	
                color:#F4A137 ;	
            }	

            .cloud-zoom-big {
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
        </style>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready(function ($) {


                $(".sm-searbox-content").jqTransform();
                //on top header 1
                //on top header 2


                //on top header 3
                //on top header 4


                //custom js (theme config) 


                // get content top search of search box pro		
                if ($('#yt_header .sm-searchbox-popular').length) {
                    $('#custom_popular_search').html($('#yt_header .sm-searchbox-popular').html());
                    $('#yt_header .sm-searchbox-popular').remove();
                }


            });
            //]]>	
        </script>
    </head>
    <body id="bd" class="sm_market    cms-index-index cms-home-v2 homepagev2">
        <div id="yt_wrapper">

            <!-- BEGIN: Header -->
            <div id="yt_header" class="yt-header wrap header2-wrap">

                <?php $this->load->view("themes/" . THEME . "/layout/inc-header"); ?>


                <?php // $this->load->view("themes/" . THEME . "/layout/inc-topsearch"); ?>


                <div class="yt-header-bottom">
                    <div class="container">
                        <div class="row">

                            <?php // $this->load->view("themes/" . THEME . "/layout/inc-left-category"); ?>

                            <?php $contents ?>

                        </div>
                    </div>
                </div>


            </div>
            <!-- END: Header -->



            <!-- BEGIN: footer -->
            <?php $this->load->view("themes/" . THEME . "/layout/inc-footer"); ?>
            <!-- END: footer -->         



            <?php echo $CI->assets->renderFooter(); ?>
        </div>
    </body>
</html>

