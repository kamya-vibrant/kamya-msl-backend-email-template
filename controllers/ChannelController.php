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

class ChannelController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $channelTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $channelTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $channelTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $channelTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function insertimg($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

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

		return ($update['code']==200) ? $channelTransformer->transform($update, $data) : $update;
	}

	public function custom_retrieve($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$retData = (isset($data['condition']) && !empty($data['condition']))
			? array(
				"model" => $data['table'],
				"action" => "retrieve",
				"retrieve" => "*",
				"condition" => $data['condition']
			)
			:
			array(
				"model" => $data['table'],
				"action" => "retrieve",
				"retrieve" => "*"
			);

		$query = $QueryTransformer->prepareQuery($retData);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code'] == 200) ? $channelTransformer->transform($retrieve, $data) : $retrieve;
	}
	public function custom_update($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "update",
			"update" => $data['update'],
			"condition" => $data['condition']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$update = $model->update($query);

		return ($update['code'] == 200) ? $channelTransformer->transform($update, $data) : $update;
	}
	public function custom_delete($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "delete",
			"condition" => $data['condition']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$delete = $model->delete($query);

		return ($delete['code'] == 200) ? $channelTransformer->transform($delete, $data) : $delete;
	}
	public function custom_insert($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$channelTransformer = "{$modelClass}Transformer";
		$channelTransformer = new $channelTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "insert",
			"insert" => $data['insert']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$insert = $model->insert($query);

		return ($insert['code'] == 200) ? $channelTransformer->transform($insert, $data) : $insert;
	}

}