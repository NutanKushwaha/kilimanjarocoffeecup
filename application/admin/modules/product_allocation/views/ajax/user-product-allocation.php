<?php if (!$products) { ?>
    <div class="bs-example">
        <div class="alert alert-danger fade in">        
            <strong>No product is assign to the department.</strong><br/>
            If you want to assign product now please <strong><a href="company/department/assignProduct/<?= $dept_id; ?>">click here</a></strong>.
        </div>
    </div>
<?php
} else {
    $pCounts = sizeof($products);
    $pxSize = 980;
    if ($pCounts > 3) {
        $pxSize += ( $pCounts - 3) * 250;
    }
    ?>
    <style>
        .container-full {
            overflow-y: scroll;
            width: auto;
        }
        .inner-container {
            border: 1px solid rgb(187, 187, 187);
            width: <?= $pxSize; ?>px;
        }    
        .container-row{
            background: rgb(238, 238, 238) none repeat scroll 0 0;
            border-bottom: 1px solid rgb(187, 187, 187);
            margin-bottom: 1px;

        }
        .right{
            background: rgb(255, 255, 255) none repeat scroll 0 0;
            border-left: 1px solid rgb(202, 202, 202);
            float: left;
        }
        .left {
            background: rgb(238, 238, 238) none repeat scroll 0 0;
            float: left;
            padding: 0 10px;
        }
        ul{
            list-style: none;
            margin: 0;
            padding: 0;
        }


        .right-ul-list{
            display: inline-block;
        }
        .right-ul-list > li {
            border-left: 1px solid rgb(238, 238, 238);
            float: left;
            padding: 5px 10px;
            width: 250px;
        }
        .block-first-list {
            float: left;
            margin: 0 3px;
            width: auto;
        }
        .block-first-list:nth-child(3n+4) {

        }
        .header-top{
            background: #eee;
        }
        .left-ul-list > li {
            font-size: 13px;
            font-weight: 900;
            padding: 5px;
            text-transform: capitalize;
            width: 110px;
        }

        .top-text-head {
            font-weight: 600;
            margin-bottom: 3px;
            padding-left: 4px;
        }
    </style>
    <?php
    echo form_hidden($hidden_qty);
    echo form_hidden($hidden_days);
    echo form_hidden($hidden_prods);
    echo form_hidden($hidden_by_pass);    
    ?>
    <div class="container-full">
        <div class="outer-container row-first">
            <div class="inner-container">
                <!-- Top container-->
                <div class="header-title-container">
                    <div class="container-row">
                        <div class="left">  
                            <ul class="left-ul-list">
                                <li> Company User</li>
                            </ul>
                        </div>
                        <div class="right">
                            <div class="header-top">
                                <?php
                                /* Check is products exist */
                                if ($products) {
                                    /* Loop on products */
                                    echo '<ul class="right-ul-list" >';
                                    foreach ($products as $prodK => $prodV) {
                                        ?>
                                        <li>
										<?php /* Product Name */ ?>
                                            <div class="top-text-head-text">
                                                <div class="top-text-head">
														<?= ellipsize($prodV['product_name'], 20, .5); ?>
                                                </div>
                                                <div class="top-text-head" style="width:100%">
                                                    <small>Policy:
                                                    <?= $period[$prodV['days_limit']] . '-(' . $prodV['qty_limit'] . ')'; ?></small>
                                                    <?php
                                                    $row_qty = array(
                                                        'name' => 'prod-quantity',
                                                        'value' => $prodV['qty_limit'],
                                                        'type' => 'number',
                                                        'style' => 'width:40%',
                                                        'class' => 'userQty',
                                                        'data-id' => 'userQty' . $prodV['product_id'],
                                                    );
                                                    echo form_input($row_qty);
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            /* Check is product related attribute exist then loop to display */
                                            if (isset($prodAttr[$prodV['product_id']]['prdAttrLblOpts']['product'])) {
                                                foreach ($prodAttr[$prodV['product_id']]['prdAttrLblOpts']['product']
                                                as $prodAttrK => $prodAttrV) {
                                                    /* Product Attributes */
                                                    ?>
                                                    <div class="block-first-list"> 
                                                        <?php
                                                        $productAttrId = 'product_' . $dept_id . '_' . $prodV['product_sku'] . '_product_' . $prodAttrK;
                                                        $productAttrName = 'product[' . $dept_id . '][' . $prodV['product_sku'] 
                                                                            . '][product][' . $prodAttrK . ']';
                                                        $defaultVal = '';
                                                        /* check is hidden product value exist for specific dept & prod sku & product related attribute */
                                                        if (isset($hidden_prods[$productAttrName])) {
                                                            $defaultVal = $hidden_prods[$productAttrName];
                                                        }
                                                        $lblAttr = [
                                                            'style' => 'width:89px;float:left;'
                                                        ];
                                                        echo form_label($prodAttrV['label'], $productAttrId, $lblAttr);
                                                        $js = 'style = "width: 91px;float:left;" id="'.$productAttrId.'"';
                                                        echo form_dropdown($productAttrName, $prodAttrV['attrOpts'], $defaultVal, $js);
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </li>
                                    <?php
                                    } /* End for each loop */
                                    echo '</ul>';
                                } else {
                                    echo '<ul class="right-ul-list"><li>No produc assigned to this department</li></ul>';
                                }
                                ?>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div> 
                </div>
                <!-- Lower container-->            
                <?php
                /* Check is user exist */
                if ($users) {
                    /* Loop on users */
                    foreach ($users as $usersK => $usersV) {
                        $user_id = $usersV['uacc_id'];
                        ?>
                        <div class="container-row">
                            <div class="left">
                                <ul class="left-ul-list">
                                    <li><?= $usersV['uacc_username']; ?></li>
                                </ul>
                            </div>
                            <div class="right">
                                <?php
                                echo '<ul class="right-ul-list">';
                                /* first check is products exist */
                                if ($products) {
                                    /* Loop on every product */
                                    foreach ($products as $prodK => $prodV) {
                                        /* Check is user attribute exist for current index product */
                                        $userProductAttrHtml = '';
                                        if (isset($prodAttr[$prodV['product_id']]['prdAttrLblOpts']['user'])) {
                                            /* loop on user  */
                                            foreach ($prodAttr[$prodV['product_id']]['prdAttrLblOpts']['user']
                                            as $prodAttrK => $prodAttrV) {
                                                $productAttrId = 'product_' . $dept_id . '_' . $prodV['product_sku'] . '_user_' . $prodAttrK . '_' . $user_id;
                                                $productAttrName = 'product[' . $dept_id . '][' . $prodV['product_sku'] 
                                                                    . '][user][' . $prodAttrK . '][' . $user_id . ']';
                                                $userProductAttrVal = '';
                                                if (isset($hidden_prods[$productAttrName])) {
                                                    $userProductAttrVal = $hidden_prods[$productAttrName];
                                                }
                                                $lblAttr = [ 'style' => 'float: left; width: 89px;'];
                                                $userProductAttrHtml .= '<div class="block-first-list" style="margin-top:10px;">';
                                                $userProductAttrHtml .= form_label($prodAttrV['label'], $productAttrId, $lblAttr);
                                                
                                                $dd='style="float: left; width: 134px;" id="'.$productAttrId.'"';
                                                $userProductAttrHtml .= form_dropdown($productAttrName, $prodAttrV['attrOpts'], $userProductAttrVal,$dd);
                                                $userProductAttrHtml .= '<div class="clearfix"></div>'.'</div>';
                                            }
                                        }

                                        $dayName = 'days[' . $dept_id . '][' . $prodV['product_sku'] . '][' . $user_id . ']';
                                        $qtyName = 'quantity[' . $dept_id . '][' . $prodV['product_sku'] . '][' . $user_id . ']';
                                        $noPolicyId = 'users_no_policy_' . $dept_id . '_' . $prodV['product_sku'] . '_' . $user_id;
                                        $noPolicyName = 'users_no_policy[' . $dept_id . '][' . $prodV['product_sku'] . '][' . $user_id . ']';

                                        $userQtyDefVal = $prodV['qty_limit'];
                                        if (isset($hidden_qty[$qtyName])) {
                                            $userQtyDefVal = $hidden_qty[$qtyName];
                                        }

                                        $userDayDefVal = $prodV['days_limit'];
                                        if (isset($hidden_qty[$dayName])) {
                                            $userDayDefVal = $hidden_qty[$dayName];
                                        }

                                        $no_policyDefVal = 0;
                                        if (isset($hidden_by_pass[ $noPolicyName ]) && $hidden_by_pass[ $noPolicyName ] ) {
                                            $no_policyDefVal = 1;
                                        }

                                        $row_qty = array(
                                            'name' => $qtyName,
                                            'value' => $userQtyDefVal,
                                            'type' => 'number',
                                            'style' => 'width:100%',
                                            'class' => 'userQty userQty' . $prodV['product_id']
                                        );
                                        $policy_status_opt = [
                                                            '0' => 'No',
                                                            '1' => 'Yes',
                                                        ];
                                        $userPordDaysLimitInput = [
                                                                'type' => 'hidden',
                                                                'name' => $dayName,
                                                                'value' => $userDayDefVal,
                                                            ];
                                        $otherJs = ' id="'.$noPolicyId.'" style="float: left; width: 134px;"';
                                        $userProductPolicyHtml = '  <div style="width:100%">
                                                                    <div style="width:40%;float:left;">
                                                                        ' .form_input( $userPordDaysLimitInput ).
                                                                    '</div>
                                                                    <div style="width:60%;float:left;">
                                                                    ' . form_input($row_qty) . '
                                                                    </div>
                                                                </div>';
                                        $userProductPolicyByPassHtml = '<div class="block-first-list" style="margin-top:10px;"> 
                                                                            <label  style="float: left; width: 89px;" 
                                                                                        for="'.$noPolicyId.'">By-Pass Policy
                                                                            </label>
                                                                            ' .form_dropdown($noPolicyName, $policy_status_opt, $no_policyDefVal, 
                                                                                    $otherJs)
                                                                            . '
                                                                        </div>';
                                        echo '<li>' . $userProductPolicyHtml .$userProductPolicyByPassHtml. $userProductAttrHtml . '</li>';
                                    }
                                } else {
                                    echo '<li>No produc assigned to this department</li>';
                                }
                                echo '</ul>';
                                ?>                    
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        /* Loop End here */
                    }
                    /* IF end */
                }
                ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.userQty').on('change', function (event) {
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
                var relatedQty = '.' + $(this).data('id');
                $(relatedQty).val($(this).val());
                event.preventDefault();
            });
        });
    </script>
<?php
}
