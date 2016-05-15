<?php 
    if (count($companies_store) == 0) {
        $this->load->view(THEME . 'messages/inc-norecords');
    } else {
?>
<div class="tableWrapper">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
        <tr style="background: #EAEAEA">
            <th width="75%">Company Store</th>
            <th width="25%" style="text-align:left;padding-left: 75px;">Action</th>
        </tr>
        <?php foreach ($companies_store as $cstore) {
            $actText    = 'Active';
            $actUrl     = 'enable';
            if( $cstore['is_active'] ){
                $actText    = 'De-active';
                $actUrl     = 'disable';
            }            
            ?>
            <tr  class="<?php echo alternator('', 'alt'); ?>">
                <td style="text-align:left;"><?php echo $cstore['store_name']; ?></td>
                <td style="text-align:right;">
                    <a  href="company/store/edit/<?php echo $cstore['id']; ?>">Edit</a> |                     
                    <a  href="company/store/stock_detail/<?php echo $cstore['id'] ?>" >Delete</a></td>
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
