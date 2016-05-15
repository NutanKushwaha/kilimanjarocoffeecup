<div class="widget <?php echo $widget['widget_type_alias'] . " " . $widget_image['image_class']; ?>">
    <?php if (isset($widget_image['link_url']) && $widget_image['link_url'] !== '') { ?>
        <a href="<?php echo $widget_image['link_url']; ?>" class="<?php echo $widget_image['image_class']; ?>" <?php
        if (isset($widget_image['new_window']) && $widget_image['new_window'] == 1) {
            echo ' target="_blank"';
        }
        ?>>
            <img src="<?php echo $this->config->item('WIDGET_URL') . $widget_image['image']; ?>" />
        </a>
    <?php } else { ?>
        <img src="<?php echo $this->config->item('WIDGET_URL') . $widget_image['image']; ?>"  />
    <?php } ?>
</div>

