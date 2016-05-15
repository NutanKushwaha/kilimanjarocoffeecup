<?php 
    if (function_exists('validation_errors') && validation_errors() != '') { 
?>
    <div class="alert alert-danger alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b><i class="icon fa fa-ban"></i>Error(s)</b>:
        <ul class="error">
            <?php echo validation_errors(); ?>
        </ul>
    </div>
<?php 
}
    if (isset($INFO) && ($INFO != '')) { 
?>
    <div class="alert alert-info alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <b><i class="icon fa fa-info"></i>Notice</b>:
        <ul><?php echo $INFO; ?></ul>
    </div>
<?php 
} 
    if (isset($SUCCESS) && ($SUCCESS != '')) {
?>
    <div class="alert alert-success alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b><i class="icon fa fa-check"></i>Success:</b>
        <ul><?php echo $SUCCESS; ?></ul>
    </div>
<?php 
}
    if (isset($ERROR) && ($ERROR != '')) { 
?>
    <div class="alert alert-danger alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b><i class="icon fa fa-ban"></i>Error</b>:
        <ul>
            <?php echo $ERROR; ?>
        </ul>
    </div>
<?php 
}
    if (isset($MESSAGE) && ($MESSAGE != '')) { 
?>
    <div class="alert alert-info alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>        
        <ul><?php echo $MESSAGE; ?></ul>
    </div>
<?php 
    } ?>
