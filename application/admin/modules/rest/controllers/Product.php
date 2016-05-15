<?php

/**
* 
*/
class Product extends Admin_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper('xml');
		$this->load->helper("xmldata");
	}

	function tmp_read_proddata($path = ""){
		$path = $this->config->item('XML_PATH')."SAP_PRODUCT.xml";
		$data = read_soap_xml_data($path);
		$data = $data[0]->GetByKeyResponse;		
		$loop = 0;
		foreach($data as $data_index => $data_row){
			/*
				Item Field
			*/			
			if(!is_null($data_row->BOM->BO->Items->row) ){
				//echo "<br/> Item Detail : :";
				foreach((array)$data_row->BOM->BO->Items->row as $key => $val){
					if(!is_object($val)){
						//echo "<br/>: $key : $val";
					}
				}
				//com_e( 1 );
				
				echo "<br/> Items_Prices : : ";
				$price_rows = $data_row->BOM->BO->Items_Prices->row;
				foreach($price_rows as $key => $val){
					foreach((array)$val as $price_index => $price_data){
						if(!is_object($price_data)){
							echo "<br/>: $price_index : $price_data";
						}
					}
				}
				com_e( 1 );
				echo "<br/> ItemWarehouseInfo : : ";	
				foreach((array)$data_row->BOM->BO->ItemWarehouseInfo->row as $key => $val){
					if(!is_object($val)){
						echo "<br/>: $key : $val";
					}
				}				
			}
		}
	}

	function tmp_read_orderdata($path = ""){
		$path = $this->config->item('XML_PATH')."SAP_order.xml";
		$data = read_soap_xml_data($path);
		$data = $data[0];		
		$loop = 0;
		foreach($data as $data_index => $data_row){
			/*
				Item Field
			*/			
			if(!is_null($data_row->BOM->BO->Documents->row) ){
				echo "<br/> Order  : :";
				foreach((array)$data_row->BOM->BO->Documents->row as $key => $val){
					if(!is_object($val)){
						echo "<br/>: $key : $val";
					}
				}

				
				echo "<br/> Order Detail : : ";
				$price_rows = $data_row->BOM->BO->Document_Lines->row;
				foreach($price_rows as $key => $val){
					foreach((array)$val as $price_index => $price_data){
						if(!is_object($price_data)){
							echo "<br/>: $price_index : $price_data";
						}
					}
				}

				echo "<br/> Order TaxExtension Detail : : ";
				$price_rows = $data_row->BOM->BO->TaxExtension->row;
				foreach($price_rows as $key => $val){
					foreach((array)$val as $price_index => $price_data){
						if(!is_object($price_data)){
							echo "<br/>: $price_index : $price_data";
						}
					}
				}


				echo "<br/> AddressExtension : : ";	
				foreach((array)$data_row->BOM->BO->AddressExtension->row as $key => $val){
					if(!is_object($val)){
						echo "<br/>: $key : $val";
					}
				}				
			}
		}
	}

	function tmp_read_custdata($path = ""){
		$path = $this->config->item('XML_PATH')."SAP_customer.xml";
		$path = $this->config->item('XML_PATH')."SAP_CustomerCard.xml";
		$data = read_soap_xml_data($path);
		
		/*
		print_r($data[0]->GetByKeyResponse);		
		*/
		$data = $data[0]->GetByKeyResponse;
		$loop = 0;
		foreach($data as $data_index => $data_row){
			/*	
				Customer Field
			*/			
			if(!is_null($data_row->BOM->BO->BusinessPartners->row) ){
				$loop = $loop + 1;
				echo "<br/> $loop Company Detail : :";
				foreach((array)$data_row->BOM->BO->BusinessPartners->row as $key => $val){
					if(!is_object($val)){
						//echo "<br/>: $key : $val";
					}
				}
					
				echo "<br/> $loop Company Address Detail : :";				
				foreach($data_row->BOM->BO->BPAddresses->row as $key => $val){					
					foreach((array)$val as $addressIndex => $addressDet){						
						if(!is_object($addressDet)){	
							//echo "<br/>: $addressIndex : $addressDet";
						}
					}
				}
								
				echo "<br/> $loop Company ContactEmployees Detail : :";
				foreach($data_row->BOM->BO->ContactEmployees->row as $key => $val){
					foreach((array)$val as $contactIndex => $contactDet){
						if(!is_object($contactDet)){
							//echo "<br/>: $contactIndex : $contactDet";
						}
					}
				}				
			}
		}
		com_e( "its checked" );
	}
	
	function tmp_read_trade_partner($path = ""){
		$path = $this->config->item('XML_PATH')."OCRD-BusinessPartner.xml";		
		$xml = simplexml_load_file($path);
		
		echo "<br/> 1: Company Detail : :";
		$detail_index = 0;
		$detail_stack = [];
		foreach( $xml->BusinessPartners as $stDet ){
			com_e($detail_index, 0);
			foreach( $xml->BusinessPartners[ $detail_index ]->Addresses as $detV){
				foreach( (array)$detV as $plK => $plV ){
					echo "<br/>: $plK : $plV";
				}
			}
			/*
			foreach( (array)$stDet as $detK => $detV ){
				if( !is_object( $detV ) && !is_array( $detV ) ){
					$detail_stack[ $detK ] = $detV;
					echo "<br/>: $detK : $detV";
				}
			}
			*/ 
			$detail_index++;
		}
		com_e( $detail_stack, 1);
		
		echo "<br/> 2: Company Address Detail : :";
		$address_index = 0;
		$address_stack = [];
		foreach( $xml->BusinessPartners->Addresses as $stDet ){
			foreach( (array)$stDet as $detK => $detV ){
				if(!is_object( $detV )){					
					$address_stack[ $address_index ][ $detK ] = trim($detV);
					//echo "<br/>:".trim($detK).":".trim($detV);
				}
			}
			$address_index++;
		}
		//com_e( $address_stack, 1);
		
		echo "<br/> 3: Company ContactEmployees Detail : :";
		$contact_index = 0;
		$contact_stack = [];
		foreach( $xml->BusinessPartners->ContactEmployees as $stDet ){
			foreach( (array)$stDet as $detK => $detV ){
				if(!is_object( $detV )){
					$contact_stack[ $contact_index ][ $detK ] = $detV;
					//echo "<br/>: $detK : $detV";
				}
			}
			$contact_index++;
		}
		//com_e( $contact_stack, 0);
		com_e( "its checked" );
	}
	
	function tmp_read_trade_partner_group($path = ""){
		$path = $this->config->item('XML_PATH')."OCRG-BusinessPartnerGroup.xml";
		$xmldata = file_get_contents($path);
		$xml = new SimpleXMLElement($xmldata);
		com_e( $xml );
	}
	
	function tmp_read_prod_item($path = ""){
		$path = $this->config->item('XML_PATH')."OITM-Item.xml";
		$xml = simplexml_load_file($path);
		$index = 0; 
		$product = [];
		foreach( $xml->Items as $stDet ){
			$item_sku = $stDet->ItemCode;			
			foreach( $xml->Items[ $index ]->PriceList as $detV){
				foreach( (array)$detV as $plK => $plV ){
					echo "<br/>: $plK : $plV";
				}
			}
			$index++;
		}
		com_e( 1 );
		echo "<br/> 1: Product Item : :";		
		$detail_stack = [];
		foreach( $xml->Items as $stDet ){
			foreach( (array)$stDet as $detK => $detV ){
				if( !is_object( $detV ) && !is_array( $detV ) ){
					$detail_stack[ $detK ] = $detV;
					//echo "<br/>: $detK : $detV";
				}
			}			
		}
		//com_e($detail_stack, 1);
		
		echo "<br/> 2: PriceList Detail : :";
		$plist_index = 0;
		$plist_stack = [];
		foreach( $xml->Items->PriceList as $stDet ){
			foreach( (array)$stDet as $detK => $detV ){
				if(!is_object( $detV )){
					$plist_stack[ $plist_index ][ $detK ] = trim($detV);
					echo "<br/>:".trim($detK).":".trim($detV);
				}
			}
			$plist_index++;
		}
		com_e( $plist_stack, 1);
				
		com_e( $xml );
	}
	
	function tmp_read_prod_item_group($path = ""){
		$path = $this->config->item('XML_PATH')."OITB-ItemGroup.xml";
		$xmldata = file_get_contents($path);
		$xml = new SimpleXMLElement($xmldata);
		//com_e( $xml );
		$product_group_index = 0; 
		$product_group = [];
		foreach( $xml->ItemGroups as $stDet ){
			//com_e( $stDet->ItmsGrpCod, 0);
			//com_e( $stDet->ItmsGrpNam, 0);
		}
		com_e( "its checked" );
		
	}
	
	function tmp_read_prod_item_price_list($path = ""){
		$path = $this->config->item('XML_PATH')."OPLN-PriceList.xml";
		$xmldata = file_get_contents($path);
		$xml = new SimpleXMLElement($xmldata);
		com_e( $xml );
	}

	function tmp_read_prod_sale_order($path = ""){
		$path = $this->config->item('XML_PATH')."ORDR-SalesOrder.xml";
		$xmldata = file_get_contents($path);
		$xml = new SimpleXMLElement($xmldata);
		com_e( $xml );
	}
	
	function tmp_read_prod_special_price($path = ""){
		$path = $this->config->item('XML_PATH')."OSPP-SpecialPrice.xml";
		$xmldata = file_get_contents($path);
		$xml = new SimpleXMLElement($xmldata);
		com_e( $xml );
	}
}
/*
	echo "<pre>";
	
	//->GetByKeyResponse
		print_r($data_row);
		echo $loop;
		$loop = $loop + 1;
	continue;
*/
