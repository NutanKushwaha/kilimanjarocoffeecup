<h1>Manage Alternative Products</h1>
<div id="ctxmenu"><a href="cpcatalogue/alternative_product/add/<?php echo $product['product_sku']; ?>">Add Alternative Product</a> | <a href="cpcatalogue/product/index">Manage Products</a></div>
<?php
$this->load->view(THEME . 'messages/inc-messages');
if (empty($alternative_products)) {
    $this->load->view(THEME . 'messages/inc-norecords');
} else {
    ?>
    <div class="tableWrapper">
        <div class="main_action" style="padding-bottom:20px;">
            <div class="product_name" style="float:left; text-align: center; padding-left:15px; font-size:12px; font-weight:bold;">Alternative Product Name</div>
            <div class="action" style="float:right; padding-right:30px; font-size:12px; font-weight:bold">Action</div>
        </div>
        <ul id="pagetree">
            <?php
            foreach ($alternative_products as $item) {
                ?>
                <li id="alternative_product_<?php echo $item['alternative_product_id']; ?>">
                    <div class="page_item">
                        <div class="page_item_name" style="text-align: center;"><?php echo $item['alternative_product_name']; ?></div>
                        <div class="action" style="float:right;"><a href="cpcatalogue/alternative_product/delete/<?php echo $item['alternative_product_id']; ?>" onclick="return confirm('Are you sure you want to delete this Alternative product?');">Delete</a>
                        </div>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<div id="dialog-modal" title="Working">
    <p style="text-align: center; padding-top: 40px;">Updating the sort order...</p>
</div>                  
