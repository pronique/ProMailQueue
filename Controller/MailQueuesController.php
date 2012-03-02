<?php
App::uses('ProMailQueueAppController', 'ProMailQueue.Controller');
/**
 * MailQueues Controller
 *
 * @property MailQueue $MailQueue
 */
class MailQueuesController extends ProMailQueueAppController {

/**
 * Components
 *
 * @var array
 */
    public $components = array('Session');

/**
 * Helpers
 *
 * @var array
 */
    public $helpers = array('Html', 'Form', 'Time');
    
    public $uses = 'ProMailQueue.MailQueue';
    
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->MailQueue->recursive = 0;
		$this->set('mailQueues', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->MailQueue->id = $id;
		if (!$this->MailQueue->exists()) {
			throw new NotFoundException(__('Invalid mail queue'));
		}
		$this->set('mailQueue', $this->MailQueue->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MailQueue->create();
			if ($this->MailQueue->save($this->request->data)) {
				$this->Session->setFlash(__('The mail queue has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mail queue could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->MailQueue->id = $id;
		if (!$this->MailQueue->exists()) {
			throw new NotFoundException(__('Invalid mail queue'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MailQueue->save($this->request->data)) {
				$this->Session->setFlash(__('The mail queue has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mail queue could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->MailQueue->read(null, $id);
		}
	}

/**
 * toggleActive method - Pause/Resume Queue
 *
 * @param string $id
 * @param string $newState
 * @return void
 */
    public function toggleActive($id = null, $newState='' ) {

        $this->MailQueue->id = $id;
        if (!$this->MailQueue->exists()) {
            throw new NotFoundException(__('Invalid mail queue'));
        }
        
        if ( $newState == 'on' ) {
            $new_value = 1;
        } elseif( $newState == 'off' ) {
            $new_value = 0;
        } else {
            throw new InvalidArgumentException('$newState can only be on|off');
        }
        
        $this->{$this->modelClass}->saveField('is_active', $new_value );

        if ($this->request->is('post') || $this->request->is('put')) {
            //TODO move saveField call in here 
        } 
        $this->redirect(array('action' => 'view', $id ));
    }
    
/**
 * doEmpty method - Delete all messages in specified queue
 *
 * @param string $id
 * @param string $newState
 * @return void
 */
    public function doEmpty($id = null ) {

        $this->MailQueue->id = $id;
        if (!$this->MailQueue->exists()) {
            throw new NotFoundException(__('Invalid mail queue'));
        }
 
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->{$this->modelClass}->truncate(); 
        } 
        $this->redirect(array('action' => 'view', $id ));
    }
    
/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->MailQueue->id = $id;
		if (!$this->MailQueue->exists()) {
			throw new NotFoundException(__('Invalid mail queue'));
		}
		if ($this->MailQueue->delete()) {
			$this->Session->setFlash(__('Mail queue deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Mail queue was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
