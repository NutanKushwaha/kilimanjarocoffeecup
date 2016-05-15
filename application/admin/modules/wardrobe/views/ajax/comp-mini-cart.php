<a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-bottom:0;"> 
    <div class="pull-left topbar-cart-left"> <img src="../imgs/ico-minicart.png"></div> 
    <div class="pull-right topbar-cart-right"> 
        <div class="cart-head-text"> Shopping Cart </div>
        <div class="cart-title-text"><?= $itemTotal; ?> Items &nbsp; <i class="fa fa-gbp"></i> <?= $cartTotal; ?> </div>
    </div>
    <div class="clearfix"></div>
</a>
<div class="dropdown-menu dropdown-cart" id="wardrobe-cart-items-list">
    <ul class="dropdown-menu-cart-items-list" role="menu">
        <?php
            $params = [
                    'image_url' => $this->config->item('PRODUCT_IMAGE_URL').'default_product.jpg',
                    'image_path' => $this->config->item('PRODUCT_IMAGE_PATH').'default_product.jpg',
                    'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
                    'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
                    'width' => 50,
                    'height' => 50,
                    ];
            $default_image_url = resize( $params );
            foreach ($cartContents as $cRowId => $cRowDetail) {
            $productImage = "";
            if( isset( $cRowDetail['options']['product']['product_imgae'] ) ){
                $productImage = $cRowDetail['options']['product']['product_imgae'];
            }            
            $p_img_url =    $this->config->item('PRODUCT_IMAGE_URL').$productImage;            
            if( empty( $productImage ) ){
                $p_img_url = $default_image_url;
            }
            $params = [
                    'image_url' => $p_img_url,
                    'image_path' => $this->config->item('PRODUCT_IMAGE_PATH').$productImage,
                    'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
                    'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
                    'width' => 50,
                    'height' => 50,
                    'default_picture' => $default_image_url
                    ];
            $image_url = resize( $params );
        ?>
            <li id="<?= $cRowId; ?>">
                <div class="item wardrobes-topmini-cart-container">
                    <div class="item-left">                                                        
                        <div  class="wardrobes-mini-cart-left-prod-img-block">
                            <img src="<?= $image_url; ?>" alt="" >
                        </div>
                        <div class="item-info wardrobes-mini-cart-center-block">
                            <div><?= ellipsize(ucfirst( $cRowDetail['name']), 18, 1); ?></div>
                            <div><i class="fa fa-gbp"></i><?= $cRowDetail['price']; ?> * <?= $cRowDetail['qty']; ?></div>
                        </div>
                    </div>
                    <div class="item-right wardrobes-mini-cart-right-block">
                        <button onclick="removeCartItem('<?= $cRowId;?>')" 
                                class="btn btn-xs btn-danger pull-right">x</button>
                    </div>
                </div>
            </li>
        <?php
            }
        ?>
    </ul>
    <div class="divider"></div>
    <div class="text-center"><a href="cart">View Cart</a></div>
</div>