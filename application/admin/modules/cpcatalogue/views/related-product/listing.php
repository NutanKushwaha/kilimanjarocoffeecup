<div class="row">
<div class="col-md-6">
    <h1 class="mar-0">Manage Related Products</h1>
</div>
<div class="col-md-6">
    <div id="ctxmenu" class="top-prod-related-manage-option">
        <ul class="list-unstyled list-inline text-right" >
            <li> <a href="cpcatalogue/related_product/add/<?php echo $product['product_sku']; ?>" class="btn btn-primary"> <i class="fa fa-plus fa-lg"></i> Add Related Product</a>  &nbsp;&nbsp;| </li>
            <li> <a href="cpcatalogue/product/index" class="btn btn-primary"> <i class="fa fa-edit fa-lg"></i> Manage Products</a> </li>
        </ul>
    </div>
</div>
</div>
<?php
$this->load->view(THEME.'messages/inc-messages');   
if (empty($related_products)) {
    $this->load->view(THEME.'messages/inc-norecords');    
} else {
    ?>
<div class="tableWrapper" id="manage-related-prod">
        <div class="main_action" style="padding-bottom:20px;">
            <div class="product_name" style="float:left; text-align: center;font-size:12px; font-weight:bold;">Related Product Name</div>
            <div class="action" style="float:right; padding-right:30px; font-size:12px; font-weight:bold">Action</div>
        </div>
        <ul id="pagetree">
            <?php
            foreach ($related_products as $item) {
                ?>
                <li id="related_product_<?php echo $item['related_product_id']; ?>">
                    <div class="page_item">
                        <div class="page_item_name" style="text-align: center;"><?php echo $item['related_product_name']; ?></div>
                        <div class="action" style="float:right;"><a href="cpcatalogue/related_product/delete/<?php echo $item['related_product_id']; ?>" onclick="return confirm('Are you sure you want to delete this Related product?');">Delete</a>
                        </div>
                        <div class="clearfix"></div>
                </li>
            <?php } ?>
        </ul>
        <div class="clearfix"></div>
    </div>
<?php } ?>

<div id="dialog-modal" title="Working">
    <p style="text-align: center; padding-top: 40px;">Updating the sort order...</p>
</div>                  
