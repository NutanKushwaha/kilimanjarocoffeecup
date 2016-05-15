<table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">
    <tr>
        <th width="15%">Banner Image</th>
        <td width="85%"><?php if ($banner['page_setting'] && $banner['page_setting_value'] != '') { ?> <img src="<?php echo $this->config->item('PAGE_DATA_IMAGE_URL') . $banner['page_setting_value']; ?>" border="0" width="200" /><br/><?php } ?>
            <input type="file" name="image" id="image"><br />
            <small>Only .jgp,.gif,.png images allowed</small></td>
    </tr>
    <tr>
        <th>Link</th>
        <td><input type="text" name="link" id="link" class="textfield" size="35px" value="<?php echo set_value('link', $banner_link['page_setting_value']); ?>"></td>
    </tr>
    <!--<tr>
        <th>New Window</th>
        <td><input type="radio" name="new_window" value="1" <?php //echo set_radio("new_window", 1, ($banner_new_window['page_setting_value'] == 1)); ?> />Yes
            <input type="radio" name="new_window" value="0" <?php //echo set_radio("new_window", '0', ($banner_new_window['page_setting_value'] == 0)); ?> />No</td>
    </tr>-->
    <tr>
        <th>Show Banner</th>
        <td><input type="radio" name="show_banner" value="1" <?php echo set_radio('show_banner', 1, ($show_banner['page_setting_value'] == 1)); ?> />
            Yes
            <input type="radio" name="show_banner" value="0" <?php echo set_radio('show_banner', 0, ($show_banner['page_setting_value'] == 0)); ?> />
            No</td>
    </tr>
    <tr>
        <td><input type="hidden" name="image_v" id="image_v" value="1"></td>
        <td></td>
    </tr>
</table>