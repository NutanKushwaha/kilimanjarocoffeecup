<?php //com_e($specialPriceList);            ?>

<script type="text/javascript" src="<?php echo base_url('js/jquery-datetimepicker/jquery-ui.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url('js/jquery-datetimepicker/jquery-ui-timepicker-addon.js') ?>"></script>

<link href="<?php echo base_url('js/jquery-datetimepicker/date-style.css') ?>" rel="stylesheet" type="text/css">

<link href="<?php echo base_url() ?>/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

    $(document).ready(function () {

        $('#from_date').datetimepicker({

            dateFormat: 'yy-mm-dd',

            onSelect: function (date) {

                var maxDate = $('#from_date').datepicker('getDate');

                $("#to_date").datetimepicker("change", {minDate: maxDate});

            }

        });

        $('#to_date').datetimepicker({

            dateFormat: 'yy-mm-dd',

        });

    })

</script>

<header class="panel-heading">

    <div class="row">

        <div class="col-sm-1">

            <i class="fa fa-cart-plus fa-2x"></i>

        </div>

        <div class="col-sm-10">

            <h3 style="margin: 0; ">Add Special Price</h3>

        </div>

        <div class="col-sm-1" style="text-align: right">

            <a href="<?php echo base_url('special_price/add') ?>"><h3 style="cursor: pointer; margin: 0; color: #000"><i class="fa fa-plus-square" title="Add New Special Offer"></i></h3></a>

        </div>

    </div>

</header>

<!--<h5 style="color:red">Configurable products are not considered in this module.</h5>-->

<div class="col-lg-12 padding-0" style="padding-top: 15px;">

    <form name="form-special-price" action="" method="post" enctype="multipart/form-data">

        <table id="special_price_add"  class="table table-bordered table-striped">

            <tbody>

                <tr>

                    <td>Product</td>

                    <td>

                        <select name="product">

                            <?php if ($products['num_rows'] > 0) { ?>



                                <option value=""></option> 

                                <?php foreach ($products['result'] as $product) { ?>

                                    <option value="<?php echo $product['product_sku'] ?>" <?php echo(com_arrIndex($detail, 'product_sku') == $product['product_sku']) ? "selected" : ""; ?> ><?php echo $product['product_name'] ?> - $<?php echo $product['product_price'] ?></option>

                                <?php } ?>    

                            <?php } else { ?>

                                <option value="">No Product Found.</option> 

                            <?php } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td>Price</td>

                    <td><input type="text" name="price" value="<?= com_arrIndex($detail, 'price'); ?>"></td>

                </tr>            

                <tr>

                    <td>Date From</td>

                    <td><input type="text" name="from_date" id="from_date" readonly="true" value="<?= com_arrIndex($detail, 'from_date'); ?>"></td>

                </tr>            

                <tr>

                    <td>Date To</td>

                    <td><input type="text" name="to_date" id="to_date" readonly="true" value="<?= com_arrIndex($detail, 'to_date'); ?>"></td>

                </tr>            

                <tr>

                    <td>Status</td>

                    <td>

                        <select name="status">

                            <?php foreach ($status as $key => $value) { ?>

                                <option value="<?php echo $key ?>" <?php echo (com_arrIndex($detail, 'active') == $key) ? "selected" : '' ?>><?php echo $value; ?></option>

                            <?php } ?>

                        </select>

                    </td>

                </tr>            

                <tr>

                    <td colspan="2"><input type="submit" value="submit" class='btn btn-primary'></td>

                </tr>            

            </tbody>

        </table>

    </form>

</div>

<?php //$this->load->view(THEME . 'headers/table-bootstrap-script', ['table_id' => 'company-list-table']); ?>

