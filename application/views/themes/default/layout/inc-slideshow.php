<?php
    //echo "<pre>";
    //print_R($slides);
    //echo "</pre>";
?>

<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="yt_header_right">   
                
                    <!-- Carousel -->
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                           
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                            
                                <?php 
                                $slidecount = 1;
                                foreach($slides as $slide){ ?>
                                    <div class="item <?php echo ($slidecount==1)?"active":'' ?>">
                                            <img src="<?php echo $this->config->item('SLIDESHOW_IMAGE_URL').$slide['slideshow_image'] ?>" alt="First slide">
                                            <!-- Static Header -->
                                            <div class="header-text hidden-xs">
                                                <div class="col-lg-offset-6 col-lg-6 col-md-8 col-sm-12 col-xs-12 text-center">
                                                    <div class="slider-caption-head" style="font-size: 45px;">
                                                        <div><?php echo $slide['img_title'] ?>  </div> 
                                                    </div>
                                                    <p>
                                                        <span><?php echo !empty($slide['img_desc'])?$slide['img_desc'].'.':'' ?></span>
                                                    </p>
                                                </div>
                                            </div><!-- /header-text -->
                                        </div>                                
                                <?php 
                                $slidecount++;
                                } ?>
                            </div>
                            <!-- Controls -->
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="fa fa-angle-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="fa fa-angle-right"></span>
                            </a>
                        </div><!-- /carousel -->
                       



                <div class="clearfix"></div>
<!--                <div style="" id="slider-abt-content" class="slider-bottom-container">
                    <div class="slider-bottom-inner">
                        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 col-first-half col-left-section">
                            <div class="btm-abt-contant-desc">
                                <div class="slider-bottom-header">
                                    <h1> Welcome to Bodyguard Workwear  </h1>
                                </div>
                                <p class="slider-bottom-desc-text-section">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                                    when an unknown printer took a galley of type and scrambled it to make a type 
                                    specimen book. It has survived not only five centuries, but also the leap into 
                                    electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with 
                                    the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop 
                                    publishing software like Aldus PageMaker including versions of Lorem Ipsum. 
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-third col-right-section">
                            <div class="right-offer-timer-count-section">
                                <div class="big-hot-offer-heading-text"> <h1>Hot Offers </h1></div>
                                <div class="sale-offer-heading-text"> <h1>Sale </h1></div>
                                <div class="disc-offer-heading-text"> <p>up to 60% off</p>  </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>   -->
            </div>
