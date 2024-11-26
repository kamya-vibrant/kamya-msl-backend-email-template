<?php

class Mail
{

	/*
	*
	*	Actions: 
	*
	*/

    public function send($param){

		$to_email = $param['mail_to'];
		$subject = $param['subject'];
		$body = $param['body'];
		$headers = array(
	        'From: No reply',
	        'Content-Type: text/html'
        );
        $headers = "From: maverickvillar@gmail.com";
		$mail = mail($to_email, $subject, $body, $headers);//implode("\r\n",$headers)); 
		echo json_encode($mail);
		if ($mail) {
		    return "Email successfully sent to $to_email...";
		} else {
		    return "Email sending failed...";
		}

	}
}