<header class="panel-heading">
    <div class="row">
        
        <div class="col-sm-12">
            <h3 style="margin: 0; text-align: left"> <i class="fa fa-file-picture-o"></i> Add Slide Show</h3>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0 mar-top20">
    <?php $this->load->view(THEME . 'messages/inc-messages'); ?>
    <form action="cms/slideshow/add/" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
        <div class="form-group">
            <label class="control-label">Slideshow Title <span class="">*</span></label>
            <input type="text" name="slideshow_title" id="slideshow_title" class="form-control" required=""/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Slideshow URL <span class="">*</span></label>
            <input type="text" name="slideshow_alias" id="slideshow_alias" class="form-control" required="" value="<?php echo set_value('slideshow_alias'); ?>"/>
        </div>
        <p>Fields marked with <span class="">*</span> are required.</p>
        <p><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>

    </form>
</div>