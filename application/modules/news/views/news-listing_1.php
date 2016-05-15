<h1>News</h1>
<?php if(count($news) == 0) { $this->load->view(THEME.'messages/inc-norecords'); return; }?>
<?php $counter = 0;
foreach($news as $item) {$counter++;
	$news_date = strtotime($item['news_date']);
	?>
        <p><strong><?php echo $item['title'];?></strong><br/>
	<?php echo date("j F Y ",$news_date);?></p><br/>
          <?php echo word_limiter($item['contents'], 40);?>
           <p align="right"><a href="news/details/<?php echo $item['url_alias'];?>">[read more]</a></p>
        <?php if($counter != count($news)){ ?>
           <div class="hr_dotted"></div>
      <?php } ?>
<?php } ?>

<p style="text-align:center"><?php echo $pagination;?></p>