<?php    

    com_get_product_image('default_product.jpg', 197, 134);

    com_get_product_image('default_product.jpg', 250, 250);

?>

<div class="warddrobe-choose-product-list-section">

    <ul class="list-unstyled">

    <?php

        if( $user_dept_prod ){/* If product exist */

            foreach ($user_dept_prod as $prod_key => $prod_det) { /* For each start*/                

    ?>

        <li class="choice-warddrobe-product col-md-4">                

            <div class="warddrobe-product-img">

                <img src="<?= com_get_product_image($prod_det['product_image'], 197, 134); ?>" />

            </div>

            <div class="warddrobe-product-title">                                    

                <div class="text-left">

                    <span><?= character_limiter($prod_det['product_name'], 25); ?>...</span>

                </div>                                    

            </div>                                

            <div class="clearfix"></div>

            <div class="add-warddrobe-product-section">

               <div class="pull-left">

                    <span class="product_curreny_price">$<?= $prod_det['product_price']; ?></span>

                    <span class="badge pull-right"><?= $prod_det['product_point']; ?></span> 

                </div>

                

                <div class="pull-right"> 

                    <button type="button" class="btn btn-orange" data-toggle="modal" onclick="getProdDetail('<?= $prod_det['product_sku']; ?>')"> 

                       <i class="fa fa-plus-circle"></i>  Add 

                    </button>

                </div>

                <div class="clearfix"></div>

            </div>

        </li>

    <?php

        }/* For each end*/ }else{ ?> 

        <div class="col-md-12">

            <h4>

                No product assign to department 

            </h4>

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