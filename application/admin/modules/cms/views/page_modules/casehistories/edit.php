<table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">
    <tr>
		<th width="15%" style="vertical-align: top">Case studies Category</th>
		<td width="85%">
			<?php echo form_dropdown('casestudy_category_alias', $categories, set_value('casestudy_category_alias', $current_category['page_setting_value']), ' id="casestudy_category_alias" class="textfield"'); ?>
			<p><small>Select the case studies category you want to display on this page.</small></p>
		</td>
	</tr>
</table>
