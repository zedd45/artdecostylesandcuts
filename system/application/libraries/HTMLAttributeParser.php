<?php
/**
 * creates an instance of an object that can manipulate strings representing HTML attributes
 * @author Chris Keen 
 */
class HTMLAttributeParser {
    protected $logUtil = null;
    
    /** 
     * constructor 
     */
    public function __construct(){
        global $logUtil;
        $this->logUtil = $logUtil;
    }
    
    /**
     * adds the new attribute to the existing attribute string, if that attribute has not already been specified, or if $replaceExisting is true
     * @param $newAttribute string  the new attribute="value" string to add to existingAttributes
     * @param $existingAttributes string  the string to add $newAttribute to
     * @param $replaceExisting boolean  should the new attribute replace the existing attribute, if found.  Optional.  Defaults to true
     */
    public function addAttribute($newAttribute,$existingAttributes,$replaceExisting=true){
        $duplicateAttribute = $this->attributeAlreadySpecified($newAttribute,$existingAttributes);
        
        if ($duplicateAttribute && $replaceExisting){
            $this->logUtil->debug("duplicate attribute found for addAttribute: $newAttribute");
            return $this->mergeAttributes($newAttribute,$existingAttributes);        
        }
        else if (!$duplicateAttribute) {
            $this->logUtil->debug("sucessfully added the attribute: $newAttribute without duplicates.");
            return $existingAttributes . ' ' . $newAttribute;
        }
        else {
            $this->logUtil->debug("addAttribute was NOT allowed to replace the existing attribute in: $existingAttributes with $newAttribute");
            return $existingAttributes;
        }
    }
    
    /**
     * helper that checks the custom attributes string for a required attribute, and gives the default if it's not there.
     * @param  string  $currentAttributes  the name of the attribute to ensure is in the element.  This MUST include the value.
     * @param $existingAttributes string  the string to add $newAttributes to
     * @param $replaceExisting boolean  should the new attribute replace the existing attribute, if found.  Optional.  Defaults to true
     * @return string  the string for the default attribute, if it was not found in the custom string
     */
    public function addAttributes($newAttributes,$existingAttributes,$replaceExisting=true){
        if ($this->attributesEmpty($existingAttributes)){
            $this->logUtil->debug("no existing attributes: new-" . $newAttributes);
            return $newAttributes;
        }
        else if ($this->attributesEmpty($newAttributes)){
            $this->logUtil->debug("no new attributes: existing-" . $existingAttributes);
            return $existingAttributes;
        }
        else {
            $this->logUtil->debug("merging new attributes: $newAttributes - into $existingAttributes");
            return $this->mergeAttributes(trim($newAttributes),trim($existingAttributes));        
        }
    }
    
    /**
     * helper - keeps addAttribute and addAttributes DRY
     * @param string $newAttributes - the custom attributes to add to $existingAttributes
     * @param string $existingAttributes - the custom attributes to merge into
     * @return string $resultSet - the merged set of attributes
     */
    private function mergeAttributes($newAttributes,$existingAttributes){
        $finalAttributes = $existingAttributes;
        //break apart the string at the point that seperates them
        $attributesToMerge = explode('" ',$newAttributes);
        $attributesToMergeInto = explode('" ',$existingAttributes);

        foreach($attributesToMerge as $keyValuePair){
            #### FIXME: make this cleaner with a preg ####
            if ($this->attributeAlreadySpecified($keyValuePair,$finalAttributes)){
                if (1==count($attributesToMergeInto)){
                     $this->logUtil->debug("Only one attribute to overwrite: $existingAttributes with $newAttributes");
                     return $newAttributes;
                }
                $attributeToMerge = $this->getAttributeString($keyValuePair);
                $this->logUtil->debug("trying to merge attribute: $attributeToMerge into $finalAttributes");
                
                //find the position of the attribute in the current string, then find the end of the key (="key"), so it can be truncated.
                $duplicateStart = stripos($finalAttributes,$attributeToMerge);
                $duplicateEnd = strpos($finalAttributes,'"',$duplicateStart);
                //find the second occurance of "
                $duplicateEnd = strpos($finalAttributes,'"',$duplicateEnd);
                //now find out if that's the end of the string...
                $duplicateEnd = strpos($finalAttributes,' ',$duplicateEnd);
                $duplicateEnd = false === $duplicateEnd ? strlen($existingAttributes) : $duplicateEnd;
                
                //find the position from 0 to duplicate start, then duplicate end to end of string, we maintain the existing attributes.
                $mergeLeft = substr($finalAttributes,0,$duplicateStart);
                $mergeRight = substr($finalAttributes,$duplicateEnd+1);
                $toDiscard = substr($finalAttributes,$duplicateStart,$duplicateEnd);
                $finalAttributes = $mergeLeft . $mergeRight . $this->getCleanKeyValueString($keyValuePair);
                
                $this->logUtil->debug("mergeLeft: $mergeLeft.  mergeRight: $mergeRight.  Discarded: $toDiscard");
            }
            else {
                $finalAttributes .= $this->getCleanKeyValueString($keyValuePair);
                $this->logUtil->debug("appending attribute: $keyValuePair to end of string.  New String: $finalAttributes.");
            }
        }
        
        $this->logUtil->debug("final attributes: $finalAttributes");
        return $finalAttributes;
    }

    /**
     * helper checks to see if the new attribute has already been defined in the attributes string - case insensitive
     * @param  string  $newAttribute  the name of the attribute to add.
     * @param  string  $existingAttributes  the custom attributes
     * @return boolean is duplicate
     */
    private function attributeAlreadySpecified($newAttribute,$existingAttributes){
        if ($this->noConflict($newAttribute,$existingAttributes)){
            return false;
        }
        
        $attributeName = $this->getAttributeString($newAttribute);
        return false !== stripos($existingAttributes,$attributeName);
    }
    
    /**
     * helper 
     * @param $attributes  string  the default attributes
     * @return boolean - true if $existingAttributes is null or the empty string
     */
    private function attributesEmpty($attributes){
       return (null == $attributes) || ("" == trim($attributes));
    }
    
    /**
     * helper - extracts the key portion of the key="value" pair
     * @param string $attributes - the string to extract the attribute from
     * @param string $seperator - optional - the string to parse on
     * @return $attribute - the string before $seperator
     */
    private function getAttributeString($attribute,$seperator="="){
        //return stristr($attribute,$seperator,true);  //5.3.0 safe ONLY
        $seperatorPosition = strpos($attribute,$seperator);
        return substr($attribute,0,$seperatorPosition);
    }
    
    /**
     * helper - extracts the value portion of the key="value" pair 
     * @param string $attributes - the string to extract the attribute from
     * @param char $seperator - optional - the string to parse on
     * @return string $value - stripped of quotes
     */
    private function getValueString($attribute,$seperator="="){
        //return stristr($attribute,$seperator);
        $seperatorPosition = strpos($attribute,$seperator);
        $value = substr($attribute,$seperatorPosition);
        //replace all $seperator chars and " chars and return the 'clean' value
        return str_replace(array('"',$seperator),"",$value);
    }
    
    /**
     * helper - breaks the attribute string into it's respective pieces, then gets the XHTML compliant version of the attribute
     * @param string $attribute - the string to parse
     * @return string $clean - the XHTML compliant version of the string
     */
    private function getCleanKeyValueString($attribute){
        $key = $this->getAttributeString($attribute);
        $value = $this->getValueString($attribute);
        return $this->getCleanKeyValueFromPair($key,$value);
    }
    
    /**
     * helper - prints the key="value" pair after sending the key to lowercase (XHTML) and adding the = & "" as required, and adding a leading space.
     * @param string $key - the leading (attribute) portion
     * @param string $value - the value of the attribute
     * @return string $clean - the XHTML compliant version of the string
     */
    private function getCleanKeyValueFromPair($key,$value){
        return ' ' . strtolower($key) . '="' . $value . '"';
    }
    
    /**
     * helper
     * @param string $newAttributes - the custom attributes to add to $existingAttributes
     * @param string $existingAttributes - the custom attributes to merge into
     * @return boolean - true if $existingAttributes is null or the empty string
     */
    private function noConflict($newAttributes,$existingAttributes){
       return $this->attributesEmpty($newAttributes) || $this->attributesEmpty($existingAttributes);
    }
}