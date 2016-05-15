<h1>
    <?php echo $market_email == 1 ? 'Manage Email Templates' : 'Manage Email Content' ?></h1>

<div id="ctxmenu"><a href="email_message/add">Add Email Template</a></div>
<?php
if (count($messages) == 0) {
    $this->load->view('inc-norecords');
} else {
    ?>
    <div class="tableWrapper">
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="grid">
            <tr>
                <th width="80%">Email Name</th>
                <th width="20%">Action</th>
            </tr>
            <?php foreach ($messages as $item) { ?>
                <tr  class="<?php echo alternator('', 'alt'); ?>">
                    <td><?php echo $item['email_name']; ?></td>
                    <td><a href="email_message/edit/<?php echo $item['email_content_id']; ?>">Edit</a>
                        | 
                        <a href="email_message/delete/<?php echo $item['email_content_id'] ?>"  
                        onclick="return confirm('Are you sure you want to Delete this Email Template?');">Delete</a> 
                        
                </tr>
            <?php } ?>
        </table>
    </div>
<?php } ?>
