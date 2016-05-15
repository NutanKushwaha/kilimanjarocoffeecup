<h1>Add Related Department</h1>
<div id="ctxmenu"><a href="cpcatalogue/related_department/index/<?php echo $product['product_id']; ?>">Manage Related Departments</a></div>
<?php 
	$this->load->view('inc-messages');
	$FORM_JS = ' name="addcatform" id="addcatform" ';
	echo form_open( current_url($product['product_id']),  $FORM_JS);
?>
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th>Related Department <span class="error"> *</span></th>
            <td><?php echo form_dropdown('related_department_name', $options, set_value('related_department_name'), ' class="textfield"'); ?></td>
        </tr>
    </table>

    <p align="center"><input type="hidden" name="product_id" id="product_id" value="<?php echo $product['product_id']; ?>"></p>
    <p align="center"><input type="submit" name="button" id="button" value="Submit"></p>
<?php echo form_close(); ?>
</div>
