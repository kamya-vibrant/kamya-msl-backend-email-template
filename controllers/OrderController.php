<?php
/**
 * Modify the queries to point on $data['table'] instead of $data['model'] so we can use this to all future assoc tables
 * **/
include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class OrderController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $orderTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $orderTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $orderTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $orderTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "insert",
			"insert" => $data['insert']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $orderTransformer->transform($insert, $data) : $insert;

	}

	public function custom_update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "update",
			"update" => $data['update'],
			"condition" => $data['condition']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$update = $model->update($query);

		return ($update['code']==200) ? $orderTransformer->transform($update, $data) : $update;

	}

	public function custom_retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "retrieve",
			"retrieve" => "*"
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $orderTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$orderTransformer = "{$modelClass}Transformer";
		$orderTransformer = new $orderTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "delete",
			"condition" => $data['condition']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $orderTransformer->transform($delete, $data) : $delete;

	}
}