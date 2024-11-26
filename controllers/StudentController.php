<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class StudentController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$studentTransformer = "{$modelClass}Transformer";
		$studentTransformer = new $studentTransformer();

		$query = $QueryTransformer->prepareQuery($data);
		$insert = $model->insert($query);

		return ($insert['code']==200) ? $studentTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$studentTransformer = "{$modelClass}Transformer";
		$studentTransformer = new $studentTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $studentTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$studentTransformer = "{$modelClass}Transformer";
		$studentTransformer = new $studentTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $studentTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$studentTransformer = "{$modelClass}Transformer";
		$studentTransformer = new $studentTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);
		return ($retrieve['code']==200) ? $studentTransformer->transform($retrieve, $data) : $retrieve;

	}

}