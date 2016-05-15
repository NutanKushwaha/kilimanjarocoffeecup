<h1>Edit Form</h1>
<div id="ctxmenu"><a href="settings/form/index/">Forms Settings</a></div>
<?php $this->load->view(THEME.'messages/inc-messages');?>
<form action="settings/form/edit/<?php echo $form_details['form_alias'];?>" method="post" enctype="multipart/form-data" name="addalbumform" id="addalbumform">
  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
    <tr>
      <th width="114">Form Name</th>
      <td width="621"><?php echo $form_details['form_name'];?></td>
    </tr>
    <tr>
      <th width="114">Form Alias</th>
      <td width="621"><?php echo $form_details['form_alias'];?></td>
    </tr>
    <tr>
      <th width="114">Email Address <span class="error"> *</span></th>
      <td width="621"><textarea name="email_address" cols="40" class="textfield" id="email_address" style="width:80%"><?php echo set_value('email_address', $form_details['email_address']);?></textarea></td>
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

