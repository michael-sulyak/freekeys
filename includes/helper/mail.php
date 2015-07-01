<?php

class Mail {
	public function send($arg) {
		global $me;

		$user = $me->config('user');

		$to = '<'.($arg['to'] ? $arg['to'] : $user['email']).'>';
		$subject = $arg['subject'];
		$message = wordwrap($arg['message'], 70, "\r\n");
		$headers = $arg['is_html'] ? 'Content-type: text/html;' : 'Content-type: text/plain;';
		$headers .= "charset=utf-8\r\n";
		if ($arg['from']) {
			$headers .= "From: <{$arg['from']}>\r\n".
    					"Reply-To: <{$arg['from']}>\r\n";
		} else {
			$headers .= "From: <".$me->config('email_admin').">\r\n".
    					"Reply-To: ".$me->config('email_admin')."\r\n" ;
		}

		mail($to, $subject, $message, $headers);
	}
}

?>