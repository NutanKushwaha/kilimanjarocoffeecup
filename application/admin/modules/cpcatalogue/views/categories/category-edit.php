<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <h3 style="margin: 0;"> <i class="fa fa-pencil-square-o" title="Edit Category"></i> Edit Category</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <a href="cpcatalogue/category/index">
                <h3 style="cursor: pointer; margin: 0; color: #000;">
                    <i class="fa fa-home" title="Manage Categories"></i>
                </h3>
            </a>
        </div>
    </div>
</header>
<?php 
    $this->load->view(THEME . 'messages/inc-messages');
    $FORM_JS = ' name="addcatform" id="editcatform" enctype="multipart/form-data"';
    echo form_open(current_url($current_category['category_id']), $FORM_JS);
?>
    <div class="form-group">
        <div class="col-sm-12">
            <label>Parent Category<span class="red">*</span></label>            
            <?php echo form_dropdown('parent_id', $parent, set_value('parent_id', $current_category['parent_id']), ' class="form-control"'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>Category Name<span class="red">*</span></label>
            <?php
                $data = array(
                    'name'  => 'category',
                    'id'    => 'category',
                    'value' => set_value('category', $current_category['category']),
                    'class' => 'form-control',
                    'placeholder' => 'Category name *',
                );
                echo form_input($data);
            ?>
        </div>
    </div>    
    <div class="form-group">
        <div class="col-sm-12">
            <label>URL Alias<span class="red">*</span>&nbsp;(Will be auto-generated if left blank)</td></label>
            <input  type="text" class="form-control" name="category_alias" 
                    id="category_alias" placeholder="category alias" 
                    size="40" 
                    value="<?php echo set_value('category_alias', $current_category['category_alias']); ?>" />            
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6">
            <label>Image <small>Only .jgp,.gif,.png images allowed</small></label>
            <input type="file" class="form-control" name="image" id="image">
            <?php if ($current_category['category_image'] != '') { ?>
                <img src="<?php echo $this->config->item('CATEGORY_RESIZE_IMAGE_URL').'50_50/'.$current_category['category_image']; ?>" 
                     border="0" style="padding: 5px; border: 2px solid rgb(138, 138, 138);"/><?php } 
            ?><br />
        </div>
        <div class="col-sm-6">
            <ul class="list-unstyled list-inline text-right">
                <li>
                    <div class="checkbox">
                        <label style="text-align:center;">
                            <br/>
                            <input type="checkbox" name="checkbox" 
                                    value="1" 
                                    <?php echo $current_category['is_fulltext'] ? 'checked=checked' : ''; ?>/>
                            <br/>
                            Show Full Description 
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox">
                <label style="text-align:center;">
                    <br/>
                    <input type="checkbox" name="c_active" 
                            value="1" 
                            <?php echo $current_category['c_active'] ? 'checked=checked' : ''; ?>/>
                    <br/>
                    Active/De-active
                </label>
            </div>
                </li>
            </ul>
        </div>        
    </div>    
    <div class="form-group">
        <div class="col-sm-12  pad-top20">
            <label>Description</label>
            <?php 
                //$default_value = set_value('category_desc');                
                $default_value = com_arrIndex($current_category, 'category_desc', isset($_REQUEST['category_desc'])?$_REQUEST['category_desc']:'');
                echo $this->ckeditor->editor('category_desc',@$default_value);
            ?>
        </div>
    </div>        
    <div class="form-group">
        <div class="col-sm-12 ">
            <p style="text-align:center">
                <input class="btn btn-primary" type="submit" name="button" id="button" value="Update">
            </p>
        </div>
    </div>
<?php echo form_close(); ?>
