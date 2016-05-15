<?PHP
$comp_color = com_get_theme_menu_color();
$base_color = '#783914';
$hover_color = '#d37602';
if ($comp_color) {
    $base_color = com_arrIndex($comp_color, 'theme_menu_base', '#f27733');
    $hover_color = com_arrIndex($comp_color, 'theme_menu_hover', '#d37602');
}
?>

<style>
    /*    
        
    */

    .btn-primary {
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: <?= $hover_color; ?> !important;
        border-color: <?= $base_color; ?> !important    ;
    }
    .pagination > .active > a, .pagination > .active > span, 
    .pagination > .active > a:hover, .pagination > .active > span:hover, 
    .pagination > .active > a:focus, .pagination > .active > span:focus{
        background-color: <?= $base_color; ?> !important;
        border-color: <?= $hover_color; ?> !important;
    }
    
</style>


<?php     
    $attr = [
        'id' => 'user-bonus-product-distribution',
        'name' => 'user-bonus-product-distribution',
    ];    
    echo form_open(current_url(), $attr);
    $hidden_user = [
        'type'  => 'hidden',
        'name'  => 'selected_user',
        'id'    => 'selected_user',
        'value' => '0',
    ];
    $hidden_store = [
        'type'  => 'hidden',
        'name'  => 'store_changed',
        'id'    => 'store_changed',
        'value' => '0',
    ];
    echo form_input($hidden_user);
    echo form_input($hidden_store);
?>    
    <div class="col-lg-12 ">
        <div class="col-sm-12" style="text-align: center;">
            <h4>Bonus product distribution</h4>
        </div>
        <?= $this->load->view(THEME . 'layout/inc-menu-only-dashboard');  ?>
        <div class="col-sm-4"> 
            <?= form_dropdown('department', $departments, '' , ' class="form-control" id="department"'); ?>
        </div>
        <div class="col-sm-2" id="stores">
			<?= form_dropdown('company_store', $company_stores, '' , ' class="form-control" id="company_store"'); ?>
			<small>Store change will reset distribution</small>
		</div>		
        <div class="col-sm-1" id="allowted-prod-calc">
            <button id="distribute" type="submit" class="btn btn-primary">Distribute!</button>
        </div>             
    </div>
    <div class="col-lg-12 ">
        <div class="tableWrapper" id="prod-user-view">
            
        </div>
    </div>
    <div class="clearfix"></div>
    <?= form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
			
            $('#department').on('change', function (e) {
                $("#selected_user").val("0");
                getDeptUserProdCombView();
            })
            $('#company_store').on('change', function (e) {
                $("#selected_user").val("0");
                $("#store_changed").val("1");
                getDeptUserProdCombView();
            })            
        });

        function getDeptUserProdCombView(){
            if ( $('#department').val() == "0" || $('#department').val() == "") {
                $('#comMsgModalTitle').html('Department');
                $('#comMsgModalBody').html("Please select department");
                $('#comMsgModal').modal('show');
                return false;
            }
             $.ajax({
                type: "POST",
                data: $('#user-bonus-product-distribution').serialize(),
                url: "product_allocation/ajax/product_allocation/getDeptUserProdDistCombView",
                success: function(data){
                        $('#prod-user-view').html("");                        
                        data = JSON.parse(data);
                        if(data.success){
                            $('#prod-user-view').html(data.html);
                            $( "input[name='<?= $this->security->get_csrf_token_name() ?>']" ).val(data.csrf_hash);
                        }
                    }
                });            
        }
    </script>
