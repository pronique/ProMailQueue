<?php
App::uses('MailQueue', 'ProMailQueue.Model');
App::uses('CakeEmail', 'Network/Email');

class MailerShell extends AppShell {
    public $uses = array('ProMailQueue.MailQueue');
    
    /**
    * MailerConfig - see app/Plugin/ProMailQueue/Config
    * 
    * @var array
    */
    protected $_config;
    
    /**
    * Number of Messages to send
    * 
    * @var int
    */
    protected $_sendCount;
    
    /**
    * Total Number of Messages sent
    * 
    * @var int
    */
    protected $_sentCount = 0;

    /**
    * Total Number of Messages that failed to send
    * 
    * @var int
    */
    protected $_failureCount = 0;
   
    /**
    * Total Number of Messages sent and failed
    * 
    * @var int
    */
    protected $_totalCount = 0;
    
    /**
    * Last error string when CakeEmail failed to send a message
    * 
    * @var int
    */
    protected $_lastError;
     
    /**
    * Show welcome message, read config and parse command line switches;
    * 
    */
    public function startup() {
        //read MailerConfig
        $this->_config = Configure::read('ProMailQueue.Mailer');
        $this->_sendCount = $this->_config['limit'];
        $this->_sendCount = 3;
        $this->_config['delay'] = 0;

        $this->CakeEmail = new CakeEmail();
        
        //TODO parse command line switches
        
        //Say Hello
        $this->out( 'ProMailQueue.Mailer');
    }
    
    
    /**
    * main method
    * 
    */
    public function main() {
        $this->out();
        $this->out("Usage: cake ProMailQueue.mailer [--switches] [command] [args]");
        $this->out();
        $this->out("Examples:");
        $this->out("  cake ProMailQueue.mailer run                  Process all queues");
        $this->out("  cake ProMailQueue.mailer run high medium      Process selective queues");
        $this->out("  cake ProMailQueue.mailer list-messages        (Not Implemented) List message that will be delivered next");
        $this->out("  cake ProMailQueue.mailer list-messages high   (Not Implemented) List message in the high queue that will be delivered next");
        $this->out("  cake ProMailQueue.mailer send 433             (Not Implemented) Process one or more message by message id");
        $this->out();
    }
    
    /**
    * run method - Process Queues
    * 
    */
    public function run( ) {
        $this->out('Processing Queue(s)');
        $queueList = $this->args;

        if ( !empty( $queueList ) ) {
            unset( $queueList[array_search('debug', $queueList )] );
            $qFindParams = array(
                'conditions'=>array(
                    $this->{$this->modelClass}->alias.'.is_active'=> 1,
                    $this->{$this->modelClass}->alias.'.queue' => $queueList,
                    $this->{$this->modelClass}->alias.'.mail_message_count >' => 0
                ),
                'order'=>array(
                    $this->{$this->modelClass}->alias.'.priority' => 'asc'
                )
            );
        } else {
            $queueList = array('all');
            $qFindParams = array(
                'conditions'=>array(
                    $this->{$this->modelClass}->alias.'.is_active'=> 1,
                    $this->{$this->modelClass}->alias.'.queue != ' => 'debug',
                    $this->{$this->modelClass}->alias.'.mail_message_count >' => 0
                ),
                'order'=>array(
                    $this->{$this->modelClass}->alias.'.priority' => 'asc'
                )
            );
        }
        
        $queues = $this->MailQueue->find('all', $qFindParams );
        
        foreach( $queues as $queue ) {     
            $this->processQueue( $queue );
            
            //update last_processed
            $this->{$this->modelClass}->id = $queue[$this->modelClass]['id'];
            $this->{$this->modelClass}->saveField( 'last_processed', date('Y-m-d H:i:s') );
        
            if ( $this->_sentCount >= $this->_sendCount ) { break; }
        }
        
        $this->out();
    } //end run()
    
    /**
    * processQueue method
    * 
    * @param mixed $queue
    */
    protected function processQueue( $queue ) {
        $this->out( );
        $this->underline( 'Processing Queue "' . $queue[$this->modelClass]['queue'] . "\" (ID:" . $queue['MailQueue']['id'] . ', P:' . $queue['MailQueue']['priority'] . ')'  );    

        $this->{$this->modelClass}->id = $queue[$this->modelClass]['id'];
        
        while ( $this->_sentCount < $this->_sendCount  ) {
            
            $message = $this->{$this->modelClass}->shift();

            if ( empty($message) ) { 
                debug( 'No Messages to Process in ' . $queue[$this->modelClass]['queue'] );
                break; 
            }
            
            $result = $this->sendMessage( $message );
            //send success
            if ( $result === true ) {
                $this->_sentCount++;
                //delete message
                $message = $this->{$this->modelClass}->shift( true );
            } else { //send failure
                $this->_failureCount++;
                $this->_totalCount++;
                //update message retries and last_error
                $this->{$this->modelClass}->MailMessage->updateError(
                    $message['MailMessage']['id'],
                    $this->_lastError
                );
            }
        } //end while
        
    } //end processQueue

    /**
    * sendMessage method
    * 
    * @param mixed $message
    */
    protected function sendMessage( $message ) {
        pr('sendMessage');
        //TODO Send with CakeEmail 
        $this->CakeEmail->reset();
        $this->CakeEmail->from( unserialize( $message['MailMessage']['_from'] ) );
        $this->CakeEmail->to( unserialize( $message['MailMessage']['_to'] ) );
        $this->CakeEmail->subject( $message['MailMessage']['subject'] );
        try {
            $sendResult =  $this->CakeEmail->send( $message['MailMessage']['message'] );
            $result = true;
        } catch (Exception $e) {
            $result = false;
            $this->_lastError = $e->getMessage();
        }
        
        if ( $result != false && !empty( $sendResult ) ) {
            $result = true;
        }
        
        $this->out( 
            'Message ' 
            . "ID:" . str_pad( $message['MailMessage']['id'], 10, '0', STR_PAD_LEFT ) 
            . ", P:" .  $message['MailMessage']['priority']
            . ", Attempt:" . ( $message['MailMessage']['retries'] + 1 ) 
            . ", Sent:" . ( $result ? 'YES' : 'NO' ) 
            . "\tFrom:" . String::truncate( $message['MailMessage']['from'], 18 ) 
            . "\tTo:" . String::truncate( $message['MailMessage']['to'], 18 ) 
            . "\tSubject:" . String::truncate( $message['MailMessage']['subject'], 24 ) 
        );
        
        return $result;
    }
    
    /**
    * Underline a string
    * 
    * @param str $str
    * @param char $char
    */
    protected function underline( $str, $char='-' ) {
        $this->out( $str );    
        $this->out( str_repeat($char, strlen($str ) ) );    
    }
    
    /**
    * Draw a border around some output
    * 
    * @param str $str
    * @param char $char
    */
    protected function drawBox( $str, $padding=2, $topChar='\\', $rightChar='\\', $bottomChar='\\', $leftChar = '\\', $cornerChar=' ', $padChar=' ' ) {
        $strArr = split( "\n", $str );
        $longestLineLen = $strArr[0];
        foreach( $strArr as $line ) {
            if ( strlen($line) > $longestLineLen ) { $longestLineLen = strlen($line); }
        }
        //top border
        $this->out( $cornerChar . str_repeat($topChar, $longestLineLen + ( ($padding  *  $padding  ) ) ) . $cornerChar );    
        
        //top padding
        for( $i=1; $i <= $padding; $i++ ) {
            $this->out( 
                $leftChar . str_repeat( $padChar, $padding ) 
                . str_repeat( $padChar, $longestLineLen )
                . str_repeat( $padChar, $padding ) . $rightChar
            );    
        }
        
        //contents
        foreach( $strArr as $line ) { 
            $this->out( 
                $leftChar . str_repeat( $padChar, $padding ) 
                . str_pad( $line , $longestLineLen, $padChar )
                . str_repeat( $padChar, $padding ) . $rightChar
            );   
        }
        
        //top padding
        for( $i=1; $i <= $padding; $i++ ) {
            $this->out( 
                $leftChar . str_repeat( $padChar, $padding ) 
                . str_repeat( $padChar, $longestLineLen )
                . str_repeat( $padChar, $padding ) . $rightChar
            );    
        }
        
        //bottom border
        $this->out( $cornerChar . str_repeat($bottomChar, $longestLineLen + ( ($padding  *  $padding  )  ) ) . $cornerChar );    
    }
}