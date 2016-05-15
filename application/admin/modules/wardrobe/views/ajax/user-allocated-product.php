<?php    

    com_get_product_image('default_product.jpg', 197, 134);

    com_get_product_image('default_product.jpg', 250, 250);

?>

<div class="warddrobe-choose-product-list-section">

    <ul class="list-unstyled" style="margin-bottom:0">

    <?php

        if( $products ){/* If product exist */			

            foreach ($products as $prod_key => $prod_det) { /* For each start*/                

    ?>

        <li class="choice-warddrobe-product col-md-4">                

            <div class="warddrobe-product-img">

                <img src="<?= com_get_product_image($prod_det['product_image'], 197, 134); ?>" />

            </div>

            <div class="warddrobe-product-title">                                    

                <div class="text-left">					

                    <span><?= character_limiter( ucfirst($prod_det['product_name']) , 25); ?></span><br/>

                    <small><span><?= $prod_det['product_sku']; ?></span></small>

                </div>                                    

            </div>                                

            <div class="clearfix"></div>

            <div class="add-warddrobe-product-section">

               <div class="pull-left">

                    <span class="product_curreny_price">$<?= $prod_det['product_price']; ?></span>

                    <span class="badge pull-right"><?= $prod_det['product_point']; ?></span> 

                </div>

                

                <div class="pull-right">

                    <?php if( in_array( $user_type, [ CMP_ADMIN, CMP_MD, CMP_PM ] ) ){ ?>

                        <a type="button" class="btn btn-orange" 

                            href="<?= base_url("wardrobe/view_product/".$prod_det['product_sku']."/".$category_dept."/".$current_offset ); ?>"> 

                            <i class="fa fa-plus-circle"></i>  View 

                        </a>

                    <?php } else  { ?>

                        <button type="button" class="btn btn-orange" data-toggle="modal" 

                                onclick="getProdDetail('<?= $prod_det['product_sku']; ?>', '<?= $current_offset; ?>')"> 

                           <i class="fa fa-plus-circle"></i>  View

                        </button>                        

                    <?php } ?>

                </div>

                <div class="clearfix"></div>

            </div>

        </li>

    <?php

        }/* For each end*/ }else{ ?> 

        <div class="col-md-12">

            <h4> <?= $error_message; ?> </h4>

        </div>

    <?php

        }

    ?>

    </ul>            

</div>

<div class="clearfix"></div>

<div class="col-lg-12 padding-0" style="padding-top: 15px;text-align:center" >

    <?= $pagination; ?>

</div>

