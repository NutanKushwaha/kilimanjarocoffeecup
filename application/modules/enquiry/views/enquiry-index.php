<h1>Submit Your Enquiry</h1>
<?php $this->load->view('inc-messages'); ?>
<div id="enquiry_form">
    <form action="enquiry" method="post" enctype="multipart/form-data" name="profile_frm" id="profile_frm">
        <table width="100%" cellpadding="0" cellspacing="0" class="grid_table" style="margin-left: auto; margin-right: auto">
            <tr>
                <td width="10%">First Name <span class="error">*</span></td>
                <td width="40%"><input name="first_name" type="text" class="textfield full_field" size="40" id="first_name" value="<?php echo set_value('first_name'); ?>" /></td>
				<td width="10%">Last Name <span class="error">*</span></td>
                <td width="40%"><input name="last_name" type="text" class="textfield half_field"  size="40" id="last_name" value="<?php echo set_value('last_name'); ?>" /></td>
            </tr>
            <tr>
                <td>Email <span class="error">*</span></td>
                <td> <input name="email" type="text" class="textfield full_field" size="40" id="email" value="<?php echo set_value('email'); ?>" /></td>
				<td>Telephone <span class="error">*</span></td>
                <td><input name="phone" type="text" class="textfield half_field"  size="40" id="phone" value="<?php echo set_value('phone'); ?>" /></td>

            </tr>
            <tr>
                <td>Fax <span class="error">*</span></td>
                <td> <input name="fax" type="text" class="textfield half_field" size="40" id="fax" value="<?php echo set_value('fax'); ?>" /></td>
            </tr>
			<tr>	
                <td>Message</td>
                <td colspan="3"><textarea name="message" cols="30" rows="10" class="textfield full_areafield" id="message" style="height:auto; width: 86%"><?= set_value('message'); ?></textarea> </td>
            </tr>
            <tr>
				<td>&nbsp;</td>
                <td colspan="3"><input type="submit" name="button"><br />Fields marked with <span class="error">*</span> are required.</td>
            </tr>              

        </table>
    </form>
    <div style="clear:both"></div>
</div>

<div style="padding-bottom: 20px;">&nbsp;</div>

<h2>Items included in your enquiry basket</h2>
<?php
if ($this->cart->total_items() == 0) {
	echo '<p>There are no items in your inquiry basket.</p>';
	return;
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="grid">
    <tr>
        <th width="60%">Item</th>
        <th width="20%">Quantity</th>
        <th width="20%">Subtotal</th>
    </tr>
	<?php foreach ($this->cart->contents() as $item) { ?>
		<tr>
			<td> <?php echo $item['name']; ?>
				<?php if ($this->cart->has_options($item['rowid']) == true) { ?> (
					<?php foreach ($this->cart->product_options($item['rowid']) as $key => $option) { ?>
						<?php echo "$key : $option , "; ?> 
					<?php }
					?>)
				<?php } ?></td>
			<td><?php echo $item['qty']; ?></td>
			<td><?php echo MCC_CURRENCY_SYMBOL . $item['subtotal']; ?></td>
		<?php } ?>
    </tr>	
</table>