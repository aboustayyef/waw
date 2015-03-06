<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class endOfYearStats extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lebaneseBlogs:endOfYearStats';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'get statistics for end of year';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	  $this->firstDay = 1388534400;
  }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
    $this->getFacebookTop(20);
	}

  public function getTopPostsPerBlogger(){
    $topPosts = topPost::Where('top_post_timestamp_added','>',$this->firstDay)->get();
    $bloggersResults = [];
    foreach ($topPosts as $key => $topPost) {
      try {
        $blog_id = Post::where('post_url',$topPost->top_post_url)->get()->first()->blog_id;
        if (isset($bloggersResults[$blog_id])) {
          $bloggersResults[$blog_id] += 1;
        } else {
          $bloggersResults[$blog_id] = 1;
        }
      }
       catch (Exception $e) {
      }
    }
    echo "Blog, Number of times #1, Number of Posts \n";
    foreach ($bloggersResults as $key => $result) {
      $numberOfPosts = Post::where('blog_id',$key)->where('post_timestamp','>',$this->firstDay)->get()->count();
      echo "$key, $result, $numberOfPosts\n";
    }
//    print_r($bloggersResults);
  }

  public function getListOfBlogStats(){
    // Temporarily increase memory limit to 256MB
    ini_set('memory_limit','256M');
    $blogs = [];
    $posts = Post::where('post_timestamp','>',$this->firstDay)->get();
    foreach ($posts as $key => $post) {
      if (!isset($blogs[$post->blog_id])) {
        // initialize each blogger's counters
        $blogs[$post->blog_id] = ['total_virality'=>0,'post_count'=>0];
      } else {
        $blogs[$post->blog_id]['total_virality'] += $post->post_virality;
        $blogs[$post->blog_id]['post_count'] += 1;
      }
    }
    echo "Blog, total virality, post count \n";
    foreach ($blogs as $key => $blog) {
      echo "$key, {$blog['total_virality']}, {$blog['post_count']} \n";
    }
  }


  public function temp(){
    $test = ['inkontheside' => '79.17%',
  'stateofmind13' => '55.41%',
  'ginosblog' => '35.76%',
  'beirutreport' => '32.59%',
  'ivysays' =>  '31.88%',
  'beirutcityguide' => '31.49%',
  'plus961' => '31.25%',
  'blogbaladi' => '28.94%',
  'blogoftheboss' => '26.73%',
  'karlremarks' => '25.45%',
  'ultgate' => '25.42%',
  'beirutista' => '25.00%',
  'thedscoop' => '23.08%',
  'chitiktikchiti3a' => '20.19%',
  'bikaffe' => '17.86%',
  'sietske-in-beiroet' => '17.65%',
  'joesbox' => '14.94%',
  'lfadi' => '13.37%',
  'sharbelfaraj' => '12.16%',
  'nogarlicnoonions' => '10.87%'];
  $counter = 1;
  foreach ($test as $key => $value) {
    $blog = Blog::find($key);
    $blogname = $blog->blog_name;
    $bloglink = $blog->blog_url;
    echo '<tr>';
    echo "<td>$counter</td>";
    echo '<td><a href="'.$bloglink.'">'.$blogname.'</a></td>';
    echo "<td>$value</td>";
    echo '</tr>';
    $counter++;
    }
  }

  public function getFacebookTop($howMuch){
    //get posts with most facebook shares;
    $posts = Post::where('post_timestamp','>', $this->firstDay)->where('post_tags','NOT LIKE','%politics%')->orderBy('post_facebookShares','desc')->take($howMuch)->get();
    echo "Post Title, Post URL, Facebook Shares \n";
    foreach ($posts as $key => $post) {
      $title = str_replace(',', '', $post->post_title);
      $url = $post->post_url;
      $shares =$post->post_facebookShares;
      echo "$title, $url, $shares \n";
    }
  }

  public function getTwitterTop($howMuch){
    //get posts with most facebook shares;
    $posts = Post::where('post_timestamp','>', $this->firstDay)->orderBy('post_twitterShares','desc')->take($howMuch)->get();
    echo "Post Title, Post URL, Twitter Shares \n";
    foreach ($posts as $key => $post) {
      $title = str_replace(',', '', $post->post_title);
      $url = $post->post_url;
      $shares =$post->post_twitterShares;
      echo "$title, $url, $shares \n";
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
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
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

}
