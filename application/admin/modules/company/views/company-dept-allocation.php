<style type="text/css">
    .badge {
        border-radius:10%;
        min-height: 30px;
        margin-left: 5px;
        min-width: auto;
        padding: 10px 0 10px;
        min-width: 55px;
        width: auto;
        height: auto;
    }
    .left-add-product-abut-desc p span {
        font-size: 13px !important;
        line-height: 18px;
    }
</style>
<div class="modal-dialog" role="document">
    <div class="col-sm-12">         
            <a href="company/index/<?= ( $perpage.'/'.(((int)$offset ? (int)$offset : 1)-1) *15).'/#'.strtolower($company['details']['name']) ?>" class="btn btn-orange" >Go Back!</a>
            <div class="clearfix"></div>
    </div>        
    <div class="modal-content col-sm-12">
        <?php            
            $form_attr = [ 'name' => 'company-policy', 
                            'id' => "company-policy" 
                        ];
            $hiddden = ['company_code' => $company['details']['company_code'] ];
            echo form_open('company/dept_allocation/'.$company['details']['id'], $form_attr, $hiddden );            
        ?>        

          <div class="modal-body">
                <div class="col-md-12">
                    <h4><?= 'Company :'.ucfirst( $company['details']['name'] ); ?></h4>
                </div>                
                <div class="clearfix"></div>
                <div class="border-1x"></div>      
                <div class="product-buy-limit-info-container">                
                    <div class="clearfix"></div>
                                  
                    <div class="col-sm-12">
                        <div class="product-management-container-2">

                            <div class="product-manager-list-header-section">
                                <div class="prod-dept-name">
                                    <div class="product-buy-limit-title">
                                        <h4> Department </h4>
                                    </div>
                                </div>
                            </div><!-- Heading Product Option Manage -->

                            <ul class="list-unstyled product-manager-list">

                                <?php 
                                    foreach ($departments as $key => $value) {
                                    $checked = '';
                                    $policy_days = '1';
                                    $policy_qty = '1';
                                    if(isset($company_policy[$value['id']])){
                                        $checked = " checked ";
                                    }                                    
                                ?>
                                    <li class="col-md-3">
                                        <div class="product-department-check-container">                                            
                                            <div class="left-product-department-checkbox-btn-container">
                                                <!-- .roundedTwo -->
                                                    <div class="dept-check-round-btn" >
                                                        <div class="roundedTwo">
                                                            <input type="checkbox" id="roundedOne-<?= $key; ?>" 
                                                            name="depatment[]" 
                                                            <?= $checked; ?>   value="<?= $value['id']; ?>" >
                                                            <label for="roundedOne-<?= $key; ?>"></label>
                                                        </div>
                                                <!-- end .roundedTwo -->
                                                    </div>

                                                    <div class="dept-check-btn-title">
                                                        <label for="roundedThree"><?= $value['name']; ?></label>
                                                    </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>                                  
                                    </li>
                                <?php 
                                }
                                ?>

                                <div class="clearfix"></div>
                            </ul>
                             <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
              
                <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
            <input name="policy-submit" type="submit" class="btn btn-orange btn-large" value="Submit!"></input>
        </div>
        <?= form_close(); ?>        
    </div>
  </div>
