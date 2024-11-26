<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class AllergenController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$allergenTransformer = "{$modelClass}Transformer";
		$allergenTransformer = new $allergenTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $allergenTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$allergenTransformer = "{$modelClass}Transformer";
		$allergenTransformer = new $allergenTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $allergenTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$allergenTransformer = "{$modelClass}Transformer";
		$allergenTransformer = new $allergenTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $allergenTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$allergenTransformer = "{$modelClass}Transformer";
		$allergenTransformer = new $allergenTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $allergenTransformer->transform($retrieve, $data) : $retrieve;

	}

}