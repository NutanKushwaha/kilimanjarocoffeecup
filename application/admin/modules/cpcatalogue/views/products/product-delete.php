<h1></h1>
<div id="ctxmenu">	<a href="cpcatalogue/product/index">
						Manage Products
					</a> |
					<a href="cpcatalogue/product/product_delete/<?php echo $product['product_id'] ?>" 
					onclick="return confirm('Are you sure you want to delete this Product and all its contents?');">
						Delete Product
					</a>
</div>
<div class="tableWrapper">  
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable" id="">
		<tr>
			<td colspan="2">
				<h4>Delete Product And All Its Contents</h4>
			</td>
		</tr>
        <tr>
            <th width="34%">
				<a id="main_prod" href="cpcatalogue/product/product_delete/<?php echo $product['product_id'] ?>" 
				onclick="return confirm('Are you sure you want to delete this product and all its contents?');">
				Delete Product</a>
			</th>
            <td width="66%"><label for="main_prod" > <?php echo  ucfirst($product['product_name']); ?> </label></td>
        </tr>
        <?php
        if( $product[ 'product_type_id' ] == 2 ){
			$form_attr = [];
			$form_attr[ 'id' ] 	= 'config_del';
			$form_attr[ 'name'] = 'config_del';
			$hidden = [];
			$hidden[ 'ref_product_id' ] = $product['product_id'];
			echo form_open('cpcatalogue/product/product_delete_config', $form_attr, $hidden);
        ?>
		<tr>
			<td colspan="2">
				<h4>Delete config products</h4>
			</td>
		</tr>
		<?php		
			foreach($configProducts as $stIndex => $stDet ){
				if( intval($stIndex) !== intval($product_id) ){
		?>
        <tr>
            <th width="34%">
				<?php				
				$check = [];
				$check[ 'id' ] = "config_products_".$stIndex;
				echo form_checkbox( 'config_products[]', $stIndex, 0, $check);				
				?>
			</th>
            <td width="66%">				
				<label for="<?= $check[ 'id' ]; ?>" > <?php echo ucfirst($stDet['product_name']); ?> </label>
			</td>
        </tr>
        <?php
			}
			}
		?>
		<tr>
			<td> </td>
			<td >
				<input type="submit" value="Delete config" class="btn btn-info pull-left">
			</td>
		</tr>		
        <?php
			echo form_close();
		}
        ?>            
        <tr>
            <th width="34%">&nbsp;</th>
            <td width="66%"></td>
        </tr>
    </table>
</div>
