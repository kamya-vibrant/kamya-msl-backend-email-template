<?php
// include "integrations/mailgun/SendClient.php";
// $logger = require 'service/logger.php';
// $sendClient = new SendClient();
// class EmailDeliveryController
// {
//     public function send($data)
//     {
//         global $logger;
//         global $sendClient;
//         $logger->debug("{EmailDeliveryController} send begin:: data" . print_r($data, true));
//         //$sendClient->sendEmail('lyndon@vibrantbrands.com.au', 'Test Subject', 'Hello, this is a test email.');
//         //$sendClient->sendTextEmail('lyndon@vibrantbrands.com.au', 'Test Subject', 'Hello, this is a test email.');
//         switch($data['parameter']['template']){
//             case 'registration_credential_setup' :
//                 $logger->debug("registration_credential_entry begin:: ");
//                 return $sendClient->sendHTMLEmail(
//                     $data['parameter']['recipient'],
//                     $data['parameter']['subject'], 
//                     "<html lang=\"en\">
//                         <head>
//                         <meta charset=\"UTF-8\">
//                         <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
//                         <title>Email Template</title>
//                         </head>
//                         <body style=\"margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;\">
//                         <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"background-color: #f4f4f4;\">
//                             <tr>
//                             <td align=\"center\" style=\"padding: 20px;\">
//                                 <table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"background-color: #ffffff; border-radius: 8px;\">
//                                 <tr>
//                                     <td align=\"center\" style=\"padding: 20px 0; background-color: #4CAF50; color: white; font-size: 24px; font-weight: bold;\">
//                                     Welcome to Vibrant Brands
//                                     </td>
//                                 </tr>
//                                 <tr>
//                                     <td style=\"padding: 20px; text-align: left; color: #333;\">
//                                     <p>Hi <strong>".ucFirst($data['parameter']['name'])."</strong>,</p>
//                                     <p>Thank you for signing up for our service. We're excited to have you on board!</p>
//                                     <p>Please setup your credentials by visiting this link:</p>
//                                     <p style=\"text-align: center;\">
//                                         <a href=\" " . $data['parameter']['url'] . " \" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;\">
//                                         Confirm Email
//                                         </a>
//                                     </p>
//                                     <p>If you did not sign up, please ignore this email.</p>
//                                     <p>Best regards,</p>
//                                     <p>Vibrant Brand Team</p>
//                                     </td>
//                                 </tr>
//                                 <tr>
//                                     <td align=\"center\" style=\"padding: 10px; background-color: #f4f4f4; color: #777; font-size: 12px;\">
//                                     &copy; 2024 Company Name. All rights reserved.
//                                     </td>
//                                 </tr>
//                                 </table>
//                             </td>
//                             </tr>
//                         </table>
//                         </body>
//                         </html>"
//                     );
//                 break;
//             default :
//                 break;
//         }
//         $logger->debug("{EmailDeliveryController} send end::");
//     }
// }

?>