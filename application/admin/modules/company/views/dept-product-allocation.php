 <!-- Multiple Select -->
<link href="css/multiple-select.css" rel="stylesheet" />
<script src="js/multiple-select.js"></script>
<header class="panel-heading">
    <div class="row">
        <div class="col-sm-10">
            <h4 style="margin: 0; text-align: left;"><?= $dept_name; ?> Kit Product Allocation </h4>
        </div>
        <div class="col-sm-2" style="text-align: right">
            <a href="company/department/">
                <h3 style="cursor: pointer; margin: 0; color: #000">
                        <i class="fa fa-home" title="Departments"></i>
                </h3>
            </a>
        </div>
    </div>
</header>
<?php
    $attr = [
        'id' => 'dept-product-allot',
        'name' => 'dept-product-allot',
    ];            
    echo form_open(current_url(), $attr);
?>    
<div class="col-lg-12" id="product-tbl-div">
    <?= $product_allot_html ?>
</div>
<div class="col-lg-12">
    <?= form_submit('dept-prod', 'Update', ' class="btn btn-primary pull-right"'); ?>
</div>
<div class="clearfix"></div>
<?= form_close(); ?>
