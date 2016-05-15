<h1>Manage Enquiries</h1>
<?php $this->load->view('inc-messages'); ?>
<?php
if ($list['num_rows'] == 0) {
    $this->load->view(THEME . 'messages/inc-norecords');
} else {
    ?>
    <div class="tableWrapper">
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
            <tr>
                
                <th width=""> Name</th>
                <th width="">Email</th>
                <th width="">Subject</th>
                <th width="">description</th>
                <th width="2%">Action</th>
            </tr>
            <?php foreach ($list['result'] as $item) { ?>
                <tr class="<?php echo alternator('', 'alt') ?>">
                    <td valign="top"><?php echo $item['name']; ?></td>
                    <td><?php echo $item['email']; ?></td>
                    <td><?php echo $item['subject']; ?> </td>
                    <td><?php echo $item['description']; ?> </td>
                    <td><a href="contactus/delete/<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to Delete this Enquiry?');">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <p style="text-align:center"><?php echo $pagination; ?></p>
<?php } ?>