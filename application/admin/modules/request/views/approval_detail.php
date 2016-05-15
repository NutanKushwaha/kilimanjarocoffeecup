<div class="invoice-top-title-section">
    <h2>Request detail</h2>
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
                <div class="col-sm-6 pad-left0">
                    <h3 style="margin: 10px 0 10px 0px;font-size: 18px; font-weight: 600; text-align: left; color: f27733;">
                    <?= $reqCommon['req_num']; ?></h3>
                </div>                
                <div class="col-sm-6 attr-set-list" style="text-align: right">
					<?php 
						if( !$req_processed ){
							$form_attr = [];
							$form_attr[ 'id' ] = 'approval-action';
							$form_attr[ 'name' ] = 'approval-action';
							echo form_open('request/approval_action/'.$reqCommon['req_num'],$form_attr  );
					?>
							<div class="col-md-8">
								<?php
									echo form_dropdown("approval" ,$reqActions, '5', ' class="form-control" ');
								?>
							</div>
							<div class="col-md-4">
								<?php
									echo form_submit( 'approval_action', 'Update', 'class="btn btn-primary"' );
								?>							
							</div>
					<?php 
						echo form_close();						
						} else {
							echo $status;
					} ?>
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
