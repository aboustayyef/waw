<?php namespace waw\Crawling;

use Symfony\Component\DomCrawler\Crawler ;

  /**
  *
  */
  class MetaDataExtractor
  {
    private $html, $crawler;

    function __construct($url)
    {
      $this->html = @file_get_contents($url);
      if (!$this->html) {
        return false;
      }
      $this->crawler = new Crawler($this->html);
    }

    function title(){
      $title = $this->crawler->filter('title')->first()->text();

      // some titles come with description, split them;
      $partsOfTitle = preg_split('#\s*\||–\s*#',$title);

      return trim($partsOfTitle[0]);
    }

    function feed(){
      $crawler = new Crawler($this->html);
      $feed = $this->crawler->filter('link[type="application/rss+xml"]')->first()->attr('href');
      return $feed;
    }

    function engine(){

    }

  }

?>
