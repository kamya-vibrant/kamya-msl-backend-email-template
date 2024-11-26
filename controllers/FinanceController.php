<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";
include "transformers/User.php"; 
include "models/User.php";
include "models/Contact.php";
include "models/Address.php";
include "models/School.php";

$model = new $modelClass();
$User = new User();
$Contact = new Contact();
$Address = new Address();
$School = new School();

class FinanceController
{

		public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$financeTransformer = "{$modelClass}Transformer";
		$financeTransformer = new $financeTransformer();

		$query = $QueryTransformer->prepareQuery($data);
		
		$insert = $model->insert($query);

		return ($insert['code']==200) ? $financeTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$financeTransformer = "{$modelClass}Transformer";
		$financeTransformer = new $financeTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $financeTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$financeTransformer = "{$modelClass}Transformer";
		$financeTransformer = new $financeTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $financeTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$financeTransformer = "{$modelClass}Transformer";
		$financeTransformer = new $financeTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $financeTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function insertfile($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$financeTransformer = "{$modelClass}Transformer";
		$financeTransformer = new $financeTransformer();

		$retData = array(
			'model' => $data['model'],
			'action' => 'retrieve',
			'fields' => $data['fields'],
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retImg = $this->retrieve($retData);

		if($retImg['code']==200){
			$updateData = array(
				'model'=>$data['model'],
				'action' => 'update',
				'update' => array(
					$data['fields'][0] => $retImg['data'][0][$data['fields'][0]].$data['insert']['which']
				),
				'condition' => $data['condition']
			);

			$query = $QueryTransformer->prepareQuery($updateData);

			$update = $model->update($query);

			$retImg = $update;
		}

		return ($update['code']==200) ? $financeTransformer->transform($update, $data) : $update;
		
	}
	public function generateCode($length=10){

    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[random_int(0, $charactersLength - 1)];
	    }

	    return sha1($randomString.date("Ymd-His"));
    }

}