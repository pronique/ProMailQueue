<?php
App::uses('ProMailQueueAppController', 'ProMailQueue.Controller');
/**
 * MailMessages Controller
 *
 * @property MailMessage $MailMessage
 */
class MailMessagesController extends ProMailQueueAppController {

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

/**
* Models
* @var mixed
*/
    public $uses = 'ProMailQueue.MailMessage';

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->MailMessage->recursive = 0;
		$this->set('mailMessages', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->MailMessage->id = $id;
		if (!$this->MailMessage->exists()) {
			throw new NotFoundException(__('Invalid mail message'));
		}
		$this->set('mailMessage', $this->MailMessage->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MailMessage->create();
			if ($this->MailMessage->save($this->request->data)) {
				$this->Session->setFlash(__('The mail message has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mail message could not be saved. Please, try again.'));
			}
		}
		$mailQueues = $this->MailMessage->MailQueue->find('list');
		$this->set(compact('mailQueues'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->MailMessage->id = $id;
		if (!$this->MailMessage->exists()) {
			throw new NotFoundException(__('Invalid mail message'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MailMessage->save($this->request->data)) {
				$this->Session->setFlash(__('The mail message has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mail message could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->MailMessage->read(null, $id);
		}
		$mailQueues = $this->MailMessage->MailQueue->find('list');
		$this->set(compact('mailQueues'));
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
		$this->MailMessage->id = $id;
		if (!$this->MailMessage->exists()) {
			throw new NotFoundException(__('Invalid mail message'));
		}
		if ($this->MailMessage->delete()) {
			$this->Session->setFlash(__('Mail message deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Mail message was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
