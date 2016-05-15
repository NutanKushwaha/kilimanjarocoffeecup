<header class="panel-heading">
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-cart-plus fa-2x"></i>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; ">Bulk Product Upload</h3>
        </div>
    </div>
</header>

<div class="col-lg-12 padding-0" style="padding-top: 15px;">    
	<?php 
		$attr = [];
		$attr[ 'id' ] = 'form-special-price';
		$attr[ 'name' ] = 'form-special-price';
		echo form_open_multipart("", $attr)
	?>
        <table id="special_price_add"  class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td> CSV</td>
                    <td>
						<input type="file" name="csv" accept=".csv"><input type="hidden" name="csvfile" value="1" >
						<small> Max Size=<?= $post_size; ?></small>
                    </td>
                </tr>            
                <tr>
                    <td colspan="2">
						<input type="submit" value="submit" class='btn btn-primary'>						
                    </td>                    
                </tr>            
            </tbody>
        </table>
    <?php 
		echo form_close();
    ?>
</div>
