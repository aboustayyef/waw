<?php
/**
*   usage: listArticlesFromUrl::get($link);
*   returns: (array) a list of article links
*/
use Symfony\Component\DomCrawler\Crawler ;

class listArticlesFromUrl extends BaseController
{

  function __construct()
  {
    # code...
  }


  public static function get($link, $type){

    $sources = array(
      array(
        'Column Type'   =>  'The Daily Star',
        'domain'        =>  'dailystar.com.lb',
        'root'          =>  'http://dailystar.com.lb',
        'articleLinks'  =>  '.more-news h4 a'
      ),
      array(
        'Column Type'   =>  'Now Lebanon',
        'domain'        =>  'now.mmedia.me',
        'root'          =>  'http://now.mmedia.me',
        'articleLinks'  =>  'div.author_profile_listing_content  a'
      ),
      array(
        'Column Type'   =>  'Now Lebanon Blogs',
        'domain'        =>  'now.mmedia.me',
        'root'          =>  'http://now.mmedia.me',
        'articleLinks'  =>  'div.author_profile_listing_content  a'
      ),
      array(
        'Column Type'   =>  'The National',
        'domain'        =>  'thenational.ae',
        'root'          =>  'http://thenational.ae',
        'articleLinks'  =>  'li .holder h4 a'
      ),
      array(
        'Column Type'   =>  'Al-Akhbar English',
        'domain'        =>  'al-akhbar.com',
        'root'          =>  'http://english.al-akhbar.com',
        'articleLinks'  =>  '.views-field-title a'
      ),
      array(
        'Column Type'   =>  'Beirut.com',
        'domain'        =>  'beirut.com',
        'root'          =>  'http://beirut.com',
        'articleLinks'  =>  '.list-rows .post h3 a'
      ),
    );

    foreach ($sources as $key => $source) {
      if ($source['Column Type'] == $type) {
        $articleLinks = $source['articleLinks'];
        $theRoot = $source['root'];
      }
    }
    if (empty($articleLinks)) {
      echo '$articleLinks is empty';
      return false;
    }

    $crawler = new Crawler;
    $crawler->addHTMLContent(file_get_contents($link), 'UTF-8');

    $allLinks = $crawler->filter($articleLinks);
    $linksArray = array();

    foreach ($allLinks as $key => $link) {
      $link = new Crawler($link);
      $theLink = $link->attr('href');
      if ($theLink[0] == '/') {
        // check if relative link and append root if it is
        $theLink = $theRoot.$theLink;
      }
      $linksArray[] = $theLink;
    }

    if (!empty($linksArray)) {
      return $linksArray;
    } else {
      echo '$linksArray is empty';
      return false;
    }

  }
}
