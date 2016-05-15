<?php
    if( $cartContents ){
        foreach ($cartContents as $cartRowId => $cartRowDet) {
?>
        <tr id="<?= $cartRowId; ?>">
            <td class="col-sm-2 col-md-6">
            <div class="media">                            
                <div class="media-body">                
                    <p class="media-heading text-left"><?= $cartRowDet['name'] ?></p>
                </div>
            </div></td>                        
            <td class="text-left order_qty_counting col-sm-2 col-md-2">
                <div class=""><p><?= $cartRowDet['qty'] ?></p></div>
            </td>
            <td class="col-sm-1 col-md-2 text-left order_qty_counting">
                <div class=""><p>£ <?= com_convertToDecimal( $cartRowDet['price'], '2' ); ?></p></div>
            </td>
            <td class="col-sm-1 col-md-1 text-right deleterow">
                <div class="close-btn removeItemCart" onclick="removeCartItem('<?= $cartRowId;?>')">
                    <span class="glyphicon glyphicon-remove"></span>
                </div>
            </td>
        </tr>
<?php
        }
    }
?>