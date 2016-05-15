<?php

class Enquirymodel extends CI_Model {

    public $tbl;
    private $enq_status;
    public $loggId;    
    private $closed;
    private $active;
    function __construct() {
        parent::__construct();
        $this->load->model('Emailsmodel');
        
        $this->tbl = 'enquiry';
        $this->loggId = $this->aauth->get_user()->id;
        $this->closed = 1;
        $this->active = 2;
        $this->enq_status = array(
            '0' => 'today',
            '1' => 'all',
            '2' => 'closed'
        );
    }

    function fetchByName($name) {
        $this->db->like('first_name', $name)
                ->or_like('last_name', $name);
        $rs = $this->db->get($this->tbl);

        if ($rs->num_rows())
            return $rs->result_array();
        return false;
    }

    function fetchByID($primary_id) {
        $this->db->from($this->tbl)
                ->where('id', $primary_id);
        $rs = $this->db->get();
        if ($rs->num_rows()) {
            return $rs->row_array();
        }
        return false;
    }

    function insertRecord() {
//print_r($this->input->post()); exit();
        $data = array();
        $data['first_name'] = $this->input->post('first_name', TRUE);
        $data['last_name'] = $this->input->post('last_name', TRUE);
        $data['email_addr'] = strtolower($this->input->post('email_addr', TRUE));
        $data['tel_number'] = $this->input->post('tel_number', TRUE);
        $data['post_code'] = $this->input->post('post_code', TRUE);
        $data['enq_reason'] = $this->input->post('enq_reason', TRUE);
        $data['receive_update_news'] = $this->input->post('receive_update_news', TRUE);
        $data['how_reach'] = $this->input->post('how_reach', TRUE);
        $data['enquiry'] = $this->input->post('enquiry', TRUE);
        $data['souceSiteUser'] = '2';
        $data['enq_creator_id'] = $this->loggId;
        $data['next_follow_up'] = date('Y-m-d');
        /**
         * If any index is has false then empty string assign
         */
        foreach ($data as $key => $val) {
            if (!$data[$key]) {
                $data[$key] = '0';
            }
        }        
        $data['insert_time'] = date('Y-m-d', NOW());
        $data['next_follow_up'] = $data['insert_time'];
        $status = $this->db->insert($this->tbl, $data);
        if (!$status)
            return FALSE;

        $enquiry_id = $this->db->insert_id();

        //fetch email details
        $email_dtls = $this->Emailsmodel->fetchDetails('enquiry_registration');

        //Send Confirmation email
        $emailData = array();
        $emailData['DATE'] = date("jS F, Y");
        $emailData['NAME'] = $data['first_name'] . $data['last_name'];
        $emailData['EMAILADDR'] = $data['email_addr'];

        $search = array();
        $replace = array();

        $search[] = '{DATE}';
        $replace[] = $emailData['DATE'];

        $search[] = '{NAME}';
        $replace[] = $emailData['NAME'];

        $search[] = '{EMAILADDR}';
        $replace[] = $emailData['EMAILADDR'];

        $emailBody = str_ireplace($search, $replace, $email_dtls['email_content']);
        //print_r($emailBody); exit();

        $this->email->initialize($this->config->item('EMAIL_CONFIG'));
        $this->email->from(MCC_EMAIL_NOREPLY, MCC_EMAIL_FROM);
        $this->email->to($data['email_addr']);
        $this->email->subject($email_dtls['email_subject']);
        $this->email->message($emailBody);
        $status = $this->email->send();

        $data['enquiry_id'] = $enquiry_id;
        return $data;
    }

    function getEnquiryTypeList() {
        $rstSet = $this->db->select('*')
                        ->from('enquiry_types')
                        ->order_by('sort_order')->get();
        if ($rstSet->num_rows()) {
            return $rstSet->result_array();
        }
        return array();
    }

    function getAvailEnquiryList(){
        $enq_filter_status = $this->input->post('filter-enq-status', TRUE);
        $enq_filter_date = $this->input->post('filter-enq-date', TRUE);
        
        $where_sql = ' active = 1 ';
        if($enq_filter_status == 2) $where_sql = ' active = 0 ';
        else if ($enq_filter_status == 1) $where_sql = false;
        
//        $date_sql = ' (enq_creator_id = '.$this->loggId
//                        .' or event_creator_id = '
//                        .$this->loggId.($this->aauth->isAdmin()?' or enq_creator_id = 0 ': '').'  )'
//                        . ' and next_follow_up <= "'.date('Y-m-d').'"';
        
        $date_sql = ' (enq_creator_id = '.$this->loggId
                        .' or event_creator_id = 0)'
                        . ' and next_follow_up <= "'.date('Y-m-d').'"';
        
        if($enq_filter_date){
            $enq_filter_date =explode(' - ', $enq_filter_date);
            $date_sql = ' ( next_follow_up >= "'.date('Y-m-d',strtotime($enq_filter_date[0]))
                            .'" AND next_follow_up <= "'.date('Y-m-d',strtotime($enq_filter_date[1])).'" )';
        }
        
        $sql =  'SELECT IF(next_follow_up <  DATE(NOW()), "1", "0") as old, '
                . ' id, enq_creator_id, first_name, last_name, enq_reason,'
                . ' enquiry, souceSiteUser, status, next_follow_up, active, insert_time, event_related, event_id, event_creator_id '
                . ' FROM dpd_enquiry WHERE '.$where_sql.($where_sql ? ' AND ' :'' )
                .$date_sql.' ORDER BY ID DESC';        
     
        $rstSet = $this->db->query($sql);
            
        
        if($rstSet->num_rows()){
            return $rstSet->result_array();
        }
        
        return array();
    }

    function getEnquiryFollowUpList($id = null) {
        if (!$id)
            return null;
        $rstSet = $this->db->select('*')
                ->from('enquiry_followup')
                ->where('enquiry_id', $id)
                ->order_by('id', 'desc')
                ->get();
        if ($rstSet->num_rows()) {
            return $rstSet->result_array();
        }
        return array();
    }

    function getUserAllEnquiriesCount($status = null) {
        $this->db->select('count(id) as ttl')
                    ->from($this->tbl)
                    ->where('enq_creator_id', $this->loggId)
                    ->or_where('event_creator_id', $this->loggId);
        if ($this->aauth->isFranshisor()) {
            $this->db->or_where('enq_creator_id', '0');
        }
        if($status == $this->closed){
            $this->db->where('active', 0);
        }
        if($status == $this->active){
            $this->db->where('active', 1);
        }                
        
        $rstSet = $this->db->get();
        return $rstSet->row_array();
    }

}

?>