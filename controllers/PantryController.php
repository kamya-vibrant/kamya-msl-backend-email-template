<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class PantryController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $pantryTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $pantryTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $pantryTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $pantryTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function insert_unit($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$retData = array(
			'model' => 'unit', // set model to unit to insert in unit table
			'action' => 'insert',
			'insert' => $data['insert'],
			'condition' => ''
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retUnit = $model->insert($retQuery);

		return ($retUnit['code']==200) ? $pantryTransformer->transform($retUnit, $data) : $retUnit;

	}

	public function save_unit($data){ // for editing the added unit in the form

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$retData = array(
			'model' => 'unit', // set model to unit to insert in unit table
			'action' => 'update',
			'update' => $data['fields'],
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retUnit = $model->update($retQuery);

		return ($retUnit['code']==200) ? $pantryTransformer->transform($retUnit, $data) : $retUnit;

	}

	public function update_unit($data){ // for updating the pid of the unit when product is created

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$retData = array(
			'model' => 'unit', // set model to unit to insert in unit table
			'action' => 'update',
			'update' => array("pid" => $data['update']['pid']),
			'condition' => array(
				array('id', '=', $data['update']['uid'])

			)
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retUnit = $model->update($retQuery);

		return ($retUnit['code']==200) ? $pantryTransformer->transform($retUnit, $data) : $retUnit;

	}

	public function remove_unit($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$retData = array(
			'model' => 'unit', // set model to unit to insert in unit table
			'action' => 'delete',
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retUnit = $model->delete($retQuery);
		
		return ($retUnit['code']==200) ? $pantryTransformer->transform($retUnit, $data) : $retUnit;

	}

	public function insert_pantry_details($data) {

		/**
		 * Customized to handle update/insert of data into allergens_assoc or dietary_assoc table. Determined by $data['table']
		 * Added a condition, to check first if the allergen or dietary is already in the table with the pantry id (pid)
		 * If it is already exisiting, just update the record. if not, insert a new record
		 * insertPantryDetails function in create-product.js runs after the main product is successfully added into DB
		 * **/

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$tid = ($data['table'] == "allergens_assoc") ? "aid" : "did";

		
		$retData = array(
			'model' => $data['table'],
			'action' => 'retrieve',
			'condition' => array(
				array("pid", "=", $data['field']['pid'], "AND"),
				array("$tid", "=", $data['field']['tid'])
			)
		);

		$detailsQuery = $QueryTransformer->prepareQuery($retData);
		$details = $model->retrieve($detailsQuery);
		
		if (!empty($details['data'])) :
			#do update the existing record
			$par = array(
				'model' => $data['table'],
				'action' => 'update',
				'update' => array(
					$tid => $data['field']['tid']
				),
				'condition' => array(
					array('id', '=', $details['data'][0]['id'])
				)
			);

			$updateQuery = $QueryTransformer->prepareQuery($par);
			$update = $model->update($updateQuery);
			
			return ($update['code']==200) ? $pantryTransformer->transform($update, $data) : $update;

		else :

			$par = array(
				'model' => $data['table'],
				'action' => 'insert',
				'insert' => array(
					'pid' => $data['field']['pid'],
					$tid => $data['field']['tid']
				)
			);

			$insertQuery = $QueryTransformer->prepareQuery($par);
			$insert = $model->insert($insertQuery);

			return ($insert['code']==200) ? $pantryTransformer->transform($insert, $data) : $insert;

		endif;

	}

	public function insertimg($data){

		/**
		 * Modified a bit so we can choose on which table the update/upload of image will be. Either it will be for the pantry table or the unit table
		 * sendsplice function in create-product.js has been customized for uploading image if it is for main product or for the unit image.
		 * 'model' => $data['model'] changed to 'model' => $data['table']
		 * **/

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		$retData = array(
			'model' => $data['table'],
			'action' => 'retrieve',
			'fields' => $data['fields'],
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retImg = $model->retrieve($retQuery);

		if($retImg['code']==200){
			$updateData = array(
				'model'=>$data['table'],
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

		return ($update['code']==200) ? $pantryTransformer->transform($update, $data) : $update;
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

	public function custom_join_query($data) {

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$sql = "SELECT u.*, p.* FROM pantry as p LEFT JOIN unit as u ON p.id = u.pid WHERE p.id = 88";

		return $sql;

	}

}