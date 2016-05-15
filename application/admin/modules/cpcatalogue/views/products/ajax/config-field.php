<div class="panel-group" id="accordion"> <!-- accordion 1 -->
   <div class="panel panel-primary">
   
        <div class="panel-heading"> <!-- panel-heading -->
            <h4 class="panel-title"> <!-- title 1 -->
            <a data-toggle="collapse" data-parent="#accordion" href="#accordionOne">
              General
            </a>
           </h4>
        </div>
        <!-- panel body -->
        <div id="accordionOne" class="panel-collapse collapse">
          <div class="panel-body">
            <div class="col-sm-12">
                <div class="row common-field">
                    <label>Select Category<span class="error">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <?php
                            $listhtml = '';
                            com_makeDropDownList($categories, $listhtml, set_value('category_id'));
                            echo $listhtml;
                         ?>
                    </select>
                </div>

                <div class="row common-field">
                    <label>Featured</label>
                    <?php
                        $JS = ' class="form-control attribute-field" id="is_featured"';
                        echo form_dropdown('is_featured',array('0' => 'No', '1' => 'Yes'), set_value('is_featured'), $JS);
                    ?>
                </div>

                <!-- <div class="row common-field">
                    <label>Supplier Config Name</label>
                    <?php
                        $JS = ' class="form-control" id="supplier_id" onchange="supplier_brands( this);"';
                       // echo form_dropdown('supplier_id',$options, set_value('supplier_id'), $JS);
                    ?>
                </div> -->

                <!-- <div class="row attribute-field">
                    <label>Brands</label>
                    <?php
						$options = [];
                        $JS = ' class="form-control" id="brand_id"';
                        //echo form_dropdown('brand_id',$options ,set_value('brand_id'), $JS);
                    ?>        
                </div> -->
                
                <div class="row attribute-field">
                    <label style="float:left;">New mark from:</label>
                    <div class='col-sm-4'>
                          <div class='input-group date' id='new_from'>
                              <input name="new_from" type='text' class="form-control ddlselect" autocomplete="off"/>
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                          </div>
                    </div>
                    <label style="float:left;">to:</label>
                    <div class='col-sm-4'>
                          <div class='input-group date' id='new_to'>
                              <input name="new_to" type='text' class="form-control ddlselect" autocomplete="off"/>
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                          </div>                    
                    </div>
                </div>

                <div class="row common-field">
                    <label>Description</label>
                    <?php
                        $default_value = '';
                        echo $this->ckeditor->editor('product_description',@$default_value);
                    ?>
                </div>
            </div>
          </div>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#accordionTwo">
             Attribute <small>Other</small>
            </a>
           </h4>
        </div>
        <!-- panel body -->
        <div id="accordionTwo" class="panel-collapse collapse">
          <div class="panel-body">
                <div class="col-sm-3">
                    <div class="gernal-add-config-product-container">
                        <button value="Add Config Product" onclick="getSetAttributes(this);" 
                                type="button" id="add-configurable" class="btn btn-primary form-control" > <i class="fa fa-plus"></i> Add Config Product</button>
                    </div>
                </div>
              <div class="col-sm-9"></div>
              <div class="clearfix"></div>
              <div class="col-sm-12">
                    <div class="panel-group" id="accordion-panel"><!-- accordion panel -->
                        <?php
                            echo $config_attrib;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery-datetimepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link href="css/datepicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
	
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
	
function getSetAttributes(ths){
    $(ths).attr('disabled','true');
    $.get('cpcatalogue/attributes/getAttributeForSetId/'+$('#attribute_set_id').val(),{data:'accord',fp:'1'}, function(data){
        data = JSON.parse(data);
        $('#accordion-panel').append(data.html);
        setTimeout(function(){
            $(ths).removeAttr('disabled');
        }, 1*1000);
    });    
}
$(function (){
        $('#new_from').datetimepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(date) {
                var maxDate = $('#new_from').datepicker('getDate');
                $("#new_to").datetimepicker("change", { minDate: maxDate });
            }
        });

        $('#new_to').datetimepicker({
            dateFormat: 'yy-mm-dd',
        });
        var startDate = new Date('<?= date("d/m/Y"); ?>');
        var FromEndDate = new Date();
        var ToEndDate = new Date();
        ToEndDate.setDate(ToEndDate.getDate()+365);

        $('#new_from').datepicker({            
            weekStart: 1,
            startDate: '<?= date("d/m/Y"); ?>',
            endDate: FromEndDate, 
            autoclose: true
        })
        .on('changeDate', function(selected){
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('#new_to').datepicker('setStartDate', startDate);
        }); 
        $('#new_to')
            .datepicker({
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true
            })
            .on('changeDate', function(selected){
                FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('#new_from').datepicker('setEndDate', FromEndDate);
            });

        $('.ddlselect').on('keypress', function(e) {
            e.preventDefault();
            return false;
        });
}); 
</script>
