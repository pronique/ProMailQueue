<?php
/**
 * Queue Sent mail using the ProMailQueue plugin
 *
 */

/**
 * Send mail using mail() function
 *
 * @package       ProMailQueue.Lib.Network.Email
 */


App::uses( 'MailQueue', 'ProMailQueue.Model');
//App::uses( 'MailMessage', 'ProMailQueue.Model');

class MailQueueTransport extends AbstractTransport {

/**
 * Send mail
 *
 * @param CakeEmail $email CakeEmail
 * @return array
 */
	public function send(CakeEmail $email) {
	
        $eol = PHP_EOL;
		if (isset($this->_config['eol'])) {
			$eol = $this->_config['eol'];
		}
        
		$headers = $email->getHeaders(array( 'from','sender', 'replyTo', 'readReceipt', 'returnPath', 'to', 'cc', 'bcc'));

        $from = $headers['From'];
        $to = $headers['To'];
		unset($headers['To']);
		$headers = $this->_headersToString($headers, $eol);
		$message = implode($eol, $email->message() );

        //Instance of MailQueue Model
        $MailQueue = new MailQueue();
        
        //Build and Insert message into mail queue
        $newmail['from'] = $from;
        $newmail['_from'] = $email->getAttribute('_from');
        $newmail['to'] = $to;
        $newmail['_to'] = $email->getAttribute('_to');
        $newmail['headers'] = $headers;
        $newmail['_headers'] = $email->getHeaders();
        $newmail['subject'] = $email->subject();
        $newmail['message'] = $message;
        $newmail['_message'] = $email->message();
        $newmail['_debug'] = $email->_pmq_debug;
        pr( $MailQueue->push( $newmail ) );
        exit; 
        
        
        if (ini_get('safe_mode') || !isset($this->_config['additionalParameters'])) {
			if (!@mail($to, $email->subject(), $message, $headers)) {
				throw new SocketException(__d('cake_dev', 'Could not send email.'));
			}
		} elseif (!@mail($to, $email->subject(), $message, $headers, $this->_config['additionalParameters'])) {
			throw new SocketException(__d('cake_dev', 'Could not send email.'));
		}
		return array('headers' => $headers, 'message' => $message);
	}

}
