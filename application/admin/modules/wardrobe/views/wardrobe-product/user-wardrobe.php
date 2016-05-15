<?php

    $prodInpt = [                    

                    'id'    => 'product_ref',

                    'name'  => 'product_ref',

                    'val'   => '',

                    'type'  => 'hidden',

                ];

    $deptInpt = [ 

                    'type'  => 'hidden',

                    'id'    => 'deptId',

                    'name'  => 'deptId',

                    'val'   => '',

                ];

    $offset = [ 

                    'type'  => 'hidden',

                    'id'    => 'offset',

                    'name'  => 'offset',

                    'val'   => '0',

                ];                

    echo    form_open('wardrobe/cuser_view_product', 'id="user-view-prod" name="user-view-prod"').

            form_input($offset).

            form_input($prodInpt).

            form_input($deptInpt).

            form_close();



?>







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





<div class="col-md-6 ">

    <div class="invoice-top-title-section"> <h2> <?= $cat_suffix; ?> Wardrobe </h2> </div>

    <div class="manage-ward-product-container">

            <div class="active-point-count">

                <a href="#">Available Point <span class="badge">0</span></a><br>

            </div>   

    </div>

    <div id="table_length" class="dataTables_length warddrobe-product-select-section">

        <?php

            $attr = ' size="1" aria-controls="table" id="department" ';

            echo form_dropdown('department', $user_dept, $selected_dept, $attr);

        ?>        

    </div>    

</div>

<div style="padding: 0;" class="col-md-6">        

  <div class="warddrobe-product-detail-container"> 

    <!-- MINI CART START -->

        <div class="warddrobe-cart-table-inner">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th class="col-md-6"> Name </th>                        

                        <th class=" text-left col-md-2"> Quantity </th>

                        <th class="col-md-3"> Amount </th>                        

                    </tr>

                </thead>

                <tbody id="wardrobecart">

                </tbody>

                <tfoot class="cart-footer-total-section">

                    <tr class="border-none">

                        <td>   </td>

                        <td>   </td>

                        <td>   </td>

                        <td class="col-md-2"><h5 class="mar-0 pad-top5"> <strong></strong></h5></td>

                        <td class="text-right col-md-2"><p class="mar-0 pad-top5"></p></td>

                    </tr>

                    <tr class="border-none mar-left15">

                        <td class="col-md-7">

                            <button onclick="placeRequest();" type="button" class="btn btn-orange">

                                <i class="fa fa-shopping-basket"></i>Place Request

                            </button>

                        </td>

                        <td></td>

                        <td></td>

                        <td class="col-md-2"><h5 class="mar-0 pad-top5"><strong>Total </strong></h5></td>

                        <td class="col-md-3"><h5 class="mar-0 pad-top5"><strong> $<span id="cartTotal">0.00</span></strong></h5></td>

                    </tr>                    

                </tfoot>                   

            </table>

        </div>

    <!-- MINI CART END -->

        <div class="clearfix"></div>

    </div>    

</div>

<div class="clearfix"></div>

<div style="padding-top: 15px;" class="col-lg-12 padding-0">

    <div role="grid" class="dataTables_wrapper form-inline" id="table_wrapper">     

        <div class="clearfix"></div>

        <div class="warddrobe-full-container">

            <div class="row">

                <div class="col-xs-12" id="prod-list-table">

                    <?=  $table_prod_view; ?>

                </div>

            </div>

        </div><!-- order-history-full-container -->    

    </div>

</div>



<script>

    $(".deleterow").on("click", function(){

            var killrow = $(this).parent('tr');

                killrow.addClass("danger");

                killrow.fadeOut(2000, function(){

                $(this).remove();

            });

    });

    function removeCartItem( rowId ){

        $.get( '<?= base_url()."cart/ajax/cart/removeCartItem/" ?>',

                {   'rowId': rowId },

                function( data ){

                    data = JSON.parse(data);                    

                    getMiniCart(  );

                }

            );

    }



    function getMiniCart(){

        $.get( "cart/ajax/cart/getWardrobeMiniCart", function( data ) {

            data = jQuery.parseJSON( data );

            $("#cartTotal").html( data.cartTotal );

            $("#wardrobecart").html( data.wardrobecart );            

        });

    }



    $('#department').on("change", function(){

        if( $(this).val() == "0" || $(this).val() == ""){

            $('#prod-list-table').html('<div class="col-md-12"><h4>Please Select Department </h4></div>');

            return false;

        }

        $.get( '<?= base_url()."wardrobe/ajax/wardrobe/getUserProd/" ?>',

                {   'offset':'0',

                    'dept_id':$(this).val(),

                },

                function(data){

                    data = JSON.parse(data);

                    if(data.success){

                        $('#prod-list-table').html(data.html);

                    }

                }

            );

    });



    function getProdDetail( productRef, offSet ){

        $( '#product_ref' ).val( productRef );

        $( '#deptId' ).val( $('#department').val() );

        $( '#offset' ).val( offSet );

        $( '#user-view-prod' ).submit();

    }



    $( "#add-to-cart" ).submit(function(event) {

        var form = $( this );

        var selectedQt = $('#quantity').val();        

        if( selectedQt > 0 ) {        

            $.post( form.attr('action'), 

                    {   <?= $this->security->get_csrf_token_name(); ?> : '<?= $this->security->get_csrf_hash(); ?>', 

                        'form-data': $( this ).serialize(),

                    }

                ).done(function( data ) {

                    data = jQuery.parseJSON( data );

                    $("#cartTotal").html( data.cartTotal );

                    $("#wardrobecart").html( data.wardrobecart );

                    $('#addProd').modal('hide'); 

                });

            event.preventDefault();            

        }else{

            $('#addProd').modal('hide');

            $('#comMsgModalTitle').html('Quantity');

            $('#comMsgModalBody').html("Sorry quantity cannot be 0");

            $('#comMsgModal').modal('show');

        }

        return false;

    });



    function placeRequest( ){

        $.get( "request/ajax/request/addRequest", function( data ) {

                data = jQuery.parseJSON( data );                

                $("#cartTotal").html( "0.00" );

                $("#wardrobecart").html( "" );

                $('#comMsgModalTitle').html('Order');

                $('#comMsgModalBody').html( data.message );

                $('#comMsgModal').modal('show');

        });

    }



    $(document).ready( function (){

        getMiniCart();

    });

</script>

