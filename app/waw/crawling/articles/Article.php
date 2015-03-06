<?php namespace LebaneseBlogs\Crawling\Articles ;

use Symfony\Component\DomCrawler\Crawler ;
use \Carbon\Carbon;
use \crawlHelpers;
// Takes in a URL and a Media Source and returns meta data like title, image, content ..etc

class Article{

  private $mediaSource, $url, $MainCrawler, $content=null;
  public function __construct($url, MediaSource $mediaSource){

    $this->mediaSource = $mediaSource;
    $this->url = urldecode($url);

    $this->MainCrawler = new Crawler();
    try {
      $this->MainCrawler->addHTMLContent(file_get_contents($this->url), 'UTF-8');
    } catch (Exception $e) {
      echo "== Having Some Difficulty with extracting $this->url == \n";
      continue;
    }
  }

  public function getTitle()
  {
    $this->_title = $this->MainCrawler->filter($this->mediaSource->title)->text();
    return trim(html_entity_decode($this->_title));
  }

  public function getTimeStamp()
  {
    if (is_array($this->mediaSource->dateTimeLocation))
    {
      foreach ($this->mediaSource->dateTimeLocation as $key => $source) {
        $dateTimeGross = $this->MainCrawler->filter($source)->text();
        preg_match('#(' . $this->mediaSource->dateTime_regex . ')#u', $dateTimeGross, $dateTime);
        if (!empty($dateTime)) {
          break;
        }
      }
    } else {
      $dateTimeGross = $this->MainCrawler->filter($this->mediaSource->dateTimeLocation)->text();
      preg_match('#(' . $this->mediaSource->dateTime_regex . ')#u', $dateTimeGross, $dateTime);
    }
    $dateTime = $dateTime[0];
    $dateTimeObject = (new Carbon)->createFromFormat($this->mediaSource->dateTime_format, $dateTime);
    // Manually remove 10800 (3 hours) to convert to UTC (inelegant but faster than timezone Tinkering in DateTime)
    $this->_timeStamp = $dateTimeObject->getTimestamp() - 10800 ;
    return $this->_timeStamp ;
  }

  public function getContent(){
    if ($this->content) { //singleton
      return $this->content;
    }
    if ($this->MainCrawler->filter($this->mediaSource->content)->count() > 0) {
      $this->_content = $this->MainCrawler->filter($this->mediaSource->content)->html();
      $cleanContent = (new ContentSanitizer($this->_content))->sanitize();
      $this->content = $cleanContent;
    } else {
      $this->content = 'Content Not Available';
    }
    return $this->content;
  }

  public function getImage(){
    $imageContainer = new Crawler($this->getContent());
    $this->_image = crawlHelpers::getImageFromContainer($imageContainer, $this->mediaSource->root);
    return $this->_image;
  }

  public function getExcerpt()
  {
    return trim(substr(strip_tags($this->getContent()), 0,128)).' ...';
  }

}

class ArticleTesting{
    public function test(){
    $nadim = \Columnist::find('nkoteich-almodon');
    $mediaSource = $nadim->mediaSource();
    $article = new Article('http://www.almodon.com/politics/26575731-1b58-425a-83ed-4d7a3f311701', $mediaSource);
    echo $article->getImage();
  }


}

?>
