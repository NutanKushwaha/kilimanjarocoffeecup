<!-- jQuery -->
<!-- FlexSlider -->
<?php //e($featuredProduct); ?>
<style>

</style>
<script>
    jQuery(document).ready(function ($) {
        //$(window).load(function () {
        $('.featuredFlexslider').flexslider({
            animation: "slide",
            controlNav: false,
            prevText: " ",
            nextText: " ",
            animationLoop: false,
            itemWidth: 275,
            itemMargin: 8,
            touch: true,
            move: 0
        });
        //});

    })
</script>
<div class="yt_main_inner">	                            	
    <ul class="nav nav-tabs feature-product-tab-section">
        <li class="tab-first-title-text"> FEATURED PRODUCTS </li>
        <li class="pull-right"></li>
    </ul>
    <div class="clearfix"></div>
    <!-- Tab panes -->
    <script id="main-example-template" type="text/template">
        <div class="time <%= label %>">
        <span class="count curr top"><%= curr %></span>
        <span class="count next top"><%= next %></span>
        <span class="count next bottom"><%= next %></span>
        <span class="count curr bottom"><%= curr %></span>
        <span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span>
        </div>
    </script>
    <div class="tab-content feature-product-tab-content">
        <div class="tab-pane active" id="home">
            <div id="" class="flex-responsive-carousel carousel_slides featuredFlexslider">
                <!-- Wrapper for slides -->
                <ul class="slides">
                    <?php if ($featuredProduct['num_rows'] > 0) { ?>
                        <?php
                        $rel = 1;                        
                        foreach ($featuredProduct['result'] as $fproduct) {
                            ?>       
                            <li>
                                <div class="featuredProduct-block">
                                    <a href="<?php echo createUrl('catalogue/product/') . $fproduct['product_alias'] ?>">
                                        <div class="photos">
                                            <img src="<?php echo com_get_image('PRODUCT_IMAGE_URL', 'PRODUCT_IMAGE_PATH', 'PRODUCT_RESIZE_IMAGE_URL', 'PRODUCT_RESIZE_IMAGE_PATH', $fproduct['product_image'], 230, 200) ?>" class="img-responsive" alt="<?php echo $fproduct['product_name'] ?>" />
                                        </div>
                                    </a>
                                    <div class="info">
                                        <div class="">
                                            <div class="price">
                                                <a href="<?php echo createUrl('catalogue/product/') . $fproduct['product_alias'] ?>">
                                                    <h5 class="product-name">
                                                        <?php echo $fproduct['product_name'] ?> 
                                                    </h5>
                                                </a>
                                            </div>
                                            <div class="product-review">
                                                <div id="ShowRelated-true-<?= $rel ?>" class="star-rating-block"></div>

                                            </div>
                                            <div class="">
                                                <div class="product-price">
                                                    <div class="price-box">
                                                        <?php if ($fproduct['end_time']) { ?>
                                                            <span class="special-price">
                                                                <!--<span class="price-label"></span>-->
                                                                <div class="main-example">
                                                                    <span id="main-example-<?= $rel ?>"></span>
                                                                    <p class="note-offer-till-only-fixtime"> <i class="fa fa-clock-o" style="color:#333;"></i> offer is for limited time </p>
                                                                </div>
                                                                <?php $price = explode('-',$fproduct['end_time']); ?>
                                                                <span id="product-price-891_b" class="special-price">
                                                                    <span class="price"><?php echo MCC_CURRENCY_SYMBOL . $price[1] ?></span>						
                                                                </span>
                                                            </span>
                                                            <span class="old-price">
                                                                <span id="old-price-891_b" class="price">
                                                                    <?php echo MCC_CURRENCY_SYMBOL . $fproduct['product_price'] ?>               
                                                                </span>
                                                            </span>
                                                        
                                                        <script type="text/javascript">
                                            jQuery(window).on('load', function () {
                                                var labels = ['weeks', 'days', 'hours', 'minutes', 'seconds'],
                                                        nextYear = (new Date().getFullYear() + 1) + '/01/01',
                                                        template = _.template(jQuery('#main-example-template').html()),
                                                        currDate = '00:00:00:00:00',
                                                        nextDate = '00:00:00:00:00',
                                                        parser = /([0-9]{2})/gi,
                                                        $example = jQuery('#main-example');
                                                // Parse countdown string to an object
                                                function strfobj(str) {
                                                    var parsed = str.match(parser),
                                                            obj = {};
                                                    labels.forEach(function (label, i) {
                                                        obj[label] = parsed[i]
                                                    });
                                                    return obj;
                                                }
                                                // Return the time components that diffs
                                                function diff(obj1, obj2) {
                                                    var diff = [];
                                                    labels.forEach(function (key) {
                                                        if (obj1[key] !== obj2[key]) {
                                                            diff.push(key);
                                                        }
                                                    });
                                                    return diff;
                                                }
  
                                                    // Build the layout
                                                    var initData = strfobj(currDate);
                                                    labels.forEach(function (label, i) {
                                                        jQuery('#main-example-<?= $rel ?>').append(template({
                                                            curr: initData[label],
                                                            next: initData[label],
                                                            label: label
                                                        }));
                                                    });
                                                    // Starts the countdown
                                                    <?php $entime = explode('-',$fproduct['end_time'])?>
                                                    jQuery('#main-example-<?= $rel ?>').countdown(<?php echo $entime[0]?>, function (event) {
                                                        var newDate = event.strftime('%w:%d:%H:%M:%S'),
                                                                data;
                                                        if (newDate !== nextDate) {
                                                            currDate = nextDate;
                                                            nextDate = newDate;
                                                            // Setup the data
                                                            data = {
                                                                'curr': strfobj(currDate),
                                                                'next': strfobj(nextDate)
                                                            };

                                                            // Apply the new values to each node that changed
                                                            diff(data.curr, data.next).forEach(function (label) {
                                                                var selector = '.%s'.replace(/%s/, label),
                                                                        $node = jQuery('#main-example-<?= $rel ?>').find(selector);
                                                                // Update the node
                                                                $node.removeClass('flip');
                                                                $node.find('.curr').text(data.curr[label]);
                                                                $node.find('.next').text(data.next[label]);
                                                                // Wait for a repaint to then flip
                                                                _.delay(function ($node) {
                                                                    $node.addClass('flip');
                                                                }, 50, $node);
                                                            });
                                                        }
                                                    });
                                                       });
                                         </script>
                                                        <?php } else {
                                                            ?>
                                                            <span class="special-price">
                                                                <span id="product-price-891_b" class="special-price">
                                                                    <span class="price"><?php echo MCC_CURRENCY_SYMBOL . $fproduct['product_price'] ?></span>						
                                                                </span>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php //e($fproduct); ?>
                                        <div class="separator clear-left">
                                            <div class="product-addto-wrap">
                                                <div class="button-action item-addcart">
                                                    <form name='form-featured-product-<?=$fproduct['product_sku'] ?>' 
														class='form-featured-product-<?=$fproduct['product_sku'] ?>' 
														action='<?php echo base_url('catalogue/cart/addToCart') ?>' method='post'>
                                                        <input type='hidden' name='product_id' value='<?=$fproduct['product_id'] ?>'>
                                                        <input type='hidden' name='product_sku' value='<?=$fproduct['product_sku'] ?>'>
                                                        <input type='hidden' name='product_name' value='<?=$fproduct['product_name'] ?>'>
                                                        <input type='hidden' name='quantity' value='1'>
                                                        <div class="product-addcart">
															<?php 																
																if( $fproduct[ 'product_type_id' ] == 2 ){
															?>
																<a class="btn-cart" id='<?=$fproduct['product_sku'] ?>' 
																	href="<?= base_url( 'catalogue/product/'.$fproduct[ 'product_alias' ] ) ?>" 
																	title="Add to Cart">
																	<span>Add to Cart</span>																
																</a>
															<?php
																} else {
															?>
                                                            <button class="btn-cart add-to-cart" 
																id='<?=$fproduct['product_sku'] ?>' 
																title="Add to Cart" 
																type="submit">
																<span>Add to Cart</span>
															</button>
															<?php 
															}
															?>
                                                        </div>
                                                    </form>
                                                    <div class="wishlist-compare">												
                                                        <a class="link-wishlist" href="javascript:void(0);" title="Add to Wishlist" data-product_id="<?php echo $fproduct['product_id'] ?>">
                                                            Add to Wishlist
                                                        </a>							
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                            </li>
                            <?php
                            $rel++;
                        }
                    } else {
                        ?>
                        <div class="  col-sm-12 col-xs-12">
                            <h2>No Featured Products.</h2>
                        </div>

                    <?php } ?>  
                    <div class="clearfix"></div>
                </ul>
                <div class="clearfix"></div>         
            </div>
        </div>
    </div>
</div>
<div class="fancy-popup-custom-container">
    <div id="bpopup1" style="padding-top: 30px; display: none;">
        <div class=""></div>
        <span><img src="<?php echo base_url('img/add_to_cart_ico.png') ?>" width="32px" height="32px;"></span>
        <span id="bpopupContent" class="bpopupContent" style="font-size: 13px;font-weight: 600;padding-left: 5px;position: relative;top:3px;"> </span>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function ($) {
        $(".add-to-cart").click(function (event) {
            var sku = $(this).attr('id');
            console.log($('.form-featured-product-'+sku).serialize());
            
            
            var form = $('.form-featured-product-'+sku);
            var selectedQt = 1;
            if (selectedQt) {
                ;
                $.post(form.attr('action'),
                        {   <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>',
                            'form-data': $('.form-featured-product-'+sku).serialize(),
                        }
                ).done(function (data) {
                    data = jQuery.parseJSON(data);
                    $(".bpopupContent").html(data.message);
                    $("#bpopup1").fancybox().trigger('click');
                    
                   
                    setTimeout( function() {$.fancybox.close(); },2000);
                    $('.dropdown-cart').html(data.html)
                    $('.item-count').html(data.itemcount);

                    //  location.reload();
                });
                event.preventDefault();
                return false;
            } else {
                alert("Sorry quantity cannot be 0");
                return false;
            }
        });
    })
</script>
