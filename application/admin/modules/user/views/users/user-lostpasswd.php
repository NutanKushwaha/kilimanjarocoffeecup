<h1>Retrieve Password</h1>
<p>Input your Username in the box below and we will send your password .</p>
<?= form_open("welcome/lostpasswd/", ' name="lpFrm" id="lpFrm" ');  ?>
	<?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td width="12%">Username <span class="error"> *</span></td>
			<td width="88%"><input name="username" type="text" class="textfield" id="username" autocomplete="off" size="40"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><small>Fields marked with <span class="error">*</span> are required.</small></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="button" id="button" value="Submit"></td>
		</tr>
	</table>
<?= form_close(); ?>