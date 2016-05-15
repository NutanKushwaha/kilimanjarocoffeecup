<h1>Add Alternative Product</h1>
<div id="ctxmenu"><a href="cpcatalogue/alternative_product/index/<?php echo $product['product_id']; ?>">Manage Alternative Products</a></div>
<div id="ctxmenu"><a href="cpcatalogue/alternative_product/add/<?php echo $product['product_sku']; ?>">Add Alternative Product</a> | <a href="cpcatalogue/product/index">Manage Products</a></div>
<div class="form-search">
    <form name="formAlternate" action="" method="GET">
        <div class="pull-left" style="width:30%">
            <select id="cat" name="cat" style="width:100%">
                <option value="">All Categories</option>
                <?php
                echo($searchTree);
                ?>
            </select>
        </div>
        <div class="pull-right" style="width:70%" >
            <div class="jqTransformInputInner"><div>
                    <input type="text" placeholder="Enter keywords to search..." class="" name="q" style="width:95%" size="50" autocomplete="off" >
                    <button class="button " title="Search" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </div>
    </form>
</div>
<div class="clear"></div>

<?php
$FORM_JS = '  name="addcatform" id="addcatform" ';
echo form_open(current_url($product['product_id']), $FORM_JS);
//com_e($options);
?>
<div style="height: 400px;overflow-y: scroll">
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr><th colspan="2">Result</th><td><input type="checkbox" class="selectALL"></td></tr>
        <?php foreach ($options as $key => $opt) { ?>
            <tr>
                <td><?php echo $opt ?></td>
                <td><?php echo $key ?></td>
                <td><input type="checkbox" class="checkme" name="alternative_product_name[]" value="<?php echo $key ?>"></td>
            </tr>
        <?php } ?>
    </table>
</div>

<p align="center"><input type="hidden" name="product_id" id="product_id" value="<?php echo $product['product_id']; ?>"></p>
<p align="center"><input type="submit" name="button" id="button" value="Submit"></p>
    <?php echo form_close(); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.selectALL').click(function () {
            if (jQuery(this).prop('checked'))
            {
                $('.checkme').each(function () {
                    $(this).prop('checked', true);
                });
            }
            else {
                $('.checkme').each(function () {
                    $(this).prop('checked', false);
                });
            }

        })
    })
</script>