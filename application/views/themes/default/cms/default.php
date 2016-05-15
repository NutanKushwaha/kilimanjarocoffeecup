<h1><?php echo htmlentities($page['page_title']);  ?></h1>
<?php echo $page['page_contents']; ?>
<div class="hr_shadow_horiz"></div>
<?php foreach ($compiledblocks as $block) { ?>
	<?php echo $block; ?>
	<div class="hr_shadow_horiz"></div>
<?php } ?>

