<header class="panel-heading">
    <div class="row">
        <div class="col-sm-1">
            <a href="cms/slideshow/slide/index/<?php echo $slideshowimage['slideshow_id']; ?>" style="color: #fff;" title="Manage Slideshow Images"><i class="fa fa-arrow-left fa-2x"></i></a>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Edit Slide Show Image</h3>
        </div>
        <div class="col-sm-1">
            <a href="cms/slideshow/index" style="color: #fff;" title="Manage Slide Show"><i class="fa fa-home fa-2x"></i></a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0 mar-top20">
    <?php //$this->load->view(THEME . 'messages/inc-messages'); ?>
    <form action="cms/slide/edit/<?php echo $slideshowimage['slideshow_image_id']; ?>" method="post" enctype="multipart/form-data" name="addcatform" id="addcatform">
        <div class="form-group">
            <label class="control-label">Slideshow Image <span class="">*</span></label>
            <?php if ($slideshowimage['slideshow_image'] != '') { ?>
                <img src="<?php echo $this->config->item('SLIDESHOW_IMAGE_URL') . $slideshowimage['slideshow_image']; ?>" border="0" width="200px"/><br />
            <?php } ?>
            <input name="image" type="file" id="image" size="42" class="form-control" />
             <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>" />
            <small>Only .jgp,.gif,.png images allowed</small>
        </div>
        <div class="form-group">
            <label class="control-label">Slide Image Title</label>
            <input type="text" name="img_title" id="alt" class="form-control" value="<?php echo set_value('alt', $slideshowimage['img_title']); ?>">
        </div>
        <div class="form-group">
            <label class="control-label">Slide Image Description</label>
            <input type="text" name="img_desc" id="alt" class="form-control" value="<?php echo set_value('alt', $slideshowimage['img_desc']); ?>">
        </div>          
        <div class="form-group">
            <label class="control-label">Alt</label>
            <input type="text" name="alt" id="alt" class="form-control" value="<?php echo set_value('alt', $slideshowimage['alt']); ?>">
        </div>
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" name="link" id="link" class="form-control" value="<?php echo set_value('link', $slideshowimage['link']); ?>">
        </div>
        <div class="form-group">
            <label class="control-label">New Window</label>
            <input type="radio" name="new_window" value="1" <?php echo set_radio("new_window", 1, ($slideshowimage['new_window'] == 1)); ?> />Yes
            <input type="radio" name="new_window" value="0" <?php echo set_radio("new_window", '0', ($slideshowimage['new_window'] == 0)); ?>  />No
        </div>
        <input name="v_image" type="hidden" id="v_image" value="1" />
        <p>Fields marked with <span class="">*</span> are required.</p>
        <p><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>

        
    </form>
</div>