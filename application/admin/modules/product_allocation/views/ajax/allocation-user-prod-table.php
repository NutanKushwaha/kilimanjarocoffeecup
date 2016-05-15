<div class="pull-left search">
	<input class="form-control" type="text" name="prod_search" id="prod_search" placeholder="Search">
</div>
<table id="products-qty" width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered">
<thead>
	<th width="15%" class="border" style="text-align:center">Name</th>
    <th width="20%" class="border" style="text-align:center;display:none;">D-Policy<br/>N-Policy</th>	
	<th width="5%" class="border" style="text-align:center">Allowed</th>
	<th width="70%" class="border" style="text-align:center">Attributes</th>
</thead>
<?php
    
    echo form_hidden($hidden_prods);
	$tbl_row_html = '';
    $period = ['1' => 'Weekly','2' => 'Monthly', '3' => 'Yearly'];
    foreach($products as $value){

        /*
    	$row_product = [
    					'type' => 'number',
				        'name'  => 'products['.$dept_id.']['.$user_id.']['.$value['product_sku'].']',
				        'value'	=> com_arrIndex($current_dept_user_prod, $value['product_sku'], ''),
                        'class' => 'allownumericwithoutdecimal user-prod-allow-input',
                        'data-orignal' => com_arrIndex($current_dept_user_prod, $value['product_sku'], 0),
                        'data-pcode' => $value['product_sku'],
                        'data-ttl_alloc' => $value['qty_limit'],
						];
        */
        $row_qty = array(
                    'name'  => 'quantity['.$dept_id.']['.$user_id.']['.$value['product_sku'].']',
                    'value' => $value['qty_limit'],
                    'type'  => 'number',
                    'style' => 'width:80%',
                );

        $attrHtml = '';
        if( isset($productAttr[$value['product_id']]) ){
            foreach( $productAttr[ $value['product_id']] as $attrIndex => $attrOpts) {
                $attrHtml .= '<div class="col-lg-3">'.
                                form_dropdown(
                                    'attr['.$dept_id.']['.$user_id.']['.$value['product_sku'].']['.$attrIndex.']',
                                     $attrOpts, "", ' class="form-control" ')
                            .'</div>';
            }
        }        
    	$tbl_row_html .= 	'<tr  class="'.alternator('', 'alt').'" data-code="'.$value['product_sku'].'">
    							<td>'.$value['product_name'].'</td>
                                <td class="set-day-limit-option" style="display:none;" >
                                    <div style="width:100%">
                                        <b>'.$period[$value['days_limit']].'-('.$value['qty_limit'].')</b>
                                    </div> '.'
                                    <div style="width:100%">
                                        <div style="width:60%;float:left;">
                                        '.  form_dropdown('days['.$dept_id.']['.$user_id.']['.$value['product_sku'].']', 
                                                            $period, $value['days_limit'], 'class="select_picker"').'
                                        </div>
                                        <div style="width:40%;float:left;">
                                            '.form_input($row_qty).'
                                        </div>
                                    </div>
                                </td>
    							<td id="'.$value['product_sku'].'">'.($value['qty_limit'] - com_arrIndex($dept_user_prod_count,$value['product_sku'], 0 ) ).'</td>    							
    							<td>'.$attrHtml.'</td>
    						</tr>';
    }
    echo $tbl_row_html;
?>
</table>
<script>
    $( document ).ready(function() {
		$(".allownumericwithoutdecimal").on("keypress keyup blur change",function (event) {
           	$(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }            
        });

        $(".user-prod-allow-input").on( 'change', function(event) {
        	
        	var obj_this = $(this);
        	var current_val = obj_this.val();
        	var old_val = obj_this.data('orignal');
        	var act_alloc = obj_this.data('ttl_alloc');        	
        	var val_left = old_val - current_val;        	
        	var left_prod_obj =  $('#'+obj_this.data('pcode'));
        	var curr_left = parseInt( left_prod_obj.text() ) + val_left;

        	if( act_alloc < curr_left || curr_left < 0) {
        		obj_this.val( old_val );        		        		
        		$('#msg-pop-model-title').html('Wrong product allocation');
        		$('#msg-pop-model-msg').html('Sorry, you cannot exceed allocated quantity.');
        		$('#msg-pop-model').modal('show');
        		return false;
        	}
        	left_prod_obj.text( curr_left );
        	obj_this.data('orignal', current_val);
        	
        } );

		$("input#prod_search").on('keyup', function () {
		    var filter = $(this).val();
		    var regExPattern = "gi";
		    var regEx = new RegExp(filter, regExPattern);
		    $("#products-qty tr").each(function () {
		        if($(this).data('code'))
		        if (
		        $(this).text().search(new RegExp(filter, "i")) < 0
		        && $(this).data('code').search(regEx) < 0
		        ){
		            $(this).hide();
		        } else {
		            $(this).show();
		        }
		    });
		});
	});
</script>