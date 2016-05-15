<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
             <a href="cms/menu"><h3 style="margin: 0; color: #444;"><i class="fa fa-home" title="Manage Menu"></i> <?php echo $menu_detail['menu_name']; ?></h3></a>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="cms/menu_item/add/<?php echo $menu_detail['menu_id']; ?>" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-plus-square" title="Add Menu Item"></i> Add Menu Item</h3></a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0">    
    <?php
    if (count($menu_items) == 0) {
        $this->load->view(THEME . 'messages/inc-norecords');
        return;
    }
    ?>

    <div class="tableWrapper">
        <?php echo $menutree; ?>
    </div>
</div>