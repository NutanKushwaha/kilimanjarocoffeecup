<h1>Manage Widgets: <?php echo $page['page_title']; ?></h1>
<div id="ctxmenu"><a href="cms/page/index/">Manage Pages</a> | <a href="cms/page/edit/<?php echo $page['page_id']; ?>/2">Edit Page</a> <?php if ($page['do_not_delete'] == 0) { ?>| <a href="cms/widgets/add/<?php echo $page['page_id']; ?>">Add Widget</a> |  <a href="cms/widgets/sort/<?php echo $page['page_id']; ?>" class="sort_thickbox">Sort Widget</a><?php } ?></div>

<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<?php
if (count($widgets) == 0) {
    $this->load->view(THEME.'messages/inc-norecords');
    return;
}
?>
<div class="tableWrapper">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formtable">
        <tr>
            <th width="70%">Widget</th>
            <th width="30%">Action</th>
        </tr>
        <?php foreach ($widgets as $item) { ?>
            <tr class="<?php echo alternator('', 'alt'); ?>">
                <td><?php echo $item['widget_name']; ?></td>
                <td><a href="cms/widgets/edit/<?php echo $item['widget_id']; ?>">Edit</a> | <a href="cms/widgets/delete/<?php echo $item['widget_id']; ?>"onclick="return confirm('Are you sure you want to delete this widget?');">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</div>
<p align="center"><?php echo $pagination; ?></p>