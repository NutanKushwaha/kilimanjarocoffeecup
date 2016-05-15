<h1>Edit Product Image</h1>
<div id="ctxmenu"><a href="cpcatalogue/product_images/index/<?php echo $image['product_id']; ?>">Manage Images</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<form action="cpcatalogue/product_images/edit/<?php echo $image['product_image_id']; ?>" method="post" enctype="multipart/form-data" name="addcatform" id="addcatform">
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th width="26%">Title <span class="error">*</span></th>
            <td width="74%"><input name="image_title" type="text" class="textfield" id="image_title" value="<?php echo set_value('image_title', $image['image_title']); ?>" size="40" />
            </td>
        </tr>
        <tr>
            <th> Image <span class="error">*</span></th>
            <td><?php if ($image['image'] != '') { ?>
                    <img src="<?php echo  $image['image']; ?>"border="0" width="200px" /><br />
                <?php } ?>
                <input name="image" type="file" id="image" size="42" class="textfield" />
                <br />
                <small>Only .jgp,.gif,.png images allowed</small> 
                <br />
                <small>Apply image to all products <input type="checkbox" name="img_products" value="1" /></small>                 
			</td>
        </tr>

        <tr>
            <td><b><input name="v_image" type="hidden" id="v_image" value="1" /></b></td>
            <td><input type="submit" name="button" id="button" value="Submit"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Fields marked with <span class="error">*</span> are required.</td>
        </tr>
    </table>
<?php echo form_close(); ?>
