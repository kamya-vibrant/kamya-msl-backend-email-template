<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class MealdealController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$unitTransformer = "{$modelClass}Transformer";
		$unitTransformer = new $unitTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $unitTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$unitTransformer = "{$modelClass}Transformer";
		$unitTransformer = new $unitTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $unitTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$unitTransformer = "{$modelClass}Transformer";
		$unitTransformer = new $unitTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $unitTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$unitTransformer = "{$modelClass}Transformer";
		$unitTransformer = new $unitTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $unitTransformer->transform($retrieve, $data) : $retrieve;

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

	public function upload_image_parts($data) {

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		#test manual query
		$sql = "SELECT image FROM `".$data['table']."` WHERE id=".$data['condition'][0][2];
		$res = $model->retrieve($sql);
		//return $sql;

		if ($res['code'] == 200) :

			$currentImageString = $res['data'][0]['image'];
			//get the next part and join into the current image string
			$nextPart = $data['parts'][0];

			#clean the next part string and remove whitespaces
			$nextPart = str_replace(array("\r", "\n"), '', $nextPart);

			#change spaces into "+" sign
			$nextPart = str_replace(" ", "+", $nextPart);

			$newImageString = $currentImageString.$nextPart;

			#proceed to update
			$updateImageString = array(
				'model' => $data['table'],
				'action' => 'update',
				'update' => array(
					'image' => $newImageString
				),
				'condition' => $data['condition']
			);

			$updateQuery = $QueryTransformer->prepareQuery($updateImageString);
			$update = $model->update($updateQuery);

			return ($update['code']==200) ? $pantryTransformer->transform($update, $data) : $update;

		endif;
	}
}