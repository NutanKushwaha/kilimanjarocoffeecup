
<link href="<?php echo base_url() ?>/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />



<?PHP
$comp_color = com_get_theme_menu_color();
$base_color = '#783914';
$hover_color = '#d37602';
if ($comp_color) {
    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#783914');
    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');
}
?>

<style>
    /*    
        
    */

    .btn-primary {
        background-color: <?= $base_color; ?>;
        border-color: <?= $hover_color; ?>;
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: <?= $hover_color; ?>;
        border-color: <?= $base_color; ?>;
    }
    .pagination > .active > a, .pagination > .active > span, 
    .pagination > .active > a:hover, .pagination > .active > span:hover, 
    .pagination > .active > a:focus, .pagination > .active > span:focus{
        background-color: <?= $base_color; ?>;
        border-color: <?=  $hover_color?>;
    }


</style>


<header class="panel-heading">    
    <div class="row">

        <div class="col-sm-9">
            <h3 style="margin: 0;"> <i class="fa fa-user"></i> Users Management</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">            
            <a href="user/add"><h3 style="cursor: pointer; margin: 0;font-size: 15px;" class="btn btn-primary"><i class="fa fa-plus-square" title="Add New user"></i> Add New user </h3></a>
        </div>
    </div>
</header>
<div class="clearfix"></div>
<?php
$attr = [
    'name' => 'user-search',
    'id' => 'user-search',
];
echo form_open('user/ajax/user/index/', $attr);
?>
<div class="user-mgmnt-filter-block-top" style="padding-top:20px;">
    <div class="col-sm-8 padding-0">
        <div id="imaginary_container"> 
            <div class="input-group stylish-input-group">
                <?php
                $attr = [
                    'id' => 'userName',
                    'class' => 'form-control',
                    'placeholder' => 'Searh by username'
                ];
                echo form_input('userName', '', $attr);
                ?>                    
                <span class="input-group-addon">
                    <button type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>  
                </span>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <?php
            $attr = [
                'id' => 'userGroup',
                'class' => 'form-control'
            ];
            echo form_dropdown('userGroup', $user_groups, '', $attr);
            ?>              
        </div>       
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<div class="col-lg-12 padding-0" style="padding-top: 15px;" id="user-list-div">
<?= $user_list_view; ?>
</div>
    <?= form_close(); ?>
<script>
    $("#user-search").submit(function (e) {
        var form = $(this);
        $.post(form.attr('action'),
                {'form_data': $('#user-search').serialize(),
                    '<?= $this->security->get_csrf_token_name(); ?>': $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val(),
                    'ajax': '1',
                    'offset': '0',
                },
                function (data) {
                    data = JSON.parse(data);
                    if (data.success) {
                        $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val(data.csrf_hash);
                        $('#user-list-div').html(data.html);
                    }
                }
        );
        e.preventDefault(); //STOP default action        
    });
</script>
