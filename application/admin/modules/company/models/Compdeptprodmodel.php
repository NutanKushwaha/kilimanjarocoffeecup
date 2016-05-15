<?php

class Compdeptprodmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
        $this->tbl_name = 'department_product';
        $this->tbl_pk_col = 'id';
        $this->tbl_alias = 'cdProd'; // company department products
    }

    function insertDeptPolicy( $dept_id ){		
        $opt = [];
        $opt[ 'select' ] = ' distinct(product_code) ';
        $opt[ 'from_tbl' ] = 'user_product_allocation';
        $opt[ 'where' ][ 'department_id' ] = $dept_id;
        $dept_prods = $this->get_all( $opt );
        $assigned_dept_prod_sku = array_column($dept_prods, 'product_code');
        
		$this->load->model( 'Company/Companymodel' );
		$dept_users = $this->Companymodel->getCompDeptUser( $dept_id );
		
        $insert_prod = [];
        $assign_prod = [];
        $remove_prod = [];
        $days = $this->input->post('days');
        $qty = $this->input->post('quantity');
        $gpolicy = $this->input->post('gpolicy');
        $products = $this->input->post('products');
       
        $new_post = array_diff($products, $assigned_dept_prod_sku);
        $old_remove = array_diff($assigned_dept_prod_sku, $products);
        
        $index = 0;
        foreach($products as $pindex => $sku){
            $gPolicyDet = com_arrIndex( $gpolicy, $sku, [] );
            $insert_prod[$index]['department_id'] = $dept_id;
            $insert_prod[$index]['product_sku'] = $sku;
            $insert_prod[$index]['days_limit'] = com_arrIndex($days, $sku, '1');
            $insert_prod[$index]['qty_limit'] = com_arrIndex($qty, $sku, '0');
            $insert_prod[$index]['group_policy'] = json_encode( $gPolicyDet );
            $insert_prod[$index]['added'] = date("Y-m-d H:i:s");            
        
            if( in_array($sku, $new_post) ){
				foreach($dept_users as $user_index => $user_det ){
					$assign_prod[$index][ 'created_on'] = date("Y-m-d H:i:s");
					$assign_prod[$index][ 'quantity'] = com_arrIndex($qty, $sku, '0');
					$assign_prod[$index][ 'user_id' ] = $user_det[ 'uacc_id' ];
					$assign_prod[$index][ 'days_limit'] = com_arrIndex($days, $sku, '1');
					$assign_prod[$index][ 'is_by_pass' ] = 0;
					$assign_prod[$index][ 'product_code' ] = $sku;					
					$assign_prod[$index][ 'department_id' ] = $dept_id;
					$index++;
				}
			}
			$index++;
        }
        
        $toRemoveIds = $this->makeLog($dept_id);
        $param = [];
        $param['where']['department_id'] = $dept_id;
        $this->delete_record($param);
        $this->insert_bulk($insert_prod);
        if( $assign_prod ){
			$this->insert_bulk($assign_prod, 'user_product_allocation');
		}
        if( $old_remove ){
			$opt = [];
			$opt[ 'where' ][ 'in_array' ] = ['0' => 'product', '1' => $old_remove];
			$opt[ 'where' ][ 'department_id' ] = $dept_id;
			$this->delete_record($param, 'user_product_allocation');
		}
		
    }


    function getDeptPolicy( $dept_id ){
        $param = [];
        $param['where']['department_id'] = $dept_id;
        return $this->get_all($param);
    }

    private function makeLog($dept_id){

        $removeIds = [];
        $insert_log = [];
        $all_dept_records = $this->getDeptPolicy( $dept_id );
        if($all_dept_records) {
            $index = 0;
            foreach($all_dept_records as $recInd => $recDet){
                $removeIds[] = $recDet['id'];
                unset($recDet['id']);
                $insert_log[$index] = $recDet;
                $insert_log[$index]['changed'] = date("Y-m-d H:i:s");
                $index++;
            }
            $this->load->model('Compdeptprodlogmodel', 'deptPolicyLog');            
            $this->deptPolicyLog->insert_bulk($insert_log);
        }
        return $removeIds;
    }

    function getDeptProdAttrSet( $deptIds ){
        $param = [];
        $param['select'] = 'asa.id, asa.label, asa.set_id';
        $param['join'][] = [
                            'tbl' => $this->db->dbprefix('product').' as prd', 
                            'cond' => 'prd.product_sku=cdProd.product_sku', 
                            'type' => 'inner',
                            'pass_prefix' => true
                        ];
        $param['join'][] = [
                            'tbl' => $this->db->dbprefix('attributes_set_attributes').' as asa', 
                            'cond' => 'asa.set_id=prd.attribute_set_id', 
                            'type' => 'inner',
                            'pass_prefix' => true
                        ];
        $param['where']['is_userrelated'] = 1;
        $param['where']['in'] = [ '0' => 'department_id', '1' => $deptIds];
        return $this->get_all( $param );
    }
}
