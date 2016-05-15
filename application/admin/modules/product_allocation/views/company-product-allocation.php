<?php
    $attr = [
        'id' => 'comp-product-allot',
        'name' => 'comp-product-allot',
    ];            
    echo form_open(current_url(), $attr);
    $hidden_data = [
        'type'  => 'hidden',
        'name'  => 'selected_company',
        'id'    => 'selected_company',
        'value' => '0',
    ];

    echo form_input($hidden_data);            
?>
	<div class="col-lg-12">
        <?= $this->load->view(THEME . 'layout/inc-menu-only-dashboard');  ?>		
        <div class="col-sm-1 pull-right" style="padding-bottom:10px;">
            <button type="submit" class="btn btn-primary">Update!</button>                             
        </div>             
	</div>
    <div class="col-lg-12 ">
    	<div class="tableWrapper" id="product-tbl-div"> 
    		<table width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered">
    			<thead>
    				<th width="20%" style="text-align:center">Companies</th>
    				<th>Products</th>
    			</thead>
    			<tbody>
    				<!-- User list -->
    				<tr>
    					<td style="vertical-align:top;" id="related-companies-table">
                            <?php
                                $company_list_html = '';
                                foreach ($company_list as $comp_det) {
                                    $company_list_html .= '<li data-ccode="'.$comp_det['company_code'].'" 
                                                            class="list-group-item ">'.ucfirst( $comp_det['name'] ).'</li>';
                                }
                                $company_list_html = '<ul id="comp-list" class="list-group"  >'.$company_list_html.'</ul>';
                                echo $company_list_html;
                            ?>
    					</td>
						<td id="related-product-table"> <!-- Product table -->
                            <div class="alert alert-danger"> Please select company. </div>
    					</td>    					
    				</tr>
    			</tbody>
			</table>
		</div>
	</div>
    <div class="clearfix"></div>
    <?= form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $("#comp-list li").on("click", function (e) {
                $("#comp-list li").removeClass("active");
                $(this).addClass("active");
                $("#selected_company").val( $(this).attr("data-ccode") );
                getCompProds();
            });                        
        });

        function getCompProds() {
            if( $('#selected_company').val() == 0 || $('#selected_company').val() == "" ) {
                $("#related-product-table").html('<div class="alert alert-danger"> Please select company. </div>');                
            }
             $.ajax({
                type: "POST",
                data: $('#comp-product-allot').serialize() ,
                url: "product_allocation/ajax/product_allocation/getCompProd",
                success: function(data){
                        $('#related-product-table').html("");
                        data = JSON.parse(data);
                        if(data.success){
                            $('#related-product-table').html(data.html);
                            $( "input[name='<?= $this->security->get_csrf_token_name() ?>']" ).val(data.csrf_hash);
                        }
                    }
                });
        }
    </script>
