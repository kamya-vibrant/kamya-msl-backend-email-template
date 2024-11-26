<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";
include "transformers/User.php"; 
include "models/User.php";
include "models/Contact.php";
include "models/Address.php";
include "service/Mail.php";


$model = new $modelClass();
$User = new User();
$Contact = new Contact();
$Address = new Address();
$Mail = new Mail();

class SupplierController
{

	public function register($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $User;
		global $Contact;
		global $Address;
		global $Mail;
		global $frontend;

		$supplierTransformer = "{$modelClass}Transformer";
		$supplierTransformer = new $supplierTransformer();

		$userTransformer = "UserTransformer";
		$userTransformer = new $userTransformer();

		$currentDate = date("Y-m-d H:i:s");

		$vcode = $User->generateCode(10);

		$user = array(
			'model' => 'user',
			'action' => 'register',
			'insert' => array(
				'first_name' => $data['insert']['first_name'],
				'last_name' => $data['insert']['last_name'],
				'email' => $data['insert']['email'],
				'usertype' => 2,
				'verification_char' => $vcode
			)
		);

		$query = $QueryTransformer->prepareQuery($user);
		$insert = $User->insert($query);

		$phone = $data['insert']['phone'];
		$address = $data['insert']['postal_address'];

		$first_name = $data['insert']['first_name'];
		$email = $data['insert']['email'];

		//$data['insert']['name'] = $data['insert']['first_name']." ".$data['insert']['last_name'];
		unset($data['insert']['first_name']);
		unset($data['insert']['last_name']);
		unset($data['insert']['phone']);
		unset($data['insert']['postal_address']);

		$data['insert']['owner_id'] = $insert['id'];

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);

		$contact = array(
			'model' => 'contact',
			'action' => 'insert', 
			'insert' => array(
				'model_name' => 'supplier',
				'model_id' => $insert['id'],
				'phone' => $phone,
				'type' => 'supplier-primary'
			)
		);

		$query = $QueryTransformer->prepareQuery($contact);
		$contact = $Contact->insert($query);

		$address = array(
			'model' => 'address',
			'action' => 'insert', 
			'insert' => array(
				'model_name' => 'supplier',
				'model_id' => $insert['id'],
				'address' => $address,
				'type' => 'supplier-primary'
			)
		);

		$query = $QueryTransformer->prepareQuery($address);
		$address = $Address->insert($query);

		$data['insert']['phone'] = $phone;
		$data['insert']['postal_address'] = $address;

		$mailS = new PHPMailer(true);
		try {

			$setUrl = "{$frontend}/setpassword/{$vcode}";
			$imageUrl = "{$frontend}/site/assets/images/image-1.png";

            // SMTP configuration
            $mailS->isSMTP();
            $mailS->Host = 'smtp.gmail.com';
            $mailS->SMTPAuth = true;
            $mailS->Username = 'developerexpert2024@gmail.com';
            $mailS->Password = 'bacdbmohpevcjcwf';
            $mailS->SMTPSecure = 'tls';   
            $mailS->Port = 587;

            // Email details
            $mailS->setFrom('developerexpert2024@gmail.com', 'OneHive');
            $mailS->addAddress($email, $first_name);

            $mailS->isHTML(true);
            $mailS->Subject = 'Welcome to OneHive';
            $mailS->Body = '<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
			    <table role="presentation" style="width: 100%; max-width: 600px; margin: 30px auto; background-color: #ffffff; border-spacing: 0; border-radius: 8px;">
			        <tr>
			            <td style="text-align: center; padding: 20px;">
			                <a href="'.$frontend.'"><img src="'.$imageUrl.'" /></a>
			            </td>
			        </tr>
			        <tr>
			            <td style="text-align: center; padding: 0 20px;">
			                <h2 style="margin: 0; color: #1ebc60;">Confirm Your Registration!</h2>
			            </td>
			        </tr>
			        <tr>
			            <td style="padding: 20px; color: #333333; font-size: 16px;">
			                <p>Dear '.$first_name.',</p>
			                <p>We are excited to have you get started! To complete your registration and activate your account, please confirm your email address by clicking the button below.</p>
			                <p style="text-align: center; margin-bottom: 0;">
			                    <a href="'.$setUrl.'" style="background: #1ebc60; color: white; padding: 10px 20px; text-align: center; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 15px;">Confirm Your Email</a>
			                </p>
			                <p>If you did not create an account with us, you can safely ignore this email.</p>
			                <p>If you have any questions or need assistance, please dont hesitate to <a href="mailto:support@example.com">contact us</a>.</p>
			            </td>
			        </tr>

			        <tr>
			            <td style="padding: 0;">
			                <hr style="border-color: #bbb; outline: none; background: transparent; border-style: solid; border-width: 0 0 1px;" />
			            </td>
			        </tr>
			        <tr>
			            <td style="padding: 20px; text-align: center;">
			                <p style="font-size: 12px; margin: 8px;">&copy; 2024 Your Company Name. All rights reserved.</p>
			                <p style="font-size: 12px; margin: 8px;"><a href="#">Unsubscribe</a> from our emails</p>
			            </td>
			        </tr>
			    </table>
			</body>';
            $mailS->send();
        } catch (Exception $e) {
            echo "Registration successful, but email could not be sent. Error: {$mailS->ErrorInfo}";
        }

		// $mail = array(
		// 	'mail_to' => 'developerexpert2024@gmail.com',
		// 	'subject' => 'My School Lunch - Registration Verification',
		// 	'body' => 'test'
		// );

		// $mail = $Mail->send($mail);

		//echo json_encode($frontend);
		//$data['']
		// $insert['redirect'] = "{$frontend}/setpassword/{$vcode}";
		$insert['redirect'] = "{$frontend}/register";
		return ($insert['code']==200) ? $userTransformer->transform($insert, $data) : $insert;

	}

	public function update($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$supplierTransformer = "{$modelClass}Transformer";
		$supplierTransformer = new $supplierTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$update = $model->update($query);
		return ($update['code']==200) ? $supplierTransformer->transform($update, $data) : $update;
	}

	public function delete($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$supplierTransformer = "{$modelClass}Transformer";
		$supplierTransformer = new $supplierTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);
		return ($delete['code']==200) ? $supplierTransformer->transform($delete, $data) : $delete;
	}

	public function retrieve($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$supplierTransformer = "{$modelClass}Transformer";
		$supplierTransformer = new $supplierTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);
		return ($retrieve['code']==200) ? $supplierTransformer->transform($retrieve, $data) : $retrieve;
	}

	public function insertimg($data){

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$supplierTransformer = "{$modelClass}Transformer";
		$supplierTransformer = new $supplierTransformer();

		$retData = array(
			'model' => $data['model'],
			'action' => 'retrieve',
			'fields' => $data['fields'],
			'condition' => $data['condition']
		);

		$retQuery = $QueryTransformer->prepareQuery($retData);
		$retImg = $model->retrieve($retQuery);

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

		return ($update['code']==200) ? $supplierTransformer->transform($update, $data) : $update;
	}

	public function upload_image_parts($data) {

		global $QueryTransformer;
		global $modelClass;
		global $model;

		$pantryTransformer = "{$modelClass}Transformer";
		$pantryTransformer = new $pantryTransformer();

		#test manual query
		$sql = "SELECT ".$data['fields'][0]." FROM `".$data['table']."` WHERE id=".$data['condition'][0][2];
		$res = $model->retrieve($sql);
		//return $sql;

		if ($res['code'] == 200) :

			$currentImageString = $res['data'][0][$data['fields'][0]];
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
					$data['fields'][0] => $newImageString
				),
				'condition' => $data['condition']
			);

			$updateQuery = $QueryTransformer->prepareQuery($updateImageString);
			$update = $model->update($updateQuery);

			return ($update['code']==200) ? $pantryTransformer->transform($update, $data) : $update;

		endif;
	}

}