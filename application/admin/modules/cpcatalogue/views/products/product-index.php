<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <a href="cpcatalogue/product/"><h3 style="cursor: pointer; margin: 0; color: #000"><i class="fa fa-file-text-o" title="Product Listing"></i> Manage Products </h3></a>
        </div>
        
        <div class="col-sm-3" style="text-align: right">            
            <a href="cpcatalogue/product/add" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-plus-square" title="New Product"></i> Add New Product </h3></a>            
        </div>
    </div>
</header>
<div class="col-sm-12 col-xs-12">
    <div class="col-sm-12 pad-top10" align="center">
        <?php
            $FORM_JS = '  name="filter_frm" id="filter_frm"';
            echo form_open(base_url('cpcatalogue/ajax/product/'), $FORM_JS);
        ?>
        <h4 align="center" style="font-size: 14px; text-align: left;" class="mar-top0">Search by category or product name:</h4>
            <div class="row pad-top10">
                <div class="col-sm-12 text-left">        
                    <div class="product-name-field-block">
                        <label><strong>Category Name:</label>
                        <?php                   
                            $js = ' class="form-control" id="category" ';
                            echo form_dropdown('category', $options, '', $js); 
                        ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2"> 
                        <h4 class="label-or-block" style="padding-top: 16px;font-weight: 900;color: #f27733;"> OR </h4>
                    </div>
                    <div class="col-sm-4"> 
                        <button onclick="resetForm();" type="reset" value="Reset" class="btn btn-primary form-control" style="margin-top:24px;">Reset</button>
                    </div>
                    <div class="col-sm-6">
                        <input type="submit" name="button" 
                        id="button" value="Search" class="btn btn-primary form-control" style="margin-top:24px;">
                    </div>
                    <div class="clearfix"></div>
                    <div class="product-name-field-block">
                        <label>Product Name:</label>
                        <input  style="width:100%" type='text' name='prodName' id='prodName' value='<?= $prodName; ?>' 
                            placeholder='Search via name' class="form-control" />
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
        <script>
            $( "#filter_frm" ).submit(function( event ) {
                var actionUrl  = $(this).attr('action');
                $.get(actionUrl,{'category':$('#category').val(),
                                    'prodName':$('#prodName').val(),
                                    'ajax':'1','offset':'0',
                                },function(data){
                                    data = JSON.parse(data);
                                    if(data.success){                           
                                        $('#products-list-div').html(data.html);                            
                                    }
                            });
                  event.preventDefault();
            });
            function resetForm(){                
                $('#category').val( 0 );
                $('#prodName').val( "" )
                $( "#filter_frm" ).trigger( 'submit' );                
            }
        </script>
    </div>
    <?php        
        if (count($products) == 0) {
                $this->load->view(THEME.'messages/inc-norecords');
            echo "</div>";
            return;
        }
    ?>
</div>
<div class="clearfix"></div>
<div class="col-lg-12 pad-top30" id="products-list-div">
    <?= $products_list_view; ?>
</div>
<!-- Start Jquery Model popup Window -->
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">  
</div>
<!-- End Jquery Model popup Window -->
