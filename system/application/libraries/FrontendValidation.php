<?php 
/**
 * Server-side validation specifically for the front-end-form object
 *
 * @author Chris Keen
 */
class frontend_validation extends simple_validation {

    /**
	 * uses a regular expression to check a field's value.
	 * @param String $fieldName the field name used by the associative superglobal array.
	 * @param String $validation the regular expression to run
	 * @return boolean true if pattern matches, false otherwise
	 */
	public function patternMatches($fieldName,$validation){
		if (isSet($_SESSION[$fieldName])){
			return preg_match($validation,$_SESSION[$fieldName]);
		}
	}
	
	/**
	 * determines if the trimmed value is not the empty string.
	 * @param String $field the field name used by the associative superglobal array.
	 * @return boolean $Length if length is greater than 0
	 */
	public function checkFieldLength($field){
		if (isSet($_SESSION[$fieldName])){
			return strLen(trim($_SESSION[$fieldName]));
		}
		else
			return false;
	}
	
	/**
	 * Method to match the JQuery style validation on the back-end
	 * @param String $field the name of the field in the session that you want to check
	 * @param String $className the html attribute class that is passed into frontend_form.
	 */
	public function checkElementByClassName($field,$className){
		$classArray = split($className,' ');
		
		foreach ($classArray as $classItem){
			$pattern = $this->determinePattern($classItem);
			
			if($pattern && !$this->patternMatches($field,$pattern)){
				$this->determineErrorMessage($classItem);
			}
		}
	}
	
	/**
	 * determines what pattern to use for the pattern match method.
	 * @param String $className the html attribute class that is passed into frontend_form.
	 * @return boolean $patternFound returns false if the pattern can not be determined.  This prevents unnecessary Exceptions from unknown classes, which may be valid CSS.
	 */
	public function determinePattern($className){
		
	}
	
}
?>