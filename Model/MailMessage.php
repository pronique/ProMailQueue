<?php
App::uses('ProMailQueueAppModel', 'ProMailQueue.Model');
/**
 * Mail Model
 *
 * @property MailQueue $MailQueue
 */
class MailMessage extends Model {

    public $name = 'MailMessage';    
    public $order = array(
        'MailMessage.priority' =>'ASC'
    );
    
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'MailQueue' => array(
			'className' => 'ProMailQueue.MailQueue',
			'foreignKey' => 'mail_queue_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
            'counterCache' => true
		)
	);
    
    
    public function updateError( $id, $errorStr ) {
        if ( empty($id) || empty($errorStr) ) {
            throw new InvalidArgumentException('Must provide $id and $errorStr when calling ::updateError');
        }
        $this->recursive = -1;
        $this->id = $id;
        $message = $this->read( null, $id );
        $this->saveField( 'last_error', $errorStr );
        $newRetries = (int) $message[$this->alias]['retries'] + 1;
        $this->saveField( 'retries', $newRetries );
    }
    
    public function beforeSave() {
        if ( !empty( $this->data[$this->alias]['_from'] ) ) {
            $this->data[$this->alias]['uniqueness'] = sha1( 
                $this->data[$this->alias]['_from'] 
                . $this->data[$this->alias]['_to'] 
                . $this->data[$this->alias]['subject']
                . $this->data[$this->alias]['message']
            );
        }
        return true;
    }
}
