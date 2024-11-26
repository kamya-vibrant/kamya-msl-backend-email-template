<?php

include "models/User.php";
include "models/Auth.php";
include "models/Supplier.php";
include "models/Setting.php";
include "transformers/User.php";  

require_once('config/config.php');

$model = new User();
$Auth = new Auth();
$Supplier = new Supplier();
$Setting = new Setting();
$logger = require 'service/logger.php';

class AuthController
{

	public $accessControls = array(
		

	);
	public function relatedAccounts_login($data){
		global $QueryTransformer;
		global $model;
		global $Auth;
		global $origin;
		global $logger;
		$csrf_token = $model->generateCode(12);
		$authData = array(
				'model' => 'auth',
				'action' => 'insert',
				'insert' => array(
					'user_id' => $data['id'],
					'csrf_token' => $csrf_token,
					'origin' => $origin,
					'logged_in' => date("Y-m-d H:i:s")
				)
			);

			$query = $QueryTransformer->prepareQuery($authData);

			$logger->debug("query: " . print_r($query, true));


			$res = $Auth->insert($query);
			$res['csrf']=$csrf_token;
			$res['redirect'] = MSL_PORTAL."/csrf/".$csrf_token;
			return $res;
	}
	public function login($data) {
		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $Auth;
		global $frontend;
		global $origin;
		global $logger;

		$logger->debug("AuthController::login begin: " . print_r($data, true));

		$userTransformer = "UserTransformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$logger->debug("query: " . print_r($query, true));

		$response = $model->login($query);

		$csrf_token = $model->generateCode(12);


		$logger->debug("csrf_token:: " . $csrf_token);

		if($response['code']==200 && isset($response['data'][0]['id'])){

			$uid = $response['data'][0]['id'];

			$authData = array(
				'model' => 'auth',
				'action' => 'insert',
				'insert' => array(
					'user_id' => $uid,
					'csrf_token' => $csrf_token,
					'origin' => $origin,
					'logged_in' => date("Y-m-d H:i:s")
				)
			);

			$query = $QueryTransformer->prepareQuery($authData);

			$logger->debug("query: " . print_r($query, true));


			$res = $Auth->insert($query);
			
			$records = array();

			if($res['code']==200){
				//msl-036 start
				$logger->debug("final response:: " . print_r($response, true));
				$usertype = $response['data'][0]['usertype'];
				$logger->debug("usertype:: " . $usertype);
				if($usertype == 2 || $usertype == 1) {    
					$response['redirect'] = ONEHIVE_PORTAL."/csrf/{$csrf_token}";
				} else if ($usertype == 3 || $usertype == 5) {
					$response['redirect'] = $frontend."/csrf/".$csrf_token;
				} else {
					// for 1 and 4 by default
					$response['redirect'] = $frontend."/csrf/".$csrf_token;
				}
				//msl-036 end
				#$response['redirect'] = str_replace(":3000",":3001","{$frontend}/csrf/{$csrf_token}"); // remove for now - Ernest
				#$response['redirect'] = "//dev-portal.vibrantbrands.com.au"; //manually set url to portal for now - Ernest
			}else{
				$response = array('code'=>201, 'message'=>'Invalid user!', 'data'=>$records); 
			}
		} 
		$logger->debug("AuthController::login end: " . print_r($response, true));
		return $response;

	}

	public function loggedData($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $Auth;
		global $Supplier;
		global $frontend;
		global $Setting;

		$userTransformer = "UserTransformer";
		$userTransformer = new $userTransformer();

		$csrf = $data['csrf'];

		$query = "SELECT a.user_id, a.csrf_token, a.origin, u.id, u.first_name, u.last_name, u.first_name, u.email, u.usertype FROM auth as a INNER JOIN user as u ON a.user_id = u.id WHERE a.csrf_token = '$csrf'";

		$res = $Auth->retrieve($query);

		if($res['code']==200 && isset($res['data'][0]['usertype'])){

			$res['isSupplier'] = ($res['data'][0]['usertype']==2) ? true : false;
			$res['isCustomer'] = ($res['data'][0]['usertype']==5) ? true : false;
			$res['isOrganiser'] = ($res['data'][0]['usertype']==3) ? true : false;
			$res['isAdmin'] = ($res['data'][0]['usertype']==1) ? true : false;

			$usertype = $res['data'][0]['usertype'];

			$acl = (array) $this->getAccessControls();
			$setting = (array) $Setting->fetchKeys();

			if(isset($acl[$usertype])){
				$res['acl'] = $acl[$usertype];
			}

			if(isset($setting[$usertype])){
				$res['setting_keys'] = $setting[$usertype];
			}
			if($res['data'][0]['usertype']==2 || $res['data'][0]['usertype']==3 || $res['data'][0]['usertype']==5){

				switch($res['data'][0]['usertype']){
					case 2: $type = 'supplier';
							break;
					case 3: $type = 'organiser';
							break;
					case 5: $type = 'customer';
							break;
				}

				$par = array(
					'model' =>  $type,
					'action' => 'retrieve',
					'condition' => array(
						array('owner_id', '=', $res['data'][0]['id'])
					)
				);

				$query = $QueryTransformer->prepareQuery($par);
				$result = $Supplier->retrieve($query);

				$res[$type] = $result['code']==200 ? $result['data']  : null;
			}

			$res['data'] = $res['data'][0];
		}

		return $res;
	}

	public function logout($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $Auth;
		global $frontend;

		$query = $QueryTransformer->prepareQuery($data);
		$response = $Auth->logout($query);

		if($response['code']==200){
			$par = array(
				'model' => 'auth',
				'action' => 'update',
				'update' => array(
					'logged_out' => date("Y-m-d H:i:s")
				),
				'condition' => $data['condition']
			);

			$query = $QueryTransformer->prepareQuery($data);
			$res = $Auth->update($query);

			if($res['code']==200){
				$response['redirect'] = $frontend; //remove for now - Ernest
				#$response['redirect'] = "//dev-frontend.vibrantbrands.com.au"; //manually set url to portal for now - Ernest
			}

		}

		return $response;

	}

	public function getAccessControls(){

		$string = file_get_contents(DEFUALT_DIRECTORY.ACL);
		return json_decode(stripslashes($string));

	}


}