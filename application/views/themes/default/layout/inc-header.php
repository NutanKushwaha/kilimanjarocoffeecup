<!-- Static navbar -->

<div class="header_top light_gray"><!--header_top-->
    <div class="container">
        <div class="row">
            <div class="col-sm-4 ">
                <?php if (cms_getcompany()) { ?>
                    <div class="contactinfo ">
                        <ul class="nav nav-pills">
                            <li><a><i class="fa fa-university"> <?= cms_getcompany(); ?></i></a></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-8">
                <div class="shop-menu pull-right">
                    <ul class="nav navbar-nav top-menu">

                        <li> <a href="<?php echo createUrl('wishlist'); ?>"><i class="fa fa-star"></i>  <span class="top-link-wishlist"></span>  <span class="topbar-account-icons"> My Wishlist </span> </a></li>
                        <li> <a href="<?php echo createUrl('catalogue/cart'); ?>"><i class="fa fa-shopping-cart"></i>  <span class="top-link-checkout"></span>  <span class="topbar-account-icons"> Cart </span> </a> </li>

                        <?php if (!$this->flexi_auth->is_logged_in()) { ?>
                            <li  class="hidden"> 
                                <a href="<?php echo createUrl('customer/register'); ?>" class="btn-head" title="Join Free">
                                    <i class="fa fa-crosshairs"></i> Register 
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo createUrl('customer/login'); ?>" class="btn-head" title="Sign in">
                                    <i class="fa fa-lock"></i> Login                         
                                </a>
                            </li> 
                            <?php
                        } else {
                            ?>
                            <?php
                            $ci = &get_instance();
                            $this->load->model('customer/Customermodel');
                            $userDetails = $this->Customermodel->getUserInformation();
                            ?>
                                
                            <li> <a href="<?php echo createUrl('customer/dashboard'); ?>"><i class="fa fa-user"></i>  <span class="top-link-myaccount"></span> <span class="topbar-account-icons"> <?php echo $this->flexi_auth->get_user_custom_data('upro_first_name') ?> </span></a> </li>

                            <?php // e($userDetails);  ?>
                            <li>
                                <a href="<?php echo createUrl('customer/logout') ?>">Logout</a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- logo topbar section -->
<div class="yt-header-middle">
    <div class="header-middle-right">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12 text-center">
                    <div class="logo pad-bot10 pad-top10">
                        <a href="<?php echo createUrl(''); ?>" title="kilimanjaro">
                            <img alt="kilimanjaro" src="<?php echo base_url() ?>image/logo.png" /> 
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12 mar-top30 right text-center">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown" id="topbar-cart-container">
                            <a aria-expanded="false" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#"> 
                                <div class="pull-left topbar-cart-left"> <img src="<?php echo base_url('imgs/ico-minicart.png') ?>" alt="Shopping Cart"/></div> 

                                <div class="pull-right topbar-cart-right"> 
                                    <div class="cart-head-text"> Shopping Cart </div>
                                    <div class="cart-title-text"><span class="item-count"> <?php echo $this->cart->total_items(); ?></span> - Items &nbsp; 
                                        <!--<i class="fa fa-gbp"></i> 0.00 -->
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </a>
                            <ul role="menu" class="dropdown-menu dropdown-cart "  >
                                <?php
                                $this->load->model('catalogue/Productmodel');
                                if (count($this->cart->contents()) > 0) {
                                    foreach ($this->cart->contents() as $key => $cartProduct) {
                                        ?>
                                        <li>


                                            <div class="media"> 
                                                <div class="media-heading">
                                                    <div class="item-info">
                                                        <span><?php echo $cartProduct['name']; ?></span>
                                                        
                                                    </div>
                                                </div>  
                                                <div class="media-body"> 
                                                    <div style="width: 50%;float: left;"> 
                                                        <div style="padding-left: 5px;" class="pad-top20">
                                                            <a href="#"> 
                                                                <img  alt="" src="<?php echo com_get_image('PRODUCT_IMAGE_URL', 'PRODUCT_IMAGE_PATH', 'PRODUCT_RESIZE_IMAGE_URL', 'PRODUCT_RESIZE_IMAGE_PATH', $this->Productmodel->getProductImageBySku($cartProduct['product_sku']), 350, 500) ?>" alt="<?php echo $cartProduct['name'] ?>" class="img-responsive" >
                                                            </a> 
                                                        </div> 
                                                    </div>
                                                     <div style="width: 50%;float: left;"> 
                                                        <div class="item-info pad-top30">
                                                            <div>Qty : <?php echo $cartProduct['qty']; ?></div>
                                                            <div>Price : <?= MCC_CURRENCY_SYMBOL . $cartProduct['price']; ?></div>
                                                            <span  class="mar-top10 btn btn-xs btn-danger pull-right remove-item" data-product-id="<?php echo $key ?>">x</span>
                                                        </div>
                                                    </div>
                                                    
                                                </div> 
                                            </div>
                                        </li>
                                        <li><hr style="margin: 0;padding: 0;"></li>
                                        <?php
                                    }
                                    ?>
                                    <div class="media"> 
                                        <div class="col-md-12" style="text-align:center; background-color: rgb(255, 255, 255);" >
                                            <a href="<?php echo createUrl('catalogue/cart'); ?>" >
                                                Checkout
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <li>No items added in cart.</li>
                                <?php } ?>
                            </ul>
                            <!--<a href="<?php // echo createURl('catalogue/cart');           ?>" class="text-center view-cart-option-btn"> View Cart </a>-->
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class=" header-menu">
    <div class="container">
        <div class="row">
            <nav class="navbar mar-bot0 col-lg-12" id="top-first-navbar-container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <button data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="clearfix"></div>
                <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
                    <?php
                    $params = array(
                        'menu_alias' => 'header_menu',
                        'ul_format' => ' <ul class="nav navbar-nav">{MENU}</ul>',
                        'level_1_format' => '<a href="{HREF}"{ADDITIONAL}><span>{LINK_NAME}</span></a>',
                        'level_2_format' => '<a href="{HREF}"{ADDITIONAL}><span>{LINK_NAME}</span></a>'
                    );
                    echo $CI->html->menu($params);
                    ?>
                </div><!-- /.navbar-collapse -->
                <!-- /.container-fluid -->
            </nav>
        </div>
    </div>
</div>
<div class="clearfix"></div>
