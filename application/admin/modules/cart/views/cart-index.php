

<?php
    $default_prod_image = com_get_product_image('default_product.jpg', 250, 180);
?>
<!-- \* New code  start *\ -->

<?PHP
    $comp_color = com_get_theme_menu_color();
    $base_color = '#f0ad4e';
    $hover_color = '#ec971f';
    if ($comp_color) {
        $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#f0ad4e');
        $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#ec971f');
    }
?>
<style>

    .btn-warning{
        background-color: <?= $base_color; ?>;
        border-color: <?= $hover_color; ?>;
    }
    .btn-warning:hover, .btn-warning:focus, 
    .btn-warning.focus, .btn-warning:active, 
    .btn-warning.active, .open > .dropdown-toggle.btn-warning{
        background-color: <?= $hover_color; ?>;
        border-color: <?= $base_color; ?>;
        color: #fff !important;
    }
</style>

<style>    

    .view-cart-full-container .btn{
        color: #fff;
    }
    .view-cart-full-container th {
        font-size: 14px;
    }    
    .tfooter-btm-total-price{
        font-size: 14px;
    }            

</style>

<div class="view-cart-full-container">
    <div class="view-cart-full-container-2">
        <?php
        if (!$cartContents) {
            echo '<p>There are no items in your Enquiry basket.</p>';
        } else {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <h4><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart</h4>
                            </div>

                        </div>
                    </div>
                </div>
                <table id="cart" class="table table-hover table-condensed table-cart-view-container">                
                    <thead>
                        <tr>
                            <th style="width:57%">Product</th>
                            <th style="width:10%">Price</th>
                            <th style="width:13%">Quantity</th>
                            <th style="width:15%" class="text-center">Subtotal</th>
                            <th style="width:10%"> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($cartContents as $cRowId => $cRowDetail) {
                            $prodDesc = "";
                            if (isset($cRowDetail['options']['product']['product_description'])) {
                                $len = 110;
                                $prodDesc = substr(strip_tags($cRowDetail['options']['product']['product_description'])
                                        , 0, $len);
                                $prodDesc = ellipsize(ucfirst($prodDesc), 97, 1);
                            }
                            $productImage = "";
                            if (isset($cRowDetail['options']['product']['product_imgae'])) {
                                $productImage = $cRowDetail['options']['product']['product_imgae'];
                            }
                            $prod_img_url = $this->config->item('PRODUCT_IMAGE_URL') . $productImage;
                            if (empty($productImage)) {
                                $prod_img_url = $default_prod_image;
                            }
                            $params = [
                                'image_url' => $prod_img_url,
                                'image_path' => $this->config->item('PRODUCT_IMAGE_PATH') . $productImage,
                                'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
                                'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
                                'width' => 250,
                                'height' => 180,
                                'default_picture' => $default_prod_image
                            ];
                            $image_url = resize($params);
                            ?>
                            <tr id="<?= $cRowId; ?>">
                                <td data-th="Product">
                                    <div class="row">
                                        <div class="col-sm-3 hidden-xs">
                                            <img    src="<?= $image_url; ?>" 
                                                    alt="Bodyguard" 
                                                    class="img-responsive"/>
                                        </div>
                                        <div class="col-sm-9 cart-view-prod-desc-text">
                                            <h4 class="nomargin"><?= ellipsize(ucfirst($cRowDetail['name']), 35, 1); ?></h4>
                                            <p><?= $prodDesc; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td data-th="Price" class="cart-product-price">&#163; <?= $cRowDetail['price']; ?></td>
                                <td data-th="Quantity">                            
                                    <input id="qty-<?= $cRowId; ?>" type="number" class="form-control text-center" 
                                           value="<?= $cRowDetail['qty']; ?>" min="1" max="10000">
                                </td>
                                <td data-th="Subtotal" 
                                    class="text-center cart-product-price">&#163; <span id="subTotal-<?= $cRowId; ?>"><?= $cRowDetail['subtotal']; ?></span></td>
                                <td class="actions" data-th="">
                                    <button onclick="updateCartItem('<?= $cRowId; ?>')" 
                                            class="btn btn-info btn-sm"><i class="fa fa-refresh"></i></button>
                                    <button onclick="removeCartItem('<?= $cRowId; ?>')" 
                                            class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                                </td>
                            </tr>                    
                            <?php
                        }
                        ?>
                    </tbody>                
                    <tfoot class="panel-footer">                    
                        <tr>
                            <td>
                                <a href="wardrobe" class="btn btn-default" style="color:#222;">
                                    <i class="fa fa-angle-left"></i> Continue Shopping <i class="glyphicon glyphicon-shopping-cart"></i>
                                </a>
                            </td>
                            <td colspan="2" class="hidden-xs"></td>
                            <td class="hidden-xs text-center tfooter-btm-total-price"><strong>Total &#163; <span id="cartTotal"><?= $cartTotal; ?></span></strong></td>
                            <td>
                                <?php if ($user_type == CMP_ADMIN) { ?>
                                    <a href="<?= base_url('order/addOrder'); ?>" 
                                       class="btn btn-warning btn-block">Place Order <i class="fa fa-angle-right"></i>
                                    </a>
                                <?php } else if ($user_type == CMP_MD || $user_type == CMP_PM) { ?>
                                    <button class="btn btn-warning btn-block" onclick="placeRequest()">
                                        Place Request <i class="fa fa-angle-right"></i>								
                                    </button>
                                    <script>
                                        function placeRequest( ) {
                                            $.get("request/ajax/request/addRequest", function (data) {
                                                data = jQuery.parseJSON(data);
                                                $("#cartTotal").html("0.00");
                                                $("#wardrobecart").html("");
                                                $('#comMsgModalTitle').html('Order');
                                                $('#comMsgModalBody').html(data.message);
                                                $('#comMsgModal').modal('show');
                                                $("#cart").html("");
                                            });

                                        }
                                    </script>
                                <?php } ?>
                            </td>
                        </tr>
                    </tfoot>
                    <script>
                        function updateCartItem(rowId) {
                            var qty = $("#qty-" + rowId).val();
                            $.get('<?= base_url() . "cart/ajax/cart/updateCartItem/" ?>',
                                    {
                                        'qty': qty,
                                        'rowId': rowId,
                                    },
                                    function (data) {
                                        data = JSON.parse(data);
                                        if (data.success) {
                                            $('#cartTotal').html(data.cartTotal);
                                            $('#subTotal-' + rowId).html(data.itemSubTotal);
                                        }
                                    }
                            );
                        }

                        function removeCartItem(rowId) {
                            $.get('<?= base_url() . "cart/ajax/cart/removeCartItem/" ?>',
                                    {'rowId': rowId},
                                    function (data) {
                                        data = JSON.parse(data);
                                        if (data.success) {
                                            $('#cartTotal').html(data.cartTotal);
                                        }
                                        var rowTr = "#" + rowId;
                                        $(rowTr).remove();
                                    }
                            );
                        }

                        $(document).ready(
                                function () {
                                    // Select your input element.
                                    var numInput = document.querySelector('input');
                                    // Listen for input event on numInput.
                                    numInput.addEventListener('input', function () {
                                        // Let's match only digits.
                                        var num = this.value.match(/^\d+$/);
                                        if (num === null || this.value == "0") {
                                            // If we have no match, value will be empty.
                                            this.value = "1";
                                        }
                                        if (num > 100000) {
                                            this.value = 100000;
                                        }
                                    }, false)
                                });
                    </script>
                </table>
            </div>
            <?php
        }
        ?>
    </div>    
</div>
