<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class getRatings extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lebaneseBlogs:getRatings';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Checks blogger for ratings of posts';

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
    $howmany_argument = $this->argument('howmany');
    $howmany = empty($howmany_argument) ? 100 : $howmany_argument;
		if (!Blog::exists($this->argument('blogger'))) {
      throw new Exception("Blogger Doesn't exist", 1);
    }
    $posts = Post::where('blog_id', $this->argument('blogger'))->orderBy('post_id','desc')->take($howmany)->get();
    foreach ($posts as $key => $post) {
      $this->info('now crawling ' . $post->post_title );
      $rating = new LebaneseBlogs\Crawling\RatingExtractor($post->blog_id, $post->post_content, $post->post_url);
      $getRatings = $rating->getRating();

      if ($getRatings) {
        $post->rating_numerator = $rating->numerator;
        $post->rating_denominator = $rating->denominator;
        $post->save();
        $this->comment('added rating ' . $rating->numerator . '/' . $rating->denominator . ' to the post ' . $post->post_title);
      }
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
			array('blogger', InputArgument::REQUIRED, 'which blogger to look for'),
		  array('howmany', InputArgument::OPTIONAL, 'How many posts to extract'),
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
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
