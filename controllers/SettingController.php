<?php

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$model = new $modelClass();

class SettingController
{

	public function insert($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$settingTransformer = "{$modelClass}Transformer";
		$settingTransformer = new $settingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		return ($insert['code']==200) ? $settingTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$settingTransformer = "{$modelClass}Transformer";
		$settingTransformer = new $settingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);

		return ($update['code']==200) ? $settingTransformer->transform($update, $data) : $update;

	}

	public function delete($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$settingTransformer = "{$modelClass}Transformer";
		$settingTransformer = new $settingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);

		return ($delete['code']==200) ? $settingTransformer->transform($delete, $data) : $delete;

	}

	public function retrieve($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$settingTransformer = "{$modelClass}Transformer";
		$settingTransformer = new $settingTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);

		return ($retrieve['code']==200) ? $settingTransformer->transform($retrieve, $data) : $retrieve;

	}
	public function requestpasswordreset($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$locationTransformer = "{$modelClass}Transformer";
		$locationTransformer = new $locationTransformer();

		$retData = array(
			'model' => 'supplier',
			'action' => 'retrieve',
			'fields' => ['owner_id'],
			'condition' => [['id','=',$data['requestpasswordreset']['supplier_id']]]
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retowner = $model->retrieve($retQuery);

		$mail = new PHPMailer(true);

	    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
	    $mail->isSMTP();                                           
	    $mail->Host = 'smtp.gmail.com';                     
	    $mail->SMTPAuth = true;                                   
	    $mail->Username = 'john@vibrantbrands.com.au';                  
	    $mail->Password = '-password-';           
	    $mail->SMTPSecure = 'TLS';          
	    $mail->Port = 587;         
	    $mail->setFrom('john@vibrantbrands.com.au', 'MySchoolLunch');
	    $mail->addAddress($data['requestpasswordreset']['email'], '');     

	    $mail->isHTML(true);                                 
	    $mail->Subject = 'Request Password Reset';
	    $mail->Encoding = 'base64';

	    $mail->Body = '<p><b>You requested to reset your password. If yes, click the link below</b></p>
	    			    <p><a href="'.$data['requestpasswordreset']['url'].'resetpassword?id='.$retowner['data'][0]['owner_id'].'">Reset Your Password</a></p>';

	    if($mail->send()){
	    	return array('code'=>200, 'message'=>'Email sent to '.$data['requestpasswordreset']['email']);
	    }
	}
}