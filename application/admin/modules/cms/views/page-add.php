<header class="panel-heading">
    <div class="row">
        <div class="col-sm-10">
            <h3 style="margin: 0;">Add Page</h3>
        </div>
        <div class="col-sm-2" style="text-align: right">
            <a href="cms/page/index"><h3 style="cursor: pointer; margin: 0; color: #fff"><i class="fa fa-home" title="Manage Pages"></i></h3></a>
        </div>
    </div>
</header>
<form action="cms/page/add" method="post" enctype="multipart/form-data" name="regFrm" id="regFrm">
    <div class="nav-tabs-custom ">        
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tabs-1" data-toggle="tab">Main</a></li>
            <li><a href="#tabs-2" data-toggle="tab">Metadata</a></li>
            <li><a href="#tabs-3" data-toggle="tab">Template</a></li>
            <li><a href="#tabs-4" data-toggle="tab">Block</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1">
                <div class="form-group">
                    <label class="control-label">Status <span class="error">*</span></label>
                    <input type="radio" name="page_status" value="Draft" <?php echo set_radio('page_status', 'Draft', TRUE); ?> />Draft
                    <input type="radio" name="page_status" value="Published" <?php echo set_radio('page_status', 'Published'); ?> />Published
                </div>
                <div class="form-group">
                    <label class="control-label">Page Title <span class="error">*</span></label>
                    <input  maxlength="100" type="text" required="required" class="form-control"  name="page_title" id="page_title" value="<?php echo set_value('page_title'); ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Parent Page <span class="error">*</span></label>
                    <?php echo form_dropdown('parent_id', $parent, set_value('parent_id'), ' class="form-control"'); ?>
                </div>
                <div class="form-group">
                    <label class="control-label">Browser Title</label>
                    <input  maxlength="100" type="text" class="form-control" name="browser_title" id="browser_title" value="<?php echo set_value('browser_title'); ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Contents</label>
                    <!--
                        <textarea  maxlength="100" style="height: 500px" type="text" class="form-control editor" name="contents" id="contents" ><?php echo set_value('contents'); ?></textarea> 
                    -->
                    <?php 
                    $default_value = set_value('contents');
                    echo $this->ckeditor->editor('description',@$default_value);?>
                </div>
            </div>
            <div class="tab-pane" id="tabs-2">
                <div class="form-group">
                    <label class="control-label">Page URI - &nbsp;(Will be auto-generated if left blank)</label>
                    <input  maxlength="100" type="text" class="form-control"  name="page_uri" id="page_uri" value="<?php echo set_value('page_uri'); ?>" size="45"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Meta Keywords</label>
                    <textarea  maxlength="100" type="text" class="form-control" name="meta_keywords" id="meta_keywords" ><?php echo set_value('meta_keywords'); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Meta Description</label>
                    <textarea  maxlength="100" type="text" class="form-control" name="meta_description" id="meta_description" ><?php echo set_value('meta_description'); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Additional Header Contents</label>
                    <textarea  maxlength="100" type="text" class="form-control editor" name="before_head_close" id="before_head_close" ><?php echo set_value('before_head_close'); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Additional Footer Contents</label>
                    <textarea  maxlength="100" type="text" class="form-control editor" name="before_body_close" id="before_body_close" ><?php echo set_value('before_body_close'); ?></textarea>
                </div>
            </div>
            <div class="tab-pane" id="tabs-3">
                <div class="form-group">
                    <label class="control-label">Template <span class="error">*</span></label>
                    <?php echo form_dropdown('page_template', $page_templates, set_value('page_template'), ' class="form-control"'); ?>
                </div>
            </div>
            <div class="tab-pane clearfix" id="tabs-4">
                <div class="form-group" id="addGlobalBlock">
                    <div class="col-sm-12">
                        <label class="control-label">Block<span class="error">*</span></label>
                    </div>
                    <div class="col-sm-10">
                        <?php echo form_dropdown('page_blocks', $globlblocks, '', 'class="form-control " id="globalblocklist"'); ?>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" name="add-block" value="Add" id="addBlock" class=" btn btn-primary">Add</button>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <p style="text-align:center"><input class="btn btn-primary" type="submit" name="button" id="button" value="Submit"></p>
        <p style="text-align:center">Fields marked with <span class="error">*</span> are required.</p>
    </div>
</form>
<script>
    $('#addBlock').on('click', function () {
        var totalRows = $('#addGlobalBlock tr').length;
        var selectedText = $("#globalblocklist option:selected").text();
        var selectedVal = $("#globalblocklist option:selected").val();
        var trHtml = '<table class="table"><tr id="tblRow' + totalRows + '"><td width="20%"> Block -> </td>'
                + '<td width="50%">' + selectedText + '</td>'
                + '<td width="40%"><button type="button" onclick="removeRow(\'tblRow' + totalRows + '\');" id="removeBlock" class="btn btn-danger">Remove</button></td>'
                + '<input type="hidden" name="blockadd[]" value="' + selectedVal + '" /></tr></table>';
        console.log(trHtml);
        $('#addGlobalBlock').append(trHtml);
    });
    function removeRow(id) {
        $('#' + id).remove();
    }
</script>
