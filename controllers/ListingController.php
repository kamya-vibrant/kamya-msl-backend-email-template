<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class ListingController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$listingTransformer = "{$modelClass}Transformer";
		$listingTransformer = new $listingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $listingTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$listingTransformer = "{$modelClass}Transformer";
		$listingTransformer = new $listingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $listingTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$listingTransformer = "{$modelClass}Transformer";
		$listingTransformer = new $listingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $listingTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$listingTransformer = "{$modelClass}Transformer";
		$listingTransformer = new $listingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $listingTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_insert($data) {

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$listingTransformer = "{$modelClass}Transformer";
		$listingTransformer = new $listingTransformer();

		$condition = [];
		$insert_update_fields = [];

		switch ($data['option_type']) :

			case 'product': case 'ingredient':

				$condition = array(
								array('sb_id', '=', $data['insert']['sb_id'], 'AND'),
								array('item_id', '=', $data['insert']['item_id'], 'AND'),
								array('opt_parent_id', '=', $data['insert']['opt_parent_id'], 'AND'),
								array('opt_id', '=', $data['insert']['opt_id'], 'AND'),
								array('type', '=', $data['insert']['type'])
							);

				$insert_update_fields = array(
											'min' => $data['insert']['min'],
											'max' => $data['insert']['max'],
											'exceed' => $data['insert']['exceed']
										);

				break;

			case 'sizing_product':

				$condition = array(
								array('sb_id', '=', $data['insert']['sb_id'], 'AND'),
								array('name', '=', $data['insert']['name'], 'AND'),
								array('item_id', '=', $data['insert']['item_id'], 'AND'),
								array('type', '=', $data['insert']['type'])
							);

				$insert_update_fields = array();

				if (isset($data['insert']['rrp'])) $insert_update_fields['rrp'] = $data['insert']['rrp'];
				if (isset($data['insert']['extra_portion'])) $insert_update_fields['extra_portion'] = $data['insert']['extra_portion'];

				break;

			default:

				$condition = array(
								array('sb_id', '=', $data['insert']['sb_id'], 'AND'),
								array('item_id', '=', $data['insert']['item_id'], 'AND'),
								array('opt_parent_id', '=', $data['insert']['opt_parent_id'], 'AND'),
								array('opt_id', '=', $data['insert']['opt_id'], 'AND'),
								array('type', '=', $data['insert']['type'])
							);

				$insert_update_fields = array(
											'sort' => @$data['insert']['sort']
										);

				break;

		endswitch;

		$retData = array(
			'model' => $data['table'],
			'action' => 'retrieve',
			'retrieve' => '*',
			'condition' => $condition
		);

		$retrieveQuery = $QueryTransformer->prepareQuery($retData);
		$retrieve = $model->retrieve($retrieveQuery);

		if ($retrieve['code'] == 200 && !empty($retrieve['data'])) :
			//update only
			$updateData = array(
				'model' => $data['table'],
				'action' => 'update',
				'update' => $insert_update_fields,
				'condition' => array(
					array('id', '=', $retrieve['data'][0]['id'])
				)
			);

			$updateQuery = $QueryTransformer->prepareQuery($updateData);
			$update = $model->update($updateQuery);
			return ($update['code']==200) ? $listingTransformer->transform($update, $data) : $update;

		else:

			$insertData = array(
				'model' => $data['table'],
				'action' => 'insert',
				'insert' => $data['insert']
			);

			$insertQuery = $QueryTransformer->prepareQuery($insertData);
			$insert = $model->insert($insertQuery);
			return ($insert['code']==200) ? $listingTransformer->transform($insert, $data) : $insert;
		
		endif;

	}

	public function custom_update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "update",
			"update" => $data['update'],
			"condition" => $data['condition']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$update = $model->update($query);

		return ($update['code']==200) ? $bookingTransformer->transform($update, $data) : $update;

	}

	public function custom_retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "retrieve",
			"retrieve" => "*"
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $bookingTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "delete",
			"condition" => $data['condition']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $bookingTransformer->transform($delete, $data) : $delete;

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