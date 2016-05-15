<div class="col-lg-12" >
    <?php 
        $this->load->view(THEME . 'messages/inc-messages');
        $form_attributes = ['class' => 'user-group-add', 'id' => 'user-group-add'];
        echo form_open_multipart(base_url('user/group/insert_group'), $form_attributes); 
    ?>
    <div class="form-group">
        <header class="panel-heading">
            <div class="row">
                <div class="col-sm-6">
                    <h3 style="margin: 0"> Add New User Group</h3>
                </div>
            </div>
        </header>

        <div class="panel panel-info">
        <div class="panel-body">
            <div class="row">                    
                    <div class=" col-md-12 col-lg-12 "> 
                        <table class="table table-user-information">                    
                            <tbody>  
                                <tr>
                                    <td>Group Name *:</td>
                                    <td><?= form_input('insert_group_name', com_gParam('insert_group_name', '0', ''), ' class="form-control"');  ?></td>
                                </tr>
                                <tr>
                                    <td>Group Description:</td>
                                    <td><?= form_input('insert_group_description',
                                     com_gParam('insert_group_description', '0', ''), ' class="form-control"');  ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer col-lg-12">
                <?= anchor(base_url('user/group/manage_groups'), 'Group list', 
                        array('data-toggle'=> "tooltip", 'type'=>"button", 'class'=>"col-lg-3 btn btn-primary pull-left")
                );
                ?>
                <div class="col-lg-1"></div>
                <?= form_submit('group-add', 'Submit!' , ['data-toggle'=> "tooltip" , 
                        'class'=>"col-lg-3 btn btn-primary pull-right"] ); 
                ?>
            </div>        
        </div>
    </div>
    <?= form_close(); ?>
</div>