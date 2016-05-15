<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;

class Formwidget {

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

	function loadForm($form_alias, $widget, $form_widget) {
		$CI = & get_instance();
		$CI->db->where('form_alias', $form_alias);
		$rs = $CI->db->get('form');
		if (!$rs || $rs->num_rows() != 1)
			return false;

		$form = $rs->row_array();

		$CI->load->library('form');

		$CI->form->open(current_url(), $form['form_alias'], ' class="autoform"');
		$CI->form->hidden('form_id', $form['form_id']);

		$CI->form->html('<ul>');

		//Fetch all fields
		$CI->db->where('form_id', $form['form_id']);
		$rs = $CI->db->get('form_field');
		$fields = $rs->result_array();
		foreach ($fields as $field) {
			$required = ($field['field_required'] == 1) ? '|required' : '';
			$required .= ($field['field_validation'] != '') ? "|{$field['field_validation']}" : '';
			switch ($field['field_type']) {
				case 'text':
					$CI->form->text("field{$field['form_field_id']}", $field['field_label'], "trim$required");
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
			}
		}

		$CI->form->submit();
		//$CI->form->onsuccess('redirect', 'thank-you');
		$CI->form->html('</ul>');

		$template = '{FORM}';
		if ($form_widget['widget_form_template'] != '') {
			$template = $form_widget['widget_form_template'];
		}

		$form_html = $CI->form->get();
		$form_errors = $CI->form->errors;
		$form_html = $form_errors . $form_html;

		$template = str_replace('{FORM}', $form_html, $template);

		$CI->form->validate();

		if ($CI->form->valid) {
			//Send email here
			$email_data = array();
			foreach ($fields as $field) {
				if ($field['field_type'] == "radiogroup") {
					$email_data[$field['field_label']] = join(', ', $CI->input->post("field{$field['form_field_id']}", true));
				} else {
					$email_data[$field['field_label']] = $CI->input->post("field{$field['form_field_id']}", true);
				}
			}
			return $CI->load->view('form-email', array('email_data' => $email_data), true);
		}

		return $CI->load->view('form', array('form' => $template), true);
	}

	function addView() {
		return "add";
	}

	//function to edit record
	function editView() {
		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('Pagemodel');
		$this->CI->load->model('slideshow/Slideshowmodel');

		$current_slideshow = array();
		$current_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'slideshow');

		$show_slideshow = array();
		$show_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'show_slideshow');

		//fetch slideshow
		$slideshow = array();
		$slideshow[''] = '--Select Slideshow--';
		$slideshow_rs = $this->CI->Slideshowmodel->listAll(TRUE);
		foreach ($slideshow_rs as $row) {
			$slideshow[$row['slideshow_id']] = $row['slideshow_title'];
		}

		$inner = array();
		$inner['page'] = $this->page;
		$inner['slideshow'] = $slideshow;
		$inner['current_slideshow'] = $current_slideshow;
		$inner['show_slideshow'] = $show_slideshow;

		//include APPPATH.'/modules/cms/views/page_modules/slideshow/edit.php';
		return $this->CI->load->view('page_modules/slideshow/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $this->CI->Pagemodel->getPageData($this->page, 'slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		$show_slideshow = array();
		$show_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'show_slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $show_slideshow['page_data_id']);
		$this->CI->db->delete('page_data');
	}

	function actionUpdate() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $this->CI->Pagemodel->getPageData($this->page, 'slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'slideshow';
		$data['module_name'] = $this->getName();
		$data['page_setting_value'] = $this->CI->input->post('slideshow_id', true);
		$data['page_id'] = $page['page_id'];
		$this->CI->db->insert('page_data', $data);

		$show_slideshow = array();
		$show_slideshow = $this->CI->Pagemodel->getPageData($this->page, 'show_slideshow');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $show_slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'show_slideshow';
		$data['module_name'] = $this->getName();
		$data['page_setting_value'] = $this->CI->input->post('show_slideshow', true);
		$data['page_id'] = $page['page_id'];
		$this->CI->db->insert('page_data', $data);
	}

}

?>