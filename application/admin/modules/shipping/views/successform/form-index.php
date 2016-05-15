<h1>Success Forms Settings</h1>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<?php if(count($formsuccess) == 0) { $this->load->view(THEME.'messages/inc-norecords'); } else {?>
<div class="tableWrapper">
  <table width="100%" border="0" cellpadding="2" cellspacing="0">
    <tr>
      <th width="84%">Form</th>
      <th width="16%">Action</th>
    </tr>
    <?php foreach($formsuccess as $item) { ?>
    <td><?php echo $item['form_name'];?></td>
      <td><a href="settings/successform/edit/<?php echo $item['form_alias'];?>">Edit</a></td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php } ?>
<p style="text-align:center">
  <?php echo $pagination;?>
</p>
