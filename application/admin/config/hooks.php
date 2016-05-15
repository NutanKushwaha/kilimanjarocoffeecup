<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
/*
Disabled for make other thing fine
$hook['post_controller_constructor'][] = array(
												'class'    => 'autoChecking',
												'function' => 'checkDeptUserRelatedAttribute',
												'filename' => 'autoChecking.php',
												'filepath' => 'hooks',
												'params'   => array()
											);
*/
$hook['post_controller_constructor'][] = array(
												'class'    => 'autoChecking',
												'function' => 'destroycart',
												'filename' => 'autoChecking.php',
												'filepath' => 'hooks',
												'params'   => array()
											);
/*
$hook['post_controller_constructor'][] = array(
	'class'    => 'islogged',
	'function' => 'checkLogged',
	'filename' => 'checkLogged.php',
	'filepath' => 'hooks',
	'params'   => array()
);
 * 
 */
/* End of file hooks.php */
/* Location: ./application/admin/config/hooks.php */