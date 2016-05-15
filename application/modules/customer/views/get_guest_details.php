<div class="col-lg-12">
    <div class="columns-w">														
        <div class="columns-w">
            <div class="account-create">
                <div class="page-title">
                    <h1>Checkout Customer Details</h1>
                </div>
                <form action="" method="post" id="form-validate" enctype="multipart/form-data">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group required-field-block">
                            <label>First Name <span class="required">*</span></label>
                            <input type="text" placeholder="First Name *" name="upro_first_name" class="form-control" value="">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                        </div>
                        <div class="form-group required-field-block">
                            <label>Last Name <span class="required">*</span></label>
                            <input type="text" placeholder="Last Name *" name="upro_last_name" class="form-control" value="<?php echo arrIndex($post, 'upro_last_name') ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group required-field-block">
                            <label>User Email <span class="required">*</span></label>
                            <input type="text" placeholder="Email *" name="uacc_email" class="form-control" value="<?php echo arrIndex($post, 'uacc_email') ?>">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="buttons-set">
                            <p class="required">* Required Fields</p>
                            <p class="back-link"><a href="<?php echo base_url(''); ?>" class="back-link"><small>« </small>Back</a></p>
                            <button type="submit" title="Submit" class="button"><span><span>Checkout</span></span></button>
                        </div>
                    </div>
                </form>
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
