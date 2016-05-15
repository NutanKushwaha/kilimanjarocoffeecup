<div class="block-clientsay">
                                <div class="block-title"><span>Testimonials</span></div>
                                <div class="block-content">
                                    <div data-interval="0" data-ride="carousel" class="carousel slide" id="carousel-generic"><!-- Controls -->
                                        <div class="carousel-control-wrap">
                                            <a data-slide="prev" href="#carousel-generic" class="carousel-control-tab prev"> Previous </a> 
                                            <a data-slide="next" href="#carousel-generic" class="carousel-control-tab next"> Next </a></div>
                                        <div class="carousel-inner">
                                            <?php if($testimonial['num_rows'] > 0){ ?>
                                            <?php 
                                            $testcount = 1;
                                            foreach($testimonial['result'] as $testimonials)
                                            {
                                            ?>
                                            <div class="item <?php echo ($testcount==1)?"active":''; ?>">
                                                <div class="client-cont"><?php echo $testimonials['testimonial']  ?></div>
                                                <div class="client-info"><?php /* ?><img alt="" height="200px" width="150px" src="<?php echo $this->config->item('TESTIMONIAL_IMAGE_URL').$testimonials['image']; ?>"><?php */ ?>
                                                    <div class="inner"><span><?php echo $testimonials['name'] ?></span>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <?php 
                                            $testcount++;
                                            }
                                            
                                            }else{ ?>

                                            <div class="item active">
                                                <div class="client-cont">No Testimonial found.</div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                       
                             <div class="header2-wrap-bottom-copy">
                                <div class="header2-wrap">
                                    <div class="block-newsletter">
                                        <div class="block-title">Newsletter</div>
                                        <div class="block-content">
                                            <p>Please sign up to the Market mailing list to receive updates on new arrivals, special offers and other discount information</p>
                                            <form id="newsletter-validate-detail" action="<?php echo base_url('subscribe'); ?>" method="post">
                                                <div class="input-box">
                                                    <input type="text" id="newsletter" class="input-text required-entry validate-email" title="Sign up for our newsletter" onfocus="if(this.value=='Your email address') this.value='';" onblur="if(this.value=='') this.value='Your email address';" name="email" value="Your email address"> <button type="submit"><span>Subscribe</span></button></div>
                                                     <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>" />
                                            </form>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                <script type="text/javascript">// &amp;lt;![CDATA[
                                        
                                // ]]&amp;gt;</script>            
                                </div>

                            </div>