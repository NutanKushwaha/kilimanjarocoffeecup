<?php com_e($cartContents, 0);  ?>

<div class="alert alert-danger"> Logic for calculate is applicable for shop particular product pending </div>

<div class="top-quick-reorder-container reorder-filtering">    

        <div class="reorder-product-detail-container">

            <div class="col-sm-12 col-md-12">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th class="col-md-8">Name</th>

                            <th class="text-center col-md-2">Quantity</th>

                            <th class="text-center col-md-2">Amount</th>

                            <th></th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php                        

                        foreach ($cartContents as $cartRowId => $cartRowDet) {

                    ?>

                        <tr id="<?= $cartRowId; ?>">

                            <td class="col-sm-5 col-md-5">

                                <div class="media">

                                    <div class="media-body">

                                        <h5 class="media-heading text-left"><?= $cartRowDet['name'] ?></h5>

                                    </div>

                                </div>

                            </td>

                            <td class="col-sm-3 col-md-2 order_account_name">                                

                            </td>

                            <td class="text-center order_qty_counting col-sm-2 col-md-2">

                                <div class=""><p><?= $cartRowDet['qty'] ?></p></div>

                            </td>

                            <td class="col-sm-2 col-md-2 text-center order_qty_counting">

                                <div class=""><p>£ <?= com_convertToDecimal( $cartRowDet['price'], '2' ); ?></p></div>                                

                            </td>

                            <td class="col-sm-1 col-md-1 text-right deleterow">

                                <div class="close-btn " onclick="removeCartItem('<?= $cartRowId;?>')" >

                                    <span class="glyphicon glyphicon-remove"></span>

                                </div>

                            </td>

                        </tr>                        

                     <?php                       

                        }

                    ?>

                    </tbody>

                    <tfoot>

                        <tr class="border-none">

                            <td>   </td>

                            <td>   </td>

                            <td>   </td>

                            <td class="col-md-2"><h5 class="mar-0 pad-top5"> <strong>SubTotal</strong></h5></td>

                            <td class="text-right col-md-2"><p class="mar-0 pad-top5">$<?= $cartVariables['cartTotal']; ?></p></td>

                        </tr>

                        <tr class="border-none mar-left15">

                            <td class="col-sm-8 col-md-10">

                                <button type="button" class="btn btn-orange btn-large">

                                Place Order

                                </button>

                            </td>

                            <td>   </td>

                            <td>   </td>

                            <td>   </td>

                            <td>   </td>

                        </tr>

                        <tr class="border-none">

                            <td>   </td>

                            <td>   </td>

                            <td>   </td>

                            <td><h3>Total</h3></td>

                            <td class="text-right"><h3><strong>$<?= $cartVariables['cartTotal']; ?></strong></h3></td>

                        </tr>

                    </tfoot>

                </table>

            </div>

            <div class="clearfix"></div>

        </div>

</div>

<div class="clearfix"></div>

<script>

    function removeCartItem( cartId ){



    }

</script>