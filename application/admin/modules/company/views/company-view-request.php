<?PHP
$comp_color = com_get_theme_menu_color();
$base_color = '#783914';
$hover_color = '#d37602';
if ($comp_color) {
    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#f27733');
    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');
}
?>

<style>
    /*    
        
    */

    .btn-info {
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }

    .btn-info:hover, .btn-info:active, .btn-info.hover {
        background-color: <?= $hover_color; ?> !important;
        border-color: <?= $base_color; ?> !important    ;
    }
    .pagination > .active > a, .pagination > .active > span, 
    .pagination > .active > a:hover, .pagination > .active > span:hover, 
    .pagination > .active > a:focus, .pagination > .active > span:focus{
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }
    .col-sm-9.pad-left0 > h3{
        color: <?= $base_color; ?> !important;
    }
</style>



<div class="invoice-top-title-section">
        <h2>Stock request detail</h2>
    </div>
    <div class="quick-order-address-detail-container">
        <div class="row">
            <div class="col-sm-8 col-xs-12">
                <div class="left-quick-order-address-detail">
                    <ul class="list-unstyled text-left">
                        <li><span class="bold-text">Contact:</span>
                            <?= ucfirst( $customer_detail['upro_first_name'] ).' '.$customer_detail['upro_last_name']; ?></li>
                        <li><span class="bold-text">Request no:</span><?= $req_num; ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="right-quick-order-address-detail">                    
                    <div class="clearfix"></div>
                </div>                
            </div>
        </div>
    </div>


<style>
    .custom-border-table-container{
        background: none !important;
    }
    table.custom-border-table-style thead{
        background:#555;
        color:#fff;
    }
    table.custom-border-table-style thead th{
        background:#555;
        color:#fff;
    }
    table.custom-border-table-style th,table.custom-border-table-style td{
        border:1px solid #ddd !important;
    }
    .custom-border-table-container .rightbar-invoice-header-section{
        padding: 0 15px !important;
    }
    .quick-order-address-detail-container{
        padding-bottom: 0px !important;
    }
    .bold-text{
        font-weight: 600 !important;
    }
    .quick-order-address-detail-container ul li{
       
    }
    .right-quick-order-address-detail ul {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-bottom: 1px solid #d0d0d0;
        border-image: none;
        border-left: 1px solid #d0d0d0;
        border-radius: 5px;
        border-right: 1px solid #d0d0d0;
        padding-bottom: 10px;
    }
    .head-title-name {
        background: #6a6a6a none repeat scroll 0 0;
        border-radius: 4px 4px 0 0 !important;
        color: #fff !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        margin-bottom: 5px;
        padding: 6px 10px !important;
        text-align: right;
        text-transform: uppercase;
    }
    .quick-order-address-detail-container ul li {
        border-bottom: 1px solid #ddd;
        border-radius: 0;
        color: #555;
        font-size: 13px;
        font-weight: normal;
        line-height: 20px;
        padding: 0 10px 3px;
    }
    .quick-order-address-detail-container ul li:last-child {
        border-bottom: none;
    }
    .left-quick-order-address-detail ul li{
        border:none;
    }
</style>

    <div class="rightbar-invoice-full-section custom-border-table-container">
        <header class="panel-heading rightbar-invoice-header-section">
            <div class="row">                        
                <div class="col-sm-9 pad-left0">
                    <h3 style="margin: 10px 0 10px 0px;font-size: 18px; font-weight: 600; text-align: left; color: f27733;"><?= $req_num; ?></h3>
                </div>
                <div class="col-sm-3" style="text-align: right">
                    <?php                         
                        if( $processedStatus ){
                            $stock_status = $processedStatus;
                        } else {
                            
							$actions = '';
							foreach ($company_stores as $store_index => $store_det) {
								$actions .= '<li style="width:90%">
												<a 	style="width:100%"
													class="btn btn btn-default pull-left" 
													href="company/stock_request_issue/'.$req_num.'/'.$store_det[ 'id' ].'" >
													Store-'.ucfirst($store_det[ 'store_name' ]).'</a>
											</li>';
							}
                            $stock_status = '<div class="dropdown">
												<button class="btn btn-info dropdown-toggle" 
														style="background-color:#F27733;"
														type="button" data-toggle="dropdown"> Issue stock
												<span class="caret"></span></button>
												<ul class="dropdown-menu">
												'.$actions.'
												</ul>
											</div>';
                        }
                        echo $stock_status;
                    ?>
                </div>
            </div>
        </header>
        <div class="rightbar-invoice-info-container">    
            <div class="table-responsive text-center">
                <table id="pagination-table" class="table table-bordered table-striped custom-border-table-style">
                    <thead>
                        <tr>
                            <th width="50">S.no</th>
                            <th class="leftalign">Code</th>
                            <th width="150">Product Image</th>
                            <th class="leftalign">Product Name</th>
                            <th class="leftalign">Qty.</th>
                            <th class="leftalign">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php                        
                        com_get_product_image('default_product.jpg', 50, 50);
                        foreach ($reqDetail as $reqIndex => $reqDet ) {
                            $sub_options = json_decode( $reqDet['req_item_options'] , true);                            
                            $product_image = com_arrIndex($sub_options[ 'product' ], 'product_imgae', '' );
                    ?>
                        <tr>
                            <td><?= ($reqIndex + 1); ?></td>
                            <td class="leftalign"><?= $reqDet['product_ref']; ?></td>
                            <td><img src="<?= com_get_product_image( $product_image, 50, 50); ?>" height="50px"/> </td>
                            <td class="leftalign"><?= $reqDet['req_item_name']; ?></td>
                            <td class="leftalign"><?= $reqDet['req_item_qty']; ?></td>
                            <td class="leftalign">&#163; <?= $reqDet['req_item_price']; ?></td>
                        </tr>
                    <?php            
                        }
                    ?>
                    </tbody>                    
                </table>
                <div class="clearfix"></div>        
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
