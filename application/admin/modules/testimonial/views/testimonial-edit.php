<h1>Edit Testimonial</h1>
<div id="ctxmenu"><a href="testimonial/">Manage Testimonials</a></div>
<?php $this->load->view('inc-messages'); ?>
<?php //echo validation_errors(); ?>
<?php //echo validation_errors(); 
$attributes = array('enctype'=>'multipart/form-data');
echo form_open('', $attributes); ?>
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th>Name<span class="error">*</span></th>
            <td><input name="name" type="text" class="textfield" id="name" value="<?= set_value('name', $testimonial['name']); ?>" size="40" /></td>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>" />
        </tr>
             <!--<tr>
          <th>URL Alias</span></th>
          <td><input name="url_alias" type="text" class="textfield" id="url_alias" value="<?php echo set_value('url_alias', $testimonial['url_alias']); ?>" size="40">&nbsp;(Will be auto-generated if left blank)</td>
        </tr>-->
        <tr>
            <th>Testimonial Image</th>
            <td><input name="image" type="file" class="textfield" id="image" value="<?php echo set_value('image', $testimonial['image']); ?>" size="40"><br />
                <input type="hidden" name="image1" value="<?= $testimonial['image'] ?>" />
                <img src="<?= $this->config->item('TESTIMONIAL_IMAGE_URL') . $testimonial['image'] ?>" width="75px" />
            </td>

        </tr>
        <tr>
            <th>Testimonial<span class="error">*</span></th>
            <td><textarea name="testimonial" id="testimonial" rows="4" style="width:80%" class="textfield"><?php echo set_value('testimonial', $testimonial['testimonial']); ?></textarea></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Fields marked with <span class="error">*</span> are required.</td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="button" id="button" value="Submit"></td>
        </tr>
    </table>
<?php echo form_close() ?>
