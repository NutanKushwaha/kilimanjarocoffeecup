<div class="manage-template-form-container">
<div class="col-md-6 pad-left0">
	<h1 class="mar-top0">Edit Template</h1>	
</div>

<div class="col-md-6 pad-right0">
   <div id="ctxmenu" class="text-right"><a href="cms/template/" class="btn btn-primary"> <i class="fa fa-share"></i> Manage Template</a></div>
</div>
<div style="float: left; width: 100%" class="manage-template-form-container">
	<form action="cms/template/edit/<?php echo $template['template_id']; ?>" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
			<tr>
				<th width="15%">Template Name <span class="error">*</span></th>
				<td width="85%"><input type="text" name="template_name" id="template_name" class="textfield" size="40" value="<?php echo set_value('template_name', $template['template_name']); ?>" /></td>
			</tr>
			<tr>
				<th>Template Alias</th>
				<td><input type="text" name="template_alias" id="template_alias" class="textfield" size="40" value="<?php echo set_value('template_alias', $template['template_alias']); ?>" />
                <small>&nbsp;(Will be auto-generated if left blank)</small></td>
			</tr>
			<tr>
				<th>Template <span class="error">*</span></th>
				<td><textarea name="template_contents" cols="0" rows="50" style="width:90%" class="textfield" id="template_contents"><?php echo set_value('template_contents', $template_content); ?></textarea></td>
			</tr>
			<tr>
				<td><input type="hidden" name="template_id" id="template_id" value="<?php echo $template['template_id']; ?>"></td>
				<td></td>
			</tr>
			
		</table>
		<div>
				
				<div class="col-md-3" style="text-align: right;"><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></div>
				<div class="col-md-4" style="text-align: left;">Fields marked with <span class="error">*</span> are required.</div>
			</div>
	</form>
</div>
</div>