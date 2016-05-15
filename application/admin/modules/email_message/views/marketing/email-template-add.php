<h1>Add Email Template</h1>
<div id="ctxmenu"><a href=" email_message/index/1">Manage Email Template</a></div>
<form action="email_message/add/" method="post" enctype="multipart/form-data" name="editemlform" id="editemlform">
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">

        <tr>
            <th>Name <span class="error">*</span></th>
            <td><input name="email_name" type="text" class="textfield" size="40" id="email_name" value="<?php echo set_value('email_name'); ?>" /></td>
        </tr>

        <tr>
            <th>Email Subject <span class="error">*</span></th>
            <td><input name="email_subject" type="text" class="textfield" size="40" id="email_subject" value="<?php echo set_value('email_subject'); ?>" /></td>
        </tr>
        <tr>
            <th width="15%">Email Content <span class="error">*</span></th>
            <td width="85%">
                <?php
                $default_value = com_gParam('category_desc', 0, '');
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
