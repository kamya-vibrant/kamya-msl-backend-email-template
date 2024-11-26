<?php

require_once('config/config.php');
require_once("integrations/fatzebra/vendor/autoload.php");

class FatzebraController
{

	/**

	$data parameter should be array that has the below columns:
	
	'amount'
	'reference',
	'name'
	'card_number'
	'card_expiry_month'
	'card_expiry_year'
	'card_cvv'

	**/

	public function subitPayment($data){
		
		try {
		    $gateway = new FatZebra\Gateway(FATZEBRA_UN, FATZEBRA_TK, FATZEBRA_TESTMODE);

		    $response = $gateway->purchase($data['amount'], $data['reference'], $data['name'], $data['card_number'], $data['card_expiry_month'] . "/" . $data['card_expiry_year'], $data['card_cvv']);
			return array('code'=>200, 'data'=> json_encode($response));

		} catch (Exception $ex) {
			return array(
					'code'=> 201, 
					"response"=> null, 
					"errors" => array($ex->getMessage()));
		}
	}

}

