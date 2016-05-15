<?php 
    if (count($bonus_stock) == 0) {
        $this->load->view(THEME . 'messages/inc-norecords');
    } else {
?>
<div class="tableWrapper">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
        <tr style="background: #EAEAEA">
            <th width="25%">Product Code</th>
            <th width="40%">Product Name</th>
            <th width="10%">Quantity</th>
            <th width="25%" style="text-align:left;padding-left: 75px;">Issue date</th>
        </tr>
        <?php foreach ($bonus_stock as $bstock) {?>
            <tr  class="<?php echo alternator('', 'alt'); ?>">
                <td style="text-align:left;"><?php echo $bstock['product_code']; ?></td>
                <td style="text-align:left;"><?php echo $bstock['product_name']; ?></td>
                <td style="text-align:center;"><?php echo $bstock['stock_qty']; ?></td>
                <td style="text-align:center;"><?php echo date("d/m/y", strtotime( $bstock['issue_date_time'] ) ); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="clearfix"></div>
<div class="ajax-pagination" style="text-align:center;">
        <ul class="pagination">
            <?= $pagination;?> 
        </ul>
</div>
<?php }
?>