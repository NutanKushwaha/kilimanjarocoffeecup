<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <h3 style="margin: 0;"><i class="fa fa-navicon"></i> Edit Menu</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="cms/menu" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-share" title="Manage Menu"></i> Back</h3></a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0 mar-top15">
    <?php
        $attr = [];
        $attr['id'] = 'addcatform';
        echo form_open('cms/menu/edit/'.$menu['menu_id'], $attr);
    ?>
        <div class="form-group">
            <label class="control-label">Menu Name <span class="error">*</span></label>
            <input  maxlength="100" type="text" class="form-control" name="menu_name" id="menu_name" value="<?php echo set_value('menu_name', $menu['menu_name']); ?>"/>
        </div>
        <div class="form-group">
            <label class="control-label">Menu Alias</label>
            <input  maxlength="100" type="text" class="form-control" name="menu_alias" id="menu_alias" value="<?php echo set_value('menu_alias', $menu['menu_alias']); ?>"/>
        </div>
        <p style="text-align:center">Fields marked with <span class="error">*</span> are required.</p>
        <p style="text-align:center"><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>
    </form>
</div>