<link href="<?php echo base_url() ?>/css/datatables/dataTables.bootstrap.css" 
      rel="stylesheet" type="text/css" />
<header class="panel-heading">
    <div class="row">

        <div class="col-sm-9 pad-left0">
            <h3 style="margin: 0;"><i class="fa fa-university"></i> Organization Management</h3>
        </div>
        <div class="col-sm-3 pad-right0">
            <a title="" data-original-title="" href="company/addCompany" data-toggle="tooltip" type="button" class="btn btn-primary pull-right"><i class="fa fa-share"></i> Add Organization</a>
        </div>
        <?php
        /*
          <div class="col-sm-1" style="text-align: right">
          <a href="company/add"><h3 style="cursor: pointer; margin: 0; color: #000"><i class="fa fa-plus-square" title="Add New user"></i></h3></a>
          </div>
         */
        ?>
    </div>
</header>
<div class="col-lg-12 padding-0" style="padding-top: 15px;">
    <table id="company-list-table" class="table table-bordered table-striped">
        <thead>
            <tr>                        
                <?php foreach ($table_labels as $label): ?>
                    <th><?php echo $label ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?= com_arrIndex($company, 'company_code'); ?></td>
                    <td><?= com_arrIndex($company, 'name'); ?></td>
                    <td><?= com_arrIndex($company, 'email_address'); ?></td>
                    <td><?= com_arrIndex($company, 'contact_person'); ?></td>    

                    <td><?php
                        echo anchor('company/view/' . com_arrIndex($company, 'id'), 'View &nbsp; <i class="fa fa-file-text-o"></i>');
                        if ($company['enable'] == 0) {
                            echo anchor('company/disable/' . com_arrIndex($company, 'id'), 'Disable &nbsp; <i class="fa fa-user-times"></i>');
                        } else {
                            echo anchor('company/enable/' . com_arrIndex($company, 'id'), 'Enable &nbsp; <i class="fa fa-user"></i>');
                        }
                        ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $this->load->view(THEME . 'headers/table-bootstrap-script', ['table_id' => 'company-list-table']); ?>
