<?php 
  $current_time  = time();
?>
<div class="panel panel-primary" id="cont-accord-<?php echo $current_time; ?>">
        <div class="panel-heading"> <!-- panel-heading -->
            <h4 class="panel-title"> <!-- title 1 -->
            <a data-toggle="collapse" data-parent="#accordion-panel" href="#accordion<?php echo $current_time; ?>">
              Add Configurable Product
            </a>
            <?php  if( isset( $fp ) ) { ?>
            <span onclick="$('#cont-accord-<?php echo $current_time; ?>').remove();" class="pull-right"> Remove</span>
            <?php  } ?>
           </h4></div>
		<!-- panel body -->
        
        <div id="accordion<?php echo $current_time; ?>" class="panel-collapse collapse">
          <div class="panel-body">
            <div class="col-sm-12">
            <?php
              echo form_hidden('product_id['.$current_time.']', $current_time);
              $unique_fld = array();
              $date_fields = array();
              foreach($attr_list as $k => $val){
              $class_arr = array();
              $js_id = '';
              $other = '';
              $JS = '';
              if($val['type'] == "FILE"){
                $html = '<div class="row attribute-field">
                          <label>'.$val['label'].' <small>Only GIF, PNG, JPG and size is 1MB.</small>'.'</label>';
              }else{
                $html = '<div class="row attribute-field">
                        <label>'.$val['label'].'</label>';                
              }

              $class_arr[] = 'form-control';
                  
              switch($val['type']){
                case 'FILE':
                  $class_arr[] = 'file-upload';
                  if($val['is_sys']){
                    $html .= '<input type="file" name="'.$val['sys_label'].'['.$current_time.']'.'" value="Add Image" JS_FLD/>';
                  }else{
                    $html .= '<input type="file" name="'.'attribute['.$val['id'].']['.$current_time.']'.'" value="Add Image" JS_FLD/>';
                  }

                break;

                case 'TEXTBOX':
                  if($val['is_numeric']){
                    $class_arr[] = ' allownumericwithdecimal ';
                  }
                  if($val['is_sys']){
                    $html .= form_input($val['sys_label'].'['.$current_time.']', '', 'JS_FLD');
                  }else{
                    $html .= form_input('attribute['.$val['id'].']['.$current_time.']', '', 'JS_FLD');
                  }
                break;

                case 'TEXTAREA':
                  $other = ' " cols="20" rows="2" ';
                  if($val['is_sys']){
                    $html .= form_textarea($val['sys_label'].'['.$current_time.']', '', 'JS_FLD');
                  }else{
                    $html .= form_textarea('attribute['.$val['id'].']['.$current_time.']', '', 'JS_FLD');
                  }                                    
                break;

                case 'DROPDOWN':                
                  if($val['is_sys']){                    
                    $html .= form_dropdown($val['sys_label'].'['.$current_time.']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');
                  }else{
                    $html .= form_dropdown('attribute['.$val['id'].']['.$current_time.']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');
                  }
                break;

                case 'MULTISELECT':
                  if($val['is_sys']){                    
                    $html .= form_multiselect($val['sys_label'].'['.$current_time.']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');                    
                  }else{
                    $html .= form_multiselect('attribute['.$val['id'].']['.$current_time.']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', 'JS_FLD');
                  }
                break;

                case 'DATE';
                    $class_arr[] = 'make-read-only';
                    $js_id = 'datetimepicker'.$val['id'];
                    if($val['is_sys']){
                      $html .= form_input($val['sys_label'].'['.$current_time.']', '', 'JS_FLD');
                    }else{
                      $html .= form_input('attribute['.$val['id'].']['.$current_time.']', '', 'JS_FLD');
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
            ?></div>
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
      </script>
          </div>
        </div>
        </div> 
        </div>