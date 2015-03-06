<?php namespace waw\Crawling;

use Symfony\Component\DomCrawler\Crawler ;

class RatingExtractor{

  public $numerator;
  public $denominator;

  private $content;
  private $bloggerId;

  public function __construct($bloggerId, $html, $url){
    if ($bloggerId == 'nogarlicnoonions') {
      // NGNO rating info is not in RSS, get from DOM
      $this->content = @file_get_contents($url);
    } else {
      $this->content = strip_tags($html);
    }
    $this->bloggerId = $bloggerId;
  }

  public function getRating(){
    switch ($this->bloggerId) {
      case 'nogarlicnoonions':
        return $this->getNgnoRating();
        break;

      case 'nadsreviews':
        return $this->getNadsRating();
        break;

      default:
        return $this->getRatioRating();
        break;
    }
  }

  public function getRatioRating(){
      preg_match('#(r|R)ating\s*:\s*(\d+(\.5)?)/(\d+)#', $this->content, $result);
      if (is_array($result) && (count($result) >= 4) ) {
        $this->numerator = $result[2];
        $this->denominator = $result[4];
        return true;
      } else {
        return false;
      }
  }

  public function getNgnoRating(){
    $crawler = new Crawler($this->content);
    $ratingCrawler = $crawler->filter('.rating-result');
    if ($ratingCrawler->count() == 0) {
      return false;
    } else {
      $onOnions = $ratingCrawler->filter('img[src="/img/onion_on.png"]')->count();
      $this->numerator = $onOnions;
      $this->denominator = 10;
      return true;
    }
  }

  public function getNadsRating(){
    preg_match('#\s+(R|r)ating.*((A|B|C)(\+|-)?)#', $this->content, $result);
    if (is_array($result) && (count($result) >= 3) ) {
        $score = $result[2]; //A+ 5, A 4.5, A- 4, B+ 3.5, B 3, B- 2.5, C+2, C 1.5, C- 1;
        $equivalence = [
          'A+' => 5,
          'A' => 4.5,
          'A-' => 4,
          'B+' => 3.5,
          'B'  => 3,
          'B-' => 2.5,
          'C+' => 2,
          'C'  =>1.5,
          'C-' =>1 ];
          $this->numerator = $equivalence[$score];
          $this->denominator = 5;
        return true;
    } else{
      return false;
    }
  }

}


?>
