<h1>Manage Reviews</h1>
<?php $this->load->view('inc-messages');?>
<?php if(count($enquiries) == 0) { $this->load->view(THEME.'messages/inc-norecords'); } else {?>
<div class="tableWrapper">
<table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
<tr>
  <th width="2%">Id</th>
  <th width="">Product Name</th>
  <th width="">Name</th>
  <th width="">Summary</th>
  <th width="">Review</th>
  <th width="">Ip</th>
  <th width="">rating</th>
  <th width="">review</th>
</tr>
<?php foreach($enquiries as $item) {  ?>
<tr class="<?php echo alternator('','alt')?>">
  <td valign="top"><?php echo $item['id'];?></td>
  <td><?php echo $item['product_name'];?></td>
  <td><?php echo $item['name'];?> </td>
  <td><?php echo $item['summary'];?> </td>
  <td><?php echo $item['review'];?> </td>
  <td><?php echo $item['ip'];?> </td>
  <td><?php echo $item['rating'];?></td>
  <td><a href="reviews/<?php echo $item['status']=="1" ? "disable" : "enable"?>/<?php echo $item['id'];?>"><?php echo $item['status']=="1" ? "Disable" : "Enable"?></a> | <a href="reviews/delete/<?php echo $item['id'];?>" onclick="return confirm('Are you sure you want to Delete this Enquiry?');">Delete</a></td>
</tr>
<?php } ?>
</table>
</div>
<p style="text-align:center"><?php echo $pagination;?></p>
<?php } ?>