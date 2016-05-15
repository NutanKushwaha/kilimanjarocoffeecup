<header class="panel-heading">
    <div class="row">
         <div class="col-sm-9">
            <h3 style="margin: 0;">  <i class="fa fa-bars"></i> Manage Template</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="cms/template/add" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-plus-square" title="Add Template"></i> Add Template </h3></a>
        </div>
    </div>
</header>

<div class="col-sm-12 padding-0 mar-top15">
    <?php
    if (count($templates) == 0) {
        $this->load->view(THEME . 'messages/inc-norecords');
    } else { ?>
        <div class="tableWrapper">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
                <tr style="background: #EAEAEA">
                    <th width="80%">Template</th>
                    <th width="20%" style="text-align:left;padding-left: 75px;">Action</th>
                </tr>
                <?php foreach ($templates as $item) { ?>
                    <tr  class="<?php echo alternator('', 'alt'); ?>">
                        <td style="text-align:left;"><?php echo $item['template_name']; ?></td>
                        <td style="text-align:right;"><a href="cms/template/edit/<?php echo $item['template_id'];?>">Edit</a> | <a href="cms/template/delete/<?php echo $item['template_id'];?>" onclick="return confirm('Are you sure you want to delete this Template?');">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
</div>
<ul align="center">
    <div class="pagination">
        <?= $pagination; ?>
    </div>
</ul>