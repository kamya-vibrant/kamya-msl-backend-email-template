<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";
include "transformers/User.php"; 
include "models/User.php";
include "models/Contact.php";
include "models/Address.php";
include "models/School.php";
include "models/Class_.php";

$model = new $modelClass();
$User = new User();
$Contact = new Contact();
$Address = new Address();
$School = new School();
$Class_ = new Class_();

class OrganiserController
{

	public function register($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $User;
		global $Contact;
		global $Address;
		global $School;
		global $frontend;

		$organiserTransformer = "{$modelClass}Transformer";
		$organiserTransformer = new $organiserTransformer();

		$currentDate = date("Y-m-d H:i:s");
		$vcode = $User->generateCode(10);

		$userData = array(
			'model' => 'user',
			'action' => 'register',
			'insert' => array(
				'phone' => $data['insert']['phone'],
				'position' => $data['insert']['position'],
				'email' => $data['insert']['email'],
				'usertype' => 3,
				'verification_char' => $vcode
			)
		);

		$query = $QueryTransformer->prepareQuery($userData);
		$user = $User->insert($query);

		$orgData = array(
			'model' => 'organiser',
			'action' => 'insert',
			'insert' => array(
				'owner_id' => $user['id'],
				'name'=> $data['insert']['name'], 
                // 'finance_contact_person'=> $data['insert']['finance_contact_person'],
                // 'finance_contact_email'=> $data['insert']['finance_contact_email'],
                // 'finance_contact_number'=> $data['insert']['finance_contact_number'],
                // 'bank_account_name'=> $data['insert']['bank_account_name'],
                // 'bank_account_bsb'=> $data['insert']['bank_account_bsb'],
                // 'bank_account_number'=> $data['insert']['bank_account_number'],
                // 'terms_and_conditions'=> $data['insert']['terms_and_conditions'],
                // 'signature'=> $data['insert']['imgSign_'],

			)
		);

		$query = $QueryTransformer->prepareQuery($orgData);

		$org = $model->insert($query);

		$contactData = array(
			'model' => 'contact',
			'action' => 'insert', 
			'insert' => array(
				'model_name' => 'organiser',
				'model_id' => $org['id'],
				'phone' => $data['insert']['phone'],
				'type' => 'organiser-primary'
			)
		);

		$query = $QueryTransformer->prepareQuery($contactData);
		$contact = $Contact->insert($query);

		// $addressData = array(
		// 	'model' => 'address',
		// 	'action' => 'insert', 
		// 	'insert' => array(
		// 		'model_name' => 'organiser',
		// 		'model_id' => $org['id'],
		// 		'address' => $data['insert']['organiser_address'],
		// 		'type' => 'organiser-primary'
		// 	)
		// );

		// $query = $QueryTransformer->prepareQuery($addressData);
		// $address = $Address->insert($query);
                              
		$schoolData = array(
			'model' => 'school',
			'action' => 'insert',
			'insert' => array(
				'name' => $data['insert']['school_name'],
				'org_id' => $org['id'],
				'owner_id' => $user['id'],
				'website'=> $data['insert']['school_name'],
				'address' => $data['insert']['school_address'],
				'lat' => $data['insert']['lat'],
				'lng' => $data['insert']['lng']
			)
		);

		$query = $QueryTransformer->prepareQuery($schoolData);

		$school = $model->insert($query);
		$contactData = array(
			'model' => 'contact',
			'action' => 'insert', 
			'insert' => array(
				'model_name' => 'school',
				'model_id' => $school['id'],
				'phone' => $data['insert']['school_phone'],
				'type' => 'school-primary'
			)
		);

		$query = $QueryTransformer->prepareQuery($contactData);
		$contact = $Contact->insert($query);

		$addressData = array(
			'model' => 'address',
			'action' => 'insert', 
			'insert' => array(
				'model_name' => 'school',
				'model_id' => $school['id'],
				'address' => $data['insert']['school_address'],
				'type' => 'school-primary'
			)
		);

		$query = $QueryTransformer->prepareQuery($addressData);
		$address = $Address->insert($query);

		// $class_lists = explode(',',$data['insert']['list_of_classes']);
		// foreach($class_lists as $val){
		// 	$ClassData = array(
		// 		'model' => 'class_',
		// 		'action' => 'insert',
		// 		'insert' => array(
		// 			'name' => trim($val),
		// 			'org_id' => $org['id'],
		// 			'school_id' => $school['id']
		// 		)
		// 	);

		// 	$query = $QueryTransformer->prepareQuery($ClassData);
		// 	$class_ = $Address->insert($query);
		// }
		

		$org['redirect'] = "{$frontend}/setpassword/{$vcode}";
		return ($org['code']==200) ? $organiserTransformer->transform($org, $data) : $org;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$organiserTransformer = "{$modelClass}Transformer";
		$organiserTransformer = new $organiserTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $organiserTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$organiserTransformer = "{$modelClass}Transformer";
		$organiserTransformer = new $organiserTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $organiserTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$organiserTransformer = "{$modelClass}Transformer";
		$organiserTransformer = new $organiserTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $organiserTransformer->transform($retrieve, $data) : $retrieve;

	}
	public function insertfile($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$financeTransformer = "{$modelClass}Transformer";
		$financeTransformer = new $financeTransformer();

		$retData = array(
			'model' => $data['model'],
			'action' => 'retrieve',
			'fields' => $data['fields'],
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retImg = $this->retrieve($retData);

		if($retImg['code']==200){
			$updateData = array(
				'model'=>$data['model'],
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

		return ($update['code']==200) ? $financeTransformer->transform($update, $data) : $update;
		
	}

}