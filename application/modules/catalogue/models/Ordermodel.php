<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
  CREATE TABLE `bg_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `company_code` varchar(255) NOT NULL DEFAULT '',
  `company_name` varchar(255) NOT NULL DEFAULT '',
  `is_guest_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_num` varchar(255) NOT NULL,
  `order_qty` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0',
  `transaction_no` varchar(255) NOT NULL DEFAULT '',
  `cart_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `order_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(255) NOT NULL DEFAULT '',
  `status_updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_num` (`order_num`),
  KEY `customer_id` (`customer_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */

class OrderModel extends Commonmodel {

    private $data;

    function __construct() {
        parent::__construct();
        $this->set_attributes();
    }

    protected function set_attributes() {
        $this->data = array();

        $this->tbl_name = 'order';
        $this->tbl_pk_col = 'id';

        $this->tbl_alias = 'ord';
    }

    public function addOrder() {
        $output = [];
        $output['status'] = '0';
        $output['message'] = 'Please add item in cart';
        $this->load->model('Cartmodel');
        $this->load->model('catalogue/Orderitemmodel');
        $this->load->model('catalogue/Ordershipdetmodel');
        $cartContents = $this->cart->contents();
        if ($cartContents) {
            $donation_sku = uniqid('don-');
            $orderItems = [];
            $insert_order_id = 0;
            $is_reordered = '';
            foreach ($cartContents as $cartIndex => $cartItemDetail) {
                $is_reordered = com_arrIndex($cartItemDetail['options'], 'reorder', 0);
                $productSku = $cartItemDetail['options']['product']['product_sku'];
                $orderItems[$cartIndex]['order_id'] = &$insert_order_id;
                $orderItems[$cartIndex]['product_ref'] = $productSku;
                $orderItems[$cartIndex]['order_item_qty'] = $cartItemDetail['qty'];
                $orderItems[$cartIndex]['order_item_normal_price'] = com_arrIndex($cartItemDetail, 'normal_price', 0);
                $orderItems[$cartIndex]['order_item_name'] = $cartItemDetail['name'];
                $orderItems[$cartIndex]['order_item_price'] = $cartItemDetail['price'];
                $orderItems[$cartIndex]['order_sub_total'] = $cartItemDetail['subtotal'];
                $orderItems[$cartIndex]['order_item_options'] = json_encode($cartItemDetail['options']);
            }
            if ($this->session->userdata('donation') != null) {
                $donation_key = md5($donation_sku);
                $orderItems[$donation_key]['order_id'] = &$insert_order_id;
                $orderItems[$donation_key]['product_ref'] = $donation_sku;
                $orderItems[$donation_key]['order_item_qty'] = 1;
                $orderItems[$donation_key]['order_item_name'] = "Donation";
                $orderItems[$donation_key]['order_item_price'] = round($this->session->userdata('donationAmount'), 2);
                $orderItems[$donation_key]['order_item_options'] = json_encode(array("" => ""));
                $orderItems[$donation_key]['order_item_normal_price'] = 0;
            }

            if ($orderItems) {
                $order = [];
                /* Inser Order */
                /* Logged user Company code */
                $userCompanyCode = $this->flexi_auth->get_user_custom_data('upro_company');
                /* Company name set to "" */
                $param = [];
                $param['select'] = 'name';
                $param['result'] = 'row';
                $param['from_tbl'] = 'company';
                $param['where']['company_code'] = $userCompanyCode;
                $companyName = $this->Cartmodel->get_all($param);
                $order['company_name'] = '';
                if ($companyName) {
                    /* Company name set to code owner name */
                    $order['company_name'] = $companyName['name'];
                }
                $order['is_paid'] = 0;
                $order['vat'] = MCC_VAT;
                $order['discount'] = 0;
                $order['is_guest_order'] = 1;
                $order['status'] = '';
                $order['transaction_no'] = '';
                $order['is_reordered'] = $is_reordered;
                $order['order_time'] = date('Y-m-d H:i:s');
                $order['cart_total'] = $this->cart->total();
                $conpany = $this->session->userdata('company');
                if ($conpany) {
                    $order['unique_code'] = $conpany['unique_code'];
                    $order['com_user_id'] = $conpany['company_user_id'];
                    $order['company_id'] = $conpany['company_id'];
                }
                /*
                  $cart_total = $this->cart->total();
                  $coupon_details = $this->session->userdata('coupon' );
                  $coupon_discounted_amount = $this->session->userdata('coupon_discounted_amount' );
                  if ($coupon_details && $coupon_discounted_amount) {
                  $cart_total = $cart_total - $coupon_discounted_amount;
                  $order['voucher_code'] = $coupon_details['code'];
                  $order['discount'] = floatval($coupon_discounted_amount);
                  }
                 */
                $order['order_total'] = total_after_calcualtion();
                if ($this->session->userdata('donation') != null) {
                    $donation = $this->session->userdata('donation');
                    $order['donation_sku'] = $donation_sku;
                    $order['donation'] = "$donation";
                    $order['donation_type'] = $this->session->userdata('donationMode');
                    $order['donation_amount'] = round($this->session->userdata('donationAmount'), 2);
                }
                $order['status_updated_on'] = date('Y-m-d H:i:s');
                $order['order_qty'] = $this->cart->total_items();
                $order['status'] = 'Not Processed';
                if ($this->session->userdata('checkoutRole') == "guest") {
                    $order['order_num'] = strtoupper(uniqid('GUEST_'));
                    $orderNumber = $order['order_num'];
                    $order['customer_id'] = $this->session->userdata('guest_user_id');
                    $loged_in_id = $order['customer_id'];
                    $order['company_code'] = "";
                } else {
                    $order['order_num'] = strtoupper(uniqid('ORD_'));
                    $orderNumber = $order['order_num'];
                    $order['customer_id'] = $this->flexi_auth->get_user_custom_data('uacc_id');
                    $loged_in_id = $order['customer_id'];
                    $order['company_code'] = $this->flexi_auth->get_user_custom_data('upro_company');
                }
                $billingAndShipping = $this->session->userdata('CheckoutAddress');
                $userInfo = $this->session->userdata('guestUserInfo');
                //e($order);
                $insert_order_id = $this->Ordermodel->insert($order);
                $order_ship_detail = [];
                $order_ship_detail['order_id'] = $insert_order_id;
                $order_ship_detail['billing_phone'] = $billingAndShipping['billing_uadd_phone'];
                $order_ship_detail['billing_company'] = $this->input->post('billing_uadd_company');
                $order_ship_detail['billing_city'] = $billingAndShipping['billing_uadd_city'];
                $order_ship_detail['billing_county'] = $billingAndShipping['billing_uadd_county'];
                $order_ship_detail['billing_country'] = $billingAndShipping['billing_uadd_country'];
                $order_ship_detail['billing_zipcode'] = $billingAndShipping['billing_uadd_post_code'];
                $order_ship_detail['billing_address1'] = $billingAndShipping['billing_uadd_address_01'];
                $order_ship_detail['billing_address2'] = $this->input->post('billing_uadd_address_02');

                $order_ship_detail['phone'] = $billingAndShipping['uadd_phone'];
                $order_ship_detail['company'] = $this->input->post('uadd_company');
                $order_ship_detail['city'] = $billingAndShipping['uadd_city'];
                $order_ship_detail['county'] = $billingAndShipping['uadd_county'];
                $order_ship_detail['country'] = $billingAndShipping['uadd_country'];
                $order_ship_detail['postcode'] = $billingAndShipping['uadd_post_code'];
                $order_ship_detail['address_1'] = $billingAndShipping['uadd_address_01'];
                $order_ship_detail['address_2'] = $this->input->post('uadd_address_02');

                if ($this->session->userdata('guest_user_id') != null) {
                    $order_ship_detail['first_name'] = $userInfo['upro_first_name'];
                    $order_ship_detail['order_email'] = $userInfo['uacc_email'];
                    $order_ship_detail['last_name'] = $userInfo['upro_first_name'];
                } else {
                    $order_ship_detail['first_name'] = "";
                    $order_ship_detail['order_email'] = "";
                    $order_ship_detail['last_name'] = "";
                }
                $this->Orderitemmodel->insert_bulk($orderItems);
                $this->Ordershipdetmodel->insert($order_ship_detail);
                if ($coupon_details && $coupon_discounted_amount) {
                    $opt = [];
                    $opt['order_num'] = $orderNumber;
                    $opt['voucher_id'] = $coupon_details['id'];
                    $opt['customer_id'] = $loged_in_id;
                    $opt['voucher_details'] = json_encode($coupon_details);
                    $this->db->insert('voucher_used', $opt);
                    $opt = [];
                }
                $output['status'] = '1';
                $output['message'] = 'Order placed successfully';
            }
        }
        /* ------------- unset the variable after order processing ------------- */
//        $this->session->unset_userdata("donation");
//        $this->session->unset_userdata("donationMode");
//        $this->session->unset_userdata("donationAmount");
//        $this->Cartmodel->emptyCart();
        /* ------------- unset the variable after order processing ------------- */
        return $insert_order_id;
    }

    function OrderEmail($onum) {
        $this->load->model('customer/Ordermodel', 'ORD');
        $orderDetail = $this->ORD->getOrderDetail($onum);

        $orderItems = $this->ORD->listOrderItems($orderDetail['id']);
        $inner = array();
        $shell = array();
        $inner['order'] = $orderDetail;
        $inner['order_items'] = $orderItems;
        $shell['contents'] = $this->load->view("order-success", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    public function fetchOrders($user_type = NULL, $offset = 0, $perpage = 15, $user_id = 0) {
        $param = [];
        if ($user_type == ADMIN) {
            com_e("code pending for admin");
        } else if ($user_type == CMP_ADMIN) {
            $companyCode = $this->flexi_auth->get_comp_admin_company_code();
            $param['select'] = '  uacc_username,  customer_id,    is_guest_order,
                                    company_code,   order_num,      order_total,    
                                    order_qty ,     DATE_FORMAT( order_time,  "%d-%m-%Y") as order_date ';
            $param['join'][] = [ 'alias' => 'uac',
                'tbl' => 'user_accounts',
                'cond' => 'uac.uacc_id=customer_id',
                'type' => 'left',
                'pass_prefix' => true
            ];
            $param['where']['is_guest_order'] = 0;
            $param['where']['company_code'] = $companyCode;
            if ($user_id) {
                $param['where']['customer_id'] = $user_id;
            }
        } else if ($user_type == USER) {
            $param['select'] = '  order_num, order_total, order_qty , 
                                    DATE_FORMAT( order_time,  "%d-%m-%Y") as order_date ';
            $param['where']['is_guest_order'] = 0;
            $param['where']['customer_id'] = $this->flexi_auth->get_user_custom_data('uacc_id');
        }
        $param['limit'] = [ 'limit' => $perpage, 'offset' => $offset];
        return $this->get_all($param);
    }

    function countAllOrders($user_type, $user_id = 0) {
        if ($user_type == ADMIN) {
            com_e("code pending for admin");
        } else if ($user_type == CMP_ADMIN) {
            $companyCode = $this->flexi_auth->get_comp_admin_company_code();
            $param['select'] = 'id';
            $param['where']['is_guest_order'] = 0;
            $param['where']['company_code'] = $companyCode;
            if ($user_id) {
                $param['where']['customer_id'] = $user_id;
            }
        } else if ($user_type == USER) {
            $param['select'] = 'id';
            $param['where']['is_guest_order'] = 0;
            $param['where']['customer_id'] = $this->flexi_auth->get_user_custom_data('uacc_id');
        }
        return $this->count_rows($param);
    }

    function fetchOrderDet($order_num = null) {
        $param = [];
        $param['join'][] = [ 'tbl' => $this->db->dbprefix('order_item') . ' as ord_item',
            'cond' => 'ord.id=ord_item.order_id',
            'type' => 'inner',
            'pass_prefix' => true,
        ];
        $param['join'][] = [ 'tbl' => $this->db->dbprefix('order_ship_detail') . ' as ordShip',
            'cond' => 'ord.id=ordShip.order_id',
            'type' => 'inner',
            'pass_prefix' => true,
        ];
        $param['where']['order_num'] = $order_num;
        return $this->get_all($param);
    }

    function reorderFromSavedOrder($params = []) {
        extract($params);
        $policyOverMsg = '';
        $output = [];
        $output['status'] = FALSE;
        $output['message'] = 'Data is insufficent';
        if (is_array($orderDetail) && $orderDetail) {
            $product_sku = '';
            $product_name = '';
            $ordersCartIds = [];
            $user_id = $this->flexi_auth->get_user_custom_data('uacc_id');
            foreach ($orderDetail as $orderIndex => $orderItem) {
                $orderQty = $orderItem['order_item_qty'];
                $orderOptions = json_decode($orderItem['order_item_options'], true);
                if (!$orderQty) {
                    $policyOverMsg .= ' Your selection for product ' . $product_name . ' exceed 
                                        so did not included in cart.<br/>';
                    continue;
                }
                /* Insert to cart */
                $insertCartData = [];
                /* product sku used as id */
                $orderOptions['reorder'] = $orderItem['order_num'];
                $insertCartData['options'] = $orderOptions;
                $insertCartData['product_sku'] = $orderItem['product_ref'];
                $insertCartData['qty'] = $orderQty;
                $insertCartData['id'] = uniqid('ITEM');
                $insertCartData['name'] = $orderItem['order_item_name'];
                $insertCartData['price'] = $orderItem['order_item_price'];
                $this->cart->product_name_rules = '\w+ \'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\\,\-\.\:';
                $cartRowId = $this->cart->insert($insertCartData);
                if ($cartRowId) {
                    /* cartAssociateData for make unique product index for manupulation */
                    $cartAssocDet = $this->session->cartAssocDet;
                    /* If not exist */
                    if (!$cartAssocDet) {
                        $cartAssocDet = [ $orderItem['product_ref'] => $cartRowId];
                    } else {
                        /* If exist */
                        $cartAssocDet[$orderItem['product_ref']] = $cartRowId;
                    }
                    $this->session->set_userdata('cartAssocDet', $cartAssocDet);
                }
                $ordersCartIds[] = $cartRowId;
            }

            $order_num = $orderDetail[0]['order_num'];
            /* cartReorderRef for check about order alredy added  */
            $cartReorderRef = $this->session->cartReorderRef;
            /* If not exist */
            if (!$cartReorderRef) {
                $cartReorderRef = [ $order_num => $ordersCartIds];
            } else {
                /* If exist */
                $cartReorderRef[$order_num] = $ordersCartIds;
            }
            $this->session->set_userdata('cartReorderRef', $cartReorderRef);
            $output['status'] = TRUE;
            $output['message'] = 'Product add to cart successfully';
            if ($policyOverMsg) {
                $output['message'] .= '<br/>' . $policyOverMsg;
            }
        }
        return $output;
    }

}
