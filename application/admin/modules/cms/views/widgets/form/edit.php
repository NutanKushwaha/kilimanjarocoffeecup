<h1>Edit <?php echo $widget['widget_type']; ?> Widget: <?php echo $page['page_title']; ?></h1>
<div id="ctxmenu"><a href="cms/page/index/">Manage Pages</a> | <a href="cms/widgets/index/<?php echo $page['page_id']; ?>">Manage Widgets</a></div>

<?php $this->load->view(THEME.'messages/inc-messages'); ?>

<form action="cms/widgets/edit/<?php echo $widget['widget_id']; ?>" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
    <div id="tabs">
        <ul>
            <li><a href="<?php echo current_url(); ?>#tabs-1">Details</a></li>
            <li><a href="<?php echo current_url(); ?>#tabs-2">Template</a></li>
        </ul>

        <div id="tabs-1">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
                <tr>
                    <th width="15%">Widget Location <span class="error">*</span></th>
                    <td width="85%"><?php echo form_dropdown('widget_location_id', $locations, set_value('widget_location_id', $widget['widget_location_id']), ' class="textfield"'); ?></td>
                </tr>
                <tr>
                    <th>Widget Name <span class="error">*</span></th>
                    <td><input type="text" name="widget_name" id="widget_name" class="textfield width_30"  value="<?php echo set_value('widget_name', $widget['widget_name']); ?>" /></td>
                </tr>
                <!--<tr>
                    <th>Widget Alias <span class="error">*</span></th>
                    <td><input type="text" name="widget_alias" id="widget_alias" class="textfield width_30"  value="<?php echo set_value('widget_alias', $widget['widget_alias']); ?>" /></td>
                </tr>-->
                <tr>
                    <th>Form <span class="error">*</span></th>
                    <td><?php echo form_dropdown('form_id', $forms, set_value('form_id', $widget_data['form_id']), ' class="textfield"'); ?></td>
                </tr>

                <tr>
                    <td><input name="menu_page_id" type="hidden" id="menu_page_id" value="<?php echo $page['page_id']; ?>" />&nbsp;</td>
                    <td align="center">Fields marked with <span class="error">*</span> are required.</td>
                </tr>
            </table>
        </div>

        <div id="tabs-2">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
                <tr>
                    <td><textarea name="widget_form_template" class="textfield width_99" id="widget_form_template" rows="5"><?php echo set_value('widget_form_template', $widget_data['widget_form_template']); ?></textarea></td>
                </tr>

            </table>
        </div>

        <p style="text-align:center"><input name="v_image" type="hidden" id="v_image" value="1" /><input name="page_id" type="hidden" id="page_id" value="<?php echo $page['page_id']; ?>" /><input type="submit" name="button" id="button" value="Submit"></p>
    </div>

</form>
