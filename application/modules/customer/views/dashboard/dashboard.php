<div id="yt_content" class="yt-content wrap">        	
    <div class="yt-content-inner">
        <div class="container">
            <div class="row">
                <div class="columns-w">
                    <?php $this->load->view('themes/' . THEME . '/layout/inc-dashboard'); ?>
                    <div class="yt-main-right yt-main col-main col-lg-9 col-md-9 col-sm-8 col-xs-12" id="yt_main">
                        <div class="yt_main_inner">	                            	

                            <div class="global-site-notice demo-notice">
                                <div class="notice-inner"><p>This is a demo store. Any orders placed through this store will not be honored or fulfilled.</p></div>
                            </div>

                            <div class="my-account"><div class="dashboard">
                                    <div class="page-title">
                                        <h1>My Account</h1>
                                    </div>
                                    <div class="welcome-msg">
                                        <p class="hello"><strong>Hello, <?php echo $userDetails['upro_first_name'] . ' ' . $userDetails['upro_last_name'] ?>!</strong></p>
                                        <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
                                    </div>
                                    <div class="box-account box-info">
                                        <div class="box-head">
                                            <h2>Account Information</h2>
                                        </div>
                                        <div class="col2-set">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-1">
                                                <div class="box">
                                                    <div class="box-title">
                                                        <h3>Contact Information</h3>
                                                        <a href="<?php echo createUrl('customer/contact/edit') ?>">Edit</a>
                                                    </div>
                                                    <div class="box-content">
                                                        <p>
                                                            <?php echo $userDetails['upro_first_name'] . ' ' . $userDetails['upro_last_name'] ?><br>
                                                            <?php echo $userDetails['uacc_email'] ?><br>
                                                            <a href="<?php echo createUrl('customer/contact/edit') ?>">Change Password</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2-set">
                                            <div class="box col-lg-12 col-1">
                                                <div class="box-title ">
                                                    <h3>Address Detail</h3>
                                                    <a href="<?php echo createUrl('customer/contact/all_address') ?>">Manage Addresses</a>
                                                </div>
                                                <div class="box-content">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mar-left-10">
                                                        <h4>Default Billing Address</h4>
                                                        <address>
                                                            <?php
                                                            if ($BilllingAddress['num_rows'] > 0) {
                                                                $BilllingAddress = $BilllingAddress['result'];
                                                                $billing = "";
                                                                //$billing .= ($userDetails['upro_first_name'])?$userDetails['upro_first_name'].',':"";
                                                                $billing .= ($BilllingAddress['uadd_address_01']) ? $BilllingAddress['uadd_address_01'] . ',' : "";
                                                                $billing .= ($BilllingAddress['uadd_address_02']) ? $BilllingAddress['uadd_address_02'] . ',' : "";
                                                                $billing .= ($BilllingAddress['uadd_city']) ? $BilllingAddress['uadd_city'] . ',' : "";
                                                                $billing .= ($BilllingAddress['uadd_county']) ? $BilllingAddress['uadd_county'] . ',' : "";
                                                                $billing .= ($BilllingAddress['uadd_post_code']) ? $BilllingAddress['uadd_post_code'] . ',' : "";
                                                                $billing .= ($BilllingAddress['uadd_country']) ? $BilllingAddress['uadd_country'] . ',' : "";
                                                                echo substr($billing, 0, -1);
                                                                ?>
                                                                <a href="<?php echo createUrl('customer/contact/edit_address/') . $BilllingAddress['uadd_id'] ?>">Edit Address</a>
                                                                <?php
                                                            } else {
                                                                echo "No Default Billing Address .";
                                                            }
                                                            ?>

                                                        </address>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <h4>Default Shipping Address</h4>
                                                        <address>
                                                            <?php
                                                            if ($shippingAddress['num_rows'] > 0) {
                                                                $shippingAddress = $shippingAddress['result'];
                                                                $shipping = "";
                                                                //    $shipping .= ($userDetails['upro_first_name'])?$userDetails['upro_first_name'].',':"";
                                                                $shipping .= ($shippingAddress['uadd_address_01']) ? $shippingAddress['uadd_address_01'] . ',' : "";
                                                                $shipping .= ($shippingAddress['uadd_address_02']) ? $shippingAddress['uadd_address_02'] . ',' : "";
                                                                $shipping .= ($shippingAddress['uadd_city']) ? $shippingAddress['uadd_city'] . ',' : "";
                                                                $shipping .= ($shippingAddress['uadd_county']) ? $shippingAddress['uadd_county'] . ',' : "";
                                                                $shipping .= ($shippingAddress['uadd_post_code']) ? $shippingAddress['uadd_post_code'] . ',' : "";
                                                                $shipping .= ($shippingAddress['uadd_country']) ? $shippingAddress['uadd_country'] . ',' : "";
                                                                echo substr($shipping, 0, -1);
                                                                ?>
                                                                <a href="<?php echo createUrl('customer/contact/edit_address/') . $shippingAddress['uadd_id'] ?>">Edit Address</a>
                                                                <?php
                                                            } else {
                                                                echo "No Default Shipping Address .";
                                                            }
                                                            ?>

                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>                                      	                        
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hot categories home page v2 -->
        </div>
    </div>      
    <!-- END: content -->		
</div>