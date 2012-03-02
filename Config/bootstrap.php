<?php
/**
* ProMailQueue Plugin bootstrap
* 
*/
defined('PMQ_CONFIG_FILENAME') or define( 'PMQ_CONFIG_FILENAME', 'ProMailQueue.php' );
defined('PMQ_PLUGIN_NAME') or define( 'PMQ_PLUGIN_NAME', 'ProMailQueue' );


//if debug mode, lets do some sanity checks
if ( Configure::read('debug') > 0 ) {
    if( !CakePlugin::loaded('ProUtils') ) {
        throw new RuntimeException(__('The %s plugin requires the ProUtils plugin.', PMQ_PLUGIN_NAME) );
    }
    if ( !file_exists(APP.'Config'.DS.'email.php') ) {
        throw new RuntimeException(__('%s will not run until you configure app/Config/email.php.',  PMQ_PLUGIN_NAME ) );
    }
}
    

/**
* default config  
* !!!!!!!!!!!!!  Do not edit these values  !!!!!!!!!!!!!!! 
* copy ProMailQueue.php.sample to app/Config/ProMailQueue.php
* to configure the Plugin.  Any options you omit will be default.  
*/
$_default_config = array(
    'CakeEmail'=>array(
        'autoLoad'=>true,
        'debug'=>false,
        'debugLogFile'=>'promailqueue.debug.log',
        'fireEvents'=> true
    ),
    'Queue'=>array(
        'fireEvents'=> true,
        'defaultQueue'=>'medium',
        'debug'=>false,
        'debugLogFile'=>'promailq.debug.log',
        'defaultQueuePriority'=>63,
        'defaultMessagePriority'=>63,
        'defaultEmailConfig'=>'default'
    ),
    'Mailer'=>array(
        'fireEvents'=> true,
        'delay'=>300,
        'limit'=>10,
        'maxRetries'=>4,
        'log'=>true,
        'debug'=>false,
        'debugLogFile'=>'promailq.debug.log',
        'defaultEmailConfig'=>'default'
    )    
);

//Check if config already set prior to loading plugin
$_preset_config = Configure::read(PMQ_PLUGIN_NAME);
if ( !empty( $_preset_config ) ) {
    //Merge existing config over default config
    Configure::write( PMQ_PLUGIN_NAME, 
        array_merge( $_default_config,  $_preset_config )
    );
} else { //else Merge app/Config/ProMailQueue.php over default config 
    Configure::write( PMQ_PLUGIN_NAME, $_default_config );
    if ( file_exists(APP.'Config'.DS.PMQ_CONFIG_FILENAME) ) {
        Configure::load(PMQ_CONFIG_FILENAME);    
    }    
}

if ( Configure::read( PMQ_PLUGIN_NAME.'.CakeEmail.autoLoad' ) === true ) {
    App::uses('CakeEmail', PMQ_PLUGIN_NAME.'.Lib/Network/Email');
}

define( 'PMQ_BOOTSTRAPPED', true );