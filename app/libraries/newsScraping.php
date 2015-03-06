<?php
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

// Data Transfer object
// This object holds all the data relating to the news source (eg naharnet)
class newsObject
{
  public $nameid, $title, $locationDefinitions, $root, $attribution, $language;
  function __construct($locationDefinitions)
  {
    $this->url = $locationDefinitions['url'];
    $this->nameid = $locationDefinitions['id'];
    $this->title = $locationDefinitions['title'];
    $this->language = $locationDefinitions['language'];
    $this->root = $locationDefinitions['root'];
    $this->attribution = $locationDefinitions['attribution'];
    $this->locationDefinitions = $locationDefinitions['scraping'];
  }
}

// bluePrint for the scraping class
abstract class newsScraper{
    protected $articles;
    public $newsObject;
    public function __construct(newsObject $newsObject){
        $this->newsObject = $newsObject;
    }

    // get the value of $this->articles
    abstract function getLatestArticles();

    function storeArticles(){
      Cache::forever($this->newsObject->nameid, $this->articles);
    }
}

// html scraping class, uses the newsObject;
class htmlNewsScraper extends newsScraper
{
  public function logger($message){
    echo "$message \n";
  }

  public function getLatestArticles(){
    $this->articles = array();
    $definition = $this->newsObject->locationDefinitions;

    // Entire Page Crawler
    $crawler = new Crawler;
    $crawler->addHTMLContent(file_get_contents($this->newsObject->url), 'UTF-8');
    $this->logger('created General crawler');

    // Gets a list of containers that contain our news items
    $containerCrawler = $crawler->filter($definition['container']);
    $this->logger('Created List of containers, found '.$containerCrawler->count().'. will loop through them');
    $count = 1;
    $maximum = 10;
    foreach ($containerCrawler as $containerKey => $containerNode) {
        try {
          $this->logger("=== container $count:");
          // Get the link and title
          $linkCrawler = new Crawler($containerNode);
          $this->logger('created Headline/Link Crawler');
          $a = $linkCrawler->filter('a')->eq($definition['orderOfAnchor']);
          $text = $a->text();
          $link = $a->attr('href');
          $link = $this->newsObject->root.$link;
          $virality = (new SocialScore($link))->getVirality();

          // Get the image if it exists
          if (!empty($definition['ImageContainer'])) {
            $imgCrawler = new Crawler($containerNode);
            $this->logger('added image crawler');
            if ($definition['ImageContainer']=='[IMG]') {
              $img = $imgCrawler->filter('img')->first();
            }else {
              $img = $imgCrawler->filter($definition['ImageContainer'].' img')->first();
            }

            $img = $definition['ImageRoot'].$img->attr('src');

            if (!empty($img)) {
              // cache image
              $filename = md5($img).'.jpg';
              $directory = $_ENV['DIRECTORYTOPUBLICFOLDER'] . '/img/cache/'.$this->newsObject->nameid ;
              if (!file_exists($directory)) {
                mkdir($directory);
              }
              $image = new imagick($img);
              $image->setFormat('JPEG');
              $image->cropThumbnailImage(70,70);
              $outFile = $directory. '/'. $filename;
              $image->writeImage($outFile);
            }
          }

          // Get the DateStamp
          if (!empty($definition['timeContainer'])) {
            $timeCrawler = new Crawler($containerNode);
            $this->logger('added time crawler');
            $time = $timeCrawler->filter($definition['timeContainer'])->first();
            $time = $time->text();
            $carbon= new Carbon($time, $definition['timeZone']);

            $gmtDateTime = $carbon->setTimezone('GMT')->toDateTimeString();
          }

          if (empty($img)) {
            $img='';
          }

          $this->articles['content'][] = array(
            'headline'=>$text,
            'url'=> $link,
            'virality'=>$virality,
            'img'=>$img,
            'gmtDateTime'=>$gmtDateTime
            );

          $this->articles['meta'] = array(
            'feedTitle' => $this->newsObject->title,
            'attribution'=> $this->newsObject->attribution,
            'language'=>$this->newsObject->language
          );
          $count++;
          if ($count > $maximum) {
            break;
          }
        } catch (Exception $e) {
          $this->logger('Sorry, problem with item '.$count);
        }


      }
    var_dump($this->articles);
  }
}

?>
