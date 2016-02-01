<?php
/**
 * base class for uploading images.
 * @author Chris Keen 
 */
class ImageUploader {
    protected $imgPath, $logUtil = null;
    
    /**
     * constructor
     */
    public function __construct($imgPath = null){
        if (null == $imgPath){
            throw new Exception("The path for uploads was not specified!");
        }
        $this->imgPath = $imgPath;
        
        global $logUtil;
        $this->logUtil = $logUtil;
        $this->logUtil->enableConsole();
    }
    
    public function uploadImage($inputName = null){
        if (null == $inputName){
            throw new Exception("Unable to upload file(s).  Please check the name of the file input.");
        }
        
        $this->logUtil->debug("attempting upload with path: " . $this->imgPath);

        $clientFile = $_FILES[$inputName]['name'];
        $uploadPath = $this->imgPath . basename($_FILES[$inputName]['temp']);

        if (move_uploaded_file($clientFile,$uploadPath)) {
            echo 'File is valid, and was successfully uploaded.';
        } else {
            echo '<br/><br/>The File was not uploaded Successfully: ' . $_FILES[$inputName]['error'];
            $this->logUtil->error("File Upload Failed in ImageUploader: " . $_FILES[$inputName]['error']);
        }

        echo '<br/><br/>Here is some more debugging info:';
        var_dump($_FILES);
    }
 
    public function processMultipleImages($inputName = 'pictures'){
        foreach ($_FILES[$inputName]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
                $name = $_FILES[$inputName]["name"][$key];
                //if (!move_uploaded_file($tmp_name, $this->imgPath . "/$name");
            }
        }
    }
    
}
