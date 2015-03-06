<?php namespace waw\Crawling;

use Symfony\Component\DomCrawler\Crawler ;

  interface ImageExtractor{
    function image();
  }

  /**
  *
  */
  class TwitterImageExtractor implements ImageExtractor
  {
    private $html;

    function __construct($twitterHandle)
    {
      $this->html = @file_get_contents('http://twitter.com/'.$twitterHandle);
      if (!$this->html) {
        return false;
      }
    }

    function image(){
      $crawler = new Crawler($this->html);
      $image = $crawler->filter('img.ProfileAvatar-image')->first()->attr('src');
      return $image;
    }

  }

?>
