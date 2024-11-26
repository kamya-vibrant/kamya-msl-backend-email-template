<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class SizingController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$sizingTransformer = "{$modelClass}Transformer";
		$sizingTransformer = new $sizingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $sizingTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$sizingTransformer = "{$modelClass}Transformer";
		$sizingTransformer = new $sizingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $sizingTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$sizingTransformer = "{$modelClass}Transformer";
		$sizingTransformer = new $sizingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $sizingTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$sizingTransformer = "{$modelClass}Transformer";
		$sizingTransformer = new $sizingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $sizingTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_insert($data) {
		/**
		 * This custom function is for checking first if record is already existing in the table to prevent duplications
		 * **/
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$sizingTransformer = "{$modelClass}Transformer";
		$sizingTransformer = new $sizingTransformer();

		$check_sql = "SELECT * FROM sizing WHERE LOWER(name) = LOWER('".$data['name']."') AND pid = ".$data['pid'];

		$check = $model->retrieve($check_sql);

		if (empty($check['data'])) :

			$insertData = array(
				'model' => 'sizing',
				'action' => 'insert',
				'insert' => array(
					'pid' => $data['pid'],
					'name' => $data['name']
				)
			);

			$insertQuery = $QueryTransformer->prepareQuery($insertData);
			$insert = $model->insert($insertQuery);

			return ($insert['code']==200) ? $sizingTransformer->transform($insert, $data) : $insert; 
		else:
			$check['code'] = 201;
			$check['message'] = "Existing";
			$check['data'] = [];
			return $check; 

		endif;	

	}

}