<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//use Zend\Form\Element;
//use Zend\Form\Fieldset;
//use Zend\Form\Form;

class Dwsforms {

    function __construct() {
        $this->init();
    }

    function init() {
        //require_once("Zend/Form/Element.php");
        //require_once("Zend/Form/Fieldset.php");
        //require_once("Zend/Form/Form.php");
        /* $CI = & get_instance();
          $CI->load->library('zend');
          $CI->zend->load('Zend/Form/Element');
          $CI->zend->load('Zend/Form/Fieldset');
          $CI->zend->load('Zend/Form/Form');
          $CI->zend->load('Zend/Form/View/Helper/Form'); */
    }

    /* function loadForm($form_id) {
      echo ini_get('include_path');
      echo "<br>";
      ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'libraries');
      echo ini_get('include_path');

      require_once 'Zend/Loader/Autoloader.php';
      $autoloader = Zend_Loader_Autoloader::getInstance();

      $CI = & get_instance();
      $CI->db->where('form_id', $form_id);
      $rs = $CI->db->get('form');
      if (!$rs || $rs->num_rows() != 1)
      return false;

      $form = $rs->row_array();

      $name = new Element('name');
      $name->setLabel('Your name');
      $name->setAttributes(array(
      'type' => 'text'
      ));

      $email = new Element\Email('email');
      $email->setLabel('Your email address');

      $subject = new Element('subject');
      $subject->setLabel('Subject');
      $subject->setAttributes(array(
      'type' => 'text'
      ));

      $form = new Form('contact');
      $form->add($name);
      $form->add($email);
      $form->add($subject);

      $form->prepare();
      $form->setAttribute('action', 'contact/process');

      $form->setAttribute('method', 'post');

      return $form->render(new Zend_View());

      $formLabel = $name->getLabel();

      $output = $form->openTag($form);
      $output .= $formLabel->openTag() . $name->getOption('label');
      $output .= $form->formInput($name);
      $output .= $form->formElementErrors($name);
      $output .= $formLabel->closeTag();
      return $output;
      } */

    function loadForm($form_alias, $external_template = '') {
        $CI = & get_instance();
        $CI->db->where('form_alias', $form_alias);
        $rs = $CI->db->get('form');
        if (!$rs || $rs->num_rows() != 1)
            return false;

        $form = $rs->row_array();

        $CI->db->where('page_id', $form['redirect_page_id']);
        $rs = $CI->db->get('page');
        if (!$rs || $rs->num_rows() != 1)
            return false;

        $redirect_page = $rs->row_array();

        $CI->load->library('form');
        $CI->form->clear();
        $CI->form->open(current_url(), $form['form_alias'], ' class="autoform"');
        $CI->form->hidden("form{$form['form_id']}_id", $form['form_id']);

        $CI->form->html('<ul>');

        //Fetch all fields
        $CI->db->where('form_id', $form['form_id']);
        $rs = $CI->db->get('form_field');
        $fields = $rs->result_array();
        if ($form['form_template'] != '') {
            $CI->form->config['element_prefix'] = '';
            $CI->form->config['element_suffix'] = '';
        }
        foreach ($fields as $field) {
            $required = ($field['field_required'] == 1) ? '|required' : '';
            $required .= ($field['field_validation'] != '') ? "|{$field['field_validation']}" : '';
            $CI->form->html('<li>');
            switch ($field['field_type']) {
                case 'text':
                    $CI->form->text("field{$field['form_field_id']}", $field['field_label'], "trim$required");
                    break;
                case 'textarea':
                    $CI->form->textarea("field{$field['form_field_id']}", $field['field_label'], "trim$required", "", ' class="textarea"');
                    break;
                case 'radiogroup':
                    //$CI->form->html('<li>' . $field['field_label'] . '</li>');
                    $CI->form->html('<li class="inline">');
                    $CI->form->html('<ul>');
                    $CI->db->where('form_field_id', $field['form_field_id']);
                    $rs = $CI->db->get('field_option');
                    $options = array();
                    foreach ($rs->result_array() as $option) {
                        $options[] = array($option['field_option_value'], $option['field_option_name']);
                    }
                    $CI->form->radiogroup("field{$field['form_field_id']}", $options, $field['field_label'], '', "trim$required{$field['field_validation']}");
                    $CI->form->html('</ul>');
                    $CI->form->html('</li>');
                    break;
                case 'branches':
                    $rs = $CI->db->get('branch');
                    $options = array();
                    $options[''] = '-Your Location-';
                    foreach ($rs->result_array() as $option) {
                        $options[$option['branch_name']] = $option['branch_name'];
                    }
                    $CI->form->select("field{$field['form_field_id']}", $options, $field['field_label'], '', "trim$required{$field['field_validation']}");
                    break;
            }
            $CI->form->html('</li>');
        }

        $CI->form->submit("Submit", "submit{$form['form_id']}");
        //$CI->form->onsuccess('redirect', 'thank-you');
        $CI->form->html('</ul>');

        if ($CI->input->post('form_id', true) == $form['form_id']) {
            $CI->form->validate();
        }

        $template = '{ERRORS}{FORM}';
        $form_arr = $CI->form->get();
        
        if ($external_template != '') {
            $template = $external_template;
            $form_html = $form_arr['HTML'];
        } else {
            if ($form['form_template'] == '') {
               $form_html = $form_arr['HTML'];
            } else {
                $form_template = $form['form_template'];
                //$form_fields_arr = $CI->form->get_array();
                $form_fields_arr = $form_arr['ARRAY'];
                $search = array();
                $replace = array();
                foreach ($form_fields_arr as $key => $val) {
                    $search[] = "{" . $key . "}";
                    $replace[] = $val;
                }
                $search[] = '{FORM}';
                $replace[] = $form_arr['HTML'];                
                
                $form_html = str_replace($search, $replace, $form_template);
                $template = $form_html;
                //$template = "{FORM}";
                //$template = str_replace('{FORM}', $form_html, $template);
            }
        }
        $form_errors = $CI->form->errors;

        $template = str_replace('{ERRORS}', $form_errors, $template);
        $template = str_replace('{FORM}', $form_html, $template);

        if ($CI->form->valid) {
            //Send email here
            $email_data = array();
            foreach ($fields as $field) {
                if ($field['field_type'] == "radiogroup") {
                    $email_data[$field['field_label']] = join(', ', $CI->input->post("field{$field['form_field_id']}", true));
                } elseif($field['field_type'] == "branches") {
                    $email_data[$field['field_label']] = join(', ', $CI->input->post("field{$field['form_field_id']}", true));
                }
                else {
                    $email_data[$field['field_label']] = $CI->input->post("field{$field['form_field_id']}", true);
                }
            }
            $CI->load->library('email');
            $emailBody = $CI->load->view('form-email', array('email_data' => $email_data), true);
            $config = array();
            $CI->email->initialize($CI->config->item('EMAIL_CONFIG'));
            $CI->email->from($form['email_from']);
            $CI->email->to($form['email_to']);
            if ($form['email_cc'] != '') {
                $CI->email->cc($form['email_cc']);
            }
            if ($form['email_bcc'] != '') {
                $CI->email->bcc($form['email_bcc']);
            }
            $CI->email->subject($form['form_name']);
            $CI->email->message($emailBody);
            $status = $CI->email->send();
            $status = true;
            if ($status == TRUE) {
                redirect($redirect_page['page_uri']);
                exit();
            }
        }

        return $template;
    }

}

?>