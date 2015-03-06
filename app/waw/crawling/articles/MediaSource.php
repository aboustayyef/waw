<?php namespace LebaneseBlogs\Crawling\Articles ;


// creates a MediaSource object from a media source identifier (found in the columnists table);

class MediaSource{

  public $domain, $root, $articleLinks, $title, $content, $dateTimeLocation, $dateTime_regex, $dateTime_format;

  public function __construct($identifier = 'The Daily Star'){
    $all = (new MediaSourceDefinitions)->all;
    foreach ($all as $source) {
      if ($source['identifier'] == $identifier) {
        $this->domain = $source['domain'];
        $this->root = $source['root'];
        $this->articleLinks = $source['articleLinks'];
        $this->title = $source['title'];
        $this->content = $source['content'];
        $this->dateTimeLocation = $source['dateTimeLocation'];
        $this->dateTime_regex = $source['dateTime_regex'];
        $this->dateTime_format = $source['dateTime_format'];
        return true;
      }
    }
  return false;
  }
}

?>
