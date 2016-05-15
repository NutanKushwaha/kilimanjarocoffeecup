<style>

    .bd-cart-inner-container {

        font-size: 14px;

    }

    .bd-cart-top-header {

        background: #535353 none repeat scroll 0 0;

        color: #fff;

        /*margin-bottom: 15px;*/

        padding: 10px;

    }

    .bd-cart-top-content.tabl-row {

        border-left: 1px solid #ccc;

        border-right: 1px solid #ccc;

        padding: 10px 0 2px;

        vertical-align: middle

    }

    .bd-cart-top-content.tabl-row:last-child {

        border-bottom: 1px solid #ccc;

        padding: 10px 10px 0;

    }

    .bd-cart-top-footer.tabl-row:last-child {

        margin-top: 30px;

    }

    .donate-check-section{



    }

    .donationResponse{

        background: rgb(255, 128, 76) none repeat scroll 0 0;

        color: rgb(255, 255, 255);

        display: none;

        float: right;

        margin-bottom: 5px;

        padding: 5px 10px;

        text-align: center;

    }

    .bd-cart-top-content.tabl-row.odd {

        background: #f4f4f4 none repeat scroll 0 0;

    }

    .bd-cart-top-content p {

        padding-top: 8px;

    }

    .charitymode{

        margin-top: 10px !important;

    }

</style>



<?php // e($this->cart->contents());                                   ?>

<div style='text-align: center'>

    <?php //$this->load->view('inc-messages'); ?>

</div>

<?php // e( $allAddress) ?>

<div class="col-lg-12">

    <div class="columns-w">														

        <div class="columns-w">

            <?php //$this->load->view('themes/'.THEME.'/layout/inc-dashboard'); ?>

            <div class="account-create yt-main-right col-xs-12">

                <div class="page-title">

                    <h1>Cart</h1>

                </div>

                <div class="bd-cart-full-container">

                    <form action="<?php echo createUrl('catalogue/cart') ?>" method="post" enctype="multipart/form-data">

                        <div class="bd-cart-inner-container">

                            <div class="row">

                                <?php

                                if (count($cart) > 0) {

                                    ?>

                                    <div class="col-md-12">

                                        <div class="bd-cart-top-header">

                                            <div class="col-md-5">

                                                <strong> Product name </strong>

                                            </div>

                                            <div class="col-md-2">

                                                <strong> Product price </strong>

                                            </div>

                                            <div class="col-md-2">

                                                <strong> Product quantity </strong>

                                            </div>

                                            <div class="col-md-2">

                                                <strong> Subtotal </strong>

                                            </div>

                                            <div class="col-md-1">

                                                <strong> Action </strong>

                                            </div>

                                            <div class="clearfix"></div>

                                        </div>

                                    </div>                                

                                    <?php

                                    foreach ($cart as $product) {

                                        ?>

                                        <div class="clearfix"></div>

                                        <!-- (content area) -->



                                        <div class="col-md-12">

                                            <div class="bd-cart-top-content tabl-row even">

                                                <div class="col-md-5">

                                                    <p>   <?php echo $product['name'] ?>

                                                        <?php if (isset($product['options']['attributes'])) {

                                                            ?>

                                                            <br>

                                                            <?php foreach ($product['options']['attributes'] as $attr) { ?>

                                                                <span><?php echo $attr['label'] ?>:<?php echo $attr['value'] ?>

                                                                </span>

                                                            <?php } ?>

                                                        <?php } ?>  </p>

                                                </div>

                                                <div class="col-md-2">

                                                    <p>

                                                        <?php echo MCC_CURRENCY_SYMBOL . number_format($product['price'], 2) ?>

                                                        <input type="hidden" name='item-index' value='<?php echo $product['rowid'] ?>' >



                                                    </p>

                                                </div>

                                                <div class="col-md-2">

                                                    <input 	type="number" 

                                                            min="1" 

                                                            value="<?php echo $product['qty'] ?>" name="qty[<?php echo $product['rowid'] ?>]" class="qty" style="width: 60px; height: auto; padding: 5px 0px 5px 5px;">

                                                </div>

                                                <div class="col-md-2">

                                                    <p> <?php echo MCC_CURRENCY_SYMBOL . number_format($product['subtotal'], 2) ?></p>

                                                </div>

                                                <div class="col-md-1">

                                                    <p> <a onclick="return confirm('Are you sure you want to delete this record ?')" href="<?php echo createUrl('catalogue/cart/removeCartItem/') . $product['rowid'] ?>">Delete</a></p>

                                                </div>

                                                <div class="clearfix"></div>

                                            </div>

                                        </div>

                                    <?php } ?>

                                    <div class="clearfix"></div>

                                    <!-- (content area) -->

                                    <div class="col-md-12">

                                        <div class="bd-cart-top-content tabl-row odd">

                                            <div class="col-md-offset-7 col-md-4 text-right">                                                

                                                <p><strong>Subtotal (Excl vat)</strong></p>                                     

                                            </div>

                                            <div class="col-md-1">

                                                <p>

                                                    <?php

                                                    $total = $this->cart->total();

                                                    echo number_format(($total * (100 / (100 + MCC_VAT))), 2);

                                                    ?>

                                                </p>

                                            </div>                                        								

                                            <div class="clearfix"></div>

                                        </div>

                                    </div>

                                    <div class="clearfix"></div>

                                    <!-- (content area) -->

                                    <div class="col-md-12">

                                        <div class="bd-cart-top-content tabl-row even">

                                            <div class="col-md-offset-7 col-md-4 text-right">

                                                <p> <strong> VAT  </strong> </p>

                                            </div>



                                            <div class="col-md-1">

                                                <p> <?php echo MCC_VAT . '%' ?> </p>

                                            </div>

                                            <div class="clearfix"></div>

                                        </div>

                                    </div>



                                    <div class="clearfix"></div>

                                    <!-- (content area) -->

                                    <div class="col-md-12">

                                        <div class="bd-cart-top-content tabl-row odd">

                                            <div class="col-md-offset-7 col-md-4 text-right">

                                                <p><strong> Subtotal (Incl vat) 

                                                        <?php if ($coupon_details && $coupon_discounted_amount) { ?>

                                                            <br/> <Small>Coupon <?= $coupon_details['code'] ?> applied</Small>

                                                        <?php } ?>

                                                    </strong></p>

                                            </div>



                                            <div class="col-md-1">

                                                <?php

                                                $total = $this->cart->total();

                                                if ($coupon_details) {

                                                    if ($coupon_details['vstyle'] == "value") {

                                                        $total = $total - $coupon_details['amount'];

                                                    } elseif ($coupon_details['vstyle'] == "percentage") {

                                                        $total = $total - ($total * $coupon_details['amount'] / 100);

                                                    }

                                                }

                                                ?>

                                                <p> <?php echo MCC_CURRENCY_SYMBOL . number_format($total, 2); ?> </p>

                                            </div>

                                            <div class="clearfix"></div>

                                        </div>

                                    </div>



                                    <div class="clearfix"></div>

                                    <!-- (content area) -->

                                    <div class="col-md-12">

                                        <div class="bd-cart-top-content tabl-row even">

                                            <div class="col-md-offset-7 col-md-4 text-right hidden">

                                                <p><strong> Do you want to donate ?  </strong></p>

                                            </div>



                                            <div class="col-md-1">

                                                <div> 

    <?php

    $display = "";

    if ($this->session->userdata('donation') == "1") {

        $display = "checked";

    }

    ?>

                                                    <input type="checkbox" value="1" class="charitymode hidden" name="charitymode" <?php echo $display; ?>><br/>



                                                </div>

                                            </div>

                                            <div class="col-md-12">

                                                <div class="donate-check-section"> 

    <?php

    $display = "display:none";

    if ($this->session->userdata('donation') == "1") {

        $display = "display:block";

    }

    ?>

                                                    <div class="donationResponse text-right"  style="<?php echo $display ?>">

                                                    <?php if ($this->session->userdata('donation') != null) { ?>

                                                            (Sub Total After donation :$<?php echo $total + $this->session->userdata('donationAmount') ?>)

                                                        <?php } ?>



                                                    </div>  

                                                </div>

                                            </div>

                                            <div class="clearfix"></div>

                                        </div>

                                    </div>



                                    <div class="clearfix"></div>

                                    <!-- (content area) -->

    <?php

    $display = "display:none";

    if ($this->session->userdata("donation") != null) {

        $display = "display:block";

    }

    ?>

                                    <div class="col-md-12 charityType" style="<?php echo $display ?>">

                                        <div class="bd-cart-top-content tabl-row charityType odd">

                                            <div class="col-md-offset-6 col-md-4 text-right">

                                                <p><strong> Donation type:  </strong></p>

                                            </div>



                                            <div class="col-md-2">

                                                <p> 

    <?php echo form_dropdown('DonationType', $donationType, $this->session->userdata('donationMode'), 'style="width:100%"'); ?>

                                                </p>

                                            </div>

                                            <div class="clearfix"></div>

                                        </div>

                                    </div>



                                    <div class="clearfix"></div>

                                    <!-- (content area) -->

    <?php

    $display = "display:none";

    if ($this->session->userdata("donationAmount") != null) {

        $display = "display:table-row";

    }

    ?>

                                    <div class="col-md-12 newAmount " style="<?php echo $display ?>">

                                        <div class="bd-cart-top-content tabl-row charityType even">

                                            <div class="col-md-offset-6 col-md-4 text-right">

                                                <p><strong> Donation Amount:  </strong></p>

                                            </div>



                                            <div class="col-md-2">

                                                <p> 

                                                    <input 	type="text" name='newAmount' 

                                                            value='<?php echo $this->session->userdata('donationAmount') ?>' style="width:100%;">

                                                </p>

                                            </div>

                                            <div class="clearfix"></div>

                                        </div>

                                    </div>



                                    <div class="clearfix"></div>

                                    <!-- (content area) -->

                                    <div class="col-md-12">

                                        <div class="bd-cart-top-footer tabl-row">

                                            <div class="yt-product-detail text-right">

                                                <div class="col-xm-8">

                                                    <div class="add-to-cart pull-left">

    <?php if ($logged_user_id) { ?>														

                                                        <input class="hidden"  	type="text" name="coupon" id="coupon" 

                                                                    value="<?= set_value('coupon', '') ?>" 

                                                                    placeholder="One per purchase" 

                                                                    class="pull-left" style="pull-left:5px"

                                                                    />



                                                            <input class="hidden" 	type="submit" name="coupon_applied" value='Apply coupon' 

                                                                    class="button update-cart-button pull-left" 

                                                                    style=" padding: 11px 15px;"

                                                                    onclick=" function(){

                                                                                                                                                            var coupon_text = $('#coupon').val();

                                                                                                                                                            if (coupon_text == '') {

                                                                                                                                                                alert('Please fill coupon');

                                                                                                                                                                return false;

                                                                                                                                                            }

                                                                                                                                                            return true;

                                                                                                                                                        }

                                                                                                                                                        "

                                                                    >

                                                            

    <?php } ?>

                                                        <a 	href="<?php echo createUrl('catalogue/cart/clear_cart') ?>" 

                                                            class="button btn-cart btn-cart-single pull-left" style="padding: 11px 21px; text-decoration: none; ">Clear cart</a>

                                                    </div>

                                                </div>

                                                <div class="col-xm-4">

                                                    <input 	type	="submit" 

                                                            class	="button update-cart-button" 

                                                            value	="Update cart"  

                                                            name	="update_cart"  

                                                            style=" padding: 11px 15px;">  | 

                                                    <a  href="<?php echo createUrl('catalogue/cart/process'); ?>" 

                                                        class="button charityProcess" style="padding: 8px 21px; text-decoration: none; ">Checkout</a>

                                                </div>

                                                <div class="clearfix"></div>

                                            </div>

                                        </div>

                                    </div>





<?php } else { ?>

                                    <div class="clearfix"></div>

                                    <div class="col-md-12">

                                        <div class="no-item-container">

                                            <div class="col-md-12">



                                                <div class="no-item-msg-box-block text-center">

                                                    <p> <strong> <span> <img src="../img/cart-empty-2.png" width="35px"/> </span> &nbsp; No item found </strong></p>

                                                </div>



                                                <div class="cart-other-continous-container">

                                                    <div class="cart-continous-btn-block text-center">

                                                        <a href="catalogue" class="btn-cart add-to-cart">

                                                            <span> Continue shopping </span>

                                                        </a>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="clearfix"></div>

                                        </div>



                                    </div>

<?php } ?>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        $('#coupon').on('change', function () {

            $(this).val($(this).val().toUpperCase());

        });

        $('.qty').on('input', function () {

            if (/\D/g.test(this.value))

            {

                // Filter non-digits from input value.

                this.value = this.value.replace(/\D/g, '');

            }

        });

        $('.qty').keypress(function (event) {

            if (event.keyCode == 13) {

                event.preventDefault();

            }

        });



        $('.charitymode').on('click', function () {

            var check = $(this).prop("checked");

            if (check)

            {

                $('.donationResponse').html("");

                $('.donationResponse').hide();

                $(".charityType").show();

                $("select[name='DonationType'] option[value='']").prop("selected", true);

            } else {

                $('.donationResponse').html("");

                $('.donationResponse').hide();

                $(".charityType").hide();

                $('.newAmount').hide();

                $("select[name='DonationType'] option[value='']").prop("selected", true);



                $.get("<?php echo base_url("catalogue/cart/uns_charity") ?>", function (data) {



                }, 'JSON')

            }

        });

        $("select[name='DonationType']").change(function () {

            var donationType = $(this).val();

            if (donationType == "insert")

            {

                $("input[name='newAmount']").val("")

                $(".newAmount").show()

                $('.donationResponse').html("");

                $('.donationResponse').hide();

            } else {



                $(".newAmount").hide()

                $.post("<?php echo base_url("catalogue/cart/charity_process") ?>", {mode: donationType}, function (data) {

                    if (data.response == true)

                    {

                        var addedVAlue = parseFloat(data.totalAmount);

                        $('.donationResponse').html("(Sub Total After donation :$;" + addedVAlue + ")");

                        $('.donationResponse').show();

                    } else {

                        $('.donationResponse').html(data.msg);

                        $('.donationResponse').show();

                        $("select[name='DonationType'] option[value='round']").prop("disabled", true);

                    }

                }, 'JSON')

            }

        })

        $("input[name='newAmount']").keyup(function () {

            var donationType = $("select[name='DonationType']").val();

            var nwAmount = $("input[name='newAmount']").val();

            $.post("<?php echo base_url("catalogue/cart/charity_process") ?>", {mode: donationType, newAmount: nwAmount}, function (data) {

                if (data.response == true) {

                    var addedVAlue = parseFloat(data.totalAmount);

                    $('.donationResponse').html("(Sub Total After donation :$" + addedVAlue + ")");

                    $('.donationResponse').show();

                } else {



                    $('.donationResponse').html(data.msg);

                }

            }, 'JSON')

        })

    })

</script>

