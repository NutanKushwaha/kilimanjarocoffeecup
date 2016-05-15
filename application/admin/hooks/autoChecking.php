<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * 
 */

class autoChecking extends Admin_Controller{
    
    protected $ci;
    function __construct() {
        
        global $CI;
        $this->ci = $CI;
        $this->ci->load->model('Commonmodel');
        $this->routerDet['class'] = $this->ci->router->class;
        $this->routerDet['method'] = $this->ci->router->method;
        $this->routerDet['module'] = $this->ci->router->module;
        $this->routerDet['directory'] = $this->ci->router->directory;
    }
    
    function saveNotify(){
        /* 
         * Store the ip as a INT(11) UNSIGNED, 
         * then use the INET_ATON and INET_NTOA 
         * functions to store/retrieve the ip address.
         */
        $loggedUserData = $this->ci->session->userdata;
        if(!isset($loggedUserData['loggedin']) || !$loggedUserData['loggedin']){
            return false;
        }
        $data = array();                
        $data[$this->ci->UserNotificationmodel->user_id] = $loggedUserData['id'];
        $data[$this->ci->UserNotificationmodel->activity_time] = time();
        $data[$this->ci->UserNotificationmodel->user_ip] = $loggedUserData['ip_address'];
        
        $data[$this->ci->UserNotificationmodel->source_class] = $this->ci->router->fetch_class();
        $data[$this->ci->UserNotificationmodel->source_action] = $this->ci->router->fetch_method();
        
        $this->ci->UserNotificationmodel->insertRecord($data);
    }

    function checkDeptUserRelatedAttribute(){
        /*
            Check is current class is as per mark
         */
        $this->ci->data['userRelatedAttributeMissing'] = '0' ;
        if( in_array($this->routerDet['class'], ['dashboard'])
            && in_array($this->routerDet['method'], ['index']) 
            && $this->ci->isLogged){
            /*
                check is login person is company or not
             */
            if( $this->ci->user_type == CMP_ADMIN ){
                /* 
                    get all department ids
                 */
                $this->ci->load->model('Departmentmodel');
                $deptList = $this->ci->Departmentmodel->getCompanyDept();                
                $deptIds =  array_column( $deptList , 'id');
                
                /* 
                    get company related department attribute id which is marked as user related 
                    to check is any user have no value for this attribute.
                 */                
                $this->ci->load->model('company/Compdeptprodmodel' , 'deptProd');
                $userRelatedAttributes = $this->ci->deptProd->getDeptProdAttrSet( $deptIds );

                if( $userRelatedAttributes ){
                    /* 
                        get company code
                     */
                    $companyCode = $this->ci->flexi_auth->get_comp_admin_company_code();                    


                    // Select user data to be displayed.
                        $sql_select = [ $this->ci->flexi_auth->db_column('user_acc', 'id'),
                                        $this->ci->flexi_auth->db_column('user_acc', 'username'), ];
                        $this->ci->flexi_auth->sql_select($sql_select);

                    // where condition
                        // condition for company reference
                        $sql_where['upro_company'] = $companyCode;
                        $this->ci->flexi_auth->sql_where($sql_where);

                        // condition for department
                        $sql_like = [];
                        $sql_or_like = [];

                        foreach($deptIds as $dIndx => $dId){
                            if($dIndx == 0){
                                $sql_like['upro_department'] = '"'.$dId.'"';
                                $this->ci->flexi_auth->sql_like($sql_like);
                            }else{
                                $sql_or_like['upro_department'] = '"'.$dId.'"';
                                $this->ci->flexi_auth->sql_or_like($sql_or_like);
                            }
                        }

                    // get unique set id
                        $attrSetId = array_unique(array_column( $userRelatedAttributes, 'set_id' ));                        

                    /*
                        
                        foreach( $attrSetId as $setId ){
                            // Fetch only selected attributes details
                            $setUserAttrbs = com_searchArrKeyValPair( $userRelatedAttributes , 'set_id', $setId );

                            // attributeid extract
                            $setUserAttrbsIds = array_column($setUserAttrbs, 'id');

                            $param = [];
                            $param['from_tbl'] = 'department_user_attribute';
                            $param['where']['user_value'] = '!= ""';
                            $param['where']['in'] = [ '0' => 'attribute_id', '1' => $attr_ids];
                            $param['where']['in'] = [ '0' => 'user_id', '1' => $dept_users_ids];
                            
                            $this->ci->Commonmodel->get_all( $param );
                        } 
                    */                    

                    // get attributes ids
                        $attr_ids = array_column( $userRelatedAttributes, 'id' );

                    // User as per department fetched
                        $dept_users = $this->ci->flexi_auth->search_users_array();
                        
                    // get users account id
                        $dept_users_ids = array_column( $dept_users, $this->ci->flexi_auth->db_column('user_acc', 'id') );

                        $param = [];
                        $param['from_tbl'] = 'department_user_attribute';
                        $param['where']['user_value'] = '!= ""';
                        $param['where']['in_array'][] = [ '0' => 'attribute_id', '1' => $attr_ids];
                        $param['where']['in_array'][] = [ '0' => 'user_id', '1' => $dept_users_ids];
                        
                        $existUsers = $this->ci->Commonmodel->get_all( $param );
                        if(MCCDEV){
                            com_e( $this->ci->db->last_query() , 0 );
                        }
                        
                        if( !$existUsers ){
                            $this->ci->data['ERROR'] = 'Some user related attributes are not filled please fill' ;
                        }
                }
            }
        } 
    }

    function destroycart( ){
        if( in_array($this->routerDet['class'], ['welcome'])
                    && in_array($this->routerDet['method'], ['logout']) 
            ){
            $this->ci->session->unset_userdata( 'cartAssocDet' );
            $this->ci->session->unset_userdata( 'cartReorderRef' );
            $this->ci->session->unset_userdata( 'cart_contents' );
        }
    }
}
