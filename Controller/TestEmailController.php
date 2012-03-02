<?php
App::uses('ProMailQueueAppController', 'ProMailQueue.Controller');
/**
 * MailQueues Controller
 *
 */
class TestEmailController extends ProMailQueueAppController {

    public $uses = 'ProMailQueue.MailMessage';

    public function index() {
        $this->autoRender = false;
        $email = new CakeEmail();
        
        //$email->from( array( 'foo@example.com'=>'Jonathan') );
        $email->from('joncutrer@gmail.com' );
        $email->to( 'joncutrer@gmail.com' );
        $email->subject( 'Example Email Injected for Testing' );
        $email->send('This is the body of the example email injected for testing purpose.');
        //pr($email);
        
    }
    
    public function compose() {
        if ($this->request->is('post')) {
            $email = new CakeEmail();
            $email->from( $this->data['MailMessage']['from'] );
            $email->to( $this->data['MailMessage']['to'] );
            $email->subject( $this->data['MailMessage']['subject'] );
            $sendSuccess = $email->send( $this->data['MailMessage']['message'] );
            
            if ( $sendSuccess ) {
                $this->Session->setFlash(__('The mail message has been queued.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The mail message could not be injected, Please, try again.'));
            }
        }
        $mailQueues = $this->MailMessage->MailQueue->find('list');
        $this->set(compact('mailQueues'));
    }
}
