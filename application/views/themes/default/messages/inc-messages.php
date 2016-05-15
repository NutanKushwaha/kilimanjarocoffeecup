<?php if (function_exists('validation_errors') && validation_errors() != '') { ?>
    <div class="alert alert-danger alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b><i class="icon fa fa-ban"></i>Error(s)</b>:
        <ul class="error">
            <?php echo validation_errors(); ?>
        </ul>
    </div>
<?php } ?>

<?php if (isset($INFO) && ($INFO != '')) { ?>
    <div class="alert alert-info alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
<!--
        <b><i class="icon fa fa-info"></i>Notice</b>:
-->
        <ul><?php echo $INFO; ?></ul>
    </div>
<?php } ?>

<?php if (isset($SUCCESS) && ($SUCCESS != '')) { ?>
    <div class="alert alert-success alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b><i class="icon fa fa-check"></i>Success:</b>
        <ul><?php echo $SUCCESS; ?></ul>
    </div>
<?php } ?>

<?php if (isset($ERROR) && ($ERROR != '')) { ?>
    <div class="alert alert-danger alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b><i class="icon fa fa-ban"></i>Error</b>:
        <ul>
            <?php echo $ERROR; ?>
        </ul>
    </div>
<?php } ?>
<?php if ($this->session->flashdata('custom-msg')) { ?>
    <script>
        $(document).ready(function () {
            $('.custom-msg').hide();
            var errorHtml = "<div class='alert alert-danger alert-dismissable'>"
                    + " <button class='close' aria-hidden='true' "
                    + " data-dismiss='alert' type='button'>×</button>" +
                    "<b><i class='icon fa fa-ban'></i>Error</b>:<ul><?php echo str_replace('"', '\'', $this->session->flashdata('custom-msg')); ?></ul></div>";
            $('.custom-msg').html(errorHtml).show();

        });
    </script>
    <div class="custom-msg"></div>
<?php } ?>
