<link href="<?php echo base_url() ?>/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<?php $this->load->view(THEME . 'messages/inc-messages'); ?>
<header class="panel-heading">    
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-user fa-2x"></i>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Users Group Management</h3>
        </div>
        <div class="col-sm-1" style="text-align: right">            
            <a href="user/group/insert_group"><h3 style="cursor: pointer; margin: 0; color: #000;">
            <i class="fa fa-plus-square" title="Add New User Group"></i></h3></a>            
        </div>
    </div>
</header>
<?= form_open(current_url()); ?>
<div class="col-lg-12 padding-0" style="padding-top: 15px;">
        <table id="user-group-list-table" class="table table-bordered table-striped">
            <thead>
                <tr>                        
                    <?php foreach ($table_labels as $label): ?>
                        <th><?php echo $label ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_groups as $item):?>   
                    <tr>
                        <td><?= com_arrIndex($item, 'ugrp_name'); ?></td>
                        <td><?= com_arrIndex($item, 'ugrp_desc'); ?></td>            
                        <td><?= anchor(base_url("user/update_group_privileges/".com_arrIndex($item, 'ugrp_id')), 'Manage'); ?></td>
                        <td><?= (!com_arrIndex($item, 'ugrp_admin') ? form_checkbox('delete_group[]', com_arrIndex($item, 'ugrp_id') , false) : '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>        

</div>
<div class="panel-footer col-lg-12">
    <?php
        $disable = (! $this->flexi_auth->is_privileged('Update User Groups') 
                && ! $this->flexi_auth->is_privileged('Delete User Groups')) ? 'disabled="disabled"' : NULL; 
        echo form_submit('udate-group', 'Delete Checked User Groups!' , ['data-toggle'=> "tooltip" , 
            'class'=>"col-lg-5 btn btn-primary pull-right", $disable ] ); 
    ?>
</div>
<?= form_close(); ?>
<?php $this->load->view(THEME .'headers/table-bootstrap-script', ['table_id' => 'user-group-list-table']); ?>
