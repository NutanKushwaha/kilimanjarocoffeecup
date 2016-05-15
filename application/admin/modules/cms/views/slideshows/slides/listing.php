<header class="panel-heading">
    <div class="row">
        
        <div class="col-sm-9">
            <a href="cms/slideshowindex" title="Manage Slide Shows">
                <h3 style="margin: 0;color: #444;"><i class="fa fa-home"></i> Manage <?php echo $slideshow['slideshow_title']; ?>  Slides</h3>
            </a>
        </div>
        <div class="col-sm-3 text-right">
            <a href="cms/slide/add/<?php echo $slideshow['slideshow_id']; ?>" title="Add Slide" class="btn btn-primary">
                <h4 style="font-size: 15px;margin: 0;"><i class="fa fa-plus-square"></i> Add Slides </h4>
            </a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0 mar-top20">
    <?php $this->load->view(THEME.'messages/inc-messages'); ?>
    <div class="tableWrapper">
        <div class="main_action" style="padding-bottom:20px;">
            <div class="category_name" style="float:left; padding-left:15px; font-size:12px; font-weight:bold;">Slide Image</div>
            <div class="action" style="float:right; padding-right:30px; font-size:12px; font-weight:bold">Action</div>
        </div>
        <?php echo $slidetree; ?>
    </div>

    <div id="dialog-modal" title="Working">
        <p style="text-align: center; padding-top: 40px;">Updating the sort order...</p>
    </div>
</div>