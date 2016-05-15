<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui-timepicker-addon.js"></script>
<link href="js/jquery-datetimepicker/date-style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link href="css/datepicker.css" rel="stylesheet" type="text/css">
<?PHP
    $comp_color = com_get_theme_menu_color();
    $base_color = '#F27733';
    $hover_color = '#C24703';
    if( $comp_color ){
        $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#F27733');
        $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#C24703');
    }
    
?>
<style>
    .table-mystyled th{
        font-size: 14px;
        font-weight: 600;
    }
    .table-mystyled td{
        font-size: 13px;
    }
    .table.table-mystyled a{
        border: 1px solid;
        color: rgb(242, 119, 51);
        font-size: 13px;
        font-weight: 100;
        margin: 0 2px;
        padding: 1px 6px;
    }
</style>
<style>
    .order-history-full-container .table-mystyled th{
        color: #404040 !important;
        font-size: 12px !important;
        padding-bottom: 9px !important;
        padding-top: 5px !important;
        text-transform: capitalize !important;
    }
    #table,.table-mystyled th,.table-mystyled td{
        border: 1px solid #dedede !important;
    }
    .order-form-custom-fields input {
        margin-bottom: 5px;
        padding: 0;
    }
    .top-order-history-srh-btn input, button{
        box-shadow: none;
    }
    #order-table tbody tr td{
        font-size: 12px;
    }
    #order-table tbody tr td select{
        font-size: 11px;
        height: 25px;
        margin-bottom: 5px;
        padding-bottom: 0;
        padding-top: 0;
        width: 100%;
    }
    .order-form-custom-fields > div {
        font-size: 11px;
        font-weight: 600;
    }
    .order-history-full-container .table-mystyled th {
/*        background: rgb(242, 119, 51) none repeat scroll 0 0 !important;
        border: 1px solid #d25713 !important;*/
        background: <?= $base_color; ?> !important;
        border: 1px solid <?= $hover_color; ?> !important;
        
        border-bottom: none !important;
        border-radius: 0 !important;
        color: #fff !important;
        font-size: 12px !important;
        padding-bottom: 9px !important;
        padding-top: 5px !important;
        text-transform: capitalize !important;
    }
    
</style>
<div class="col-lg-12 ">
    <?= $this->load->view(THEME . 'layout/inc-menu-only-dashboard');  ?>
    <div class="col-sm-9">         
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-12">
   <div class="invoice-top-title-section" style="padding: 0;margin-bottom:0">
       <div class="row">
           <div class="col-md-9">
                <h3 class="mar-bot0"> Order History </h3>
           </div>
           <div class="col-md-3"></div>
        </div>
    </div>
    <div class="col-lg-12 padding-0">
        <div role="grid" class="dataTables_wrapper form-inline" id="table_wrapper">
            <div  id="order-view-div" >
                <?php echo $orders_history_html; ?>
            </div>
        </div>
    </div>
</div>
