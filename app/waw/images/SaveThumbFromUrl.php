<?php namespace waw\Images;
use \Imagick;
class SaveThumbFromUrl
{
  private $url, $name;
  function __construct($url,$name)
  {
    $this->url = $url;
    $this->name = $name;
  }

  function save()
  {
    if ($image = new Imagick($this->url))
    {
        $image = $image->flattenImages();
        $image->setFormat('JPEG');
        $image->cropThumbnailImage(100,100);
        $outFile = $_ENV['DIRECTORYTOPUBLICFOLDER'] . '/img/thumbs/' . $this->name .'.jpg';
        $image->writeImage($outFile);
    }
  }
}

?>
