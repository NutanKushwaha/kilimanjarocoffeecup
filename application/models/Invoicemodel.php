<?php

class InvoiceModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getIsFirst($fid = NULL) {
        $franchise_id = $fid;
        $this->db->select('is_paid_first');
        $this->db->from('user_extra_detail');
        $this->db->where('id', $franchise_id);

        $query = $this->db->get();
        if ($query->num_rows) {
            return $query->result();
        }
        return FALSE;
    }

    function updateIsFirst($appid = NULL) {


        $today = date("Y-m-d");
        $time = strtotime($today);


        $final = date("Y-m-d", strtotime("+1 month", $time));

//        if($type=='Q'){
//            $final = date("Y-m-d", strtotime("+4 month", $time));
//            $invoice_type="Q";
//        }
//        if($type=='HF'){
//            $final = date("Y-m-d", strtotime("+6 month", $time));
//            $invoice_type="HF";
//        }

        $data1 = array(
            "date_of_month" => $final
        );

        $this->db->where('id', $appid);
        $this->db->update('applications', $data1);
    }

    function getWeeklyUsers() {

        //$date = date("Y-m-d");

        $today_date = date("Y-m-d");
        $weekday = date('l', strtotime($today_date));

        $this->db->select('*');
        $this->db->from('applications');
        $this->db->join('applicants', 'applications.applicant_id = applicants.applicant_id');
        $this->db->where('applications.is_deal_start', '1');
        $this->db->where('applications.is_active', '1');
        $this->db->where('applications.invoice_type', 'W');
//        $this->db->where('applications.day_of_week', $weekday); // commented for sometime
        //$this->db->limit(1);
        $query = $this->db->get();
        //echo $this->db->last_query();
        //exit;
        if ($query->num_rows) {
            return $query->result_array();
        }

        return FALSE;
    }

    function getMonthlyUsers() {

        //$date = date("Y-m-d");
        $today_date = date("Y-m-d");

        $this->db->select('*');
        $this->db->from('applications');
        $this->db->join('applicants', 'applications.applicant_id = applicants.applicant_id');
        $this->db->where('applications.is_deal_start', '1');
        $this->db->where('applications.is_active', '1');
        $this->db->where('applications.invoice_type', 'M');
        $this->db->where('applications.date_of_month', $today_date);
        //$this->db->limit(1);
        $query = $this->db->get();
        //echo $this->db->last_query();
        //exit;
        if ($query->num_rows) {
            return $query->result_array();
        }

        return FALSE;
    }

    function getQuaterlyUsers() {
        //$date = date("Y-m-d");
        $today_date = date("Y-m-d");
        $this->db->select('*');
        $this->db->from('user_extra_detail');
        $this->db->join('aauth_users', 'user_extra_detail.id = aauth_users.id');
        $this->db->where('user_extra_detail.mon_fee_type', 'Q');
        $this->db->where('user_extra_detail.last_installment', $today_date);
        //$this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows) {
            return $query->result();
        }

        return FALSE;
    }

    function getHalfyearlyUsers() {
        //$date = date("Y-m-d");
        $today_date = date("Y-m-d");
        $this->db->select('*');
        $this->db->from('user_extra_detail');
        $this->db->join('aauth_users', 'user_extra_detail.id = aauth_users.id');
        $this->db->where('user_extra_detail.mon_fee_type', 'HY');
        $this->db->where('user_extra_detail.last_installment', $today_date);
        //$this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows) {
            return $query->result();
        }

        return FALSE;
    }

    function getInvoice($applicaton_id = NULL, $fid = NULL, $com_id, $installment_fees, $amt_after_vat, $type) {
        $applicant_id = $fid;
        $company_id = $com_id;
        $today_date = date("Y-m-d");
        $due_date = date('Y-m-d', strtotime("+5 days"));

        $data = array(
            'application_id' => $applicaton_id,
            'applicant_id' => $applicant_id,
            'company_id' => $com_id,
            'installment_fees' => $installment_fees,
            'vat' => '20',
            'total_amount' => $amt_after_vat,
            'invoice_code' => '0',
            'created_on' => $today_date,
            'due_on' => $due_date,
            'invoice_type' => $type,
            'paid_on' => '0000-00-00 00:00:00',
            'is_paid' => '0'
        );
        //e($data);
        $this->db->insert('invoice_new', $data);
        // echo $this->db->last_query();
        //exit;
        $insert_id = $this->db->insert_id();

        $rand = rand(00000, 99999);
        $invoice_code = $rand . "" . $insert_id;
        $this->db->where('invoice_id', $insert_id);
        $this->db->update('invoice_new', array('invoice_code' => $invoice_code));

        $array = array(
            "invoice_id" => $invoice_code,
            "created_date" => $today_date,
            "due_date" => $due_date
        );

        return $array;
    }

    function vat($price_without_vat = NULL) {

        $vat = 20; // define what % vat is

        $price_with_vat = $price_without_vat + ($vat * ($price_without_vat / 100)); // work out the amount of vat

        $price_with_vat = round($price_with_vat, 2); // round to 2 decimal places

        return $price_with_vat;
    }

    function withoutvat($price_without_vat = NULL) {

        $vat = 20; // define what % vat is

        $price_with_vat = ($vat * ($price_without_vat / 100)); // work out the amount of vat

        $price_with_vat = round($price_with_vat, 2); // round to 2 decimal places

        return $price_with_vat;
    }

    function updatevirtualcabnet($appId = NULL, $creater_id = NULL, $filename, $assigne_grp = 3, $is_applicant = 0) {
        $date = date("Y-m-d h:i:s");
        $data = array(
            "visible_name" => $filename . '.pdf',
            "filetype" => 'pdf',
            "actual_name" => $filename . '.pdf',
            "creator_id" => $creater_id,
            "assigne_grp" => $assigne_grp,
            "assignes" => $creater_id,
            "create_dtime" => $date,
            "is_applicant" => $is_applicant,
            "update_dtime" => $date
        );

        $this->db->insert('virtualCab', $data);
    }

    function getTodayInvoices() {
        $today = date('Y-m-d');
        $today = '2015-07-28';
        $this->db->where('is_paid', 0);
        $this->db->like('created_on', $today);
        $this->db->from('invoice_new t1');
        $rs = $this->db->get()->result_array();
        return $rs;
    }

    function updateInvoice($invoice_id) {
        $data = array();
        $rand = rand(00000, 99999);
        $data['invoice_code'] = $rand . "" . $invoice_id;
//        $this->db->where('invoice_id', $invoice_id);
//        $this->db->update('invoice_new', $data);
    }

    function getByPk($id, $table, $where = 'id') {
        if (!$id)
            return false;
        $this->db->where($where, $id);
        $row = $this->db->get($table)->row_array();
        return $row;
    }

}

?>
