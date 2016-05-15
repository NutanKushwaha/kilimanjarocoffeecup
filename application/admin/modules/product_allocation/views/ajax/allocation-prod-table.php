<div class="search-prod-form"> 
	<div class="col-md-10">
		<input 	type="text"	id="search_product" name="search_product" 
				placeholder="Search by product sku or name" 
				value="<?php echo $search_product; ?>" 
				class="form-control"
				onkeydown="if (event.keyCode == 13) { return false;}">
	</div>
	<div class="col-md-1 checkbox">
		<label>		  
		  <?php			
			echo form_checkbox("exact_match", 1, $exact_match);
		  ?> Exact
		</label>
	</div>
	<div class="col-md-1">
		<input type="button" value="Search" class="btn btn-primary" id="find-products">
	</div>	
</div>
<div class="tableWrapper"> 
	<?php
        foreach($hidden_sku as $hidden_index){
            foreach($hidden_index as $prod_input_key => $prod_input_val){
                echo form_hidden($prod_input_key, $prod_input_val);
            }
        }
		$this->table->clear();
		$table_property = array('table_open' => '<table width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered">');
        $this->table->set_template($table_property);
        $this->table->set_heading(  ['data' => 'Name' , 'class' => "border", 'style' => "text-align:center", 'width' => "40%"],                                     
                                    ['data' => 'Selected' , 'class' => "border", 'style' => "text-align:center", 'width' => "20%" ], 
                                    ['data' => 'SKU' , 'class' => "border", 'style' => "text-align:center", 'width' => "40%"]
                                    );
                                    

    	foreach ($products as $key => $value) {

                $selected = false;
                if( in_array($value['product_sku'], $selected_posted_sku)){
                    $selected = true;
                }
                $product_sel_box = 'products['.$comp_id.'][]';
                $product_sle_box_id = $comp_id.'-'.$value['product_sku'];
	        	$row_product = array(
							        'name'  => $product_sel_box,
							        'id'  => $product_sle_box_id,
							        'value'	=> $value['product_sku'],
                                    'checked' => $selected
								);
    		$this->table->add_row( $value['product_name'], 
                                    ['data' => form_checkbox($row_product), 'style' => 'text-align:center;' ],
                                    form_label($value['product_sku'], $product_sle_box_id)
                                 );
    	}                
    	echo $this->table->generate(); 
	?>
</div>
<div class="clearfix"></div>
<div class="ajax-pagination" style="text-align:center;">
    <ul class="pagination">
         <?= $pagination;?> 
    </ul>
</div>
<script>
	$(document).ready( function(){
		$( "#find-products" ).on('click', function(){
			$.post("<?= base_url('/product_allocation/ajax/product_allocation/getCompProd/'); ?>",{'form_data':$('#comp-product-allot').serialize(),'ci_csrf_token':'','ajax':'1','offset':'0',},
						function(data){
							data = JSON.parse(data);
							if(data.success){
								$('input[name=\'ci_csrf_token\']' ).val(data.csrf_hash);                        
								$('#related-product-table').html(data.html);								
							}  
						});
			return false;
		});
	});
</script>
