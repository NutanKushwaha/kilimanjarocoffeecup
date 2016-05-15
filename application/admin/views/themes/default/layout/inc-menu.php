<?php
$menu_html = "";
$active_class_dashboard = "men ";
$is_other_menu_selected = FALSE;
extract( com_getMenu() );
$comp_color = com_get_theme_menu_color();
foreach ($filtered_menu as $key => $value) {
	$menu_class = 'men';
    if( strtolower( $value[ 'class' ] ) == strtolower($active_class_name)  
        && strtolower( $value[ 'method' ] ) == strtolower($active_class_method)  ){
        //$menu_class .= ' activemen';
        $is_other_menu_selected = TRUE;
	}	
    $menu_html .= '<div class="col-bs-15 col-sm-2">
                        <a href="' . $value['url'] . '">
                                <div class="'. $menu_class .'">
                                        <i class="' . $value['icon'] . '"></i>
                                        <h3>' . $value['label'] . '</h3>
                                </div>
                        </a>							
                </div>';
}
if( !$is_other_menu_selected ){
    //$active_class_dashboard .= ' activemen';
}
$menu_html = '<div class="menubar_right_container">
				<div class="col-bs-15 col-sm-2">
	                <a href="dashboard">
	                	<div class="'.$active_class_dashboard.'"><i class="fa fa-tachometer siz"></i>
	                		<h3>dashboard</h3>
	            		</div>
	        		</a>
        		</div>' . $menu_html . '</div>';
$demo_style	= '';
if( $comp_color ){	
	$demo_style = ' <style>	
					.men{
						background-color:'.com_arrIndex($comp_color, 'theme_menu_base', '#f27733').' !important;
					}
					.men:hover{
						background: '.com_arrIndex($comp_color, 'theme_menu_hover', '#783914').' none repeat scroll 0 0 !important;
						border: 1px solid #d37602;
					}					
				</style>';	
}
echo $demo_style.$menu_html;
?>
<div class="clearfix"></div>
