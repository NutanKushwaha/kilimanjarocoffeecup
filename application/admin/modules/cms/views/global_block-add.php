<header class="panel-heading">
    <div class="row">
        <div class="col-sm-10">
            <h3 style="margin: 0;">Add Global Block</h3>
        </div>
        <div class="col-sm-2" style="text-align: right">
            <a href="cms/globalblock"><h3 style="cursor: pointer; margin: 0; color: #fff"><i class="fa fa-home" title="Manage Global Blocks"></i></h3></a>
        </div>
    </div>
</header>
<div class="col-lg-12 padding-0">    
    <form action="cms/globalblock/add" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
        <div class="nav-tabs-custom ">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Details</a></li>
                <li><a href="#tab_2" data-toggle="tab">Template</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">
                        <div class="col-lg-3">Block Title <span class="error">*</span></div>
                        <div class="col-lg-9"><input type="text" name="block_title" id="block_title" class="form-control" size="40" value="<?php echo set_value('block_title'); ?>" /></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">Block Alias <span class="error">*</span></div>
                        <div class="col-lg-9"><input type="text" name="block_alias" id="block_alias" class="form-control" size="40" value="<?php echo set_value('block_alias'); ?>" /></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">Block Image <span class="error">*</span></div>
                        <div class="col-lg-9"><input type="file" name="block_image" id="block_image"  size="40" value="" /></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">Block Content</div>
                        <div class="col-lg-9">
                            <?php /*
                            <textarea   name="block_contents" class="form-control editor" 
                                        id="block_contents" cols="25" rows="5"><?php echo set_value('block_alias'); ?>                                        
                            </textarea>                                                        
                                */
                                $default_value = "";
                                echo $this->ckeditor->editor('block_contents',@$default_value);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">Character Limit</div>
                        <div class="col-lg-9"><input type="text" name="char_limit" id="char_limit" class="form-control" size="40" value="<?php echo set_value('char_limit'); ?>" /></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">Read More Link</div>
                        <div class="col-lg-9"><input type="text" name="block_link" id="link" class="form-control" size="40" value="" /></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9">&nbsp;</div>
                        <div class="col-lg-9">Fields marked with <span class="error">*</span> are required.</div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <textarea name="block_template" cols="0" rows="25" style="width:99%" class="form-control  " id="block_template"><?php echo set_value('block_template'); ?></textarea>
                </div>
                <p style="text-align:center"><input name="v_image" type="hidden" id="v_image" value="1" /><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary"></p>

            </div>
        </div>
    </form>
</div>

