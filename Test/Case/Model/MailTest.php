<?php
App::uses('Mail', 'ProMailQueue.Model');

/**
 * Mail Test Case
 *
 */
class MailTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('plugin.pro_mail_queue.mail', 'app.app_model');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Mail = ClassRegistry::init('Mail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mail);

		parent::tearDown();
	}

}
