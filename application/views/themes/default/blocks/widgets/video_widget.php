<div class="widget <?php echo $widget['widget_type_alias']; ?>">
    <div class="video_box">
        <div class="video_img"><img src="<?php echo ar($this->config->item('VIDEO_CAP_PATH') . $widget_video['video']['frameshot'], 264, 124, 'video_widget'); ?>" width="264" height="124"></div>
        <div class="btn_play"><a href="<?php echo base_url(); ?>js/media/mediaplayer.swf?file=<?php echo $this->config->item('VIDEO_FLV_URL') . $widget_video['video']['flv']; ?>&autostart=1" target="_blank"><img src="images/btn-play.png" width="46" height="47" /></a></div>
    </div>
</div>
