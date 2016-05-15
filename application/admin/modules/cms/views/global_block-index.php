<div style="clear:both"></div>
<header class="panel-heading">
    <div class="row">
        
        <div class="col-sm-9">
            <h3 style="margin: 0;"> <i class="fa fa-th-large"></i> Manage Global Blocks</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="cms/globalblock/add"><h3 style="font-size: 15px; cursor: pointer; margin: 0; ;" class="btn btn-primary"><i class="fa fa-plus-square" title="Add Globel Block"></i>  Add Globel Block </h3></a>
        </div>
    </div>
</header>
<div id="cms" style="margin-top: 15px;">
    <div class="tableWrapper">
        <?php $this->load->view(THEME . 'messages/inc-messages'); ?>
        <?php
        if (empty($global_blocks)) {
            $this->load->view(THEME . 'messages/inc-norecords');
            return;
        }
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formtable">
            <tr style="background: #EBEBEB; padding: 2px">
                <th width="564" style="padding: 5px;">Block Title</th>
                <th width="593" style="padding: 5px;">Block Alias</th>
                <th width="160" style="padding: 5px;">Action</th>
            </tr>
            <?php foreach ($global_blocks as $item) { ?>
                <tr class="<?php echo alternator('', 'alt'); ?> tr-border">
                    <td><?php echo $item['block_title']; ?></td>
                    <td><?php echo $item['block_alias']; ?></td>
                    <td><a href="cms/globalblock/edit/<?= $item['block_id']; ?>">Edit</a> | <a href="cms/globalblock/delete/<?= $item['block_id']; ?>"onclick="return confirm('Are you sure you want to Delete this Block?');">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<ul align="center">
    <div class="pagination">
        <?= $pagination; ?>
    </div>
</ul>
