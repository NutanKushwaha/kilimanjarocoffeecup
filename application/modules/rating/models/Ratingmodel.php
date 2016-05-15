<?php

class Ratingmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //fetch the attributes details
    function insertRecord() {
        
        $data = array();
        $data['product_id'] = $this->input->post('product_id');
        $data['rating'] = $this->input->post('rating');
        $data['name'] = $this->input->post('name');
        $data['summary'] = $this->input->post('summary');
        $data['review'] = $this->input->post('review');
        $data['rating'] = $this->input->post('score');
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['status'] = 0;
        $insertRes = $this->db->insert('review', $data);

        return $insertRes;
    }

    function checkIp($productId) {
        $this->db->where('ip', $_SERVER['REMOTE_ADDR']);
        $this->db->where('product_id', $productId);
        $res = $this->db->get('review');
        //  echo $this->db->last_query();
        if ($res->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function getVerifiedReviewsByProductID($id) {
        $this->db->where('product_id', $id);
        $this->db->where('status', 1);
        return $this->db->get('review')->result_array();
    }

    function getaverage($productId) {
        $sql_query = $this->db->query("select count(*) as total, sum(rating) as total_rate from eve_review where product_id = $productId and status = 1");
        $row = $sql_query->result_array();

        foreach ($row as $resul) {
            $total_rate = $resul['total_rate'];
            $total = $resul['total'];
            if ($total > 0) {
                $average = $total_rate / $total;
                return $average;
            }
        }
//        select count(*) as total, sum(rating) as total_rate from eve_review where product_id = $productId;
    }

    function getCatAverage($categoryId) {

        $this->db->select('product_id')
                ->from('product')
                ->where('category_id', $categoryId);

        $query = $this->db->get();
        $ids = "";
        if ($query->num_rows() > 0) {
            $rows = $query->result_array();

            foreach ($rows as $row) {
                $ids .= $row['product_id'] . ",";
            }
            $ids = substr($ids, 0, -1);
        }

        if ($ids != '') {
            $sql_query = $this->db->query("SELECT count(*) as total, SUM(rating) as total_rating FROM eve_review where 1 = 1 and product_id IN($ids) and status = 1");
            $s_row = $sql_query->row_array();

            $_total = $s_row['total'];
            $_Rating = $s_row['total_rating'];

            $average = round($_Rating / $_total);

            return $average;
        } else {
            $average = "0";
            return $average;
        }
    }

}

?>