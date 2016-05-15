<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payments_pro extends Front_Controller 
{
	function __construct()
	{
		parent::__construct();

		// Load helpers
		$this->load->helper('url');
		$this->load->model('Cartmodel');
		// Load PayPal library
		$this->config->load('paypal');
		
		$config = array(
			'Sandbox' => $this->config->item('Sandbox'), 			// Sandbox / testing mode option.
			'APIUsername' => $this->config->item('APIUsername'), 	// PayPal API username of the API caller
			'APIPassword' => $this->config->item('APIPassword'), 	// PayPal API password of the API caller
			'APISignature' => $this->config->item('APISignature'), 	// PayPal API signature of the API caller
			'APISubject' => '', 									// PayPal API subject (email address of 3rd party user that has granted API permission for your app)
			'APIVersion' => $this->config->item('APIVersion')		// API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
		);
		
		// Show Errors
		if($config['Sandbox'])
		{
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
		
		$this->load->library('Paypal_pro', $config);	
	}
	
	
	function index()
	{
		$this->load->view('');
	}

    function Do_checkout(){
        
        $orderDetailAfterExpress = $this->paypal_pro->GetExpressCheckoutDetails($_GET['token']);

// echo "<pre>";
// print_r($orderDetailAfterExpress['CUSTOM']); exit;

        $token = $_GET['token'];
        $payer = $_GET['PayerID'];

        $this->load->model('customer/Ordermodel');
        $this->load->model('customer/Customermodel');
        $orderDetail = $this->Ordermodel->getGuestOrderDetail($orderDetailAfterExpress['CUSTOM']);
        $orderItems = $this->Ordermodel->listOrderItems($orderDetail['id']);

        $customer_details = $this->Customermodel->getGuestUserInfo($orderDetail['customer_id']);

        $whole_total=$this->cart->total();

        if ($this->session->userdata('shipping_charges') != null) {

            $whole_total =  $whole_total + $this->session->userdata('shipping_charges');
        }


        $this_script = createUrl('paypal/Payments_pro/');
        $purchase_contents = $orderItems;

// echo "<pre>";
// print_r($purchase_contents); exit;


        if (count($orderItems) > 1) 
            $multiple_items = TRUE;
        else
            $multiple_items = FALSE;

        //var_dump($purchase_contents);die;

        foreach ($purchase_contents as $item) {
            $this->purchase = array(
                'qty'            =>    $item['order_item_qty'],
                'price'            =>    round($item['order_item_price'],2),
                'name'            =>    $item['order_item_name']
                );
        }

        $DECPFields = array(
                            'token' => $token,                                 // A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
                            //'maxamt'                 => '1000',             // The expected maximum total amount the order will be, including S&H and sales tax.
                            'returnurl'             => $this_script . 'success',        // Required.  URL to which the customer will be returned after returning from PayPal.  2048 char max.
                            'cancelurl'             => '',        // Required.  URL to which the customer will be returned if they cancel payment on PayPal's site.
                            'callback' => '',                             // URL to which the callback request from PayPal is sent.  Must start with https:// for production.
                            'callbacktimeout' => '',                     // An override for you to request more or less time to be able to process the callback request and response.  Acceptable range for override is 1-6 seconds.  If you specify greater than 6 PayPal will use default value of 3 seconds.
                            'callbackversion' => '',                     // The version of the Instant Update API you're using.  The default is the current version.                            
                            'reqconfirmshipping' => '',                 // The value 1 indicates that you require that the customer's shipping address is Confirmed with PayPal.  This overrides anything in the account profile.  Possible values are 1 or 0.
                            'noshipping' => '',                         // The value 1 indiciates that on the PayPal pages, no shipping address fields should be displayed.  Maybe 1 or 0.
                            'allownote' => '',                             // The value 1 indiciates that the customer may enter a note to the merchant on the PayPal page during checkout.  The note is returned in the GetExpresscheckoutDetails response and the DoExpressCheckoutPayment response.  Must be 1 or 0.
                            'addroverride' => '',                         // The value 1 indiciates that the PayPal pages should display the shipping address set by you in the SetExpressCheckout request, not the shipping address on file with PayPal.  This does not allow the customer to edit the address here.  Must be 1 or 0.
                            'localecode' => '',                         // Locale of pages displayed by PayPal during checkout.  Should be a 2 character country code.  You can retrive the country code by passing the country name into the class' GetCountryCode() function.
                            'pagestyle' => '',                             // Sets the Custom Payment Page Style for payment pages associated with this button/link.  
                            'hdrimg' => '',                             // URL for the image displayed as the header during checkout.  Max size of 750x90.  Should be stored on an https:// server or you'll get a warning message in the browser.
                            'hdrbordercolor' => '',                     // Sets the border color around the header of the payment page.  The border is a 2-pixel permiter around the header space.  Default is black.  
                            'hdrbackcolor' => '',                         // Sets the background color for the header of the payment page.  Default is white.  
                            'payflowcolor' => '',                         // Sets the background color for the payment page.  Default is white.
                            'skipdetails' => '',                         // This is a custom field not included in the PayPal documentation.  It's used to specify whether you want to skip the GetExpressCheckoutDetails part of checkout or not.  See PayPal docs for more info.
                            'email' => '',                                 // Email address of the buyer as entered during checkout.  PayPal uses this value to pre-fill the PayPal sign-in page.  127 char max.
                            'solutiontype' => '',                         // Type of checkout flow.  Must be Sole (express checkout for auctions) or Mark (normal express checkout)
                            'landingpage' => '',                         // Type of PayPal page to display.  Can be Billing or Login.  If billing it shows a full credit card form.  If Login it just shows the login screen.
                            'channeltype' => '',                         // Type of channel.  Must be Merchant (non-auction seller) or eBayItem (eBay auction)
                            'giropaysuccessurl' => '',                     // The URL on the merchant site to redirect to after a successful giropay payment.  Only use this field if you are using giropay or bank transfer payment methods in Germany.
                            'giropaycancelurl' => '',                     // The URL on the merchant site to redirect to after a canceled giropay payment.  Only use this field if you are using giropay or bank transfer methods in Germany.
                            'banktxnpendingurl' => '',                  // The URL on the merchant site to transfer to after a bank transfter payment.  Use this field only if you are using giropay or bank transfer methods in Germany.
                            'brandname' => '',                             // A label that overrides the business name in the PayPal account on the PayPal hosted checkout pages.  127 char max.
                            'customerservicenumber' => '',                 // Merchant Customer Service number displayed on the PayPal Review page. 16 char max.
                            'giftmessageenable' => '',                     // Enable gift message widget on the PayPal Review page. Allowable values are 0 and 1
                            'giftreceiptenable' => '',                     // Enable gift receipt widget on the PayPal Review page. Allowable values are 0 and 1
                            'giftwrapenable' => '',                     // Enable gift wrap widget on the PayPal Review page.  Allowable values are 0 and 1.
                            'giftwrapname' => '',                         // Label for the gift wrap option such as "Box with ribbon".  25 char max.
                            'giftwrapamount' => '',                     // Amount charged for gift-wrap service.
                            'buyeremailoptionenable' => '',             // Enable buyer email opt-in on the PayPal Review page. Allowable values are 0 and 1
                            'surveyquestion' => '',                     // Text for the survey question on the PayPal Review page. If the survey question is present, at least 2 survey answer options need to be present.  50 char max.
                            'surveyenable' => '',                         // Enable survey functionality. Allowable values are 0 and 1
                            'totaltype' => '',                             // Enables display of "estimated total" instead of "total" in the cart review area.  Values are:  Total, EstimatedTotal
                            'notetobuyer' => '',                         // Displays a note to buyers in the cart review area below the total amount.  Use the note to tell buyers about items in the cart, such as your return policy or that the total excludes shipping and handling.  127 char max.                            
                            'payerid' => $payer,                             // The unique identifier provided by eBay for this buyer. The value may or may not be the same as the username. In the case of eBay, it is different. 255 char max.
                            'buyerusername' => '',                         // The user name of the user at the marketplaces site.
                            'buyerregistrationdate' => '',              // Date when the user registered with the marketplace.
                            'allowpushfunding' => ''                    // Whether the merchant can accept push funding.  0 = Merchant can accept push funding : 1 = Merchant cannot accept push funding.            
                        );
        
        // Basic array of survey choices.  Nothing but the values should go in here.  
        $SurveyChoices = array('Choice 1', 'Choice2', 'Choice3', 'etc');
        
        // You can now utlize parallel payments (split payments) within Express Checkout.
        // Here we'll gather all the payment data for each payment included in this checkout 
        // and pass them into a $Payments array.  
        
        // Keep in mind that each payment will ahve its own set of OrderItems
        // so don't get confused along the way.
        $Payments = array();
        $Payment = array(
                        'amt'                     =>    round(($this->cart->total() + $this->session->userdata('shipping_charges')),2),             // Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
                        'currencycode'             =>    'USD',    // A three-character currency code.  Default is USD.
                        'itemamt'                 =>    round($this->cart->total(),2),     // Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.  
                        'shippingamt' => round($this->session->userdata('shipping_charges'),2),    // Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
                        'shipdiscamt' => '',                     // Shipping discount for this order, specified as a negative number.
                        'insuranceoptionoffered' => '',         // If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
                        'handlingamt' => '',                     // Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
                        'taxamt' => '',                         // Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order. 
                        'desc' => '',                             // Description of items on the order.  127 char max.
                        'custom' => $orderDetailAfterExpress['CUSTOM'],                         // Free-form field for your own use.  256 char max.
                        'invnum' => '',                         // Your own invoice or tracking number.  127 char max.
                        'notifyurl' => '',                         // URL for receiving Instant Payment Notifications
                        'shiptoname' => '',                     // Required if shipping is included.  Person's name associated with this address.  32 char max.
                        'shiptostreet' => '',                     // Required if shipping is included.  First street address.  100 char max.
                        'shiptostreet2' => '',                     // Second street address.  100 char max.
                        'shiptocity' => '',                     // Required if shipping is included.  Name of city.  40 char max.
                        'shiptostate' => '',                     // Required if shipping is included.  Name of state or province.  40 char max.
                        'shiptozip' => '',                         // Required if shipping is included.  Postal code of shipping address.  20 char max.
                        'shiptocountrycode' => '',                 // Required if shipping is included.  Country code of shipping address.  2 char max.
                        'shiptophonenum' => '',                  // Phone number for shipping address.  20 char max.
                        'notetext' => '',                         // Note to the merchant.  255 char max.  
                        'allowedpaymentmethod' => '',             // The payment method type.  Specify the value InstantPaymentOnly.
                        'allowpushfunding' => '',                 // Whether the merchant can accept push funding:  0 - Merchant can accept push funding.  1 - Merchant cannot accept push funding.  This will override the setting in the merchant's PayPal account.
                        'paymentaction' => 'Sale',                     // How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order. 
                        'paymentrequestid' => '',                  // A unique identifier of the specific payment request, which is required for parallel payments. 
                        'sellerid' => '',                         // The unique non-changing identifier for the seller at the marketplace site.  This ID is not displayed.
                        'sellerusername' => '',                 // The current name of the seller or business at the marketplace site.  This name may be shown to the buyer.
                        'sellerpaypalaccountid' => ''            // A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
                        );
        
        // For order items you populate a nested array with multiple $Item arrays.  
        // Normally you'll be looping through cart items to populate the $Item array
        // Then push it into the $OrderItems array at the end of each loop for an entire 
        // collection of all items in $OrderItems.
        
        //if ($multiple_items == TRUE) :        
        $PaymentOrderItems = array();
        foreach ($purchase_contents as $item) { 
            
        $Item = array(
                    'name'                 => $item['order_item_name'],         // Item name. 127 char max.
                    'amt'                 => round($item['order_item_price'],2),         // Cost of item.
                    'qty'                 => $item['order_item_qty'],         // Item qty on order.  Any positive integer.
                    
                    );
        array_push($PaymentOrderItems, $Item);
        }

        // Now we've got our OrderItems for this individual payment, 
        // so we'll load them into the $Payment array
        $Payment['order_items'] = $PaymentOrderItems;
        
        // Now we add the current $Payment array into the $Payments array collection
        array_push($Payments, $Payment);


        $PayPalRequestData = array(
                        'DECPFields' => $DECPFields, 
                        'Payments' => $Payments
                    );

        $PayPalResult = $this->paypal_pro->DoExpressCheckoutPayment($PayPalRequestData);


            // echo "<pre>";
            // print_r($PayPalResult); exit;
        if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
        {
            $errors = array('Errors'=>$PayPalResult['ERRORS']);
            //$this->load->view('paypal/paypal_error',$errors);
            redirect( 'paypal/Payments_pro/cancel');
        }
        else
        {
            $order_number =  $orderDetailAfterExpress['CUSTOM'];
            $PayPalResult['order_number'] = $order_number;
            $this->session->set_userdata(array('paypal_status' => $PayPalResult));
            // Successful call.  Load view or whatever you need to do here.    
            redirect( 'paypal/Payments_pro/success');
        }        
    }

	function Set_express_checkout($order_number)
    {

    	$this->load->model('customer/Ordermodel');
        $this->load->model('customer/Customermodel');
        $orderDetail = $this->Ordermodel->getGuestOrderDetail($order_number);
        $orderItems = $this->Ordermodel->listOrderItems($orderDetail['id']);

        $customer_details = $this->Customermodel->getGuestUserInfo($orderDetail['customer_id']);

        $whole_total=$this->cart->total();

        if ($this->session->userdata('shipping_charges') != null) {

            $whole_total =  $whole_total + $this->session->userdata('shipping_charges');
        }


    	$this_script = createUrl('paypal/Payments_pro/');
        $purchase_contents = $orderItems;

 

        if (count($orderItems) > 1) 
            $multiple_items = TRUE;
        else
            $multiple_items = FALSE;

        //var_dump($purchase_contents);die;

        foreach ($purchase_contents as $item) {
            $this->purchase = array(
                'qty'            =>    $item['order_item_qty'],
                'price'            =>    round($item['order_item_price'],2),
                'name'            =>    $item['order_item_name']
                );
        }

        $SECFields = array(
                            'token' => '',                                 // A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
                            //'custom'                => $order_number,             // The expected maximum total amount the order will be, including S&H and sales tax.
                            'returnurl'             => $this_script . 'Do_checkout',        // Required.  URL to which the customer will be returned after returning from PayPal.  2048 char max.
                            'cancelurl'             => $this_script . 'cancel',        // Required.  URL to which the customer will be returned if they cancel payment on PayPal's site.
                            'callback' => '',                             // URL to which the callback request from PayPal is sent.  Must start with https:// for production.
                            'callbacktimeout' => '',                     // An override for you to request more or less time to be able to process the callback request and response.  Acceptable range for override is 1-6 seconds.  If you specify greater than 6 PayPal will use default value of 3 seconds.
                            'callbackversion' => '',                     // The version of the Instant Update API you're using.  The default is the current version.                            
                            'reqconfirmshipping' => '',                 // The value 1 indicates that you require that the customer's shipping address is Confirmed with PayPal.  This overrides anything in the account profile.  Possible values are 1 or 0.
                            'noshipping' => '',                         // The value 1 indiciates that on the PayPal pages, no shipping address fields should be displayed.  Maybe 1 or 0.
                            'allownote' => '',                             // The value 1 indiciates that the customer may enter a note to the merchant on the PayPal page during checkout.  The note is returned in the GetExpresscheckoutDetails response and the DoExpressCheckoutPayment response.  Must be 1 or 0.
                            'addroverride' => '',                         // The value 1 indiciates that the PayPal pages should display the shipping address set by you in the SetExpressCheckout request, not the shipping address on file with PayPal.  This does not allow the customer to edit the address here.  Must be 1 or 0.
                            'localecode' => '',                         // Locale of pages displayed by PayPal during checkout.  Should be a 2 character country code.  You can retrive the country code by passing the country name into the class' GetCountryCode() function.
                            'pagestyle' => '',                             // Sets the Custom Payment Page Style for payment pages associated with this button/link.  
                            'hdrimg' => '',                             // URL for the image displayed as the header during checkout.  Max size of 750x90.  Should be stored on an https:// server or you'll get a warning message in the browser.
                            'hdrbordercolor' => '',                     // Sets the border color around the header of the payment page.  The border is a 2-pixel permiter around the header space.  Default is black.  
                            'hdrbackcolor' => '',                         // Sets the background color for the header of the payment page.  Default is white.  
                            'payflowcolor' => '',                         // Sets the background color for the payment page.  Default is white.
                            'skipdetails' => '',                         // This is a custom field not included in the PayPal documentation.  It's used to specify whether you want to skip the GetExpressCheckoutDetails part of checkout or not.  See PayPal docs for more info.
                            'email' => '',                                 // Email address of the buyer as entered during checkout.  PayPal uses this value to pre-fill the PayPal sign-in page.  127 char max.
                            'solutiontype' => '',                         // Type of checkout flow.  Must be Sole (express checkout for auctions) or Mark (normal express checkout)
                            'landingpage' => '',                         // Type of PayPal page to display.  Can be Billing or Login.  If billing it shows a full credit card form.  If Login it just shows the login screen.
                            'channeltype' => '',                         // Type of channel.  Must be Merchant (non-auction seller) or eBayItem (eBay auction)
                            'giropaysuccessurl' => '',                     // The URL on the merchant site to redirect to after a successful giropay payment.  Only use this field if you are using giropay or bank transfer payment methods in Germany.
                            'giropaycancelurl' => '',                     // The URL on the merchant site to redirect to after a canceled giropay payment.  Only use this field if you are using giropay or bank transfer methods in Germany.
                            'banktxnpendingurl' => '',                  // The URL on the merchant site to transfer to after a bank transfter payment.  Use this field only if you are using giropay or bank transfer methods in Germany.
                            'brandname' => '',                             // A label that overrides the business name in the PayPal account on the PayPal hosted checkout pages.  127 char max.
                            'customerservicenumber' => '',                 // Merchant Customer Service number displayed on the PayPal Review page. 16 char max.
                            'giftmessageenable' => '',                     // Enable gift message widget on the PayPal Review page. Allowable values are 0 and 1
                            'giftreceiptenable' => '',                     // Enable gift receipt widget on the PayPal Review page. Allowable values are 0 and 1
                            'giftwrapenable' => '',                     // Enable gift wrap widget on the PayPal Review page.  Allowable values are 0 and 1.
                            'giftwrapname' => '',                         // Label for the gift wrap option such as "Box with ribbon".  25 char max.
                            'giftwrapamount' => '',                     // Amount charged for gift-wrap service.
                            'buyeremailoptionenable' => '',             // Enable buyer email opt-in on the PayPal Review page. Allowable values are 0 and 1
                            'surveyquestion' => '',                     // Text for the survey question on the PayPal Review page. If the survey question is present, at least 2 survey answer options need to be present.  50 char max.
                            'surveyenable' => '',                         // Enable survey functionality. Allowable values are 0 and 1
                            'totaltype' => '',                             // Enables display of "estimated total" instead of "total" in the cart review area.  Values are:  Total, EstimatedTotal
                            'notetobuyer' => '',                         // Displays a note to buyers in the cart review area below the total amount.  Use the note to tell buyers about items in the cart, such as your return policy or that the total excludes shipping and handling.  127 char max.                            
                            'buyerid' => '',                             // The unique identifier provided by eBay for this buyer. The value may or may not be the same as the username. In the case of eBay, it is different. 255 char max.
                            'buyerusername' => '',                         // The user name of the user at the marketplaces site.
                            'buyerregistrationdate' => '',              // Date when the user registered with the marketplace.
                            'allowpushfunding' => ''                    // Whether the merchant can accept push funding.  0 = Merchant can accept push funding : 1 = Merchant cannot accept push funding.            
                        );
        
        // Basic array of survey choices.  Nothing but the values should go in here.  
        $SurveyChoices = array('Choice 1', 'Choice2', 'Choice3', 'etc');
        
        // You can now utlize parallel payments (split payments) within Express Checkout.
        // Here we'll gather all the payment data for each payment included in this checkout 
        // and pass them into a $Payments array.  
        
        // Keep in mind that each payment will ahve its own set of OrderItems
        // so don't get confused along the way.
        

        $Payments = array();
        $Payment = array(
                        'amt'                     =>    round(($this->cart->total() + $this->session->userdata('shipping_charges')),2),             // Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
                        'currencycode'             =>    'USD',    // A three-character currency code.  Default is USD.
                        'itemamt'                 =>    round($this->cart->total(),2),     // Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.  
                        'shippingamt' => round($this->session->userdata('shipping_charges'),2),    // Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
                        'shipdiscamt' => '',                     // Shipping discount for this order, specified as a negative number.
                        'insuranceoptionoffered' => '',         // If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
                        'handlingamt' => '',                     // Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
                        'taxamt' => '',                         // Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order. 
                        'desc' => '',                             // Description of items on the order.  127 char max.
                        'custom' => $order_number,                         // Free-form field for your own use.  256 char max.
                        'invnum' => '',                         // Your own invoice or tracking number.  127 char max.
                        'notifyurl' => '',                         // URL for receiving Instant Payment Notifications
                        'shiptoname' => '',                     // Required if shipping is included.  Person's name associated with this address.  32 char max.
                        'shiptostreet' => '',                     // Required if shipping is included.  First street address.  100 char max.
                        'shiptostreet2' => '',                     // Second street address.  100 char max.
                        'shiptocity' => '',                     // Required if shipping is included.  Name of city.  40 char max.
                        'shiptostate' => '',                     // Required if shipping is included.  Name of state or province.  40 char max.
                        'shiptozip' => '',                         // Required if shipping is included.  Postal code of shipping address.  20 char max.
                        'shiptocountrycode' => '',                 // Required if shipping is included.  Country code of shipping address.  2 char max.
                        'shiptophonenum' => '',                  // Phone number for shipping address.  20 char max.
                        'notetext' => '',                         // Note to the merchant.  255 char max.  
                        'allowedpaymentmethod' => '',             // The payment method type.  Specify the value InstantPaymentOnly.
                        'allowpushfunding' => '',                 // Whether the merchant can accept push funding:  0 - Merchant can accept push funding.  1 - Merchant cannot accept push funding.  This will override the setting in the merchant's PayPal account.
                        'paymentaction' => '',                     // How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order. 
                        'paymentrequestid' => '',                  // A unique identifier of the specific payment request, which is required for parallel payments. 
                        'sellerid' => '',                         // The unique non-changing identifier for the seller at the marketplace site.  This ID is not displayed.
                        'sellerusername' => '',                 // The current name of the seller or business at the marketplace site.  This name may be shown to the buyer.
                        'sellerpaypalaccountid' => ''            // A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
                        );
        
        // For order items you populate a nested array with multiple $Item arrays.  
        // Normally you'll be looping through cart items to populate the $Item array
        // Then push it into the $OrderItems array at the end of each loop for an entire 
        // collection of all items in $OrderItems.
        
        //if ($multiple_items == TRUE) :        
        $PaymentOrderItems = array();
        foreach ($purchase_contents as $item) { 
            
        $Item = array(
                    'name'                 => $item['order_item_name'],         // Item name. 127 char max.
                    'amt'                 => round($item['order_item_price'],2),         // Cost of item.
                    'qty'                 => $item['order_item_qty'],         // Item qty on order.  Any positive integer.
                    
                    );
        array_push($PaymentOrderItems, $Item);
        }

        // Now we've got our OrderItems for this individual payment, 
        // so we'll load them into the $Payment array
        $Payment['order_items'] = $PaymentOrderItems;
        
        // Now we add the current $Payment array into the $Payments array collection
        array_push($Payments, $Payment);

        

        $PayPalRequestData = array(
                        'SECFields' => $SECFields, 
                        'Payments' => $Payments
                    );

        $PayPalResult = $this->paypal_pro->SetExpressCheckout($PayPalRequestData);
 //echo "<pre>";
 //print_r($PayPalResult); exit;

        if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
        {
            $errors = array('Errors'=>$PayPalResult['ERRORS']);
            $this->load->view('paypal/paypal_error',$errors);
        }
        else
        {
            // Successful call.  Load view or whatever you need to do here.    
            redirect( 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $PayPalResult['TOKEN'] );
           //  redirect( 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $PayPalResult['TOKEN'] );
        }
    }



    function ipn() {
        $p = new paypal_class;

        if ($p->validate_ipn()) {
            $re = "";
            foreach ($_REQUEST as $r => $s) {
                $re .= "\n$r: $s";
            }
            $this->Cartmodel->emptyCart();
            $this->db->where('id', $_REQUEST['custom']);
            $this->db->update('order', array('is_paid' => '1', 'transaction_no' => $_REQUEST['verify_sign']));
            $this->session->unset_userdata('checkoutRole');
            $this->session->unset_userdata('user_register');
            $this->session->unset_userdata('guest_user_id');
            $this->session->unset_userdata('CheckoutAddress');
            $this->session->unset_userdata('guestUserInfo');
            $this->session->unset_userdata('last_order');
        }
    }


    function success() {
        $response = $this->session->userdata('paypal_status');
        
        $pdf_file_name ="";
        $tracking_no = "";
        $this->load->model('customer/Ordermodel');
        //$this->load->model('customer/Ordermodel');
        if($this->session->userdata('pdf_res')){
            $pdf_res = $this->session->userdata('pdf_res');
            $tracking_no   = $pdf_res['token'];
            $pdf_file_name = $pdf_res['pdffile'];
        }


    $this->db->where('id', $response['order_number']);
        $this->db->update('order', array(
            'is_paid' => '1', 
            'transaction_no' => $response['PAYMENTS'][0]['TRANSACTIONID'], 
            'status' => $response['PAYMENTS'][0]['PAYMENTSTATUS'],
            'shipping_name' => $this->session->userdata('shipping_name'),
            'shipping_charges' => $this->session->userdata('shipping_charges'),
            'track_num' => $tracking_no,
            'shipping_pdf' => $pdf_file_name
            ));
        $orderDetail = $this->Ordermodel->getGuestOrderDetail($response['order_number']);
        $this->session->unset_userdata('checkoutRole');
        $this->session->unset_userdata('user_register');
        $this->session->unset_userdata('guest_user_id');
        $this->session->unset_userdata('CheckoutAddress');
        $this->session->unset_userdata('guestUserInfo');
        $this->session->unset_userdata('last_order');
        $this->session->unset_userdata('paypal_status');
        $this->session->unset_userdata('shipping_name');
        $this->session->unset_userdata('shipping_charges');
        $this->session->unset_userdata('pdf_res');
//        echo base_url('paypal/order_placed/'.$orderDetail['order_num']);
        //   file_put_contents( 'ipn_errors.log', $body);
        $this->Cartmodel->emptyCart();
        redirect(base_url('paypal/Payments_pro/order_placed/' . $orderDetail['order_num']));
    }

    function order_placed($onum) {
        $this->load->model('customer/Ordermodel');
        $orderDetail = $this->Ordermodel->getOrderDetail($onum);
        $orderItems = $this->Ordermodel->listOrderItems($orderDetail['id']);
        $inner = array();
        $shell = array();
        $inner['order'] = $orderDetail;
        $inner['order_items'] = $orderItems;
        $shell['contents'] = $this->load->view("order-success", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }

    function cancel() {
        $inner = array();
        $shell = array();
        $this->session->unset_userdata('CheckoutAddress');
        $this->session->unset_userdata('shipping_name');
        $this->session->unset_userdata('shipping_charges');
        $this->session->unset_userdata('pdf_res');
//      
        $shell['contents'] = $this->load->view("order-cancel", $inner, true);
        $this->load->view("themes/" . THEME . "/templates/subpage", $shell);
    }
		
}

/* End of file Payments_pro.php */
/* Location: ./system/application/controllers/paypal/samples/Payments_pro.php */