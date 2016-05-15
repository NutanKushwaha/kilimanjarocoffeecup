<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Catalogue extends Front_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Productmodel');
        $this->load->model('Categorymodel');
    }

    function index($alias = false, $offset = 0) {
        //hard-coding $alias for coffee === by shiv
        $alias = 'coffee';
        $this->load->library('pagination');
        $this->load->helper('common_helper');
        $attributes = gParam('attributes', array());
        $inner = array();
        /* ----fetching categories---- */
        $config = array();
        $limit = 12;
        if ($alias) {
            $categoryDet = $this->Categorymodel->fetchByAlias($alias);
            if (!$categoryDet) {
                show_404();
            } else {
                $catProduct = $this->Productmodel->listAllByCategoryId($categoryDet['category_id'], $limit, $offset);
                //com_lquery(1);
                $inner['subcat'] = true;
                $inner['catdetails'] = $categoryDet;
                $inner['products'] = $catProduct;
                $config['total_rows'] = $this->Productmodel->countAll($categoryDet['category_id']);
                $category = $this->Categorymodel->listAllCategoriesAccordingToParentId($categoryDet['category_id']);
            }
        } else {
            $catProduct = $this->Productmodel->listAllByCategoryId(0);
            $inner['subcat'] = false;
            $inner['products'] = $catProduct;
            $config['total_rows'] = $this->Productmodel->countAll(0);
            $category = $this->Categorymodel->listAllCategoriesAccordingToParentId(0);
        }
        /* --------End fetching categories-------- */
        $config['base_url'] = base_url() . "catalogue/index/" . $alias;
        $config['uri_segment'] = 4;
        $config['per_page'] = $limit;
        $this->pagination->initialize($config);
        $inner['categories'] = $category;
        $inner['pagination'] = $this->pagination->create_links();
        // echo $inner['pagination'];
        $shell['contents'] = $this->load->view("catalogue-index", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
    }

    function product($p_alias = false) {
        $this->load->helper('common_helper');
        $this->load->model('rating/Ratingmodel');        
        if (!$p_alias) {
            redirect('catalogue');
            return false;
        }
        $product = $this->Productmodel->fetchByAlias($p_alias);        
        if (!$product) {
            $this->utility->show404();
            return;
        }

        /* product main details fetched */
        $product_main_details = $this->Productmodel->details($product['product_sku']);

        /* -------------get Special price if exists-------------- */
        $specialPrice = $this->Productmodel->get_special_price_by_sku($product['product_sku']);

        /* --------fetching alternate products------- */
        $alternateProduct = $this->Productmodel->getAlternateProducts($product['product_sku']);

        /* --------fetching product image gallery------- */
        $imageGallery = $this->Productmodel->getProductImageGallery($product['product_sku']);

        /* --------fetching related products------- */
        $relatedProduct = $this->Productmodel->getRelatedProducts($product['product_sku']);

        /* -------------get verified reviews-------------- */
        $reviews = $this->Ratingmodel->getVerifiedReviewsByProductID($product['product_id']);

        /* -----------getAverageRating for a product----------- */
        $avgRating = $this->Productmodel->getAverageRating($product['product_id']);

        // product detail page array starts from here
        $inner = array();
        if ($product['ref_product_id'] == 0 && $product['product_type_id'] == 2) {
            /* ------get product attributes if the current product is a parent product----- */
            $prodids = [$product_main_details['product_id'] => $product_main_details];            
            $product_main_details = $this->Productmodel->prodAttributes($prodids);
            $inner['parent'] = true;
            $inner['child'] = $product_main_details;            
            /* ------End get product attributes if the current product is a parent product----- */
        } else {
            $inner['parent'] = false;
        }
        $inner['avgRating'] = $avgRating;
        $inner['imageGallery'] = $imageGallery;
        $inner['reviews'] = $reviews;
        $inner['detail'] = $product;
        $inner['specialPrice'] = $specialPrice;
        $inner['altProduct'] = $alternateProduct;
        $inner['relProduct'] = $relatedProduct;
        $shell = array();
        $shell['contents'] = $this->load->view("detail", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/default-subpage", $shell);
    }

    /* configProdOption */
    function confProdAttr(){
        $form_data = [];
        parse_str($this->input->get("form_data"), $form_data);        
        $next_attr = $this->input->get("next_attr");
        $holdOnlyFilledAttributes = array_filter( $form_data[ 'attribute' ] );
        $holdOnlyFilledAttributes[ $next_attr  ] = '';
        $prodAttrDet = $this->Productmodel->getProdAttribSetAndSetAtrributes(  $form_data[ 'product_id' ],
                                                                array_keys( $holdOnlyFilledAttributes ));		
        $nextAttrDet = '';
        $param = [];
        foreach($prodAttrDet as $selAttrIndex => $selAttrDet){
            if( $selAttrDet[ 'id' ] ==  $next_attr){
                $nextAttrDet = $selAttrDet;
                unset( $holdOnlyFilledAttributes[ $next_attr ] );
            } else if( $selAttrDet[ 'is_sys' ] ){
                $param[ 'where' ][ $selAttrDet[ 'sys_label' ] ] = $holdOnlyFilledAttributes[ $selAttrDet[ 'id' ] ];
                unset( $holdOnlyFilledAttributes[ $selAttrDet[ 'id' ] ] );
            }
        }
        
        $prodWithAttrDet = $this->Productmodel->getProdWithAttrDetail($form_data[ 'product_id' ], $param);
        $prodWithAttrDetObj = com_arrayToObject( $prodWithAttrDet );
        $prodWithAttrDetObj = ( array )$prodWithAttrDetObj;
        /** Include path For PLINQ**/
        set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/plinq/Classes');
        /** PHPLinq_LinqToObjects */
        require_once 'PHPLinq/LinqToObjects.php';
        $filteredProdIds = [];
        $distinctProdIds = com_makelist($prodWithAttrDet, 'product_id', 'product_id', FALSE );        
        foreach($holdOnlyFilledAttributes as $leftOthrAttrIndex => $leftOthrAttrVal){
                $filteredProdIds = [];
                foreach ($distinctProdIds as $prodKind => $prodKVal) {
                    $searchProd = new stdClass();
                    $searchProd->product_id = $prodKVal;
                    $searchProd->attribute_id = $leftOthrAttrIndex;
                    $searchProd->attribute_value = $leftOthrAttrVal;                    
                    $filResultStatus = from('$filterResult')->in($prodWithAttrDetObj)
                                    ->contains( $searchProd );
                    if( $filResultStatus ){
                        $filteredProdIds[ $prodKVal ] = $prodKVal;
                    }
                }
                $distinctProdIds = $filteredProdIds;
        }
        $nextAttrOptsHtml = '';
        if( $next_attr ){            
            $holdAllAttrWithNextAttr = from('$arrDet')->in($prodWithAttrDetObj)
                                            ->where('$arrDet => $arrDet->attribute_id == "'.$next_attr.'"')                                            
                                            ->select('$arrDet');
            $distinctNextAttrOpts = [];
            foreach( $distinctProdIds as $stackProdK => $stackProdV){                
                $holdFilteredAttrWithProd = from( '$arrDet' )
                                            ->in($holdAllAttrWithNextAttr)
                                            ->where('$arrDet => $arrDet->product_id == "'.$stackProdV.'"')
                                            ->select('$arrDet');                
                if( $holdFilteredAttrWithProd ){
                    foreach($holdFilteredAttrWithProd as $holdStackIndex => $holdStackDet ){
                        $distinctNextAttrOpts[ ] = $holdStackDet->attribute_value;
                    }
                }
            }
            if( $distinctNextAttrOpts ){
                $param = [];
                $param[ 'select' ]      = 'id,option_text';
                $param[ 'from_tbl' ]    = 'attributes_set_attributes_option';
                $param[ 'where'][ 'in' ][ 0 ] = 'id';
                $param[ 'where'][ 'in' ][ 1 ] = $distinctNextAttrOpts;
                $attribOpt = $this->Productmodel->get_all( $param );
                $nextAttrOptsHtml = com_makelistElem($attribOpt, 'id', 'option_text', TRUE, 'Select '.$nextAttrDet[ 'label' ]);                
            }
        }
        $ouput = [
                    'success'   => 1,
                    'html'      => $nextAttrOptsHtml
                ];
        echo json_encode( $ouput );
        exit();
    }    
}

?>
