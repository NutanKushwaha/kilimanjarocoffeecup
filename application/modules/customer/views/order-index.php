<div id="yt_content" class="yt-content wrap">        	

    <div class="yt-content-inner">

        <div class="container">

            <div class="row">

                <div class="columns-w" id="my-all-order-list">

                    <?php $this->load->view('themes/' . THEME . '/layout/inc-dashboard'); ?>

                    <div class="yt-main-right yt-main col-main col-lg-9 col-md-9 col-sm-8 col-xs-12">

                        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="o-detail table-responsive">

                            <div class="page-title">

                                <h1>My Orders</h1>

                            </div>

                            <tr>

                                <th>Order No.</th>

                                <th>Order Qty</th>

<!--                                <th>Discount</th>-->

                                <th>Cart Total</th>

                                <th>Order Time</th>
                                <th>Status</th>


                                <th>View</th>

                            </tr>

                            <?php
                            if ($orders['num_rows'] > 0) {

                                foreach ($orders['result'] as $k => $v) {
                                    ?>

                                    <tr>

                                        <td><?php echo $v['order_num'] ?></td>

                                        <td><?php echo $v['order_qty']; ?> </td>

                <!--                                        <td><?php echo $v['discount']; ?></td>-->

                                        <td><?php echo '$'.$v['cart_total']; ?></td>

                                        <td><?php echo $v['order_time']; ?></td>

                                        <td><?php echo $v['status']; ?></td>

                                        <td><a href="<?php echo createUrl('customer/order/details/') . $v['order_num']; ?>"><div class="button"> View Order</div></a>

                                            |

                                            <a href="<?php echo createUrl('customer/order/getpdf/') . $v['order_num']; ?>"><div class="button"> Invoice Pdf</div></a>

                                        </td>

                                    </tr>

                                    <?php
                                }
                            } else {
                                ?>

                                <tr>

                                    <td colspan="7" align="center" style="color: red"><h3> No record Found.</h3></td>

                                </tr>                                    

                                <?php
                            }
                            ?>

                        </table>

                        <div class="pagination"><?php echo $pagination ?></div>

                        <?php ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>