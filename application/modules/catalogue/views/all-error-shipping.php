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
                    <h1>Select Shipping</h1>
                </div>
                <div class="bd-cart-full-container">
                    <strong><?php echo 'Sorry!! There is no valid shipping service avaialble at this shipping address. 
                                        Please select  another shipping address or contact Administrator.';
                     ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

