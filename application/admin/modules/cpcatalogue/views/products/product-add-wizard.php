<link rel="stylesheet" href="css/wizard/normalize.css">
<link rel="stylesheet" href="css/wizard/main.css">
<link rel="stylesheet" href="css/wizard/jquery.steps.css">
<script src="js/wizard/modernizr-2.6.2.min.js"></script>
<script src="js/wizard/jquery.cookie-1.3.1.js"></script>
<script src="js/wizard/jquery.steps.js"></script>
<header class="panel-heading">
    <div class="row">
        <div class="col-sm-12">
            <h2 style="margin: 0;"> <i class="fa fa-plus-square"></i> Add Product</h2>
        </div>
        <div class="col-sm-2" style="text-align: right">
            <a href="cpcatalogue/product"><h3 style="cursor: pointer; margin: 0; color: #fff"><i class="fa fa-home" title="Manage Products"></i></h3></a>
        </div>
    </div>
</header>
<?php $this->load->view(THEME . 'messages/inc-messages'); ?>
<script>
var uniques_flds = [];
$(function ()
{

    $("#wizard").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "slideLeft",

        onStepChanging: function (event, currentIndex, newIndex)
        {            
            var return_bool = true;            
            switch(currentIndex){
                case 0:
                    var attr_set = $('#attribute_set_id').val();
                    var prod_type = $('#product_type_id').val(); 
                    if($('#attribute_set_id').val() == '' || 
                        $('#product_type_id').val() == ''
                        ){
                        $('#error-msg').html("<pre>Please select Type or Attribute Set</pre>");
                        return_bool = false;
                    }
                    if(return_bool){
                           $('#error-msg').html("");
                            $.get('cpcatalogue/product/getFields',{'set_id' : attr_set , 'prod_type' : prod_type},function(data){
                                        data = JSON.parse(data);                        
                                        $('#general').html(data.html);
                            });
                    }
                break;

                case 1:                    
                    var error_msg = "<pre>Please Fill the required fields in both General and Attribute</pre>";
                    $.each($('.common-field :input[type=text][required]'), function( index, value ) {
                        var current_val = $(this).val();
                        if(current_val == ""){
                            $(this).addClass('has-error');
                            return_bool = false;                         
                        }else{
                            $(this).removeClass('has-error');
                        }
                    });
                    $.each($('.common-field select[required]'), function( index, value ) {
                        var current_sel_val = $(this).val();
                        if(current_sel_val == ""){
                            $(this).addClass('has-error');
                            return_bool = false;
                        }else{
                            $(this).removeClass('has-error');
                        }
                    });

                    $.each($('.attribute-field :input[type=text][required]'), function( index, value ) {
                        var current_val = $(this).val();
                        if(current_val == ""){
                            $(this).addClass('has-error');
                            return_bool = false;
                        }else{
                            $(this).removeClass('has-error');
                        }
                    });
                    $.each($('.attribute-field select[required]'), function( index, value ) {
                        var current_sel_val = $(this).val();
                        if(current_sel_val == ""){
                            $(this).addClass('has-error');
                            return_bool = false;
                        }else{
                            $(this).removeClass('has-error');
                        }
                    });


                    $.each($('.common-field :input[type=file]'), function( index, value ) {
                        var fileUpload = value;
                        if( fileUpload.value ){
                            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png|.gif)$");
                            if (!regex.test(fileUpload.value.toLowerCase())) {
                                $(this).addClass('has-error');
                                return_bool = false;
                                error_msg = error_msg + "<pre>Image must be JPG|PNG|GIF</pre>";
                            }else {
                                $(this).removeClass('has-error');
                            }
                        }
                    });

                    $.each($('.attribute-field :input[type=file]'), function( index, value ) {
                        var fileUpload = value;
                        if( fileUpload.value ){
                            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png|.gif)$");
                            if (!regex.test(fileUpload.value.toLowerCase())) {
                                $(this).addClass('has-error');
                                return_bool = false;
                                error_msg = error_msg + "<pre>Image must be JPG|PNG|GIF</pre>";
                            }else {
                                $(this).removeClass('has-error');
                            }
                        }
                    });


                    $.each(uniques_flds, function( index, value ) {
                        var fld_val = "";
                        var fld_values = [];
                        var is_duplicate = false;
                        $( value ).each(function( index ) {
                          fld_val  =  $(this).val();
                          if(fld_val){
                            if(jQuery.inArray( fld_val, fld_values ) >= 0){
                              is_duplicate = true;
                              $(this).addClass('has-error');
                            }else{
                              $(this).removeClass('has-error');
                              fld_values.push($(this).val());
                            }
                            
                            if(is_duplicate == true){
                                return_bool = false;
                                error_msg = error_msg +"<pre>Field must contain unique values</pre>";                                
                            }else{
                                var sys_attr = 0;
                                var attrib_sys = $(this).attr("data-sys");
                                var unique_class =  $(this).attr('data-unique');
                                if (typeof attrib_sys !== typeof undefined && attrib_sys !== false) {
                                    sys_attr = 1;
                                }                                
                                $.ajax({
                                    method: "GET",
                                    url: "cpcatalogue/product/check_unique",
                                    data: {  fld : unique_class, val : fld_val, sys_attr : attrib_sys },
                                    async:false
                                })
                                  .done(function( data ) {
                                        data = JSON.parse(data);
                                        if(data.success == 1){
                                            if(data.unique == false){
                                                $(this).addClass('has-error');
                                                return_bool = false;
                                                error_msg = error_msg +"<pre>Field already occupied</pre>";
                                            }
                                        }else{
                                            $(this).addClass('has-error');
                                            return_bool = false;
                                            error_msg = error_msg +"<pre>Server error in checking</pre>";
                                        }
                                  });
                            }
                          }
                        });
                    });
                
                    if(return_bool == false){
                        $('#error-msg').html(error_msg);
                    }else{
                        $('#error-msg').html("");
                    }                    
                break;
            }

            var wizardid = '#wizard-t-'+ currentIndex;            
            jQuery(wizardid).parent('li').addClass('disabled');            
            return return_bool;
        },
        onFinished: function (event, currentIndex)
        {
            $('#addprodform').submit();
        }        
    });
});
</script>
<style type="text/css">
    .form-cate-product-container .content {
        overflow-x:hidden;
        overflow-y:scroll;
        height: 500px; 
    }
    #error-msg pre{
        color: #FF3111;
        border:1px solid #FF3111;
    }
    .panel-title small{
        color: #d7d7d7;
        font-size: 11px;
    }
</style>        
    <?php         
        $FORM_JS = ' name="addprodform" id="addprodform" ';
        echo form_open_multipart(current_url(), $FORM_JS);
    ?>
            <input type="hidden" name="add_product" value="1">
            <div id="error-msg" class="error">                
            </div>
            <div class="form-cate-product-container">
            <div id="wizard">                
                <h2>Settings</h2>
                
                    <section>
                        <div class="col-sm-12">
                            <div class="row">
                                <label>Attribute set</label>
                                <?php
                                    $option = array('1' => 'Default');
                                    $JS = ' class="form-control" onchange="getAttributes(this);" id="attribute_set_id"';
                                    echo form_dropdown('attribute_set_id', $option, '', $JS);
                                ?>
                            </div>                        
                            <div class="row">
                                <label>Product Type</label>
                                <i><small> -Type is not-editable</small></i>
                                <?php
                                    $option = array('1' => 'Simple');
                                    $JS = ' class="form-control" id="product_type_id"';
                                    echo form_dropdown('product_type_id', $option, '', $JS);
                                ?>
                            </div>
                        </div>
                    </section>


                <h2>General</h2>
                    <section>
                        <div id="general" style="vertical-sc">
                        </div>
                    </section>

                <h2>Metadata</h2>
                <section>
                    <div class="col-sm-12">
                        <div class="row">
                            <label>Meta Title</label>
                            <input name="product_meta_title" type="text" id="product_meta_title" class="form-control" value="<?php echo set_value('product_meta_title'); ?>" />
                        </div>
                        <div class="row">
                            <label>Meta Keywords </label>
                            <textarea name="product_meta_keywords" cols="40" rows="4" class="form-control" id="product_meta_keywords"><?php echo set_value('product_meta_keywords'); ?></textarea>
                        </div>
                        <div class="row">
                            <label>Meta Description</label>
                            <textarea name="product_meta_description" cols="40" rows="4" class="form-control" id="product_meta_description"><?php echo set_value('product_meta_description'); ?></textarea>
                        </div>
                    </div></section>
            </div>
            </div>
            <?php echo form_close(); ?>
<script>
    function getAttributes(select){
        $.get('cpcatalogue/attributes/getAttributeForSetId/'+select.value, function(data){
            data = JSON.parse(data);
            $('#attribute_options').html(data.html);
        });
    }
    function showConfig(select){
        if(select.value == '2'){            
            $('#accordion').css('display', 'block');
        }else{            
            $('#accordion').css('display', 'none');
            $('#accordion').html('');
        }
    }
    function getSetAttributes(ths){
        $(ths).attr('disabled','true');    
        $('#add-cofigurable').css('display', 'none');
        $.get('cpcatalogue/attributes/getAttributeForSetId/'+$('#attribute_set_id').val(),{data:'accord'}, function(data){
            data = JSON.parse(data);
            $('#accordion').append(data.html);
            setTimeout(function(){
                $(ths).removeAttr('disabled');
            }, 1*1000);
        });
        $('#add-cofigurable').css('display', 'block');
    }
</script>
