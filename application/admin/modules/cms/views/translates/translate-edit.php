<h1>Edit Page</h1>
  <div id="ctxmenu"><a href="page/index/">Manage Pages</a> | <a href="translate/index/<?php echo $page_rs['page_id'];?>/">Translate Pages</a> | <a href="block/index/<?php echo $page_details['page_id'];?>">Manage Blocks</a></div>
  <?php $this->load->view(THEME.'messages/inc-messages'); ?>
  <div style="float: left; width: 100%">
    <div id="tabs">
      <ul class="nav" id="tabs-nav">
        <li><a href="#tabs-1">Main</a></li>
        <li><a href="#tabs-2">Metadata</a></li>
        <li><a href="#tabs-3">Template </a></li>
		<li><a href="#tabs-4">Miscellaneous</a>
      </ul>
      <form action="translate/edit/<?php echo $page_rs['page_id'];?>/<?php echo $page_details['page_id']; ?>" method="post" enctype="multipart/form-data" name="regFrm" id="regFrm">
        <div id="tabs-1" class="tab">
          <table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">
            <tr>
              <th width="15%">Page Title <span class="error">*</span></th>
              <td width="85%"><input type="text" name="title" id="title" class="textfield" value="<?php echo set_value('title', $page_details['title']); ?>" size="40"></td>
            </tr>
            <tr>
              <th>Parent Page<span class="error">*</span></th>
              <td><?php echo form_dropdown('parent_id', $parent, set_value('parent_id', $page_details['parent_id']), ' class="textfield"'); ?></td>
            </tr>
            <tr>
              <th>Browser Title</th>
              <td><input name="browser_title" type="text" class="textfield" id="browser_title" value="<?php echo set_value('browser_title', $page_details['browser_title']); ?>" size="40"></td>
            </tr>
            
            <tr>
              <th>Contents</th>
              <td><textarea name="contents" cols="37" rows="5" style="width:99%" class="textfield contents"id="contents"><?php echo set_value('contents', $page_details['block_contents']); ?></textarea></td>
            </tr>

          </table>
        </div>
        <div id="tabs-2" class="tab">
          <table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">
            <tr>
              <th  width="18%">Page URI</th>
              <td  width="82%"><?php if ($page_details['do_not_delete'] == 1) { ?>
                <input name="page_uri" type="text" class="textfield" id="page_uri" readonly="page_uri" value="<?php echo set_value('page_uri', $page_details['page_uri']); ?>" size="40">
                <?php } else { ?>
                <input name="page_uri" type="text" class="textfield" id="page_uri"  value="<?php echo set_value('page_uri', $page_details['page_uri']); ?>" size="40">
                &nbsp;(Will be auto-generated if left blank)
                <?php } ?>
              </td>
            </tr>
              <tr>
              <th>Meta Keywords </th>
              <td><textarea name="meta_keywords" cols="40" rows="4" class="textfield" style="width:99%" id="meta_keywords"><?php echo set_value('meta_keywords', $page_details['meta_keywords']); ?></textarea></td>
            </tr>
            <tr>
              <th>Meta Description</th>
              <td><textarea name="meta_description" cols="40" rows="4" class="textfield" id="meta_description" style="width:99%"><?php echo set_value('meta_description', $page_details['meta_description']); ?></textarea></td>
            </tr>
            <tr>
              <th>Additional Header Contents</th>
              <td><textarea name="before_head_close" cols="40" rows="4" class="textfield" id="before_head_close" style="width:99%"><?php echo set_value('before_head_close', $page_details['before_head_close']); ?></textarea></td>
            </tr>
            <tr>
              <th>Additional Footer Contents</th>
              <td><textarea name="before_body_close" cols="40" rows="4" style="width:99%" class="textfield" id="before_body_close"><?php echo set_value('before_body_close', $page_details['before_body_close']); ?></textarea></td>
            </tr>
            <tr>
              <td><input type="hidden" name="page_id" id="page_id" value="<?php echo $page_details['page_id']; ?>"></td>
              <td></td>
          </table>
        </div>
        <div id="tabs-3" class="tab">
          <table width="100%" border="0" cellspacing="0" cellpadding="4" class="formtable">
            <tr>
              <th width="19%">Template Type <span class="error">*</span></th>
              <td width="81%"><?php echo form_dropdown('template', $page_template, set_value('template', $page_details['template']), ' class="textfield"'); ?></td>
            </tr>
          </table>
        </div>
		 <div id="tabs-4" class="tab">
          <table width="100%" border="0" cellspacing="0" cellpadding="4" class="formtable">
            <tr>
              <th width="16%">Testimonials</th>
              <td width="84%"><?php echo form_dropdown('testimonial_id', $page_testimonials, set_value('testimonial_id', $page_details['testimonial_id']), ' class="textfield width_20"'); ?></td>
            </tr>
            <tr>
              <th width="16%">Banner</th>
              <td width="84%"><?php echo form_dropdown('banner_id', $page_banners, set_value('banner_id', $page_details['banner_id']), ' class="textfield width_20"'); ?></td>
            </tr>
          </table>
        </div>
        <p style="text-align:center">
           <input type="hidden" name="menu_title" id="menu_title" value="<?php echo $page_details['menu_title'];?>" >
          <input type="hidden" name="show_in_menu" id="show_in_menu" value="<?php echo $page_details['show_in_menu'];?>" >
          <input type="submit" name="button" id="button" value="Submit">
        </p>
      </form>
    </div>
  </div>
