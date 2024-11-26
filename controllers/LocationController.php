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

class LocationController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$locationTransformer = "{$modelClass}Transformer";
		$locationTransformer = new $locationTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $locationTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$locationTransformer = "{$modelClass}Transformer";
		$locationTransformer = new $locationTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $locationTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$locationTransformer = "{$modelClass}Transformer";
		$locationTransformer = new $locationTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $locationTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$locationTransformer = "{$modelClass}Transformer";
		$locationTransformer = new $locationTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $locationTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function insertimg($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$locationTransformer = "{$modelClass}Transformer";
		$locationTransformer = new $locationTransformer();

		$retData = array(
			'model' => $data['model'],
			'action' => 'retrieve',
			'fields' => $data['fields'],
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retImg = $model->retrieve($retQuery);

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

		return ($update['code']==200) ? $locationTransformer->transform($update, $data) : $update;
	}

}