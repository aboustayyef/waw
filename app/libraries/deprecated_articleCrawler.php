<?php
use Symfony\Component\DomCrawler\Crawler ;

/**
* usage:
*   $article = new articleCrawler('http://theLink.of.the/article')
*   $title = $article->getTitle();
*   $timestamp = $article->getTimestamp();
*   $content = $article->getContent();
*   $excerpt = $article->getExcerpt();
*/
class articleCrawler extends BaseController
{

  static private $sources = array(
    array(
      'Column Type'      =>  'The Daily Star',
      'title'             =>  '#bodyHolder_divTitle',
      'content'           =>  '#divDetails',
      'dateTimeLocation'  =>  '#bodyHolder_divDate',
      'dateTime_regex'    =>  '[a-zA-z]{3}\.\s\d{2},\s\d{4}\s\|\s\d{1,2}:\d{1,2}\s[A-Z]{2}', // ex Aug. 03, 2014 | 09:34 PM
      'dateTime_format'   =>  'M. d, Y \| h:i A',
    ),
    array(
      'Column Type'       =>  'Now Lebanon',
      'title'              =>  'h1.article_title',
      'content'            =>  '#news_template > div.main_area_align > div > div.article_section > div.article_main_section > div.main_article > div.main_txt',
      'dateTimeLocation'   =>  'h3.article_date',
      'dateTime_regex'     =>  '\d{1,2}/\d{1,2}/\d{4}\s{3}\d{1,2}:\d{1,2}\s[A-Z]{2}', // ex 1/08/2014 11:09 AM
      'dateTime_format'    =>  'j/m/Y   h:i A',
    ),
    array(
      'Column Type'       =>  'Now Lebanon Blogs',
      'title'              =>  'h1.article_title',
      'content'            =>  '#news_template > div.main_area_align > div > div.article_section > div.article_main_section > div.main_article > div.main_txt',
      'dateTimeLocation'   =>  'h3.article_date',
      'dateTime_regex'     =>  '\d{1,2}/\d{1,2}/\d{4}\s{3}\d{1,2}:\d{1,2}\s[A-Z]{2}', // ex 1/08/2014 11:09 AM
      'dateTime_format'    =>  'j/m/Y   h:i A',
    ),
    array(
      'Column Type'       =>  'The National',
      'title'              =>  '.mainflash-article h1',
      'content'            =>  'div.article-body-page',
      'dateTimeLocation'   =>  'div.articleinfo',
      'dateTime_regex'     =>  '[A-Za-z]+\s\d{1,2},\s\d{4}', // ex August 3, 2014
      'dateTime_format'    =>  'F j, Y',
    ),
    array(
      'Column Type'       =>  'Al-Akhbar English',
      'title'              =>  'h1.title',
      'content'            =>  'div.content-wrap',
      'dateTimeLocation'   =>  'span.date-display-single',
      'dateTime_regex'     =>  '[a-zA-z]+\s\d{1,2},\s\d{4}', // ex July 21, 2014
      'dateTime_format'    =>  'F j, Y',
    ),
    array(
      'Column Type'       =>  'Beirut.com',
      'title'              =>  '#sidebar h2',
      'content'            =>  '.profile p',
      'dateTimeLocation'   =>  array('div.info > p:nth-child(2) > span:nth-child(3)','div.info > p:nth-child(2) > span:nth-child(2)'),
      'dateTime_regex'     =>  '[a-zA-Z]+\s\d{1,2},\s\d{4}', // ex Aug 9, 2014
      'dateTime_format'    =>  'M j, Y',
    )
  );

  private $_link, $_sourceName, $_titleSource, $_contentSource, $_dateTimeSource, $_dateTimeRegex, $dateTimeFormat;
  private $_MainCrawler;
  private $_title, $_timeStamp, $_image, $_content;

  function __construct($link, $type)
  {
    $this->_link = urldecode($link);

    // get which news site
    foreach (self::$sources as $key => $source) {

      if ($source['Column Type'] == $type) {
        $this->_titleSource = $source['title'];
        $this->_contentSource = $source['content'];
        $this->_dateTimeSource = $source['dateTimeLocation'];
        $this->_dateTimeRegex = $source['dateTime_regex'];
        $this->dateTimeFormat = $source['dateTime_format'];
      }
    }

    $this->_MainCrawler = new Crawler;
    try {
      $this->_MainCrawler->addHTMLContent(file_get_contents($link), 'UTF-8');
    } catch (Exception $e) {
      echo "== Having Some Difficulty with $link == \n";
      continue;
    }

  }

  public function getTitle()
  {
    $this->_title = $this->_MainCrawler->filter($this->_titleSource)->text();
    return trim(html_entity_decode($this->_title));
  }

  public function getTimeStamp()
  {
    if (is_array($this->_dateTimeSource))
    {
      foreach ($this->_dateTimeSource as $key => $source) {
        $dateTimeGross = $this->_MainCrawler->filter($source)->text();
        preg_match('#(' . $this->_dateTimeRegex . ')#u', $dateTimeGross, $dateTime);
        if (!empty($dateTime)) {
          break;
        }
      }
    } else {
      $dateTimeGross = $this->_MainCrawler->filter($this->_dateTimeSource)->text();
      preg_match('#(' . $this->_dateTimeRegex . ')#u', $dateTimeGross, $dateTime);
    }

    $dateTime = $dateTime[0];
    $dateTimeObject = DateTime::createFromFormat($this->dateTimeFormat, $dateTime);
    // Manually remove 10800 (3 hours) to convert to UTC (inelegant but faster than timezone Tinkering in DateTime)
    $this->_timeStamp = $dateTimeObject->getTimestamp() - 10800 ;
    return $this->_timeStamp ;
  }

  public function getImage(){
    $this->_image = crawlHelpers::getImageFromUrl($this->_link);
    return $this->_image;
  }

  public function getContent()
  {
    if ($this->_MainCrawler->filter($this->_contentSource)->count() > 0) {
      $this->_content = $this->_MainCrawler->filter($this->_contentSource)->html();

      //Pipe raw content through sanitizing filters to produce cleaner output
      $this->content = self::removeScriptTags($this->_content);
      $this->content = self::removeEmptyTags($this->content);
      $this->content = self::removeDivTags($this->content);
      $this->content = trim($this->content);
      return $this->content;
    } else {
      return 'Content Not Available';
    }

    // Old stuff: remove if everything is working
    // try to sanitize it by removing javascript ..etc
    // $grossContentCrawler = new Crawler($this->_content);
    // $paragraphs = $grossContentCrawler->filter('p');
    // $finalContent = '';
    // if ($paragraphs->count() > 0) {
    //   foreach ($paragraphs as $key => $paragraph) {
    //     $paragraph = new Crawler($paragraph);
    //     $finalContent .= '<p>'. $paragraph->html()."</p>\n";
    //   }
    //   $this->_content = $finalContent;
    //   return trim(html_entity_decode(strip_tags($this->_content)));
    // }else{
    //   return trim(html_entity_decode(strip_tags($this->_content)));
    // }
  }
  public function getExcerpt()
  {
    if (empty($this->_content)) {
      $this->getContent();
    }
    return substr(strip_tags($this->_content), 0,128).' ...';
  }
  public static function removeScriptTags($ourString){
    $result = preg_replace("#<script>.+</script>#uiUs", " ", $ourString);
    return $result;
  }
  public static function removeEmptyTags($ourString){
    $result = preg_replace("#<[a-zA-Z]+>\\s*</[a-zA-Z]+>#uiUs", " ", $ourString);
    return $result;
  }
  public static function removeDivTags($ourString){
    $result = preg_replace("#<div .+>|<\\/div>#uiUs", " ", $ourString);
    return $result;
  }
}
