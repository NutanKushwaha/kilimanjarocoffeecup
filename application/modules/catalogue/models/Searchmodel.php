<?php

class Searchmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
    }

    function countSearchResult($str = 0, $cat) {
        if ($cat != null || $cat != "") {
            $this->db->where('category.category_alias', $cat);
        }
        if ($str != "") {
            $this->db->select('product.product_name');
            $this->db->where('(product.product_name like "%' . $str . '%" or product.product_sku like "%' . $str . '%")');
        }
        $this->db->join('category', 'category.category_id = product.category_id');
        $query = $this->db->where('product_is_active', 1)
						->where('ref_product_id', 0)
							->get('product');
        return $query->num_rows();
    }

    function SearchResult($offset = 0, $limit = 12) {
        $str = $this->input->get('q');
        $cat = $this->input->get('cat');
        (isset($offset)) ? $this->db->offset($offset) : NULL;
        (isset($limit)) ? $this->db->limit($limit) : NULL;

        if ($cat != null || $cat != "") {
            $this->db->where('category.category_alias', $cat);
        }
        if ($str != "") {            
            $this->db->where('(product.product_name like "%' . $str . '%" or product.product_sku like "%' . $str . '%")');
        }
        
        $special_price_tbl = $this->db->dbprefix('front_special_price');
        $CountDownsql = "(select concat((unix_timestamp(to_date)*1000),'-',price) 
                        from $special_price_tbl where (NOW() BETWEEN from_date AND to_date) 
                        and ($special_price_tbl.product_sku=".$this->db->dbprefix('product').".product_sku) and active = 1) as end_time";
        $this->db->select('product.product_name,product.product_id,product.product_alias,product.product_sku,product.
                            product_price,product.product_image,(select avg(rating) from '.$this->db->dbprefix('review')
                            .' as rview where rview.product_id='.$this->db->dbprefix('product').'.product_id) as avgRating,'.$CountDownsql);
        $this->db->join('category', 'category.category_id = product.category_id');
        $query = $this->db->where('product_is_active', 1)
						->where('ref_product_id', 0)->get('product');
        return array('num_rows' => $query->num_rows(), 'result' => $query->result_array());
    }

}
