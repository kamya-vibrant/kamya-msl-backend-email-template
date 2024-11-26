<?php

include "transformers/{$modelClass}.php";
include "models/{$modelClass}.php";

$logger = require 'service/logger.php';

$model = new $modelClass();

class MenuController
{

	public function insert($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code'] == 200) ? $menuTransformer->transform($insert, $data) : $insert;
	}

	public function update($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code'] == 200) ? $menuTransformer->transform($update, $data) : $update;
	}

	public function delete($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code'] == 200) ? $menuTransformer->transform($delete, $data) : $delete;
	}

	public function retrieve($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $logger;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$logger->debug("query: " . print_r($query, true));

		$retrieve = $model->retrieve($query);

		return ($retrieve['code'] == 200) ? $menuTransformer->transform($retrieve, $data) : $retrieve;
	}

	public function init_menu($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$retData = array(
			'model' => 'menu',
			'action' => 'retrieve',
			'retrieve' => '*',
			'condition' => array(
				array('sb_id', '=', $data['id'])
			)
		);

		$retrieveQuery = $QueryTransformer->prepareQuery($retData);

		$retrieve = $model->retrieve($retrieveQuery);

		if (empty($retrieve['data'])) :

			$insertData = array(
				'model' => 'menu',
				'action' => 'insert',
				'insert' => array(
					'sb_id' => $data['id'],
					'name' => 'Default Menu',
					'start_date' => '0000-00-00',
					'end_date' => '0000-00-00',
					'is_default' => 1
				)
			);

			$insertQuery = $QueryTransformer->prepareQuery($insertData);

			$insert = $model->insert($insertQuery);

			if ($insert['code'] == 200) :

				$fetchData = array(
					'model' => 'menu',
					'action' => 'retrieve',
					'retrieve' => '*',
					'condition' => array(
						array('id', '=', $insert['id'])
					)
				);

				$fetchQuery = $QueryTransformer->prepareQuery($fetchData);
				$fetch = $model->retrieve($fetchQuery);

				return ($fetch['code'] == 200) ? $menuTransformer->transform($fetch, $data) : $fetch;

			endif;

		else:

			return ($retrieve['code'] == 200) ? $menuTransformer->transform($retrieve, $data) : $retrieve;

		endif;
	}    

	public function duplicate_menu($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$fetchData = array(
			'model' => 'menu',
			'action' => 'retrieve',
			'retrieve' => '*',
			'condition' => array(
				array('id', '=', $data['id'])
			)
		);

		$fetchQuery = $QueryTransformer->prepareQuery($fetchData);
		$fetch = $model->retrieve($fetchQuery);

		if ($fetch['code'] == 200 && !empty($fetch['data'])) :

			// return $fetch['data'][0]['name'];
			//$is_default = ($fetch['data'][0]['is_default'] == 1) ? 1 : 0;
			$insData = array(
				'model' => 'menu',
				'action' => 'insert',
				'insert' => array(
					'sb_id' => $data['sb_id'],
					'name' => $data['name'],
					'start_date' => $fetch['data'][0]['start_date'],
					'end_date' => $fetch['data'][0]['end_date']
				)
			);

			$insertQuery = $QueryTransformer->prepareQuery($insData);

			$insert = $model->insert($insertQuery);

			return ($insert['code'] == 200) ? $menuTransformer->transform($insert, $data) : $insert;

		endif;
	}


	//lxbordo start
	public function custom_menu_fetch($data)
	{
		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $logger;

		$logger->debug("custom_menu_fetch begin::");

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$sql = "";

		switch ($data['table']):
			case 'supplier_menu_by_suppid_and_availability':

				$sql = "SELECT listings, start_date, end_date, name, id FROM menu AS me 
				WHERE me.sb_id = '".$data['id']."' AND '".$data['deliveryDate']."'  between me.start_date AND me.end_date";


				$retrieve = $model->retrieve($sql);
				$logger->debug(print_r($retrieve['data'], true));		
				
				//since menu has the listing separated by comma, we fetch it by IN clause (merge the result)
				if (isset($retrieve['data'])) {
					foreach ($retrieve['data'] as &$menu) {
						$sql = "SELECT * FROM listing as li 
						WHERE li.id in (" . $menu['listings'] . ") 
						AND '" . $data['deliveryDate'] . "' >= li.start_date 
						AND ('" . $data['deliveryDate'] . "' <= li.end_date OR li.end_date = '0000-00-00');";
						$retrieveListing = $model->retrieve($sql);
						//$logger->debug(print_r($retrieveListing['data'], true));

						//merge time
						if (isset($retrieveListing['data'])) {
							$listingsArray = ['listing_data' => $retrieveListing['data']];
							$menu = array_merge($menu, $listingsArray);
						} else {
							$menu = array_merge($menu, ['listing_data' => []]);
						}
						//$logger->debug(print_r($menu, true));

					}
				}
				//$logger->debug(print_r($retrieve, true));
				break;
			default;
		endswitch;

		return ($retrieve['code'] == 200) ? $menuTransformer->transform($retrieve, $data) : $retrieve;
	}
	//lxbordo end

	public function custom_join_query($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$sql = "";
		$where = "";

		switch ($data['table']):

			case 'pantry_to_unit':
				$where = ($data['pid'] == 0) ? "" : " WHERE u.pid = " . $data['pid'];
				$sql = "SELECT u.*, p.id as p_id, p.name as p_name FROM unit as u LEFT JOIN pantry as p ON u.pid = p.id " . $where;
				break;

			case 'dietary_assoc':
				$sql = "SELECT d.* FROM dietary_assoc as da LEFT JOIN dietary as d ON da.did = d.id WHERE da.pid = " . $data['pid'];
				break;

			default;
		endswitch;

		$retrieve = $model->retrieve($sql);
		return ($retrieve['code'] == 200) ? $menuTransformer->transform($retrieve, $data) : $retrieve;
	}

	public function insertimg($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$menuTransformer = "{$modelClass}Transformer";
		$menuTransformer = new $menuTransformer();

		$retData = array(
			'model' => $data['model'],
			'action' => 'retrieve',
			'fields' => $data['fields'],
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retImg = $model->retrieve($retQuery);
		if ($retImg['code'] == 200) {
			$updateData = array(
				'model' => $data['model'],
				'action' => 'update',
				'update' => array(
					$data['fields'][0] => $retImg['data'][0][$data['fields'][0]] . $data['insert']['which']
				),
				'condition' => $data['condition']
			);

			$query = $QueryTransformer->prepareQuery($updateData);

			$update = $model->update($query);

			$retImg = $update;
		}

		return ($update['code'] == 200) ? $menuTransformer->transform($update, $data) : $update;
	}
}
