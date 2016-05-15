<script>

    (function ($) {

        "use strict"

        // Accordion Toggle Items

        var iconOpen = 'fa fa-minus',

                iconClose = 'fa fa-plus';

        $(document).on('show.bs.collapse hide.bs.collapse', '.accordion', function (e) {

            var $target = $(e.target)

            $target.siblings('.accordion-heading')

                    .find('i').toggleClass(iconOpen + ' ' + iconClose);

            if (e.type == 'show')

                $target.prev('.accordion-heading').find('.accordion-toggle').addClass('active');

            if (e.type == 'hide')

                $(this).find('.accordion-toggle').not($target).removeClass('active');

            $('.accordion').find('.collapse').not($target).removeClass('in');

        });

    })(jQuery);

</script>



<?PHP

$comp_color = com_get_theme_menu_color();

$base_color = '#f27733';

$hover_color = '#d37602';

if ($comp_color) {

    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#f27733');

    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');

}

?>



<style>

    /*    .more-wrap {

            background: #f27733 none repeat scroll 0 0;

        }

        .more-wrap:hover{

            background-color: #d37602;

        }

        .badge{

            background: #f27733 none repeat scroll 0 0;

        }

        .badge{

            background: #f27733 none repeat scroll 0 0;

        }

        .btn-orange{

            background-color: rgb(255, 133, 27);

            border-color: #ef6c3d;

        }

        .pagination > .active > a, .pagination > .active > span, 

        .pagination > .active > a:hover, .pagination > .active > span:hover, 

        .pagination > .active > a:focus, .pagination > .active > span:focus{

            background-color: #783914;

            border-color: #380000;

        }

    */



    .more-wrap {

        background: <?= $base_color; ?>;

    }

    .more-wrap:hover{

        background-color: <?= $hover_color; ?>;

    }

    .badge{

        background: <?= $base_color; ?>;

    }

    .btn-orange{

        background-color: <?= $base_color; ?>;

        border-color: <?= $hover_color; ?>;

    }



    .pagination > .active > a, .pagination > .active > span, 

    .pagination > .active > a:hover, .pagination > .active > span:hover, 

    .pagination > .active > a:focus, .pagination > .active > span:focus{

        background-color: <?= $base_color; ?>;

        border-color: <?= $hover_color; ?>;

    }









    .accordion-heading{

        background: none;

    }

</style>

<div class="col-lg-12 ">

    <?= $this->load->view(THEME . 'layout/inc-menu-only-dashboard'); ?>

    <div class="col-sm-9">         

    </div>

</div>

<!-- Start Jquery Model popup Window -->

<!-- Button trigger modal -->

<!-- Modal -->

<div class="modal fade" id="addProd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <?= form_open('wardrobe/ajax/wardrobe/addToCart', 'id="add-to-cart"'); ?>

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Add to cart</h4>

            </div>

            <div class="modal-body">

                <div class="leftsidebar col-sm-5">

                    <div class="popup-left-add-product-img-container">

                        <div class="left-add-product-img" >

                            <img src="" id="prodImage" class="img-responsive" />                      

                        </div>

                    </div>

                </div>

                <div class="rightsidebar col-sm-7">

                    <div class="popup-left-add-product-full-desc-container">

                        <div class="left-add-product-full-desc text-right">

                            <div class="left-add-product-tittle">

                                <h4 class="productName"></h4>

                                <div class="col-md-9 text-right">

                                    <h4>$ <span id="prodPrice">0.00</span></h4>

                                </div>

                                <div class="left-add-product-point-add col-md-3">

                                    <span class="badge pull-right" id="prodPoint">0</span>

                                    <div class="clearfix"></div>

                                </div>

                            </div>

                            <div class="clearfix"></div>



                            <div class="product-buy-limit-info-container" style="padding-bottom: 25px;">

                                <div class="clearfix"></div>

                                <hr class=" hrizental-line-0">

                                <div class="container-prod-set">

                                    <div class="product-management-container-2" >

                                        <ul class="list-unstyled product-manager-list" id="prodAttribute">

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="prod-cart-container">

                        <button type="submit" class="btn btn-orange btn-md"> <i class="fa fa-shopping-cart"></i>  Add </button>

                    </div>

                </div>

                <div class="clearfix"></div>

                <div class="product-buy-limit-info-container" style="padding-bottom: 25px;">

                    <div class="clearfix"></div>

                    <hr>

                    <div class="col-md-12">

                        <div class="left-add-product-abut-desc">

                            <h4>Product Description</h4>

                            <!--<p id="prodDesc"></p>-->

                            <ul class="list-unstyled">

                                <li id="prodDesc"></li>

                            </ul>

                        </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-12">

                        <div class="product-management-container-2" >

                            <ul class="list-unstyled product-manager-list  circle" id="prodAttribute"></ul>                        

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">                

            </div>

        </div>

    </div>

    <?= form_close(); ?>

</div>

<!-- End Jquery Model popup Window -->

<div class="clearfix"></div>

<div class="col-md-3">    

    <div class="left-wardrobe-compny-bulk-item-container">

        <div class="left-wardrobe-bulk-block">

            <div class="top-left-wardrobe-title padding-0">

                <h3 data-cid="0" onclick="getCatProduct(this);"> <i class="fa fa-list"></i>&nbsp; Product Categories </h3>

                <input type="hidden" value="" name="category" id="category">

            </div>

            <div class="top-left-wardrobe-cat-list">                

                <div id="accordion-first" class="clearfix">

                    <ul class="accordion wardrobe-cat-list wardrobe-category" id="accordion2">

                        <?php foreach ($categories as $catkey => $catvalue) { ?>

                            <li class="accordion-group <?= $catvalue['category_id'] == $category_dept ? 'comp_active_categ' : ''; ?>">

                                <div class="accordion-heading">

                                    <a style="padding-left:2px;" 

                                       class="accordion-toggle collapsed"

                                       data-cid="<?= $catvalue['category_id']; ?>"

                                       onclick="getCatProduct(this)" > 

                                        <i class="fa fa-angle-double-right"></i>

                                        &nbsp; <?= $cat_suffix . ' ' . $catvalue['category']; ?>

                                    </a>

                                </div>

                            </li>

                        <?php } ?>

                    </ul><!-- end accordion -->

                </div>

                <script>

                    function getCatProduct(srcElement) {

                        var sourceElem = $(srcElement);

                        var category = sourceElem.data('cid');

                        $('#category').val(category);

                        $.get('<?= base_url("wardrobe/ajax/wardrobe/getCompProd/"); ?>',

                                {'category': $('#category').val(),

                                    'prodName': $('#prodName').val(),

                                    'ajax': '1', 'offset': '0',

                                },

                                function (data) {

                                    data = JSON.parse(data);

                                    if (data.success) {

                                        $("#accordion2 > li").removeClass('comp_active_categ');

                                        $('#prod-list-table').html(data.html);

                                        if (category) {

                                            sourceElem.parent('div').parent('li').addClass('comp_active_categ');

                                        }

                                    }

                                }

                        );

                        return false;

                    }

                </script>

                <div class="clearfix"></div>

            </div>

        </div>

    </div>

</div>

<div class="col-md-9">

    <div class="row">

        <div class="col-md-6 ">

            <div class="invoice-top-title-section"> <h2 style="margin-bottom:0;"> <?= $cat_suffix; ?> Wardrobe </h2> </div>

        </div>

        <div class="col-md-6">   

            <div class="right-order-cart-block text-right">

                <!--                <h4> Your Cart </h4>                               -->

                <div class="dropdown-cart-container">

                    <div class="wardrobe-cart-block-count">

                        <ul class="nav navbar-nav navbar-right">

                            <li id="topbar-cart-container" class="dropdown">

                                <?= $mini_cart_view; ?>

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="clearfix"></div>

    <div class="row">

        <div class="col-lg-12">

            <div role="grid" class="dataTables_wrapper form-inline" id="table_wrapper">     

                <div class="clearfix"></div>

                <div class="warddrobe-full-container" style="margin-top:0;">

                    <div class="row">

                        <div class="col-xs-12" id="prod-list-table">

                            <?= $table_prod_view; ?>

                        </div>

                    </div>

                </div><!-- order-history-full-container -->    

            </div>

        </div>

    </div>

</div>

<script>

    $(".deleterow").on("click", function () {

        var killrow = $(this).parent('tr');

        killrow.addClass("danger");

        killrow.fadeOut(2000, function () {

            $(this).remove();

        });

    });

    function removeCartItem(rowId) {

        $.get('<?= base_url() . "cart/ajax/cart/removeCartItem/" ?>',

                {'rowId': rowId},

                function (data) {

                    data = JSON.parse(data);

                    getMiniCart();

                }

        );

    }



    function getMiniCart() {



        $.get("cart/ajax/cart/getWardrobeMiniCart", function (data) {

            data = jQuery.parseJSON(data);

            $("#cartTotal").html(data.cartTotal);

            $("#topbar-cart-container").html(data.wardrobecart);

        });

    }



    $('#department').on("change", function () {

        if ($(this).val() == "0" || $(this).val() == "") {

            $('#prod-list-table').html('<div class="col-md-12"><h4>Please Select Department </h4></div>');

            return false;

        }

        $.get('<?= base_url() . "wardrobe/ajax/wardrobe/getUserProd/" ?>',

                {'offset': '0',

                    'dept_id': $(this).val(),

                },

                function (data) {

                    data = JSON.parse(data);

                    if (data.success) {

                        $('#prod-list-table').html(data.html);

                    }

                }

        );

    });



    function getProdDetail(productRef) {

        $('.productName').text('');

        $('#prodImage').attr('src', '');

        $('#prodDesc').html('');

        $('#prodPrice').html('');

        $('#prodPoint').html('');

        $('#prodAttribute').html('');

        $.get('wardrobe/ajax/wardrobe/getProductViewForShop',

                {'product_ref': productRef,

                    'deptId': $('#department').val()

                },

                function (data) {

                    data = jQuery.parseJSON(data);

                    $('.productName').text(data.prodName);

                    $('#prodImage').attr('src', data.prodImage);

                    $('#prodDesc').html(data.prodDesc);

                    $('#prodPrice').html(data.prodPrice);

                    $('#prodPoint').html(data.prodPoint);

                    $('#prodAttribute').html(data.prodAttrHtml);

                }

        );

        $('#addProd').modal('show');

    }



    $("#add-to-cart").submit(function (event) {

        var form = $(this);

        var selectedQt = $('#quantity').val();

        if (selectedQt) {

            $.post(form.attr('action'),

                    {<?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>',

                        'form-data': $(this).serialize(),

                    }

            ).done(function (data) {

                data = jQuery.parseJSON(data);

                $("#cartTotal").html(data.cartTotal);

                $("#wardrobecart").html(data.wardrobecart);

                $('#addProd').modal('hide');

            });

            event.preventDefault();

            return false;

        } else {

            $('#comMsgModalTitle').html('Add cart');

            $('#comMsgModalBody').html("Sorry quantity cannot be 0");

            $('#comMsgModal').modal('show');

            return false;

        }

    });



    function placeOrder( ) {

        $.get("order/ajax/order/addOrder", function (data) {

            data = jQuery.parseJSON(data);

            $("#cartTotal").html("0.00");

            $("#wardrobecart").html("");

            $('#comMsgModalTitle').html('Add order');

            $('#comMsgModalBody').html(data.message);

            $('#comMsgModal').modal('show');

        });

    }



    $(document).ready(function () {

        getMiniCart();

    });

</script>







<!-- Bootstrap core JavaScript

    ================================================== -->

<!-- Placed at the end of the document so the pages load faster -->    

<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        $("#yt-totop").hide();

        $(function () {

            var wh = $(window).height();

            var whtml = $(document).height();

            $(window).scroll(function () {

                if ($(this).scrollTop() > whtml / 10) {

                    $('#yt-totop').fadeIn();

                } else {

                    $('#yt-totop').fadeOut();

                }

            });

            $('#yt-totop').click(function () {

                $('body,html').animate({

                    scrollTop: 0

                }, 800);

                return false;

            });

        });

    });

</script>

<!--

<script src="js/bootstrap.min.js"></script>

-->

<script type="text/javascript">

    $(document).ready(function () {

        $i = 0;

        $('#accordion-first #accordion2').append('<div class="more-wrap"><span class="more-view"> <i class="fa fa-plus-square-o"></i> More Categories  </span></div>');

        $('#accordion-first #accordion2 > li.accordion-group').each(function () {

            $i++;

            if ($i > 7) {

                $(this).css('display', 'none');

            }

        });

        $('#accordion-first #accordion2 .more-wrap > .more-view').click(function () {

            if ($(this).hasClass('open')) {

                $i = 0;

                $('#accordion-first #accordion2 > li.accordion-group').each(function () {

                    $i++;

                    if ($i > 7) {

                        $(this).slideUp(200);

                    }

                });

                $(this).removeClass('open');

                $('.more-wrap').removeClass('active-i');

                $(this).text('More Categories');

            } else

            {

                $i = 0;

                $('#accordion-first #accordion2 > li.accordion-group').each(function () {

                    $i++;

                    if ($i > 7) {

                        $(this).slideDown(200);

                    }

                });

                $(this).addClass('open');

                $('.more-wrap').addClass('active-i');

                $(this).text('Close Menu');

            }





        })

        var nhd2 = $('#accordion-first #accordion2 > li.accordion-group').length;

        if (nhd2 < 7) {



            $('#accordion-first .more-wrap').css('display', 'none');

        } else {



            $('#accordion-first .more-wrap').css('display', 'block');

        }

        // console.log($i);

    })

</script>

