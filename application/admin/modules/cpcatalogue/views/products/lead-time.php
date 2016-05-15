<h1>Lead time</h1>
<div id="ctxmenu"><a href="cpcatalogue/product">Manage Products</a> | <a href="cpcatalogue/category/index">Manage Categories</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<div style="float: left; width: 100%">	
	<?php
		$FORM_JS = '  name="leadtimeform" id="leadtimeform" ';
		echo form_open(current_url(), $FORM_JS);
	?>	
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
			<tr>
				<td width="15%">
					Lead time
				</td>
				<td width="50%">
					<input type="text" name="leadTimeText" value="" placeholder="Add lead time"/>
					<input type="hidden" name="prodid" value="<?= $prodid; ?>" />
				</td>
				<td width="35%">
					<input type="submit" name="button" id="button" value="Submit">
				</td>
			</tr>
		</table>
	<?php 
		echo form_close();
		$FORM_JS = ' name="leadtimeform" id="leadtimeform" ';
		echo form_open(current_url(), $FORM_JS);
	?>
			<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
				<tr>
					<th width="40%">Label</th>
					<th width="30%">Active</th>
					<th width="30%">Action</th>
				</tr>
				<?php foreach($addleads as $lead){ ?>
				<tr>
					<td>
						<?= $lead['leadlabel']; ?>
					</td>
					<td>
						<a href="cpcatalogue/product/activeleadtime/<?php echo $lead['id']; ?>/<?php echo $lead['selected'] ? 0 : 1?>">
							<?php echo $lead['selected'] ? 'De-active' : 'Active'; ?>
						</a>
					</td>
					<td>
						<a href="cpcatalogue/product/deleteleadtime/<?= $lead['id'];?>">
							Delete
						</a>
					</td>
				</tr>
				<?php } ?>
			</table>
	<?php echo form_close(); ?>
</div>
