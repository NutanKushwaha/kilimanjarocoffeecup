<table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">
    <tr>
		<th width="15%">Slideshow</th>
		<td width="85%"><?php echo form_dropdown('slideshow_id', $slideshow, set_value('slideshow_id', $current_slideshow['page_setting_value']), ' id="slideshow_id" class="textfield"'); ?></td>
	</tr>
	<tr>
	<th>Show Slideshow</th>
	<td><input type="radio" name="show_slideshow" value="1" <?php echo set_radio('show_slideshow', 1, ($show_slideshow['page_setting_value'] == 1)); ?> />
		Yes
		<input type="radio" name="show_slideshow" value="0" <?php echo set_radio('show_slideshow', 0, ($show_slideshow['page_setting_value'] == 0)); ?> />
		No</td>
	</tr>
</table>
