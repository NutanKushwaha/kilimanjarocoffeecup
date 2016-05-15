<div style="float: left; width: 250px; padding-right: 50px">
	<?php if ($left_widget) { ?>
		<ul class="widgettree">
			<?php foreach ($left_widget as $item) { ?>
				<li id="widget_<?php echo $item['widget_id']; ?>"><div class="item"><div class="item_name"><?php echo $item['widget_name']; ?></div></div></li>
			<?php } ?>
		</ul>
	<?php } ?>
</div>
<div style="float: left; width: 100px; padding-right: 50px">
	<h2>Page Content...</h2>
</div>

<div style="float: left; width: 250px">
	<?php if ($right_widget) { ?>
	<ul class="widgettree">
		<?php foreach ($right_widget as $item) { ?>
			<li id="widget_<?php echo $item['widget_id']; ?>"><div class="item"><div class="item_name"><?php echo $item['widget_name']; ?></div></div></li>
		<?php } ?>
	</ul>
	<?php } ?>
</div>
<div id="dialog-modal" title="Working">
	<p style="text-align: center; padding-top: 40px;">Updating the sort order...</p>
</div>
