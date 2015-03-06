<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use LebaneseBlogs\Crawling\Articles\Article;

class CrawlArticles extends Command {

  private $columnist;  // (object) columnist being crawled
  private $homepage;  // link of homepage of crawled columnist
  private $hr;     // interface <hr> for terminal
  private $dhr;    // interface strong <hr> for terminal

  /**
   * The console command name.
   */
  protected $name = 'lebaneseBlogs:crawlArticles';

  /**
   * The console command description.
   */
  protected $description = 'This command crawls the articles of the columnists listed in Lebanese Blogs';

  /**
   * Create a new command instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->hr = str_repeat('-', 70);
    $this->dhr = str_repeat('=', 70);
  }

  /**
   * Execute the console command.
   */
  public function fire()
  {

    // Produce header
    $this->info($this->dhr);
    $this->info('Work began: '.date('d M Y , H:i:s'));
    $robot = shell_exec('whoami');
    $whichphp = shell_exec('which php');
    $this->info("Robot: $robot");
    $this->info("PHP in use: $whichphp");
    $this->info($this->dhr);

    // if the 'blog' argument is set, we only crawl the feed of that blog, otherwise, we crawl all;
    if ($this->argument('columnist')) {
      $columnists = Columnist::where('col_shorthand','=',$this->argument('columnist'))->get();
    } else {
      // get all columnists
      $columnists = Columnist::All();
    }


    // loop through them to look for new posts
    foreach ($columnists as $key => $columnist) {
      $this->columnist = $columnist;
      $this->homepage = $columnist->col_home_page;
      $this->columnType = $columnist->col_media_source;
      echo 'Article #'.$key;
      $this->exploreColumnist();
    }

    $this->info('Crawl Ended: '.date('d M Y , H:i:s'));
    $this->info('Kind of fetching used: '. $this->option('fetching'));

  } // fire

  private function exploreColumnist(){
    $columnDescription = $this->columnist;
    $columnDescription = $columnDescription['col_name'];
    $columnHomePage = $this->homepage;
    $columntype = $this->columnType;
    $this->info("Now fetching posts from columist: $columnDescription from the home page: $columnHomePage");

    // Get list of articles
    $articles = $this->columnist->latestArticles()->list;
    $mediaSource = $this->columnist->mediaSource();
    $this->info(print_r($articles));
    // loop through feed items
    foreach($articles as $key => $article)
    {
      // get article link
      $article_link = $article;

      // get col_shorthand , example: myoungds
       $domain = $this->columnist->col_shorthand;

      // initialize article crawling object
      $article_object = new Article($article_link, $mediaSource);

      // get timestamp
      $article_timestamp =  $article_object->getTimeStamp(); // get post's timestamp;

      // get title & sanitize it
      $article_title = lbNormalise::cleanUpText($article_object->getTitle(), 120);
      $article_title = lbNormalise::unicode_decode($article_title);

      $this->info($article_title);

      /*--------------------------------------------------
      | check if this post is already in the database.    |
      ---------------------------------------------------*/

      // First, remove host (http or https) to avoid counting secure links as duplicates
      $article_link_without_host = preg_replace("(https?://)", "", $article_link );

      // Is the post's url in the database?
      $urlExists = Post::where('post_url','like', '%'.$article_link_without_host)->count();

      // Did the blogger post another post with the exact same title?
      $nameExists = Post::where('post_title', $article_title)->where('blog_id', $domain)->count();

      // Did the blogger post another post with the exact same timestamp?
      $timeStampExists = Post::where('post_timestamp', $article_timestamp)->where('blog_id', $domain)->count();

      //If any of the conditions above is true then the post exists
      $postExists = $urlExists + $nameExists + $timeStampExists;

      if ($postExists > 0) {

      // If the post exists

        $this->comment('[ x Article already in Database ]');

        /*
        |   After a an existing post is found, Depending on the 'fetching' setting,
        |   the crawler will either move to the next feed ('thorough') or keep going through
        |   the feed until it reaches the end ('updater')
        */

        if ($this->option('fetching') == 'thorough'){
          continue;
        }else{
          break;
        }

      } else {

      // If the post doesn't exist

        $this->info('New Article: '.$article_title);

        // Normalise & sanitize Content
        $article_content = html_entity_decode($article_object->getContent(), ENT_COMPAT, 'utf-8');
        $article_content = substr($article_content, 0, 7500); // larger than 8000 chars won't fit in mysql
        // remove non utf-8 characters;

        // Crawl for suitable image
        $article_image = $article_object->getImage();

        // Get Excerpt
        $article_excerpt = $article_object->getExcerpt();

        // Get Image Dimensions (if exists)
        if ($article_image) {
          list($width, $height, $type, $attr) = getimagesize($article_image);
          $article_image_width = $width;
          $article_image_height = $height;
        } else {
          $article_image = NULL;
          $article_image_width = 0;
          $article_image_height = 0;
        }

        // Save new record
        $post = new Post;

        // which time to use?
        if ($this->option('timeSetting') == 'time_of_fetching') {
          $article_timestamp = time() - rand(0,600) ;
          $post->post_timestamp = $article_timestamp ;
        } else { // time_in_post. we use that setting if we're doing a long time thing
          $post->post_timestamp = $article_timestamp ;
        }

        $post->post_url = $article_link ;
        $post->post_title = $article_title ;
        $post->post_image = $article_image ;
        $post->post_excerpt = $article_excerpt ;
        $post->blog_id = $this->columnist->col_shorthand ;

        $post->post_content = $article_content ;
        $post->post_image_width = $article_image_width ;
        $post->post_image_height = $article_image_height ;
        $post->post_visits = 0 ;
        $post->post_image_hue = 0;
        $post->post_tags = $this->columnist->col_tags;
        try {
          $post->save();

          // index post (disabled elasticsearch)
          // $params = array();
          // $params['index']='lebaneseblogs';
          // $params['type']='post';
          // $params['id']=$post->post_id;
          // $params['body']= array(
          //   'title' =>  $post->post_title,
          //   'content'  =>  $post->post_content
          // );
          // try {
          //   $ret = $this->searchClient->index($params);
          // } catch (Exception $e) {
          //   $this->error('Failed to index post: ' . $post->post_title);
          // }
          // $this->comment('Indexed post ' . $post->post_title);

          // add timestamp to blogger's record (as timestamp of last post)
          $blog = Blog::where('blog_id', $this->columnist->col_shorthand)->first();
          $blog->blog_last_post_timestamp = $article_timestamp;
          $blog->save();

          $this->comment('New Post Saved: "' . $article_title . '"');
        } catch (Exception $e) {
          $this->error('Problem saving post [' . $post->post_title .']. | '. $e->getMessage());
        }

        // Cache image if exists. Flatten and convert to jpg;
        if ($article_image) { // image exists

          // cache it
          $candidateCachingFile = $_ENV['DIRECTORYTOPUBLICFOLDER'] . '/img/cache/' . $article_timestamp.'_'.$this->columnist->col_shorthand.'.jpg' ;
          if (!file_exists($candidateCachingFile)) {
            if ($image = new Imagick($article_image))
            {
              $image = $image->flattenImages();
              $image->setFormat('JPEG');
              $image->thumbnailImage(300,0);
              $outFile = $_ENV['DIRECTORYTOPUBLICFOLDER'] . '/img/cache/' . $article_timestamp.'_'.$this->columnist->col_shorthand.'.jpg';//.Lb_functions::get_image_format($blog_post_image);
              echo $outFile;
              $image->writeImage($outFile);
              $this->comment('Image added to cache folder');

              // add hue color
              $imageAnalyzer = new imageAnalyzer($outFile);
              $hue = $imageAnalyzer->getDominantHue();
              $post->post_image_hue = $hue;
              try {
                $post->save();
                $this->comment('Hue added');
              } catch (Exception $e) {
                $this->error('could not save hue');
              }
            };
          }else{
            $this->info('Cache image ' . $candidateCachingFile . ' already exists');
          }

        }

      }

    } // end foreach feed item

  } // exploreFeed

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
    return array(
      array('columnist', InputArgument::OPTIONAL, 'This will be set if only one blog is to be crawled')
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
      // the kind of fetching can be "updater" or "thorough"
      // 'updater' breaks the loop and goes to next blog as soon as fetcher finds an existing post
      // 'thorough' goes through the entire feed to find older unlisted posts.
      array('fetching', null, InputOption::VALUE_OPTIONAL, 'method of fetching', "updater"),
      array('timeSetting', null, InputOption::VALUE_OPTIONAL, 'Which time to use when fetching', "time_of_fetching"),
    );
  }

}
