
<?PHP
$comp_color = com_get_theme_menu_color();
$base_color = '#783914';
$hover_color = '#d37602';
if ($comp_color) {
    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#f27733');
    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');
}
?>

<style>
    /*    
        
    */
    .top-header-right ul li:last-child a{
        color: <?= $base_color; ?> !important;
    }
    
    
</style>

<div class="top">
    <div class="container">
    <div class="row">                            
             <div class="col-sm-4">
                <div class="top-header-right">
                    <div class="top-left-dashbd-title" id="dashbd-title">
                        <p>
						<?php 
								$comp_logo_img = com_company_and_get_pic( [] );
								if( $comp_logo_img ){
									$image_properties = [
										'src' => $comp_logo_img,
										'alt' => com_logInName(),
										'class' => '',										
									];
									echo img($image_properties);
								}
						?>
                        </p>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-4">
                <div class="top-header-center">
                    <div class="logo-section">
                        <a href="<?= base_url(); ?>">
                            <img src="images/logo.png" />
                        </a>
                    </div>
                    
                </div>
            </div>
        
            <div class="col-sm-4">
                <div class="top-header-right">
                    <ul class="list-unstyled list-inline pull-right">
                        <li> Welcome <span>Mr. <?= com_logInName(); ?>
                            <?php 
                                $user_pic = com_logInPic(1, '50_50');
                                $user_pic = $user_pic ? $this->config->item('UPLOAD_USERS_RESIZE_IMG_URL').'50_50/'.$user_pic: $this->config->item('SYS_IMG').'default-user.png'; 
                                $image_properties = [
                                    'src' => $user_pic,
                                    'alt' => com_logInName(),
                                    'class' => 'img-circle',
                                    'width' => '50',
                                    'height' => '50',                                    
                                ];
                                echo img($image_properties);
                            ?>
                        </span> 
                        </li>
                        <li> | <a href="<?= base_url('welcome/logout') ?>">logout</a> </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
            <div class="clearfix"></div>
            </div>
        
        </div>
    </div>
</div>
