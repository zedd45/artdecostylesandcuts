<?php
/**
 * This class extends & overrides methods of form to enable extra features for the front end.
 * It will incorporate some element attributes (class names) that will allow for front-end and back-end validation
 * The contents are displayed in the order they are added to the object - as they are printed immediately. 
 * @author chris keen <chris@keeninnovations.net>
 */
 //implements simple_validation {
class FrontendForm extends Form { 
	private $state="", $doNotPrintElementOnSubmit = array("submit","button","reset","hidden");
	protected $submittedLabel = "submitted";
	
	/*#### Standard Functions ####*/
	
	/**
	 * Creates a new instance of an html form.
	 * @param string $name The name attribute of the form element.
	 * @param string $attributes Optional.  Additional HTML attributes you want to add to the form
	 */
	public function __construct($name, $attributes=null) {
        parent::__construct($name,$attributes);
        parent::hiddenInput($this->submittedLabel,"1");
		//this helps ensure that we're printing the correct version of the form
		$this->checkSubmittedState();
	}
	
	public function __destruct(){
		parent::__destruct();
		unset($this->state);
	}
	
	
	/*#### setter & getter ####*/
	
	/**
	 * Changes the global action for the form - the "state" the form is in
	 * @param string $state new state
	 */
	public function setState($state) {
		$this->state = $state;
	}
	
	/**
	 * Getter for the state of the form
	 * @return string state 
	 */
	public function getState() {
	    return $this->state;
	}
	
	/**
	 * returns true if the state is empty
	 * @return bool - true if state is not set 
	 */
	public function defaultState() {
	    return "" == $this->state || null == $this->state;
	}
	
	### Primary Methods ###
	
	/**
	 * prints the element that clears the floated HTML elements
	 * @param string $elem - the HTML element to print that will clear the row. Optional.  Defaults to <br/>
	 */
	public function clearFloatedRow($elem='<br/>'){
	    echo $elem;
	}
	
	/**
	 * wraps the value the user input in an HTML element with a class used to hilight it.  used for confirmation screen.
	 * @param string $value - the value the user input
	 * @param string $elem - the HTML element to wrap $value in
	 * @param string $class - the CSS className for $elem
	 */
	public function getConfirmationElement($value,$elem='div',$class='hilite'){
	    return "<$elem class=\"$class\">$value</$elem>";
	}
	
	/**
	 * this method is the underpinning of the front end form - it checks the state and displays either the input or the result (if form is submitted)
	 * @param string $name - the name of this element, also it's ID.  We need ID for the label click to transfer focus to the input, and a name for JS.
	 * @param string $value - Optional.  The default value attribute for the element. Default: ""
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null
	 * @param string $selected - Optional - This should be set to the key that will be selected by default (If null then the first element is selected). Default: null
	 */
	public function getElementState($name, $type, $value="", $attributes=null, $selcted=null) {
		$type = strtolower(trim($type));
		
		if ($this->defaultState()){
    		switch($type){
    		    case "text":
    		        parent::textInput($name,$value,$attributes);
    		        break; 
    		    case "textarea":
    		        parent::textArea($name,$value,$attributes);
    		        break;
                case "hidden":
                    parent::hiddenInput($name,$value,$attributes);
                    break;
                case "file":
                    parent::fileInput($name,$value,$attributes);
                    break;
                case "select":
                    parent::select($name,$value,$selected,$attributes);
                    break;
                case "reset":
                    parent::resetButton($name,$value,$attributes);
                    break;
                case "button":
                    parent::button($name,$value,$attributes);
                    break;
                default:
    		        parent::input($name,$type,$value,$attributes);
    		        break;
    		}
    	}
		else{
		    if ($this->outputOnStateChange($type)){
			    echo $this->getConfirmationElement($this->selectedValueOf($name));
			}
		}
	}
	
	/**
	 * @param string $labelText - the text the EU sees on the screen
     * @param string $inputName - the name of the input, which links the label to the input.
     * @param string $inputValue - Optional.  The default value attribute for the element. Default: ""
     * @param string $inputAttributes - Optional.  writes HTML attributes as key=>value pairs.  if default, nothing will be printed.
     * @param string $labelAttributes - Optional.  writes HTML attributes as key=>value pairs.  if default, nothing will be printed.
     */
	public function textInputWithLabel($labelText, $inputName, $inputValue = null, $inputAttributes = null, $labelAttributes = null){
	    $labelAttributes = $this->addAttribute("for=\"$inputName\"",$labelAttributes);
	    $this->label($labelText,$labelAttributes);
	    $this->textInput($inputName,$inputValue,$inputAttributes);
	    $this->clearFloatedRow();
	}
	
	### overrides for parent ###
	### see parent for documentation ###
	public function textInput($name,$value,$attributes=null){
	    $this->getElementState($name,'text',$value,$attributes);
	}
	public function textArea($name,$value,$attributes=null){
	    $this->getElementState($name,'textarea',$value,$attributes);
	}
	public function checkbox($name,$value,$attributes=null){
	    $this->getElementState($name,'checkbox',$value,$attributes);
	}
	public function radioButton($name,$value,$attributes=null){
	    $this->getElementState($name,'radio',$value,$attributes);
	}
	public function hiddenInput($name,$value,$attributes=null){
	    $this->getElementState($name,'hidden',$value,$attributes);
	}
	public function fileInput($name,$value,$attributes=null){
	    $this->getElementState($name,'file',$value,$attributes);
	}
	public function select($name,$values,$selected=null,$attributes=null){
	    $this->getElementState($name,'select',$values,$attributes,$selected);
	}
	public function button($name,$value,$attributes=null){
	    $this->getElementState($name,'button',$value,$attributes);
	}
	public function resetButton($name,$value,$attributes=null){
	    $this->getElementState($name,'reset',$value,$attributes);
	}
	public function submitButton($submitLabel,$imgPath=null,$attributes=null){
	    if ($this->defaultState()){
	        parent::submitButton($submitLabel,$imgPath,$attributes);
	    }
	}
	### END: overrides for parent ###
	
	
	/**
	 * Internal function that checks the $_POST & $_GET arrays for the hidden value $submittedLabel, and toggles the private variable state
	 */
	private function checkSubmittedState(){
		session_start();
		
		if(isSet($_POST[$this->submittedLabel]) || isSet($_GET[$this->submittedLabel])){
		    $this->logUtil->debug("Detected that the form was submitted!");
			$this->storeFormDataToSession();
			//set the state so that foratted data prints instead of inputs
			$this->state = $this->submittedLabel;
		}
		else {
			//defaut state
			$this->state='';
		}
	}
	
	/**
	* helper function stores the form results in the session so we can retrieve them between user actions
	*/
	private function storeFormDataToSession(){
		if (isSet($_POST[$this->submittedLabel])){
			foreach($_POST as $fieldLabel=> &$value){
				$_SESSION[$fieldLabel] = $this->clean($value);
			}
		}
		if (isSet($_GET[$this->submittedLabel])){
			foreach($_GET as $fieldLabel=> &$value){
				$_SESSION[$fieldLabel] = $this->clean($value);
			}
		}		
	}
		
	/**
	* prints the "clean" version of a POST, GET, or SESSION value - stripped of spaces & html - and surrounded with html for formatting.
	* Intended for use when re-printing user input to the screen (verification screen).
	* @param String $formField is the name of the form field you want to format and print
	* @return String $data - the formatted data to print to the screen.
	*/
	protected function selectedValueOf($formField){
		if (isset($_POST[$formField]))
			return $this->getConfirmationElement($this->clean($_POST[$formField]));
		else if (isSet($_GET[$formField]))
			return $this->getConfirmationElement($this->clean($_GET[$formField]));
		else if (isset($_SESSION[$formField]))
    		return $this->getConfirmationElement($this->clean($_SESSION[$formField]));
	}
	
	/**
	 * helper determines if the type should output it's value on submit, or simply print nothing.
	 * @param string $type - the type of element to check
	 * @return boolean $shouldPrintOutput - true if the value should be printed to the screen
	 */
	private function outputOnStateChange($type){
	    foreach ($this->doNotPrintElementOnSubmit as $elementType){
	        if ($type == $elementType){
	            return false;
	        }
	    }
	    return true;
	}
}