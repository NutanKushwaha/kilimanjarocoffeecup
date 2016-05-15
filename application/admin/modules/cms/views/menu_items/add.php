<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <a href="cms/menu_item/index/<?php echo $menu_detail['menu_id']; ?>"><h3 style="color: #444;margin: 0;"> <i class="fa fa-home" title="Manage Menu Items"></i> Add Menu Item</h3></a>
        </div>
        <div class="col-sm-3 text-right" >
            <a href="cms/menu" class="btn btn-primary"><h3 style="margin: 0; cursor: pointer;font-size: 15px; "><i class="fa fa-share" title="Manage Menu"></i> Back</h3></a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0" style="margin-top: 15px">
    <div id="cms">
        <?php
        $attr = [];
        $attr['id'] = 'regFrm';
        echo form_open('cms/menu_item/add/'.$menu_detail['menu_id'], $attr);
        ?>
            <div class="form-group">
                <label class="control-label">Parent</label>
                <?php echo form_dropdown('parent_id', $parent_menu, set_value('parent_id'), ' class="form-control"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label">Menu Item Type <span class="error">*</span></label>
                <?php echo form_dropdown('menu_item_type', $menu_item_types, set_value('menu_item_type'), ' id="menu_item_type" class="form-control"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label">Menu Item Name <span class="error">*</span></label>
                <input  maxlength="100" type="text" class="form-control" name="menu_item_name" id="menu_item_name" value="<?php echo set_value('menu_item_name'); ?>"/>
            </div>
            <div class="form-group">
                <label class="control-label">Page <span class="error">*</span></label>
                <?php echo form_dropdown('page_id', $pages, set_value('page_id'), ' class="form-control"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label">URL <span class="error">*</span></label>
                <input  maxlength="100" type="text" class="form-control" name="url" id="url" value="<?php echo set_value('url'); ?>"/>
            </div>
            <div class="form-group">
                <label class="control-label">Open in New Window <span class="error">*</span></label><br>
                <input type="radio" name="new_window" value="1" <?php echo set_radio('new_window', '1', false); ?> />Yes
                <input type="radio" name="new_window" value="0" <?php echo set_radio('new_window', '0', TRUE); ?> />No
            </div>
            <p style="text-align:center"><input class="btn btn-primary" type="submit" name="button" id="button" value="Submit"></p>
        </form>
    </div>
</div>