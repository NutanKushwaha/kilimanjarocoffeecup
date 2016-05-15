<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	CREATE TABLE `bg_user_product_allocation` (
	 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	 `user_id` int(10) unsigned NOT NULL,
	 `department_id` int(10) unsigned NOT NULL,
	 `product_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'hold sku code',
	 `days_limit` tinyint(1) unsigned NOT NULL DEFAULT '1',
	 `quantity` int(11) NOT NULL DEFAULT '0',
	 `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	 `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	 PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
*/
class UserprodallocationModel extends Commonmodel{
	
	private $data;
	function __construct()
	{
		# code...
        parent::__construct();
        $this->set_attributes();
	}

	/* model variable initialise */
	protected function set_attributes(){
    	$this->data = array();

        $this->tbl_name = 'user_product_allocation';
        $this->tbl_alias = 'upa';
        $this->tbl_pk_col = 'id';

        $this->tbl_cols['id'] = 'id';
        $this->tbl_cols['user_id'] = 'user_id';
        $this->tbl_cols['department_id'] = 'department_id';
        $this->tbl_cols['product_code'] = 'product_code';
        $this->tbl_cols['days_limit'] = 'days_limit';
        $this->tbl_cols['quantity'] = 'quantity';
        $this->tbl_cols['created_on'] = 'created_on';
        $this->tbl_cols['updated_on'] = 'updated_on';
	}

	/* 
		save and update the user product policy
		posted data format
		for products
    	[product] => Array (	[department-id] => Array
                				(	[product-sku] => Array 
                									( 	[product] => Array ( [attribute-id] => attribute-value )
                            							[user] => Array ( [attribute-id] => Array ( [user-id] => attribute-value )
                                					)
                        		)
                			)
    	[days] => Array ( 	[department-id] => Array (	[product-sku] => Array ( [user-id] => days-index )  )
    	[quantity] => Array ( 	[department-id] => Array (	[product-sku] => Array ( [user-id] => qty-value )  )
    	[users_no_policy] => Array ( 	[department-id] => Array (	[product-sku] => Array ( [user-id] => 1 )  )
	*/	
	function update_user_product_allocation( $params ){
				
		/* put all post days in one variable */
		$posted_days = $this->input->post('days');

		/* put all post qtys in one variable */
		$posted_quantity = $this->input->post('quantity');

		/* put all post prods in one variable */
		$posted_products = $this->input->post('product');

		/* dept keys which are posted */
		$deptKeys = array_keys( $posted_days );

		/* put all post users_no_policy in one variable */
		$posted_users_no_policy = $this->input->post('users_no_policy');
		
		/* fetch all product and user combination from database as per dept keys */
		$join_tbl = $this->db->dbprefix('user_product_allocation_attributes_details'). ' as upaattr'; 
		$param = [];
		$param['select'] = 'upa.id, upa.user_id, upa.department_id, upa.product_code, upa.days_limit, 
							upa.quantity, upaattr.attribute_id, upaattr.attribute_value';
		$param['join'][] = [	'tbl' => $join_tbl, 
							'cond' => 'upa.id=upaattr.policy_ref', 
							'type' => 'left',
							'pass_prefix' => true 
						];
		$param['where']['in'] = [ 0 => 'department_id', 1 => $deptKeys];
		$deptProdsPolicyAttrs	=	$this->get_all( $param );
		
		$keysComb = ['department_id', 'product_code', 'user_id'];
		/* 
			db fetched products in custom key index format
			$arrDet["department_id"]:$arrDet["user_id"]:$arrDet["product_code"]
		*/
		$deptProdsPolicyAttrs = com_makeArrIndexToArrayFieldComb( $deptProdsPolicyAttrs, $keysComb, TRUE, TRUE);
				
		/* delete allocation id */
		$del_user_product_allocation = [];
		/* $user_product_allocation  */
		$user_product_allocation = [];
		/* $user_product_allocation_attributes_details */
		$user_product_allocation_attributes_details = [];
		/* $user_product_allocation_log */
		$user_product_allocation_log = [];

		/* loop on department keys */
		foreach ($deptKeys as $deptKeysIndex => $deptKey) {
			/* assign specific product user comb to deptUserDays */
			$deptUserDays = $posted_days[$deptKey];
			/* loop on dept deptUserDays */
			foreach($deptUserDays as $deptUserDayProdSku => $deptUserDayUserDet){				
				$userDeptQty = $posted_quantity[$deptKey][$deptUserDayProdSku];
				$userPolicyStatus = [];
				if( isset( $posted_users_no_policy[$deptKey][$deptUserDayProdSku] ) ){
					$userPolicyStatus = $posted_users_no_policy[$deptKey][$deptUserDayProdSku];
				}				
				/* $deptPostedProducts contains product attributes */
				$deptPostedProductsAttr = [];
				if( isset( $posted_products[$deptKey][ $deptUserDayProdSku ] ) ){
					$deptPostedProductsAttr = $posted_products[$deptKey][ $deptUserDayProdSku ];
				}
				$userIndex = 0;
				/* loop on dept deptUserDayUserDet */
				foreach ($deptUserDayUserDet as $userId => $userDay) {
					/* $arrDet["department_id"]:$arrDet["user_id"]:$arrDet["product_code"] */
					$customKey = $deptKey.':'.$deptUserDayProdSku.':'.$userId;
					/* check is custome key exist in db result set */
					if( isset( $deptProdsPolicyAttrs[$customKey] ) ){
						$dbFetchedProdAttributes = $deptProdsPolicyAttrs[ $customKey ];
						/* 
							Because of array $deptProdsPolicyAttrs 0 index will must exist 
						*/
						$deleteId = $dbFetchedProdAttributes[0]['id'];
						$qtysLimit = $dbFetchedProdAttributes[0]['quantity'];
						$daysLimit = $dbFetchedProdAttributes[0]['days_limit'];
						$del_user_product_allocation[] = $deleteId;
						$log_attribute_val_combination = json_encode( com_makelist($dbFetchedProdAttributes, 'attribute_id', 'attribute_value', false) );
						$user_product_allocation_log[] = [	'user_id' => $userId,
															'product_code' => $deptUserDayProdSku,
															'days_limit' => $daysLimit,
															'quantity' => $qtysLimit,
															'updated_on' => date("Y-m-d H:i:s"),
															'attributes_detail' => $log_attribute_val_combination,
														];
					}
					$user_product_allocation[$userId][$deptUserDayProdSku] = [
															'user_id' => $userId,
															'days_limit' 	=> $userDay,
															'department_id' => $deptKey,
															'created_on' 	=> date("Y-m-d H:i:s"),
															'quantity' 		=> $userDeptQty[ $userId ],
															'product_code' 	=> $deptUserDayProdSku,
															'is_by_pass' 	=> com_arrIndex($userPolicyStatus, $userId, 0),
														];
					$prod_attributes = [];
					/* for specific user post product details will chceked or array build */
					$prodAttrIndex = 0;
					foreach ($deptPostedProductsAttr as $deptPostedProductsAttrKey => $deptPostedProductsAttrDetails) {
						if( is_array( $deptPostedProductsAttrDetails ) ){
							foreach( $deptPostedProductsAttrDetails as $attributeId => $attributeValue) {
								if( $deptPostedProductsAttrKey == "product" ) {
									$prod_attributes[$prodAttrIndex][ 'policy_ref' ] = 0;
									$prod_attributes[$prodAttrIndex][ 'is_user_related' ] = 0;
									$prod_attributes[$prodAttrIndex][ 'attribute_id' ] = $attributeId;
									$prod_attributes[$prodAttrIndex][ 'attribute_value' ] = $attributeValue;
									$prodAttrIndex++;
								} else if( $deptPostedProductsAttrKey == "user" ) {
									$prod_attributes[$prodAttrIndex][ 'policy_ref' ] = 0;
									$prod_attributes[$prodAttrIndex][ 'is_user_related' ] = 1;
									$prod_attributes[$prodAttrIndex][ 'attribute_id' ] = $attributeId;
									$prod_attributes[$prodAttrIndex][ 'attribute_value' ] = $attributeValue[ $userId ];
									$prodAttrIndex++;
								}
							}
						}
					}
					$user_product_allocation_attributes_details[ $userId ][$deptUserDayProdSku] = $prod_attributes;
				}
			}
		}

		if( 1 == 0){
			com_e( $del_user_product_allocation , 0);
			com_e( $user_product_allocation, 0);
			com_e( $user_product_allocation_attributes_details, 0);
			com_e( $user_product_allocation_log);
		}
		/* ids are selected for delete from prouct allocation log */
		if( $del_user_product_allocation ){
			$param = [];
			$param['where']['in'][0] = 'id';
			$param['where']['in'][1] = $del_user_product_allocation;
			$this->delete_record( $param );
		}
		//com_e( $user_product_allocation );
		if( $user_product_allocation ) {
			foreach ( $user_product_allocation as $userKey => $userProds ) {
				foreach( $userProds as $prodSku => $prodDet ){
					$policy_id	=	$this->insert( $prodDet );
					$allRelatedAttrs = $user_product_allocation_attributes_details[ $userKey ][ $prodSku ];
					$allAttributes = [];
					foreach( $allRelatedAttrs as $allRelatedAttrsInd => $allRelatedAttrsArr ){
						$allRelatedAttrsArr[ 'policy_ref' ] = $policy_id;
						$allAttributes[] = $allRelatedAttrsArr;
					}
					if( is_array( $allAttributes ) && sizeof( $allAttributes ) ){
						$this->load->model('Userprodattrallocationmodel');
						$this->Userprodattrallocationmodel->insert_bulk( $allAttributes );
					}
				}
			}
		}

		if( $user_product_allocation_log ){
			$this->load->model('Userprodattrallocationlogmodel');
			$this->Userprodattrallocationlogmodel->insert_bulk( $user_product_allocation_log );
		}
	}

	/* return department users product allocations  */
	function getDeptUsersAllocProducts( $department_id ){
		$param = [];
		$param['select'] = 'upa.department_id, upa.user_id, upa.product_code, upa.days_limit, upa.quantity, upa.is_by_pass, 
							upaat.is_user_related, upaat.attribute_id, upaat.attribute_value';
		$param['join'][] = [	'tbl' => $this->db->dbprefix('user_product_allocation_attributes_details').' as upaat',
								'cond' => 'upa.id=upaat.policy_ref',
								'type' => 'left', 'pass_prefix' => true
							];
		$param['where'][ $this->tbl_cols['department_id'] ] = $department_id;		
		return $this->get_all($param);
	}

	/* return product details as per user dept and product */
	function getDeptUserProductDetail($deptId, $userId, $productRef ){
		$param = [];
		$param['select'] = 'days_limit, quantity, attribute_id, attribute_value, is_by_pass';
		$param['join'][]  = [
							'tbl' => $this->db->dbprefix('user_product_allocation_attributes_details'),
							'cond' => 'policy_ref=upa.id',
							'type' => 'left',
							'pass_prefix' => TRUE
						];
		$param['where']['user_id']  = $userId;
		$param['where']['department_id']  = $deptId;
		$param['where']['product_code']  = $productRef;
        return $this->get_all( $param );
	}
	/* 
		old use 
		company user product allocation 
	*/	
	private function getCompUserProdAlloc( ) {

		$param = [];
		$param['select'] = 'product_code, quantity';
		$param['where'][ $this->tbl_cols['user_id'] ] = $this->input->post('selected_user');
		$param['where'][ $this->tbl_cols['department_id'] ] = $this->input->post('department');
		$param['where'][ $this->tbl_cols['company_code'] ] = $this->flexi_auth->get_comp_admin_company_code();
		return $this->get_all($param);
	}

	/*
		old use
		return sum quantity as per user
	*/
	private function getDepProdCount( ) {
		$param = [];
		$param['select'] = 'sum(quantity) as ttl,product_code , user_id';
		$param['where'][ $this->tbl_cols['department_id'] ] = $this->input->post('department');
		$param['where'][ $this->tbl_cols['company_code'] ] = $this->flexi_auth->get_comp_admin_company_code();
		$param['group'] = 'product_code , user_id';
		return $this->get_all($param);		
	}

	/*
		old use
		return array with user id index and product quantity
	*/
	private function getDeptUserProdCount( ){
		$ret_user_prod_count = [];
		$user_prod_count = $this->getDepProdCount();
		foreach($user_prod_count as $product_counts ){
			$ret_user_prod_count[ $product_counts['user_id'] ][ $product_counts['product_code'] ] = $product_counts['ttl'];
		}
		return $ret_user_prod_count;
	}	

	/* 
		for logged user return product
	*/
	function getLoginUserProdAlloc( $param = [] ) {

		$department_id = $param['dept_id'];
		$limit = $param['limit'];

		$param = [];
		$param['select'] = 'upa.product_code,upa.quantity,jt1.product_name,jt1.product_sku,jt1.product_price,jt1.product_point,jt1.product_image';
		$param['join'][] = 	['tbl' => $this->db->dbprefix('product'). ' as jt1',
                            'cond' => "jt1.product_sku=product_code",
                            'type' => 'inner'
                          	];
      	$param['where'][ 'jt1.product_is_active' ] = 1;
		$param['where'][ $this->tbl_cols['user_id'] ] = $this->flexi_auth->get_user_id();
		$param['where'][ $this->tbl_cols['department_id'] ] = $department_id;		
		if( isset($limit) && $limit ){
			$param['limit'] = $limit;
		}
		return $this->get_all($param);
	}

	/* 
		for logged user return product count
	*/
	function getLoginUserProdCount( $param = [] ) {
		$department_id = $param['dept_id'];
		$param = [];
		$param['result'] = 'row';
		$param['select'] = 'count(id) as ttl';
		$param['join'][] = 	['tbl' => $this->db->dbprefix('product'). ' as jt1',
                            'cond' => "jt1.product_sku=product_code",
                            'type' => 'inner'
                          	];
		$param['where'][ 'jt1.product_is_active' ] = 1;                          	
		$param['where'][ $this->tbl_cols['user_id'] ] = $this->flexi_auth->get_user_id();
		$param['where'][ $this->tbl_cols['department_id'] ] = $department_id;		
		return $this->get_all($param)['ttl'];
	}
}
