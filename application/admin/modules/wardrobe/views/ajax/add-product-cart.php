<?php

  /* quantity range to display */
  $qty_range_start = 0;
  $qty_range_last = 0;

  if( $userAssignments && is_array($userAssignments) 
      && isset($userAssignments[0]['quantity']) && $userAssignments[0]['quantity'] ){
      $qty_range_start = 1;
      $qty_range_last = $userAssignments[0]['quantity'];
  }
  $qty_range = range( $qty_range_start, $qty_range_last );
  $qty_range = array_combine($qty_range, $qty_range);

  $userAssignments =   com_makeArrIndexToField( $userAssignments , 'attribute_id');  
  $attributeHtml = '';
  if( $prodAttrb )  {
    $attributeHtml = '<li>
                        <div class="product-manager-list-header-section">
                            <div class="col-md-4">
                                <div class="product-buy-limit-title">
                                    <h4> Attribute </h4>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-md-8 hidden">
                                <div class="product-buy-limit-title">
                                    <h4> Options </h4>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                      </li>
                      <div class="clearfix"></div>';
  /* Attributes  */                      
    foreach ($prodAttrb as $prodAttrRelatedKey => $prodAttrDetails) {
        foreach ($prodAttrDetails as $prodAttrDetailsAttrKey => $prodAttrDetailsAttrDetail) {
          $defSelect = '';
          if( isset( $userAssignments[ $prodAttrDetailsAttrKey ] ) ){
              $defSelect = com_arrIndex($userAssignments[ $prodAttrDetailsAttrKey ], 'attribute_value', $defSelect);
          }
          $attributeHtml .= '<li>
                              <div class="col-md-12" style="text-align:left;">
                                <label for="roundedThree">'.$prodAttrDetailsAttrDetail['label'].'</label>
                              </div>
                              <div class="col-md-12">
                                <div class="set-day-limit-option">'.form_dropdown('attribute['.$prodAttrDetailsAttrKey.']', $prodAttrDetailsAttrDetail['attrOpts'], $defSelect, ' class="form-control"' ).'</div>
                              </div>
                            </li>
                            <div class="clearfix"></div>';
        }
    }
  }

  /* Quantity */
  $attributeHtml .= '<li>
                      <div class="col-md-12" style="text-align:left;">
                        <label for="roundedThree">Quantity</label>
                      </div>
                      <div class="col-md-12">
                        <div class="set-day-limit-option">'.form_dropdown('quantity', $qty_range, '0', ' class="form-control" id="quantity"' ).'</div>
                      </div>
                    </li>
                    <div class="clearfix"></div>';
  $hiddenDet = [];  
  $hiddenDet[ 'deptId' ] = $deptId;
  $hiddenDet[ 'product_id' ] = $product_main_details['product_id'];
  $hiddenDet[ 'product_sku' ] = $product_main_details['product_sku'];
  $hiddenDet[ 'product_name' ] = $product_main_details['product_name'];

  $attributeHtml .= form_hidden( $hiddenDet );  
  echo $attributeHtml;
?>  
