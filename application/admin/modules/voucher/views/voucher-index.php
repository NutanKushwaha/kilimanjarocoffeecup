<header class="panel-heading">
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-user fa-2x"></i>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Manage vouchers</h3>
        </div>
        <div class="col-sm-1" style="text-align: right">
            <a href="<?php echo base_url('voucher/add') ?>">
				<h3 style="cursor: pointer; margin: 0; color: #000">
					<i class="fa fa-plus-square" title="Add Voucher"></i>
				</h3>
			</a>
        </div>
    </div>
</header>
<div class="col-lg-12 padding-0" style="padding-top: 15px;">
<?php  echo form_open('voucher') ?>
        <div class="">
            <div class="col-md-6">
				<input 	type="text" name="code" 
						placeholder="Search..." 
						value="<?php echo $this->input->get('code'); ?>" class="form-control" />
			</div>
            
                    <?php 
						//~ <div class="col-md-4">
							//~ <div class="form-group">
							//~ echo form_dropdown('active', [ '' => 'Select Active/De-active', '1' => 'Active', '0' => 'De-Active' ], 1, [ 'id' => 'active', 'class' => 'form-control' ]  );
							//~ </div>
						//~ </div>							
					  ?>                    

            <div class="col-md-2">
				<input name="search_voucher" type="submit" value="Search" class="btn btn-primary">
			</div>
        </div>
<?php  echo form_close(); ?>    
</div>
<?php
if (sizeof($voucher) == 0) {
    $this->load->view(THEME.'messages/inc-norecords');
} else {
?>
<div class="col-lg-12 padding-0" style="padding-top: 15px;">
    <table  class="table table-bordered table-striped">
        <thead>
            <tr>                
				<th>Code</th>
				<th>Amount</th>
				<th>Order Price</th>
				<th>Act on</th>
				<th>Start</th>
				<th>Expires</th>				
				<th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (  isset( $voucher ) && $voucher ) { ?>

                <?php foreach ($voucher as $voucher_ind => $voucher_det): ?>
                    <tr>
                        <td><?= com_arrIndex($voucher_det, 'code'); ?></td>
                        <td><?= com_arrIndex($voucher_det, 'amount'); ?></td>
                        <td><?= com_arrIndex($voucher_det, 'min_order_value'); ?></td>
                        <td><?= com_arrIndex($voucher_det, 'vstyle'); ?></td>
                        <td><?= com_arrIndex($voucher_det, 'active_from'); ?></td>
                        <td><?= com_arrIndex($voucher_det, 'active_to'); ?></td>                        
                        <td>
							<?php
								$voucher_active = com_arrIndex($voucher_det, 'active');
								$new_status = 'enable';
								$new_status_text = 'Enable';
								if( $voucher_active ){
									$new_status = 'disable';
									$new_status_text = 'Disable';
								}
								$actions = anchor('voucher/'.$new_status.'/'.com_arrIndex($voucher_det, 'id'), $new_status_text);
								$actions .= '|'.anchor('voucher/edit/'.com_arrIndex($voucher_det, 'id'), 'Edit');
								$actions .= '|'.anchor('voucher/delete/' . com_arrIndex($voucher_det, 'id'), 'Delete');
								echo $actions;
							?>
						</td>						
                    </tr>
                <?php endforeach; ?>
            <?php }else { ?>
                <tr>
                    <td colspan="7">
                        <?php $this->load->view(THEME.'messages/inc-norecords') ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div  style="text-align:center;">
		<?php echo $pagination ?>
	</div>
</div>    
<?php } ?>
