<div id="dialog-modal" title="Basic modal dialog"></div>
<div id="footer">
	<div id="footer_link">
		<div class="footer_menu">
			<?php
			$params = array(
				'menu_alias' => 'footer_menu',
				'ul_format' => '<ul>{MENU}</ul>',
				'level_1_format' => '<a href="{HREF}"{ADDITIONAL}><span>{LINK_NAME}</span></a>',
				'level_2_format' => '<a href="{HREF}"{ADDITIONAL}><span>{LINK_NAME}</span></a>'
			);
			echo $CI->html->menu($params);
			?>
		</div>
		<div class="copyright">DD legal information and copyright</div>
	</div>
	<div id="footer_icon">
		<div class="icon"><img src="images/footer-icon1.png" width="58" height="35"></div>
		<div class="icon"><img src="images/footer-icon2.png" width="61" height="35"></div>
		<div class="icon"><img src="images/footer-icon3.png" width="60" height="35"></div>
		<div class="icon"><img src="images/footer-icon4.png" width="25" height="35"></div>
		<div class="icon"><img src="images/footer-icon5.png" width="55" height="35"></div>
		<div class="icon"><img src="images/footer-icon6.png" width="37" height="35"></div>
    </div>
	<div style="clear:both"></div>
</div>	