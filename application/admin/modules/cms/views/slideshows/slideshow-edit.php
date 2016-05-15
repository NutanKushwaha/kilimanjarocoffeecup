<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <a href="slideshow" style="color: #444;" title="Manage Slide Show"><h3 style="margin: 0;"> <i class="fa fa-image"></i>  Edit Slide Show</h3></a>
        </div>
        <div class="col-sm-3 text-right">
            <a href="cms/slideshow/index" title="Manage Slide Show" class="btn btn-primary">
                <h4 style="margin:0;font-size: 15px;">
                    <i class="fa fa-share fa-lg"></i> Back
                </h4>
            </a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0 mar-top20">
    <?php $this->load->view(THEME . 'messages/inc-messages'); ?>
    <form action="cms/slideshow/edit/<?php echo $slideshow['slideshow_id']; ?>" method="post" enctype="multipart/form-data" name="addcatform" id="addcatform">
        <div class="form-group">
            <label class="control-label">Slideshow Title <span class="">*</span></label>
            <input type="text" name="slideshow_title" id="slideshow_title" class="form-control" required="" value="<?php echo set_value('slideshow_title', $slideshow['slideshow_title']); ?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Slideshow URL <span class="">*</span></label>
            <input type="text" name="slideshow_alias" id="slideshow_alias" class="form-control" required="" value="<?php echo set_value('slideshow_alias', $slideshow['slideshow_alias']); ?>"/>
        </div>
        <p>Fields marked with <span class="">*</span> are required.</p>
        <p><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>
    </form>
</div>