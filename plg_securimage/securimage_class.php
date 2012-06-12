<?php
require (dirname ( __FILE__ ) . '/securimage/securimage.php');
jimport( 'joomla.session.session' );

class JSecurimage extends Securimage {
    protected function saveData() {        
        $session = JFactory::getSession();
        $session->set('securimage_code_value', $this->code, $this->namespace);
        $session->set('securimage_code_ctime', time (), $this->namespace);
    }
    
    protected function validate() {
        $code = $this->getCode ();
        // returns stored code, or an empty string if no stored code was found
        // checks the session and sqlite database if enabled
        
        if ($this->case_sensitive == false && preg_match ( '/[A-Z]/', $code )) {
            // case sensitive was set from securimage_show.php but not in class
            // the code saved in the session has capitals so set case sensitive
            // to true
            $this->case_sensitive = true;
        }
        
        $code_entered = trim ( (($this->case_sensitive) ? $this->code_entered : strtolower ( $this->code_entered )) );
        $this->correct_code = false;
        
        if ($code != '') {
            if ($code == $code_entered) {
                $this->correct_code = true;
                $session = JFactory::getSession();
                $session->clear('securimage_code_value', $this->namespace);
                $session->clear('securimage_code_ctime', $this->namespace);
            }
        }
    }
    
    protected function getCode() {
        $session = JFactory::getSession();
        $code = '';
        
        if ($session->has('securimage_code_value', $this->namespace) && trim ( $session->get('securimage_code_value', '', $this->namespace) ) != '') {
            if ($this->isCodeExpired ( $session->get('securimage_code_ctime', $this->namespace) ) == false) {
                $code = $session->get('securimage_code_value', '', $this->namespace);   
            }
        } else { /* no code stored in session or sqlite database, validation will fail */ }
        
        return $code;
    }
}