<header class="panel-heading">
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-cart-plus fa-2x"></i>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; ">Bulk Product Image Upload</h3>
        </div>
    </div>
</header>

<div class="col-lg-12 padding-0" style="padding-top: 15px;">    
	<?php 
		$attr = [];
		$attr[ 'id' ] = 'form-prod-images';
		$attr[ 'name' ] = 'form-prod-images';
		echo form_open_multipart("", $attr);		
	?>
        <table id="prod_img_add"  class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td> Report Name</td>
                    <td>
						<?php
							$attr = [];
							$attr[ 'name' ] = 'report_name';
							$attr[ 'id' ] 	= 'report_name';							
						echo form_input( $attr );
						?>
                    </td>
                </tr>				
                <tr>
                    <td> Images</td>
                    <td>
						<?php echo form_upload('uploadedimages[]','','multiple'); ?>
						<small> allowed format gif|jpg|png</small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
						<input name="upload_data" type="submit" value="submit" class='btn btn-primary'>	
                    </td>                    
                </tr>            
            </tbody>
        </table>
    <?php 
		echo form_close();
    ?>
</div>
