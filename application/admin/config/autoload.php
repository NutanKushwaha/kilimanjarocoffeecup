<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Helper files
| 4. Custom config files
| 5. Language files
| 6. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packges
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/

$autoload['packages'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
*/
$lib_data = array();

$lib_data[] = 'cart';
$lib_data[] = 'table';
$lib_data[] = 'encrypt';
$lib_data[] = 'utility';
$lib_data[] = 'session';
$lib_data[] = 'database';
$lib_data[] = 'pagination';
$lib_data[] = 'assets/assets';
$lib_data[] = 'Ajax_pagination';
$lib_data[] = 'form_validation';
$lib_data[] = 'globaldata'; 	// Load the site setting ex site name extra

/*
$lib_data[] = 'Aauth';
$lib_data[] = 'userauth';
*/
$autoload['libraries'] = $lib_data;


/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$help_data = array();

$help_data[] = 'url';
$help_data[] = 'html';
$help_data[] = 'text';
$help_data[] = 'cms';
$help_data[] = 'form';
$help_data[] = 'string';
$help_data[] = 'image';
$help_data[] = 'common';
$help_data[] = 'security';
/*
$help_data[] = 'function';
$help_data[] = 'globalfunc';
*/
$autoload['helper'] = $help_data;


/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

$autoload['config'] = array('custom-config');


/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/

$autoload['language'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model1', 'model2');
|
*/

$autoload['model'] = array('Commonmodel');


/* End of file autoload.php */
/* Location: ./application/config/autoload.php */
