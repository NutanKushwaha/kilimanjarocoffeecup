<div class="portfolio-item">
   <?php if ($block_image) { ?>
  <div class="block_banner">
   
    <img src="<?php echo $img_url; ?>" alt="<?php echo $block_title; ?>" class="img-responsive"/>
</div>
<?php } ?>

  <div class="article-content">
  <h2><?php echo  $block_title;?></h2>


<?php echo $block_contents;?>
    <p> <a class="create" href="<?php echo  $block_link;?>" >Discover more</a> </p>
</div>
</div> 