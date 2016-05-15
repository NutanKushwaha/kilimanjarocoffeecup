<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attribute_tab extends Adminajax_Controller {

    function __construct() {
        parent::__construct();
        $this->is_admin_protected = TRUE;
    }

    function index($pid = FALSE) {
        $this->load->model('cpcatalogue/Productmodel');
        $this->load->model('cpcatalogue/Categorymodel');
        $this->load->model('cpcatalogue/Attributesmodel');
        
        

        $output = array();
        $output['status'] = 1;
        $output['tabs'] = '';
        $output['tab_content'] = '';

        $current_url = $this->input->post('current_url');

        $cid = array();
        $cid = $this->input->post('checked', true);

        //Get Product Detail
        $product = array();
        $product = $this->Productmodel->details($pid);
        if (!$product) {
            $this->utility->show404();
            return;
        }

        $attributes = array();
        if ($cid) {
            foreach ($cid as $row) {
                $attributes_rs = $this->Productmodel->fetchAttributes($row);
                foreach ($attributes_rs as $attr) {
                    $attributes[$attr['category']][] = $attr;
                }
            }


            //Attribute values
            $attribute_values = $this->Productmodel->fetchAttributeValues($product['product_id']);

            $category = array();
            $category = $this->Categorymodel->categoriesTreeForProduct(0, $cid);

            if ($attributes) {
                $output['tabs'] .= '<li  class="dynamic"><a href="' . $current_url . '#tabs-attributes">Attributes</a></li>';
                $output['tab_content'] .= '<div id="tabs-attributes" class="dynamic tab">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">';

                foreach ($attributes as $key => $value) {
                    $output['tab_content'] .= '
                    <tr>
                        <th width="20%">' . $key . ' :Attributes</th>
                        <td width="85%">Only 10 words allowed for post-fix rest will eliminated</th>
                    </tr>';
                    foreach ($value as $attr) {
                        $attribute_type = '<input type="text" 
												name="attribute_field_' . $attr['attribute_id'] . '"   
												class="textfield width_40" 
												id="attribute_field_' . $attr['attribute_id'] . '" 
												value="'.
													set_value('attribute_field_'
														.$attr['attribute_id'], 
														isset($attribute_values[$attr['attribute_id']])
														? $attribute_values[$attr['attribute_id']] : '') 
											. '" />
											<input type="text" 
													name="attribute_postfix['.$attr['attribute_id'].']"  
													class="textfield width_20"
													id="attribute_postfix_' . $attr['attribute_id'] . '" 
													value="'.
													set_value('attribute_postfix[' . $attr['attribute_id'].']', 
													isset($attribute_values['postfix'][$attr['attribute_id']]) 
													? $attribute_values['postfix'][$attr['attribute_id']] : '') 
													. '"
													placeholder="Only 10 words "
											 />
												';

                        $output['tab_content'] .= '
                        <tr>
                            <th width="15%">' . $attr['attribute_label'] . '</th>
                            <td width="85%">' . $attribute_type . '</td>
                        </tr>
                    ';
                    }
                }
                $output['tab_content'] .= '</table>
                </div>';
            } 

            $output['category'] = $category;
            echo json_encode($output);
            exit();
        }
    }

    function add() {		
        $this->load->model('cpcatalogue/Productmodel');
        $this->load->model('cpcatalogue/Categorymodel');
        $this->load->model('cpcatalogue/Attributesmodel');
        
        

        $output = array();
        $output['status'] = 1;
        $output['tabs'] = '';
        $output['tab_content'] = '';

        $current_url = $this->input->post('current_url');

        $cid = array();
        $cid = $this->input->post('checked', true);
		
		if(!$cid){
			$cid = array();
		}
		
        $attributes = array();
        foreach ($cid as $row) {
            $attributes_rs = $this->Productmodel->fetchAttributes($row);
            foreach ($attributes_rs as $attr) {
                $attributes[$attr['category']][] = $attr;
            }
        }

        $category = array();
        $category = $this->Categorymodel->categoriesTreeForProduct(0, $cid);

        if (!empty($attributes)) {
            $output['tabs'] .= '<li  class="dynamic"><a href="' . $current_url . '#tabs-attributes">Attributes</a></li>';
            $output['tab_content'] .= '<div id="tabs-attributes" class="dynamic tab">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="formtable">';

            foreach ($attributes as $key => $value) {
                $output['tab_content'] .= '
                    <tr>
                        <th width="20%">' . $key . ' :Attributes</th>
                        <td width="85%">Only 10 words allowed for post-fix rest will eliminated</th>
                    </tr>';
                foreach ($value as $attr) {
                    $attribute_type = '<input type="text" 
												name="attribute_field_'.$attr['attribute_id'].'"   
												class="textfield width_40" 
												id="attribute_field_'.$attr['attribute_id'].'" 
												value="'.set_value('attribute_field_'.$attr['attribute_id']).'" />

										<input type="text" 
												name="attribute_postfix['.$attr['attribute_id'].']"
												class="textfield width_20" 
												id="attribute_postfix_' . $attr['attribute_id'] . '" 
												value="'.set_value('attribute_postfix[' . $attr['attribute_id'].']').'" 
												placeholder="Only 10 words "  
												/> 
										';

                    $output['tab_content'] .= '
                        <tr>
                            <th width="20%">' . $attr['attribute_label'] . '</th>                               
                            <td width="80%">' . $attribute_type . '</td>
                        </tr>
                    ';
                }
            }
            $output['tab_content'] .= '</table>
                </div>';
        }

        $output['category'] = $category;
        echo json_encode($output);
        exit();
    }

}

?>
