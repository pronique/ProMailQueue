<?php
/**
* ProMailQueueAppController class
* 
*/
class ProMailQueueAppController extends AppController {
    
    function beforeFilter() {
        
        // begin __developer_hack__
        if ( $layout = Configure::read('ProMailQueue.__developer_hack__.View.layout' ) ) {    
            $this->layout = $layout;       
        }    
        // end __developer_hack__
        
        // begin __developer_hack__
        if ( $scaffold = Configure::read('ProMailQueue.__developer_hack__.Controller.scaffold' ) ) {       
            if ( $scaffold !== false ) {
                $this->scaffold = $scaffold;
            }       
        }    
        // end __developer_hack__
    }
}

