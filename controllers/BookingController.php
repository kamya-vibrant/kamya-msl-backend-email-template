<?php
/**
 * Modify the queries to point on $data['table'] instead of $data['model'] so we can use this to all future assoc tables
 * **/
include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";
include "models/School.php";

$model = new $modelClass();
$School = new School();

class BookingController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $bookingTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $bookingTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $bookingTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $bookingTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function custom_insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "insert",
			"insert" => $data['insert']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $bookingTransformer->transform($insert, $data) : $insert;

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

		//return $model;

		$query = $QueryTransformer->prepareQuery($retData);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $bookingTransformer->transform($delete, $data) : $delete;

	}

	public function custom_join_query($data) {

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$sql = "";
		$clause = "";

		switch($data['table']):

			case 'allergens_assoc':
				$sql = "SELECT a.* FROM allergens_assoc as aa LEFT JOIN allergen as a ON aa.aid = a.id WHERE aa.item_id = ".$data['id'];
				break;

			case 'dietary_assoc':
				$sql = "SELECT d.* FROM dietary_assoc as da LEFT JOIN dietary as d ON da.did = d.id WHERE da.pid = ".$data['id'];
				break;

			case 'category_to_cat_assoc':
				$sql = "SELECT ca.* FROM cat_assoc as ca LEFT JOIN category as c ON ca.cid = c.id";
				break;

			case 'optionset_assoc_to_category':
				$sql = "SELECT c.*, oa.id as osid, oa.osid as os FROM optionset_assoc as oa LEFT JOIN category as c ON oa.cid = c.id WHERE oa.osid = ".$data['id'];
				break;

			case 'optionset_assoc_to_option':
				$sql = "SELECT o.*, oa.id as osid, oa.osid as os FROM optionset_assoc as oa LEFT JOIN option as o ON oa.oid = o.id WHERE oa.osid = ".$data['id'];
				break;

			case 'pantry_to_cat_assoc':
				$sql = "SELECT p.* FROM pantry p LEFT JOIN cat_assoc c ON p.id = c.pid AND c.cid = ".$data['id']." WHERE p.name LIKE '%".$data['keyword']."%' AND c.pid IS NULL";
				break;

			case 'pantry_to_option_assoc':
				#$sql = "SELECT p.* FROM product p LEFT JOIN option_assoc oa ON p.id = oa.item_id AND oa.oid = ".$data['id']." WHERE p.name LIKE '%".$data['keyword']."%' AND oa.item_id IS NULL";
				$sql = "(
						    SELECT p.*, 'product' AS type
						    FROM product p
						    LEFT JOIN option_assoc oa ON p.id = oa.item_id AND oa.oid = {$data['id']}
						    WHERE p.name LIKE '%{$data['keyword']}%'
						    AND oa.item_id IS NULL
						)
						UNION ALL
						(
						    SELECT i.*, 'ingredient' AS type
						    FROM ingredient i
						    LEFT JOIN option_assoc oa ON i.id = oa.item_id AND oa.oid = {$data['id']}
						    WHERE i.name LIKE '%{$data['keyword']}%'
						    AND oa.item_id IS NULL
						)";
				break;

			case 'options_to_optionset_assoc':
				if ($data['id'] == 0) $clause = "";
				else $clause = "AND oa.osid != ".$data['id'];
				$sql = "SELECT o.* FROM option o LEFT JOIN optionset_assoc oa ON o.id = oa.oid WHERE o.name LIKE '%".$data['keyword']."%' ".$clause;
				break;

			case 'cat_to_optionset_assoc':
				if ($data['id'] == 0) $clause = "";
				else $clause = "AND oa.osid != ".$data['id'];
				$sql = "SELECT c.* FROM category c LEFT JOIN optionset_assoc oa ON c.id = oa.cid WHERE c.name LIKE '%".$data['keyword']."%' ".$clause;
				break;

			case 'cat_assoc_to_pantry':
				$sql = "SELECT p.id as pid, p.name, p.image, ca.id as caid FROM cat_assoc as ca LEFT JOIN pantry as p ON p.id = ca.pid WHERE ca.cid = ".$data['id'];
				break;

			case 'product_to_ingredient_assoc':
				$sql = "SELECT i.* FROM ingredient_assoc as ia LEFT JOIN ingredient as i ON ia.inid = i.id WHERE ia.item_id = ".$data['id'];
				break;

			case 'product_to_allergens_assoc':
				$sql = "SELECT a.* FROM allergens_assoc as aa LEFT JOIN allergen as a ON aa.aid = a.id WHERE aa.item_id = ".$data['id'];
				break;

			case 'sizing':
				if ($data['id'] == 0) $clause = "";
				else $clause = "AND s.pid != ".$data['id'];
				$sql = "SELECT * FROM product WHERE name LIKE '%".$data['keyword']."%' GROUP BY id";
				break;

			case 'sizing_assoc':
				$sql = "SELECT s.*, p.name as p_name FROM sizing as s LEFT JOIN product as p ON s.pid = p.id";
				break;

			case 'sizing_listing_assoc':
				$sql = "SELECT s.*, p.name as p_name FROM sizing as s LEFT JOIN product as p ON s.pid = p.id WHERE LOWER(s.name) LIKE LOWER('%".$data['keyword']."%') AND s.pid = ".$data['id'];
				break;

			case 'option_assoc_to_pantry':
				#$sql = "SELECT p.id as pid, p.name, p.image, oa.id as oaid FROM option_assoc as oa LEFT JOIN product as p ON p.id = od.item_id WHERE oa.oid = ".$data['id'];
				$sql = "( 
							SELECT p.id AS pid, p.name, p.image, oa.id AS oaid, 'product' AS type 
							FROM option_assoc AS oa 
							LEFT JOIN product AS p ON p.id = oa.oid WHERE oa.item_id = {$data['id']} 
							AND p.id IS NOT NULL 
						) 
						UNION ALL 
						( 
							SELECT i.id AS pid, i.name, i.image, oa.id AS oaid, 'ingredient' AS type 
							FROM option_assoc AS oa 
							LEFT JOIN ingredient AS i ON i.id = oa.oid WHERE oa.item_id = {$data['id']}
							AND i.id IS NOT NULL 
						);";
				break;

			case 'listing_to_sizing_assoc':
				$sql = "SELECT s.* FROM sizing as s WHERE s.item_id = ".$data['id'];
				break;

			case 'listing_to_serving_temp':
				$sql = "SELECT st.*, sta.id as sta_id FROM serving_temp_assoc as sta LEFT JOIN serving_temp as st ON sta.stid = st.id WHERE sta.item_id = ".$data['id'];
				break;

			case 'listing_to_product_addon':
				$sql = "SELECT p.*, p.id as p_id FROM addon as a LEFT JOIN product as p ON p.id = a.item_id WHERE a.type = 'addon-product' AND a.lid = ".$data['id'];
				break;

			case 'listing_to_option_assoc':
				$sql = "SELECT o.*, o.id as o_id, oa.id as oa_id, oa.type as oa_type, oa.sort as oa_sort FROM option_assoc as oa LEFT JOIN option as o ON oa.oid = o.id WHERE oa.item_id = ".$data['id']." ORDER BY oa.sort ASC";
				break;

			case 'optionset_from_optionset_assoc': #get the options of the optionset using optionset_assoc
				$sql = "SELECT o.* FROM optionset_assoc as osa LEFT JOIN option as o ON osa.oid = o.id WHERE osa.osid = ".$data['id'];
				break;

			case 'option_from_option_assoc': #get the ingredients or products of the option using option_assoc
				#$sql = "SELECT o.* FROM option_assoc as oa LEFT JOIN option as o ON osa.oid = o.id WHERE osa.osid = ".$data['id'];
				$sql = "SELECT 
						    p.*,
						    p.id as p_id,
						    p.name as p_name,
						    i.*,
						    i.id as i_id,
						    i.name as i_name,
						    oa.type as oa_type
						FROM option_assoc AS oa
						LEFT JOIN product as p ON oa.oid = p.id AND oa.type = 'product'
						LEFT JOIN ingredient as i ON oa.oid = i.id AND oa.type = 'ingredient'
						WHERE oa.item_id = ".$data['id']."
						";
				break;

			case 'listing_to_product_option_assoc':
				#$sql = "SELECT o.*, o.id as o_id, oa.id as oa_id, oa.type as oa_type, oa.sort as oa_sort FROM option_assoc as oa LEFT JOIN option as o ON oa.oid = o.id WHERE oa.item_id = ".$data['id']." ORDER BY oa.sort ASC";
				$sql = "SELECT 
						    COALESCE(o.id, os.id) AS o_id,
						    poa.id AS oa_id,
						    poa.opt_id AS oa_opt_id,
						    poa.type AS oa_type,
						    poa.sort AS oa_sort,
						    o.*,
						    o.name as o_name,
						    os.*,
						    os.name as os_name
						FROM product_option_assoc AS poa
						LEFT JOIN option AS o ON poa.opt_id = o.id AND poa.type = 'option'
						LEFT JOIN optionset AS os ON poa.opt_id = os.id AND poa.type = 'optionset'
						WHERE poa.item_id = ".$data['id']."
						ORDER BY poa.sort ASC;";
				break;

			case 'listing_to_product_assoc':
				$sql = "SELECT p.*, pa.id as pa_id FROM product_assoc as pa LEFT JOIN product as p ON pa.pid = p.id WHERE pa.item_id = ".$data['id'];
				break;

			case 'listing_to_allergens_assoc':
				$sql = "SELECT a.* FROM listing as l LEFT JOIN product_assoc as pa ON pa.item_id = l.id LEFT JOIN allergens_assoc as aa ON pa.pid = aa.item_id LEFT JOIN allergen as a ON aa.aid = a.id WHERE l.id = ".$data['id'];
				break;

			default:
				break;
		endswitch;
		
		#return $sql;

		$retrieve = $model->retrieve($sql);
		return ($retrieve['code']==200) ? $bookingTransformer->transform($retrieve, $data) : $retrieve;

	}

	public function fetchSchoolBookings($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $School;

		$bookingTransformer = "{$modelClass}Transformer";
		$bookingTransformer = new $bookingTransformer();

		$sql = "SELECT sch.id as sc_id, st.school, st.customer_id as customer_id, sch.org_id as org_id, sch.name as school_name FROM student as st LEFT JOIN school as sch ON sch.id = st.school WHERE st.customer_id = '".$data['params']['customer_id']."'";
		$schools = $School->retrieve($sql);

		$conditionIs = "";
		$schoolData = $schools['data'] ?? [];
		//$schools['data'][] = $sql;
		$schoolIds = array();

		foreach($schoolData as $skey => $sval){
			$conditionIs = ($conditionIs == "") ? " WHERE (b.organizer_fid = '{$sval['org_id']}'" : $conditionIs." OR b.organizer_fid = '{$sval['org_id']}'";
			$schoolIds[$sval['org_id']] = $sval['sc_id'];
		}

		$dateNow = date("Y-m-d");

		$conditionIs = ($conditionIs!="") ? $conditionIs.") AND delivery_date > '{$dateNow}'" : " WHERE l.id='0'";

		$sql = "SELECT b.id as booking_id, b.organizer_fid, b.product_id as item_id, b.delivery_date as delivery_date, l.item_name, l.short_description, l.image FROM booking as b LEFT JOIN product as l ON l.id = b.product_id ".$conditionIs;
		//$schools['data'][] = $sql;
		// echo $sql.'---------------------------------------------------------------------------------';
		$retrieve = $model->retrieve($sql);
		
		$returndata = array(

			'bookings' => $retrieve['data'],
			'schoolids' => $schoolIds

		);

		$retrieve['data'] = $returndata;

		return ($retrieve['code']==200) ? $retrieve : $retrieve;
	}
}