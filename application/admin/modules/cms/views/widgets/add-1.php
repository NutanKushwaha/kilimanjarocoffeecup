<h1>Add Widget: <?php echo $page['page_title']; ?></h1>
<div id="ctxmenu"><a href="cms/page">Manage Pages</a> | <a href="cms/block/index/<?php echo $page['page_id']; ?>">Manage Blocks</a> | <a href="cms/widgets/index/<?php echo $page['page_id']; ?>">Manage Widgets</a></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>

<ul>
    <?php foreach ($widget_types as $type) { ?>
        <li><a href="cms/widgets/add_2/<?php echo $page['page_id']; ?>/<?php echo $type['widget_type_id']; ?>"><?php echo $type['widget_type']; ?></a></li>
    <?php } ?>
</ul>
