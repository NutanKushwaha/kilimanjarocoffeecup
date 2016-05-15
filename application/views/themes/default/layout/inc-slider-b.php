<section>
    <div class="container-full" >
        <div id="myCarousel" class="carousel slide" >
            <div class="carousel-inner">
                 <?php 
                                $slidecount = 1;
                                
                                foreach($slides as $slide){
                                        $active = '';
                                    if($slidecount ==1 ){
                                        $active = 'active';
                                    }
                                    ?>
                                <article class="item <?= $active;?>">
                                    <img  width="100%" src="<?php echo $this->config->item('SLIDESHOW_IMAGE_URL').$slide['slideshow_image'] ?>">
                                    <div class="carousel-caption">
                                        <p><?php echo $slide['img_title'] ?></p>
                                        <div class="carousel-bot">
                                            <span href="#myCarousel" data-slide="prev" class="fa fa-angle-double-left"></span>
                                            <span  href="#myCarousel" data-slide="next" class="fa fa-angle-double-right"></span>
                                        </div>
                                    </div>
                                </article>
                                                                  
                                <?php 
                                $slidecount++;
                                } ?>


                <!-- <article class="item active">
                    <img  width="100%" src="<?=  base_url();?>image/gallery2.png">
                    <div class="carousel-caption">
                        <p>We believe in fair trade and giving back.</p>
                        <div class="carousel-bot">
                            <span href="#myCarousel" data-slide="prev" class="fa fa-angle-double-left"></span>
                            <span  href="#myCarousel" data-slide="next" class="fa fa-angle-double-right"></span>
                        </div>
                    </div>
                </article>
                <article class="item">
                    <img  width="100%" src="<?=  base_url();?>image/gallery4.png">
                    <div class="carousel-caption">
                        <p>Hardworking farmers cultivate, harvest and process their crop the traditional way.</p>
                        <div class="carousel-bot">
                            <span href="#myCarousel" data-slide="prev" class="fa fa-angle-double-left"></span>
                            <span  href="#myCarousel" data-slide="next" class="fa fa-angle-double-right"></span>
                        </div>
                    </div>
                </article> 
                <article class="item">
                    <img  width="100%"  src="<?=  base_url();?>image/gallery3.png">
                    <div class="carousel-caption">
                        <p>We bring the world gourmet organic, naturally grown, hand picked coffee beans.</p>
                        <div class="carousel-bot">
                            <span href="#myCarousel" data-slide="prev" class="fa fa-angle-double-left"></span>
                            <span  href="#myCarousel" data-slide="next" class="fa fa-angle-double-right"></span>
                        </div>
                    </div> -->
                </article>                      
            </div>

        </div>    
    </div>

</section>
<div class="slider-bot">
    <div class="container ">
        <div class="pad-bot30 pad-top30">
            <div class="col-lg-7 col-md-6 col-sm-4 col-xs-12 text-center">
                <p> 
                    From this ancient tradition, you enjoy a cup of coffee brewed from 
                    the finest hand selected Peaberry and Arabica beans
                </p>

            </div>
            <div class="col-lg-5 col-md-6 col-sm-8 col-xs-12 text-center">
                <ul class="social-contact list-inline">
                   <li> <a href="<?php echo  MCC_TWITTER_ACCOUNT ? MCC_TWITTER_ACCOUNT: '#';?>" target="_blank"><i class="fa fa-4x fa-twitter"></i></a></li>
                <li> <a href="<?php echo  MCC_FACEBOOK_ACCOUNT ? MCC_FACEBOOK_ACCOUNT: '#';?>" target="_blank"><i class="fa fa-4x fa-facebook"></i></a></li>
                <li> <a href="<?php echo  MCC_YOUTUBE_ACCOUNT ? MCC_YOUTUBE_ACCOUNT: '#';?>" target="_blank"><i class="fa fa-4x fa-youtube"></i></a></li>
                <li> <a href="<?php echo  MCC_INSTAGRAM_ACCOUNT ? MCC_INSTAGRAM_ACCOUNT: '#';?>" target="_blank"><i class="fa fa-4x fa-instagram"></i> </a></li>
                <li><a href="<?php echo  MCC_PINTEREST_ACCOUNT ? MCC_PINTEREST_ACCOUNT: '#';?>" target="_blank"> <i class="fa fa-4x fa-pinterest"></i></a></li>
                </ul>  
            </div>
        </div>
    </div>
</div>