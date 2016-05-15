<h1>Manage Related Departments</h1>
<div id="ctxmenu"><a href="cpcatalogue/related_department/add/<?php echo $product['product_id']; ?>">Add Related Department</a> | <a href="cpcatalogue/product/index">Manage Products</a></div>
<?php
$this->load->view('inc-messages');
?>
<?php
if (empty($related_departments)) {
    $this->load->view(THEME.'messages/inc-norecords');
} else {
    ?>
    <div class="tableWrapper">
        <div class="main_action" style="padding-bottom:20px;">
            <div class="product_name" style="float:left; text-align: center; padding-left:15px; font-size:12px; font-weight:bold;">Related Department Name</div>
            <div class="action" style="float:right; padding-right:30px; font-size:12px; font-weight:bold">Action</div>
        </div>
        <ul id="pagetree">
            <?php
            foreach ($related_departments as $item) {
                ?>
                <li id="related_department_<?php echo $item['related_department_id']; ?>">
                    <div class="page_item">
                        <div class="page_item_name" style="text-align: center;"><?php echo $item['name']; ?></div>
                        <div class="action" style="float:right;"><a href="cpcatalogue/related_department/delete/<?php echo $item['related_department_id']; ?>" onclick="return confirm('Are you sure you want to delete this Related Department?');">Delete</a>
                        </div>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<div id="dialog-modal" title="Working">
    <p style="text-align: center; padding-top: 40px;">Updating the sort order...</p>
</div>                  
