<div style='text-align: center'>
    <?php //$this->load->view('inc-messages'); ?>
</div>
<div class="col-lg-12">
    <div class="columns-w">														
        <div class="columns-w">
            <div class="account-create yt-main-right yt-main col-main col-lg-12 col-md-9 col-sm-8 col-xs-12">
                <?php //if (!$loggedIn) { ?>
                <div class="account-create yt-main-right yt-main col-main col-lg-12 col-md-9 col-sm-8 col-xs-12">
                    <div align="right" style=""><input type="checkbox" name="copyShipping" class="pull-right"><span style="   position: relative;top: -2px;">Blling address same as shipping address</span></div>
                </div>
                <?php //} ?>
                <div class="responseMessage"></div>
                <form action="" method="post" id="form-validate" enctype="multipart/form-data">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="page-title">
                            <h1>Shipping Address :</h1>
                        </div>
                        <?php if ($loggedIn) { ?>
                            <div class="form-group required-field-block" >
                                <?php if ($allAddress['num_rows'] > 0) { ?>

                                    <select class="form-control shippingOption">
                                        <option value="">- Select a shipping address -</option>
                                        <?php foreach ($allAddress['result'] as $addressShipping) { ?>
                                            <option value="<?= $addressShipping['uadd_alias'] ?>"><?= $addressShipping['uadd_alias'] ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <div class="form-group required-field-block">
                                        No Address Found. please add your address from here <a href="<?php echo base_url('customer/contact/all_address') ?>">Address Management.</a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="form-group required-field-block">
                            <label>Contact Name <span class="required">*</span></label>
                            <input type="text" placeholder="Contact Name *" name="uadd_recipient" class="form-control" value="<?php echo arrIndex($address, 'uadd_recipient') ?>">
                        </div>
                        <div class="form-group required-field-block">
                            <label>Phone <span class="required">*</span></label>
                            <input type="text" placeholder="Phone *" name="uadd_phone" class="form-control" value="<?php echo arrIndex($address, 'uadd_phone') ?>">
                        </div>
                        <div class="form-group required-field-block">
                            <label>Address 1 <span class="required">*</span></label>
                            <input type="text" placeholder="Address 1 *" name="uadd_address_01" class="form-control" value="<?php echo arrIndex($address, 'uadd_address_01') ?>">
                        </div>    
                        <div class="form-group required-field-block">
                            <label>Address 2</label>
                            <input type="text" placeholder="Address 2" name="uadd_address_02" class="form-control" value="<?php echo arrIndex($address, 'uadd_address_02') ?>">
                        </div>
                        <div class="form-group required-field-block">
                            <label>City <span class="required">*</span></label>
                            <input type="text" placeholder="City *" name="uadd_city" class="form-control" value="<?php echo arrIndex($address, 'uadd_city') ?>">
                        </div>     
                        <div class="form-group required-field-block">
                            <label>Postal Code <span class="required">*</span></label>
                            <input type="text" placeholder="Postal code *" name="uadd_post_code" class="form-control" value="<?php echo arrIndex($address, 'uadd_post_code') ?>">
                        </div>
                        <?php /* ?>
                          <div class="form-group required-field-block">
                          <label>Company </label>
                          <input type="text" placeholder="Company *" name="uadd_company" class="form-control" value="<?php echo arrIndex($address, 'uadd_company') ?>">
                          </div>
                          <?php */ ?>

                        <div class="form-group required-field-block">
                            <label>County <span class="required">*</span></label>
                            <input type="text" placeholder="County *" name="uadd_county" maxlength="2" class="form-control" value="<?php echo arrIndex($address, 'uadd_county') ?>">
                        </div>  
                        <div class="form-group required-field-block">
                            <label>Country <span class="required">*</span></label>
                            <input type="text" placeholder="Country *" class="form-control" value="United States" readonly>
                            <input type="hidden"  name="uadd_country" class="form-control" value="US">
                        </div>
                        <?php if ($loggedIn) { ?>
                            <div class="form-group required-field-block">
                                <input type="checkbox" value="save_shipping" name="save_shipping"  >
                                <label>Save Shipping Address </label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="page-title">
                            <h1>Billing Address :</h1>
                        </div>
                        <?php if ($loggedIn) { ?>
                            <div class="form-group required-field-block" >
                                <?php if ($allAddress['num_rows'] > 0) { ?>

                                    <select class="form-control billingOption">
                                        <option value="">- Select a billing address -</option>
                                        <?php foreach ($allAddress['result'] as $addressShipping) { ?>
                                            <option value="<?= $addressShipping['uadd_alias'] ?>"><?= $addressShipping['uadd_alias'] ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <div class="form-group required-field-block">
                                        No Address Found. please add your address from here <a href="<?php echo base_url('customer/contact/all_address') ?>">Address Management.</a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="form-group required-field-block">
                            <label>Contact Name <span class="required">*</span></label>
                            <input type="text" placeholder="Contact Name *" name="billing_uadd_recipient" class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_recipient') ?>">
                        </div>
                        <div class="form-group required-field-block">
                            <label>Phone <span class="required">*</span></label>
                            <input type="text" placeholder="Phone *" name="billing_uadd_phone" class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_phone') ?>">
                        </div>
                        <div class="form-group required-field-block">
                            <label>Address 1 <span class="required">*</span></label>
                            <input type="text" placeholder="Address 1 *" name="billing_uadd_address_01" class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_address_01') ?>">
                        </div>  
                        <div class="form-group required-field-block">
                            <label>Address 2</label>
                            <input type="text" placeholder="Address 2" name="billing_uadd_address_02" class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_address_02') ?>">
                        </div>  
                        <div class="form-group required-field-block">
                            <label>City <span class="required">*</span></label>
                            <input type="text" placeholder="City *" name="billing_uadd_city" class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_city') ?>">
                        </div>     
                        <div class="form-group required-field-block">
                            <label>Postal Code <span class="required">*</span></label>
                            <input type="text" placeholder="Postal code *" name="billing_uadd_post_code" class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_post_code') ?>">
                        </div>
                        <?php /* ?>
                          <div class="form-group required-field-block">
                          <label>Company </label>
                          <input type="text" placeholder="Company *" name="billing_uadd_company" class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_company') ?>">
                          </div>
                          <?php */ ?>


                        <div class="form-group required-field-block">
                            <label>County <span class="required">*</span></label>
                            <input type="text" placeholder="County *" name="billing_uadd_county" maxlength="2"x class="form-control" value="<?php echo arrIndex($address, 'billing_uadd_county') ?>">
                        </div>  
                        <div class="form-group required-field-block">
                            <label>Country <span class="required">*</span></label>
                            <input type="text" placeholder="Country *" class="form-control" value="United States" readonly>
                            <input type="hidden"  name="billing_uadd_country" class="form-control" value="US">
                        </div>
                        <?php if ($loggedIn) { ?>
                            <div class="form-group required-field-block">
                                <input type="checkbox" value="save_billing" name="save_billing" >
                                <label>Save Billing Address </label>
                            </div>
                        <?php } ?>
                        <div class="buttons-set">
                            <p class="required">* Required Fields</p>
                            <button type="submit" title="Submit" class="button"><span><span>Submit</span></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .required{
        color:red;
    }
</style>
<?php if ($loggedIn) { ?>
    <script type="text/javascript">
        $(document).ready(function ($) {
            $('.shippingOption , .billingOption').on('change', function () {
                $('.responseMessage').html("");
                var ths = this;
                var mode = "";
                if ($(ths).hasClass('shippingOption')) {
                    mode = "shipping";
                }
                else {
                    mode = "billing";
                }
                $.ajax({
                    url: '<?php echo base_url('customer/contact/addressDetail') ?>',
                    type: 'POST',
                    context: document.body,
                    data: {alias: $(ths).val(), fillmode: mode},
                    dataType: "JSON",
                    success: function (data) {
                        if (data.response == true)
                        {
                            if (data.fillmode == "shipping")
                            {
                                jQuery('input[name="uadd_recipient"]').val(data.result.uadd_recipient);
                                jQuery('input[name="uadd_phone"]').val(data.result.uadd_phone);
                                jQuery('input[name="uadd_address_01"]').val(data.result.uadd_address_01);
                                jQuery('input[name="uadd_city"]').val(data.result.uadd_city);
                                jQuery('input[name="uadd_post_code"]').val(data.result.uadd_post_code);
                                jQuery('input[name="uadd_company"]').val(data.result.uadd_company);
                                jQuery('input[name="uadd_address_02"]').val(data.result.uadd_address_02);
                                jQuery('input[name="uadd_county"]').val(data.result.uadd_county);
                                jQuery('input[name="uadd_country"]').val(data.result.uadd_country);
                            }
                            else {
                                jQuery('input[name="billing_uadd_recipient"]').val(data.result.uadd_recipient);
                                jQuery('input[name="billing_uadd_phone"]').val(data.result.uadd_phone);
                                jQuery('input[name="billing_uadd_address_01"]').val(data.result.uadd_address_01);
                                jQuery('input[name="billing_uadd_city"]').val(data.result.uadd_city);
                                jQuery('input[name="billing_uadd_post_code"]').val(data.result.uadd_post_code);
                                jQuery('input[name="billing_uadd_company"]').val(data.result.uadd_company);
                                jQuery('input[name="billing_uadd_address_02"]').val(data.result.uadd_address_02);
                                jQuery('input[name="billing_uadd_county"]').val(data.result.uadd_county);
                                jQuery('input[name="billing_uadd_country"]').val(data.result.uadd_country);
                            }
                        }
                        else {
                            $('.responseMessage').html(data.msg);
                        }
                        return false
                    },
                    error: function () {
                        $('.responseMessage').html("An Error Occured in processing your request");
                        return false;
                    }
                })
            })
        })
    </script>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('input[name="copyShipping"]').click(function () {
            var check = $('input[name="copyShipping"]').prop('checked');
            if (check)
            {
                jQuery('input[name="billing_uadd_recipient"]').val(jQuery('input[name="uadd_recipient"]').val());
                jQuery('input[name="billing_uadd_phone"]').val(jQuery('input[name="uadd_phone"]').val());
                jQuery('input[name="billing_uadd_address_01"]').val(jQuery('input[name="uadd_address_01"]').val());
                jQuery('input[name="billing_uadd_city"]').val(jQuery('input[name="uadd_city"]').val());
                jQuery('input[name="billing_uadd_post_code"]').val(jQuery('input[name="uadd_post_code"]').val());
                jQuery('input[name="billing_uadd_company"]').val(jQuery('input[name="uadd_company"]').val());
                jQuery('input[name="billing_uadd_address_02"]').val(jQuery('input[name="uadd_address_02"]').val());
                jQuery('input[name="billing_uadd_county"]').val(jQuery('input[name="uadd_county"]').val());
                jQuery('input[name="billing_uadd_country"]').val(jQuery('input[name="uadd_country"]').val());

            } else {
                jQuery('input[name="billing_uadd_recipient"]').val('');
                jQuery('input[name="billing_uadd_phone"]').val('');
                jQuery('input[name="billing_uadd_address_01"]').val('');
                jQuery('input[name="billing_uadd_city"]').val('');
                jQuery('input[name="billing_uadd_post_code"]').val('');
                jQuery('input[name="billing_uadd_company"]').val('');
                jQuery('input[name="billing_uadd_address_02"]').val('');
                jQuery('input[name="billing_uadd_county"]').val('');
                jQuery('input[name="billing_uadd_country"]').val('');

            }
        })
    })
</script>
