<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";

$model = new $modelClass();

class PurchaseorderController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$purchaseorderTransformer = "{$modelClass}Transformer";
		$purchaseorderTransformer = new $purchaseorderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $purchaseorderTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$purchaseorderTransformer = "{$modelClass}Transformer";
		$purchaseorderTransformer = new $purchaseorderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $purchaseorderTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$purchaseorderTransformer = "{$modelClass}Transformer";
		$purchaseorderTransformer = new $purchaseorderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $purchaseorderTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$purchaseorderTransformer = "{$modelClass}Transformer";
		$purchaseorderTransformer = new $purchaseorderTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $purchaseorderTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_join_query($data) {

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$purchaseorderTransformer = "{$modelClass}Transformer";
		$purchaseorderTransformer = new $purchaseorderTransformer();

		$sql = "";
		$where = "";

		switch($data['table']):
			case 'purchaseorderTostudentToclass':
				$sql ="SELECT po.*, s.first_name, s.last_name, s.allergies, c.name as class_name, c.id as class_id, b.delivery_time
					   FROM purchaseorder as po 
					   LEFT JOIN booking as b ON po.booking_id=b.id
					   LEFT JOIN student as s ON po.student_id = s.id 
					   LEFT JOIN class_ as c ON c.id = s.class_id
					   WHERE po.booking_id = ".$data['id'];
				break;
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
		return ($retrieve['code']==200) ? $purchaseorderTransformer->transform($retrieve, $data) : $retrieve;

	}
}