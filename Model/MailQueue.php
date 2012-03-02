<?php
App::uses('ProMailQueueAppModel', 'ProMailQueue.Model');
//App::uses('MailMessage', 'ProMailQueue.Model');
/**
 * MailQueue Model
 *
 * @property AppModel $AppModel
 * @property Mail $Mail
 */
class MailQueue extends ProMailQueueAppModel {
    
    public $name = 'MailQueue';

    public $displayField = 'queue';

    public $order = array(
        'MailQueue.priority' =>'ASC'
    );
    
    /**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'queue' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)/*,
            'unique' => array(
                'rule' => array('unique')
            ) */
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MailMessage' => array(
			'className' => 'ProMailQueue.MailMessage',
			'foreignKey' => 'mail_queue_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
    
    public function truncate() {
        //TODO Not Implemented
        $this->MailMessage->deleteAll(array(
            'mail_queue_id'=>$this->id
        ));
        $this->saveField('mail_message_count', 0 );
    }
    
    /**
    * Returns first message in queue
    * and optionally removes it from the queue if $remove==true
    * 
    * @param bool $remove Delete switch
    * @returns array $message MailMessage record
    */
    public function shift( $remove=false ) {

        if ( empty($this->id) ) {
            throw new LogicException('Cannot call ::shift() without first setting $Model->id.');
        } 

        $delay = Configure::read('ProMailQueue.Mailer.delay') or 300;
        if ( Configure::read('debug') > 0 ) {
            $delay = 0;
        }

        $mFindOptions = array(
            'conditions'=>array(
                'MailMessage.mail_queue_id' => $this->id,  
                //'MailMessage.send_after <' => date('Y-m-d H:i:s'),  
                //'MailMessage.created <' => date('Y-m-d H:i:s', time() - $delay ),  
                //'MailMessage.updated <' => date('Y-m-d H:i:s', time() - $delay )  
                //TODO Add condition to introduce delay
            ),
            'order'=>array(
                'MailMessage.priority'=>'asc',
                'MailMessage.retries'=>'asc',
            )
        );
        
        $message = $this->MailMessage->find('first', $mFindOptions );
        
        //if $remove then delete message
        if ( !empty( $message ) && $remove===true ) {
            $this->MailMessage->delete( $message['MailMessage']['id'] );
        }
        
        return $message;
    }
    
    /**
    * Add a email message to the queue
    *     
    * @param mixed $mail
    */
    public function push( $mail ) {
        $mailClass = $this->MailMessage->alias;
        $mailMessage[$mailClass] = $mail;
        
        //Serialize all fields that begin with _
        foreach( $mailMessage[$mailClass] as $fieldKey=>$data) {
            if ( substr( $fieldKey , 0 , 1) == '_' ) {
                $mailMessage[$mailClass][$fieldKey] = serialize( $data );
            }   
        }
                
        //resolve mail_queue_id
        //if queue is defined as a valid queue name, it will override a preset mail_queue_id
        if ( !empty( $mailMessage[$mailClass]['queue'] ) ) {
            $mail_queue_id = $this->field(
                'id', 
                array('queue'=> $mailMessage[$mailClass]['queue'] )
            );
        }
        
        //if mail_queue_id is empty, set mail_queue_id as default queue id
        if ( empty( $mail_queue_id ) ) {
            $defaultQueueKeyname = Configure::read( PMQ_PLUGIN_NAME.'.Queue.defaultQueue' );
            $default_mail_queue_id = $this->field('id', array('queue'=> $defaultQueueKeyname ) );
            $mail_queue_id = $default_mail_queue_id;    
        }
        $mailMessage[$mailClass]['mail_queue_id'] = $mail_queue_id;    
        
        //read the queue by id, throw exception if not found
        $this->recursive = -1;
        if ( !$mailQueue = $this->read(null, $mail_queue_id ) ) {
            throw new OutOfRangeException(_d('pro_mail_queue','Invalid mail_queue_id specified' ) );
        }
        
        //messages injected through this method cannot preset created, updated
        unset( $mailMessage[$mailClass]['created'] );
        unset( $mailMessage[$mailClass]['updated'] );
        
        //if not specified, set the default message priority
        if ( empty( $mailMessage[$mailClass]['priority'] ) ) {
            $mailMessage[$mailClass]['priority'] = Configure::read( PMQ_PLUGIN_NAME.'.Queue.defaultMessagePriority' );    
        }
        
        pr( $mailQueue );
        pr( $mailMessage );
        $this->MailMessage->create();
        pr( $this->MailMessage->save( $mailMessage ) );
        
        //TODO Fire Event MailQueue.Queue.{QueueName}.push
    }

}
