<h1> <i class="fa fa-picture-o"></i> Manage Product Images</h1>
<div id="ctxmenu" class="pad-top20 text-left">
    <a href="cpcatalogue/product_images/add/<?php echo $product['product_id'];?>" class="btn btn-primary"> <i class="fa fa-plus-square"></i> Add Image</a> | <a class="btn btn-primary" href="cpcatalogue/product"> <i class="fa fa-wrench"></i> Manage Products</a>
</div>

<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<?php if(count($images) == 0) { $this->load->view(THEME.'messages/inc-norecords'); return ; } ?>
<div class="tableWrapper">
  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
    <tr>
      <th width="50%">Image Name</th>
      <th width="25%">Action</th>
    </tr>
    <?php
        foreach($images as $item) {
           /* $main_img_url = '<img src="images/icons/Aqua-Ball-Green-icon.png" /></a>';
            if($item['is_main_image'] == 0) {
                $main_img_url = '<a href="cpcatalogue/product_images/set_main/'.$item['product_image_id'].'" title="Set as main image"><img src="images/icons/Aqua-Ball-icon.png" /></a>';
            }*/
    ?>
    <tr class="<?php echo alternator('', 'alt');?>">
      <td><img src="<?php echo  $this->config->item('PRODUCT_IMAGE_URL').$item['image']; ?>" width="150px" /></td>
       <?php /*<td><?php echo $main_img_url;?></td>  */?>
      <td><a href="cpcatalogue/product_images/edit/<?php echo $item['product_image_id'];?>">Edit</a> | <a href="cpcatalogue/product_images/delete/<?php echo $item['product_image_id'];?>">Delete</a></td>
    </tr>
    <?php } ?>
  </table>
</div>