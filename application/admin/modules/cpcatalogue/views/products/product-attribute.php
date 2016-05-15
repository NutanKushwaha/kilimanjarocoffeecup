<header class="panel-heading">
    <div class="row">
        <div class="col-sm-1" style="text-align: right">
            <a href="cpcatalogue/product/"><h3 style="cursor: pointer; margin: 0; color: #fff"><i class="fa fa-arrow-left" title="Products"></i></h3></a>
        </div>
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Manage Product Attributes</h3>
        </div>
    </div>
</header>
<div class="col-sm-12">
    <?php
        $this->load->view(THEME . 'messages/inc-messages');        
    ?>
</div>
<div class="tableWrapper mar-top10">
    <?php
        $FORM_JS = ' name="product-attributes" ';
        echo form_open(current_url($product['product_id']) , $FORM_JS );
            $field_html = '';
            foreach ($attributes as $key => $value) {
                $val = 'attribute['.$value['id'].']';
                if(isset($attribute_values[$value['id']]) ){
                    $val = $attribute_values[$value['id']];
                }
                
                if(isset($_REQUEST['attribute['.$value['id'].']'])){
                    $val = $_REQUEST['attribute['.$value['id'].']'];
                }
                $field_html .= '<div class="form-group">
                                    <div class="col-sm-12">
                                        <label>'.$value['label'].'</label>
                                        <input type="text" class="form-control"
                                            name="attribute['.$value['id'].']"
                                            value="'.$val.'"
                                        />
                                    </div>
                                </div>
                                ';
            }
            echo $field_html;
        ?>
        <div class="form-group">
            <div class="col-sm-12">                
                <input type="submit" value="Submit" class="btn btn-primary">
            </div>
        </div>
    <?php echo form_close(); ?>
</div>
