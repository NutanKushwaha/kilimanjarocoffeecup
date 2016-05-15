<h1>Add Supplier</h1>
<div id="ctxmenu" class="text-right"><a href="cpcatalogue/supplier" class="btn btn-primary mar-bot15"><i class="fa fa-user"></i> Manage Suppliers</a></div>
<div class="clearfix"></div>
<?php 	
    $this->load->view(THEME.'messages/inc-messages');  
    $FORM_JS = ' name="addcatform" id="addcatform" ';
    echo form_open(current_url(), $FORM_JS); 
?>	
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th width="20%"><label>Supplier Name <span class="error"> *</span></label></th>
            <td width="80%"><input name="supplier_name" type="text" class="textfield form-control" id="supplier_name" value="<?php echo set_value('supplier_name'); ?>" size="40"></td>
        </tr>
    </table>
    <p align="center"><input type="submit" name="button" id="button" value="Submit"></p>
<?php echo form_close(); ?>
</div>
