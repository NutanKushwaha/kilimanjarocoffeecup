<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <h3 style="margin: 0;"> <i class="fa fa-navicon"></i> Edit Store</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="company/store/" class="btn btn-primary">
                <h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-share fa-lg" title="Manage Menus"></i> Back</h3>
            </a>
        </div>
    </div>
</header>
<div class="col-sm-12 padding-0 mar-top15">
    <?php         
        $attr = [];
        $attr['id'] = 'addcatform';
        echo form_open('company/store/edit/'.$storeDetail[ 'id' ], $attr);
    ?>
        <div class="form-group">
            <label class="control-label">Store Name <span class="error">*</span></label>
            <input  maxlength="100" type="text" class="form-control" name="store_name" 
                    id="store_name" value="<?php echo set_value('store_name', $storeDetail[ 'store_name' ] ); ?>" required/>
        </div>
        <div class="hide form-group">
            <label class="control-label">Active<span class="error">*</span></label>
            <?php
                $options = [];
                $options[ '0' ] = 'De-Active';
                $options[ '1' ] = 'Active';
                echo form_dropdown('is_active', $options, set_value('is_active', $storeDetail[ 'is_active' ]), ' id="is_active" required');
            ?>
        </div>
        <p style="text-align:center">Fields marked with <span class="error">*</span> are required.</p>
        <p style="text-align:center"><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>
    <?php
        echo form_close();
    ?>
</div>
