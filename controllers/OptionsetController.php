<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$logger = require 'service/logger.php';

$model = new $modelClass();

class OptionsetController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $logger;

		$optionsetTransformer = "{$modelClass}Transformer";
		$optionsetTransformer = new $optionsetTransformer();

		$logger->debug("{OptionSetController} begin::");

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		$insert['query'] = $query;

		$logger->debug("{OptionSetController} end::");
		return ($insert['code']==200) ? $optionsetTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$optionsetTransformer = "{$modelClass}Transformer";
		$optionsetTransformer = new $optionsetTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $optionsetTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$optionsetTransformer = "{$modelClass}Transformer";
		$optionsetTransformer = new $optionsetTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $optionsetTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$optionsetTransformer = "{$modelClass}Transformer";
		$optionsetTransformer = new $optionsetTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $optionsetTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_join_query($data) {

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$sql = "";
		$where = "";

		switch($data['table']):

			case 'pantry_to_unit':
				$where = ($data['pid'] == 0) ? "" : " WHERE u.pid = ".$data['pid'];
				$sql = "SELECT u.*, p.id as p_id, p.name as p_name FROM unit as u LEFT JOIN pantry as p ON u.pid = p.id ".$where;
				break;

			case 'dietary_assoc':
				$sql = "SELECT d.* FROM dietary_assoc as da LEFT JOIN dietary as d ON da.did = d.id WHERE da.pid = ".$data['pid'];
				break;

			default;
		endswitch;
		
		$retrieve = $model->retrieve($sql);
		return ($retrieve['code']==200) ? $assocTransformer->transform($retrieve, $data) : $retrieve;

	}
}