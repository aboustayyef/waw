<?php namespace LebaneseBlogs\Crawling\Articles ;

use Symfony\Component\DomCrawler\Crawler ;
use \Columnist;

// Takes an Author id and lists all their latest posts

class ListOfAuthorArticles{

public $list = array();

public function __construct($authorId){

  $columnist = Columnist::find($authorId);
  if (!$columnist) {
    return false;
  }

  // mediaSource object has all data needed for extraction
  $mediaSource = new MediaSource($columnist->col_media_source);

  // prepare crawler.
  $homePageCrawler = new Crawler;
  $homePageCrawler->addHTMLContent(file_get_contents($columnist->col_home_page), 'UTF-8');

  // get list of links
  $allLinks = $homePageCrawler->filter($mediaSource->articleLinks);

  // convert relative links to absolute links
  foreach ($allLinks as $key => $link) {
      $link = new Crawler($link);
      $theLink = $link->attr('href');
      if ($theLink[0] == '/') {
        // check if relative link and append root if it is
        $theLink = $mediaSource->root.$theLink;
      }
      $this->list[] = $theLink;
    }

}

}

?>
