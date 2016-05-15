<?php //com_e($specialPriceList);     ?>
<link href="<?php echo base_url() ?>/css/datatables/dataTables.bootstrap.css" 
      rel="stylesheet" type="text/css" />
<header class="panel-heading">
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-user fa-2x"></i>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Special Price Management</h3>
        </div>
        <div class="col-sm-1" style="text-align: right">
            <a href="<?php echo base_url('special_price/add') ?>"><h3 style="cursor: pointer; margin: 0; color: #000"><i class="fa fa-plus-square" title="Add New Special Offer"></i></h3></a>
        </div>
    </div>
</header>
<div class="col-lg-12 padding-0" style="padding-top: 15px;">

    <form action="" method="get">
        <div class="">
            <div class="col-md-6"><input type="text" name="product" placeholder="Search..." value="<?php echo $this->input->get('product'); ?>" class="form-control"></div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php // echo $this->input->get('status').'-----' ?>
                    <select class="form-control" name="status">
                        <?php foreach ($status as $key => $value) { ?>
                            <option value="<?php echo $key ?>" 
								<?php echo ($this->input->get('status') != null && $this->input->get('status') == $key) 
								? "selected" : "" ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>

            </div>
            <div class="col-md-2"><input type="submit" value="Search" class="btn btn-primary"></div>
        </div>
    </form>
    <table  class="table table-bordered table-striped">
        <thead>
            <tr>                        
                <?php foreach ($table_labels as $label): ?>
                    <th><?php echo $label ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($specialPriceList['num_rows'] > 0) { ?>

                <?php foreach ($specialPriceList['result'] as $spcecial): ?>
                    <tr>
                        <td><?= com_arrIndex($spcecial, 'id'); ?></td>
                        <td><?= com_arrIndex($spcecial, 'product_name'); ?></td>
                        <td><?= com_arrIndex($spcecial, 'product_sku'); ?></td>
                        <td><?= com_arrIndex($spcecial, 'from_date'); ?></td>
                        <td><?= com_arrIndex($spcecial, 'to_date'); ?></td>
                        <td><?= com_arrIndex($spcecial, 'price'); ?></td>                    
                        <td><?= anchor('special_price/edit/' . com_arrIndex($spcecial, 'id'), 'Edit'); ?>/<?= anchor('special_price/delete/' . com_arrIndex($spcecial, 'id'), 'Delete'); ?></td></td>
                    </tr>
                <?php endforeach; ?>
            <?php }else { ?>
                <tr>
                    <td colspan="7">
                        <?php $this->load->view(THEME.'messages/inc-norecords') ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php echo $pagination ?>
</div>
<?php $this->load->view(THEME . 'headers/table-bootstrap-script', ['table_id' => 'company-list-table']); ?>
