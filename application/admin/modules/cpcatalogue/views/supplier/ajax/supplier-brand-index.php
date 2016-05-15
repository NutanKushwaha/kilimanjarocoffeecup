<?php 
    if (count($supplier_brand) == 0) {
        $this->load->view(THEME . 'messages/inc-norecords');
    } else {
?>
<div class="tableWrapper">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
        <tr style="background: #EAEAEA">
            <th width="80%">Supplier Brands</th>
            <th width="20%" style="text-align:left;padding-left: 75px;">Action</th>
        </tr>
        <?php foreach ($supplier_brand as $sbrand) {            
            ?>
            <tr  class="<?php echo alternator('', 'alt'); ?>">
                <td style="text-align:left;"><?php echo $sbrand['brand_name']; ?></td>
                <td style="text-align:right;">
                    <a  href="cpcatalogue/supplier/brands/<?php echo $supplier_id.'/'.$sbrand['id']; ?>">Edit</a> |
                    <a  href="cpcatalogue/supplier/delete_brand/<?php echo $supplier_id.'/'.$sbrand['id']; ?>" 
                        onclick="return confirm('Are you sure you want to Delete this brand?');">Delete</a></td>
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
