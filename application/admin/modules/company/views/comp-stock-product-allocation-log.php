
    <div class="invoice-top-title-section">
        <h2>Product stock issue log</h2>
    </div>
    <div class="product-log-detail-container">
        <div class="row">
            <div class="col-sm-8 col-xs-12">
                <div class="left-product-log-address-detail">
                    <h4><?= $product_det[ 'product_name' ] ?></h4> 
                    <small><?= $product_det[ 'product_sku' ] ?></small>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="right-quick-order-address-detail">
                    <?php
                        $default_prod_image = com_get_product_image('default_product.jpg', 250, 250);
                        $productImage = $product_det[ 'product_image' ];
                        $params = [
                            'image_url' => $this->config->item('PRODUCT_IMAGE_URL').$productImage,
                            'image_path' => $this->config->item('PRODUCT_IMAGE_PATH').$productImage,
                            'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),
                            'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),
                            'width' => 250,
                            'height' => 250,
                            'default_picture' => $default_prod_image
                        ];
                        $image_url = resize( $params );             
                        echo '<img  src="'.$image_url.'" 
                                    alt="Bodyguard" 
                                    class="img-responsive"/>';
                    ?>                    
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
                <div class="col-sm-12 pad-left0">                    
                </div>                
            </div>
        </header>
        <div class="rightbar-invoice-info-container">
            <div class="table-responsive text-center" style="overflow-x:initial;">                
                <table id="pagination-table" class="table table-bordered table-striped custom-border-table-style">
                    <thead>
                        <tr>
                            <th width="50">S.no</th>
                            <th class="leftalign">Reference</th>
                            <th class="leftalign">Req. by</th>
                            <th class="leftalign">Req. qty</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php   
                        foreach ($logDet as $logSindex => $logDet ) {
                            $stock_allocation_reference = "";
                            if( $logDet[ 'bonus_given' ] ){
                                $stock_allocation_reference = 'Bonus issued on '.
                                                            date("d/m/Y" , strtotime( $logDet[ 'issue_date_time' ] ));
                            } else {
                                $anchorAttr = [];
                                $anchorAttr[ 'title' ] = 'View request';
                                $stock_allocation_reference = anchor('company/request_view/'.$logDet['request_ref'],
                                                                    $logDet['request_ref'] , $anchorAttr);
                            }
                    ?>
                        <tr>
                            <td><?= ($logSindex + 1); ?></td>
                            <td class="leftalign">
                                <?= $stock_allocation_reference; ?>                                
                            </td>
                            <td class="leftalign"><?= $logDet['uacc_username']; ?></td>                            
                            <td class="leftalign"><?= $logDet['stock_qty']; ?></td>
                        </tr>
                    <?php            
                        }
                    ?>
                    </tbody>
                    <tfoot>
                        <td colspan="6">
                        </td>                        
                    </tfoot>
                </table>                
                <div class="clearfix"></div>        
            </div>
        </div>
    </div>
    <div class="clearfix"></div>