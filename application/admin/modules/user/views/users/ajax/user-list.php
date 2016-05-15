<div class="tableWrapper"> 
    <?php
            $table_property = array('table_open' => '<table width="100%" border="0" cellpadding="2" cellspacing="0" class="table-bordered user-management-list-table">');
            $this->table->set_template($table_property);
            $this->table->set_heading($table_labels);            
            foreach ($users as $item) {									
                    $actn = 'activate_email';
                    $actn_txt = 'Activate Email';
                    $actn_confrm = 'onclick="return confirm(\'Are you sure you want to send activation email?\');"';
                    if($item['uacc_active']){
                        $actn = 'suspend';
                        $actn_txt = 'Suspend';
                        $actn_confrm = 'onclick="return confirm(\'Are you sure you want to suspend this account?\');"';
                    }
                    /* <td><?= ellipsize(com_arrIndex($item, 'uacc_email'), 15, .5); ?></td> */
                    $this->table->add_row(  com_arrIndex($item, 'uacc_username'),
                                            com_arrIndex($item, 'ugrp_name'), 
                                            com_arrIndex($item, 'upro_company', '--------'),
                                            com_arrIndex($item, 'upro_profession', ''),
                                            com_get_approval( com_arrIndex($item, 'upro_approval_acc', '') ),
                                            anchor(base_url("user/forgot_password/".com_arrIndex($item, 'uacc_username')), 'Forgot Password'),
                                            anchor(base_url("user/view/".com_arrIndex($item, 'uacc_id')), 'View'),
                                            anchor(base_url("user/update_privilieges/".com_arrIndex($item, 'uacc_id')), 'Manage'),
                                            anchor(base_url("user/update_direct_order_flag/".com_arrIndex($item, 'uacc_id')), ($item[ 'upro_direct_order' ] ? 'Disable' : 'Enable') )
                                            //anchor(base_url("user/$actn/".com_arrIndex($item, 'uacc_username')), $actn_txt, $actn_confrm)
                                        );
            }            
            echo $this->table->generate();
    ?>
</div>
<div class="clearfix"></div>
<div class="ajax-pagination" style="text-align:center;">
        <ul class="pagination">
            <?= $pagination;?> 
        </ul>
</div>
