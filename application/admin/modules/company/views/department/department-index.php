<?PHP
$comp_color = com_get_theme_menu_color();
$base_color = '#783914';
$hover_color = '#d37602';
if ($comp_color) {
    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#f27733');
    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');
}
?>

<style>
    /*    
        
    */

    .btn-info {
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }

    .btn-info:hover, .btn-info:active, .btn-info.hover {
        background-color: <?= $hover_color; ?> !important;
        border-color: <?= $base_color; ?> !important    ;
    }
    .pagination > .active > a, .pagination > .active > span, 
    .pagination > .active > a:hover, .pagination > .active > span:hover, 
    .pagination > .active > a:focus, .pagination > .active > span:focus{
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }
</style>


<header class="panel-heading">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Manage Kits</h3>
        </div>
        <div class="col-sm-1" style="text-align: right">
            <a href="company/department/add">
                <h3 style="cursor: pointer; margin: 0; color: #000">
                    <i class="fa fa-plus-square" title="Add New Kit"></i>
                </h3>
            </a>
        </div>
    </div>
</header>
<div class="tableWrapper mar-top10">
    <?php echo $list_view; ?>
</div>
