<?PHP

$comp_color = com_get_theme_menu_color();

$base_color = '#783914';

$hover_color = '#d37602';

if ($comp_color) {

    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#F27733');

    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');

}

?>



<style>

    /*    

    .table.table-mystyled a{

        border: 1px solid;

        color: rgb(242, 119, 51);

        font-size: 13px;

        font-weight: 100;

        margin: 0 2px;

        padding: 1px 6px;

    }

    */



    .table.table-mystyled a {

/*        background-color: <?= $base_color; ?> !important;*/

        border-color: <?= $base_color; ?> !important;

        color:<?= $base_color; ?> !important;

    }

    .table.table-mystyled a:hover {

/*        background-color: <?= $hover_color; ?> !important;*/

        border-color: <?= $hover_color; ?> !important;

        color: <?= $hover_color; ?> !important;

    }



</style>









<style>

    .payment-paid-status-block .btn{

        padding:0; 



    }

    #table_wrapper > div {

        overflow: hidden;

        width: 100%;

    }

    .order-history-full-container {

        margin-bottom: 20px;

        margin-top: 20px;

        overflow-y: auto;

        overflow-x: none;

        width: auto;

    }

</style>

<div class="order-history-top-srch-block">

    <div class="top-order-history-srh-btn text-right">

        <button class="btn btn-default" onclick="letSearch()"> <i class="fa fa-search fa-sm"></i> Search  </button>

    </div>

</div>

<div class="order-history-full-container">

    <?php

    $hidden = [ 'order-search-submit' => 1];

    echo form_open('order/ajax/order/history', ' id="order-search-for"', $hidden);

    ?>        

    <table id="order-table" class="table-mystyled table table-bordered table-striped dataTable" 

           cellspacing="0" width="100%">

        <thead>

            <tr role="row">

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 70px;" >

                    Order No.

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    Group

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    Ordered By

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    Ordered Date

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    Order Quantity

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    Order Total

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    Status

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    Action

                </th>

            </tr>

        </thead>

        <tbody role="alert" aria-live="polite" aria-relevant="all" id="order-result-view-content">

            <tr>

                <td>

                    <input  class="search" id="search-order-num" name="search-order-num" 

                            type="text" value="<?= com_arrIndex($form_data, 'search-order-num', ''); ?>" class="input-sm" style="width:100%;" />

                </td>

                <td>

                    <?php

                    echo form_dropdown('search-order-grp', $groups, com_arrIndex($form_data, 'search-order-grp', ''), 'class="search" id="search-order-grp" onchange="getGroupUser()"');

                    ?>

                </td>

                <td> 

                    <?php

                    echo form_dropdown('search-order-grp-holder', $group_users, com_arrIndex($form_data, 'search-order-grp-holder', ''), ' class="search" id="search-order-grp-holder"');

                    echo '<br/><div><small>Select group first</small></div>';

                    ?>

                </td>

                <td>

                    <div class="order-form-custom-fields" style="">

                        <div style="width: 25%;float:left">

                            From:

                        </div>

                        <div style="width: 75%;float:left">

                            <input type="text" class="search ddlselect" 

                                   id="search-orderdate-from" name="search-orderdate-from" 

                                   style="width: 100%;"

                                   value="<?= com_arrIndex($form_data, 'search-orderdate-from', ''); ?>">

                        </div>

                        <div style="width: 25%;float:left">To:  </div>

                        <div style="width: 75%;float:left">

                            <input  type="text" class="search ddlselect" 

                                    id="search-orderdate-to" name="search-orderdate-to" 

                                    style="width: 100%;"

                                    value="<?= com_arrIndex($form_data, 'search-orderdate-to', ''); ?>"></div>

                    </div>

                    <div class="clearfix"></div>

                </td>                

                <td>

                    <div class="order-form-custom-fields" style="">

                        <div style="width: 40%;float:left">

                            From:

                        </div>

                        <div style="width: 60%;float:left">

<?php

echo form_dropdown('search-orderqty-from', $orders_qrange, com_arrIndex($form_data, 'search-orderqty-from', ''), 'id="search-orderqty-from" 

                                                    onchange="setOrderQtyTo()" style="width:100%"');

?>

                        </div>

                        <div style="width: 40%;float:left">

                            To:

                        </div>

                        <div style="width: 60%;float:left">                            

<?php

echo form_dropdown('search-orderqty-to', $orders_qrange, com_arrIndex($form_data, 'search-orderqty-to', ''), 'id="search-orderqty-to"  style="width:100%"');

?>                                    

                        </div>

                    </div>

                    <div class="clearfix"></div>

                </td>                

                <td> 

                    <!-- <td><input class="search" id="search-order-total" name="search-order-total" type="text" class="input-sm"/></td> -->

                    <div class="order-form-custom-fields" style="">

                        <div style="width: 30%;float:left"> From: </div>

                        <div style="width: 70%;float:left">

<?php

echo form_dropdown('search-orderttl-from', $orders_prange, com_arrIndex($form_data, 'search-orderttl-from', ''), 'id="search-orderttl-from" style="width:100%" onchange="setOrderTotalTo()"');

?>

                        </div>

                        <div style="width: 30%;float:left"> To: </div>

                        <div style="width: 70%;float:left">

                            <?php

                            echo form_dropdown('search-orderttl-to', $orders_prange, com_arrIndex($form_data, 'search-orderttl-to', ''), 'id="search-orderttl-to" style="width:100%"');

                            ?>

                        </div>                        

                    </div>

                    <div class="clearfix"></div>

                </td>                

                <td> 

<?php

echo form_dropdown('search-order-status', $orders_status, com_arrIndex($form_data, 'search-order-status', ''), 'id="search-order-status"');

?>

                </td>

                <td style="min-width: 100px;" class="payment-paid-status-block">

                    <?php

                    if ($user_type == ADMIN) {

                        $stock_order = com_arrIndex($form_data, 'stock_order', FALSE);

                        ?>

                        <label for="paid" >

                            <input  type="checkbox"

                        <?php

                        $is_paid = com_arrIndex($form_data, 'paid', FALSE);

                        echo ($is_paid === FALSE ? '' : 'checked');

                        ?>                                    

                                    name="paid" id="paid" value="1" class="badgebox"  autocomplete="off"> Paid

                        </label><br/>

                        <label for="stock_order" >

                            <input  type="checkbox" 

    <?php

    $stock_order = com_arrIndex($form_data, 'stock_order', FALSE);

    echo ($stock_order === FALSE ? '' : 'checked');

    ?>

                                    name="stock_order" 

                                    value="1" id="stock_order" class="badgebox" autocomplete="off"> Stock Order</label>

                        <?php } ?>

                </td>

            </tr>            

                        <?php

                        echo form_close();

                        foreach ($orders as $ordersIndex => $orderDet) {

                            ?>

                <tr class="<?= alternator('odd', 'even'); ?> ">

                    <td><?= $orderDet['order_num']; ?></td>

                    <td><?= $orderDet['ugrp_name']; ?></td>

                    <td ><?= ucfirst($orderDet['uacc_username']); ?></td>

                    <td ><?= date('d-M-Y', strtotime($orderDet['order_date'])); ?></td> 

                    <td ><?= $orderDet['order_qty']; ?></td>

                    <td >$ <?= $orderDet['order_total']; ?></td>

                    <td ><?= $orderDet['status']; ?></td>

                    <td class="order-hist-view-more-btn">

    <?php

    $oUrl = 'order/view/';

    if ($orderDet['is_stock_order'] == 2) {

        $oUrl = 'order/issued/';

    }

    echo anchor($oUrl . $orderDet['order_num'], 'View');

    ?>

                    </td>

                </tr>

                    <?php } ?>

        </tbody>

    </table>    

</div>

<div class="ajax-pagination" style="text-align:center;">

    <ul class="pagination">

<?= $pagination; ?> 

    </ul>

</div>

<script>

    $(document).ready(function () {

        $('#search-orderdate-from').datetimepicker({

            dateFormat: 'yy-mm-dd',

            onSelect: function (date) {

                var maxDate = $('#search-orderdate-from').datepicker('getDate');

                $("#search-orderdate-to").datetimepicker("change", {minDate: maxDate});

            }

        });

        $('#search-orderdate-to').datetimepicker({

            dateFormat: 'yy-mm-dd',

        });

        var startDate = new Date('<?= date("d/m/Y"); ?>');

        var FromEndDate = new Date();

        var ToEndDate = new Date();

        ToEndDate.setDate(ToEndDate.getDate() + 365);

        $('#search-orderdate-from').datepicker({

            weekStart: 1,

            startDate: '<?= date("d/m/Y"); ?>',

            endDate: FromEndDate,

            autoclose: true

        })

                .on('changeDate', function (selected) {

                    startDate = new Date(selected.date.valueOf());

                    startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));

                    $('#search-orderdate-to').datepicker('setStartDate', startDate);

                });

        $('#search-orderdate-to')

                .datepicker({

                    weekStart: 1,

                    startDate: startDate,

                    endDate: ToEndDate,

                    autoclose: true

                })

                .on('changeDate', function (selected) {

                    FromEndDate = new Date(selected.date.valueOf());

                    FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));

                    $('#search-orderdate-from').datepicker('setEndDate', FromEndDate);

                });

        $('.ddlselect').on('keypress', function (e) {

            e.preventDefault();

            return false;

        });

    });

    function setOrderTotalTo( ) {

        var rangeToDisable = parseInt($('#search-orderttl-from').val());

        $('#search-orderttl-to').val(0);

        var sOrderTotalTo = $('#search-orderttl-to > option');

        sOrderTotalTo.prop('disabled', false);

        sOrderTotalTo.each(function () {

            if (parseInt($(this).val()) < rangeToDisable) {

                $(this).prop('disabled', true);

            }

        });

    }



    function setOrderQtyTo( ) {

        var rangeToDisable = parseInt($('#search-orderqty-from').val());

        $('#search-orderqty-to').val(0);

        var sOrderTotalTo = $('#search-orderqty-to > option');

        sOrderTotalTo.prop('disabled', false);

        sOrderTotalTo.each(function () {

            if (parseInt($(this).val()) < rangeToDisable) {

                $(this).prop('disabled', true);

            }

        });

    }



    function getGroupUser( ) {

        var usersgroup = $('#search-order-grp').val();

        $.get("user/ajax/user/getGroupUsers", {group: usersgroup}, function (data) {

            data = JSON.parse(data);

            $("#search-order-grp-holder").html(data.html);

        });

    }



    function letSearch() {

        var form = $('#order-search-for');

        $.post(form.attr('action'),

                {'form_data': form.serialize(),

                    '<?= $this->security->get_csrf_token_name(); ?>': $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val(),

                },

                function (data) {

                    data = JSON.parse(data);

                    if (data.success) {

                        $('#order-view-div').html(data.html);

                    }

                }

        );

    }

</script>

