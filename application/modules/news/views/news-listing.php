<div id="news_scroll" class="newssec">
    <h1>Current News</h1>
    <div class="hr_solid"></div>
    <?php
    if (count($news) == 0) {
        echo "here";
        $this->load->view(THEME . 'messages/inc-norecords');
        return;
    }
    ?>

    <?php
    foreach ($news as $item) {
        $news_date = strtotime($item['news_date']);
        ?>
        <h2><?php echo $item['news_title']; ?></h2>
        <p><?php echo date('j F Y H:m', strtotime($item['news_date'])); ?></p>
        <p>
            <?php echo word_limiter($item['contents'], 40); ?>
            <a href="news/details/<?php echo $item['url_alias'] ?>" style="text-decoration:none;color:#8ac53e;font-size:15px;">read more</a>
        </p>
        <div class="hr_dotted"></div>
    <?php } ?>
</div>
