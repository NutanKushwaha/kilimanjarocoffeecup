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

            <a href="cpcatalogue/product/index/15/<?= ( ( (int)$product_offset-1 > 0 ? (int)$product_offset-1 :0  ) *15).'/#'.strtolower($product['product_name']) ?>" class="btn btn-orange" >Go Back!</a>

            <div class="clearfix"></div>

    </div>        

    <div class="modal-content col-sm-12">

        <?php            

            $form_attr = [ 'name' => 'product-policy', 

                            'id' => "product-policy" 

                        ];

            $hiddden = ['product_id' => $product['product_id'] ];

            echo form_open('cpcatalogue/product/policy/'.$product['product_id'], $form_attr, $hiddden );            

        ?>        



          <div class="modal-body">

                <div class="leftsidebar col-sm-4">

                    <div class="popup-left-add-product-img-container">

                        <div class="left-add-product-img">

                        <?php

                            $params = [     'image_url' => $this->config->item('PRODUCT_IMAGE_URL').$product['product_image'],

                                            'image_path' => $this->config->item('PRODUCT_IMAGE_PATH').$product['product_image'],

                                            'resize_image_url' => $this->config->item('PRODUCT_RESIZE_IMAGE_URL'),

                                            'resize_image_path' => $this->config->item('PRODUCT_RESIZE_IMAGE_PATH'),

                                            'width' => 200,

                                            'height' => 200,                                        

                                            'default_picture' => $this->config->item('PRODUCT_IMAGE_URL').'default_product.jpg',

                                    ];

                            $new_image_url = resize( $params);

                        ?>

                            <img src="<?= $new_image_url; ?>" class="img-responsive" />

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                     <h4><?= $product['product_name']; ?></h4>



                </div>



                <div class="rightsidebar col-sm-4">

                    <div class="popup-left-add-product-full-desc-container">

                        <div class="left-add-product-full-desc text-right">

                            <div class="left-add-product-tittle">

                               

                                <h4>$ <?= $product['product_price']; ?></h4>

                                <div class="left-add-product-point-add">

                                    <span class="badge pull-right"><?= $product['product_point']; ?></span>

                                    <div class="clearfix"></div>

                                </div>

                            </div>

                            

                        </div>

                    </div>

                </div>

                <div class="clearfix"></div>



                <div class="col-md-12">

                    <div class="prod-full-desc-container">

                        <div class="left-add-product-abut-desc">

                                <h4 style="background:#eee;padding:10px;">Product Description</h4>

                                <?= $product['product_description']; ?>

                            </div>

                     </div>



                </div>

              

                <div class="product-buy-limit-info-container">                

                    <div class="clearfix"></div>

                    <hr>                

                    <div class="col-sm-12">

                        <div class="product-management-container-2">

                            <ul class="list-unstyled product-manager-list">

                                <li>

                                    <div class="product-manager-list-header-section">

                                        <div class="col-md-4">

                                            <div class="product-buy-limit-title">

                                                <h4> Department </h4>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="product-buy-limit-title">

                                                <h4> Days Limit  </h4>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="product-buy-limit-title">

                                                <h4> Qty Allowed  </h4>

                                            </div>

                                        </div>

                                    </div>

                                </li><!-- Heading Product Option Manage -->

                                <?php 

                                    foreach ($departments as $key => $value) {

                                    $checked = '';

                                    $policy_days = '1';

                                    $policy_qty = '1';

                                    if(isset($product_policy[$value['id']])){

                                        $checked = " checked ";

                                        $policy_qty = $product_policy[$value['id']]['qty_limit'];

                                        $policy_days = $product_policy[$value['id']]['days_limit'];

                                    }                                    

                                ?>

                                    <li>

                                        <div class="col-md-4">

                                            <div class="left-product-department-check-title">

                                                <label for="roundedThree"><?= $value['name']; ?></label>

                                            </div>

                                            <div class="left-product-department-checkbox-btn-container">

                                                <!-- .roundedTwo -->

                                                    <div class="left-product-department-checkbox-btn">

                                                        <div class="roundedTwo">

                                                            <input type="checkbox" id="roundedOne-<?= $key; ?>" name="depatment[]" 

                                                            <?= $checked; ?>   value="<?= $value['id']; ?>" >

                                                            <label for="roundedOne-<?= $key; ?>"></label>

                                                        </div>

                                                <!-- end .roundedTwo -->

                                                    </div>

                                                <div class="clearfix"></div>

                                            </div>

                                        </div>

                                  

                                        <div class="col-md-4">

                                            <div class="set-day-limit-option">

                                                <?php

                                                    $JS = ' class="select_picker" ';

                                                    echo form_dropdown('days['.$value['id'].']', $days, $policy_days, $JS);

                                                ?>                                                

                                            </div>

                                        </div>

                                  

                                        <div class="col-md-4">

                                            <div class="set-day-limit-option">

                                                <input type="number" 

                                                        value="<?= $policy_qty ?>" 

                                                        name="qty[<?= $value['id'] ?>]">

                                            </div>

                                        </div>

                                    </li>

                                <?php 

                                }

                                ?>

                            </ul>

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

<script type="text/javascript">

    /*

    $( "#product-policy" ).submit(function( event ) {        

        var form_data = $( this ).serialize();

        $.ajaxSetup({

            data: {

                <?= $this->security->get_csrf_token_name().':"'.$this->security->get_csrf_hash().'"'; ?>

            }

        });

        $.post( "cpcatalogue/product/setPolicy", { policy: form_data }, function( data ) {

                $('#myModal').modal('hide');

                location.reload();

            }, "json");

         event.preventDefault();

    });    

    */



    $( ".set-day-limit-option" ).keypress(function() {

        return false;

    });

    $( ".set-day-limit-option > input" ).change(function() {

        if($(this).val() < 0){

            $(this).val(0);

        }

    });

</script>  

