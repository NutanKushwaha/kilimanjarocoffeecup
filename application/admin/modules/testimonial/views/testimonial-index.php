<h1>Manage Testimonials</h1>
<div id="ctxmenu"><a href="testimonial/add/">Add Testimonial</a></div>
<?php $this->load->view('inc-messages');?>
<?php if(count($testimonials) == 0) { $this->load->view('inc-norecords'); } else {?>
<div class="tableWrapper">
<table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
<tr>
  <th width="50%"> Name</th>
  <!--<th width="51%">Testimonial</th>-->
  <th width="50%">Action</th>
</tr>
<?php  foreach($testimonials as $row){ ?>
<tr class="<?php echo alternator('', 'alt');?>">
  <td><?php echo $row['name'];?></td>
  <!--<td><?php //echo $row['testimonial'];?></td>-->
  <td><a href="testimonial/edit/<?php echo $row['testimonial_id'];?>">Edit</a> | <a href="testimonial/delete/<?php echo $row['testimonial_id']?>" onclick="return confirm('Are you sure you want to Delete this Testimonial?');">Delete</a></td>
</tr>
<?php } ?>
</table>
</div>
<?php } ?>