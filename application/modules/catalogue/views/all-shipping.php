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
                    <form action="<?php echo createUrl('catalogue/cart/shippingSetUp') ?>" method="post" enctype="multipart/form-data">
                        <div class="bd-cart-inner-container">
                            <div class="row">
                                
                                    <div class="col-md-12">
                                        <div class="bd-cart-top-header">
                                            <div class="col-md-5">
                                                <strong> Service Type </strong>
                                            </div>
                                            <div class="col-md-2">
                                                <strong> Service Charges </strong>
                                            </div>
                                            <div class="col-md-2">
                                                <strong> Transit Time </strong>
                                            </div>
                                            <div class="col-md-2">
                                                <strong> Max Transit Time </strong>
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>        
                                    <form name="myform" action="catalogue/cart/shippingSetUp" method="POST">                        
                                    <?php
                                    foreach ($all_shipping as $key => $rateReply) {
                                        ?>
                                        <div class="clearfix"></div>
                                        <!-- (content area) -->

                                        <div class="col-md-12">
                                            <div class="bd-cart-top-content tabl-row even">
                                                <div class="col-md-2">
                                                    <p>   <input type="radio" name="service_type_<?php echo $key;?>" value="<?php echo $rateReply -> ServiceType;?>"> <br>
                                                     </p>
                                                </div>
                                                <div class="col-md-3">
                                                    <p>   <?php echo $rateReply -> ServiceType; ?>
                                                     </p>
                                                </div>
                                                <div class="col-md-2">
                                                    <p>
                                                        <?php
															if($rateReply->RatedShipmentDetails && is_array($rateReply->RatedShipmentDetails))
															{
																echo  '$' .(number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",")) ;
														?>
														<input type="hidden" name="service_shipping_<?php echo $key;?>" value="<?php echo  number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",") ;?>">
														<?php	}
															elseif($rateReply->RatedShipmentDetails && ! is_array($rateReply->RatedShipmentDetails))
															{
																echo  '$'.(number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",")) ;
																?>
																<input type="hidden" name="service_shipping_<?php echo $key;?>" value="<?php echo  number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",")  ;?>">
														<?php	} // endif
															
                                                        ?>
                                                
                                                    </p>
                                                </div>
                                                <div class="col-md-2">
														<?php 

														if(array_key_exists('TransitTime',$rateReply->CommitDetails))
															{
																echo  $rateReply->CommitDetails->TransitTime ;
															}
															elseif(array_key_exists('DeliveryTimestamp',$rateReply))
															{
																$rateReply->DeliveryTimestamp ;
															}
															else
															{
																echo 'N/A' ;
															} //

														?>


                                                    
                                                </div>
                                                <div class="col-md-2">
                                                    <p> 
									                	<?php 
																if(array_key_exists('MaximumTransitTime',$rateReply->CommitDetails))
																{
																	echo $rateReply->CommitDetails->MaximumTransitTime ;
																}
																else
																{
																	echo  'Not Available' ;
																} // endif
													
									                	?>
									                </p>
                                                </div>
                                                <div class="col-md-1">
                                                    <p> </p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="clearfix"></div>
                                    <!-- (content area) -->
                                    <div class="col-md-12">
                                                                            </div>
                                    <div class="clearfix"></div>
                                    <!-- (content area) -->
                                    <div class="col-md-12">
                                        
                                    </div>

                                    <div class="clearfix"></div>
                                    <!-- (content area) -->
                                    <div class="col-md-12">
                                        <div class="bd-cart-top-content tabl-row even">
                                            <div class="col-md-offset-7 col-md-4 text-right">
                                                <p></p>
                                            </div>

                                            <div class="col-md-1">
                                <p> <?php //echo MCC_CURRENCY_SYMBOL . number_format($total, 2); ?> </p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <!-- (content area) -->
                                    <div class="col-md-12">
                                        
                                    </div>

                                    <div class="clearfix"></div>
                                    <!-- (content area) -->
    <?php
    $display = "display:none";
    if ($this->session->userdata("donation") != null) {
        $display = "display:block";
    }
    ?>
                                    <div class="col-md-12 charityType" style="<?php echo $display ?>">
                                        
                                    </div>

                                    <div class="clearfix"></div>
                                    <!-- (content area) -->
    <?php
    $display = "display:none";
    if ($this->session->userdata("donationAmount") != null) {
        $display = "display:table-row";
    }
    ?>
                                    <div class="col-md-12 newAmount " style="<?php echo $display ?>">
                                        <div class="bd-cart-top-content tabl-row charityType even">
                                            <div class="col-md-offset-6 col-md-4 text-right">
                                                <p><strong> Donation Amount:  </strong></p>
                                            </div>

                                            <div class="col-md-2">
                                                
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <!-- (content area) -->
                                    <div class="col-md-12">
                                        <div class="bd-cart-top-footer tabl-row">
                                            <div class="yt-product-detail text-right">
                                                <div class="col-xm-8">
                                                    <div class="add-to-cart pull-left">
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-xm-4">
                                                    <input 	type	="submit" 
                                                            class	="button update-cart-button" 
                                                            value	="SUBMIT"  
                                                            name	="update_shipping"  
                                                            style=" padding: 11px 15px;">  | 
                                                    
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

