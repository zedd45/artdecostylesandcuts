<?php
class EmailForm extends FrontendForm {
    
    public function __construct($name,$attributes=null){
        parent::__construct($name,$attributes);
        if (!parent::defaultState()){
            $this->sendClientEmail();
            session_destroy();
            $this->logUtil->enableConsole();
        }
    }
    
    public function __destruct(){
        parent::__destruct();
    }

    /**
	 * mails the $_SESSION values to a specified recipient
	 * @param String $recipients the email address(es) you want the mail sent to, separated by semi-colons
	 * @param String $subject Optional. The subject of the email.  Default: '<name of form> form submission from DOMAIN'. <strong>This must not contain any newline characters, or the mail may not be sent properly.</strong>
	 * @param String $body Optional.  Any additions you want appended to the body of the email
	 */
	public function emailClient($recipients,$subject=null,$body="") {
		$finalMsg = "";
		if (null==$subject){ 
		    $subject = "$this->formName form submission from " . DOMAIN;
		}
		
		foreach($_SESSION as $fieldLabel => &$value){
			$fieldLabel = trim($fieldLabel);
			
			if ($fieldLabel=='submitted' || $fieldLabel=='submit_x' || $fieldLabel=='submit_y' || $fieldLabel=='email_x' || $fieldLabel=='email_y'){
				continue;  //break from the loop w/o printing this input.
			}
			else if (eregi("TO:", $value) || eregi("CC:", $value) || eregi("CCO:", $value) || eregi("Content-Type", $value)){ 
			    exit("ERROR: Code injection attempt denied! Please don't use the following sequences in your message: 'TO:', 'CC:', 'CCO:' or 'Content-Type'.");
			} 
			else {   
				$finalMsg .= "$fieldLabel: $value\n";
			}
		}
		
		if (mail($recipients,$subject,$finalMsg)) {
			print("Thank you!  Your email has been sent.");
		}
		else {
			print("There was a problem processing your information.  Please try again later.<br/>  We apologize for the inconvenience.");
			$this->logUtil->error("Unable to send email to $recipients! Subject: $subject.  Message:$finalMsg");
		}
	}
	
	/*
	* helper function provides method overloading for emailClient function.
	*/
	public function sendClientEmail() {	
	    $this->emailClient(RECIPIENTS);
	}
}