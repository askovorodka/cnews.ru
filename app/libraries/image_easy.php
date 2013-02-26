<?php 

class Image_Easy {
 
   private $image = null;
   private $image_type = null;
   private $CI;
   private $compression = 777;
   
   function __construct()
   {
   		$this->CI =& get_instance();
   		$this->CI->load->helper('image_helper');
   }
 
   function load($filename)
   {
 
   		switch (Image_Helper::get_image_type($filename))
   		{
   			case IMAGETYPE_JPEG:
   				$this->image = imagecreatefromjpeg($filename);
   				$this->image_type = IMAGETYPE_JPEG;
   				break;
   			case IMAGETYPE_GIF:
   				$this->image = imagecreatefromgif($filename);
   				$this->imagetype = IMAGETYPE_GIF;
   				break;
   			case IMAGETYPE_PNG:
   				$this->image = imagecreatefrompng($filename);
   				$this->image_type = IMAGETYPE_PNG;
   				break;
   		}
   	
   }
   
   function save($filename)
   {
 
      if( $this->image_type == IMAGETYPE_JPEG )
      {
         imagejpeg($this->image,$filename,$compression);
      }
      elseif( $this->image_type == IMAGETYPE_GIF )
      {
         imagegif($this->image,$filename);
      }
      elseif( $this->image_type == IMAGETYPE_PNG )
      {
         imagepng($this->image,$filename);
      }
      
      chmod($filename,0777);
      
      return (string)$filename;
      
   }
   
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }

   function getWidth() {
 
      return imagesx($this->image);
   }
   
   function getHeight() {
 
      return imagesy($this->image);
   }
   
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }      
 
}