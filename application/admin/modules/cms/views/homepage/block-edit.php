<h1>Edit Block: <?php echo $pages['page_title']; ?><?php if($pages['language_code'] != 'en') { echo '('.$page_lang['language'].')'; } ?></h1>
<div id="ctxmenu"><a href="cms/homepageblock/index/<?php echo $pages['page_id']; ?>">Manage Blocks</a></div>
<?php $this->load->view(THEME.'messages/inc-messages');?>
<div style="float: left; width: 100%">
<div id="tabs">
    <ul class="nav" id="tabs-nav">
        <li><a href="#tabs-1">Main</a></li>
    </ul>

<form action="cms/homepageblock/edit/<?php echo $block['block_id']; ?>" method="post" enctype="multipart/form-data" name="add_frm" id="add_frm">
  <div id="tabs-1" class="tab">
  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
  <tr>
      <th>Block Title <span class="error">*</span></th>
      <td><input type="text" name="block_title" id="block_title" class="textfield width_30"  value="<?php echo set_value('block_title', $block['block_title']);?>" /></td>
    </tr>
	<tr>
      <th>Block Image</th>
      <td><?php if($block['block_image']) {?>
      <img src="<?php echo $this->config->item('HOMEPAGEBLOCK_IMAGE_URL').$block['block_image'];?>" border="0" width="100px" /> <?php } ?><br />
      <input name="image" type="file" id="image" size="35" class="textfield">  <br />
			<small>Only .jgp,.gif,.png images allowed</small> </td>
    </tr>
	<tr>
		<td width="15%"><b>Read More URL <span class="error">*</span></b></td>
		<td width="85"><input name="readmore_url" type="text" class="textfield" id="readmore_url" size="50" value="<?php echo set_value('readmore_url', $block['readmore_url']); ?>" /></td>
	</tr>
    <tr>
      <th>Block Content</th>
      <td><textarea name="block_contents" class="textfield width_99" id="block_contents width_99" ows="5"><?php echo set_value('block_contents', $block['block_contents']);?></textarea></td>
    </tr>
    <tr>
      <td><input name="v_image" type="hidden" id="v_image" value="1" /><input type="hidden" name="block_id" id="block_id" value="<?php echo $block['block_id'];?>">&nbsp;</td>
      <td>Fields marked with <span class="error">*</span> are required.</td>
    </tr>
  </table>
  </div>
  <p style="text-align:center"><input type="submit" name="button" id="button" value="Submit"></p>
</form>
</div>
</div>