<link rel="stylesheet" href="css/wizard/normalize.css">
<link rel="stylesheet" href="css/wizard/main.css">
<link rel="stylesheet" href="css/wizard/jquery.steps.css">
<script src="js/wizard/modernizr-2.6.2.min.js"></script>
<script src="js/wizard/jquery.cookie-1.3.1.js"></script>
<script src="js/wizard/jquery.steps.js"></script>
<header class="panel-heading">
    <div class="row">
        <div class="col-sm-10">
            <h3 style="margin: 0;">Edit Product</h3>
        </div>
        <div class="col-sm-2" style="text-align: right">
            <a href="cpcatalogue/product"><h3 style="cursor: pointer; margin: 0; color: #000"><i class="fa fa-home" title="Manage Products"></i></h3></a>
        </div>
    </div>
</header>
<script>
var uniques_flds = [];
var products_ids = [];
$(function ()
{

    $.get("cpcatalogue/product/getdescription/<?php echo $product['product_id'] ?>", function(data){      
      $('#description_prod').html(data);
    });
    $(document).on('click', '.del_image', function(){
        $.get("cpcatalogue/product/removeImage/"+$(this).data("id"), function(data){          
          location.reload();
        });        
    });
    $("#wizard").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "slideLeft",

        onStepChanging: function (event, currentIndex, newIndex)
        {
            switch(currentIndex){
                case 0:
                    var return_bool = true;
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

                    $.each(uniques_flds, function( index, value ) {
                        var fld_val = "";
                        var fld_values = [];
                        var is_duplicate = false;
                        var product_ref = 0;
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
                                error_msg = "<pre>Field must contain unique values</pre>";
                            }else{
                                var sys_attr = 0;
                                var attrib_sys = $(this).attr("data-sys");
                                var unique_class =  $(this).attr('data-unique');
                                if (typeof attrib_sys !== typeof undefined && attrib_sys !== false) {
                                    sys_attr = 1;
                                }
                                product_ref = 0;
                                if( [index] !== undefined){
                                    product_ref = products_ids[index];
                                }

                                console.log(index);
                                console.log(product_ref);
                                return false;
                                $.ajax({
                                    method: "GET",
                                    url: "cpcatalogue/product/check_unique",
                                    data: { fld : unique_class, 
                                            val : fld_val, 
                                            sys_attr : attrib_sys, 
                                            product_ref : product_ref},
                                    async:false
                                })
                                  .done(function( data ) {
                                        data = JSON.parse(data);
                                        if(data.success == 1){
                                            if(data.unique == false){                                                
                                                $(this).addClass('has-error');
                                                return_bool = false;
                                                error_msg = "<pre>Field already occupied</pre>";
                                            }
                                        }else{
                                            $(this).addClass('has-error');
                                            return_bool = false;
                                            error_msg = "<pre>Server error in checking</pre>";
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
                    return return_bool;
                break;
            }
            /*
                alert("On changing currentIndex" + currentIndex + "New index "+ newIndex);            
                console.log("On changing currentIndex" + currentIndex + "New index "+ newIndex);
            */
            return true;
        },
        onFinished: function (event, currentIndex)
        {
            $('#editprodform').submit();
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
</style>    
<?php
    $FORM_JS = ' name="editprodform" id="editprodform" ';
    echo form_open_multipart( current_url($product['product_id']) , $FORM_JS);
    $hidden = array(
                'type'  => 'hidden',
                'name'  => 'edit_product',
                'id'    => 'edit_product',
                'value' => '1',
                );
    echo form_input($hidden);

    $hidden = array(
                'type'  => 'hidden',
                'name'  => 'product_type_id',
                'id'    => 'product_type_id',
                'value' => $product['product_type_id'],
                );
    echo form_input($hidden);

    $hidden = array(
                'type'  => 'hidden',
                'name'  => 'attribute_set_id',
                'id'    => 'attribute_set_id',
                'value' => $product['attribute_set_id'],
                );
    echo form_input($hidden);        
?>
<div id="error-msg" class="error">
</div>
    <div class="form-cate-product-container">
            <div id="wizard">
                <h2>General</h2>
                    <section>
                        <div id="general" style="vertical-sc">
                            <?php echo $sub_view; ?>
                        </div>
                    </section>

                <h2>Metadata</h2>
                <section>
                    <div class="col-sm-12">
                        <div class="row">
                            <label>Meta Title</label>
                            <input name="product_meta_title" type="text" id="product_meta_title" 
                            class="form-control" value="<?php echo set_value('product_meta_title', $product['product_meta_title']); ?>" />
                        </div>
                        <div class="row">
                            <label>Meta Keywords </label>
                            <textarea name="product_meta_keywords" cols="40" rows="4" class="form-control" id="product_meta_keywords"><?php echo set_value('product_meta_keywords', $product['product_meta_keywords']); ?></textarea>
                        </div>
                        <div class="row">
                            <label>Meta Description</label>
                            <textarea name="product_meta_description" cols="40" rows="4" class="form-control" id="product_meta_description"><?php echo set_value('product_meta_description', $product['product_meta_description']); ?></textarea>
                        </div>
                    </div></section>
            </div>
    </div>
            <?php echo form_close(); ?>
<script>
	
	function supplier_brands( supplier_brands ){
		var suppliers_comp = jQuery.parseJSON('<?= json_encode($suppliers); ?>');
		$('#brand_id > option').remove();
		$('#brand_id').append($('<option>', {
					value: "",
					text : "Select Brands" 
		}));
		$(suppliers_comp).each( function( index, brandOpt ) {
			if( brandOpt.supplier_id  == $( supplier_brands ).val() 
			&& brandOpt.id !== null ){
				$('#brand_id').append($('<option>', {
					value: brandOpt.id,
					text : brandOpt.brand_name 
				}));
			}
		});
	}
	
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
