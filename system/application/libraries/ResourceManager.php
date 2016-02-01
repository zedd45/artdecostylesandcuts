<?php
/**
 * creates script and link elements on the page, with the appropriate level of compression and concatenation.
 * @author chris keen <chris@keenconcepts.com>
 */
class ResourceManager extends ResourceLoader {
    //TODO: add constants for scope - if I keep this site / page (eg w/o section) we only need on or off states
    private $scriptScope = array(), $linkScope = array();
    private $siteRevision = null;
    private $globalScriptFileLocatoin = 'presentation/js/globalScriptsTest.js', $pageScriptFileLocation   = 'presentation/js/siteScriptsTest.js';
    private $globalCSSFileLocation = 'presentation/css/globalTest.css', $pageCSSFileLocation = 'presentation/js/pageCSSTest.css';
    
    /**
     * creates an instance of resource manager.  This should only be done on the backend before a deployment, and NEVER called on a per page basis
     * calling this per page will have a significant performance impact
      * @param defaultCSSPath - String - the path to the presentation layer CSS files 
      * @param defaultJSPath - String - the path to the presentation layer JS files 
      */
    public function __construct($VCSRevisionNumber,$defaultCSSPath = '/presentation/css/', $defaultJSPath = '/presentation/js/'){
        $this->siteRevision = $VCSRevisionNumber;
        parent::__construct($defaultCSSPath,$defaultJSPath);
    }
    
    /**
     * adds a script tag to the queue
     * @param source - string representing the path to the file on the server.
     * @param globalScope - boolean representing if this is a site script vs a page script.  optional.  Defaults to global (true).
     * @throws Exception - if the file located at $source doesn't exist
     */
    public function addScript($source,$globalScope = true){
        $currentFile = $this->getFileLocation("$source");
        if (file_exists($currentFile)){
            echo "file exists: $currentFile";
        }
        else {
            echo "couldn't find file: $currentFile";
        }
        
        parent::addScript($source);
        array_push($this->scriptScope,$globalScope);
    }
    
    public function printScripts(){
        
    }
    
    /**
     * adds a link tag to the queue
     * @param source - string representing the path to the file on the server.
     * @param globalScope - boolean representing if this is a site script vs a page script.  optional.  Defaults to global (true).
     * @throws Exception - if the file located at $source doesn't exist
     */
    public function addStyleSheet($source,$globalScope = true){
        $currentFile = $this->getFileLocation("$source");
        if (file_exists($currentFile)){
            echo "file exists: $currentFile";
        }
        else {
            echo "couldn't find file: $currentFile";
        }
        
        parent::addStyleSheet($source);
        array_push($this->linkScope,$globalScope);
    }
    
    /** 
      * Loop through the array, printing the stylesheets
      */
    public function printCSS(){
        $this->concatenateCSSFiles();
        echo "<link rel=\"stylesheet\" href=\"$this->globalCSSFileLocation\" media=\"all\" />"; 
    }
    
    private function concatenateCSSFiles(){
        foreach ($this->linkCollection as $file){
            $currentFile = $this->getFileLocation($file);
            $fileContents = file_get_contents($currentFile);
            file_put_contents($this->globalCSSFileLocation,$fileContents);
        }
    }
    
    private function getFileLocation($file){
        $pathToFile = stripos($file,".js")===false ? parent::getCSSPath() : parent::getJSPath();
        //trim the first forward slash off, since PHP doesn't look at the file system the same way apache would from the HTML file.
        $currentFile = substr("$pathToFile$file",1);
        return $currentFile;
    }
}