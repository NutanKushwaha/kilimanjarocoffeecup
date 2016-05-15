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
        
    */

    .btn-primary {
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
        background-color: <?= $base_color; ?>;
        border-color: <?=  $hover_color?>;
    }


</style>




<div class="col-lg-12" >
    <?php 
        $this->load->view(THEME . 'messages/inc-messages');
        $form_attributes = ['class' => 'update_user_privilege', 'id' => 'update_user_privilege',
                                'name' => 'update_user_privilege',
                            ];
        // base_url('user/update_privilieges/')
        echo form_open( current_url( ) , $form_attributes); 
    ?>
    <div class="form-group">
        <header class="panel-heading">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Update User Privileges of '<?php echo $user['upro_first_name'].' '.$user['upro_last_name']; ?>', Member of Group '<?php echo $user['ugrp_name']; ?>'</h4>
                </div>
            </div>
        </header>

        <div class="panel panel-info">
        <div class="panel-body">
            <div class="row">                    
                    <div class=" col-md-12 col-lg-12 "> 
                        <table class="table table-user-information">                    
                            <thead>
                                <tr>
                                    <th>Privilige Name:</th>
                                    <th>Privilige Desc :</th>
                                    <th>User Has Privileg:</th>
                                    <th>Status:</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php   foreach ($privileges as $privilege) { 
                                    if( !in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')],
                                                 $group_privileges)){
                                        continue;
                                    }
                            ?>
                                <tr>
                                    <td >
                                        <input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][id]" value="<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>"/>
                                        <?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'name')];?>
                                    </td>
                                    <td><?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'description')];?></td>
                                    <td class="text-center">
                                        <?php 
                                            // Define form input values.
                                            $current_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $user_privileges)) ? 1 : 0; 
                                            $new_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $user_privileges)) ? 'checked="checked"' : NULL;
                                        ?>
                                        <input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][current_status]" value="<?php echo $current_status ?>"/>
                                        <input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][new_status]" value="0"/>
                                        <input type="checkbox" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][new_status]" value="1" <?php echo $new_status ?>/>
                                    </td>
                                    <td class="text-center">
                                        <?php echo (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $user_privileges) ? 'Yes' : 'No'); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer col-lg-12">
                <?= anchor(base_url('user/'), 'User list', 
                        array('data-toggle'=> "tooltip", 'type'=>"button", 'class'=>"col-lg-3 btn btn-primary pull-left")
                );
                ?>
                <div class="col-lg-1"></div>
                <?= form_submit('update_user_privilege', 'Update!' , ['data-toggle'=> "tooltip" , 
                        'class'=>"col-lg-3 btn btn-primary pull-right" , 'value' => '1'] ); 
                ?>
            </div>        
        </div>
    </div>
    <?= form_close(); ?>
</div>