<?php if (isset($module_banner->banner) && $module_banner->banner !== '' && $module_banner->show_banner == 1) { ?>
    <div id="banner_bg">
        <div id="banner">
            <?php if (isset($module_banner->banner_url) && $module_banner->banner_url !== '') { ?>
                <a href="<?php echo $module_banner->banner_url; ?>" <?php
                if (isset($module_banner->new_window) && $module_banner->new_window == 1) {
                    echo ' target="_blank"';
                }
                ?>>
                    <img src="<?php echo ar($this->config->item('PAGE_DATA_IMAGE_PATH') . $module_banner->banner, 430, 174, 'banner_image'); ?>" />
                </a>
            <?php } else { ?>
                <img src="<?php echo ar($this->config->item('PAGE_DATA_IMAGE_PATH') . $module_banner->banner, 430, 174, 'banner_image'); ?>" />
    <?php } ?>
        </div>
    </div>
<?php }?>