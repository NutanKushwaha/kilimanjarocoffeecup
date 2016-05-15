<?php

class Commonmodel extends CI_Model {

    protected $tbl_name;
    protected $tbl_key;
    protected $tbl_cols;
    protected $tbl_alias;
    protected $tbl_main_txt;

    /**
     * An array specifying the form validation error delimeters.
     * They can be conveniently set in either the controller or model.
     * I like to use a list for my errors, and CI default is for 
     * individual paragraphs, which I think is somewhat retarded.
     *
     * @var array
     * @access public
     */
    public $error_delimiters = array( '<li>', '</li>' );


    /**
     * An array specifying which fields to unset from 
     * the form validation class' protected error array.
     * This is helpful if you have hidden fields that 
     * are required, but the user shouldn't see them 
     * if form validation fails.
     *
     * @var string
     * @access public
     */
    public $hide_errors = array();

    /**
     * All form validation errors are stored as a string, 
     * and can be accessed from the controller or model.
     *
     * @var string
     * @access public
     */
    public $validation_errors   = '';

    /**
     * Validation rules are set in the model, since 
     * the model is aware of what data should be inserted 
     * or updated. The exception would be when using the 
     * reauthentication feature, because we can optionally 
     * pass in our validation rules from the controller.
     *
     * @var string
     * @access public
     */
    public $validation_rules = array();

    // The following method prevents an error occurring when $this->data is modified.
    // Error Message: 'Indirect modification of overloaded property Demo_cart_admin_model::$data has no effect'.
    public function &__get($key){
        $CI =& get_instance();
        return $CI->$key;
    }

    function __construct() {
        // Call the Model constructor
        parent::__construct();        
        
    }

    public function getCols(){
        return $this->tbl_cols;
    }

    /**
     * Form validation consolidation.
     */
    public function validate()
    {

        // Apply the form validation error delimiters
        $this->_set_form_validation_error_delimiters();

        // Set form validation rules
        $this->form_validation->set_rules( $this->validation_rules );

        // If form validation passes
        if( $this->form_validation->run() !== FALSE )
        {
            // Load var to confirm validation passed
            $this->load->vars( array( 'validation_passed' => 1 ) );

            return TRUE;
        }

        /**
         * If form validation passes, none of the code below will be processed.
         */

        // Unset fields from the error array if they are in the hide errors array.
        if( ! empty( $this->hide_errors ) )
        {
            foreach( $this->hide_errors as $field )
            {
                $this->form_validation->unset_error( $field );
            }
        }

        // Load errors into class member for use in model or controller.
        $this->validation_errors = validation_errors();

        // Load var w/ validation errors
        $this->load->vars( array( 'validation_errors' => $this->validation_errors ) );

        /**
         * Do not repopulate with data that did not validate
         */

        // Get the errors
        $error_array = $this->form_validation->get_error_array();

        // Loop through the post array
        if($this->input->post()){
            foreach( $this->input->post() as $k => $v )
            {
                // If a key is in the error array
                if( array_key_exists( $k, $error_array ))
                {
                    // kill set_value() for that key
                    $this->form_validation->unset_field_data( $k );
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Sometimes, when you have a successful form validation, 
     * you will not want to repopulate the form, but if you 
     * don't unset the field data, the form will repopulate.
     */
    public function kill_set_value()
    {
        $this->form_validation->unset_field_data('*');
    }

    // --------------------------------------------------------------

    /**
     * Set the form validation error delimiters with an array.
     */
    private function _set_form_validation_error_delimiters()
    {
        list( $prefix, $suffix ) = $this->error_delimiters;

        $this->form_validation->set_error_delimiters( $prefix, $suffix );
    }

    /*
        Common db functions
    */            

    public function get_by_attr($key = null, $val = null){
        
        $record = FALSE;
        if (!$key or !$val) {            
            $record = FALSE;
        } else {
            $condition = array();
            $condition['where'][$key] = $val;
            $record = $this->count_rows();
        }
        return $record;
    }

    public function check_record_exist($key = 0, $show_404 = TRUE){
        $error = FALSE;
        $record = TRUE;

        if (!$key && $show_404) {
            $error = TRUE;
            $record = FALSE;
        }

        if($record){
            $condition = array();
            $condition['where'][$this->tbl_pk_col] = $key;
            $record = $this->count_rows();            
        }

        if(!$record && $error){
            $this->utility->show404();
            return;        
        }else if(!$error && !$record){
            return false;
        }        
    }


    public function get_by_pk($pid, $show_404 = false, $select = null) {

        if(!empty($select) && !is_null($select)){
            $this->db->select($select);
        }
        $this->db->from($this->tbl_name);
        $this->db->where($this->tbl_pk_col, intval($pid));

        $rs = $this->db->get();
        
        if ($rs->num_rows() == 1) {
            return $rs->row_array();
        }
        if($show_404){
            $this->utility->show404();
            return;
        }
        return array();
    }

    public function count_rows($param = array()) {
        $this->reset_db();
        $param['result'] = 'num';        
        $result = $this->get_all($param);
        return $result;
    }

    public function get_all($param = array()) {
        $this->reset_db();        
        $result = array();  

        $this->param_action($param);
        if( isset($param['from_tbl']) && !empty($param['from_tbl'])) {
            $from_tbl = $param['from_tbl'];
            if(isset( $param['from_tbl_alias'] )){
                $from_tbl = $param['from_tbl']. ' as '.$param['from_tbl_alias'];
            }
        }else{
            $from_tbl = $this->tbl_name;
            if( !empty($this->tbl_alias) ){
                $from_tbl = $this->tbl_name . ' as '.$this->tbl_alias;
            }
        }            

        $rs = $this->db->from($from_tbl)->get();
        
        $out = isset($param['result']) ? $param['result'] : '';
        if ($rs->num_rows()) {            
            switch ($out){
                case 'obj':
                    $result = $rs->result();
                    break;
                                    
                case 'all':
                    $result = $rs->result_array();
                break;

                case 'row':
                    $result = $rs->row_array();
                break;

                case 'num':
                    $result = $rs->num_rows();
                break;

                default:
                    $result = $rs->result_array();
                break;
            }
        }        

        if (!$rs->num_rows() && !empty($out) && $out == "num") {
            return 0;
        }
        return $result;
    }

    public function last_id() {     
        return $this->db->insert_id();
    }

    public function insert($param = array()) {
        $this->reset_db();
        $this->db->insert($this->tbl_name, $param);
        return $this->last_id();
    }

    public function insert_bulk($param = array()){
        $this->reset_db();
        return $this->db->insert_batch($this->tbl_name, $param);
    }

    public function update_record($param = array()) {
        $this->reset_db();
        $this->param_action($param);
        return $this->db->update($this->tbl_name, $param['data']);
    }

    public function delete_record($param = array()) {
        $this->reset_db();
        $this->param_action($param);
        return $this->db->delete($this->tbl_name);
    }

    public function delete_by_pk($param) {
        $this->reset_db();
        $this->db->where($this->tbl_pk_col, $param);
        return $this->db->delete($this->tbl_name);
    }
    
    private function reset_db(){
        $this->db->reset_query();
    }

    private function param_action($param = array()){        
        if(sizeof($param) && is_array($param)){
            foreach($param as $actionK => $actionDet){                
                switch($actionK){
                    case 'select':
                        $this->db->select($actionDet);
                    break;

                    case 'max':
                        $this->db->select_max($actionDet);
                    break;

                    case 'min':
                        $this->db->select_min($actionDet);
                    break;

                    case 'where':
                        $this->param_db_where($actionDet);                      
                    break;

                    case 'join':
                        $this->param_db_join($actionDet);                      
                    break;

                    case 'limit':
                        $this->db->limit($actionDet['limit'], $actionDet['offset']);
                    break;

                    case 'group':
                        $this->db->group_by($actionDet);
                    break;
                }
            }
        }
    }

    private function param_db_where($where_checks){        
        if(!empty($where_checks) && is_array($where_checks)){
            $sql = '';            
            foreach ($where_checks as $checkK => $checkD){                
                switch($checkK){
                    case 'in_array':                        
                        foreach ($checkD as $inArrkey => $inArrvalue) {                            
                            if(isset($inArrvalue['col'])){
                                    $this->db->where_in($inArrvalue['col'], $inArrvalue['opt']);
                            }else{
                                $this->db->where_in($inArrvalue['0'], $inArrvalue['1']);
                            }
                        }
                    break;
                    case 'and':
                        if(MCCDEV){
                            echo '<br/>I am here please manage array';
                            exit();
                        }
                    break;
                    case 'in':
                        if(isset($checkD['col'])){
                            $this->db->where_in($checkD['col'], $checkD['opt']);
                        }else{
                            $this->db->where_in($checkD['0'], $checkD['1']);
                        }
                    break;
                    default:
                        $this->db->where($checkK, $checkD);
                    break;
                }
            }
        }else if(!empty($where_checks)){
            $this->db->where(trim($where_checks));
        }
    }   

    private function param_db_join($joins){        
        foreach ($joins as $join_det){
            if(isset($join_det['pass_prefix']) && $join_det['pass_prefix']) {
                $this->db->join($join_det['tbl'], 
                            $join_det['cond'],
                            $join_det['type'],
                            FALSE
                        );                
            }else{
                $this->db->join($join_det['tbl'], 
                            $join_det['cond'],
                            $join_det['type']
                        );
            }
        }
    }

    //create indented list
    public function indentedList($param, $relation, &$output = array()) {
        $rst = $this->get_all($param);
        foreach ($rst as $row) {
            $output[] = $row;
            $param['where'][$relation['rlt_col']] = $row[$relation['rlt_fld']];
            $this->indentedList($param, $relation, $output);
        }
        return $output;
    }

    //create indented list with group
    public function indentedListWithOptgrp($param, $relation, &$output = array()) {
        $rst = $this->get_all($param);
        foreach ($rst as $row) {
            $param['where'][$relation['rlt_col']] = $row[$relation['rlt_fld']];
            $param['result'] = 'num';
            if($this->get_all($param)){
                unset($param['result']);
                $output[$row[$this->tbl_main_txt]] = array('depth' => $row['depth']);
                $this->indentedListWithOptgrp($param, $relation, $output[$row[$this->tbl_main_txt]]);                                
            }else{
                unset($param['result']);
                $output[$row[$this->tbl_pk_col]] = array('text' => $row[$this->tbl_main_txt] , 'depth' => $row['depth']);
            }                                
        }
        return $output;
    }

    function slug($cname, $alias) {
        $main_name = ($cname) ? $cname : '';

        $replace_array = array('.', '*', '/', '\\', '"', '\'', ',', '{', '}', '[', ']', '(', ')', '~', '`');

        $slug = $main_name;
        $slug = trim($slug);
        $slug = str_replace($replace_array, "", $slug);
        //.,*,/,\,",',,,{,(,},)[,]
        $slug = url_title($slug, 'dash', true);

        $condition = array();
        $condition['where'] = array($alias => $slug);        
        if ($this->count_rows($condition) > 0) {
            $suffix = 2;
            do {
                $slug_check = false;
                $alt_slug = substr($slug, 0, 200 - (strlen($suffix) + 1)) . "-$suffix";

                $condition = array();
                $condition['where'] = array($alias => $alt_slug);
                if ($this->count_rows($condition) > 0)
                    $slug_check = true;
                $suffix++;
            }while ($slug_check);
            $slug = $alt_slug;
        }
        return $slug;
    }

    /*
     *  $config = array( 'upload_path' => '', 'allowed_types' => '', 'overwrite' => FALSE )
     */
    function upload_file ( $config = [] ) {
        $this->load->library('upload', $config);
       

        if(empty($config['upload_path']) or empty($config['allowed_types'])){
            show_error($this->upload->display_errors('<p class="err">', '</p>'));
            return FALSE;
        }        
        
        if (count($_FILES) > 0) {
            //Check for valid image upload
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && 
                is_uploaded_file($_FILES['image']['tmp_name'])) {

                if (!$this->upload->do_upload('image')) {
                    show_error($this->upload->display_errors('<p class="err">', '</p>'));
                    return FALSE;
                } else {
                    $upload_data = $this->upload->data();
                    return $upload_data['file_name'];
                }
            }
        }        
    }

    public function checkName(){
        echo '<br/>', $this->tbl_name;
    }
}
