<h1>Add Block :<?php echo $page_details['page_title']; ?><?php
    if ($page_details['language_code'] != 'en') {
        echo '(' . $page_lang['language'] . ')';
    }
    ?></h1>
<div id="ctxmenu"><a href="cms/page/index/">Manage Pages</a> | <a href="cms/block/index/<?php echo $page_details['page_id']; ?>">Manage Blocks</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<form action="cms/block/add/<?php echo $page_details['page_id']; ?>" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
    <div id="tabs">
        <!--<ul>
                <li><a href="<?php echo current_url(); ?>#tabs-1">Main</a></li>
                <<li><a href="<?php echo current_url(); ?>#tabs-2">Template </a>
        </ul>-->
        <div id="tabs-1">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
                <tr>
                    <th width="15%">Block Layout <span class="error">*</span></th>
                    <td width="85%"><?php echo form_dropdown('block_layout_id', $block_layout, set_value('block_layout_id'), ' class="textfield width_20"'); ?></td>
                </tr>
                <tr>
                    <th>Block Title <span class="error">*</span></th>
                    <td><input type="text" name="block_title" id="block_title" class="textfield width_30"  value="<?php echo set_value('block_title'); ?>" /></td>
                </tr>
                <tr>
                    <th>Block Alias </th>
                    <td><input type="text" name="block_alias" id="block_alias" class="textfield width_30"  value="<?php echo set_value('block_alias'); ?>" />&nbsp;(Will be auto-generated if left blank)</td>
                </tr>
                <tr>
                    <th>Block Image </th>
                    <td><input name="image" type="file" id="image" size="35" class="textfield">
                        <br />
                        <small>Only .jgp,.gif,.png images allowed</small> </td>
                </tr>
                <tr>
                    <th>Block Content</th>
                    <td><textarea name="block_contents" class="textfield width_99" id="block_contents" rows="5"><?php echo set_value('block_contents'); ?></textarea></td>
                </tr>
                <tr>
                    <th>Link</th>
                    <td><input type="text" name="link" id="link" class="textfield" size="35px" value="<?php echo set_value('link'); ?>"></td>
                </tr>
                <tr>
                    <th>Alt</th>
                    <td><input type="text" name="alt" id="alt" class="textfield" size="35px" value="<?php echo set_value('alt'); ?>"></td>
                </tr>
                <tr>
                    <th>New Window</th>
                    <td><input type="radio" name="new_window" value="1" <?php echo set_radio("new_window", '1'); ?> />Yes
                        <input type="radio" name="new_window" value="0" <?php echo set_radio("new_window", '0', TRUE); ?> />No</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center">Fields marked with <span class="error">*</span> are required.</td>
                </tr>
            </table>
        </div>
        <p style="text-align:center"><input name="v_image" type="hidden" id="v_image" value="1" /><input name="page_id" type="hidden" id="page_id" value="<?php echo $page_details['page_id']; ?>" /><input type="submit" name="button" id="button" value="Submit"></p>
    </div>
</form>
