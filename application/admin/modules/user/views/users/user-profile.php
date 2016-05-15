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
    <header class="panel-heading">
        <div class="row">
            <div class="col-sm-9 padding-0">
                <h3 style="margin: 0">Profile :<?php echo $user_profile->uacc_username ?></h3>                
            </div>
            <div class="col-lg-3">
                <?php
                echo anchor(base_url($profile ? 'dashboard/' : 'user/' ), '<i class="fa fa-share"></i> Back', array('data-toggle' => "tooltip", 'type' => "button", 'class' => "btn btn-primary pull-right")
                );
                $profileEdit = "user/view/$user_profile->uacc_id?edit=1";
                if ($profile) {
                    $profileEdit = "user/edit_profile";
                }
                echo anchor(base_url($profileEdit), '<i class="fa fa-edit"></i> Edit', array('data-toggle' => "tooltip", 'type' => "button", 'class' => "btn btn-primary pull-right", 'style' => "margin-right: 10px")
                );
                ?>            
            </div>
        </div>
    </header>

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 col-lg-3 left-profile-img-block-container" align="center" >
                    <div class="col-lg-12 padding-0" style="width: 190px;height:190px;">
<?php
$usr_img_src = $this->config->item('SYS_IMG') . 'default-user.png';
$img_alt = 'User Default Bodyguard';
if (com_check_user_img_exist($user_profile->upro_image)) {
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
                </div>
                <div class=" col-md-9 col-lg-9 "> 
                    <table class="table table-user-information">
                        <tbody>
<?php if (in_array($user_profile->ugrp_name, [ CMP_MD, CMP_PM, USER])) { ?>
                                <tr>
                                    <td>User Company:</td>
                                    <td><?php echo $user_company ?></td>
                                </tr>
<?php } ?>
                            <tr>
                                <td>User profile type:</td>
                                <td><?php echo $user_profile->ugrp_name ?></td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td><?php echo $user_profile->uacc_email ?></td>
                            </tr>
                            <tr>
                                <td>Last Login:</td>
                                <td><?php echo $user_profile->uacc_date_last_login ?></td>
                            </tr>
                            <tr>
                                <td>First Name:</td>
                                <td><?php echo $user_profile->upro_first_name ?></td>
                            </tr>
                            <tr>
                                <td>Last Name:</td>
                                <td><?php echo $user_profile->upro_last_name ?></td>
                            </tr>
<?php if (in_array($user_profile->ugrp_name, [USER, CMP_MD, CMP_PM])) { ?>
                                <tr>
                                    <td>Profession:</td>
                                    <td><?= $user_profile->upro_profession ?></td>
                                </tr>
    <?php
}
if (in_array($user_profile->ugrp_name, [USER, CMP_PM]) && $user_approval_name && $user_approval_title) {
    ?>
                                <tr>
                                    <td><?= $user_approval_title ?>:</td>
                                    <td><?= $user_approval_name ?></td>
                                </tr>							
<?php
}
if (($user_profile->ugrp_name == USER && !$profile) || ($user_type == USER && $profile)) {
    ?>
                                <tr>
                                    <td>Kit:</td>
                                    <td><?php echo ol($user_dept, [ 'class' => 'simple-order-list', 'style' => 'list-sytle:inherit']) ?></td>
                                </tr>
<?php } ?>
                            <tr>
                                <td>Address Recipent:</td>
                                <td><?php echo $user_profile->uadd_recipient ?></td>
                            </tr>
                            <tr>
                                <td>Telephone:</td>
                                <td><?php echo $user_profile->uadd_phone ?></td>                                  
                            </tr>
                            <tr>
                                <td>Address 1:</td>
                                <td><?php echo $user_profile->uadd_address_01 ?></td>
                            </tr>
                            <tr>
                                <td>Address 2:</td>
                                <td><?php echo $user_profile->uadd_address_02 ?></td>
                            </tr>
                            <tr>
                                <td>City:</td>
                                <td><?php echo $user_profile->uadd_city ?></td>                                        
                            </tr>
                            <tr>
                                <td>County:</td>
                                <td><?php echo $user_profile->uadd_county ?></td>                                        
                            </tr>
                            <tr>
                                <td>Post code:</td>
                                <td><?php echo $user_profile->uadd_post_code ?></td>                                        
                            </tr>
                            <tr>
                                <td>Country:</td>
                                <td><?php echo $user_profile->uadd_country ?></td>                                        

<?php
if ($user_profile->unique_url) {
    ?>

                            <tr>
                                <td>URL:</td>
                                <td><?php echo $user_profile->unique_url ?></td>                                        
                            </tr>   
    <?php } ?>                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
