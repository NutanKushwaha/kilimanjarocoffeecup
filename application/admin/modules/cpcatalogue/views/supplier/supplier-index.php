<div class="col-sm-6 mar-bot15 pad-left0">
    <h1> <i class="fa fa-user-secret"></i> Manage Suppliers</h1>
</div>
<div class="col-sm-6">
    <div id="ctxmenu" class="text-right"><a href="cpcatalogue/supplier/add" class="btn btn-primary"> <i class="fa fa-plus-square"></i> Add Supplier</a></div>
</div>
<?php 
    $this->load->view(THEME.'messages/inc-messages');
    if (empty($supplier)) {    
        $this->load->view(THEME.'messages/inc-norecords');   
    } else {
    ?>
    <div class="tableWrapper">
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered attr-set-list">
            <tr>
                <th width="70%" class="border" style="text-align:left;font-size: 14px;">Name</th>
                <th width="30%" class="border" style="text-align:left;font-size: 14px;">Action</th>
            </tr>
            <?php
            foreach ($supplier as $item) {
                ?>
                <tr class="<?php echo alternator('', 'alt'); ?> ">
                    <td> <strong> <?php echo $item['supplier_name']; ?> </strong> </td>
                    <td>
                        <a href="cpcatalogue/supplier/brands/<?php echo $item['supplier_id']; ?>" class="btn btn-info">Manage Brands</a> | 
                        <a href="cpcatalogue/supplier/edit/<?php echo $item['supplier_id']; ?>" class="btn btn-info">Edit</a> | 
                        <a href="cpcatalogue/supplier/delete/<?php echo $item['supplier_id']; ?>" 
                            onclick="return confirm('Are you sure you want to delete this Supplier?');" 
                            class="btn btn-info">Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
<?php } ?>