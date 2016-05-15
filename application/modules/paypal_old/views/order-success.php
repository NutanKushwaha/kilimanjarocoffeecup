<?php
$CI = &get_instance();
$CI->load->model('catalogue/Productmodel');
//e($order); 
?>
<div class="row">
    <div class="col-lg-12">
        <div class="columns-w" id="order-list-page">
            <h3>You Order placed Successfully !!</h3>
            <div style="display:none;" class="map-popup" id="map-popup">
                <a id="map-popup-close" class="map-popup-close" href="javascript:void(0);">x</a>
                <div class="map-popup-arrow"></div>
                <div class="map-popup-heading"><h2 id="map-popup-heading"></h2></div>
                <div id="map-popup-content" class="map-popup-content">
                    <div class="map-popup-checkout">
                        <form id="product_addtocart_form_from_popup" method="POST" action="">
                            <input type="hidden" id="map-popup-product-id" value="" class="product_id" name="product">
                            <div class="additional-addtocart-box">
                            </div>
                            <button id="map-popup-button" class="button btn-cart" title="Add to Cart" type="button"><span><span>Add to Cart</span></span></button>
                        </form>
                    </div>
                    <div id="map-popup-msrp-box" class="map-popup-msrp"><strong>Price:</strong> <span id="map-popup-msrp" style="text-decoration:line-through;"></span></div>
                    <div id="map-popup-price-box" class="map-popup-price"><strong>Actual Price:</strong> <span id="map-popup-price"></span></div>
                </div>
                <div id="map-popup-text" class="map-popup-text">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
                <div id="map-popup-text-what-this" class="map-popup-text">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
            </div>
            <div class="cart">
                <div class="title-buttons">
                    <div class="page-title"><h1> Order Details </h1></div>

                    <!--                    <ul class="checkout-types list-inline">
                                            <li>    <button class="button btn-proceed-checkout btn-checkout" title="Proceed to Checkout" type="button"><span><span>View Order</span></span></button></li>
                                            <li>    <button class="button btn-proceed-checkout btn-checkout" title="Proceed to Checkout" type="button"><span><span>Reorder Now</span></span></button></li>
                                        </ul>-->
                </div>
                <div class="clearfix"></div>

                <ul class="list-inline" id="order_number_list">
                    <li class="product-name"> Order Number:  <?php echo $order['order_num'] ?></li>
                </ul>
                <div class="clearfix"></div>
                <form method="post" action="">
                    <fieldset>
                        <table class="data-table cart-table" id="shopping-cart-table">

                            <thead>
                                <tr class="first last">
                                    <th class="hidden-mobile" rowspan="1">S No.</th>
                                    <th class="hidden-mobile" rowspan="1">&nbsp;</th>
                                    <th rowspan="1"><span class="nobr">Product Name</span></th>
                                    <th colspan="1" class="a-center">SKU</th>
                                    <th class="a-center" rowspan="1">Qty</th>
                                    <th colspan="1" class="a-center"><span class="nobr">Cart total</span></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                foreach ($order_items as $oproduct)
                                {?>
                                    <tr class="first odd">
                                        <td class="a-center">
                                            <h2 class="product-name">
                                                <?php
                                                echo $i;
                                                $i++;
                                                ?> 
                                            </h2>
                                        </td>
                                        <td class="a-center hidden-mobile">
                                            <a class="product-image" title="Kore mire dase" href="javascript:void(0);">
                                                <img  width="60px" alt="<?php echo $oproduct['order_item_name'] ?>" 
                                                src="<?php echo com_get_image('PRODUCT_IMAGE_URL', 'PRODUCT_IMAGE_PATH', 'PRODUCT_RESIZE_IMAGE_URL', 'PRODUCT_RESIZE_IMAGE_PATH', $CI->Productmodel->getProductImageBySku($oproduct['product_ref']), 350, 500) ?>">
                                                </a>
                                            </td>
                                        <td class="a-center">
                                            <h4 class="product-name">
                                                <?php echo $oproduct['order_item_name'] ?>
                                            </h4>
                                        </td>
                                        <td class="a-center">
                                            <span class="cart-price">
                                                <span class=""><?php echo $oproduct['product_ref'] ?></span>                
                                            </span>
                                        </td>
                                        <td class="a-center">
                                            <span class="cart-price">
                                                <p class="price"><strong class="text-success"> <?php echo $oproduct['order_item_qty'] ?> </strong> </p>                
                                            </span>
                                        </td>
                                        <td class="a-center">
                                            <span class="cart-price">
                                                <span class="price"><?php echo MCC_CURRENCY_SYMBOL . $oproduct['order_item_price'] ?></span>                
                                            </span>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                    </fieldset>
                </form>
                <div class="cart-collaterals">
                    <div class="col2-set">
                        <div class="row">
                            <div class="col-lg-offset-8 col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-right">
                                <div class="totals">
                                    <table id="shopping-cart-totals-table">
                                        <colgroup><col>
                                            <col width="1">
                                        </colgroup>
                                        <tfoot class="pull-right">
                                            <tr>
                                                <td colspan="1" class="a-right" style="">
                                                    <h4 style="padding-right: 10px;" class="product-name"> Subtotal (Excl vat): <span class="price product-name"><?php echo MCC_CURRENCY_SYMBOL.round(($order['order_total'] * (100 / (100 + $order['vat']))), 2) ?></span> </h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="a-right" style="">
                                                    <h4 style="padding-right: 10px;" class="product-name"> VAT: <span class="price product-name"><?php echo $order['vat'].'%'  ?></span> </h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="a-right" style="">
                                                    <h4 style="padding-right: 10px;" class="product-name"> Subtotal (Incl vat): <span class="price product-name"><?php echo MCC_CURRENCY_SYMBOL . $order['order_total'] ?></span> </h4>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
            </div>
        </div>    
    </div>
</div>