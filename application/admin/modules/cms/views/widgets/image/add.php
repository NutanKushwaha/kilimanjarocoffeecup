<h1>Add <?php echo $widget_type['widget_type']; ?> Widget: <?php echo $page['page_title']; ?></h1>
<div id="ctxmenu"><a href="cms/page/index/">Manage Pages</a> | <a href="cms/widgets/index/<?php echo $page['page_id']; ?>">Manage Widgets</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>

<form action="cms/widgets/add_2/<?php echo $page['page_id']; ?>/<?php echo $widget_type['widget_type_id']; ?>" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th width="20%">Widget Location <span class="error">*</span></th>
            <td width="80%"><?php echo form_dropdown('widget_location_id', $locations, set_value('widget_location_id'), ' class="textfield"'); ?></td>
        </tr>
        <tr>
            <th>Widget Name <span class="error">*</span></th>
            <td><input type="text" name="widget_name" id="widget_name" class="textfield width_30"  value="<?php echo set_value('widget_name'); ?>" /></td>
        </tr>
        <!--<tr>
            <th>Widget Alias <span class="error">*</span></th>
            <td><input type="text" name="widget_alias" id="widget_alias" class="textfield width_30"  value="<?php echo set_value('widget_alias'); ?>" /></td>
        </tr>-->
		<tr>
            <th>Image <span class="error">*</span></th>
            <td><input type="file" name="image" id="image" class="textfield"></td>
        </tr>
        <tr>
            <th>Alt</th>
            <td><input type="text" name="image_alt" id="image_alt" class="textfield width_30" value="<?php echo set_value('image_alt'); ?>"></td>
        </tr>
        <tr>
            <th>Link</th>
            <td><input type="text" name="link_url" id="link_url" class="textfield width_30" value="<?php echo set_value('link_url'); ?>"></td>
        </tr>
		<tr>
            <th>Class</th>
            <td><input type="text" name="image_class" id="image_class" class="textfield width_30" value="<?php echo set_value('image_class'); ?>"></td>
        </tr>
        <tr>
            <th>New Window</th>
            <td><input type="radio" name="new_window" value="1" <?php echo set_radio("new_window", 1, true); ?> />Yes
                <input type="radio" name="new_window" value="0" <?php echo set_radio("new_window", 0); ?> />No</td>
        </tr>
        <tr>
            <td></td>
            <td align="center">Fields marked with <span class="error">*</span> are required.</td>
        </tr>
    </table>

    <p style="text-align:center"><input name="v_image" type="hidden" id="v_image" value="1" /><input name="page_id" type="hidden" id="page_id" value="<?php echo $page['page_id']; ?>" /><input type="submit" name="button" id="button" value="Submit"></p>

</form>
