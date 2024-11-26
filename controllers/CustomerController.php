<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class CustomerController
{

	public function register($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);
		$insert = $model->insert($query);

		return ($insert['code']==200) ? $userTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $userTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $userTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);
		return ($retrieve['code']==200) ? $userTransformer->transform($retrieve, $data) : $retrieve;

	}
	public function custom_join_query($data) {
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$customerTransformer = "{$modelClass}Transformer";
		$customerTransformer = new $customerTransformer();
		
		switch($data['table']){
			case 'optionset':
				$sql = "SELECT * FROM optionset WHERE id = ".$data['id'];
				break;
			case 'ingredient':
				$sql = "SELECT * FROM ingredient WHERE id = ".$data['id'];
				break;
			case 'product':
				$sql = "SELECT * FROM product WHERE id = ".$data['id'];
				break;
		}

		$retrieve = $model->retrieve($sql);
		return ($retrieve['code']==200) ? $customerTransformer->transform($retrieve, $data) : $retrieve;
	}

}