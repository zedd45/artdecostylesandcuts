<?php
/**
 * creates script and link elements on the page
 * @author chris keen <chris@keenconcepts.com>
 * TODO: create a hash for what has been queued / stored / printed so we can flush the queue and fill it up again.
 */
class ResourceLoader {
    protected $scriptCollection = array(), $linkCollection = array(), $linkDisplayType = array(), $inlineScriptCollection = array();
    //protected $styleSheetCollection = array($linkCollection => array(), $linkDisplayType = array());
    protected $jsPath, $cssPath, $compressionOn;
    //TODO: implement a switch based on the DEBUG const that will append this to before the file extension
    const compressedFileSuffix = ".min";
    
    /**
     * creates an instance of resource loader.
     * @param defaultCSSPath - String - the path to the presentation layer CSS files 
     * @param defaultJSPath - String - the path to the presentation layer JS files 
     */
    public function __construct($compression = false,$defaultCSSPath = '/presentation/css/', $defaultJSPath = '/presentation/js/'){
        //TODO: check / truncate the final char to prevent // in path
        $this->cssPath = $defaultCSSPath;
        $this->jsPath = $defaultJSPath;
        $this->compressionOn = $compression;
    }
    
    /**
     * adds a script tag at the appropriate place in the document (preferably just prior to the </body> tag).
     * @param source - string - the name of the file to include
     */
    public function addScript($source){
        $source = trim($source);
        if (!in_array($source,$this->scriptCollection)){
            if ($this->compressionOn){
                $fileExtStart = stripos($source,".js");
                $source = substr($source,0,$fileExtStart) . self::compressedFileSuffix . substr($source,$fileExtStart);
            }
            $this->scriptCollection[] = $this->jsPath . $source;
        }
    }
    
    /**
     * adds a script included from another domain (bypasses system compression)
     * @param source - string - the path to the file as it will display in a browser.
     */
    public function addExternalScript($source){
        $source = trim($source);
        if (!in_array($source,$this->scriptCollection)){
            $this->scriptCollection[] = $source;
        }
    }
    
    /**
     * adds an inline script after all other scripts have loaded. 
     * no need to include the script element tags
     * @param script - string - the script to add
     */
    public function addInlineScript(string $script){
        $this->inlineScriptCollection[] = $script;
    }
    
    /**
     * adds a link tag at the appropriate place in the document (in the <head>, preceding any scripts).
     * @param source - string - the path to the file as it will display in a browser.
     * @param media - the types of media to include this stylesheet for.  
     */
    public function addStyleSheet($source,$media = "all"){
        if (!in_array($source,$this->linkCollection)){
            $this->linkCollection[] = trim($source);
            $this->linkDisplayType[] = trim($media);
        }
    }
    
    /** 
    * Loop through the array, printing the stylesheets
    */
    public function printCSS(){
        foreach ($this->linkCollection as $href){
            echo "<link rel=\"stylesheet\" href=\"$this->cssPath$href\" media=\"" . current($this->linkDisplayType) . "\" />";
            //FIXME: make the array step up until I can get the 2D version working.
            next($this->linkDisplayType);
            array_pop($this->linkCollection);
        }
    }
    
    /** 
    * Loop through the array, printing the scripts
    */
    public function printScripts(){
        foreach ($this->scriptCollection as $src){
            echo "<script type=\"text/javascript\" src=\"$src\"></script>";
            array_pop($this->scriptCollection);
        }
        foreach($this->inlineScriptCollection as $script){
            echo '<script type="text/javascript">' . $script . '</script>';
        }
    }
    
    /**
     * allows children to access the script path
     */
    protected function getJSPath(){
        return $this->jsPath;
    }
    
    /**
     * allows children to access the CSS path
     */
    protected function getCSSPath(){
        return $this->cssPath;
    }
    
    /**
     * allows children to access script collection
     */
    protected function getScripts(){
        return $this->scriptCollection;
    }
    
    /**
     * allows children to access link collection
     */
    protected function getLinks(){
        return $this->linkCollection;
    }
     
}