<div class="row">
    <div class="col-md-6">
        <h1 class="mar-0">Add Related Product</h1>
    </div>
    <div class="col-md-6">
        <div id="ctxmenu" class="top-prod-related-manage-option">
            <ul class="list-unstyled list-inline text-right">
                <li> <a href="cpcatalogue/related_product/index/<?php echo $product['product_id']; ?>" class="btn btn-primary"> <i class="fa fa-edit fa-lg"></i> Manage Related Products</a> </li>
            </ul>

        </div>

    </div>
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
</div>
<div class="clearfix"></div>
<div id="manage-related-prod">
    <?php
    
    $FORM_JS = ' name="addcatform" id="addcatform" ';
    echo form_open(current_url($product['product_id']), $FORM_JS);
    ?>
    <div style="height: 400px;overflow-y: scroll">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
            <tr><th colspan="2">Result</th><td><input type="checkbox" class="selectALL"></td></tr>
            <?php foreach ($options as $key => $opt) { ?>
                <tr>
                    <td><?php echo $opt ?></td>
                    <td><?php echo $key ?></td>
                    <td><input type="checkbox" class="checkme" name="related_product_name[]" value="<?php echo $key ?>"></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <p align="center"><input type="hidden" name="product_id" id="product_id" value="<?php echo $product['product_id']; ?>"></p>
    <p align="center"><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>
        <?php echo form_close(); ?>

</div>
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