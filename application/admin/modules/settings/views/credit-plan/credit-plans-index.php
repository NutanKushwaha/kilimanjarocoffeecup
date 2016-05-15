<h1>Manage Credit Plans</h1>
<div id="ctxmenu"><a href="settings/creditplans/add">Add Credit Plan</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<?php

if (count($credit_plans) == 0) {
	$this->load->view(THEME.'messages/inc-norecords');
} else {

	?>
	<div class="tableWrapper">
		<table width="100%" border="0" cellpadding="2" cellspacing="0">
			<tr>
				<th width="30%">Plan Name</th>
				<th width="30%">Credits</th>
				<th width="20%">Price</th>
				<th width="20%">Action</th>
			</tr>
			<?php
			foreach ($credit_plans as $item) {

				//if($)
			?>
				<tr class="<?php echo alternator('', 'alt'); ?>">
					<td><?php echo $item['plan_name']; ?></td>
					<td valign="top"><?php echo $item['credits']; ?></td>
					<td valign="top"><?php echo MCC_CURRENCY_SYMBOL.$item['price']; ?></td>
					<td valign="top"><a href="settings/creditplans/edit/<?php echo $item['credit_plan_id']; ?>">Edit</a> | <a href="settings/creditplans/delete/<?php echo $item['credit_plan_id']; ?>" onclick="return confirm('Are you sure you want to delete this Credit Plan?');">Delete</a> </td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>
