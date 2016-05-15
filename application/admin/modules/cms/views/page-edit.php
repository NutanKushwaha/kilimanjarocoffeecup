
<script type="text/javascript" src="<?php echo base_url();?>js/ckeditor/ckeditor.js"></script>
<header class="panel-heading">
    <div class="row">
        <div class="col-sm-10">
            <h3 style="margin: 0;">Edit-<?php echo $page_details['page_title']; ?></h3>
        </div>
        <div class="col-sm-2" style="text-align: right">
            <a href="cms/page/index"><h3 style="cursor: pointer; margin: 0; color: #fff"><i class="fa fa-home" title="Manage Pages"></i></h3></a>
        </div>
    </div>
</header>
<form action="cms/page/edit/<?php echo $page_details['page_id']; ?>/<?php echo $target; ?>/" method="post" enctype="multipart/form-data" name="regFrm" id="regFrm">
    <div class="nav-tabs-custom clearfix">        
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
                    <input type="radio" name="page_status" value="Draft" <?php echo set_radio('page_status', 'Draft', ($page_details['page_status'] == 'Draft')); ?> />Draft
                    <input type="radio" name="page_status" value="Published" <?php echo set_radio('page_status', 'Published', ($page_details['page_status'] == 'Published')); ?> />Published
                </div>
                <div class="form-group">
                    <label class="control-label">Page Title <span class="error">*</span></label>
                    <input  maxlength="100" type="text" required="required" class="form-control"  name="page_title" id="page_title" value="<?php echo set_value('page_title', $page_details['page_title']); ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Parent Page <span class="error">*</span></label>
                    <?php echo form_dropdown('parent_id', $parent, set_value('parent_id', $page_details['parent_id']), ' class="form-control"'); ?>
                </div>
                <div class="form-group">
                    <label class="control-label">Browser Title</label>
                    <input  maxlength="100" type="text" class="form-control" name="browser_title" id="browser_title" value="<?php echo set_value('browser_title', $page_details['browser_title']); ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Contents</label>
                    <!----<textarea  maxlength="100" style="height: 500px" type="text" class="form-control editor" name="contents" id="contents" ><?php echo set_value('contents', $page_details['page_contents']); ?></textarea>--->
                    <?php 
                    $default_value = $page_details['page_contents'];
                    echo $this->ckeditor->editor('contents',@$default_value);?>
                </div>
            </div>
            <div class="tab-pane" id="tabs-2">
                <div class="form-group">
                    <label class="control-label">Page URI - &nbsp;(Will be auto-generated if left blank)</label>
                    <?php if ($page_details['do_not_delete'] == 1) { ?>
                        <input name="page_uri" type="text" class="form-control" id="page_uri" readonly="page_uri" value="<?php echo set_value('page_uri', $page_details['page_uri']); ?>" >
                    <?php } else { ?>
                        <input name="page_uri" type="text" class="form-control" id="page_uri"  value="<?php echo set_value('page_uri', $page_details['page_uri']); ?>" >
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label class="control-label">Meta Keywords</label>
                    <textarea  maxlength="100" type="text" class="form-control" name="meta_keywords" id="meta_keywords" ><?php echo set_value('meta_keywords', $page_details['meta_keywords']); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Meta Description</label>
                    <textarea  maxlength="100" type="text" class="form-control" name="meta_description" id="meta_description" ><?php echo set_value('meta_description', $page_details['meta_description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Additional Header Contents</label>
                    <textarea  maxlength="100" type="text" class="form-control editor" name="before_head_close" id="before_head_close" ><?php echo set_value('before_head_close', $page_details['before_head_close']); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Additional Footer Contents</label>
                    <textarea  maxlength="100" type="text" class="form-control editor" name="before_body_close" id="before_body_close" ><?php echo set_value('before_body_close', $page_details['before_body_close']); ?></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="page_id" id="page_id" value="<?php echo $page_details['page_id']; ?>">
                    <input type="hidden" name="language_code" id="language_code" value="<?php echo $page_details['language_code']; ?>">
                </div>
            </div>
            <div class="tab-pane" id="tabs-3">
                <div class="form-group">
                    <label class="control-label">Template <span class="error">*</span></label>
                    <?php echo form_dropdown('page_template', $page_templates, set_value('page_template', $page_details['template_id']), ' class="form-control"'); ?>
                </div>
            </div>
            <div class="tab-pane" id="tabs-4">
                <div class="form-group clearfix" >
                    <div class="col-sm-12 padding-0">
                        <label class="control-label">Block<span class="error">*</span></label>
                    </div>
                    <div class="col-sm-10 padding-0">
                        <?php echo form_dropdown('page_blocks', $globlblocks, '', 'class="form-control" id="globalblocklist"'); ?>
                    </div>
                    <div class="col-sm-2 padding-0">
                        <button type="button" name="add-block" value="Add" id="addBlock" class=" btn btn-primary pull-right">Add</button>
                    </div>

                    <?php
                    $rowHtml = '';
                    $index = 1;
                    echo '<table id="addGlobalBlock" class="table">';
                    foreach ($selectedBlocks as $key => $keyVal) {
                        $rowHtml .= '<tr id="tblRow' . $index . '">'
                                . ' <td> Block -> </td>'
                                . ' <td>' . $keyVal['block_alias'] . '</td>'
                                . ' <td><button class="btn btn-danger" type="button" onclick="removeRow(\'tblRow' . $index . '\');" id="removeBlock" >Remove</button></td>'
                                . ' <input type="hidden" name="blockadd[]" value="' . $keyVal['block_id'] . '" />'
                                . '</tr>';
                        $index += 1;
                    }
                    echo $rowHtml;
                    echo '</table>';
                    ?>
                </div>
            </div>
         
        </div>
        <p style="text-align:center">
            <input type="submit" name="button" id="button" value="Save" class="btn btn-primary">&nbsp;<input type="submit" name="button" id="button" value="Save and close" class="btn btn-primary">
        </p>
    </div>
</form>
<script>
    $('#addBlock').on('click', function () {
        var totalRows = $('#addGlobalBlock tr').length;
        var selectedText = $("#globalblocklist option:selected").text();
        var selectedVal = $("#globalblocklist option:selected").val();
        var trHtml = '<tr id="tblRow' + totalRows + '"><td> Block -> </td>'
                + '<td>' + selectedText + '</td>'
                + '<td><button type="button" onclick="removeRow(\'tblRow' + totalRows + '\');" id="removeBlock" class="btn btn-danger">Remove</button></td>'
                + '<input type="hidden" name="blockadd[]" value="' + selectedVal + '" /></tr>';
        console.log(trHtml);
        $('#addGlobalBlock').append(trHtml);
    });
    function removeRow(id) {
        $('#' + id).remove();
    }
</script>
