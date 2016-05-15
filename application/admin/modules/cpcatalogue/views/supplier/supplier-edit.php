<div class="col-sm-6 pad-bot20 pad-left0"> <h1> <i class="fa fa-pencil-square-o"></i> Edit Supplier</h1></div>
<div class="col-sm-6 ">
    <div id="ctxmenu" class="text-right"><a href="cpcatalogue/supplier" class="btn btn-primary"> <i class="fa fa-edit"></i> Manage Suppliers </a> </div>
</div>
<?php 
    $this->load->view('inc-messages');
    $FORM_JS = ' name="addcatform" id="addcatform" ';
    echo form_open(current_url($supplier['supplier_id']), $FORM_JS); 
?>
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="table-bordered formtable">
        <tr>
            <th width="20%" style="vertical-align: middle;padding-left: 10px;font-size: 14px;"><b>Supplier Name<span class="error" style="color: red;"> *</span></b></th>
            <td width="80%" style="vertical-align: middle; padding-top: 10px;"><input name="supplier_name" type="text" class="textfield" id="supplier_name" value="<?php echo set_value('supplier_name', $supplier['supplier_name']); ?>" size="40">
                <br/>
                <p style="font-size: 12px;margin-bottom: 0;">Fields marked with <span class="error" style=" color: red;">*</span> are required.</p>
            </td>
        </tr>
        <tr>
            <td><input type="hidden" name="supplier_id" id="supplier_id" value="<?php echo $supplier['supplier_id']; ?>" />
        </tr>
<!--        <tr>
            <td><p>Fields marked with <span class="error" style=" color: red;">*</span> are required.</p></td>
        </tr>-->
    </table>
    <p align="center"><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>
<?php echo form_close(); ?>