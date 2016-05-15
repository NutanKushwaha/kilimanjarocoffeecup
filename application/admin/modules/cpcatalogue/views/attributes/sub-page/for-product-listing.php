
<th width="15%">Attribute set Options</th>
<td width="85%">
	<table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">
    <?php
        $date_fields = array();
    	foreach($attr_list as $k => $val){
    		$html = '<tr>
                    <th width="15%">'.$val['label'].'</th>
                    <td width="85%">';
    		switch($val['type']){
    			case 'TEXTBOX':
    				$JS = ' class="form-control" ';
    				$html .= form_input('attribute['.$val['id'].']', '', $JS);
    			break;

    			case 'TEXTAREA':
    				$JS = ' class="form-control" cols="20" rows="2" ';
    				$html .= form_textarea('attribute['.$val['id'].']', '', $JS);
    			break;

    			case 'DROPDOWN':
    				$JS = ' class="form-control"';
    				$html .= form_dropdown('attribute['.$val['id'].']', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', $JS);
    			break;

    			case 'MULTISELECT':
    				$JS = ' class="form-control"';
    				$html .= form_multiselect('attribute['.$val['id'].'][]', isset($attr_opt[$val['id']]) ? $attr_opt[$val['id']] : array(), '', $JS);
    			break;

                case 'DATE';

                    $JS = ' class="form-control make-read-only"  id="datetimepicker'.$val['id'].'"';
                    //$html .= '<div class="input-group input-append date" id="datetimepicker'.$val['id'].'">';
                    $html .= form_input('attribute['.$val['id'].']', '', $JS);
                    //$html .= '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
                    //$html .= '</div>';
                    $date_fields[] = '#datetimepicker'.$val['id'];
                break;
    		}

    		$html .= '</td></tr>';
    		echo $html;
    	}           
	?>
	</table>
</td>
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
    });
</script>