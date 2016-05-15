<h1>Edit Default Contents : <?php echo $default_content['default_key'];?> </h1>

<div id="ctxmenu"></div>

<?php $this->load->view(THEME.'messages/inc-messages'); ?>

<form action="settings/settings/contents/<?php echo $default_content['default_key'];?>" method="post" enctype="multipart/form-data" name="addcatform" id="addcatform">

      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">

        <tr>

          <td width="140"><strong>Default Page Title </strong></td>

          <td width="552"><input name="default_page_title" type="text" class="textfield" id="default_page_title" size="45"  value="<?php echo set_value('default_page_title', $default_content['default_page_title']);?>" /></td>

        </tr>

         <tr>

          <td width="140"><strong>Default Browser Title </strong></td>

          <td width="552"><input name="default_browser_title" type="text" class="textfield" id="default_browser_title"  size="45" value="<?php echo set_value('default_browser_title', $default_content['default_browser_title']);?>" /></td>

        </tr>

        <tr>

          <td width="140"><strong>Default Contents <span class="error">*</span></strong></td>

          <td width="552"><textarea name="default_content" rows="3" style="width:95%" class="textfield contents" id="default_content"><?php echo set_value('default_content', $default_content['default_content']);?></textarea></td>

        </tr>

        <tr>

          <td width="140"><strong>Default Meta Keywords </strong></td>

          <td width="552"><textarea name="default_meta_keywords"  rows="3" style="width:95%" class="textfield" id="default_meta_keywords"><?php echo set_value('default_meta_keywords', $default_content['default_meta_keywords']);?></textarea></td>

        </tr>

        <tr>

          <td width="140"><strong>Default Meta Description </strong></td>

          <td width="552"><textarea name="default_meta_description" rows="3" style="width:95%" class="textfield" id="default_meta_description"><?php echo set_value('default_meta_description', $default_content['default_meta_description']);?></textarea></td>

        </tr>

        

  </table>

   <h3><strong>Available Tags</strong></h3>

    <?php echo $default_content['default_tag'];?>

    

     <p style="text-align:center"><input type="submit" name="button" id="button" value="Submit"></p>

</form>