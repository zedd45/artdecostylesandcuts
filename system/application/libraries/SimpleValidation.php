<?php

/**
* creates an object for reusable server side validation 
* @author Chris Keen
*/
interface simple_validation {
	/**
	* determines the Length of a field's value
	* @param String $field the field name used by the associative superglobal array.
	* @param String $errorMessage Optional.  if this is supplied, an error message will be generated to the EU
	* @return integer $Length 
	*/
	public function checkFieldLength($field,$errorMessage=null){
		if (isSet($_GLOBALS[$fieldName])){
			//if(DEBUG) print "$field's length is: " . strLen(trim($GLOBALS[$field]));
			return strLen(trim($GLOBALS[$field]));
		}
		else{
			return 0;
		}
	}
	
	/**
	* determines if the field holds the value you want, and prints an error message if not.
	* @param String $field the field name used by the GLOBALS array.
	* @param String $validation the regular expression to run
	* @param String $errorMessage the error message you want the EU to see on the screen
	*/
	public function checkFieldRegX($field,$validation,$errorMessage){
		if (!$this->patternMatches($fieldName,$validation)){
			$this->printError($errorMessage);
		}
	}
	
	/**
	* determines the boolean value of input and prints an error if the bool is false
	* @param String $logicalCheck the logical condition you want to evaluate
	* @param String $errorMessage the message you want the EU to see
	*/
	public function checkFieldLogic($logicalCheck,$errorMessage){
		if(!$logicalCheck){
			$this->printErrorMessage($errorMessage);
		}
	}
	
	/**
	* uses a regular expression to check a field's value.
	* @param String $fieldName the field name used by the associative superglobal array.
	* @param regular expression $regX the regular expression to match against
	* @return boolean true if pattern matches, false otherwise
	*/
	public function patternMatches($fieldName,$regX){
		if (isSet($GLOBALS[$fieldName])){
			return preg_match($regX,$GLOBALS[$fieldName]);
		}
		else 
			return false;
	}
	
	/**
	* prints the error message supplied, and increments the error counter
	* @param String $error the message you want printed to the screen
	*/
	public function printErrorMessage($error){
		print($error);
	}
}
?>