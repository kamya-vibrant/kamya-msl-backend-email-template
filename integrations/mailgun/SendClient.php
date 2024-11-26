<?php
// require_once('config/config.php');
// require 'vendor/autoload.php';
// $logger = require 'service/logger.php';

// use Mailgun\Mailgun;
// use Mailgun\HttpClient\HttpClientConfigurator;
// class SendClient {
//     public function sendHTMLEmail($to, $subject, $body) {
//         global $logger;
//         $logger->debug("{SendClient} sendHTMLEmail begin:: key: " . MAILGUN_API_KEY . ",domain: " . MAILGUN_DOMAIN);
//         $configurator = new HttpClientConfigurator();
//         $configurator->setApiKey(MAILGUN_API_KEY);
//         $mgClient = new Mailgun($configurator); //Mailgun::create(API_KEY); // Initialize Mailgun
//         $responseObject = new stdClass();
//         try {
//             $response = $mgClient->messages()->send(MAILGUN_DOMAIN, [
//                 'from'    => 'VibrantBrands <no-reply@vibrantbrands.com.au>',
//                 'to'      => $to,
//                 'subject' => $subject,
//                 'html'    => $body
//             ]);
//             $logger->debug("Email sent! Message ID" . $response->getId());
//             $responseObject->code = 200;
//             $responseObject->message = "Email message successfully sent.";
//             $responseObject->responseId = $response->getId();
//             return $responseObject;
//             //echo "Email sent! Message ID: " . $response->getId();
//         } catch (Exception $e) {
//             $logger->debug("Failed to send email: " . $e->getMessage());
//             $responseObject->code = 500;
//             $responseObject->message = "Failed to send email. " . $e->getMessage();
//             //echo "Failed to send email: " . $e->getMessage();
//             return $responseObject;
//         }
//         $logger->debug("{SendClient} sendHTMLEmail end:: result: " . $response->getId());
//     }
//     public function sendTextEmail($to, $subject, $body) {
//         global $logger;
//         $logger->debug("{SendClient} sendTextEmail begin:: key: " . MAILGUN_API_KEY . ",domain: " . MAILGUN_DOMAIN);
//         $configurator = new HttpClientConfigurator();
//         $configurator->setApiKey(MAILGUN_API_KEY);
//         $mgClient = new Mailgun($configurator); //Mailgun::create(API_KEY); // Initialize Mailgun
//         try {
//             $responseObject = new stdClass();
//             $response = $mgClient->messages()->send(MAILGUN_DOMAIN, [
//                 'from'    => 'VibrantBrands <no-reply@vibrantbrands.com.au>',
//                 'to'      => $to,
//                 'subject' => $subject,
//                 'text'    => $body
//             ]);
//             $logger->debug("Email sent! Message ID" . $response->getId());
//             //echo "Email sent! Message ID: " . $response->getId();
            
//             $responseObject->code = 200;
//             $responseObject->message = "Email message successfully sent.";
//             $responseObject->responseId = $response->getId();
//             $logger->debug("registration_credential_entry end:: " . print_r($responseObject, true));
//             return $responseObject;
//         } catch (Exception $e) {
//             $logger->debug("Failed to send email: " . $e->getMessage());
//             $responseObject->code = 500;
//             $responseObject->message = "Failed to send email. " . $e->getMessage();
            
//             $logger->debug("registration_credential_entry end:: " . print_r($responseObject, true));
//             return $responseObject;
//             //echo "Failed to send email: " . $e->getMessage();
//         }
//         $logger->debug("{SendClient} sendTextEmail end:: result: " . $response->getId());
//         //sendEmail('recipient@example.com', 'Test Subject', 'Hello, this is a test email.');
//     }
// }
?>