<div style='text-align: center'>
    <?php $this->load->view('inc-messages'); ?>
</div>

<div class="col-lg-12">
    <div class="columns-w">														
        <div class="columns-w">
            <?php $this->load->view('themes/'.THEME.'/layout/inc-dashboard'); ?>
            <div class="account-create yt-main-right yt-main col-main col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="page-title">
                    <h1>Edit Account Information</h1>
                </div>
                <form action="" method="post" id="form-validate" enctype="multipart/form-data">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group required-field-block">
                            <input type="text" placeholder="User Name *" name="uacc_username" class="form-control" value="<?php echo arrIndex($userDetails, 'uacc_username') ?>">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                        </div>
                        <div class="form-group required-field-block">
                            <input type="file"  name="image" class="form-control" >
                            <img src="<?php echo com_get_image('UPLOAD_USERS_IMG_URL', 'UPLOAD_USERS_IMG_PATH', 'UPLOAD_USERS_RESIZE_IMG_URL', 'UPLOAD_USERS_RESIZE_IMG_PATH', $userDetails['upro_image'], 50, 37) ?>" > 
                        </div>                        
                        <div class="form-group required-field-block">
                            <input type="password" placeholder="Password *" name="uacc_password" class="form-control" value="" >
                        </div>
                        <div class="form-group required-field-block">
                            <input type="text" placeholder="First Name *" name="upro_first_name" class="form-control" value="<?php echo arrIndex($userDetails, 'upro_first_name') ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group required-field-block">
                            <input type="text" placeholder="Email *" name="uacc_email" class="form-control" value="<?php echo arrIndex($userDetails, 'uacc_email') ?>">
                        </div>
                        <div class="form-group required-field-block">
                            <input type="password" placeholder="Confirm Password *" name="uacc_cpassword" class="form-control"  >
                        </div>
                        <div class="form-group required-field-block">
                            <input type="text" placeholder="Last Name *" name="upro_last_name" class="form-control" value="<?php echo arrIndex($userDetails, 'upro_last_name') ?>">
                        </div>
                    </div>
                    <div class="buttons-set">
                        <p class="required">* Required Fields</p>
                        <p class="back-link"><a href="<?php echo base_url('customer') ?>" class="back-link"><small>« </small>Back</a></p>
                        <button type="submit" title="Submit" class="button"><span><span>submit</span></span></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>