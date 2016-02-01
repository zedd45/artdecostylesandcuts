<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stylist_model extends Model {
  //FIXME: make this actual data, and not string literals.. this is sloppy, but it works for the moment. 
   protected $img_path = '/presentation/images/stylists/', $img_name_prefix = 'stylist_', $img_name_suffix = ".jpg", $thumb_suffix = '.png';
   private $imgCount = 0, $imgIndex = 0, $thumbIndex = 0, $stylistCount = 0,
       // $stylistNames = array("Jackie - Owner","Resses", "Geneva", "Ben", "Flecia", "Kim", "Bobby", "Paul"),
       $stylistNames = array("Jackie - Owner","Resses", "Ben", "Flecia", "Kim", "Bobby", "Paul"),
           // 1.) Owner / Master Stylist, Master Stylist, Master Stylist, Master Barber, Master Stylist, Eyebrow & Lashes Technician, Master stylist ,Master Barber
           $stylistPhotos = array(
              array(82,75,70,23,26,17,15), //Jackie
              array(45,50,52),  //Resses
              // array(42,35,36),  //Geneva
              array(33,29,28),  //Ben
              array(86,65,64),  //Felecia
              array(79,62,61),  //Kim
              array(58,54,55),  //Bobby
              array(68,40,38)   //Paul
             );
   
   public function Stylist_model() {
       parent::Model();
       //Fake the data, for now...
       $this->stylistCount = 7;
       $this->imgCount = 21; //8 stylists, 3 pics... 
       $this->thumbIndex = 1;
   }
  
   public function getThumbIndex(){
     return $this->thumbIndex++;
   }
   
   public function getNumberOfImages(){
     return $this->imgCount;
   }
   
   public function getStylistCount(){
     return $this->stylistCount;
   }
  
  /**
   * This is the method the controller will call to get the aggregated data. 
   * @return array - a map of the data needed for each stylist
   */
   public function getThumbnails(){  //FIXME: semantics... this is much more than the thumbs at this point
     $arr = array();
     for ($i=0; $i<$this->stylistCount; $i++){
        array_push($arr,
          array(
            'thumbImg' => $this->img_path . $this->img_name_prefix . 'thumb' . sprintf("%02d",$this->getThumbIndex()) . $this->thumb_suffix,
            'stylistName' => $this->getStylistName($i),
            'photos' => $this->getStylistPhotos($i)
          )
        );
     }
     return $arr;
   }
   
   /**
    * 
    * @param int - the index in the array to retrieve from. 
    * @return string - the name of the stylist for alt / title text
    */
   private function getStylistName($arrayPosition){
      try {
        return $this->stylistNames[$arrayPosition];
      }
      catch(Exception $e){
        //FIXME: log this err... or make this data structure not suck :)
        return "";
      }
    }
   
     /**
      * retrieves an array of photos for the stylist 
      * @param int - the index in the array to retrieve from. 
      * @return array - an array of strings representing the img src elements for the "large" photos
      */
    private function getStylistPhotos($arrayPosition){
        try {
           return $this->getPhotos($this->stylistPhotos[$arrayPosition]);
        }
        catch(Exception $e){
          //FIXME: log this err... or make this data structure not suck :)
          return "";
        }
      }
      
      /**
       * converts ints to file names / img paths
       * @param array  - an array of integers to compute image names from
       * @return array - an array of strings that map to the number passed in
       */
    private function getPhotos($arrayInput){
      $photos = array();
      foreach($arrayInput as $photoNumber){
        array_push($photos,$this->getPhotoPath($photoNumber));
      }
      return $photos;
      
    }
   
   /**
    * computes the image src string
    * @param number - the integer in the file name 
    * @return string - the calculated path to the image
    */
    private function getPhotoPath($number){
      return $this->img_path . $this->img_name_prefix . sprintf("%03d",$number) . $this->img_name_suffix;
    }
   
   /*
   #LEGACY 
   #FIXME: this is all shameful, anyway.  We need to push all this into a DB and use a real model.
   
   public function getCurrentPhoto(){
      return $this->img_path . $this->img_name_prefix . sprintf("%03d",$this->getImgIndex()) . $this->img_name_suffix;
    }

    public function getStylistPhotoCount(){
       return 3;
    }
   
   public function getAllStylists(){
     $loopCounter = $this->getNumberOfImages();
     $modulus = $this->getStylistPhotoCount();
     $stylists = array();
     
     for ($i=0; $i<$loopCounter; $i++){
       array_push($stylists,$this->getCurrentPhoto());
     }
   }
   
    public function getImgIndex(){
      return $this->imgIndex++;
    }
   
   */
}
