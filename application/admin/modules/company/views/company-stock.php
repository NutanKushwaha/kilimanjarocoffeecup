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
    .btn-primary {
        background-color: <?= $base_color; ?>;
        border-color: <?= $hover_color; ?>;
    }
	
    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: <?= $hover_color; ?>;
        border-color: <?= $hover_color; ?>;
    }
    
    
</style>


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
<!--
	Top Heading
-->
<div class="col-lg-12 ">
	<?= $this->load->view(THEME . 'layout/inc-menu-only-dashboard');  ?>
	<div class="col-sm-11 invoice-top-title-section"  style="text-align:center;">
		<h3>Company Stock <small>(<?= $company_detail['name']; ?>)</small></h3>
	</div>
</div>
<div class="clearfix"></div>
<!--
	End Heading
-->    
<div class="col-lg-12">
	<?php 		
		if (!$company_store_stock) { ?>
		<div class="bs-example">
			<div class="alert alert-danger fade in">        
				<strong>No stock is available.</strong><br/>            
			</div>
		</div>
	<?php
		} else {
			com_get_product_image('default_product.jpg', 50, 50);
			$store_ids = [];
			$store_ids[] = 0;
			$sCounts = sizeof($company_stores);
			$pxSize = 980;
			if ($sCounts > 3) {
				$pxSize += ( $sCounts - 3) * 180;
			}
			//com_e( $company_store_stock, 0);
			//com_e( $company_stores, 0);
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
<!--
            background: rgb(255, 255, 255) none repeat scroll 0 0;
-->
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
            width: 180px;
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
            width: 250px;
        }

        .top-text-head {
            font-weight: 600;
            margin-bottom: 3px;
            padding-left: 4px;
        }
    </style>
    <div class="container-full">
        <div class="outer-container row-first">
            <div class="inner-container">
                <!-- Top container-->
                <div class="header-title-container">
                    <div class="container-row">
                        <div class="left">  
                            <ul class="left-ul-list">
                                <li> Products</li>
                            </ul>
                        </div>
                        <div class="right">
                            <div class="header-top">
								<ul class="right-ul-list" >
									<li>
										<div class="top-text-head-text">
											<div class="top-text-head">
												Home
											</div>
										</div>
									</li>
                                <?php
                                /* Check is products exist */
                                if ($company_stores) {
                                    /* Loop on products */
                                    echo '';
                                    foreach ($company_stores as $stockK => $stockV) {
										$store_ids[] = $stockV[ 'id' ];
                                        ?>
                                        <li>
                                            <div class="top-text-head-text">
                                                <div class="top-text-head">
													<?= ellipsize($stockV['store_name'], 20, .5); ?>
                                                </div>                                                
                                            </div>
                                        </li>
                                    <?php
                                    } /* End for each loop */                                    
                                }                                
                                echo '</ul>';
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
                if ($all_product_detail) {
                    foreach ($all_product_detail as $ssK => $ssV) {
						$currrent_product = com_arrIndex($company_store_stock, $ssV['product_sku'], []);
						$isStockAssignExist = com_arrIndex($company_store_user_issued_stock, $ssV['product_sku'], 0);
						$issuedStock = [];
						if( $isStockAssignExist ){
							$issuedStock = $isStockAssignExist;
						}

						$isStockCarryFwdExist = com_arrIndex($company_store_carry_forward, $ssV['product_sku'], 0);
						$cFwdStock = [];
						if( $isStockCarryFwdExist ){
							$cFwdStock = $isStockCarryFwdExist;
						}
						
					?>
					<div class="container-row">
						<div class="left">
							<ul class="left-ul-list">
								<li>
									<div style="width:100%; margin:0px 0 0px 0">										
										<div style="width:60%;float:left;">
											<?= $ssV['product_sku']; ?><br/>
											<?= $ssV['product_name']; ?>											
										</div>
										<div style="width:40%;float:left;">
											<img src="<?= com_get_product_image( $ssV['product_image'], 50, 50); ?>" height="50px"/>
										</div>										
									</div>
								</li>
							</ul>
                            </div>
                            <div class="right">
                                <?php
                                $company_store_product_debit = [];
                                $company_store_product_credit = [];                                
								if( isset( $company_stores_stock_exchange[ $ssV['product_sku'].":0" ] ) ){
									$company_store_product_credit = $company_stores_stock_exchange[ $ssV['product_sku'].":0" ];
								}
								if( isset( $company_stores_stock_exchange[ $ssV['product_sku'].":1" ] ) ){
									$company_store_product_debit  = $company_stores_stock_exchange[ $ssV['product_sku'].":1" ];
								}
                                $stock_list = '<ul class="right-ul-list">';
                                /* first check is stores exist */
                                if ($store_ids) {
                                    /* Loop on every stores */
                                    foreach ($store_ids as $stK => $stId) {
										$store_stock_exchange = 0;
										$store_debit_qty = com_arrIndex($company_store_product_debit, 'store_'.$stId, 0);
										$store_credit_qty = com_arrIndex($company_store_product_credit, 'store_'.$stId, 0);
										$store_stock_exchange = $store_credit_qty - $store_debit_qty;
										$sum_store_stock = com_arrIndex($currrent_product, 'store_'.$stId, 0 );
										$carry_fwd_stock = com_arrIndex($cFwdStock, 'store_'.$stId, 0 );
										$issued_store_stock = com_arrIndex($issuedStock, 'store_'.$stId, 0 );
										
										$simple = 'D-'.$store_debit_qty.'C-'.$store_credit_qty.'Ex-'.$store_stock_exchange.
												'S-'.$sum_store_stock.'Cf-'.$carry_fwd_stock.'Iss-'.$issued_store_stock;										
										$store_stock = $sum_store_stock + $carry_fwd_stock- $issued_store_stock
														+ $store_stock_exchange;
									//<a style="margin-bottom:2px;" type="button" class="btn btn-info btn-xs"> Ledger</a>
										$stock_list .= '<li>
															<div style="width:100%;margin:0px 0 0px 0">
																<div style="width:50%;float:left;text-align:center;">	
																	' .$store_stock. '
																</div>
																<div style="width:50%;float:left;" class="attr-set-list">
																
																'. ( $store_stock
																	? '<button 	onClick="stock_exchange('.$stId.',\''.$ssV['product_sku'].'\');" 
																				type="button" class="exchange_stock btn btn-info btn-xs"> 
																		Exchange</button>'
																	: '<button type="button" disabled class="btn btn-danger btn-xs"> Exchange</button>'
																	).
																'
																</div>
															</div>
														</li>';
                                    }
                                }
								$stock_list .= '</ul>';
                                echo $stock_list;
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
	<?php	
		}
	?>
</div>
<script type="text/javascript">	
	function stock_exchange(store, product_code){
		$.post( "company/store_stock_product_exchange", { store: store, product_code: product_code })
		  .done(function( data ) {
				data = jQuery.parseJSON( data );
				$('#msg-pop-model-title').html('Store product stock exchange');
				$('#msg-pop-model-msg').html( data.html );
				$('#msg-pop-model').modal('show');
		  });
	}
</script>
