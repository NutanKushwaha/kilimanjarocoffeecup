<?php
    if (count($products) == 0) {
        $this->load->view(THEME.'messages/inc-norecords');
    } else {
?>
<div class="tableWrapper">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered">
            <tr>
                <th width="30%" class="border" style="text-align:left;font-size: 14px;">Name</th>
                <th width="20%" class="border" style="text-align:left;font-size: 14px;">Category</th>
                <th width="10%" class="border" style="text-align:left;font-size: 14px;">Type</th>
                <th width="10%" class="border" style="text-align:left;font-size: 14px;">Set</th>
                <th width="35%" class="border" style="text-align:left;font-size: 14px;">Action</th>
            </tr>
            <?php foreach ($products as $item) { ?>
                <tr class="<?= alternator('', 'alt'); ?>" style="height:50px;">
                    <td id="<?= strtolower($item['product_name']); ?>"><?= ucfirst($item['product_name']); ?></td>
                    <td><?= $item['category']; ?></td>
                    <td><?= $item['product_type_id'] == 1 ? "Normal" : "Config" ; ?></td>
                    <td><?= ucfirst($item['set_name']); ?></td>
                    <td>
                        <a href="cpcatalogue/product/adctive/<?php 
								$opt = $item['product_is_active']?0:1;
								echo $item['product_id'].'/'.$opt; 
						?>">
                        <?php
                            $title = "De-Active";
                            $class = "fa fa-toggle-off fa-lg";
                            if($item['product_is_active']){
                                $title = "Active";
                                $class = "fa fa-toggle-on fa-lg";
                            }
                            echo '<i class="'.$class.'" title="'.$title.'"></i>';
                        ?>
                        
                        </a>
                        <!-- | 	<a href="cpcatalogue/product_images/index/<?= $item['product_sku']; ?>">Images</a> -->
					<!-- | 	<a href="cpcatalogue/alternative_product/index/<?= $item['product_sku']; ?>">Alternate</a>
					| 	<a href="cpcatalogue/related_product/index/<?= $item['product_sku']; ?>">Related</a>   -->
                    <span>&nbsp;&nbsp;</span>                    
					| 	<span>&nbsp;&nbsp;</span> <a href="cpcatalogue/product/edit/<?= $item['product_id']; ?>"><i class="fa fa-pencil-square-o fa-lg" title="Edit"></i></a> 
                    </td>
                </tr>
            <?php 
/*		| 	<a href="cpcatalogue/product/delete/<?= $item['product_id']; ?>" 
		onclick="return confirm('Are you sure you want to delete this product?');">
		<i class="fa fa-remove" title="Delete" style="color:red;"></i></a> */            
            } ?>
    </table>
</div>
<div class="clearfix"></div>
<div style="text-align:center;">
    <ul class="pagination">
         <?= $pagination;?> 
    </ul>
</div>
<?php } ?>
