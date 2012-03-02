<?php
App::uses('MailMessagesController', 'ProMailQueue.Controller');

/**
 * TestMailMessagesController *
 */
class TestMailMessagesController extends MailMessagesController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * MailMessagesController Test Case
 *
 */
class MailMessagesControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.mail_message', 'plugin.pro_mail_queue.mail_queue');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MailMessages = new TestMailMessagesController();
		$this->MailMessages->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MailMessages);

		parent::tearDown();
	}

}
