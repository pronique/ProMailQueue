<?php
App::uses('MailQueue', 'ProMailQueue.Model');

/**
 * MailQueue Test Case
 *
 */
class MailQueueTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('plugin.pro_mail_queue.mail_queue', 'app.app_model');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MailQueue = ClassRegistry::init('MailQueue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MailQueue);

		parent::tearDown();
	}

}
