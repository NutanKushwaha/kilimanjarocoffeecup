<header class="panel-heading">
    <div class="row">
         <div class="col-sm-9">
            <h3 style="margin: 0;">  <i class="fa fa-bars"></i> Menus</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="cms/menu/add" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-plus-square" title="Add Menu"></i> Add Menu </h3></a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0 mar-top15">
    <?php
    if (count($menu) == 0) {
        $this->load->view(THEME . 'messages/inc-norecords');
    } else {        
        ?>
        <div class="tableWrapper">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
                <tr style="background: #EAEAEA">
                    <th width="75%">Menu Alias</th>
                    <th width="25%" style="text-align:left;padding-left: 75px;">Action</th>
                </tr>
                <?php foreach ($menu as $item) { ?>
                    <tr  class="<?php echo alternator('', 'alt'); ?>">
                        <td style="text-align:left;"><?php echo $item['menu_alias']; ?></td>
                        <td style="text-align:right;"><a href="cms/menu_item/index/<?php echo $item['menu_id']; ?>">Menu Items</a> | <a href="cms/menu/edit/<?php echo $item['menu_id']; ?>">Edit</a> | <a href="cms/menu/delete/<?php echo $item['menu_id'] ?>" onclick="return confirm('Are you sure you want to Delete this Menu ?');">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
</div>