<?php

/**
 * QueryTransformer = Use to transform data and convert into a query
 *
 * **/

class QueryTransformer{


	/*
	*
	*	Actions: insert, update, delete, retrieve
	*
	*/

	public function prepareQuery($data){

		$model = $this->modelTrim($data['model']);
		$sql = "";

		if($data['action'] == 'insert' || $data['action'] == 'register'){
			$queryParams = $this->transformData($data);
			$sql = "INSERT INTO $model $queryParams";
		}

		if($data['action'] == 'login' || $data['action'] == 'logout'){
			$model = ($data['action'] == 'logout') ? 'auth' : 'user';
			$keys = $this->transformFields($data);
			$condition = $this->transformCondition($data);
			$sql = "SELECT $keys FROM $model $condition";
		}

		if($data['action'] == 'update'){
			$queryParams = $this->transformData($data);
			$condition = $this->transformCondition($data);
			$sql = "UPDATE $model SET $queryParams $condition";
		}

		if($data['action'] == 'delete'){
			$condition = $this->transformCondition($data);
			$sql = "DELETE FROM $model $condition";
		}

		if($data['action'] == 'retrieve'){
			$keys = $this->transformFields($data);
			$condition = $this->transformCondition($data);
			$sql = "SELECT $keys FROM $model $condition";
		}

		if($data['action'] == 'insertimg'){
			$keys = $this->transformFields($data);
			$condition = $this->transformCondition($data);
			$sql = "SELECT $keys FROM $model $condition";
		}

		return $sql;
	}

	/**
	 * 
	 *
	 * **/

	public function transformData($data){

		$dataString = '';

		if(isset($data['update'])){
			foreach($data['update'] as $key => $value){
				if(trim($key)=='password'){ $value = sha1($this->decodePassword($value)); }
				$dataString .= ($dataString=="") ? "$key = '$value' " : ",$key = '$value' ";
			}
			$dateTime = date("Y-m-d H:i:s");
			$dataString .= (!empty($dataString)) ? ", updated_at = '$dateTime'" : '';
		}

		if(isset($data['insert'])){

			$keys = '';
			$values = '';

			foreach($data['insert'] as $key => $value){
				if(trim($key)=='password'){ $value = sha1($this->decodePassword($value)); }
				$keys .= ($keys=="") ? $key : ", $key";
				$values .= ($values=="") ? "'$value'" : ", '$value'";
			}

			$dateTime = date("Y-m-d H:i:s");
			$dataString = (!empty($keys)) ? " ($keys, created_at, updated_at) VALUES($values, '$dateTime', '$dateTime')" : '';
		}

		return $dataString;

	}

	/**
	 * 
	 *
	 * **/

	public function transformFields($data){

		$dataString = " * ";

		if(isset($data['fields'])){
			foreach($data['fields'] as $key => $value){
				$dataString .= ($dataString=="*") ? $value : ", $value ";
			}
		}

		return $dataString;

	}

	/**
	 * 
	 *
	 * **/

	public function transformCondition($data){

		$condition = '';

		if(isset($data['condition'])){
			foreach($data['condition'] as $key => $value){

				$rel_op = isset($value[3]) ? $value[3] : 'AND';
				$key = $value[0];
				$log_op = $value[1] ?? '=';
				$value = (trim($key)=='password') ? sha1($this->decodePassword($value[2])) : $value[2];

				$condition .= ($condition=='') ? " $key $log_op '$value'" : " $rel_op $key $log_op '$value'";
			}
		}

		return empty($condition) ? "" : " WHERE $condition";
	}

	/*
	*
	*	
	*
	*/

	public function transformRecord($stmt){

		$records = [];
		$count = 0;

		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

			$count++;
			$key = (isset($result['id'])) ? $result['id'] : $count;

			if(isset($result['password'])){
				unset($result['password']);
			}

		    $records[] = $result;
		}

		return json_encode($records);
	}

	/**
	 * 
	 *
	 * **/

    public function modelTrim($model){

   	   $plural_3 = substr(trim(strtolower($model)), -3);
   	   $plural_2 = substr(trim(strtolower($model)), -2);
   	   $plural_1 = substr(trim(strtolower($model)), -1);

   	   $newmodel = $model;

   	   if($plural_3=='ies'){
   	   		$newmodel = substr($model, 0, -3).'y';
   	   }

   	   if($plural_3=='ses'){
   	   		$newmodel = substr($model, 0, -2);
   	   }

   	   if($plural_2!='ss' && $plural_1=='s'){
   	   		$newmodel = substr($model, 0, -1);
   	   }

   	   return $newmodel;

    }

	/**
	 * 
	 *
	 * **/

    public function decodePassword($pwd){
   	   $clean = strtr( $pwd, ' ', '+');
	   return base64_decode( $clean );
    }

}

?>