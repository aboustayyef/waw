<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

// Usage php artisan lebaneseBlogs:spreadPosts [blogger:id] [timeframe:minutes] [howmanyposts:number]

class spreadPosts extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lebaneseBlogs:spreadPosts';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Spreads posts by same bloggers apart to avoid dumping';

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

    // check if the blog exists;
    if (!Blog::exists($this->argument('blog'))) {
      $this->error('Blog ' . $this->argument('blog') . ' Doesnt exist');
      return;
    }

    // get last n posts
    $posts = Post::Where('blog_id', $this->argument('blog'))->orderBy('post_timestamp','desc')->take($this->argument('howmanyposts'))->get();

    $timestampsArray = [];
    $timeFrameInSeconds = $this->argument('timeframe') * 60;

    foreach ($posts as $key => $post) {
      $timestampsArray[] = $post->post_timestamp;
    }
    $this->comment('BEFORE:');
    print_r($timestampsArray);
    $location = 1;
    while ( $location < count($timestampsArray)) {
      $delta = $timestampsArray[$location-1] - $timestampsArray[$location];
        if ($delta < $timeFrameInSeconds) {
          $timestampsArray[$location] = $timestampsArray[$location-1] - $timeFrameInSeconds - 1;
          $location = 1;
        } else {
          $location++;
        }
    }
    $this->comment('AFTER:');
    print_r($timestampsArray);

    foreach ($posts as $key => $post) {
      $postToEdit = Post::find($post->post_id);
      $this->info('BEFORE: '.$postToEdit->post_timestamp);
      $postToEdit->post_timestamp = $timestampsArray[$key];
      $postToEdit->save();
      $this->comment('AFTER: '.$postToEdit->post_timestamp);
    }

  }

  protected function minutesAgo($timestamp){
    $carbon = \Carbon\Carbon::createFromTimestamp($timestamp);
    $this->comment($carbon->diffInMinutes());
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
    return array(
      array('blog', InputArgument::REQUIRED, 'The number of hours to check for'),
      array('timeframe', InputArgument::REQUIRED, 'The number of hours to check for'),
      array('howmanyposts', InputArgument::REQUIRED, 'The number of hours to check for'),
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
