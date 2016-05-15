<h1>Manage ALL Product Images</h1>
<div id="ctxmenu"><a href="cpcatalogue/product">Manage Products</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<?php if(count($total_rows) == 0) { $this->load->view(THEME.'messages/inc-norecords'); return ; } ?>
<div class="tableWrapper">
  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
    <tr>
		<th width="5%">Sr no.</th>
		<th width="45%">Product Name</th>
		<th width="50%">Images</th>      
    </tr>
	<tr>
      <td colspan="3" align="center">		  
		  <?php
			$FORM_JS = '  name="delImages" id="delImages"  ';
			echo form_open(current_url(), $FORM_JS);
		  ?>		 
		  <?php echo $pagination; ?>
	  </td>          
    </tr>    
    <?php   
		$index = 0;
        foreach($prod_images as $item) {
		$index++;
    ?>
    <tr class="<?php echo alternator('', 'alt');?>">		
		<td>
			<?php echo $index; ?> 
		</td>
		<td>
			<?php echo $item['product_name']; ?>
			<input type="checkbox" name="del_images[]" value="<?php echo $item['product_image_id'];?>" />
		</td>		
		<td>	
			<?= $item['image'] ?>
			<br/>		
			<img src="<?php echo ar($this->config->item('PRODUCT_IMAGE_URL') . $item['image'],50,50,'images'); ?>" width="150px" />
			<br/>
			<a href="cpcatalogue/product/imgdelete/<?php echo $item['product_image_id'];?>/<?php echo $offset;?>">Delete</a>
		</td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="3" align="center">
		  <input type="hidden" name="offset" value="<?php echo $offset;?>" />
		  <input type="submit" value="delete" />
      </td>
    </tr>    
    <tr>
      <td colspan="3" align="center">
		  <?php echo $pagination; ?>		  
		<?php echo form_close(); ?>  
      </td>
    </tr>    
  </table>
</div>
