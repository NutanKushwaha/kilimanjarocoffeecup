JOIN via 

$this->db->dbprefix('product') if escaping added true

$condition['select'] = 'SELECT FIELDS';
$condition['where']['set_id'] = $set_id;
$condition['join'][] = array(	'tbl' => 'attributes_set_attributes_option', 
								'cond' => 'attributes_set_attributes_option.attribute_id=attributes_set_attributes.id', 
								'type' => 'left'
							);
$condition['join'][] = array(	'tbl' => 'attributes_type', 
								'cond' => 'attributes_type.id=attributes_set_attributes.type', 
								'type' => 'inner',
								'pass_prefix' => true /* For escape the join part from escaping */
							);
