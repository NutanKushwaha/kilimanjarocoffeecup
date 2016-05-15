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

    .btn-primary {
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
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
         <div class="col-sm-9">
            <h3 style="margin: 0;">  <i class="fa fa-bars"></i> Companies Stores</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="company/store/add" class="btn btn-primary">
            <h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-plus-square" title="Add Menu"></i> Add Store </h3></a>
        </div>
    </div>
</header>
<div class="clearfix"></div>
<div class="col-lg-12 padding-0 mar-top15" id="store-view-div">
    <?= $comp_store_list; ?>
</div>