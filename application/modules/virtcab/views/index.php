<link href="<?= base_url(); ?>css/virtcabcss/fileinput.css" rel="stylesheet" />
<script src="<?= base_url(); ?>js/virtualcabjs/fileinput.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script src="<?= base_url(); ?>/admin/js/bootstrap-multiselect.js"></script>
<link href="<?= base_url(); ?>/admin/css/bootstrap-multiselect.css" rel="stylesheet" />
<div class="col-lg-8">
    <div class="corner4" id="ctx_menu"> 
        <a href="<?php echo base_url() ?>customer/dashboard/applied_properties">Applied Properties</a> | 
        <a href="<?php echo base_url() ?>virtcab">Virtual Cabinet</a> | 
        <a href="<?php echo base_url() ?>customer/dashboard/profile">Profile</a> | 
        <a href="<?php echo base_url() ?>customer/dashboard/change_pass">Change Password</a> | 
        <a href="<?php echo base_url() ?>customer/logout">Logout</a>
    </div>
    <br/>
    <div class="form-group col-sm-12">
        <?php // $this->load->view(THEME . 'messages/inc-messages'); ?>
        <div id="popup"></div>
    </div>
    <header class="panel-heading">
        <div class="row">
            <div class="col-sm-12" style="text-align: center">
                <h3 style="margin: 0">Virtual Cabinet</h3>
                <p style="margin-bottom: 0 ">Upload a file (Max Size :25mb)</p>
            </div>
        </div>
    </header>
    <?php // if (!$this->aauth->isAdmin()): ?>
    <div class="form-group form-bg col-sm-12 mar-top15 padding-0">
        <form method="POST" action="<?= createUrl('virtcab/fillcab') ?>" enctype="multipart/form-data" name="multiVir" id="multiVir">
            <div class="col-sm-9 padding-0">
                <input name="virtfile" type="file" size="10" class="btn btn-primary">
            </div>
            <div class="col-sm-3 padding-0">
                <input type="button" id="uploadCab" value='Share file' class="btn btn-primary pull-right">
            </div>
            <input type="hidden" name="virCabShareMainId" id="virCabShareMainId" value="" />
            <input type="hidden" name="virCabShareMainGrpId" id="virCabShareMainGrpId" value="" />
            <div id="popup" style="display: none;">
                <span class="button b-close"><span>X</span></span>
                <div class="clearfix" style="margin:0 0 10px 0;">
                    <h4>Choose to whom you want to share file, if not selected it remain private</h4>
                    <div class="col-sm-4">
                        <?php
                        $js = 'class="form-control" id="virCabShare" ';
                        $AvailGrps['0'] = 'Choose';
                        ksort($AvailGrps);
                        echo form_dropdown('virCabShare', $AvailGrps, '', $js);
                        ?>
                    </div>
                    <div class="col-sm-8">
                        <label class="col-md-5 control-label" id="grpLabel"></label>
                        <div class="col-md-7" id="grpSelect">
                            <?php ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="text-align: right">
                    <input type="button" class="btn btn-primary" name="submitFile" id="submitFile" value="Share" />
                </div>
            </div>
        </form>
    </div>
    <?php // endif; ?>
    <div class="col-sm-12 mar-bot10 padding-0">
        <div class="input-group custom-search-form" style="width: 100%;">
            <form name="search_file" id="search_file" action="virtcab/" method="POST" class="form-horizontal">
                <div class="input-group">
                    <input type="text" name="searchfile" id="searchfile" class="form-control" placeholder="search">
                    <input name="currentTab" type="hidden" id="currentTab" value="1">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="searchbutton">
                            <span class="fa fa-search"></span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li data-id="1" class="<?= $tab1 ?>"><a href="#tabs-1" data-toggle="tab">Shared with me.</a></li>
            <li data-id="2" class="<?= $tab2 ?>"><a href="#tabs-2" data-toggle="tab">My Files</a></li>
        </ul>
        <div class="tab-content clearfix pad-top10" style="box-shadow: 0px 0px 2px ">
            <div class="tab-pane <?= $tab1 ?>" id="tabs-1">
                <div class="form-group col-sm-12 padding-0" style="margin-bottom: 0">                       
                    <div class="" style="width: 100%; max-height:660px; padding: 0; overflow: auto">
                        <?php
                        echo $sharedWithMeHtml;
                        ?>
                    </div>
                </div>

            </div>
            <div class="tab-pane <?= $tab2 ?>" id="tabs-2">
                <div class="form-group col-sm-12 padding-0">
                    <div class="" style="width: 100%; height:500px; padding: 0; overflow: auto">
                        <?php
                        echo $myFilesHtml;
                        ?>
                    </div>
                    <div id="editVirtCab" style="display: none;">
                        <span class="button b-close"><span>X</span></span>
                        <form id="editFileInfo" name="editFileInfo"  action ="virtcab/setEditPermission" method="POST">
                            <input type="hidden" name="editFileId" value="" id="editFileId" />                            
                            <div id="editFormField">

                            </div>
                        </form>
                        <div class="col-sm-12" style="text-align: right">
                            <input type="button" class="btn btn-primary" name="editFilePermission" id="editFilePermission" value="Share" />
                        </div>
                    </div>
                </div>          
            </div>        
        </div>
    </div>
</div>

<script>
    $(function () {
<?php if ($addEditJs) { ?>
            $('.editPermission').on('click', function () {
                var file_id = $(this).attr('data-id');
                $('#editFileId').val(file_id);
                var req_url = '<?= createUrl('virtcab/getFilePermissionOpt/'); ?>' + file_id;
                $.ajax({
                    url: req_url,
                    type: "GET",
                    success: function (data, textStatus, jqXHR)
                    {
                        var data = jQuery.parseJSON(data);
                        $('#editFormField').html(data.msg);
                    }
                });
                $('#editVirtCab').bPopup();
            });
            $('.removeFile').on('click', function () {
                var file_id = $(this).attr('data-id');
                $.ajax(
                        {
                            url: '<?= base_url(); ?>virtcab/deletefile/' + file_id,
                            type: "POST",
                            success: function (data, textStatus, jqXHR)
                            {
                                var data = jQuery.parseJSON(data);
                                if (data.success == 1) {
                                    window.location.reload();
                                } else if (data.success == 0) {
                                    alert(data.msg);
                                }
                            }
                        });
            });
            $('#editFilePermission').on('click', function () {
                $("#editFileInfo").submit();
            });
<?php } ?>
        $('.nav-tabs li').on('click', function () {
            $('#currentTab').val($(this).attr('data-id'));
        });
        $('#searchbutton').on('click', function () {
            if ($('#searchfile').val() == "") {
                bootbox.alert("Please search parameters must be fill");
                return false;
            }
            $("#search_file").submit();
        });
        $('#submitFile').on('click', function () {
            $("#multiVir").submit();
        });
        $("#virCabShare").multiselect({
            onChange: function (option, checked, select) {
                var grp_id = $('#virCabShare').val();
                $('#grpLabel').html('');
                $('#grpSelect').html('');
                var req_url = '<?= createUrl('virtcab/getGrpUsers/'); ?>' + grp_id;
                $('#virCabShareMainId').val(grp_id);
                $.ajax({
                    url: req_url,
                    type: "GET",
                    success: function (data, textStatus, jqXHR)
                    {
                        var data = jQuery.parseJSON(data);
                        $('#grpLabel').append("Choose Option");
                        $('#grpSelect').append(data.msg);
                    }
                });
            }
        });
        $("#uploadAssign").hide();
        $('#uploadCab').on('click', function () {
            $('#popup').bPopup();
        });
        $('#allUser').on('click', function () {
            var selected = $('#users').find(':selected').length;
            var selectcount = $('#users').find('option').length;
            if (selected <= selectcount) {
                $('#users option').prop('selected', true);
            } else {
                $("#users option").prop("selected", false);
            }
        });
        $('#users option').on('click', function () {
            var selected = $('#users').find(':selected').length;
            var selectcount = $('#users').find('option').length;
            if (selected != selectcount) {
                $('#allUser').prop('checked', false);
            } else {
                $('#allUser').prop("checked", true);
            }
        });
    });
</script>
<style>
    #popup, .bMulti , #editVirtCab{
        background-color: #FFF;
        border-radius: 10px 10px 10px 10px;
        box-shadow: 0 0 25px 5px #999;
        color: #111;
        display: none;
        min-width: 450px;
        //min-height: 250px;
        padding: 25px;
    }

    #popup .logo, #editVirtCab .logo {
        color: #2B91AF;
        font: bold 325% 'Petrona',sans;
    }

    .button.b-close, .button.bClose {
        border-radius: 7px 7px 7px 7px;
        box-shadow: none;
        font: bold 131% sans-serif;
        padding: 0 6px 2px;
        position: absolute;
        right: -7px;
        top: -7px;
    }

    .button {
        background-color: #2B91AF;
        border-radius: 10px;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
        color: #FFF;
        cursor: pointer;
        display: inline-block;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
    }
</style>
