<?PHP
    $comp_color = com_get_theme_menu_color();
    $base_color = '#F27733';
    $hover_color = '#C24703';
    if( $comp_color ){
        $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#F27733');
        $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#C24703');
    }
    
?>
<style>
    .attr-set-list .btn.btn-info{
        background: <?= $base_color; ?> ;
        border: 1px solid  <?php $hover_color; ?>;
    }
    .attr-set-list .btn.btn-info:hover{
        background: <?= $hover_color; ?>;
        border: 1px solid <?php $base_color;; ?>;
    }
    
</style>
<div class="col-sm-1 attr-set-list padding-0">
	<a class="btn btn-info" href="dashboard"><i class="fa fa-tachometer siz"></i> dashboard</h4></a>
</div>
