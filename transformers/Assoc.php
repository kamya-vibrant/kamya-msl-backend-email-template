<?php

/**
 * Address Transformer - Use to transform data before returning back as response
 *
 * **/

class AssocTransformer{

	/**
	 * 
	 *
	 * **/

	public function transform($response, $data){

		if(isset($data['update'])){
			if(isset($data['update']['password'])){
				unset($data['update']['password']);
			}

			$response['data'] = $data['update'];
		}

		if(isset($data['insert'])){
			if(isset($data['insert']['password'])){
				unset($data['insert']['password']);
			}

			$response['data'] = $data['insert'];
		}

		return $response;

	}

}

?>