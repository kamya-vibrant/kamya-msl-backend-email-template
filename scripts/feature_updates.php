<?php

require_once('script_config.php');
require_once(CONFIG);

$query = "DELETE FROM feature";
$stmt = $dbh->prepare($query);
$stmt->execute();

$string = file_get_contents(DEFUALT_DIRECTORY."/features.json");
$json_array = json_decode($string, true);
$model = 'feature';

foreach($json_array as $key => $value){

	$id = $value['id'];

	$query = "SELECT * FROM $model WHERE id = '$id'";
	$stmt = $dbh->prepare($query);
	$stmt->execute();


	$query = ($stmt->rowCount() > 0) ? transformData($value, 'update') : transformData($value, 'insert');


	if(!empty($query)){
		$query = ($stmt->rowCount() > 0) ? "UPDATE $model SET $query WHERE  id = '$id'" : "INSERT INTO $model ".$query;
		$stmt = $dbh->prepare($query);
    	$stmt->execute();
	}
	
}

?>