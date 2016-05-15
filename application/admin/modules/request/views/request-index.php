<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui-timepicker-addon.js"></script>
<link href="js/jquery-datetimepicker/date-style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link href="css/datepicker.css" rel="stylesheet" type="text/css">
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
        font-size: 14px !important;
        padding-bottom: 15px !important;
        padding-top: 15px !important;
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
</style>
<div class="invoice-top-title-section">
    <h2>Stock requests</h2>
</div>
<div class="col-lg-12 padding-0" style="padding-top: 15px;" id="request-view-div">
    <?= $request_listing; ?>
</div>