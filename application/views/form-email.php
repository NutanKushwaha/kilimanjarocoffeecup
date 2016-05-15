<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
    <?php foreach ($email_data as $field => $value) { ?>
        <tr>
            <td width="30%"><?php echo $field; ?></<td>
            <td width="70%"><?php echo $value; ?></td>
        </tr>
    <?php } ?>
</table>