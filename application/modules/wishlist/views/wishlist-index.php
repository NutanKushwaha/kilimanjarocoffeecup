<?php //e($wishlist);  ?>
<div style='text-align: center'>
    <?php $this->load->view('inc-messages'); ?>
</div>
<?php // e( $allAddress) ?>
<div class="col-lg-12">
    <div class="columns-w">														
        <div class="columns-w">
            <?php $this->load->view('themes/' . THEME . '/layout/inc-dashboard'); ?>
            <div class="account-create yt-main-right yt-main col-main col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="page-title">
                    <h1>My Wishlist</h1>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product name</th>
                            <th>Product alias</th>
                            <th>product_sku</th>
                            <th>product_price</th>
                            <th>weight</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($wishlist['num_rows'] > 0) {
                            foreach ($wishlist['result'] as $product) {
                                ?>
                                <tr>
                                    <td><a href="<?php echo createUrl('catalogue/product/') . $product['product_alias'] ?>"><?php echo $product['product_name'] ?></a></td>
                                    <td><?php echo $product['product_alias'] ?></td>
                                    <td><?php echo $product['product_sku'] ?></td>
                                    <td><?php echo $product['product_price'] ?></td>
                                    <td><?php echo $product['weight'] ?></td>
                                    <td>
                                        <form name='form-featured-product-<?= $product['product_sku'] ?>' class='form-featured-product-<?= $product['product_sku'] ?>' action='<?php echo base_url('catalogue/cart/addToCart') ?>' method='post'>
                                            <input type='hidden' name='product_id' value='<?= $product['product_id'] ?>'>
                                            <input type='hidden' name='product_sku' value='<?= $product['product_sku'] ?>'>
                                            <input type='hidden' name='product_name' value='<?= $product['product_name'] ?>'>
                                            <input type='hidden' name='quantity' value='1'>
                                            <div class="product-addcart">
                                                <button class="btn-cart add-to-cart" id='<?= $product['product_sku'] ?>' title="Add to Cart" type="submit"><span>Add to Cart</span></button>
                                            </div>
                                        </form>
                                        |
                                        <a href="<?php echo createUrl('wishlist/delete/') . $product['product_id'] ?>" onclick="return confirm('Are you sure u want to delete this record ?')">Delete</a>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            ?>
                            <tr>
                                <td colspan="9">
                                    <h4>
                                        No Products Found.
                                    </h4>
                                </td>
                            </tr>
                         <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="fancy-popup-custom-container">
    <div id="bpopup1">
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".add-to-cart").click(function (event) {
            var sku = $(this).attr('id');
            console.log($('.form-featured-product-' + sku).serialize());
            var form = $('.form-featured-product-' + sku);
            var selectedQt = 1;
            if (selectedQt) {
                ;
                $.post(form.attr('action'),
                        {<?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>',
                            'form-data': $('.form-featured-product-' + sku).serialize(),
                        }
                ).done(function (data) {
                    data = jQuery.parseJSON(data);
                    $("#bpopup1").html(data.message).fancybox().trigger('click');
                    setTimeout(function () {
                        $.fancybox.close();
                    }, 3000);
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