<h1>
    <?php echo $messages['marketing_email'] == 1 ? 'Edit Email Template' : 'Edit Email Content' ?></h1>

<div id="ctxmenu"><a href="<?php echo $messages['marketing_email'] == 1 ? 'email_message/index/1' : 'email_message/index' ?>"><?php echo $messages['marketing_email'] == 1 ? 'Manage Email Template' : 'Manage Email Content' ?></a></div>
<form action="email_message/edit/<?php echo $messages['email_content_id']; ?>" method="post" enctype="multipart/form-data" name="editemlform" id="editemlform">
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
        <tr>
            <th>Name <span class="error">*</span></th>
            <td><input name="email_name" type="text" class="textfield" size="40" id="email_name" value="<?php echo set_value('email_name', $messages['email_name']); ?>" /></td>
        </tr>
        <tr>
            <th>Email Subject <span class="error">*</span></th>
            <td><input name="email_subject" type="text" class="textfield" size="40" id="email_subject" value="<?php echo set_value('email_subject', $messages['email_subject']); ?>" /></td>
        </tr>
        <tr>
            <th width="15%">Email Content <span class="error">*</span></th>
            <td width="85%">
                <?php
                $default_value = com_gParam('email_content', 0, $messages['email_content']);
                echo $this->ckeditor->editor('email_content', @$default_value);
                ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Fields marked with <span class="error">*</span> are required.</td>
        </tr>
    </table>
    <p style="text-align:center"><input type="submit" name="button" id="button" value="Submit"></p>
</form>