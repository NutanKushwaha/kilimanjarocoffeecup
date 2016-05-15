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

        <title>Home</title>

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

    <body id="bd" class="sm_market    cms-index-index cms-home-v2 homepagev2">
        <div id="yt_wrapper">
            <!-- BEGIN: Header -->
            <div id="yt_header" class="yt-header wrap header2-wrap">
                <?php $this->load->view("themes/" . THEME . "/layout/inc-header"); ?>
            </div>

            <section>
                <?php // $this->load->view("themes/" . THEME . "/layout/inc-topsearch"); ?>
                <?php // $this->load->view("themes/" . THEME . "/layout/inc-slideshow"); ?>
                <?php $this->load->view("themes/" . THEME . "/layout/inc-slider"); ?>
            </section>
            <!-- END: Header -->



            <!-- Section: services -->
            <section class="mar-top60  text-center">
                <div class="container ">
                    <div class="col-lg-12">
                        <div class="col-sm-4 col-md-4">

                            <div class="service-box">
                                <div class="service-icon">
                                    <img src="<?= base_url(); ?>image/coffee.png" alt="PREMIUM COFFEE SHOP" />
                                </div>
                                <div class="service-desc">
                                    <h3>PREMIUM COFFEE <br> SHOP</h3>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-4 col-md-4">

                            <div class="service-box">
                                <div class="service-icon">
                                    <img src="<?= base_url(); ?>image/food.png" alt="PREMIUM COFFEE SHOP" />
                                </div>
                                <div class="service-desc">
                                    <h3>GIVING BACK</h3>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-4 col-md-4">

                            <div class="service-box">
                                <div class="service-icon">
                                    <img src="<?= base_url(); ?>image/wifi.png" alt="PREMIUM COFFEE SHOP" />
                                </div>
                                <div class="service-desc">
                                    <h3>FUNDRAISING <br> OPPORTUNITIES</h3>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-12 mar-top20">
                        <div class="page-heading-bg pad-top20 pad-bot20" >
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="" >
                                            <hr />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center ">
                                        <h1>OUR SERVICES</h1>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class=" " >
                                            <hr />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mar-bot30 ">

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                            <img src="<?= base_url(); ?>image/1.jpg" class="img-responsive" alt="img">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                            <img src="<?= base_url(); ?>image/2.png" class="img-responsive" alt="img">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                            <img src="<?= base_url(); ?>image/3.jpg" class="img-responsive" alt="img">
                        </div>
                    </div>
                </div>

            </section>
            <div class="clearfix"></div>
            <!-- /Section: services -->



            <!-- Section: contact -->
            <section class="mar-top40 granty pad-top20 pad-bot60">

                <div class="container ">
                    <div class="row">
                        <div class="page-heading-t pad-top20 pad-bot20">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="dark_bg " >
                                            <hr/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center " >
                                        <h1>GUARANTEED SATISFACTION</h1>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="dark-bg ">
                                            <hr />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2></h2>
                    </div>
                    <div class="row">
                        <div class="col-md-4 ">
                            <div class="testimonial">
                                <p>“I call it the Kilimanjaro Coffee Experience, it's what you get as one of their customers, "One of the Family" customer service, true customer care and guaranteed satisfaction" </p><p><br></p><p class="text-right"> Steve C</p>
                            </div>

                        </div>
                        <div class="col-md-4 ">
                            <div class="testimonial">
                                <p>“As an Art Director, there are times you need that extra creativity to bring to your projects, design is a journey, art behind elements, a creative process. Kilimanjaro Coffee Cup is undeniably the best coffee to enjoy this adventure ” <br></p><p class="pad-top10 text-right">  Alexis S</p>
                            </div>

                        </div>
                        <div class="col-md-4 ">
                            <div class="testimonial">
                                <p>“Kilimanjaro Coffee Cup is the finest coffee,  Every cup is pure, pristine, fully flavored, slightly fruity and beautifully balanced. that is an experience that will change your life." </p><br><p> </p><p class="text-right">  Kimmit H
                                </p>
                            </div>

                        </div>

                    </div>	

                </div>
            </section>
            <!-- /Section: contact -->


            <!-- BEGIN: testimonial -->
            <?php // $this->load->view("themes/" . THEME . "/layout/inc-bottom-right-testimonial-newsletter"); ?>
            <!-- END: testimonial -->
            <!-- BEGIN: footer -->

            <?php $this->load->view("themes/" . THEME . "/layout/inc-footer"); ?>
            <!-- END: footer -->         


            <?php echo $CI->assets->renderFooter(); ?>

        </div>
    </body>
</html>

