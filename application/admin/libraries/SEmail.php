<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SEmail {

	public function __construct() {
	 	require_once APPPATH.'third_party/swift_mailer/swift_required.php';
		//require_once 'application/swiftmailer/swift_required.php'; 
	}


	function send_mail( $param = array( 'to' => '', 'subject' => '', 'from' => '' , 'body' => '' ) ) {		
		$to = $param['to'];
		$subject = $param['subject'];
		$from = $param['from'];
		$body = $param['body'];
		//Create the Transport 		
	 	$transport = Swift_SmtpTransport::newInstance ('smtp.gmail.com', 587, 'tls') 
	 									->setUsername('tempmccvs') 
	 									->setPassword('multimcc');
		//Create the message
	    $message = Swift_Message::newInstance();

	    //Give the message a subject
	    $message->setSubject($subject)
	                ->setFrom($from)
	                ->setTo($to)
	                ->setBody($body)
	                ->addPart('<q>'.$body.'</q>', 'text/html');

		//Create the Mailer using your created Transport
	    $mailer = Swift_Mailer::newInstance($transport);

        //Send the message
        return $mailer->send($message); 
    }
}