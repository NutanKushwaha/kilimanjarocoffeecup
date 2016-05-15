<style>
    .error{
        color: red;
    }
</style>
<!--<h1 class="mar-bot25"> <i class="fa fa-plus-square"></i> Add Product Image</h1>-->

<div id="ctxmenu">
    <div class="col-sm-9">
        <a href="cpcatalogue/product_images/index/<?php echo $product['product_id']; ?>"> <h1 class="mar-bot15" style="color: #555;"><i class="fa fa-picture-o"></i> Manage Images </h1> </a>
    </div>
    <div class="col-sm-3 text-right">
            <a href="cpcatalogue/product_images/index/BAKPAPER" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-share" title="Attributes"></i> Back</h3></a>
    </div>
</div>

    <?php $this->load->view(THEME.'messages/inc-messages'); ?>
<form action="cpcatalogue/product_images/add/<?php echo $product['product_id']; ?>" method="post" enctype="multipart/form-data" name="addcatform" id="addcatform">
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable table-bordered">
        <tr>
            <th width="26%">Title <span class="error">*</span></th>
            <td width="74%"><input name="image_title" type="text" class="textfield" id="image_title" value="<?php echo set_value('image_title'); ?>" size="40" /></td>
        </tr>
         <tr>
            <th>Image </th>
            <td><input type="file" name="image" id="image" class="textfield" accept="image/*">                
                <small>Only .jgp,.gif,.png images allowed</small>
            </td>            
        </tr>
        <tr>
            <td><input name="v_image" type="hidden" id="v_image" value="1" /></td>
            <td>
                <input type="submit" name="button" id="button" value="Submit" class="btn btn-primary mar-top10 mar-bot10">
                <br/>
                Fields marked with <span class="error" style="color:red;">*</span> are required.
            </td>
        </tr>
<!--        <tr>
            <td>&nbsp;</td>
            <td>Fields marked with <span class="error">*</span> are required.</td>
        </tr>-->
    </table>
<?php echo form_close(); ?>
