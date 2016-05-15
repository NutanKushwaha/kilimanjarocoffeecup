<?php
	//require_once('include/menu.php');
	require_once('include/fedex-common.php');
	
/**
* 
*/
class FeShipping
{
	
	public function __construct()
	{
		
		# code...
	}

		function addShipper()
		{
			$shipper = array
			(
				'Contact' => array
				(
					'PersonName' => 'Jalebi Bai',
					'CompanyName' => 'ELLT',
					'PhoneNumber' => '6466130148'
				),
				'Address' => getProperty('address1')
			);
			return $shipper;
		} // end of function addShipper
		
		function addRecipient($input)
		{
			$recipient = array
			(
				'Contact' => array
				(
					'PersonName' => $input['PersonName'],
					//'CompanyName' => $input['CompanyName'],
					'PhoneNumber' => $input['PhoneNumber']
				),
				'Address' => array(
					'StreetLines' => array($input['StreetLines']),
					'City' => $input['City'],
					'StateOrProvinceCode' => $input['StateOrProvinceCode'],
					'PostalCode' => $input['PostalCode'],
					'CountryCode' => $input['CountryCode'],
					'Residential' => 1
				)
			);
			return $recipient;	                                    
		} // end of function addRecipient
		
		function addShippingChargesPayment($input)
		{
			$shippingChargesPayment = array
			(
				'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
				'Payor' => array
				(
					'ResponsibleParty' => array
					(
						'AccountNumber' => getProperty('billaccount'),
						'CountryCode' => 'US'
					)
				)
			);
			return $shippingChargesPayment;
		} // end of function addShippingChargesPayment
		
		function addSmartPostDetail($input)
		{
			$smartPostDetail = array
			( 
				'Indicia' => 'PARCEL_SELECT',
				'AncillaryEndorsement' => 'CARRIER_LEAVE_IF_NO_RESPONSE',
				'SpecialServices' => 'USPS_DELIVERY_CONFIRMATION',
				'HubId' => getProperty('hubid'),
				'CustomerManifestId' => 'XXX'
			);
			return $smartPostDetail;
		} // end of function addSmartPostDetail
		
		function addPackageLineItem($input)
		{
			$packageLineItem = array
			( 
				'SequenceNumber' => 1,
				'GroupPackageCount' => 1,
				'Weight' => array
				(
					'Value' => $input['Weight'],
					'Units' => 'LB'
				),
				'Dimensions' => array
				(
					'Length' => $input['Length'],
					'Width' => $input['Width'],
					'Height' => $input['Height'],
					'Units' => 'IN'
				)
			);
			return $packageLineItem;
		} // end of function addPackageLineItem

		
	public function getAllRates($data){
		
			$input = array();

			$input['PersonName'] = $data['uadd_recipient'];
			$input['PhoneNumber'] = $data['uadd_phone'];
			$input['StreetLines'] = $data['uadd_address_01'];
			$input['City'] = $data['uadd_city'];
			$input['StateOrProvinceCode'] = $data['uadd_county'];
			$input['PostalCode'] = $data['uadd_post_code'];
			$input['CountryCode'] = 'US';
			$input['Weight'] = $data['Weight'];
			$input['Length'] = $data['Length'];
			$input['Height'] = $data['Height'];
			$input['Width'] = $data['Width'];


$path_to_wsdl = realpath(dirname(__FILE__) . '/wsdl/RateService_v18.wsdl');


		$newline = "<br />";
		//The WSDL is not included with the sample code.
		//Please include and reference in $path_to_wsdl variable.
		//$path_to_wsdl = "wsdl/RateService_v18.wsdl";

		ini_set("soap.wsdl_cache_enabled", "0");
		
		 
		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

		//print_r($this->addShipper()); exit;

		$request['WebAuthenticationDetail'] = array
		(
			'ParentCredential' => array
			(
				'Key' => getProperty('parentkey'),
				'Password' => getProperty('parentpassword')
			),
			'UserCredential' =>array
			(
				'Key' => getProperty('key'), 
				'Password' => getProperty('password')
			)
		); 
		$request['ClientDetail'] = array
		(
			'AccountNumber' => getProperty('shipaccount'), 
			'MeterNumber' => getProperty('meter')
		);
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** SmartPost Rate Request using PHP ***');
		$request['Version'] = array
		(
			'ServiceId' => 'crs', 
			'Major' => '18', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		//$request['RequestedShipment']['ServiceType'] = $_POST['ServiceType']; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
		$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
		$request['RequestedShipment']['Shipper'] = $this->addShipper();
		$request['RequestedShipment']['Recipient'] = $this->addRecipient($input);
		$request['RequestedShipment']['ShippingChargesPayment'] = $this->addShippingChargesPayment($input);														 
		$request['RequestedShipment']['SmartPostDetail'] = $this->addSmartPostDetail($input);
		$request['RequestedShipment']['PackageCount'] = '1';
		$request['RequestedShipment']['RequestedPackageLineItems'] = $this->addPackageLineItem($input);

		try {
			if(setEndpoint('changeEndpoint'))
			{
				$newLocation = $client->__setLocation(setEndpoint('endpoint'));
			}
			
			$response = $client -> getRates($request);

			
					
			if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR' && $response -> HighestSeverity != 'WARNING')
			{
				//$rateReply = $response -> RateReplyDetails;
					$all_rateReply = $response -> RateReplyDetails;
					return $all_rateReply;

				
			}
			else{

				return json_decode(json_encode($response), True);
			} 
			writeToLog($client);    // Write to log file   
		} 
		catch (SoapFault $exception)
		{
		   printFault($exception, $client);        
		}
	}
}