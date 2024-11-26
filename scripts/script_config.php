<?php

define("ROOT_DIRECTORY", str_replace(basename(__DIR__),"",dirname(__FILE__)));
define("DEFUALT_DIRECTORY", str_replace(basename(__DIR__),"defaults",dirname(__FILE__)));
define("CONFIG", str_replace(basename(__DIR__),"config",dirname(__FILE__))."/config.php");
define('ACL', '/access_controls.json');
define('SETTINGS_KEY', '/settings_key.json');

function transformData($data, $for='insert', $model='', $condition=''){

 	$dataString = '';

	if($for=='update'){

		foreach($data as $key => $value){
			if(trim($key)=='update'){ $value = sha1(decodePassword($value)); }
			$dataString .= ($dataString=="") ? "$key = '$value' " : ",$key = '$value' ";
		}
		$dateTime = date("Y-m-d H:i:s");
		$dataString .= (!empty($dataString)) ? " updated_at = '$dateTime'" : '';

		$dataString = (!empty($dataString)) ? "UPDATE $model SET ".$condition." $condi" : '';
	}

	if($for=='insert'){
		$keys = '';
		$values = '';

		foreach($data as $key => $value){
			if(trim($key)=='password'){ $value = sha1(decodePassword($value)); }
			$keys .= ($keys=="") ? $key : ", $key";
			$values .= ($values=="") ? "'$value'" : ", '$value'";
		}

		$dateTime = date("Y-m-d H:i:s");
		$dataString = (!empty($keys)) ? "INSERT INTO $model ($keys, created_at, updated_at) VALUES($values, '$dateTime', '$dateTime')" : '';
	}
	
	return $dataString;
}

function decodePassword($pwd){
   	$clean = strtr( $pwd, ' ', '+');
	return base64_decode( $clean );
}


?>