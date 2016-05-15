<h1>Edit Success Form</h1>
<div id="ctxmenu"><a href="settings/successform/index/">Forms Settings</a></div>
<?php $this->load->view(THEME.'messages/inc-messages');?>
<form action="settings/successform/edit/<?php echo $form_details['form_alias'];?>" method="post" enctype="multipart/form-data" name="addalbumform" id="addalbumform">
  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
    <tr>
      <th width="20%">Form Name</th>
      <td width="80%"><?php echo $form_details['form_name'];?></td>
    </tr>
    <tr>
      <th>Form Alias</th>
      <td><?php echo $form_details['form_alias'];?></td>
    </tr>
    <tr>
      <th>Before Body Close</th>
      <td><textarea name="before_body_close" cols="40" class="textfield" id="before_body_close" style="width:80%; height: 200px"><?php echo set_value('before_body_close', $form_details['before_body_close']);?></textarea></td>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <td><input type="submit" name="button" id="button" value="Submit"></td>
    </tr>
  </table>
</form>

