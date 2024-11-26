<?php

/**
 * Modify the queries to point on $data['table'] instead of $data['model'] so we can use this to all future assoc tables
 * **/
include "transformers/{$modelClass}.php";
include "models/{$modelClass}.php";

$model = new $modelClass();

class AssocController
{

	public function insert($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;
	}

	public function update($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code'] == 200) ? $assocTransformer->transform($update, $data) : $update;
	}

	public function delete($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;
	}

	public function retrieve($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code'] == 200) ? $assocTransformer->transform($retrieve, $data) : $retrieve;
	}

	public function custom_insert($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "insert",
			"insert" => $data['insert']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$insert = $model->insert($query);

		return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;
	}

	public function custom_retrieve($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$retData = (isset($data['condition']) && !empty($data['condition']))
			? array(
				"model" => $data['table'],
				"action" => "retrieve",
				"retrieve" => "*",
				"condition" => $data['condition']
			)
			:
			array(
				"model" => $data['table'],
				"action" => "retrieve",
				"retrieve" => "*"
			);

		$query = $QueryTransformer->prepareQuery($retData);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code'] == 200) ? $assocTransformer->transform($retrieve, $data) : $retrieve;
	}

	public function custom_update($data)
	{

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

		return ($update['code'] == 200) ? $bookingTransformer->transform($update, $data) : $update;
	}

	public function custom_delete($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$retData = array(
			"model" => $data['table'],
			"action" => "delete",
			"condition" => $data['condition']
		);

		$query = $QueryTransformer->prepareQuery($retData);

		$delete = $model->delete($query);

		return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;
	}

	public function custom_join_query($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$sql = "";
		$clause = "";

		switch ($data['table']):
			case 'supplier_dashboard_bookings_count_orders':
				$sql = "SELECT 
						 po.id as purchase_id,
						 b.*, (SELECT COUNT(*) FROM purchaseorder as pu WHERE pu.booking_id = b.id) as order_count,
						 (SELECT s.name FROM school as s WHERE s.org_id = b.organizer_fid LIMIT 1) as school_name,
						 (SELECT s.id FROM school as s WHERE s.org_id = b.organizer_fid LIMIT 1) as school_id,
						 (SELECT SUM(pu.total_amount) FROM purchaseorder as pu WHERE pu.booking_id = b.id) as total_amount
						FROM booking as b
						LEFT JOIN purchaseorder as po ON b.id = po.booking_id
						WHERE b.supplier_fid = ".$data['supplier_id'];
				break;
			case 'listing_allergens':
				$sql = "SELECT
						aa.aid, aa.item_id as item
						FROM listing as l
						INNER JOIN product_option_assoc as po ON po.item_id = l.id
						LEFT JOIN ingredient as i ON po.opt_id=i.id AND po.type = 'ingredient'
						LEFT JOIN product as p ON po.opt_id=p.id AND po.type = 'recipes'
						LEFT JOIN option as o ON po.opt_id=o.id AND po.type = 'option'
						LEFT JOIN optionset as os ON po.opt_id=os.id AND po.type = 'optionset'
						LEFT JOIN optionset_assoc as osa ON os.id=osa.osid 
						LEFT JOIN option_assoc as o_a ON o_a.oid = osa.oid
						LEFT JOIN ingredient as i_ ON i_.id=o_a.item_id AND o_a.type = 'ingredient'
						LEFT JOIN product as p_ ON p_.id=o_a.item_id AND o_a.type = 'product'
						LEFT JOIN allergens_assoc as aa ON (aa.item_id = i.id AND aa.type='ingredient') || 
									(aa.item_id = p.id AND aa.type='product') ||
									(aa.item_id = i_.id AND aa.type='ingredient') ||
									(aa.item_id = p_.id AND aa.type='product')
						WHERE l.id=" . $data['id'];

				break;
			case 'booking_assoc_allergens':
				$sql = "SELECT
						aa.aid, aa.item_id as item
						FROM booking_assoc as ba
						LEFT JOIN ingredient as i ON ba.item_id=i.id AND ba.type = 'ingredient'
						LEFT JOIN product as p ON ba.item_id=p.id AND ba.type = 'product'
						LEFT JOIN option as o ON ba.item_id=o.id AND ba.type = 'option'
						LEFT JOIN optionset as os ON ba.item_id=os.id AND ba.type = 'optionset'
						LEFT JOIN optionset_assoc as osa ON os.id=osa.osid 
						LEFT JOIN option_assoc as o_a ON o_a.oid = osa.oid
						LEFT JOIN ingredient as i_ ON i_.id=o_a.item_id AND o_a.type = 'ingredient'
						LEFT JOIN product as p_ ON p_.id=o_a.item_id AND o_a.type = 'product'
						LEFT JOIN allergens_assoc as aa ON (aa.item_id = i.id AND aa.type='ingredient') || 
									(aa.item_id = p.id AND aa.type='product') ||
									(aa.item_id = i_.id AND aa.type='ingredient') ||
									(aa.item_id = p_.id AND aa.type='product')
						
						WHERE ba.inc_qty<0 AND ba.booking_id=" . $data['id'];
				break;
			case 'student_to_school':
				$sql = "SELECT
						s.id as s_id,
						s.first_name as first_name,
						s.last_name as last_name,
						c.id as c_id,
						c.name as c_name
						FROM student as s
						INNER JOIN class_ as c ON s.class_id = c.id";

				if (isset($data['condition'])) :

					foreach ($data['condition'] as $key => $value) :

						$indexCol = $value[0];
						$related_op = $value[1];
						$dataValue = $value[2];
						$logical_op = !empty($value[3]) ? $value[3] : "";

						$clause .= $indexCol . " " . $related_op . " " . $dataValue;

						if (!empty($logical_op)) :
							$clause .= " " . $logical_op . " ";
						endif;

					endforeach;

					$sql .= (!empty($clause) ? " WHERE " . $clause : "");

					$sql = preg_replace('/\s+/', ' ', $sql);

				endif;

				break;

			case 'allergens_assoc':
				$sql = "SELECT a.* FROM allergens_assoc as aa LEFT JOIN allergen as a ON aa.aid = a.id WHERE aa.item_id = " . $data['id'] . " AND aa.type='" . $data['type'] . "'";
				break;

			case 'dietary_assoc':
				$sql = "SELECT d.* FROM dietary_assoc as da LEFT JOIN dietary as d ON da.did = d.id WHERE da.pid = " . $data['id'];
				break;

			case 'category_to_cat_assoc':
				$sql = "SELECT ca.* FROM cat_assoc as ca LEFT JOIN category as c ON ca.cid = c.id";
				break;

			case 'optionset_assoc_to_category':
				$sql = "SELECT c.*, oa.id as osid, oa.osid as os FROM optionset_assoc as oa LEFT JOIN category as c ON oa.cid = c.id WHERE oa.osid = " . $data['id'];
				break;

			case 'optionset_assoc_to_option':
				$sql = "SELECT o.*, oa.id as osid, oa.osid as os FROM optionset_assoc as oa LEFT JOIN option as o ON oa.oid = o.id WHERE oa.osid = " . $data['id'];
				break;

			case 'pantry_to_cat_assoc':
				$sql = "SELECT p.* FROM pantry p LEFT JOIN cat_assoc c ON p.id = c.pid AND c.cid = " . $data['id'] . " WHERE p.name LIKE '%" . $data['keyword'] . "%' AND c.pid IS NULL";
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
				else $clause = "AND oa.osid != " . $data['id'];
				$sql = "SELECT o.* FROM option o LEFT JOIN optionset_assoc oa ON o.id = oa.oid WHERE o.name LIKE '%" . $data['keyword'] . "%' " . $clause;
				break;

			case 'cat_to_optionset_assoc':
				if ($data['id'] == 0) $clause = "";
				else $clause = "AND oa.osid != " . $data['id'];
				$sql = "SELECT c.* FROM category c LEFT JOIN optionset_assoc oa ON c.id = oa.cid WHERE c.name LIKE '%" . $data['keyword'] . "%' " . $clause;
				break;

			case 'cat_assoc_to_pantry':
				$sql = "SELECT p.id as pid, p.name, p.image, ca.id as caid FROM cat_assoc as ca LEFT JOIN pantry as p ON p.id = ca.pid WHERE ca.cid = " . $data['id'];
				break;

			case 'product_to_ingredient_assoc':
				$sql = "SELECT i.* FROM ingredient_assoc as ia LEFT JOIN ingredient as i ON ia.inid = i.id WHERE ia.item_id = " . $data['id'];
				break;

			case 'product_to_allergens_assoc':
				$sql = "SELECT a.* FROM allergens_assoc as aa LEFT JOIN allergen as a ON aa.aid = a.id WHERE aa.item_id = " . $data['id'];
				break;

			case 'sizing':
				if ($data['id'] == 0) $clause = "";
				else $clause = "AND s.pid != " . $data['id'];
				$sql = "SELECT * FROM product WHERE name LIKE '%" . $data['keyword'] . "%' GROUP BY id";
				break;

			case 'sizing_assoc':
				$sql = "SELECT s.*, p.name as p_name FROM sizing as s LEFT JOIN product as p ON s.pid = p.id";
				break;

			case 'sizing_listing_assoc':
				$sql = "SELECT s.*, p.name as p_name FROM sizing as s LEFT JOIN product as p ON s.pid = p.id WHERE LOWER(s.name) LIKE LOWER('%" . $data['keyword'] . "%') AND s.pid = " . $data['id'];
				break;
			case 'sizing_from_listing':
				$sql = "SELECT
						s.*
						FROM
						listing as l
						LEFT JOIN product_option_assoc as poa ON poa.item_id = l.id
						LEFT JOIN sizing_assoc as sa ON sa.poa_id = poa.id
						LEFT JOIN sizing as s ON s.id = sa.sizing_id
						WHERE l.id = " . $data['id'];

				break;
			case 'option_assoc_to_pantry':
				#$sql = "SELECT p.id as pid, p.name, p.image, oa.id as oaid FROM option_assoc as oa LEFT JOIN product as p ON p.id = od.item_id WHERE oa.oid = ".$data['id'];
				// $sql = "( 
				// 			SELECT p.id AS pid, p.name, p.image, oa.id AS oaid, 'product' AS type 
				// 			FROM option_assoc AS oa 
				// 			LEFT JOIN product AS p ON p.id = oa.oid WHERE oa.item_id = {$data['id']} 
				// 			AND p.id IS NOT NULL 
				// 		) 
				// 		UNION ALL 
				// 		( 
				// 			SELECT i.id AS pid, i.name, i.image, oa.id AS oaid, 'ingredient' AS type 
				// 			FROM option_assoc AS oa 
				// 			LEFT JOIN ingredient AS i ON i.id = oa.oid WHERE oa.item_id = {$data['id']}
				// 			AND i.id IS NOT NULL 
				// 		);";
				$sql = "SELECT
						COALESCE(p.id, i.id) as pid,
						COALESCE(p.name, i.name) as p_name,
						oa.type as p_type,
						p.*,
						i.*
						FROM
						option_assoc as oa
						LEFT JOIN product as p ON p.id = oa.oid AND oa.type = 'product'
						LEFT JOIN ingredient as i on i.id = oa.oid AND oa.type = 'ingredient'
						WHERE oa.item_id = " . $data['id'];
				break;
			case 'optionset_assoc_to_pantry':
				$sql = "SELECT 
						COALESCE(p.id, i.id) as pid,
						COALESCE(p.name, i.name) as p_name,
						oa.type as p_type
						FROM optionset_assoc as osa
						LEFT JOIN option_assoc as oa ON oa.oid=osa.oid
						LEFT JOIN product as p ON p.id = oa.item_id AND oa.type = 'product'
						LEFT JOIN ingredient as i on i.id = oa.item_id AND oa.type = 'ingredient'
						WHERE osa.osid = " . $data['id'];

				break;
			case 'listing_to_sizing_assoc':
				$sql = "SELECT s.* FROM sizing as s WHERE s.item_id = " . $data['id'];
				break;

			case 'listing_to_serving_temp':
				$sql = "SELECT st.*, sta.id as sta_id FROM serving_temp_assoc as sta LEFT JOIN serving_temp as st ON sta.stid = st.id WHERE sta.item_id = " . $data['id'];
				break;

			case 'listing_to_product_addon':
				#$sql = "SELECT p.*, p.id as p_id FROM addon as a LEFT JOIN product as p ON p.id = a.item_id WHERE a.type = 'addon-product' AND a.lid = ".$data['id'];
				$sql = "SELECT
						p.*,
						COALESCE (a.id) as addon_id
						FROM addon as a
						LEFT JOIN product as p ON a.item_id = p.id AND a.type = 'addon-product'
						WHERE lid = " . $data['id'];
				break;

			case 'listing_to_option_assoc':
				$sql = "SELECT o.*, o.id as o_id, oa.id as oa_id, oa.type as oa_type, oa.sort as oa_sort FROM option_assoc as oa LEFT JOIN option as o ON oa.oid = o.id WHERE oa.item_id = " . $data['id'] . " ORDER BY oa.sort ASC";
				break;

			case 'optionset_from_optionset_assoc': #get the options of the optionset using optionset_assoc
				$sql = "SELECT o.* FROM optionset_assoc as osa LEFT JOIN option as o ON osa.oid = o.id WHERE osa.osid = " . $data['id'];
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
						WHERE oa.item_id = " . $data['id'] . "
						";
				break;

			case 'listing_to_addons':
				$sql = "SELECT
						p.*,
						COALESCE(a.id) as addon_id
						FROM addon as a
						LEFT JOIN product as p ON a.item_id = p.id AND a.type = 'addon-product'
						WHERE a.lid = " . $data['id'];
				break;

			case 'listing_to_product_option_assoc_sub_items':
				#$sql = "SELECT o.*, o.id as o_id, oa.id as oa_id, oa.type as oa_type, oa.sort as oa_sort FROM option_assoc as oa LEFT JOIN option as o ON oa.oid = o.id WHERE oa.item_id = ".$data['id']." ORDER BY oa.sort ASC";
				$sql = "SELECT
							poa.id as sub_poa_id,
							COALESCE(p.id, i.id) as sub_id,
							COALESCE(p.name, i.name) as sub_item_name,
							poa.opt_parent_id as sub_item_opt_parent_id,
							poa.opt_id as sub_item_opt_id,
							poa.item_id as sub_item_id,
							poa.min as sub_item_min,
							poa.max as sub_item_max,
							poa.allow_extra as sub_item_allow_extra,
							poa.type as sub_item_type,
							sa.inc_qty as sub_item_inc_qty,
							sa.additional_cost as sub_item_additional_cost,
							sa.extra_cost as sub_item_extra_cost
						FROM product_option_assoc AS poa
						LEFT JOIN product AS p ON poa.opt_id = p.id AND poa.type = 'recipes'
						LEFT JOIN ingredient as i on poa.opt_id = i.id AND poa.type = 'ingredient'
						LEFT JOIN sizing_assoc as sa ON poa.item_id = sa.item_id AND poa.opt_id = sa.opt_id AND poa.type = sa.type
						WHERE poa.item_id = " . $data['item_id'] . " AND poa.opt_parent_id = " . $data['opt_parent_id'] . " AND poa.opt_id != poa.opt_parent_id
						GROUP BY poa.opt_id
						ORDER BY poa.sort ASC;";
				break;

			case 'listing_to_product_option_assoc':
				#$sql = "SELECT o.*, o.id as o_id, oa.id as oa_id, oa.type as oa_type, oa.sort as oa_sort FROM option_assoc as oa LEFT JOIN option as o ON oa.oid = o.id WHERE oa.item_id = ".$data['id']." ORDER BY oa.sort ASC";
				// $sql = "SELECT 
				// 		    COALESCE(o.id, os.id) AS o_id,
				// 		    COALESCE(o.name, os.name) AS oa_name,
				// 		    poa.id AS oa_id,
				// 		    poa.opt_parent_id AS oa_opt_parent_id,
				// 		    poa.opt_id AS oa_opt_id,
				// 		    poa.type AS oa_type,
				// 		    poa.sort AS oa_sort,
				// 		    o.*,
				// 		    os.*
				// 		FROM product_option_assoc AS poa
				// 		LEFT JOIN option AS o ON poa.opt_id = o.id AND poa.type = 'option'
				// 		LEFT JOIN optionset AS os ON poa.opt_id = os.id AND poa.type = 'optionset'
				// 		WHERE poa.item_id = ".$data['id']." AND o.name IS NOT NULL OR
				// 		poa.item_id = ".$data['id']." AND os.name IS NOT NULL
				// 		ORDER BY poa.sort ASC;";
				$sql = "SELECT 
						    COALESCE(p.id, i.id) AS opt_id,
						    COALESCE(p.name, i.name) AS opt_name,
						    poa.id AS poa_id,
						    poa.opt_parent_id AS poa_opt_parent_id,
						    poa.opt_id AS poa_opt_id,
						    poa.type AS poa_type,
						    poa.sort AS poa_sort
						FROM product_option_assoc AS poa
						LEFT JOIN product AS p ON poa.opt_id = p.id AND poa.type = 'recipes'
						LEFT JOIN ingredient AS i ON poa.opt_id = i.id AND poa.type = 'ingredient'
						WHERE poa.item_id = " . $data['id'] . " AND p.name IS NOT NULL OR
						poa.item_id = " . $data['id'] . " AND i.name IS NOT NULL
						ORDER BY poa.sort ASC;";
				break;

			case 'listing_to_product_option_assoc_custom_booking' :
				$sql = "SELECT poa.*, 
						sa.sizing_id, sa.inc_qty, sa.additional_cost, sa.extra_cost, sa.is_enabled, s.name AS s_name,
						COALESCE(p.id, i.id) AS pid,
						COALESCE(p.name, i.name) AS p_name
						FROM product_option_assoc AS poa 
						INNER JOIN sizing_assoc AS sa ON sa.poa_id = poa.id 
                        INNER JOIN sizing AS s ON s.id = sa.sizing_id
						LEFT JOIN ingredient AS i ON i.id = sa.opt_id AND sa.type = 'ingredient' 
						LEFT JOIN product AS p ON p.id = sa.opt_id AND sa.type = 'recipes'
						WHERE poa.item_id = " . $data['id'] . "
						GROUP BY sa.sizing_id";
				break;

			case 'listing_to_product_option_assoc_custom':
				$sql = "SELECT poa.*, 
						sa.sizing_id, sa.inc_qty, sa.additional_cost, sa.extra_cost, sa.is_enabled, s.name AS s_name,
						COALESCE(p.id, i.id) AS pid,
						COALESCE(p.name, i.name) AS p_name
						FROM product_option_assoc AS poa 
						INNER JOIN sizing_assoc AS sa ON sa.poa_id = poa.id 
                        INNER JOIN sizing AS s ON s.id = sa.sizing_id
						LEFT JOIN ingredient AS i ON i.id = sa.opt_id AND sa.type = 'ingredient' 
						LEFT JOIN product AS p ON p.id = sa.opt_id AND sa.type = 'recipes'
						WHERE poa.item_id = " . $data['id'];
				break;

			case 'listing_to_product_assoc':
				$sql = "SELECT p.*, pa.id as pa_id FROM product_assoc as pa LEFT JOIN product as p ON pa.pid = p.id WHERE pa.item_id = " . $data['id'];
				break;

			case 'listing_to_allergens_assoc':
				$sql = "SELECT a.* FROM listing as l LEFT JOIN product_assoc as pa ON pa.item_id = l.id LEFT JOIN allergens_assoc as aa ON pa.pid = aa.item_id LEFT JOIN allergen as a ON aa.aid = a.id WHERE l.id = " . $data['id'];
				break;

			case 'temp_booking_to_listing':
				$sql = "SELECT * 
						FROM temp_booking AS tb 
						LEFT JOIN listing AS li ON tb.item_id = li.id 
						WHERE tb.id = " . $data['id'];
				break;

			case 'booking_to_listing':
				//msl-035-lxbordo START
				//$sql = "SELECT l.*, b.id as b_id, b.delivery_location as delivery_location, b.delivery_date as delivery_date, b.delivery_time as delivery_time FROM booking as b LEFT JOIN listing as l ON b.item_id = l.id WHERE b.org_id = ".$data['id'];
				//msl-035-lxbordo END
				// $sql = "SELECT l.*, b.id as b_id, b.delivery_location as delivery_location, b.delivery_date as delivery_date, b.delivery_time as delivery_time FROM booking as b LEFT JOIN listing as l ON b.item_id = l.id WHERE b.org_id = ".$data['id']." AND ('".$data['date']."' BETWEEN l.start_date AND l.end_date)";
				if (isset($data['date'])) {
					$sql = "SELECT l.*, b.id as b_id, b.delivery_location as delivery_location, b.delivery_date as delivery_date, b.delivery_time as delivery_time FROM booking as b LEFT JOIN listing as l ON b.item_id = l.id WHERE b.org_id = " . $data['id'] . " AND b.delivery_date ='" . $data['date'] . "'";
				} else {
					if (isset($data['sb_id'])) {

						//looking for record with sb_id
						$sql = "SELECT l.*, b.id as b_id, b.delivery_location as delivery_location, b.delivery_date as delivery_date, b.delivery_time as delivery_time FROM booking as b LEFT JOIN listing as l ON b.item_id = l.id WHERE b.sb_id = " . $data['sb_id'];
					} else {
						//looking for record with org id
						$sql = "SELECT l.*, b.id as b_id, b.delivery_location as delivery_location, b.delivery_date as delivery_date, b.delivery_time as delivery_time FROM booking as b LEFT JOIN listing as l ON b.item_id = l.id WHERE b.org_id = " . $data['id'];
					}
				}
				break;


			case 'booking_to_listing_information':
				$sql = "SELECT l.*, b.id as b_id, b.delivery_location as delivery_location, b.delivery_date as delivery_date, b.delivery_time as delivery_time, b.price as b_price FROM booking as b LEFT JOIN listing as l ON b.item_id = l.id WHERE b.id = " . $data['id'];
				break;
			case 'booking_to_menu_information':
				$sql = "SELECT m.*, b.id as b_id, b.delivery_location as delivery_location, b.delivery_date as delivery_date, b.delivery_time as delivery_time, b.price FROM booking as b LEFT JOIN menu as m ON b.item_id = m.id WHERE b.id = " . $data['id'];
				break;
			case 'menu':
				$sql = "SELECT * FROM menu WHERE id = " . $data['id'];
				break;
			case 'listing':
				$sql = "SELECT * FROM listing WHERE id = " . $data['id'];
				break;
				//customize meal deal test
 
			case 'booking_assoc_to_listing_options':
				$sql = "SELECT ba.id,
						COALESCE(o.id, i.id, p.id) as opt_id,
						COALESCE(o.name, i.name, p.name) as opt_name,
						COALESCE(p.description, i.description) as opt_description,
						COALESCE(i.image, p.image, o.image) as opt_image,
						ba.extra_cost,
						ba.additional_cost,
						ba.min,
						ba.max,
						ba.inc_qty,
						ba.sizing_id
						FROM booking_assoc as ba
						LEFT JOIN optionset as o ON ba.item_id = o.id AND ba.type = 'optionset'
						LEFT JOIN ingredient as i ON ba.item_id = i.id AND ba.type = 'ingredient'
						LEFT JOIN product as p ON ba.item_id = p.id AND ba.type = 'recipes'
						WHERE ba.booking_id = " . $data['booking_id'];
				// $sql = "SELECT
				// 		os.*,
				// 		o.*,
				// 		COALESCE(os.id, o.id) as opt_id,
				// 		COALESCE(os.name, o.name) as opt_name,
				// 	    poa.sort as opt_sort,
				// 	    poa.type as opt_type
				// 		FROM booking_assoc as ba
				// 		LEFT JOIN product_option_assoc as poa ON ba.opt_parent_id = poa.opt_id
				// 		LEFT JOIN optionset as os ON poa.opt_id = os.id AND poa.type = 'optionset'
				// 		LEFT JOIN option as o ON poa.opt_id = o.id AND poa.type = 'option'
				// 		WHERE ba.booking_id = ".$data['id']." GROUP BY ba.opt_parent_id ORDER BY poa.sort ASC";
				break;

			case 'booking_assoc_items':
				$sql = "SELECT
						COALESCE(o.id, i.id, p.id) as opt_id,
						COALESCE(o.name, i.name, p.name) as opt_name,
						COALESCE(i.image, p.image) as opt_image,
						COALESCE(i.price, p.price) as opt_price
						FROM booking_assoc as ba
						LEFT JOIN option as o ON ba.item_id = o.id AND ba.type = 'optionset'
						LEFT JOIN ingredient as i ON ba.item_id = i.id AND ba.type = 'ingredient'
						LEFT JOIN product as p ON ba.item_id = p.id AND ba.type = 'product'
						WHERE ba.booking_id = " . $data['booking_id'] . " AND ba.opt_parent_id = " . $data['opt_parent_id'];
				break;

				//customize meal deal test
			default:
				break;
		endswitch;

		#return $sql;

		$retrieve = $model->retrieve($sql);
		return ($retrieve['code'] == 200) ? $assocTransformer->transform($retrieve, $data) : $retrieve;
	}

	public function custom_join_query_new($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		$sql = "";
		$clause = "";

		switch ($data['table']):

			case 'option_to_option_assoc':
				$sql = "SELECT
							oa.id as option_assoc_id,
							oa.type as option_assoc_type,
							COALESCE(p.id, i.id) as option_oid,
							COALESCE(p.name, i.name) as option_name
						FROM option_assoc as oa
						LEFT JOIN product as p ON oa.oid = p.id AND oa.type = 'product'
						LEFT JOIN ingredient as i ON oa.oid = i.id AND oa.type = 'ingredient'
						WHERE oa.item_id = " . $data['id'];
				break;

			case 'optionset_assoc_to_option':
				$sql = "SELECT
							osa.id as optionset_assoc_id,
							o.id as option_oid,
							o.name as option_name
						FROM optionset_assoc as osa
						LEFT JOIN option as o ON osa.oid = o.id
						WHERE osa.osid = " . $data['id'] . " AND osa.sb_id = " . $data['sb_id'];
				break;

			case 'supplier_location_image':
				$sql = "SELECT s.*, l.*, i.image
						FROM supplier s 
						LEFT JOIN location l ON s.id = l.supplier_id
						LEFT JOIN image i ON s.id = i.model_id";
				break;

			case 'supplier_to_image':
				$sql = "SELECT
							s.*,
							i.image
						FROM supplier s
						LEFT JOIN image i ON s.id = i.model_id";
				break;

			case 'supplier_menus':
				$sql = "SELECT
							*
						FROM menu
						WHERE sb_id = " . $data['id'];
				break;

			case 'supplier_menu_assoc_to_listings':
				$sql = "SELECT
							l.*
						FROM menu_assoc as ma
						LEFT JOIN listing as l ON ma.item_id = l.id
						WHERE ma.menu_id =" . $data['id'];
				break;

			case 'supplier_to_listings':
				$sql = "SELECT * FROM listing WHERE sb_id =" . $data['id'];
				break;

			case 'supplier_listings_to_details':
				$sql = "SELECT * FROM listing WHERE sb_id =" . $data['id'];
				break;

			default:
				break;
		endswitch;

		#return $sql;

		$retrieve = $model->retrieve($sql);
		return ($retrieve['code'] == 200) ? $assocTransformer->transform($retrieve, $data) : $retrieve;
	}

	public function update_pantry_details($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		if ($data['task'] == "add") :

			$retData = array(
				"model" => $data['table'],
				"action" => "insert",
				"insert" => array(
					"pid" => $data['pid'],
					($data['table'] == "allergens_assoc" ? "aid" : "did") => $data['id']
				)
			);

			$insertRow = $QueryTransformer->prepareQuery($retData);
			$insert = $model->insert($insertRow);

			return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;
		else:
			$retData = array(
				"model" => $data['table'],
				"action" => "delete",
				"condition" => array(
					array("pid", "=", $data['pid'], "AND"),
					array(
						($data['table'] == "allergens_assoc" ? "aid" : "did"),
						"=",
						$data['id']
					)
				)
			);

			$delRow = $QueryTransformer->prepareQuery($retData);
			$delete = $model->insert($delRow);

			return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;
		endif;
	}

	public function custom_update_assoc($data)
	{

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$assocTransformer = "{$modelClass}Transformer";
		$assocTransformer = new $assocTransformer();

		switch ($data['table']):

			case 'sizing_assoc':
				$retData = array(
					"model" => $data['table'],
					"action" => "retrieve",
					"retrieve" => "*",
					"condition" => array(
						array("sizing_id", "=", $data['fields']['sizing_id'], "AND"),
						array("item_id", "=", $data['fields']['item_id'], "AND"),
						array("opt_id", "=", $data['fields']['opt_id'], "AND"),
						array("type", "=", $data['fields']['type'])
					)
				);

				$retRow = $QueryTransformer->prepareQuery($retData);
				$retrieve = $model->retrieve($retRow);

				if ($retrieve['code'] == 200 && !empty($retrieve['data'])) :

					//update
					$id = $retrieve['data'][0]['id'];

					$insert_update_fields = array();

					if (isset($data['fields']['field'])) :
						$insert_update_fields['insert_update_field'] = $data['fields']['field'];
						$insert_update_fields['field'] = (empty($data['fields']['value'])) ? 0 : $data['fields']['value'];
					endif;

					$updateData = array(
						"model" => $data['table'],
						"action" => "update",
						"update" => array(
							"inc_qty" => $data['fields']['inc_qty'],
							"additional_cost" => $data['fields']['additional_cost'],
							"extra_cost" => $data['fields']['extra_cost'],
							"is_enabled" => $data['fields']['is_enabled']
						),
						"condition" => array(
							array("id", "=", $id)
						)
					);

					$updateQuery = $QueryTransformer->prepareQuery($updateData);
					$update = $model->update($updateQuery);

					return ($update['code'] == 200) ? $assocTransformer->transform($update, $data) : $update;

				else :

					//insert
					//format the insert value from data['condition'] array. data['condition'] must be in correct format to do this

					$insert_update_fields = array();

					// if (isset($data['fields']['field'])) :
					// 	$insert_update_fields['insert_update_field'] = $data['fields']['field'];
					// 	$insert_update_fields['field'] = (empty($data['fields']['value'])) ? 0 : $data['fields']['value'];
					// endif;

					$insertData = array(
						"model" => $data['table'],
						"action" => "insert",
						"insert" => array(
							"sizing_id" => $data['fields']['sizing_id'],
							"item_id" => $data['fields']['item_id'],
							"opt_id" => $data['fields']['opt_id'],
							"inc_qty" => $data['fields']['inc_qty'],
							"additional_cost" => $data['fields']['additional_cost'],
							"extra_cost" => $data['fields']['extra_cost'],
							"is_enabled" => $data['fields']['is_enabled'],
							"type" => $data['fields']['type']
						)
					);

					$insertQuery = $QueryTransformer->prepareQuery($insertData);
					$insert = $model->insert($insertQuery);

					return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;

				endif;

				break;

			case 'allergens_assoc':

				$fetchData = array(
					'model' => $data['table'],
					'action' => 'retrieve',
					'retrieve' => '*',
					'condition' => array(
						array('item_id', '=', $data['item_id'], 'AND'),
						array('aid', '=', $data['id'])
					)
				);

				$fetchQuery = $QueryTransformer->prepareQuery($fetchData);
				$fetch = $model->retrieve($fetchQuery);

				if (!empty($fetch['data'])) :
					$assoc_id = $fetch['data'][0]['id'];

					$delData = array(
						'model' => 'allergens_assoc',
						'action' => 'delete',
						'condition' => array(
							array('id', '=', $assoc_id)
						)
					);

					$deleteQuery = $QueryTransformer->prepareQuery($delData);
					$delete = $model->delete($deleteQuery);

					return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;
				else:

					$insertData = array(
						'model' => 'allergens_assoc',
						'action' => 'insert',
						'insert' => array(
							'item_id' => $data['item_id'],
							($data['table'] == "allergens_assoc") ? 'aid' : 'did' => $data['id']
						)
					);

					$insertQuery = $QueryTransformer->prepareQuery($insertData);
					$insert = $model->insert($insertQuery);

					return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;
				endif;

				break;

			case 'optionset_assoc':
				$retData = array(
					"model" => $data['table'],
					"action" => "update",
					"update" => array("osid" => $data['osid']),
					"condition" => array(
						array("id", "=", $data['id'])
					)
				);

				$updateRow = $QueryTransformer->prepareQuery($retData);
				$update = $model->update($updateRow);

				return ($update['code'] == 200) ? $assocTransformer->transform($update, $data) : $update;

				break;

			case 'allergens_assoc':

				$fetchData = array(
					'model' => $data['table'],
					'action' => 'retrieve',
					'retrieve' => '*',
					'condition' => array(
						array('item_id', '=', $data['item_id'], 'AND'),
						array('aid', '=', $data['id'])
					)
				);

				$fetchQuery = $QueryTransformer->prepareQuery($fetchData);
				$fetch = $model->retrieve($fetchQuery);

				if (!empty($fetch['data'])) :
					$assoc_id = $fetch['data'][0]['id'];

					$delData = array(
						'model' => 'allergens_assoc',
						'action' => 'delete',
						'condition' => array(
							array('id', '=', $assoc_id)
						)
					);

					$deleteQuery = $QueryTransformer->prepareQuery($delData);
					$delete = $model->delete($deleteQuery);

					return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;
				else:

					$insertData = array(
						'model' => 'allergens_assoc',
						'action' => 'insert',
						'insert' => array(
							'item_id' => $data['item_id'],
							($data['table'] == "allergens_assoc") ? 'aid' : 'did' => $data['id']
						)
					);

					$insertQuery = $QueryTransformer->prepareQuery($insertData);
					$insert = $model->insert($insertQuery);

					return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;
				endif;

				break;

			case 'ingredient_assoc':

				$fetchData = array(
					'model' => $data['table'],
					'action' => 'retrieve',
					'retrieve' => '*',
					'condition' => array(
						array('item_id', '=', $data['item_id'], 'AND'),
						array('inid', '=', $data['id'])
					)
				);

				$fetchQuery = $QueryTransformer->prepareQuery($fetchData);
				$fetch = $model->retrieve($fetchQuery);

				if (!empty($fetch['data'])) :

					$delData = array(
						'model' => 'ingredient_assoc',
						'action' => 'delete',
						'condition' => array(
							array('id', '=', $fetch['data'][0]['id'])
						)
					);

					$deleteQuery = $QueryTransformer->prepareQuery($delData);
					$delete = $model->delete($deleteQuery);

					return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;

				else:

					$insertData = array(
						'model' => 'ingredient_assoc',
						'action' => 'insert',
						'insert' => array(
							'item_id' => $data['item_id'],
							'inid' => $data['id']
						)
					);

					$insertQuery = $QueryTransformer->prepareQuery($insertData);
					$insert = $model->insert($insertQuery);

					return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;

				endif;

				break;

			case 'serving_temp_assoc':

				$fetchData = array(
					'model' => $data['table'],
					'action' => 'retrieve',
					'retrieve' => '*',
					'condition' => array(
						array('stid', '=', $data['stid'], 'AND'),
						array('item_id', '=', $data['item_id'])
					)
				);

				$fetchQuery = $QueryTransformer->prepareQuery($fetchData);
				$fetch = $model->retrieve($fetchQuery);

				if (!empty($fetch['data'])) :

					$deleteData = array(
						'model' => 'serving_temp_assoc',
						'action' => 'delete',
						'condition' => array(
							array('id', '=', $fetch['data'][0]['id'])
						)
					);

					$deleteQuery = $QueryTransformer->prepareQuery($deleteData);
					$delete = $model->delete($deleteQuery);

					return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;

				else:

					$insertData = array(
						'model' => 'serving_temp_assoc',
						'action' => 'insert',
						'insert' => array(
							'item_id' => $data['item_id'],
							'stid' => $data['stid']
						)
					);

					$insertQuery = $QueryTransformer->prepareQuery($insertData);
					$insert = $model->insert($insertQuery);

					return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;

				endif;

				break;

			case 'product_assoc':

				$fetchData = array(
					'model' => $data['table'],
					'action' => 'retrieve',
					'retrieve' => '*',
					'condition' => array(
						array('pid', '=', $data['pid'], 'AND'),
						array('item_id', '=', $data['item_id'])
					)
				);

				$fetchQuery = $QueryTransformer->prepareQuery($fetchData);
				$fetch = $model->retrieve($fetchQuery);

				if (!empty($fetch['data'])) :

					$deleteData = array(
						'model' => 'product_assoc',
						'action' => 'delete',
						'condition' => array(
							array('id', '=', $fetch['data'][0]['id'])
						)
					);

					$deleteQuery = $QueryTransformer->prepareQuery($deleteData);
					$delete = $model->delete($deleteQuery);

					return ($delete['code'] == 200) ? $assocTransformer->transform($delete, $data) : $delete;

				else:

					$insertData = array(
						'model' => 'product_assoc',
						'action' => 'insert',
						'insert' => array(
							'pid' => $data['pid'],
							'item_id' => $data['item_id']
						)
					);

					$insertQuery = $QueryTransformer->prepareQuery($insertData);
					$insert = $model->insert($insertQuery);

					return ($insert['code'] == 200) ? $assocTransformer->transform($insert, $data) : $insert;

				endif;

				break;

			case 'option_assoc_sort':

				$updateData = array(
					'model' => 'product_option_assoc',
					'action' => 'update',
					'update' => array(
						'sort' => $data['sort']
					),
					'condition' => array(
						array('opt_id', '=', $data['opt_id'], 'AND'),
						array('item_id', '=', $data['item_id'])
					)
				);

				$updateQuery = $QueryTransformer->prepareQuery($updateData);
				$update = $model->update($updateQuery);

				return ($update['code'] == 200) ? $assocTransformer->transform($update, $data) : $update;

				break;

			default:
				$retData = array();
				break;
		endswitch;
	}

	/* Remove for now - Ernest */

	// public function custom_update_assoc($data) {

	// 	global $QueryTransformer;
	// 	global $modelClass;
	// 	global $model;

	// 	$assocTransformer = "{$modelClass}Transformer";
	// 	$assocTransformer = new $assocTransformer();

	// 	switch($data['table']) :



	// 		default:
	// 			$retData = array();
	// 			break;
	// 	endswitch;

	// }
}
