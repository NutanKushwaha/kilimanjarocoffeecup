<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <h3 style="margin: 0;"> <i class="fa fa-arrow-left" title="Manage Categories"></i> Add Category</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="cpcatalogue/category/index" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-wrench" title="Manage Categories"></i> Manage Categories </h3></a>
        </div>
    </div>
</header>
<?php $this->load->view(THEME . 'messages/inc-messages'); ?>
    <?php
        $FORM_JS = '  name="addcatform" id="addcatform" ';
        echo form_open_multipart(current_url(), $FORM_JS);
    ?>    
    <div class="form-group">
        <div class="col-sm-12">
            <label>Parent Category<span class="red">*</span></label>            
            <?php echo form_dropdown('parent_id', $parent, set_value('parent_id'), ' class="form-control"'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>Category Name<span class="red">*</span></label>
            <input type="text" class="form-control" name="category" 
                    id="category" placeholder="Category name *" 
            value="<?php echo set_value('category'); ?>">
        </div>        
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>URL Alias<span class="red">*</span>&nbsp;(Will be auto-generated if left blank)</td></label>
            <input  type="text" class="form-control" name="category_alias" 
                    id="category_alias" placeholder="category alias" 
                    size="40" 
                    value="<?php echo set_value('category_alias'); ?>" />            
        </div>
    </div>    
    <div class="form-group">
        <div class="col-sm-4">
            <label>Image <small>Only .jgp,.gif,.png images allowed</small></label>
            <input type="file" class="form-control" name="image" id="image" style="border: none;box-shadow: none;padding: 0;">            
        </div>
        <div class="col-sm-8">
            <div class="checkbox" style="text-align:right;">
                <label>
                    <br/>
                    <input type="checkbox" name="checkbox" value="1" />
                    
                    <strong style="position: relative; top: 2px;"> Show Full Description </strong>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">    
            <label>Description</label>
             <?php 
                    //$default_value = set_value('category_desc');
                    $default_value = com_gParam('category_desc', 0, '');
                    echo $this->ckeditor->editor('category_desc',@$default_value);?>
        </div>
    </div>    
    <div class="form-group">
        <div class="col-sm-12">    
            <p style="text-align:center">
                <input class="btn btn-primary" type="submit" name="button" id="button" value="Submit">
            </p>
        </div>
    </div>
<?php echo form_close(); ?>
