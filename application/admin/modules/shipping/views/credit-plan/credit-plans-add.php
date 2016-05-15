<h1>Add Credit Plan</h1>
<div id="ctxmenu"><a href="settings/creditplans/index">Manage Credit Plans</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<form action="settings/creditplans/add" method="post" enctype="multipart/form-data" name="addcatform" id="addcatform">
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th width="20%">Plan Name <span class="error">*</span></th>
            <td width="80%"><input name="plan_name" type="text" class="textfield" id="plan_name" value="<?php echo set_value('plan_name'); ?>" size="40"></td>
        </tr>
		
        <tr>
            <th>Credits <span class="error">*</span></th>
            <td><input name="credits" type="text" class="textfield" id="credits" value="<?php echo set_value('credits'); ?>" size="40" /></td>
        </tr>
        <tr>
            <th>Price<span class="error">*</span></th>
            <td><input name="price" type="text" class="textfield" id="price" value="<?php echo set_value('price'); ?>" size="40" /></td>
        </tr>
       
        <tr>
            <th>&nbsp;</th>
            <td><input type="submit" name="button" id="button" value="Submit"></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>Fields marked with <span class="error">*</span> are required.</td>
        </tr>
    </table>
</form>