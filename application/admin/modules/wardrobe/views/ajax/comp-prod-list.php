<?php
    if (count($products) == 0) {
        $this->load->view(THEME.'messages/inc-norecords');
    } else {    
    $attr = [];
    $attr[ 'type' ] = 'hidden';
    $attr[ 'id' ] = 'current_offset';
    $attr[ 'name' ] = 'current_offset';
    $attr[ 'value' ] = $current_offset;
    echo form_input( $attr );
?>
<div class="tableWrapper">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered">
            <tr>
                <th width="30%" class="border" style="text-align:center">Name</th>
                <th width="20%" class="border" style="text-align:center">Category</th>
                <th width="15%" class="border" style="text-align:center">Type</th>
                <th width="10%" class="border" style="text-align:center">Qty</th>
                <th width="30%" class="border" style="text-align:center">Action</th>
            </tr>
            <?php foreach ($products as $item) { ?>
                <tr class="<?= alternator('', 'alt'); ?>" style="height:50px;">
                    <td id="<?= strtolower($item['product_name']); ?>"><?= ucfirst($item['product_name']); ?></td>
                    <td><?= $item['category']; ?></td>
                    <td><?= $item['product_type_id'] == 1 ? "Simple" : "Config" ; ?></td>
                    <td><?php
                            $qty_view = '';
                            if( $item['product_type_id'] == 1 ){
                                $row_qty = array(
                                    'name'  => 'qty['.$item['product_sku'].']',
                                    'id'    => 'qty-'.$item['product_sku'],
                                    'value' => '0',
                                    'type'  => 'number',
                                    'style' => 'width:90%',
                                    'class' => 'userQty',                                    
                                );
                                $qty_view = form_input($row_qty);
                            }else{
                                $qty_view = '----';
                            }
                            echo $qty_view;
                        ?>
                    </td>
                    <td style="text-align:right;">
                    <div class="attr-set-list">
                        <?php 
                            $attr = [];
                            $attr[ 'type' ]     =    'button';
                            $attr[ 'name' ]     =   'cart-action';
                            $attr[ 'class' ]    =   'btn btn-info simpleCart';
                            $attr[ 'value' ]    =   'Add to cart';
                            $attr[ 'data-product_id'    ] =   $item['product_id'];
                            $attr[ 'data-product_sku'   ] =   $item['product_sku'];
                            if( $item['product_type_id'] == 2 ){
                                $attr[ 'class' ]    =   'btn btn-info configView';
                                $attr[ 'value' ]    =   'View';
                            }
                            echo form_input( $attr );
                        ?>
                    </div>
                    </td>
                </tr>
            <?php } ?>
            <script>
                $('.simpleCart').on('click', function(event){
                    var qtyVal = 0;
                    var thisObj     =   $( this );
                    var product_sku =   thisObj.data( 'product_sku' );
                    var qty         =   "#qty-"+product_sku;
                    var alertMsg    =   "Sorry quantity cannot be 0";
                    var product_id  =   thisObj.data( 'product_id' );
                    qtyVal      =   $( qty ) .val();
                    if( qtyVal ) {
                        $.get('<?= base_url("wardrobe/ajax/wardrobe/addToCart"); ?>',
                                {   'qty'       :qtyVal,
                                    'product_id':product_id,
                                    'product_sku':product_sku,
                                    'current_offset':$('#current_offset').val(),
                                },function(data){
                                    data = JSON.parse(data);
                                    console.log( data );
                                    return false;                                        
                                    if(data.success){
                                        $("#cartTotal").html( data.cartTotal );
                                        $("#wardrobecart").html( data.wardrobecart );
                                        $('#addProd').modal('hide');
                                    }
                                });
                    }                    
                    $('#comMsgModalTitle').html('Add cart');
                    $('#comMsgModalBody').html( alertMsg );
                    $('#comMsgModal').modal('show');
                    event.preventDefault();
                });
                $('.configView').on('click', function(event){
                    var thisObj =   $( this );
                    var product_sku =   thisObj.data( 'product_sku' );
                    var product_id  =   thisObj.data( 'product_id' );                    
                    $.get('<?= base_url("wardrobe/ajax/wardrobe/getConfProdViewForShop"); ?>',
                            {   'product_id':product_id,
                                'product_sku':product_sku,
                                'category':$('#category').val(),
                                'prodName': $('#prodName').val(),
                                'current_offset':$('#current_offset').val(),
                            },function(data){
                                data = JSON.parse(data);
                                console.log( data );
                                return false;
                                if(data.success){
                                    $('#products-list-div').html(data.html);
                                }
                        });
                    event.preventDefault();
                });                
            </script>
    </table>
</div>
<div class="clearfix"></div>
<div class="ajax-pagination" style="text-align:center;">
    <ul class="pagination">
         <?= $pagination;?> 
    </ul>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.userQty').on('change', function( event ){
            if( $(this).val() < 0){
                $(this).val(0);
            }
            event.preventDefault();
        });
    });
</script>
<?php } ?>
