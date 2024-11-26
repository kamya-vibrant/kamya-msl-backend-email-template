<?php
include 'config/config.php';
include "transformers/{$modelClass}.php"; 
include "models/{$modelClass}.php";
include "service/Mail.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$model = new $modelClass();
$Mail = new Mail();

class UserController
{

	public function register($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$insert = $model->insert($query);
		return ($insert['code']==200) ? $userTransformer->transform($insert, $data) : $insert;
	}

	public function update($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;
		global $dbh;
		global $frontend;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$sql = "SELECT * FROM user WHERE verification_char = :value";
	    $stmt = $dbh->prepare($sql);
	    $value = $data['condition']['0']['2'];
	    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
	    $stmt->execute();
	    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$query = $QueryTransformer->prepareQuery($data);

		if(!empty($results)){
			$mailS = new PHPMailer(true);
			try {
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
	            $mailS->addAddress($results['0']['email'], $results['0']['first_name']);

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
				                <h2 style="margin: 0; color: #1ebc60;">Registration Confirmed!</h2>
				            </td>
				        </tr>
				        <tr>
				            <td style="padding: 20px; color: #333333; font-size: 16px;">
				                <p>Dear '.$results['0']['first_name'].',</p>
				                <p>Thank you for registering with us! We are excited to have you on board. Your registration has been successfully processed.</p>

				                <p><strong>Account Details:</strong></p>
				                <table role="presentation" width="100%" style="margin-top: 10px;">
				                    <tr>
				                        <td style="padding: 8px; width: 35%; border: 1px solid #ddd; background-color: #f9f9f9;">Username</td>
				                        <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;">'.$results['0']['first_name'].'</td>
				                    </tr>
				                    <tr>
				                        <td style="padding: 8px; width: 35%; border: 1px solid #ddd;">Email</td>
				                        <td style="padding: 8px; border: 1px solid #ddd;">'.$results['0']['email'].'</td>
				                    </tr>
				                </table>

				                <p>If you have any questions or need further assistance, feel free to <a href="mailto:support@example.com">contact our support team</a>.</p>

				                <p>We are looking forward to helping you make the most of your new account!</p>

				                <p style="text-align: center; margin-bottom: 0;">
				                    <a href="'.$frontend.'" style="background: #1ebc60; color: white; padding: 10px 20px; text-align: center; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 15px;">Go to Your Dashboard</a>
				                </p>
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
				                <p style="font-size: 12px; margin: 8px;"><a href="[Unsubscribe URL]">Unsubscribe</a> from our emails</p>
				            </td>
				        </tr>
				    </table>
				</body>';
	            $mailS->send();
	        } catch (Exception $e) {
	            echo "Registration successful, but email could not be sent. Error: {$mailS->ErrorInfo}";
	        }
	    }

		$update = $model->update($query);
		return ($update['code']==200) ? $userTransformer->transform($update, $data) : $update;
	}

	public function delete($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$delete = $model->delete($query);
		return ($delete['code']==200) ? $userTransformer->transform($delete, $data) : $delete;
	}

	public function retrieve($data){
		global $QueryTransformer;
		global $modelClass;
		global $model;

		$userTransformer = "{$modelClass}Transformer";
		$userTransformer = new $userTransformer();

		$query = $QueryTransformer->prepareQuery($data);

		$retrieve = $model->retrieve($query);
		return ($retrieve['code']==200) ? $userTransformer->transform($retrieve, $data) : $retrieve;
	}
}