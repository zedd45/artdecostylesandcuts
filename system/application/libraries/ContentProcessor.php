<?php
/**
 * loads content created by the client into the page template
 * @author Chris Keen
 **/
class ContentProcessor {
    protected $fileType = null, $filePath = null, $logUtil = null;
    private $userErrorMsg = null;
    
    /**
     * constructor
     * @param string $fileType - the file extension to read from - with no dot precursor.  typically a three alphabetical characters
     * @param string $filePath - the location of the file on the server.  optional.
     * @param string $userErrorMsg - the copy to print for end users if the file is not found.
     */
     public function __construct($fileType = 'inc', $filePath = 'pages/userContent/', $userErrorMsg = null){
         $this->fileType = str_replace('.','',trim($fileType));
         $this->filePath = trim($filePath);
         
         if (null != $userErrorMsg){
             $this->userErrorMsg = $userErrorMsg;
         }
         else {
             //default has to be set here, or the parser will explode on the concatenation step.
             $this->userErrorMsg = '<p>We are having difficulty locating the file you have requested.  Please try again later, or <a href="' . getPageURIByName("forms/contact") . '">report</a> the problem.</p>';
         }
         
         global $logUtil;
         $this->logUtil = $logUtil;
     }
     
     public function setFilePath($filePath){
         $this->filePath = $filePath;
     }
     
     /**
      * retrieves content from the specified location
      * @param string $pageName - the name of the file you want to retrieve. 
      * @param int $connectionType - the method of retrieval - 0=local file,1=DB
      */
     public function getContent($pageName,$connectionType=0){
         $pageName = trim($pageName);
         //error checking
         if ($connectionType > 1 || $connectionType < 0){
             $logUtil->error("Error retrieving content: invalid connection type: $connectionType");
         }
         
         if (0 == $connectionType){
             $this->_getContentFromFile($pageName);
         }
         
         if (1 == $connectionType){
             echo "DB Connection not created.  Please contact your webmaster.";
         }
     }
     
     /**
       * stores content to the specified location
       * @param string $pageName - the name of the file you want to store the content to. 
       * @param string $content - the content to store
       * @param int $connectionType - the method of storage - 0=local file,1=DB
       */
     public function storeContent($pageName,$content,$connectionType){
         $pageName = strip_tags(trim($pageName));
         
         if (0 == $connectionType){
             file_put_contents($this->_getFullPath($pageName),$this->_cleanContent($content));
         }        
     }
     
     /**
      * determines if the file exists on the filesystem
      * @param string $pageName - the name of the file you want to retrieve.  
      * @return boolean - true if the file was found, false otherwise.
      */
     public function contentFileExists($pageName,$fullPathToFile=false){
         $contentToPublish = $this->_getFullPath($pageName);
         $this->logUtil->debug(get_class($this) . ' tried to locate file at: ' . $contentToPublish);
         return file_exists($contentToPublish);
     }
     
     /**
      * retrieves content from a file.
      * @param string $pageName - the name of the file you want to retrieve. 
      */
     private function _getContentFromFile($pageName){
         if ($this->contentFileExists($pageName)){
             $contentToPublish = $this->_getFullPath($pageName);
             $contentToPublish = file_get_contents($contentToPublish);
             echo $this->_cleanContent($contentToPublish);
         }
         else {
             $this->logUtil->error("could not locate file $contentToPublish");
             echo $this->userErrorMsg;
         }
     }
     
     /**
      * takes an input tag and strips it of script and style tags.
      * @param string $content - the client's content to process
      * @param array $tagListToStrip - the list of tags to remove from the content.
      * @return string $cleanContent - the content, stripped of the tags in the list provided
      */
     private function _cleanContent($content,array $tagListToStrip = array('script','style')){
         $tagsToClean = array();
         
         foreach ($tagListToStrip as $tag){
             $tagsToClean[] = '@<' . $tag . '[^>]*?.*?</' . $tag . '>@siu';
         }
         
         return preg_replace($tagsToClean,'',$content);
     }
     
     /**
      * returns the full path to the file
      * @param string $pageName - the name of the file you want to retrieve. 
      * @return string $filePath - the path necessary to include the file.
      */
     private function _getFullPath($pageName){
         return $this->filePath . $pageName . '.' . $this->fileType;
     }
    
}