<h1>Submit Your Enquiry</h1>
<?php $this->load->view('inc-messages'); ?>
<?php
if ($product == 0) {
    echo '<p>There are no items in your inquiry basket.</p>';
    return;
}
?>
<form action="inquiry/quick_inquiry/<?php echo $product['product_id']; ?>" method="post" enctype="multipart/form-data" name="profile_frm" id="profile_frm">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th>Product</th>
            <td><input name="product" type="text" class="textfield width_90" id="product" value="<?php echo $product['product_name']; ?>" readonly="readonly" /></td>
        </tr>
        <tr>
            <th width="33%">Company Name <span class="error">*</span></th>
            <td width="67%"><input name="company_name" type="text" class="textfield width_90" id="company_name" value="<?php echo set_value('company_name'); ?>" /></td>
        </tr>
        <tr>
            <th>Your Title <span class="error">*</span></th>
            <td><input name="title" type="text" class="textfield width_90" id="title" value="<?php echo set_value('title'); ?>" /></td>
        </tr>
        <tr>
            <th>Message</th>
            <td><textarea name="message" cols="10" rows="5" class="textfield textarea width_90" id="message" style="height:auto"><?= set_value('message'); ?></textarea></td>
        </tr>
        <tr>
            <th>First Name <span class="error">*</span></th>
            <td><input name="first_name" type="text" class="textfield width_90" id="first_name" value="<?php echo set_value('first_name'); ?>" /></td>
        </tr>
        <tr>
            <th>Last Name <span class="error">*</span></th>
            <td><input name="last_name" type="text" class="textfield width_90" id="last_name" value="<?php echo set_value('last_name'); ?>" /></td>
        </tr>
        <tr>
            <th>Email <span class="error">*</span></th>
            <td><input name="email" type="text" class="textfield width_90" id="email" value="<?php echo set_value('email'); ?>" /></td>
        </tr>
        <tr>
            <th>Telephone <span class="error">*</span></th>
            <td><input name="phone" type="text" class="textfield width_90" id="phone" value="<?php echo set_value('phone'); ?>" /></td>
        </tr>
        <tr>
            <th>Fax <span class="error">*</span></th>
            <td><input name="fax" type="text" class="textfield width_90" id="fax" value="<?php echo set_value('fax'); ?>" /></td>
        </tr>
        <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        <tr>
                <th>&nbsp;</th>
                <td>Fields marked with <span class="error">*</span> are required.</td>
            </tr>
        <tr>
            <th>&nbsp;</th>
            <td><input type="image" name="button" src="images/button-submit.png"></td>
        </tr>
    </table>
</form>