<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";

class SendEmail{

private $message;
private $subj;
private $to_email;
private $to_name;
private $err_message;
private $attachment;
private $string_attachment;
public function __construct($message, $subj, $to_email, $to_name, $err_message,$attachment='', $string_attachment = '') {
    $this->message = $message;
    $this->subj = $subj;
    $this->to_email = $to_email;
    $this->to_name = $to_email;
    $this->err_message = $err_message;
    $this->attachment = $attachment;
    $this->string_attachment = $string_attachment;
}
function email_config($mail)
	{
		$mail->isSMTP();
		$mail->Host = $_ENV['MAILLER_HOST']; //'mail.example.com';
		$mail->SMTPAuth = true;
		$mail->Port = 587;
		$mail->Username = $_ENV['MAILLER_USERNAME']; //your email;
		$mail->Password = $_ENV['MAILLER_PASSWORD']; //your email password';
		$mail->SMTPDebug = false;    //Enable verbose debug output
        $mail->setFrom("", "");//from email and from name
        $mail->addReplyTo("", "");//from email and from name
	}

    function send_email()
	{
		try {
			$mail = new PHPMailer(true);
			email_config($mail);
			$mail->addAddress($this->to_email, $this->to_name);

			
			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = $subj;
			$mail->Body    = $message;
			// check if the attachment is not empty
			if($attachment!=''){
				// check if it is multiple attachment
				if(is_array($attachment)){
					for ($x = 0; $x < count($attachment); $x++) {
						$mail->addAttachment($attachment[$x]);
					}
				}else{
					$mail->addAttachment($attachment);
				}
			}
			if($string_attachment != ''){
				foreach($string_attachment as $k => $v){
					$mail->AddStringAttachment($v["file"], $v["pet_name"]);
				}
			}
			$mail->AltBody = strip_tags($message);

			$mail->send();
			return 1;
			// echo 'Message has been sent';
		} catch (Exception $e) {
			//echo $err_message . "{$mail->ErrorInfo}";
			// Mailer Error:{$mail->ErrorInfo}
		}
	}
}