<h1>Edit <?php echo $widget['widget_type']; ?> Widget: <?php echo $page['page_title']; ?></h1>
<div id="ctxmenu"><a href="cms/page/index/">Manage Pages</a> | <a href="cms/widgets/index/<?php echo $page['page_id']; ?>">Manage Widgets</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>

<form action="cms/widgets/edit/<?php echo $widget['widget_id']; ?>" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
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
            <td><input name="menu_page_id" type="hidden" id="menu_page_id" value="<?php echo $page['page_id']; ?>" />&nbsp;</td>
            <td align="center">Fields marked with <span class="error">*</span> are required.</td>
        </tr>
    </table>

    <p style="text-align:center"><input name="page_id" type="hidden" id="page_id" value="<?php echo $page['page_id']; ?>" />
		<input type="submit" name="button" id="button" value="Submit"></p>

</form>
