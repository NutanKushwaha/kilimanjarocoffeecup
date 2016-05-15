<?php //e(uri_string()) ?>
<div id="yt_content" class="yt-content wrap catalogue-search">
    <div class="yt-content-inner">
        <div class="container">
            <div class="row">
                <div id="yt_pathway" class="clearfix">
                    <div class="pathway-inner">
                        <!--<h4></h4>-->
                        <ul class="breadcrumbs">
                            <div class="breadcrumbs-content">
                                <li class="home" itemscope="" itemtype="">
                                    <a itemprop="url" 
                                        href="<?php echo createUrl(''); ?>" 
                                        title="Go to Home Page" class="has-link">
                                    <span itemprop="title">Home</span>
                                    </a>
                                </li>
                                <li class="product last" itemscope="" itemtype="">
                                    search
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>
                <div class="columns-w">
                    <div class="yt-product-detail" itemscope="" itemtype="">
                        <div class="yt-product-detail-inner">
                            <div class="product-essential">
                                <!-- Custom block in right -->
                                <!--Related products-->
                                <div class="clearfix"></div>
                                <!--Upsell products-->
                                <div class="category-products sm-slider related bottom-other-products-container">
                                    <div class="block-content">
                                        <!--Related products-->
                                        <div class="container">
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="block-title">
                                                            <h3 class="pull-left other-prod-title">
                                                            Search
                                                            </h3>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <!-- Wrapper for slides -->
                                                <div class=" related-produ-slide-container">
                                                    <ul class="slides">
                                                        <?php
                                                        if ($products['num_rows'] > 0) {
                                                        $rel = 1;
                                                        foreach ($products['result'] as $relative) { ?>
                                                        <li class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="col-item">
                                                                <div class="photo product-image">
                                                                    <a href="<?php echo createUrl('catalogue/product/') . $relative['product_alias']; ?>">
                                                                    <img alt="a" class="img-responsive" src="<?php echo com_get_image('PRODUCT_IMAGE_URL', 'PRODUCT_IMAGE_PATH', 'PRODUCT_RESIZE_IMAGE_URL', 'PRODUCT_RESIZE_IMAGE_PATH', $relative['product_image'], 350, 500) ?>" height="350px">
                                                                    </a>
                                                                </div>
                                                                <div class="info">
                                                                    <div class="product-info">
                                                                        <div class="product-name">
                                                                            <a 	href="<?php echo createUrl('catalogue/product/') . $relative['product_alias']; ?>" 
																				title="<?php echo $relative['product_name'] ?> ">
																				<?php echo ellipsize($relative['product_name'], 30, .5); ?> 
																			</a>
                                                                        </div>
                                                                        <div class="star-rate product-review">
                                                                            <a href="<?php echo createUrl('catalogue/product/') . $relative['product_alias']; ?>">
                                                                            <div id="ShowRelated-true-<?=$rel?>" class="star-rating-block"></div>
                                                                            <script type="text/javascript">
                                                                            $jk1('#ShowRelated-true-<?= $rel ?>').raty({readOnly:true, score: <?= ($relative['avgRating']==NULL)?0:$relative['avgRating']; ?>});
                                                                            });
                                                                            </script>
                                                                            </a>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                        <div class="product-price">
                                                                            <div class="price-box text-right">
                                                                                <?php if($relative['end_time']){ ?>
                                                                                <span class="special-price">
                                                                                <!--<span class="price-label"></span>-->
                                                                                <div class="main-example">
                                                                                    <span id="main-example-<?= $rel ?>"></span>
                                                                                    <p class="note-offer-till-only-fixtime"> <i class="fa fa-clock-o" style="color:#333;"></i> offer is for limited time </p>
                                                                                </div>
                                                                                <?php $price = explode('-', $relative['end_time']); ?>
                                                                                <span id="product-price-891_b" class="special-price">
                                                                                <span class="price"><?php echo MCC_CURRENCY_SYMBOL . $price[1] ?></span>
                                                                                </span>
                                                                                </span>
                                                                                <span class="old-price">
                                                                                <span id="old-price-891_b" class="price">
                                                                                <?php echo MCC_CURRENCY_SYMBOL . $relative['product_price'] ?>
                                                                                </span>
                                                                                </span>
                                                                                <?php }else{ ?>
                                                                                <span class="regular-price" id="product-price-906">
                                                                                <!--span class="price-label"></span-->
                                                                                <span class="price alter-price-text"><?php echo MCC_CURRENCY_SYMBOL . $relative['product_price']; ?></span>
                                                                                <br>
                                                                                <?php //<span class="inc-tax-list"> (Including on Exclusive VAT)</span> ?>
                                                                                </span>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php
                                                                                                                                                                            }
                                                                                                                                                                        } else {
                                                        ?>
                                                        <li><h4>No Products Found.</h4></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php if ($products['num_rows'] > 0) { ?>
                                            <div class="block-content  col-lg-12" style="text-align:center;">
                                                <div class="pagination"><?php echo $pagination; ?></div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hot categories home page v2 -->
        </div>
    </div>
    <!-- END: content -->
</div>
<div id="bpopup" style="display:none"></div>
