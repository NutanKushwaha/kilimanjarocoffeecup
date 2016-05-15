<div class="widget <?php echo $widget['widget_type_alias'];?>">
		<?php
		$params = array(
			'menu_alias' => "{$widget_cmsmenu['widget_field_data']}",
			'ul_format' => '<ul class="sf-menu">{MENU}</ul>',
			'level_1_format' => '<a href="{HREF}"{ADDITIONAL}>{LINK_NAME}</a>',
			'level_2_format' => '<a href="{HREF}"{ADDITIONAL}>{LINK_NAME}</a>'
		);

		echo $CI->html->menu($params);
		?>
</div>
