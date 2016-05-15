<?php

$params = array(
    'menu_alias' => 'header_menu',
    'ul_format' => '<ul>{MENU}</ul>',
    'level_1_format' => '<a href="{HREF}"{ADDITIONAL}><span>{LINK_NAME}</span></a>',
    'level_2_format' => '<a href="{HREF}"{ADDITIONAL}><span>{LINK_NAME}</span></a>'
);
echo $CI->html->menu($params);
?>
