<link href="css/multiple-select.css" rel="stylesheet" />
<script src="js/multiple-select.js"></script>


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
        .btn-primary {
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
    .pagination > .active > a, .pagination > .active > span, 
    .pagination > .active > a:hover, .pagination > .active > span:hover, 
    .pagination > .active > a:focus, .pagination > .active > span:focus{
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }

</style>



<div class="search-prod-form"> 
    <div class="col-md-10" style="padding: 0 0 15px;">
        <input type="text" id="search_product" name="search_product" 
               placeholder="Search by product sku or name" 
               value="<?php echo $search_product; ?>" 
               class="form-control"
               onkeydown="if (event.keyCode == 13) {
                            return false;
                        }">
    </div>
    <div class="col-md-1 checkbox">
        <label>		  
            <?php
            echo form_checkbox("exact_match", 1, $exact_match);
            ?> Exact
        </label>
    </div>
    <div class="col-md-1">
        <input type="button" name="find-prods" value="Search" class="btn btn-primary" id="find-products">
    </div>	
</div>
<div class="tableWrapper"> 
    <?php
    echo form_hidden('products', $hidden);
    echo form_hidden('days', $hidden_days);
    echo form_hidden('gpolicy', $hidden_gpolicy);
    echo form_hidden('quantity', $hidden_quantity);

    $this->table->clear();
    $table_property = array('table_open' => '<table width="100%" border="0" cellpadding="2" 
														cellspacing="0" class="table-bordered">');
    $this->table->set_template($table_property);
    $this->table->set_heading(['data' => 'Name', 'class' => "border", 'style' => "text-align:center", 'width' => "60%"], ['data' => 'Selected', 'class' => "border", 'style' => "text-align:center", 'width' => "40%"]
    );

    $JS = ' class="select_picker" ';
    foreach ($products as $key => $value) {

        $selected = false;
        if (in_array($value['product_sku'], $common_products)) {
            $selected = true;
        }
        $prod_id = 'prod-' . $value['product_sku'];
        $row_product = array(
            'id' => $prod_id,
            'name' => 'products[]',
            'value' => $value['product_sku'],
            'checked' => $selected,
        );
        $row_qty = array(
            'name' => 'quantity[' . $value['product_sku'] . ']',
            'value' => com_arrIndex($common_quantity, $value['product_sku'], '1'),
            'type' => 'number',
        );
        $this->table->add_row(form_label($value['product_name'], $prod_id), ['data' => form_checkbox($row_product), 'style' => 'text-align:center;']
        );
    }
    echo $this->table->generate();
    ?>
</div>
<div class="clearfix"></div>
<div class="ajax-pagination" style="text-align:center;">
    <ul class="pagination">
<?= $pagination; ?> 
    </ul>
</div>
<script>
    $(document).ready(function () {
        $("#find-products").on('click', function () {
            $.post("<?= base_url('/company/ajax/department/getProducts/' . $dept_id); ?>",
                    {'form_data': $('#dept-product-allot').serialize(), 'ci_csrf_token': '', 'ajax': '1', 'offset': '0', },
                    function (data) {
                        data = JSON.parse(data);
                        if (data.success) {
                            $('input[name=\'ci_csrf_token\']').val(data.csrf_hash);
                            $('#product-tbl-div').html(data.html);
                        }
                    });
            return false;
        });
        $(".set-day-limit-option > input").change(function () {
            if ($(this).val() < 0) {
                $(this).val(0);
            }
        });
        $('.gpolicy').multipleSelect({
            filter: true,
            width: "100%"
        });
    });
</script>
