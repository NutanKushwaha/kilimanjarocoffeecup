<?PHP
$comp_color = com_get_theme_menu_color();
$base_color = '#F27733';
$hover_color = '#C24703';
if ($comp_color) {
    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#783914');
    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');
}
?>

<style>
    /*    .btn-primary {
            background-color: #783914;
            border-color: #330000;
        }
        .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: #d37602;
        border-color: #b35600;
    }
    */

    .btn-primary {
        background-color: <?= $base_color; ?>;
        border-color: <?= $base_color; ?>;
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: <?= $hover_color; ?>;
        border-color: <?= $hover_color; ?>;
    }
</style>


<div class="col-lg-12" >
    <?php
    $form_attributes = ['class' => 'profileform-edit', 'id' => 'profileform-edit'];

    $hidden = [];
    if ($profile) {
        $hidden = [ 'profile' => 1];
    }
    $formSumit = 'user/edit_profile';
    if (!isset($myProfile)) {
        $formSumit = "user/view/$user_profile->uacc_id?edit=1";
    }
    echo form_open_multipart(base_url($formSumit), $form_attributes, $hidden);
    ?>
    <div class="form-group">
        <header class="panel-heading">
            <div class="row">
                <div class="col-sm-6 pad-left0">
                    <h3 style="margin-top: 0;"> Edit : <?php echo $user_profile->uacc_username ?></h3>
                </div>
            </div>
        </header>

        <div class="user-edit-panel">
            <div class="user-edit-panel-body">
                <div class="row">
                    <div class="col-md-4 col-lg-4" align="center">
                        <div class="col-lg-12 left-profile-img-block-container" 
							style="height:190px;min-width:190px;width:275px;">
                            <?php
                            $usr_img_src = $this->config->item('SYS_IMG') . 'default-user.png';
                            $img_alt = 'User Default Bodyguard';
                            if ($user_profile->upro_image) {
                                $usr_img_src = $this->config->item('UPLOAD_USERS_IMG_URL') . $user_profile->upro_image;
                                $img_alt = 'User ' . $user_profile->uacc_username;
                            }

                            $image_properties = [
                                'src' => $usr_img_src,
                                'alt' => $img_alt,
                                'class' => '',
                                'width' => '150',
                                'height' => '150',
                                'title' => 'User ' . $user_profile->uacc_username,
                                'style' => 'width: auto; height: auto; max-width: 100%; max-height: 100%;',
                            ];
                            echo img($image_properties);
                            ?>
                        </div>
                        <div class="col-lg-12 padding-0">
                            <div class="" style="border: 1px solid #ddd;border-top: none;">
                                <div class="fileUpload file-upload btn btn-default-light">
                                    <span> <i class="fa fa-edit fa-lg"></i> Upload Image</span>
                                    <?php
                                    $file_data = [
                                        'name' => 'image',
                                        'value' => 'image',
                                        'id' => 'image',
                                        'maxlength' => '100',
                                        'size' => '50',
                                        'style' => 'width:90%',
                                        'class' => 'upload btn-file',
                                    ];
                                    echo form_upload($file_data);
                                    ?>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class=" col-md-8 col-lg-8 "> 
                        <table class="table table-user-information table-user-edit-information">                    
                            <tbody>  
                                <tr>
                                    <td>Last Activity:</td>
                                    <td><?php echo $user_profile->uacc_date_last_login ?></td>
                                </tr>                                                                              
                                <tr>
                                    <td>User Name *:</td>
                                    <td><?= form_input('uacc_username', set_value('uacc_username', $user_profile->uacc_username), ' class="form-control" autocomplete="off" '); ?></td>
                                </tr>                                                  
                                <tr>
                                    <td>Email *:</td> 
                                    <td><?= form_input('uacc_email', set_value('uacc_email', $user_profile->uacc_email), ' class="form-control" autocomplete="off" '); ?></td>
                                </tr>
                                <tr>
                                    <td>Password :</td>
                                    <td><?= form_password('uacc_password', '', ' class="form-control" autocomplete="off" placeholder="******"'); ?></td>
                                </tr>
                                <tr>
                                    <td>First Name *:</td>
                                    <td><?= form_input('upro_first_name', set_value('upro_first_name', $user_profile->upro_first_name), ' class="form-control" autocomplete="off" '); ?></td>
                                </tr>
                                <tr>
                                    <td>Last Name *:</td>
                                    <td><?= form_input('upro_last_name', set_value('upro_last_name', $user_profile->upro_last_name), ' class="form-control" autocomplete="off" '); ?></td>
                                </tr>
                                <?php
                                if (in_array($user_profile->ugrp_name, [ CMP_MD, CMP_PM, USER])) {
                                    if ($user_type !== $user_profile->ugrp_name) {
                                        ?>
                                        <tr>
                                            <td>Profession *:</td>
                                            <td><?= form_input('upro_profession', set_value('upro_profession', $user_profile->upro_profession), ' class="form-control" autocomplete="off"');
                                        ?></td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td>Profession :</td>
                                            <td><?= $user_profile->upro_profession; ?></td>
                                        </tr>									
                                        <?php
                                    }
                                    ?>									
                                    <tr>
                                        <td>User Company:<br/>
                                            <small>Non-editable</small>
                                        </td>
                                        <td> <?php echo $user_company; ?> </td>
                                    </tr>								
                                    <tr>
                                        <td>User Profile Type:<br/>
                                            <small>Profile is non-editable</small>
                                        </td>
                                        <td><?php echo $user_profile->ugrp_name; ?> </td>
                                    </tr>
                                    <?php if ($user_profile->ugrp_name == CMP_PM OR $user_profile->ugrp_name == USER) { ?>
                                        <tr>
                                            <td><?= $approver_title; ?>:</td>
                                            <td>
                                                <?php
                                                if ($user_type !== CMP_PM || ($user_type == CMP_PM && $user_profile->ugrp_name == USER )
                                                ) {
                                                    $element = form_dropdown('upro_approval_acc', $approvers, $user_profile->upro_approval_acc, ' id="approval_from" class="form-control" ');
                                                } else {
                                                    $element = $approvers;
                                                }
                                                echo $element;
                                                ?>
                                            </td>
                                        </tr>										
                                        <?php
                                    }
                                    if ($user_profile->ugrp_name == USER) {
                                        ?>
                                        <tr id="userParent">
                                        </tr>
                                        <tr id="compDeptOpt">
                                            <td>kits:
                                            </td>
                                            <td>
                                                <?php
                                                echo form_multiselect('upro_department[]', $related_dept, set_value('upro_department', $user_profile->upro_department), ' id="compDept" class="form-control" ');
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                if ($user_profile->ugrp_name == USER && $this->flexi_auth->get_user_group() == CMP_ADMIN && !isset($myProfile) && 1 == 0) {
                                    ?>
                                    <tr>
                                        <td>Kits *:</td>
                                        <td><?= form_multiselect('upro_department[]', $dept_list, $user_profile->upro_department, ' class="form-control" autocomplete="off" '); ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Address Recipent *:</td>
                                    <td><?= form_input('uadd_recipient', set_value('uadd_recipient', $user_profile->uadd_recipient), ' class="form-control" autocomplete="off" '); ?></td>
                                </tr>
                                <tr>
                                    <td>Telephone:</td>
                                    <td><?= form_input('uadd_phone', set_value('uadd_phone', $user_profile->uadd_phone), ' class="form-control" autocomplete="off" '); ?></td>
                                </tr>
                                <tr>

                                    <td>Address1:</td>
                                    <td><?= form_input('uadd_address_01', set_value('uadd_address_01', $user_profile->uadd_address_01), ' class="form-control" autocomplete="off" ');  ?></td>
                                </tr>
                                <tr>
                                    <td>Address2:</td>
                                    <td><?= form_input('uadd_address_02', set_value('uadd_address_02', $user_profile->uadd_address_02), ' class="form-control" autocomplete="off" ');  ?></td>
                                </tr>
                                <tr>
                                    <td>City:</td>
                                    <td><?= form_input('uadd_city', set_value('uadd_city', $user_profile->uadd_city), ' class="form-control" autocomplete="off" ');  ?></td>
                                </tr>
                                <tr>
                                    <td>County:</td>
                                    <td><?= form_input('uadd_county', set_value('uadd_county', $user_profile->uadd_county), ' class="form-control" autocomplete="off" ');  ?></td>
                                </tr>
                                <tr>
                                    <td>Post code:</td>
                                    <td><?= form_input('uadd_post_code', set_value('uadd_post_code', $user_profile->uadd_post_code) , ' class="form-control" autocomplete="off" ');  ?></td>
                                </tr>
                                <tr>
                                    <td>Country:</td>
                                    <td><?= form_input('uadd_country', set_value('uadd_country', $user_profile->uadd_country), ' class="form-control" autocomplete="off" ');  ?></td>

                                </tr>                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <?php
                $submit_buttons = form_submit('useredit', 'Submit!', ['data-toggle' => "tooltip",
                    'class' => "col-lg-3 btn btn-primary pull-right"]);
                if (!isset($myProfile)) {
                    $submit_buttons = anchor(base_url('user/'), 'User list', array('data-toggle' => "tooltip", 'type' => "button", 'class' => "col-lg-3 btn btn-primary pull-left")
                            ) . ' <div class="col-lg-1"></div> ' . anchor(base_url("user/view/$user_profile->uacc_id"), 'Back', array('data-toggle' => "tooltip", 'type' => "button", 'class' => "col-lg-3 btn btn-primary pull-left")
                            ) . ' <div class="col-lg-1"></div> ' .
                            form_submit('useredit', 'Submit!', ['data-toggle' => "tooltip",
                                'class' => "col-lg-3 btn btn-primary pull-left"]
                    );
                }
                echo $submit_buttons;
                ?>
            </div>        
        </div>
    </div>
    <?= form_close(); ?>
</div>
