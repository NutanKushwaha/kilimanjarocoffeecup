<?php
	$qty_lower = 0;
	$qty_upper = 0;
	if( $stock_available ){
		$qty_lower = 1;
		$qty_upper = $stock_available;
	}
	
	$hidden = [];
	$hidden[ 'from_store' ] = $store_details[ 'id' ];
	$hidden[ 'product_code'] = $product_code;
	$attr = [];
	$attr[ 'id' ] = 'stock-exchange';
	$attr[ 'name' ] = 'stock-exchange';
	
	echo form_open( current_url(), $attr, $hidden);
?>
	<div>
		<?php
			if( !$store_found ){
		?>
			Store does not found, main store considered
		<?php
			}
		?>
	</div>
	  <div class="form-group">
		<label >Stock available at store <?= $store_details[ 'store_name' ]; ?>: <?php  echo $stock_available; ?></label>		  
	  </div>
	  <div class="form-group">
		<label for="to_store">Exchange with store </label>
		  <?php
			$JS = ' id = "to_store" required autocomplete="off"';
			echo form_dropdown( 'to_store', $company_stores, '', $JS);
		  ?>
	  </div>
	  <div class="form-group">
		  <label for="qty">Quantity (between <?= $qty_lower . ' and ' . $qty_upper; ?>)</label>
		  <?php
			$qty = [];
			$qty[ 'id' ] = 'qty';
			$qty[ 'min' ] = $qty_lower;
			$qty[ 'max' ] = $qty_upper;
			$qty[ 'name' ] = 'qty';
			$qty[ 'type' ] = 'number';
			$qty[ 'autocomplete' ] = 'off';
			$qty[ 'value' ] = $qty_lower;
			echo form_input( $qty );
		  ?>		  
	  </div>
	  <button name="stock-exchange" type="submit" value="1" class="btn btn-default">Submit</button>
<?php
	echo form_close();
?>
