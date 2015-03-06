<?php
/**
*
*/

class ProcessUploadedImage
{

  function __construct($file, $name)
  {
    $this->file = $file;
    $this->name = $name;
  }

  public function saveThumb(){
      $this->destinationPath = 'img/thumbs';
      $this->filename = $this->name.'.jpg';
      $this->absoluteFileLocation = public_path().'/'.$this->destinationPath.'/'.$this->filename;
      if ($this->save()) {
        return true;
      } else {
        return $this->errors;
      }
  }

  public function saveCacheImage(){
      $this->destinationPath = 'img/cache';
      $this->filename = $this->name.'.jpg';
      $this->absoluteFileLocation = public_path().'/'.$this->destinationPath.'/'.$this->filename;
      if ($this->save()) {
        return true;
      } else {
        return $this->errors;
      }
  }

  public function save(){
    $this->errors = [];
      // If an image was uploaded, try to save that
      if (is_object($this->file)) {
        if ($upload_success = $this->file->move($this->destinationPath, $this->filename)) {
          if ($img = new imagick($this->absoluteFileLocation)) {
              if ($img->cropThumbnailImage( 100, 100 )) {
                if ($img->writeImage($this->absoluteFileLocation)) {
                  return true;
                } else {
                  $this->errors[] = 'Could not write cropped image';
                }
              } else {
                $this->errors[] = 'Could not crop image';
              }
            } else {
              $this->errors[] = 'Could not process file with imagick';
            }
        } else {
          $this->errors[] = 'Could not upload file';
        }
    }else { // Image not an opbject
      $this->errors[] = 'Could not instantiate file';
    }
  }
}
 ?>
