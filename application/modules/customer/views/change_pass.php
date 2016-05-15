<div id="yt_content" class="yt-content wrap">        	
    <div class="yt-content-inner">
        <div class="container">
            <div class="flexiResponse">
                <?php $this->load->view('inc-messages'); ?>
                <?php echo $this->session->flashdata('message'); ?>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="columns-w">														
                        <div class="columns-w">
                            <div class="page-title">
                                <h1>Forgot Your Password?</h1>
                            </div>
                            <form name="change_pass" method="post" action="">
                                <div class="fieldset">
                                    <h2 class="legend">Retrieve your password here</h2>
                                    <p>Please enter your email address below. You will receive a link to reset your password.</p>
                                    <ul class="form-list">
                                        <li>
                                            <label class="required" for="email_address"><em>*</em>Email Address</label>
                                            <div class="input-box">
                                                <input type="email" name="email" class="input-text required-entry validate-email" id="email_address">
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="buttons-set">
                                    <p class="required">* Required Fields</p>
                                    <p class="back-link"><a href="<?php echo base_url('customer/login') ?>"><small>« </small>Back to Login</a></p>
                                    <button class="button" title="Submit" type="submit"><span><span>Submit</span></span></button>
                                </div>
                            </form>
                        </div>          
                    </div>    
                </div>
            </div>
        </div>
    </div>      
    <a id="yt-totop" href="#" title="Go to Top" style="display: none;"></a>
</div>
<style type="text/css">
    .flexiResponse h1 , .flexiResponse .error_msg {
        color: red;
        font-size: 18px;
    }
    .flexiResponse .status_msg
    {
        color: green;
        font-size: 18px;
    }
</style>