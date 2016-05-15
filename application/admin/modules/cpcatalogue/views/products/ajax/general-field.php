<?php
  $unique_fld = array();
?>

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
        <div id="accordionOne" class="panel-collapse">
          <div class="panel-body">
            <div class="col-sm-12">
                <div class="row attribute-field">
                    <label>Select Category<span class="error">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <?php
                            $listhtml = '';
                            com_makeDropDownList($categories, $listhtml, set_value('category_id'));
                            echo $listhtml;
                         ?>
                    </select>
                </div>

                <div class="row attribute-field">
                    <label>Product Name<span class="error">*</span></label>
                    <?php
                        $unique_fld['product_name'] = '".product_name"';
                        $JS = ' class="form-control uniquefld product_name" id="product_name" required data-unique="product_name" data-sys="1"';
                        echo form_input('product_name[]', set_value('product_name'), $JS);
                    ?>         
                </div>

                <div class="row attribute-field">
                    <label>URL Alias</label>
                    <?php
                        $unique_fld['product_alias'] = '".product_alias"';
                        $JS = ' class="form-control uniquefld product_alias" id="product_alias" required data-unique="product_alias" data-sys="1"';
                        echo form_input('product_alias[]', set_value('product_alias'), $JS);
                    ?>
                </div>

                <div class="row attribute-field">
                    <label>SKU</label>
                    <?php
                        $unique_fld['product_sku'] = '".product_sku"';
                        $JS = ' class="form-control uniquefld" id="product_sku" required data-unique="product_sku" data-sys="1"';
                        echo form_input('product_sku[]', set_value('product_sku'), $JS);
                    ?>        
                </div>

                <div class="row attribute-field">
                    <label>Price</label>
                    <?php
                        $JS = ' class="form-control allownumericwithdecimal" id="product_price" required';
                        echo form_input('product_price[]', set_value('product_price'), $JS);
                    ?>
                </div>

             <!--    <div class="row attribute-field">
                    <label>Point</label>
                    <?php
                        // $JS = ' class="form-control allownumericwithdecimal" id="product_point" required';
                       // echo form_input('product_point[]', set_value('product_point'), $JS);
                    ?>
                </div>

                <div class="row attribute-field">
                    <label>Featured</label>
                    <?php
                        // $JS = ' class="form-control" id="is_featured"';
                       // echo form_dropdown('is_featured',array('0' => 'No', '1' => 'Yes'), set_value('is_featured'), $JS);
                    ?>
                </div> -->

               <!--  <div class="row attribute-field">
                    <label>Supplier Name</label>
                    <?php
                        $JS = ' class="form-control" id="supplier_id" onchange="supplier_brands( this);"';
                       // echo form_dropdown('supplier_id',$options ,set_value('supplier_id'), $JS);
                    ?>        
                </div>

                <div class="row attribute-field">
                    <label>Brands</label>
                    <?php
						$options = [];
                        $JS = ' class="form-control" id="brand_id"';
                        //echo form_dropdown('brand_id',$options ,set_value('brand_id'), $JS);
                    ?>        
                </div> -->
                <!-- 
                <div class="row attribute-field">
                    <label>Stock Level</label>
                    <?php
                        // $JS = ' class="form-control allownumericwithoutdecimal" id="stock_level" required';
                      //  echo form_input('stock_level[]', set_value('stock_level'), $JS);
                    ?>
                </div> -->
<!-- 
                <div class="row attribute-field">
                    <label>Weignt gms</label>
                    <?php
                        //$JS = ' class="form-control  allownumericwithdecimal" id="weight" required';
                      //  echo form_input('weight[]', set_value('weight'), $JS);
                    ?>
                </div> -->
                <div class="row attribute-field">
                    <label style="float:left;">New mark from:</label>
                    <div class='col-sm-4'>
                          <div class='input-group date' id='new_from'>
                              <input name="new_from" type='text' class="form-control ddlselect" />
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                          </div>
                    </div>
                    <label style="float:left;">to:</label>
                    <div class='col-sm-4'>
                          <div class='input-group date' id='new_to'>
                              <input name="new_to" type='text' class="form-control ddlselect" />
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                          </div>                    
                    </div>
                </div>
                <div class="row attribute-field">
                    <label>Description</label>        
                    <?php
                        $default_value = '';
                        echo $this->ckeditor->editor('product_description',@$default_value);
                    ?>
                </div>

                <div class="row">
                    <label>Images</label>
                    <input type="file" name="product_image[]" class="form-control" />
                </div>    
            </div>
          </div>
        </div>
  </div>
    <div class="panel panel-primary">
   <!-- 
        <div class="panel-heading"> 
            <h4 class="panel-title"> 
            <a data-toggle="collapse" data-parent="#accordion" href="#accordionTwo">
             Attribute <small>Other</small>
            </a>
           </h4>
        </div> -->
        <!-- panel body -->
        <div id="accordionTwo" class="panel-collapse collapse">
          <div class="panel-body">
            <div class="col-sm-12">
            <?php              
              $date_fields = array();
              foreach($attr_list as $k => $val){
              $class_arr = array();
              $js_id = '';
              $other = '';
              $JS = '';                
              $html = '<div class="row attribute-field">
                        <label>'.$val['label'].'</label>';

              $class_arr[] = 'form-control';
                  
              switch($val['type']){
                case 'FILE':
                $html .= '<input type="file" name="Image" value="Add Image" JS_FLD/>';
                break;

                case 'TEXTBOX':
                  if($val['is_numeric']){
                    $class_arr[] = ' allownumericwithdecimal ';
                  }
                  if($val['is_sys']){
                    $html .= form_input($val['sys_label'].'[]', '', 'JS_FLD');
                  }else{
                    $html .= form_input('attribute['.$val['id'].'][]', '', 'JS_FLD');
                  }
                break;

                case 'TEXTAREA':
                  $other = ' " cols="20" rows="2" ';
                  if($val['is_sys']){
                    $html .= form_textarea($val['sys_label'].'[]', '', 'JS_FLD');
                  }else{
                    $html .= form_textarea('attribute['.$val['id'].'][]', '', 'JS_FLD');
                  }                                    
                break;

                case 'DROPDOWN':                
                  if($val['is_sys']){                    
                    $html .= form_dropdown($val['sys_label'].'[]', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');
                  }else{
                    $html .= form_dropdown('attribute['.$val['id'].'][]', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');
                  }
                break;

                case 'MULTISELECT':
                  if($val['is_sys']){                    
                    $html .= form_multiselect($val['sys_label'].'[]', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');                    
                  }else{
                    $html .= form_multiselect('attribute['.$val['id'].'][]', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');
                  }
                break;

                case 'DATE';
                    $class_arr[] = 'make-read-only';
                    $js_id = 'datetimepicker'.$val['id'];
                    if($val['is_sys']){
                      $html .= form_input($val['sys_label'].'[]', '', 'JS_FLD');
                    }else{
                      $html .= form_input('attribute['.$val['id'].'][]', '', 'JS_FLD');
                    }
                    $date_fields[] = '#datetimepicker'.$val['id'];
                break;
              }

              if($val['is_sys']){
                $other .= ' data-sys="1" ';
              }

              if($val['is_unique']){
                $unique_class = $val['is_sys']?$val['sys_label']:'attribute_'.$val['id'];
                $class_arr[] = 'uniquefld';
                $class_arr[] = $unique_class;
                $other  .= ' data-unique="'.$unique_class.'"';

                $unique_fld[$unique_class] = '".'.$unique_class.'"';
              }

              $JS = ' class="'.implode(' ' , $class_arr).' " '.($js_id?' id="'.$js_id.'" ':' ').$other.($val['required']?'required':'');
              $html = str_replace("JS_FLD",$JS,$html);
              $html .= '</div>';
              echo $html;
            }
            ?>

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
	
    $(function (){
      $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });


        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        }); 

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
uniques_flds = [<?php echo implode(",", $unique_fld); ?>];            
</script>
