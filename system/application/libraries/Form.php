<?php

/**
 * This is the base class for all form objects 
 * @author Chris Keen <chris@keenconcepts.net>
 */
 
class Form extends HTMLAttributeParser {
	protected $formName="";
	
	/**
	 * Creates a new instance of an html form.
	 * @param $name - string - the id / name of the form tag
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function __construct($name,$attributes = null) {
		parent::__construct();
		$this->formName=$name;
		$this->startForm($name,$attributes);
	}
	
	/**
	 * stub method for children
	 */
	public function __destruct(){}
	
	/**
	 * Adds a new input element to the form
	 * @param string $name - the name attribute of the element
	 * @param string $type - the type attribute of the element
	 * @param string $type - the value attribute of the element.  Optional.  Defaults to ""
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function input($name, $type, $value="", $attributes = null) {
		$attributes = $this->addAttributes($attributes,"type=\"$type\" id=\"$name\" name=\"$name\" value=\"$value\"");
		
		$tag  = "<input ";
		$tag .= $attributes;
		$tag .= "/>";
		
		echo $tag;
	}
	
	/**
	 * prints an input of type="hidden" 
 	 * @param $name - mixed - the name attribute of the element
 	 * @param $value - mixed - the value attribute of the element
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function hiddenInput($name, $value, $attributes = null){
	    $this->input($name,'hidden',$value,$attributes);
	}
	
	/**
	 * prints an input of type="file" 
 	 * @param $name - string - the name attribute of the element
 	 * @param $value - string - the value attribute of the element
     * @param $attributes - associative array - Optional.  writes HTML attributes as key=>value pairs.  if default, nothing will be printed. 
	 */
	public function fileInput($name,$value=null,$attributes=null){
	    $this->input($name,'file',$value,$attributes);
	}
	
	/**
	 * adds a label to the form.  Typically called preceding an input
	 * @param $labelText - Mixed - the text the EU sees on the screen
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function label($labelText, $attributes=null){
		//force a leading space for non-empty attributes
		//TODO: Methodize this and use it for all tags
		$attributes = null === $attributes ? '' : ' ' . $attributes;
		
		$tag  = "<label";
		$tag .= $attributes;
		$tag .= ">$labelText</label>";
		
		echo $tag;
	}
	
	/**
	 * Adds a select element with nested option elements to the form.
	 * @param $name - mixed - the name of the element
	 * @param associative array $values - value/text pairs  the key is the value that will be assigned to the select's name if selected and the value
	 *		is the text to display for that value. Example: <code>array("GA" => "Georgia", "IA" => "Iowa");</code>
	 * @param string $selected - Optional - This should be set to the key that will be selected by default (If null then the first element is selected). Default: null
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function select($name, $values, $selected=null, $attributes=null) {
		$attributes = $this->addAttributes($attributes,"name=\"$name\" id=\"$name\"");
		
		$selectStr  = "<select ";
		$selectStr .= $attributes . ">";
		$selectStr .= $this->getOptionElements($values,$selected);
		$selectStr .= "</select>";

		echo $selectStr;
	}
	
	/**
	* Adds a submit button or submit image to the form.
	* @param $submitLabel - string - the value attribute or alt attribute of the submit button. 
	* @param $imgPath - string - Optional.  The relative (or absolute, if necessary) path to the submit image. If this is !null, a submit image will be created instead of a button.  Default:null
	* @param $name - mixed - Optional - name and ID of the element.  Defaults to "submit."
	*/
	public function submitButton($submitLabel,$imgPath=null,$attributes=null){
		$formId = $this->formName;
		$type = $imgPath ? 'image' : 'submit';
		$requiredAttributes = $imgPath ? "src=\"$imgPath\"" : '';
		$attributes = null == $attributes ? $requiredAttributes : $this->addAttributes($attributes,$requiredAttributes);
		
		$this->input("$formId-submit-button",$type,$submitLabel,$attributes);
	}
	
	/**
	 * Adds a new button input element to the form
	 * @param string $name - the name of this element, and it's ID.  We need ID for the label click to transfer focus to the input, and a name for JS.
	 * @param string $value - Optional.  The default value attribute for the element. Default: ""
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function button($name, $value, $attributes=null) {
		$this->input($name, 'button', $value, $attributes);
	}
	
	/**
	 * Adds a new reset button input element to the form
	 * @param string $name - the name of this element, and it's ID.  We need ID for the label click to transfer focus to the input, and a name for JS.
	 * @param string $value - Optional.  The default value attribute for the element. Default: ""
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function resetButton($name, $value, $attributes=null) {
		$this->input($name, 'reset', $value, $attributes);
	}
	
	/**
	 * Adds a textarea element to the form.
	 * @param $name - String - the name of the element
	 * @param $value - String - Optional.  The default text between the opening and closing tags. Default: ""
 	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function textarea($name, $value='', $attributes=null) {
		$attributes = $this->addAttributes($attributes,'rows="5" cols="10"' . "name=\"$name\" id=\"$name\"");
		
		$tag  = "<textarea ";
		$tag .= $attributes;
		$tag .= ">$value</textarea>";
		
		echo $tag;
	}
	
	/**
	 * Adds a new text input element to the form
	 * @param string $name - the name of this element, and it's ID.  We need ID for the label click to transfer focus to the input, and a name for JS.
	 * @param string $value - Optional.  The default value attribute for the element. Default: ""
	 * @param string $attributes - writes HTML attributes.  Optional.  Defaults to null 
	 */
	public function textInput($name, $value="", $attributes=null) {
		$this->input($name, 'text', $value, $attributes);
	}
	
	
	/*##### Helper Functions ########*/
	
	/**
	 * Helper function used to start the form tag.  This should be called in the constructor. 
	 * @param string $name - the id / name of the form tag
	 * @param string $attributes - any HTML attributes you wish to override, including method & action 
	 */
	protected function startForm($name,$attributes=null) {
		$attributes = $this->addAttributes($attributes,'method="post" action=""');
		
		$tag  = "<form name=\"$name\" id=\"$name\"";
        $tag .= ' ' . $attributes;
        $tag .= ">";
	
		echo $tag;
	}
	
	/**
	* Helper function outputs the closing form tag. 
	**/
	public function endForm(){
		echo "</form>";
	}
	
	/**
	 * Helper that calculates the options for a select box
	 * @param associative array $values - the array to create option elements from using the key -> value to represent the text and value attribute, respectively.
	 * @param string $selected  -  if you want the form to start with a value selected, this string should match the value in the $values array you want the select element to default to.  Optional.  Defaults to null
	 */
	protected function getOptionElements($values,$selected=null){
	    foreach($values as $value => $text){
			$options .= "<option value=\"$value\""; 
			$options .= $selected==$value ? ' selected="selected"' : '';
			$options .= ">$text</option>";
	    }
	    return $options;
	}
	
	/**
	* prints the "clean" version of a POST or GET value - stripped of spaces & html
	* Intended for use with the value="" attribute of an HTML element.
	* @param - formField is the field name of the form field you want to reprint
	* @return String $data - the formatted data to print to the screen.
	*/
	public function valueOf($formField){
        return $this->clean($this->getValueFromSuperGlobals($formField));
	}
	
	/**
	 * helper that returns the input with leading and trailing spaces removed, and HTML escaped
	 * @param $value - mixed - the value to clean
	 * @return $cleanValue - mixed - the value, stripped of whitespace and tags.
	 */
	protected function clean($value){
	    return trim(htmlentities($value));
	}
	
	/**
	 * helper that returns the value from the super globals array
	 * @param $fieldName - mixed - the name of the field
	 * @return $value - mixed - the value of the field 
	 */
    protected function getValueFromSuperGlobals($fieldName){
        switch($this->globalType){
            case "post":
                return $_POST[$formField];
                break;
            case "get":
                return $_GET[$formField];
                break;
            case "session":
                return $_SESSION[$formField];
                break;
            default: 
                return $_POST[$formField];
                break;
        }
    }
}