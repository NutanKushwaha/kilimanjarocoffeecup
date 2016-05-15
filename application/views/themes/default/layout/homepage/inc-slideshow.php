<?php if ($module_slideshow && !empty($module_slideshow->details['slides'])) { ?>
	<?php foreach ($module_slideshow->details['slides'] as $slide) { ?>
		<?php if ($slide['link'] !== '') { ?>
			<a href="<?php echo $slide['link']; ?>" <?php
			if ($slide['new_window'] == 1) {
				echo ' target="_blank"';
			}
			?>>
				<div class="slider"><img src="<?php echo ar($this->config->item('SLIDESHOW_IMAGE_PATH') . $slide['slideshow_image'], 889, 300, 'slidesgow_images'); ?>" alt="<?php echo $slide['alt']; ?>" /></div>
			</a>
		<?php } else { ?>
			<div class="slider"><img src="<?php echo ar($this->config->item('SLIDESHOW_IMAGE_PATH') . $slide['slideshow_image'], 889, 300, 'slidesgow_images'); ?>" alt="<?php echo $slide['alt']; ?>" /></div>
		<?php } ?>
	<?php } ?>
<?php } ?>