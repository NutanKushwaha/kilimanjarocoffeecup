<?PHP

    $comp_color = com_get_theme_menu_color();

    $base_color = '#783914';

    $hover_color = '#d37602';

    if ($comp_color) {

        $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#783914');

        $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');

    }

?>



<style>

    /*    

        .btn-primary {

            background-color: #783914;

            border-color: #330000;

        }

        .btn-primary:hover, .btn-primary:active, .btn-primary.hover {

            background-color: #d37602;

            border-color: #b35600;

        }

    

    */

    

    .btn-danger {

        background-color: <?= $base_color; ?>;

        border-color: <?= $hover_color; ?>;

    }

    .btn-danger:hover, .btn-danger:active, .btn-danger.hover {

        background-color: <?= $hover_color; ?>;

        border-color: <?= $base_color; ?>;

    }

    .pagination > .active > a, .pagination > .active > span, 

    .pagination > .active > a:hover, .pagination > .active > span:hover, 

    .pagination > .active > a:focus, .pagination > .active > span:focus{

        background-color: <?= $base_color; ?> !important;

        border-color: <?= $hover_color; ?> !important;

    }

    #stock-request-table th{

        color: <?= $base_color; ?>;

    }

    

    .table.table-mystyled a{

        color: <?= $base_color; ?>;

    }



</style>







<style>

    .payment-paid-status-block .btn{

        padding:0;

    }

</style>

<div class="container-fluid">

    <div class="top-order-history-srh-btn text-right">

        <button class="btn btn-danger" onclick="letSearch()"> Search <i class="fa fa-search fa-sm"></i> </button>

    </div>

</div>

<div class="order-history-full-container">

    <?php

        $hidden = [ 'stock-submit' => 1];

        echo form_open( 'company/stock_request', 

                        ' id="company-stock-request" name="company-stock-request"', 

                        $hidden);

    ?>        

    <table id="stock-request-table" class="table-mystyled table table-bordered table-striped dataTable" 

           cellspacing="0" width="100%">

        <thead>

            <tr role="row">

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 70px;" >

                    Req No.

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 93px;" >

                    Purchased By

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 100px;" >

                    <div class="allign-center" style="width:100%; float:left"> Purchased Date </div> 

                    <div class="allign-center" style="width:40%; float:left"> From </div>

                        <div class="allign-center" style="width:20%; float:left">|</div>

                    <div class="allign-center" style="width:40%; float:left"> To </div>

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 90px;" >                    

                    <div class="allign-center" style="width:100%; float:left"> Quantity</div> 

                    <div class="allign-center" style="width:45%; float:left"> From </div>

                        <div class="allign-center" style="width:10%; float:left">|</div>

                    <div class="allign-center" style="width:40%; float:left"> To </div>

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 90px;" >                    

                    <div class="allign-center" style="width:100%; float:left"> Total</div> 

                    <div class="allign-center" style="width:45%; float:left"> From </div>

                        <div class="allign-center" style="width:10%; float:left">|</div>

                    <div class="allign-center" style="width:40%; float:left"> To </div>                    

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 80px;" >

                    Status

                </th>

                <th role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" style="width: 50px;" >

                    Action

                </th>

            </tr>

        </thead>

        <tbody role="alert" aria-live="polite" aria-relevant="all">

            <tr>

                <td>

                    <input  class="search" 

                            id="stock-order-num" 

                            name="stock-order-num" 

                            type="text" value="<?= com_arrIndex($form_data , 'stock-order-num', ''); ?>" 

                            class="input-sm" style="width:100%;" />

                </td>

                <td> 

                    <?php

                        echo    form_dropdown('stock-grp-holder', 

                                            $group_users, 

                                            com_arrIndex($form_data , 'stock-grp-holder', ''),

                                            ' class="search" id="stock-grp-holder" style="width:100%"'

                                );

                    ?>

                </td>

                <td align="center">

                    <div class="order-form-custom-fields" style="width:100%">                        

                        <div style="width: 50%;float:left">

                            <input type="text" class="search ddlselect" 

                                    id="stock-request-orderdate-from" name="stock-request-orderdate-from" 

                                    style="width: 100%;"

                                    value="<?= com_arrIndex($form_data , 'stock-request-orderdate-from', ''); ?>">

                        </div>                        

                        <div style="width: 50%;float:left">

                            <input  type="text" class="search ddlselect" 

                                    id="stock-request-orderdate-to" name="stock-request-orderdate-to" 

                                    style="width: 100%;"

                                    value="<?= com_arrIndex($form_data , 'stock-request-orderdate-to', ''); ?>"></div>

                    </div>

                    <div class="clearfix"></div>

                </td>                

                <td>

                    <div class="order-form-custom-fields" style="width:100%">

                        <div style="width: 50%;float:left">

                            <?php

                                echo form_dropdown( 'stock-request-orderqty-from', 

                                                    $srequest_qrange, com_arrIndex($form_data , 'stock-request-orderqty-from', ''),

                                                    'id="stock-request-orderqty-from" 

                                                    onchange="setOrderQtyTo()" style="width:100%"');

                            ?>

                        </div>                        

                        <div style="width: 50%;float:left">

                            <?php

                                echo form_dropdown( 'stock-request-orderqty-to', $srequest_qrange, com_arrIndex($form_data , 'stock-request-orderqty-to', ''), 

                                                    'id="stock-request-orderqty-to"  style="width:100%"');

                            ?>                                    

                        </div>

                    </div>

                    <div class="clearfix"></div>

                </td>                

                <td> 

                    <!-- <td><input class="search" id="search-order-total" name="search-order-total" type="text" class="input-sm"/></td> -->

                    <div class="order-form-custom-fields" style="width:100%">

                        <div style="width: 48%;float:left">

                            <?php

                                echo form_dropdown('stock-request-orderttl-from', 

                                                    $srequest_prange, 

                                                    com_arrIndex($form_data , 'stock-request-orderttl-from', ''), 

                                                    'id="stock-request-orderttl-from" style="width:100%" 

                                                    onchange="setOrderTotalTo()"');

                            ?>

                        </div>

                        <div style="width: 2%;float:left">

                        </div>                        

                        <div style="width: 48%;float:left">

                            <?php

                                echo form_dropdown('stock-request-orderttl-to', 

                                                    $srequest_prange, 

                                                    com_arrIndex($form_data , 'stock-request-orderttl-to', ''), 

                                                    'id="stock-request-orderttl-to" style="width:100%"');

                            ?>

                        </div>

                    </div>

                    <div class="clearfix"></div>

                </td>

                 <td> 

                    <?php

                        echo form_dropdown('stock-request-order-status', $srequest_status, com_arrIndex($form_data , 'stock-request-order-status', ''), 'id="stock-request-order-status" style="width:100%"');

                    ?>

                </td>

                <td style="min-width: 50px;" class="payment-paid-status-block">                    

                </td>

            </tr>            

            <?php

            echo form_close();

            foreach ($request_detail as $reqIndex => $reqDet) { ?>

                <tr class="<?= alternator('odd', 'even'); ?> ">

                    <td><?= $reqDet['req_num']; ?></td>                    

                    <td ><?= ucfirst( $reqDet['uacc_username'] ); ?></td>

                    <td align="center"><?= date('d-M-Y',strtotime($reqDet['req_date'])); ?></td> 

                    <td ><?= $reqDet['req_qty']; ?></td>

                    <td >$ <?= $reqDet['req_total']; ?></td>

                    <td ><?= $srequest_status[ $reqDet['status'] ]; ?></td>

                    <td class="">

                        <?php                             

                            $oUrl = 'company/request_view/';                            

                            echo anchor( $oUrl.$reqDet['req_num'], 'View');

                        ?>

                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>    

</div>

<div class="ajax-pagination" style="text-align:center;">

    <ul class="pagination">

         <?= $pagination;?> 

    </ul>

</div>

<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui.js"></script>

<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui-timepicker-addon.js"></script>

<link href="js/jquery-datetimepicker/date-style.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>

<link href="css/datepicker.css" rel="stylesheet" type="text/css">

<script>

    $(document).ready(function () {

        $('#stock-request-orderdate-from').datetimepicker({

            dateFormat: 'yy-mm-dd',

            onSelect: function(date) {

                 var maxDate = $('#stock-request-orderdate-from').datepicker('getDate');

                $("#stock-request-orderdate-to").datetimepicker("change", { minDate: maxDate });

            }

        });

        $('#stock-request-orderdate-to').datetimepicker({

            dateFormat: 'yy-mm-dd',

        });

        var startDate = new Date('<?= date("d/m/Y"); ?>');

        var FromEndDate = new Date();

        var ToEndDate = new Date();

        ToEndDate.setDate(ToEndDate.getDate()+365);

        $('#stock-request-orderdate-from').datepicker({

            weekStart: 1,

            startDate: '<?= date("d/m/Y"); ?>',

            endDate: FromEndDate, 

            autoclose: true

        })

        .on('changeDate', function(selected){

            startDate = new Date(selected.date.valueOf());

            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));

            $('#stock-request-orderdate-to').datepicker('setStartDate', startDate);

        }); 

        $('#stock-request-orderdate-to')

            .datepicker({                

                weekStart: 1,

                startDate: startDate,

                endDate: ToEndDate,

                autoclose: true

            })

            .on('changeDate', function(selected){

                FromEndDate = new Date(selected.date.valueOf());

                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));

                $('#stock-request-orderdate-from').datepicker('setEndDate', FromEndDate);

            });

        $('.ddlselect').on('keypress', function(e) {

            e.preventDefault();

            return false;

        });

    });

    function setOrderTotalTo( ){

        var rangeToDisable = parseInt( $('#stock-request-orderttl-from').val() );

        $('#stock-request-orderttl-to').val( 0 );

        var sOrderTotalTo = $('#stock-request-orderttl-to > option');

        sOrderTotalTo.prop('disabled', false);

        sOrderTotalTo.each(function(){

            if( parseInt($(this).val()) < rangeToDisable ) {

                $(this).prop('disabled', true);

            }

        });

    }



    function setOrderQtyTo( ){

        var rangeToDisable = parseInt( $('#stock-request-orderqty-from').val() );

        $('#stock-request-orderqty-to').val( 0 );

        var sOrderTotalTo = $('#stock-request-orderqty-to > option');

        sOrderTotalTo.prop('disabled', false);

        sOrderTotalTo.each(function(){

            if( parseInt($(this).val()) < rangeToDisable ) {

                $(this).prop('disabled', true);

            }

        });

    }



    function getGroupUser( ){

        var usersgroup = $('#stock-request-order-grp').val();

        $.get( "user/ajax/user/getGroupUsers", { group: usersgroup }, function( data ) {

            data = JSON.parse( data );

            $( "#stock-request-order-grp-holder" ).html( data.html );

        });

    }



    function letSearch(){

        var form = $('#company-stock-request');

        $.post( form.attr('action'),

                {   'form_data': form.serialize(),

                    '<?= $this->security->get_csrf_token_name(); ?>':

                    $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]' ).val(),                    

                },

                function(data){

                    data = JSON.parse(data);

                    if(data.success){                        

                        $('#request-view-div').html(data.html);

                    }

                }

            );

    }

</script>