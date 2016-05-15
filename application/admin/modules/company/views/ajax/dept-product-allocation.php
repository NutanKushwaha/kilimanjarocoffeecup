 <!-- Multiple Select -->
<link href="css/multiple-select.css" rel="stylesheet" />
<script src="js/multiple-select.js"></script>
<div class="tableWrapper"> 
	<?php
        
        echo form_hidden('products', $hidden);
        echo form_hidden('days', $hidden_days);
        echo form_hidden('gpolicy', $hidden_gpolicy);
        echo form_hidden('quantity', $hidden_quantity);

		$this->table->clear();
		$table_property = array('table_open' => '<table width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered">');
        $this->table->set_template($table_property);
        $this->table->set_heading(  ['data' => 'Name' , 'class' => "border", 'style' => "text-align:center", 'width' => "20%"],                                     
                                    ['data' => 'Selected' , 'class' => "border", 'style' => "text-align:center", 'width' => "10%" ],
                                    ['data' => 'Policy Days' , 'class' => "border", 'style' => "text-align:center", 'width' => "20%"],
                                    ['data' => 'Policy Qantity' , 'class' => "border", 'style' => "text-align:center", 'width' => "20%" ],
                                    ['data' => 'Group Policy' , 'class' => "border", 'style' => "text-align:center", 'width' => "35%" ]
                                );
        
        $JS = ' class="select_picker" ';
    	foreach ($products as $key => $value) {

                $selected = false;
                if( in_array($value['product_sku'], $common_products)){
                    $selected = true;
                }
                $prod_id = 'prod-'.$value['product_sku'];
	        	$row_product = array(
									'id' 	=> $prod_id,
							        'name'  => 'products[]',
							        'value'	=> $value['product_sku'],
                                    'checked' => $selected,                                    
								);
                $row_qty = array(
                                    'name'  => 'quantity['.$value['product_sku'].']',
                                    'value' => com_arrIndex($common_quantity, $value['product_sku'], '1'),
                                    'type'  => 'number',
                                );
                $policy_days = com_arrIndex($common_days, $value['product_sku'], '');
                $gpolicy = com_arrIndex($common_gpolicy, $value['product_sku'], []);
    		  $this->table->add_row( form_label($value['product_name'], $prod_id), 
                                    ['data' => form_checkbox($row_product), 'style' => 'text-align:center;' ],
                                    ['data' => form_dropdown('days['.$value['product_sku'].']', $days, $policy_days, $JS), 'style' => 'text-align:center;' , 'class' => 'set-day-limit-option'],
                                    ['data' => form_input($row_qty), 'style' => 'text-align:center;' , 'class' => 'set-day-limit-option'],
                                    ['data' => form_multiselect('gpolicy['.$value['product_sku'].'][]', $group_products, $gpolicy, ' class="gpolicy" id="gpolicy_'.$value['product_sku'].'"'), 
                                                    'style' => 'text-align:center;']
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
    $( ".set-day-limit-option > input" ).change(function() {
        if($(this).val() < 0){
            $(this).val(0);
        }
    });
    
    $('.gpolicy').multipleSelect({
                filter:true,
                width:"100%"
            });
    
</script>
