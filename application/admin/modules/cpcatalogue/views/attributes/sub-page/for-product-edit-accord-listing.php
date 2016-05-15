<?php   
  $ids = array();
  foreach ($config_product as $prod_key => $prod_value) {
  $current_time  = time();  
  $current_time  .= $prod_value['product_id'];  
  $ids[] = $prod_value['product_id']; 
?>
<div class="panel panel-primary">
        <div class="panel-heading"> <!-- panel-heading -->
            <h4 class="panel-title"> <!-- title 1 -->
            <a data-toggle="collapse" data-parent="#accordion-panel" href="#accordion<?php echo $current_time; ?>">
              Add Configurable Product              
            </a>
           </h4>
        </div>
		<!-- panel body -->        
        <div id="accordion<?php echo $current_time; ?>" class="panel-collapse collapse">
          <div class="panel-body">
            <div class="col-sm-12">
                <?php
                      echo form_hidden('product_id['.$prod_value['product_id'].']', $prod_key);
                      $unique_fld = array();
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
                              if($val['is_sys']){
                                $html .= '<input type="file" name="'.$val['sys_label'].'['.$prod_value['product_id'].']'.'" value="Add Image" JS_FLD/>';
                              }else{
                                $html .= '<input type="file" name="'.'attribute['.$val['id'].']['.$prod_value['product_id'].']'.'" value="Add Image" JS_FLD/>';
                              }
                              if($prod_value['product_image']){
                                $new_image_url = com_get_product_image( $prod_value['product_image'],50 ,50);
                                $html .= '<div class="row" style="margin-top: 10px">
                                          <div class="col-lg-12">
                                            <div class="col-lg-6" style="padding:0">
                                              <img width="50px" height="50px" src="'.$new_image_url.'" /></div>
                                              <div class="col-lg-6" style="padding:0"><button type="button" data-id="'.$prod_value['product_id'].'" class="del_image btn btn-danger pull-right">Delete</button></div>
                                          </div>                                            
                                        </div>';
                              }
                            break;

                            case 'TEXTBOX':
                              if($val['is_numeric']){
                                $class_arr[] = ' allownumericwithdecimal ';
                              }
                              if($val['is_sys']){
                                $html .= form_input($val['sys_label'].'['.$prod_value['product_id'].']', $prod_value[$val['sys_label']], 'JS_FLD');
                              }else{
                                $html .= form_input('attribute['.$val['id'].']['.$prod_value['product_id'].']', isset( $prod_value['attribute'][$val['id']] ) ? $prod_value['attribute'][$val['id']] : '', 'JS_FLD');
                              }
                            break;

                            case 'TEXTAREA':
                              $other = ' " cols="20" rows="2" ';
                              if($val['is_sys']){
                                $html .= form_textarea($val['sys_label'].'['.$prod_value['product_id'].']', $prod_value[$val['sys_label']], 'JS_FLD');
                              }else{
                                $html .= form_textarea('attribute['.$val['id'].']['.$prod_value['product_id'].']', isset( $prod_value['attribute'][$val['id']] ) ? $prod_value['attribute'][$val['id']] : '', 'JS_FLD');
                              }
                            break;

                            case 'DROPDOWN':                
                              if($val['is_sys']){                    
                                $html .= form_dropdown($val['sys_label'].'['.$prod_value['product_id'].']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), $prod_value[$val['sys_label']], 'JS_FLD');
                              }else{
                                $html .= form_dropdown('attribute['.$val['id'].']['.$prod_value['product_id'].']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), isset( $prod_value['attribute'][$val['id']] ) ? $prod_value['attribute'][$val['id']] : '', 'JS_FLD');
                              }
                            break;

                            case 'MULTISELECT':
                              if($val['is_sys']){                    
                                $html .= form_multiselect($val['sys_label'].'['.$prod_value['product_id'].']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), $prod_value[$val['sys_label']], 'JS_FLD');
                              }else{
                                $html .= form_multiselect('attribute['.$val['id'].']['.$prod_value['product_id'].']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), isset( $prod_value['attribute'][$val['id']] ) ? $prod_value['attribute'][$val['id']] : '', 'JS_FLD');
                              }
                            break;

                            case 'DATE';
                                $class_arr[] = 'make-read-only';
                                $js_id = 'datetimepicker'.$val['id'];
                                if($val['is_sys']){
                                  $html .= form_input($val['sys_label'].'['.$prod_value['product_id'].']', $prod_value[$val['sys_label']], 'JS_FLD');
                                }else{
                                  $html .= form_input('attribute['.$val['id'].']['.$prod_value['product_id'].']', isset( $prod_value['attribute'][$val['id']] ) ? $prod_value['attribute'][$val['id']] : '', 'JS_FLD');
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
<?php
  }
?>
<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      <?php
          foreach ($date_fields as $key => $value) {
              echo "$('".$value."').datepicker({
                      format: \"dd/mm/yyyy\"
                      });";
          }
          if(count($date_fields)){
              echo '$(".make-read-only ").keypress(function (e) {
                      return false;});';
          }
      ?>

      $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
        //this.value = this.value.replace(/[^0-9\.]/g,'');
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
          event.preventDefault();
        }
      });
            
    });
    uniques_flds = [<?php echo implode(",", $unique_fld); ?>];
    products_ids = [<?php echo implode(",", $ids); ?>];
</script>
