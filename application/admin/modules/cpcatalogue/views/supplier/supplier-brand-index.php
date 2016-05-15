<header class="panel-heading">
    <div class="row">
         <div class="col-sm-9">
            <h3 style="margin: 0;">  <i class="fa fa-bars"></i> Supplier <small>(<?= $supplier_name; ?>)</small> Brands</h3>
        </div>
    </div>
</header>
<?php 
  $hidden = [];
  $hidden[ 'brand_id' ] = $brand_id;
  $hidden[ 'supplier_id' ] = $supplier_id;
  echo form_open(current_url() , '', $hidden); 
?>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th width="10%"><label>Brand Name <span class="error"> *</span></label></th>
            <td width="70%">
                <input name="brand_name" 
                type="text" class="textfield form-control" 
                id="brand_name" value="<?php echo set_value('brand_name', $brand_det[ 'brand_name' ]); ?>" size="40" required>
            </td>
            <td width="20%">
                <input style="margin-bottom: 14px;" class="btn btn-primary pull-right" type="submit" name="button" id="button" value="Submit">
            </td>
        </tr>
</table>
<?php echo form_close(); ?>
<div class="clearfix"></div>
<div class="col-lg-12 padding-0 mar-top15" id="store-view-div">
    <?= $supplier_brand_list; ?>
</div>
