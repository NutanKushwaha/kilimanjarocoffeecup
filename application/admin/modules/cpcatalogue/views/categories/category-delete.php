<h1></h1>
<div id="ctxmenu"><a href="cpcatalogue/category/index">Manage Categories</a> | <a href="cpcatalogue/category/category_delete/<?php echo $current_category['category_id']?>" onclick="return confirm('Are you sure you want to delete category and all its products?');">Delete Category</a></div>
<?php $this->load->view(THEME.'messages/inc-messages');?>
  <h3>Select new category for current category's product</h3>
<?php
  $FORM_JS = '  name="addcatform" id="addcatform" ';
  echo form_open(current_url($current_category['category_id']) , $FORM_JS);
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
      <th>Select New Primary Category <span class="error"> *</span></th>
      <td><?php echo form_dropdown('category_id', $interested_in, set_value('category_id'), ' class="textfield"'); ?></td>
    </tr>
    <tr>
      <td></td>
      <td width="65%"><input type="submit" name="button" id="button" value="Submit"></td>
    </tr>
  </table>
<?php echo form_close(); ?>
<h4>Or Delete Category And All Its Products</h4>
<div class="tableWrapper">  
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable" id="">
    <tr>
      <th width="34%"><a href="cpcatalogue/category/category_delete/<?php echo $current_category['category_id']?>" onclick="return confirm('Are you sure you want to delete category and all its products?');">Delete Category</a></th>
      <td width="66%"><?php echo  $current_category['category'];?></td>
    </tr>
    <tr>
      <th width="34%">&nbsp;</th>
      <td width="66%"></td>
    </tr>
  </table>
 </div>