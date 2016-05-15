<div class="col-lg-12">

    <div class="columns-w">														

        <div class="columns-w">

            <div class="account-create">

                <?php //$this->load->view('inc-messages'); ?>

                <div class="page-title">

                    <h1>Create an Account</h1>

                </div>

                <div class="fieldset">

                    <h2 class="legend">Personal Information</h2>

                </div>

                <div class="row">

                    <form action="" method="post" id="form-validate" enctype="multipart/form-data">                    

                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">                        

                            <div class="form-group required-field-block">

                                <label>User Name <span class="required">*</span></label>

                                <input autocomplete="off" type="text" placeholder="User Name *" name="uacc_username" class="form-control" tabindex="1"

                                       value="<?php echo set_value('uacc_username', '') ?>">

                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                            </div>

                            <?php /*

                              <div class="form-group required-field-block">

                              <label>Image <span class="required">*</span></label>

                              <input type="file"  name="image" class="form-control" >

                              </div>

                             */ ?>                      

                            <div class="form-group required-field-block">

                                <label>Password <span class="required">*</span></label>

                                <input autocomplete="off" type="password" tabindex="3" placeholder="Password *" name="uacc_password" class="form-control" value="">

                            </div>

                            <div class="form-group required-field-block">

                                <label>First Name <span class="required">*</span></label>

                                <input autocomplete="off" type="text" tabindex="5" placeholder="First Name *" name="upro_first_name" class="form-control" value="<?php echo arrIndex($post, 'upro_first_name') ?>">

                            </div>

                            <div class="form-group required-field-block">

                                <label>Address Recipent <span class="required">*</span></label>

                                <input autocomplete="off" type="text" placeholder="Address Recipent *" tabindex="7" name="uadd_recipient" class="form-control" value="<?php echo arrIndex($post, 'uadd_recipient') ?>">

                            </div>





                            <div class="form-group required-field-block">

                                <label>Address 1 <span class="required">*</span></label>

                                <input autocomplete="off" type="text" placeholder="Address 1 *" tabindex="9" name="uadd_address_01" class="form-control" value="<?php echo arrIndex($post, 'uadd_address_01') ?>">

                            </div>     

                            <div class="form-group required-field-block">

                                <label>City <span class="required">*</span></label>

                                <input autocomplete="off" type="text" placeholder="City *" tabindex="13" name="uadd_city" class="form-control" value="<?php echo arrIndex($post, 'uadd_city') ?>">

                            </div>

                            <div class="form-group required-field-block">

                                <label>County <span class="required">*</span></label>

                                <input type="text" placeholder="County *" tabindex="15" name="uadd_county" maxlength="2" class="form-control" value="<?php echo arrIndex($post, 'uadd_county') ?>">

                            </div>  







                        </div>

                        <div class="col-lg-5 col-lg-offset-2 col-md-6 col-sm-6 col-xs-12">

                            <div class="form-group required-field-block">

                                <label>User Email <span class="required">*</span></label>

                                <input type="text" placeholder="Email *" tabindex="2" name="uacc_email" class="form-control" value="<?php echo arrIndex($post, 'uacc_email') ?>">

                            </div>

                            <div class="form-group required-field-block">

                                <label>Confirm Password <span class="required">*</span></label>

                                <input type="password" placeholder="Confirm Password *" tabindex="4" name="uacc_cpassword" class="form-control" value="" >

                            </div>

                            <div class="form-group required-field-block">

                                <label>Last Name <span class="required">*</span></label>

                                <input type="text" placeholder="Last Name *" tabindex="6" name="upro_last_name" class="form-control" value="<?php echo arrIndex($post, 'upro_last_name') ?>">

                            </div>

                            <!-- <div class="form-group required-field-block">
   
                               <label>Address Alias <span class="required">*</span></label>
   
                               <input autocomplete="off" type="text" placeholder="Address Alias *" tabindex="8" name="uadd_alias" class="form-control" value="<?php //echo arrIndex($post, 'uadd_alias')  ?>">
   
                           </div> -->

                            <div class="form-group required-field-block">

                                <label>Phone <span class="required">*</span></label>

                                <input type="text" placeholder="Phone *" tabindex="8" name="uadd_phone" class="form-control" value="<?php echo arrIndex($post, 'uadd_phone') ?>">

                            </div>





                            <div class="form-group required-field-block">

                                <label>Address 2</label>

                                <input type="text" placeholder="Address 2" tabindex="10" name="uadd_address_02" class="form-control" value="<?php echo arrIndex($post, 'uadd_address_02') ?>">

                            </div>

                            <div class="form-group required-field-block">

                                <label>Postal code <span class="required">*</span></label>

                                <input type="text" placeholder="Postal code *" tabindex="14" name="uadd_post_code" class="form-control" value="<?php echo arrIndex($post, 'uadd_post_code') ?>">

                            </div>

                            <div class="form-group required-field-block">

                                <label>Country <span class="required">*</span></label>

                                <input type="text" placeholder="Country *" class="form-control" value="United States">
                                <input type="hidden"  name="uadd_country" class="form-control" value="US">
                            </div>

                        </div>

                        <div class="buttons-set">

                            <p class="required">* Required Fields</p>

                            <p class="back-link"><a href="<?php echo base_url('customer'); ?>" class="back-link"><small>« </small>Back</a></p>

                            <button type="submit" title="Submit" class="button"><span><span>Register</span></span></button>

                        </div>

                    </form>

                </div>

            </div>

        </div>







    </div>

</div>

<style>

    .required

    {

        color:red;

    }

</style>

