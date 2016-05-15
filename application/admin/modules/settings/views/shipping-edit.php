<header class="panel-heading">
    <div class="row">
        <div class="col-lg-1">
            <i class="fa fa-cog fa-2x"></i>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Edit Configure Settings</h3>
        </div>
    </div>
</header>
<div class="col-md-12">
    <?php
        $attr = [];
        $attr['id'] = 'addcatform';
        echo form_open('settings/settings/index/'.$group_id, $attr);
    ?>
        <div class="nav-tabs-custom clearfix"> 
            <ul class="nav nav-tabs" id="tabs-nav">
                <?php
                $i = 0;
                foreach ($groups as $group) {
                    $i++;
                    ?>
                                    <!--<li style="float: left"><a href="<?php echo current_url(); ?>#tabs-<?php echo $i; ?>"><?php echo $group['config_group']; ?></a></li>-->
                <?php } ?>
            </ul>
            <div class="tab-content mar-top20">
                <?php
                $i = 0;
                foreach ($groups as $group) {
                    $i++;
                    ?>
                    <div id="tabs-<?php echo $i; ?>" class="tab-pane active">
                        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="formtable">
                            <?php
                            if (isset($settings[$group['config_group_id']])) {
                                foreach ($settings[$group['config_group_id']] as $row) {
                                    if($row['config_comments'] == 'shipping'){
                                    $key = $row['config_key'];
                                    $label = $row['config_label'];
                                    $val = $row['config_value'];
                                    $field_type = $row['config_field_type'];
                                    $field_options = $row['config_field_options'];
                                    $comment = $row['config_comments'];
                                    ?>
                                    <tr>
                                        <td width="20%" valign="top" ><strong><?php echo $label; ?></strong></td>
                                        <td width="80%"><?php  include("settings/$field_type.php"); ?></td>
                                    </tr>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <p align="center"><input type="submit" name="button" id="button" value="submit" class="btn btn-primary"></p>
        </div>
    </form>    
</div>
<script>
	 $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
        //this.value = this.value.replace(/[^0-9\.]/g,'');
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
          event.preventDefault();
        }
      });
</script>
