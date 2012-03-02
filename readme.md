# MailQueue Plugin for CakePHP 2

> Development Status: Experimental (Not ready for use).

The ProMailQueue is a Email Queuing plugin that intercepts messages sent via CakeEmail
and queues them for delivery.  Messages are processed and delivered from the queue by 
`ProMailQueue.mailer`, a cake Shell that can be setup to run as a cronjob.

ProMailQueue supports multiple queues which can each has a priority, ie low, medium, high.
Message also have a priority within each queue.  Processing of messages from the queue is
delayed (5 minutes by default) so you can inspect the messages in the queue before they
are sent.  This is very benificial for debugging your application.

Messages are retried (4 times by default) before expiring from the queue.  This feature
makes sending email from your application more resilent to network issues.

Another benefit is that sending multiple emails via MailQueue is non-blocking so your 
application will scale.

## Documentation ##

[ProMailQueue Wiki](https://github.com/pronique/ProMailQueue/wiki)

## Installation ##

    sudo git clone https://github.com/pronique/ProMailQueue.git app/Plugin/ProMailQueue
    
    //Enable plugin in app/Config/bootstrap.php
    CakePlugin::load('ProMailQueue', array('bootstrap'=>true));
    
## Requirements ##

* PHP version: PHP 5.3+
* CakePHP version: 2.1
* [ProUtils Plugin](https://www.github.com/pronique/CakePHP-ProUtils-Plugin)

## License ##

Copyright 2011-2012, [PRONIQUE Software](http://pronique.com)

Licensed under [The MIT License](http://www.opensource.org/licenses/mit-license.php)<br/>
Redistributions of files must retain the above copyright notice.

--------------------------------------------------------------------------
ProMailQueue is Open Source Software created and managed by PRONIQUE Software.

http://www.pronique.com
