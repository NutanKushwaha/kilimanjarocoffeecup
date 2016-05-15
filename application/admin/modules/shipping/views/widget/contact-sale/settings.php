<h1>Edit Page</h1>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data" name="regFrm" id="regFrm">
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="formtable">
	<tr>
		<th width="40%">Vehicle Year</th>
		<td width="60%"><input name="vehicle_year" type="text" class="textfield" id="vehicle_year" value="<?php echo set_value('vehicle_year', $settings['vehicle_year']); ?>" size="40"></td>
	</tr>
	<tr>
		<th width="40%">Vehicle Make</th>
		<td width="60%"><input name="vehicle_make" type="text" class="textfield" id="vehicle_make" value="<?php echo set_value('vehicle_make', $settings['vehicle_make']); ?>" size="40"></td>
	</tr>
	<tr>
		<th width="40%">Vehicle Model</th>
		<td width="60%"><input name="vehicle_model" type="text" class="textfield" id="vehicle_model" value="<?php echo set_value('vehicle_model', $settings['vehicle_model']); ?>" size="40"></td>
	</tr>
	<tr>
	  <th>Form Name</th>
	  <td><?php echo form_dropdown('form_alias', $form_emails, set_value('form_alias', $settings['form_alias']), ' class="textfield width_30"'); ?></td>
	</tr>
	<tr>
	<th width="40%">Email Type</th>
	<td width="60%">
		<input type="radio" name="is_sdf" value="1" <?php echo set_radio("is_sdf", 1, ($settings['is_sdf'] == 1)); ?> />SDF
		<input type="radio" name="is_sdf" value="0" <?php echo set_radio("is_sdf", '0', ($settings['is_sdf'] == 0)); ?> />Simple
	</td>
</tr>
</table>
<p style="text-align:center"><input type="submit" name="button" id="button" value="Save"></p>
</form>