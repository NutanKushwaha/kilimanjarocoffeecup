<h1>Dealers Setting</h1>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<form action="settings/dealer/index/" method="post" enctype="multipart/form-data" name="register_frm" id="register_frm">
<table width="100%" border="0" cellpadding="2" cellspacing="0" >
   <?php foreach($dealers as $key=>$val) {?>
       <tr >
        	<td><?php  echo form_checkbox('dealer_id[]', $key, in_array($key, $active_dealer)?true:false).'&nbsp;&nbsp;'.$val; ?></td>
       </tr>
   <?php }?>
   
   <tr >
 	 <td><input type="submit" name="button" id="button" value="Submit" /></td>
   </tr>
</table>
</form>