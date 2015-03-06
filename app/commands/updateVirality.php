<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class updateVirality extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lebaneseBlogs:updateVirality';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update Virality and other social scores of posts';

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
  public function fire()
  {

    // get timeframe

    if ($this->argument('hours')) {
      $hours = $this->argument('hours');
    } else {
      $hours = 24; // default is 1 day
    }

    // convert timeframe to timestamp
    $hoursAgo = time() - ($hours * 60 * 60);

    // get all posts in that timeframe
    $posts = Post::Where('post_timestamp', '>', $hoursAgo)->get();

    // start the loop
    foreach ($posts as $key => $post) {

      $this->info('Analysing post ' . $post->post_title);

      // initiate social score object
      $score = new SocialScore($post->post_url);

      // get facebook and twitter scores;
      $facebookShares = $score->getFacebookScore();
      $twitterShares = $score->getTwitterScore();
      $totalShares = $facebookShares + $twitterShares;

      // make total shared more weighed by twitter because it's less easy to game and buy
      $totalShares = round((($facebookShares + (2 * $twitterShares)) / 3 ) * 2 );

      $this->comment('Facebook Score: ' . $facebookShares . ' , Twitter Score: ' . $twitterShares . ' , Total Score: ' . $totalShares);

      // calculate virality
      $virality = $totalShares > 1 ? round( 8 * log($totalShares) ) : 2 ;

      // set virality's upper limit of 50
      if ($virality > 50) {
        $virality = 50;
      }

      // The social score is the combination of virality and post visits
      // Visits are twice as important as virality
      $socialScore = $post->post_visits + round($virality / 2);

      // Listicle Penalty (20%)
      if (self::isListicle($post->post_title)) {
        $socialScore = round($socialScore*0.8);
        $this->comment('Listicle Penalty added');
      }

      $this->comment('Virality: ' . $virality . ' , Social Score: ' . $socialScore);

      // Save The results to database;

      $post->post_facebookShares = $facebookShares;
      $post->post_twitterShares = $twitterShares;
      $post->post_totalShares = $totalShares;
      $post->post_virality = $virality;
      $post->post_socialScore = $socialScore;
      try {
        $post->save();
      } catch (Exception $e) {
        $this->error('Couldnt save details for post in database');
      }
    } // (end of foreach)
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
    return array(
      array('hours', InputArgument::OPTIONAL, 'The number of hours to check for'),
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
