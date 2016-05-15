<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <a href="cms/slideshow/slide/index/<?php echo $slideshow['slideshow_id']; ?>" style="color: #444;" title="Manage Slideshow Images">
                <h3 style="margin: 0;"><i class="fa fa-home"></i> Add <?php echo $slideshow['slideshow_title']; ?> Slide</h3>
            </a>
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
    <?php //$this->load->view(THEME . 'messages/inc-messages'); ?>
    <form action="cms/slide/add/<?php echo $slideshow['slideshow_id']; ?>" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
        <div class="form-group">
            <label class="control-label">Slideshow Image <span class="">*</span></label>
            <input type="file" name="image" id="image" class="form-control" style="padding:8px;width:100%;height:auto;">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Slide Image Title</label>
            <input type="text" name="img_title" id="alt" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label">Slide Image Description</label>
            <input type="text" name="img_desc" id="alt" class="form-control">
        </div>         
        <div class="form-group">
            <label class="control-label">Alt</label>
            <input type="text" name="alt" id="alt" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" name="link" id="link" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label">New Window</label>
            <input type="radio" name="new_window" value="1" <?php echo set_radio("new_window", 1, true); ?> />Yes
            <input type="radio" name="new_window" value="0" <?php echo set_radio("new_window", 0); ?> />No
        </div>
        <input name="v_image" type="hidden" id="v_image" value="1" />
        <p>Fields marked with <span class="">*</span> are required.</p>
        <p><input type="submit" name="upload_btn" id="upload_btn" value="Upload" class="btn btn-primary"></p>
    </form>
</div>