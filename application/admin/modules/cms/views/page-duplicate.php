<h1>Duplicate Page: <?php echo $page_details['page_title']; ?></h1>
<div id="ctxmenu"><a href="cms/page/index">Manage Pages</a> | <a href="cms/page/edit/<?php echo $page_details['page_id']; ?>">Edit Page</a> | <a href="cms/block/index/<?php echo $page_details['page_id']; ?>">Manage Blocks</a>
<?php if($page_details['language_code'] == 'en') { ?>| <a href="cms/translate/index/<?php echo $page_details['page_id']; ?>">Translate</a><?php } ?></div>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<div style="float: left; width: 100%">
    <form action="cms/page/duplicate/<?php echo $page_details['page_id']; ?>" method="post" enctype="multipart/form-data" name="regFrm" id="regFrm">
        <table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">
            <tr>
                <th width="15%">Page Title <span class="error">*</span></th>
                <td width="85%"><input type="text" name="page_title" id="page_title" class="textfield width_30" value="<?php echo set_value('page_title'); ?>" ></td>
            </tr>
            <tr>
                <th  width="18%">Page URI</th>
                <td  width="82%"><input name="page_uri" type="text" class="textfield width_30" id="page_uri"  value="<?php echo set_value('page_uri'); ?>" >
                    &nbsp;(Will be auto-generated if left blank)
                </td>
            </tr>
            <tr>
                <th>Parent Page<span class="error">*</span></th>
                <td><?php echo form_dropdown('parent_id', $parent, set_value('parent_id', $page_details['parent_id']), ' class="textfield"'); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Fields marked with <span class="error">*</span> are required.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="button" id="button" value="Submit"></td>
            </tr>


        </table>
    </form>
</div>
