<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pending extends Admin_Controller {

    function index() {
        com_getMenu();
        if (! $this->flexi_auth->is_privileged('View Pending'))
        {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view pending details.</p>');
            redirect('dashboard');
        }

        $inner = []; 

        $inner['list'] = [
                    '3-DEC-2015' => [
                        'Admin can create admin profile user and company profile user and simple user',
                        'Company can create company profile user and simple user',
                        'Simple user should assign to a department',
                    ],        
                    '1-DEC-2015' => [
                        'Re-Order Validation pending'
                    ],
                    '25-Nov-2015-REPORT' => [
                                'Saturday-Task' => [
                                            'add, edit JS code check at saturday *',
                                            'product point allocation to product via API or from form updation',
                                            'Show product price to edit form as per sap-api product',
                                            'add from-api field in db to confirm about product',                           
                                            ],
                                'Current-Consideration-Removing-modules' => 
                                    [
                                        'Rights as per User login [admin/company/user]',
                                        'Menu as per user',
                                        'View other form recomendation',      
                                    ],
                            ],
                    '19-Nov-2015-REPORT' => ['flash message'],
                    '17-Nov-2015-REPORT'    => [
                                    'Common auth model/register_account_internal',
                                    'Flexi auth',
                                    'Flex auth config',
                                    'API ',
                                    'Companymodel ',
                        ]
            ];
        $this->load->view('pending-task', $inner);
    }

    function temp(){
        error_reporting(E_ALL);

        /** Include path **/
        set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/plinq/Classes');
        // require_once APPPATH . 'third_party/plinq/Classes/PHPLinq.php';
        /** PHPLinq_LinqToObjects */
        require_once 'PHPLinq/LinqToObjects.php';
        set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/plinq/Tests');
        set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/plinq/Tests/resources');
        require_once 'LinqToObjects/02-objects.php';
        //require_once 'LinqToZendDb/01-simple.php';
        // require_once APPPATH . 'third_party/phpthumb/ThumbLib.inc.php';
        $sql = 'SELECT prod.product_id,prod.product_name,prod.category_id,
                        prod.product_type_id,prod.product_alias,prod.product_sku,prod.product_price,prod.product_point,
                        prod.product_description,attribute_id,attribute_value,prod.weight
                FROM `bg_product` as prod
                inner join `bg_product_attribute` as prodAttr on prod.product_id = prodAttr.product_id
                WHERE prod.`product_id` in (163, 164, 165)';
        $sql = 'SELECT product_id,attribute_id,attribute_value
                FROM  `bg_product_attribute` as prodAttr 
                WHERE prodAttr.`product_id` in (163, 164, 165)';                
        echo $sql;

        $dbresults = $this->db->query( $sql )->result();
        //com_e( $dbresults, 0);
        //com_e( count($dbresults), 0);
        //create the object
        // Create data source
        $names = array("John", "Peter", "Joe", "Patrick", "Donald", "Eric");
        $pResults = from('$name')->in($names)
                        ->where('$name => strlen($name) < 5')
                        ->select('$name');

        //com_e($pResults, 0);
        
        $filresult = from('$dbRes')->in($dbresults)
                        //->where('$employee => $employee->Name == "Bill"')
                        ->where('$dbRes => $dbRes->attribute_id == 30')
                        ->select('$dbRes');
        
        com_e( $filresult, 0);

        $filresult = from('$dbRes')->in($dbresults)
                        //->where('$employee => $employee->Name == "Bill"')
                        ->where('$dbRes => $dbRes->attribute_id == 99')
                        ->select('$dbRes');
        //var_dump( $filresult );
        com_e( $filresult, 1);
        //com_e( count($filresult), 0);

        $checkProd = from('$employee')->in( $dbresults )->take(1)->select();
        $checkProd = $checkProd[0];
        unset( $checkProd->weight );
        com_e( $checkProd, 0);
        $filresult = from('$dbRes')->in($dbresults)->contains( $checkProd );
        com_e( $filresult ,0);
        /*
        $result = from('$employee')->in($employeeTable)->contains($someEmployee);
        echo 'Contains($someEmployee): ' . ($result ? 'true' : 'false') . "\r\n";
        */
        
        $checkProd = [ 
                        'product_id' => '163' ,
                        'attribute_id' => 28,
                        'attribute_value' => 20
                    ];
        $checkProd = (object) $checkProd;
        com_e($checkProd , 0);        
        
        $filresult = from('$dbRes')->in($dbresults)->contains( $checkProd );
        var_dump( $filresult );
                /*
                //->where('$employee => $employee->Name == "Bill"')
                ->where('$dbRes => $dbRes->weight == 10')                        
                ->select('$dbRes');
                */        
        //com_e( count($filresult), 0);                        
        //com_e( $dbresults );

    }
}