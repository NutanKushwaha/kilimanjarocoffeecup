

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

    /*    .btn-warning {

            background-color: #f0ad4e;

            border-color: #eea236;

        }

        .btn-warning:hover, .btn-warning:focus, .btn-warning.focus, 

        .btn-warning:active, .btn-warning.active, .open > 

        .dropdown-toggle.btn-warning{

            background-color: #ec971f;

            border-color: #d58512;

        }

    */



    .btn-warning {

        background-color: <?= $base_color; ?>;

        border-color: <?= $hover_color; ?>;

    }

    .btn-warning:hover, .btn-warning:focus, .btn-warning.focus, 

    .btn-warning:active, .btn-warning.active, .open > 

    .dropdown-toggle.btn-warning{

        background-color: <?= $hover_color; ?>;

        border-color: <?= $base_color; ?>;

    }



    .btn-primary {

        background-color: <?= $base_color; ?>;

        border-color: <?= $hover_color; ?>;

    }



    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {

        background-color: <?= $hover_color; ?>;

        border-color: <?= $base_color; ?>;

    }



    .nHideConfAttr {

        width: 100%;

    }

    .hideConfAttr {

        height: 30px;

        padding: 0;

        width: 100%;

    }



</style>









<style>

    .wardrob-prodt-old-price{

        color: #555;

    }

    .wardrob-prodt-special-price{

        color: rgb(255, 0, 0);

        font-size: 22px;

    }

    .wardrob-prodt-origianl-price {

        font-size: 15px;

        padding-left: 5px;

        text-decoration: line-through;

    }

    .availability{

        font-weight: 600;

    }

    .in-stock span a{

        color:#00B050;

        font-weight: 100;

    }

    .prodt-qty-count-center {

        width: 16%;

    }

    .prodt-qty-count-center .input-group-btn .btn, .input-group-btn .btn-group {

        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;

        border: 1px solid rgb(142, 142, 142);

        box-shadow: none;

        color: rgb(0, 0, 0);

        margin-right: -1px;

        padding: 0;

    }

</style>

<div id="yt_content" class="yt-content wrap">

    <div class="yt-content-inner">

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <div id="yt_pathway" class="clearfix">

                        <div class="pathway-inner">

                            <!--<h4></h4>-->

                            <ul class="breadcrumbs">

                                <div class="breadcrumbs-content">

                                    <li class="home" itemscope="" itemtype="">

                                        <a itemprop="url"

                                           onclick="$('#goBackShop').submit();"

                                           title="Go Back" class="btn btn-warning btn-sm button">

                                            <span itemprop="title" class="button">Back</span>

                                        </a>                                        

                                    </li>

                                    <li class="product last" itemscope="" itemtype="">

                                        <?= ucfirst($detail['product_name']); ?>

                                    </li>

                                </div>

                            </ul>

                        </div>

                    </div>

                </div>                

                <!-- /* Start middle container area */ -->

                <div class="col-md-5">

                    <div class="product-full-img-container">

                        <div class="prodt-full-desc-slider-container">

                            <!-- Place somewhere in the <body> of your page -->

                            <div id="slider" class="flexslider">

                                <ul class="slides">

                                    <?php

                                    echo '  <li>

                                                <img src="' . $product_image_url . '"

                                            </li>';

                                    /*  disabled for future purpose increase images li if gallery is available

                                      <li> <img src="../imgs/slide1.jpg" /> </li>

                                      <!-- items mirrored twice, total of 12 -->

                                     */

                                    ?>

                                </ul>

                            </div>

                            <?php /*

                              disabled for future purpose increase images li if gallery is available

                              <div id="carousel" class="flexslider">

                              <ul class="slides">

                              <li>

                              <img src="../imgs/slide1.jpg" />

                              </li>

                              <!-- items mirrored twice, total of 12 -->

                              </ul>

                              </div> */ ?>

                        </div>

                    </div>

                </div>

                <div class="col-md-7">

                    <div class="right-wardrob-prodt-shop-container">

                        <div class="wardrob-prodt-name">

                            <h1> <?= ucfirst($detail['product_name']); ?> </h1>

                            <small><span><?= $detail['product_sku']; ?></span></small>

                        </div>

                        <div class="wardrob-prodt-price-box">

                            <div class="wardrob-prodt-special-price">

                                <!--<span class="price-label"></span>-->

                                <div class="wardrob-prodt-special-price">

                                    <div class="wardrob-prodt-price">$ <?= $detail['product_price']; ?></div>

                                    <div class="clearfix"></div>

                                </div>

                            </div>

                            <div class="clearfix"></div>

                            <?php

                            /*  <span class="wardrob-prodt-old-price"> <!--<span class="price-label"></span>--> <span class="wardrob-prodt-origianl-price"> $135.00 </span> </span> */

                            ?>

                        </div>

                        <?php

                        /* <div class="product-in-out-stock-container">

                          <p class="availability in-stock">Availability: <span> <a href="javascript:void();" >  <i class="fa fa-check"></i> In stock</a>  </span> </p>

                          </div>

                         */

                        ?>

                        <div class="prodt-count-container">

                            <?php

                            $hidden = [];

                            $hidden['category_dept'] = $category_dept;

                            $hidden['product_id'] = $detail['product_id'];

                            $hidden['product_sku'] = $detail['product_sku'];

                            $hidden['product_name'] = $product_main_details['product_name'];

                            echo form_open(base_url('wardrobe/addToCart'), ' id="compAddCart" name="compAddCart" ', $hidden

                            );

                            ?>

                            <div class="col-md-3">

                                <div class="input-group">

                                    <div class="input-group-btn">

                                        <button type="button" class="btn btn-danger btn-number"  

                                                data-type="minus" data-field="qty">

                                            <span class="glyphicon glyphicon-minus"></span>

                                        </button>

                                    </div>

                                    <input  type="number" name="qty" id="qty"

                                            class="form-control input-number" 

                                            value="1" min="1" max="10000">

                                    <div class="input-group-btn">

                                        <button type="button" class="btn btn-success btn-number" 

                                                data-type="plus" data-field="qty">

                                            <span class="glyphicon glyphicon-plus"></span>

                                        </button>

                                    </div>

                                </div>

                            </div>                            

                            <div class="col-md-9">

                                <div class="input-group" style="margin-top:10px;">

                                    <a onclick="$('#compAddCart').submit();" id="addCart" class="btn btn-primary">

                                        <span class="glyphicon glyphicon-shopping-cart"></span>

                                        Add to cart

                                    </a>

                                </div>

                            </div>

                            <div class="col-md-12">

                                <small>Max limit: 10000</small>

                            </div>

                            <div class="clearfix"></div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="short-description">

                            <div class="col-md-6">

                                <h2>Product Attributes</h2>

                                <table class="table">

                                    <thead>

                                        <tr>

                                            <th class="col-md-3">Attribute</th>

                                            <th class="col-md-3">Value</th>                                  

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        if (isset($product_attr_details[$detail['product_id']]['prdAttrLblOpts'])) {

                                            $ttlDdl = 0;

                                            $attrOptHtml = '';

                                            $fConfigAttr = '';

                                            if (isset($product_attr_details[$product_main_details['product_id']]

                                                            ['attrbSetattrConf'][0])) {

                                                $fConfigAttr = $product_attr_details[$product_main_details['product_id']]

                                                        ['attrbSetattrConf'][0];

                                            }

                                            foreach ($product_attr_details[$detail['product_id']]['prdAttrLblOpts']

                                            as $prodRelkey => $prodAttr) {

                                                foreach ($prodAttr as $attrKey => $attrDet) {

                                                    if ($isSimple) {

                                                        $attrOptHtml .= '<tr>

                                                                            <td>' . ucfirst($attrDet['label']) . '</td>

                                                                            <td>' . $attrDet['attrOpts'] . '</td>

                                                                        </tr>';



                                                        $attrInput = [];

                                                        $attrInput['name'] = 'attribute[' . $attrKey . ']';

                                                        $attrInput['value'] = $attrDet['attrOpts'];

                                                        $attrInput['type'] = 'hidden';

                                                        echo form_input($attrInput);

                                                    } else {

                                                        $next_attr = '0';

                                                        $isReq = $product_attr_details[$product_main_details['product_id']]

                                                                ['attrbSetattr'][$attrKey]['required'] ? 'required' : 'required';

                                                        if (isset($product_attr_details[$product_main_details['product_id']]

                                                                        ['configOptionComb'][$attrKey])) {

                                                            $next_attr = $product_attr_details[$product_main_details['product_id']]

                                                                    ['configOptionComb'][$attrKey]['next-attr'];

                                                        }

                                                        $confHideClass = 'hideConfAttr';

                                                        if ($fConfigAttr == $attrKey) {

                                                            $confHideClass = 'nHideConfAttr';

                                                            $lblAttr = $attrDet['attrOpts']['0'];

                                                        } else {

                                                            $attrDet['attrOpts'] = [];

                                                            $attrDet['attrOpts'][''] = 'Select ' . ucfirst($attrDet['label']);

                                                            $lblAttr = 'Select ' . ucfirst($attrDet['label']);

                                                        }



                                                        $attrOptHtml[$attrKey] = '<tr>

                                                                            <td>' . ucfirst($attrDet['label']) . ($isReq ? '<span class="text-danger">*</span>' : '') . '</td>

                                                                            <td>' . form_dropdown(

                                                                        'attribute[' . $attrKey . ']', $attrDet['attrOpts'], '', " 

                                                                                            id='attr-$attrKey' 

                                                                                            class='$confHideClass'

                                                                                            data-nattr='$next_attr'

                                                                                            data-lblatr='$lblAttr' 

                                                                                            $isReq

                                                                                            data-aorder='***'

                                                                                            "

                                                                )

                                                                . '</td>

                                                                        </tr>';

                                                        $ttlDdl++;

                                                    }

                                                }

                                            }

                                            if (!$isSimple) {

                                                $confAttrHtml = '';

                                                foreach ($product_attr_details[$product_main_details['product_id']]['attrbSetattrConf']

                                                as $attrIndex => $attrVal) {

                                                    $confAttrHtml .= isset($attrOptHtml[$attrVal]) ? $attrOptHtml[$attrVal] : '';



                                                    $confAttrHtml = str_replace("data-aorder='***'", "data-aorder='$attrIndex'", $confAttrHtml);

                                                }

                                                $attrOptHtml = $confAttrHtml;

                                            }

                                            echo $attrOptHtml;

                                        }

                                        ?>                                                                                                  

                                    </tbody>

                                </table>

                            </div>

                            <div class="col-md-6">

                            </div>

                            <?= form_close(); ?>

                        </div>                        

                        <div class="clearfix"></div>                    

                        <div class="short-description">

                            <h2>Product Description</h2>

                            <p><?= $detail['product_description']; ?></p>

                        </div>

                    </div>

                </div>

                <!-- */ End middle container area */ -->            

            </div> <!-- end row -->

            <?php

            /*

              Description disabled

              <div class="row">

              <div class="col-md-12">

              <div>

              <!-- Nav tabs -->

              <ul class="nav nav-tabs" role="tablist">

              <li role="presentation" class="active"><a href="#desc" aria-controls="desc" role="tab" data-toggle="tab"> Description </a></li>

              <li role="presentation"><a href="#additional" aria-controls="additional" role="tab" data-toggle="tab"> Additional </a></li>

              </ul>

              <!-- Tab panes -->

              <div class="tab-content">

              <div role="tabpanel" class="tab-pane active" id="desc">

              <h3>

              Product Description

              </h3>

              <div class="shop-item-description">

              <p>The Uniform Wares 203 Series is an updated version of the popular 200 Series watch by London brand Uniform Wares. This classic, elegant watch houses a refined mechanism and features updated design details that set this version apart from its 202 Series predecessor. The timepiece maintains the references to engineering gauges and industrial equipment that inspired the original design of the watch. The 40mm face now features raised hour markers and a larger date window positioned at 3 o’clock. The mechanism within the watch has an updated, more powerful, Swiss-made ETA F05.111 3 jewel movement. The Uniform Wares 203 Series is available in three colours with varying textures and finishes.</p>

              <p>This colourway in the Uniform Wares 203 Series collection has a much brighter finish than the other two in the series. With a blue leather strap and a polished finish on the case, this watch stands out from the range. It has a lighter satin brushed stainless steel face with a bright blue ticker to match the strap. All colourways have a 40mm case diameter and 235mm long calf leather strap. The 203 Series is a unisex watch.</p>

              <div class="shop-item-description">

              <p>The Uniform Wares 203 Series is an updated version of the popular 200 Series watch by London brand Uniform Wares. This classic, elegant watch houses a refined mechanism and features updated design details that set this version apart from its 202 Series predecessor. The timepiece maintains the references to engineering gauges and industrial equipment that inspired the original design of the watch. The 40mm face now features raised hour markers and a larger date window positioned at 3 o’clock. The mechanism within the watch has an updated, more powerful, Swiss-made ETA F05.111 3 jewel movement. The Uniform Wares 203 Series is available in three colours with varying textures and finishes.</p>

              <p>This colourway in the Uniform Wares 203 Series collection has a much brighter finish than the other two in the series. With a blue leather strap and a polished finish on the case, this watch stands out from the range. It has a lighter satin brushed stainless steel face with a bright blue ticker to match the strap. All colourways have a 40mm case diameter and 235mm long calf leather strap. The 203 Series is a unisex watch.</p>

              </div>

              </div>

              </div>

              <div role="tabpanel" class="tab-pane" id="additional">

              <h3> Additional </h3>

              <div class="shop-item-description">

              <div class="addit-text-desc">

              <p>Lorem ipsum dolor sit amet, an munere tibique consequat mel, congue albucius no qui, at everti meliore erroribus sea. Vero graeco cotidieque ea duo, in eirmod insolens interpretaris nam. Pro at nostrud percipit definitiones, eu tale porro cum. Sea ne accusata voluptatibus. Ne cum falli dolor voluptua, duo ei sonet choro facilisis, labores officiis torquatos cum ei.</p>

              <p>&nbsp;</p>

              <p>Cum altera mandamus in, mea verear disputationi et. Vel regione discere ut, legere expetenda ut eos. In nam nibh invenire similique. Atqui mollis ea his, ius graecis accommodare te. No eam tota nostrum cotidieque. Est cu nibh clita. Sed an nominavi maiestatis, et duo corrumpit constituto, duo id rebum lucilius. Te eam iisque deseruisse, ipsum euismod his at. Eu putent habemus voluptua sit, sit cu rationibus scripserit, modus voluptaria ex per. Aeque dicam consulatu eu his, probatus neglegentur disputationi sit et. Ei nec ludus epicuri petentium, vis appetere maluisset ad. Et hinc exerci utinam cum. Sonet saperet nominavi est at, vel eu sumo tritani. Cum ex minim legere.</p>

              <p>&nbsp;</p>

              <p>Eos cu utroque inermis invenire, eu pri alterum antiopam. Nisl erroribus definitiones nec an, ne mutat scripserit est. Eros veri ad pri. An soleat maluisset per. Has eu idque similique, et blandit scriptorem necessitatibus mea. Vis quaeque ocurreret ea.</p>    </div>

              </div>

              </div>

              </div>

              </div>

              </div>

              </div>

              <!-- Hot categories home page v2 -->

             */

            ?>

        </div>

    </div>

    <!-- END: content -->

    <?php

    $hidden = array();

    $hidden['offset'] = $offset;

    $hidden['category_dept'] = $category_dept;

    echo form_open(base_url('wardrobe'), ' id="goBackShop" name="goBackShop" ', $hidden);

    echo form_close();

    ?>    

</div>

<div id="bpopup" style="display:none"></div>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

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

                    $("#bpopup").bPopup().html(data.message);

                    $('.dropdown-cart').html(data.html)

                    $('.item-count').html(data.itemcount);



                    //  location.reload();

                });

                event.preventDefault();

                return false;

            } else {

                $('#comMsgModalTitle').html('Quantity');

                $('#comMsgModalBody').html('Sorry quantity cannot be 0');

                $('#comMsgModal').modal('show');

                return false;

            }

        });

    })

</script>

<!-- Place somewhere in the <head> of your document -->

<link rel="stylesheet" href="css/flexslider/flexslider.css" type="text/css">

<script src="css/flexslider/jquery.flexslider.js"></script>

<script>

    $(window).load(function () {

        // The slider being synced must be initialized first

        $('#carousel').flexslider({

            animation: "slide",

            controlNav: false,

            directionNav: false,

            animationLoop: false,

            slideshow: false,

            itemWidth: 100,

            itemMargin: 5,

            move: 0,

            asNavFor: '#slider'

        });



        $('#slider').flexslider({

            animation: "slide",

            controlNav: false,

            directionNav: false,

            animationLoop: false,

            slideshow: false,

            sync: "#carousel"

        });

    });



</script>

<script>

    //plugin bootstrap minus and plus

    $('.btn-number').click(function (e) {

        e.preventDefault();



        fieldName = $(this).attr('data-field');

        type = $(this).attr('data-type');

        var input = $("input[name='" + fieldName + "']");

        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {

            if (type == 'minus') {



                if (currentVal > input.attr('min')) {

                    input.val(currentVal - 1).change();

                }

                if (parseInt(input.val()) == input.attr('min')) {

                    $(this).attr('disabled', true);

                }



            } else if (type == 'plus') {



                if (currentVal < input.attr('max')) {

                    input.val(currentVal + 1).change();

                }

                if (parseInt(input.val()) == input.attr('max')) {

                    $(this).attr('disabled', true);

                }



            }

        } else {

            input.val(0);

        }

    });

    $('.input-number').focusin(function () {

        $(this).data('oldValue', $(this).val());

    });

    $('.input-number').change(function () {



        minValue = parseInt($(this).attr('min'));

        maxValue = parseInt($(this).attr('max'));

        valueCurrent = parseInt($(this).val());



        name = $(this).attr('name');

        if (valueCurrent >= minValue) {

            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')

        } else {

            $('#comMsgModalTitle').html('Min value');

            $('#comMsgModalBody').html('Sorry, the minimum value has reached');

            $('#comMsgModal').modal('show');

            $(this).val($(this).data('oldValue'));

        }

        if (valueCurrent <= maxValue) {

            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')

        } else {

            $('#comMsgModalTitle').html('Max value');

            $('#comMsgModalBody').html('Sorry, the maximum value has reached');

            $('#comMsgModal').modal('show');

            $(this).val($(this).data('oldValue'));

        }





    });

    $(".input-number").keydown(function (e) {

        // Allow: backspace, delete, tab, escape, enter and .

        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||

                // Allow: Ctrl+A

                        (e.keyCode == 65 && e.ctrlKey === true) ||

                        // Allow: home, end, left, right

                                (e.keyCode >= 35 && e.keyCode <= 39)) {

                    // let it happen, don't do anything

                    return;

                }

                // Ensure that it is a number and stop the keypress

                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {

                    e.preventDefault();

                }

            });



    $(document).ready(function () {

        $('#compAddCart').submit(function () {

            var valid = "";

            var cns = $('select[required]');

            $.each(cns, function (key, value) {

                if ($(this).val() == '0')

                {

                    valid += $('#' + $(this).attr('id')).parent().prev().text() + "<br>";

                }

            })

            if (valid != "") {

                $('#msg-pop-model-title').html('Field Required');

                $('#msg-pop-model-msg').html(valid);

                $('#msg-pop-model').modal('show');

                return false;

            }

            valid = "";

        });



        $('.hideConfAttr > option').hide();

        $('.nHideConfAttr , .hideConfAttr').on('change', function () {

            var currAttr = $(this).attr('id').replace('attr-', '');

            var nextAttr = $(this).data('nattr');

            var nextAttrId = '#attr-' + nextAttr;

            var currOrder = parseInt($(this).data('aorder'));

            var thsValue = $(this).val();

            var selOptTxt = $(this).find('option:selected').text();

            var lblAttr = $(this).data('lblatr');

            var ttlAttr = <?= $ttlDdl; ?>;



            /* if( thsValue == "" || thsValue == undefined || thsValue == null ||  thsValue == 0 ) { */

            if (lblAttr == selOptTxt) {

                makeHide(currOrder, ttlAttr);

            } else if (nextAttr) {

                /*  Make all below hide*/

                makeHide(currOrder, ttlAttr);

                $.get('wardrobe/ajax/wardrobe/confProdAttr/',

                        {

                            'form_data': $("form").serialize(),

                            'next_attr': nextAttr,

                        },

                        function (data) {

                            data = JSON.parse(data);

                            if (data.success) {

                                $('#attr-' + nextAttr).html(data.html);

                            }

                        });

                $('#attr-' + nextAttr + ' option[value="0"]').show();

            }

        })

    });



    function makeHide(orderStart, ttlCount) {

        for (var i = (orderStart + 1); i < ttlCount; i++)

        {

            $('select[data-aorder="' + i + '"] option[value!=""]').remove();

        }

    }

</script>

