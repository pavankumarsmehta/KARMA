<?php
function SMTPMailSend($to, $subject, $message, $from, $from_name='HBAsales', $type = 'text/html', $ReplyTo = NULL, $cc = NULL, $bcc = NULL, $attment = NULL)
{
	$physical_path = "/home/hbasales/public_html/public/";	
	//echo $physical_path."/PHPMailer/class.phpmailer.php"; exit;
	require_once($physical_path."/PHPMailer/class.phpmailer.php");
	$mail_obj = new PHPMailer();
	//Tell PHPMailer to use SMTP
	$mail_obj->isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	//$mail_obj->SMTPDebug = 0;

	//Ask for HTML-friendly debug output
	$mail_obj->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail_obj->Host = 'smtp.gmail.com';
	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail_obj->Port = 465;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail_obj->SMTPSecure = 'ssl';

	//Whether to use SMTP authentication
	$mail_obj->SMTPAuth = true;

	$mail_obj->SMTPDebug = 0;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail_obj->Username = "gequaldev@gmail.com";

	//Password to use for SMTP authentication
	$mail_obj->Password = "Qu@lDev2@14#"; //"ufsexfjeymbnbdrs";
	//$mail_obj->Password = SMTP_EMAIL_PASSWORD;

	//Set who the message is to be sent from
	$mail_obj->setFrom('gequaldev@gmail.com', 'HBAsales');

	//Set an alternative reply-to address
	//$mail->addReplyTo('', ''); 

	//Set who the message is to be sent to
	$mail_obj->addAddress($to, $to);

	//Set who the message is to be sent to BCC
	if($bcc!='')
	{
		$mail_obj->AddBCC($bcc, $bcc);
	}

	//Set the subject line
	$mail_obj->Subject = $subject;

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail_obj->msgHTML($message);

	//send the message, check for errors

	//echo "<pre>";	print_r($mail_obj);	exit;
	if(!$mail_obj->Send())
	{
		unset($mail_obj);
		return false;
	}
	else
	{
		unset($mail_obj);
		return true;
	}
	## Send mail Via PHP Mailer Start #############################
}

?>