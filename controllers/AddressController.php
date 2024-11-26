<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class AddressController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$addressTransformer = "{$modelClass}Transformer";
		$addressTransformer = new $addressTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $addressTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$addressTransformer = "{$modelClass}Transformer";
		$addressTransformer = new $addressTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $addressTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$addressTransformer = "{$modelClass}Transformer";
		$addressTransformer = new $addressTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $addressTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$addressTransformer = "{$modelClass}Transformer";
		$addressTransformer = new $addressTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $addressTransformer->transform($retrieve, $data) : $retrieve;

	}

}