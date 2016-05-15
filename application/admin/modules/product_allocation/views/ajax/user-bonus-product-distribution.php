<?php 
echo form_hidden($hidden_qty);
if (!$products) { ?>
    <div class="bs-example">
        <div class="alert alert-danger fade in">        
            <strong>No product is assign to the department.</strong><br/>
            If you want to assign product now please <strong><a href="company/department/assignProduct/<?= $dept_id; ?>">click here</a></strong>.
        </div>
    </div>
<?php
} else {
    $pCounts = sizeof($products);
    $pxSize = 980;
    if ($pCounts > 3) {
        $pxSize += ( $pCounts - 3) * 200;
    }
    ?>
    <style>
        .container-full {
            overflow-y: scroll;
            width: auto;
        }
        .inner-container {
            border: 1px solid rgb(187, 187, 187);
            width: <?= $pxSize; ?>px;
        }    
        .container-row{
            background: rgb(238, 238, 238) none repeat scroll 0 0;
            border-bottom: 1px solid rgb(187, 187, 187);
            margin-bottom: 1px;

        }
        .right{
            background: rgb(255, 255, 255) none repeat scroll 0 0;
            border-left: 1px solid rgb(202, 202, 202);
            float: left;
        }
        .left {
            background: rgb(238, 238, 238) none repeat scroll 0 0;
            float: left;
            padding: 0 10px;
        }
        ul{
            list-style: none;
            margin: 0;
            padding: 0;
        }


        .right-ul-list{
            display: inline-block;
        }
        .right-ul-list > li {
            border-left: 1px solid rgb(238, 238, 238);
            float: left;
            padding: 5px 10px;
            width: 200px;
        }
        .block-first-list {
            float: left;
            margin: 0 3px;
            width: auto;
        }
        .block-first-list:nth-child(3n+4) {

        }
        .header-top{
            background: #eee;
        }
        .left-ul-list > li {
            font-size: 13px;
            font-weight: 900;
            padding: 5px;
            text-transform: capitalize;
            width: 110px;
        }

        .top-text-head {
            font-weight: 600;
            margin-bottom: 3px;
            padding-left: 4px;
        }
    </style>
    <?php    
    /*
        echo form_hidden($hidden_days);
        echo form_hidden($hidden_prods);
    */
    ?>
    <div class="container-full">
        <div class="outer-container row-first">
            <div class="inner-container">
                <!-- Top container-->
                <div class="header-title-container">
                    <div class="container-row">
                        <div class="left">  
                            <ul class="left-ul-list">
                                <li> Company User</li>
                            </ul>
                        </div>
                        <div class="right">
                            <div class="header-top">
                                <?php
                                /* Check is products exist */
                                if ($products) {
                                    /* Loop on products */
                                    echo '<ul class="right-ul-list" >';
                                    foreach ($products as $prodK => $prodV) {
                                        ?>
                                        <li>
                                        <?php /* Product Name */ ?>
                                            <div class="top-text-head-text">
                                                <div class="top-text-head">
                                                    <?= ellipsize($prodV['product_name'], 20, .5); ?>
                                                </div>
                                                <div class="top-text-head" style="width:100%">
                                                    <?php
                                                    $default_to_show = 0;
                                                    $available_stock_limit = 0;
                                                    if(  isset( $company_product_stock[ $prodV['product_sku'] ] ) ){
                                                            $issued_stock = com_arrIndex($company_issued_stock, $prodV['product_sku'], 0);
                                                            $available_stock_limit = $company_product_stock[ $prodV['product_sku'] ] - 
                                                                                        $issued_stock;
                                                            if( $available_stock_limit > 0){
                                                                $default_to_show = 1;
                                                            } else {
                                                                $available_stock_limit = 0;
                                                            }
                                                    }
                                                    $stock_label = [
                                                        'style' => 'width:50%',
                                                    ];
                                                    echo form_label('Stock ('.$available_stock_limit.')', 'prod-quantity-'.$prodV['product_id'], $stock_label);
                                                    $row_qty = array(
                                                        'id'    => 'prod-quantity-'.$prodV['product_id'],
                                                        'name'  => 'prod-quantity',
                                                        'value' => $default_to_show,
                                                        'type' => 'number',
                                                        'style' => 'width:50%',
                                                        'class' => 'userQty allownumericwithoutdecimal',
                                                        'data-id' => 'userQty' . $prodV['product_id'],
                                                        'data-stock-id' => '#prod-quantity-' . $prodV['product_id'],
                                                        'data-stock' => $available_stock_limit,
                                                    );
                                                    echo form_input($row_qty);
                                                    ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                    } /* End for each loop */
                                    echo '</ul>';
                                } else {
                                    echo '<ul class="right-ul-list"><li>No produc assigned to this department</li></ul>';
                                }
                                ?>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div> 
                </div>
                <!-- Lower container-->            
                <?php
                /* Check is user exist */
                if ($users) {
                    /* Loop on users */
                    foreach ($users as $usersK => $usersV) {
                        $user_id = $usersV['uacc_id'];
                        ?>
                        <div class="container-row">
                            <div class="left">
                                <ul class="left-ul-list">
                                    <li><?= $usersV['uacc_username']; ?></li>
                                </ul>
                            </div>
                            <div class="right">
                                <?php
                                echo '<ul class="right-ul-list">';
                                /* first check is products exist */
                                if ($products) {
                                    /* Loop on every product */
                                    foreach ($products as $prodK => $prodV) {
                                        $dayName = 'days[' . $dept_id . '][' . $prodV['product_sku'] . '][' . $user_id . ']';
                                        $qtyName = 'quantity[' . $dept_id . '][' . $prodV['product_sku'] . '][' . $user_id . ']';

                                        $userQtyDefVal = 0;
                                        if (isset($hidden_qty[$qtyName])) {
                                            $userQtyDefVal = $hidden_qty[$qtyName];
                                        }

                                        $row_qty = array(
                                            'name' => $qtyName,
                                            'value' => $userQtyDefVal,
                                            'type' => 'number',
                                            'style' => 'width:100%',
                                            'class' => 'userPerQty allownumericwithoutdecimal userQty' . $prodV['product_id'],
                                            'data-stock-id' => '#prod-quantity-' . $prodV['product_id'],
                                        );
                                        $policy_status_opt = [
                                                            '0' => 'No',
                                                            '1' => 'Yes',
                                                        ];                                        
                                        $userProductPolicyHtml = '  <div style="width:100%">
                                                                    ' . form_input($row_qty) . '
                                                                    </div>';
                                        echo '<li>' . $userProductPolicyHtml . '</li>';
                                    }
                                } else {
                                    echo '<li>No produc assigned to this department</li>';
                                }
                                echo '</ul>';
                                ?>                    
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        /* Loop End here */
                    }
                    /* IF end */
                }
                ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.userPerQty').on('change', function (event){                
                var currentObj = $(this);
                var currentVal = parseInt( currentObj.val() );
                var prodStock  = $(this).data( 'stock-id' );                
                var stock_qty  = parseInt( $( prodStock ).data( 'stock' ) );
                var relatedQty = '.' + $( prodStock ).data('id');
                if( currentVal > stock_qty ){
                    $('#msg-pop-model-title').html('Bonus product distribution');
                    $('#msg-pop-model-msg').html('Sorry, you cannot exceed stock quantity.');
                    $('#msg-pop-model').modal('show');
                    currentObj.val(0);
                    return false;
                }
                var allocated = 0;
                $(  relatedQty ).not(this).each(function( index ) {
                    allocated = allocated + Number( $(this).val() );
                });
                if( allocated < stock_qty ){
                    if( allocated + currentVal <=  stock_qty ){
                        currentObj.val( currentVal );
                    } else {
                        $('#msg-pop-model-title').html('Bonus product distribution');
                        $('#msg-pop-model-msg').html('Sorry, you cannot exceed stock quantity.');
                        $('#msg-pop-model').modal('show');
                        currentObj.val(0);
                        return false;                        
                    }
                } else {
                        $('#msg-pop-model-title').html('Bonus product distribution');
                        $('#msg-pop-model-msg').html('Sorry, you cannot exceed stock quantity.');
                        $('#msg-pop-model').modal('show');
                        currentObj.val(0);
                        return false;                        
                }
            });

            $('.userQty').on('change', function (event) {
                var prodStock  = $(this).data( 'stock-id' );
                var currentObj  = $( prodStock );
                var currentVal  = parseInt( currentObj.val() );
                var stock_qty   = parseInt( currentObj.data('stock') );
                if ( currentVal < 0) {
                    currentObj.val(0);
                    return false;
                }                
                if( currentVal > stock_qty ){
                    $('#msg-pop-model-title').html('Bonus product distribution');
                    $('#msg-pop-model-msg').html('Sorry, you cannot exceed stock quantity.');
                    $('#msg-pop-model').modal('show');
                    currentObj.val(0);                    
                }
                var relatedQty = '.' + currentObj.data('id');
                var allocated = 0;
                var per_person = currentVal;
                $('#distribute').prop('disabled', true);
                $(  relatedQty ).each(function( index ) {
                    if( allocated < stock_qty  ){                        
                        if( (allocated + per_person) <= stock_qty ){
                            $( this ).val( per_person );
                            allocated = allocated + per_person;
                        } else {
                            var leftStock =stock_qty - allocated;                            
                            if( leftStock > 0){
                                allocated = allocated + leftStock;
                                $( this ).val( leftStock );
                            } else {
                                $( this ).val( 0 );
                            }
                        }
                    } else {
                        $( this ).val( 0 );
                    }
                });
                $('#distribute').prop('disabled', false);
                event.preventDefault();                
            });
            $(".allownumericwithoutdecimal").on("keypress keyup blur change",function (event) {
                        $(this).val($(this).val().replace(/[^\d].+/, ""));
                        if ((event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }            
                    });            
        });
    </script>
<?php
}
