<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DomCrawler\Crawler;

class getNews extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lebaneseBlogs:getNews';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'get News';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */

  public function fire(){
    // for testing a single source

    // $newsSource = (new NewsScrapingDictionary)->source('lbci');
    // $newsObject = new newsObject($newsSource);
    // $scraper = new htmlNewsScraper($newsObject);
    // $scraper->getLatestArticles();
    // $scraper->storeArticles();

    // for the entire library

    $newsSources = (new NewsScrapingDictionary)->sources();
    foreach ($newsSources as $key => $newsSource) {
      $newsObject = new newsObject($newsSource);
      $scraper = new htmlNewsScraper($newsObject);
      $scraper->getLatestArticles();
      $scraper->storeArticles();
    }
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
    return array(
      //array('hours', InputArgument::OPTIONAL, 'The number of hours to check for'),
    );
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions()
  {
    return array(
      //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
    );
  }

  public static function isListicle($title){
    $title = strtolower($title);
    $parts = explode(" ", $title);
    $firstWord = $parts[0];
    if (count($parts) > 1) {
      $secondWord = $parts[1];
    } else {
      $secondWord = 'NotANumber';
    }

    $listOfNumbers = array('3','4','5','6','7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', 'three','four','five','six','seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
    if ((in_array($firstWord, $listOfNumbers))||(in_array($secondWord, $listOfNumbers))) {
      return TRUE;
    }else{
      return FALSE;
    }
  }

}
