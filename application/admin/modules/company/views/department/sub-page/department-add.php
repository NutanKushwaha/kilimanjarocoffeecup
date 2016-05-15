<?PHP
$comp_color = com_get_theme_menu_color();
$base_color = '#783914';
$hover_color = '#d37602';
if ($comp_color) {
    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#783914');
    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');
}
?>

<style>
    /*    
    .btn-primary.btn-primary {
        background-color: #783914;
        border-color: #330000;
    }
    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: #d37602;
        border-color: #b35600;
    }
    
    */
    
    .btn-primary.btn-primary {
        background-color: <?= $base_color; ?>;
        border-color: <?= $hover_color; ?>;
    }
    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: <?= $hover_color; ?>;
        border-color: <?= $base_color; ?>;
    }
   
</style>

<header class="panel-heading">
    <div class="row">
                
        <div class="col-sm-9" style="text-align: left;padding-left: 0;">
            <h3 style="padding-top: 0;margin-top: 0;"> <i class="fa fa-plus-square"></i>  Kit Add-from</h3>
        </div>
        <div class="col-sm-3" style="text-align: right;padding-left: 0;">            
            <a href="company/department/"><h3 style="cursor: pointer; margin: 0;"><i class="fa fa-arrow-right" title="Attributes"></i></h3></a>
        </div>
    </div>
</header>
<div class="tableWrapper mar-top10">	
    <?php
    $FORM_JS = ' id="add-attr-set" class="form-horizontal"  ';
    echo form_open(current_url(), $FORM_JS);
    ?>
    <fieldset>					
        <div class="control-group">
            <label class="control-label" for="set_name">Kit Name</label>
            <div class="controls">
                <input type="text" required class="span6" id="name" name="name">
                <p class="help-block">Name should be unique</p>
            </div> <!-- /controls -->
        </div> <!-- /control-group -->		
        <div class="control-group">
            <button type="submit" class="btn btn-primary">Save</button> 				
        </div> <!-- /form-actions -->					
    </fieldset>
    <?php echo form_close(); ?>
</div>

