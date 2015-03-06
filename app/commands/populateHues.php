<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class populateHues extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lebaneseBlogs:populateHues';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fills up posts with the hue color of image';

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
		$posts_to_populate = Post::where('post_image_height', '>', 0)->orderBy('post_timestamp','desc')->take($this->argument('howmany'))->get();
	   foreach ($posts_to_populate as $key => $post) {
      $this->info('Analyzing image for post: '. $post->post_title);
      $imageData = new imageAnalyzer($post->post_image);
      $hue = (int) $imageData->getDominantHue();
      $post->post_image_hue = $hue;
      try {
        $post->save();
        $this->comment('Hue saved: '.$hue);
      } catch (Exception $e) {
        $this->error('sorry, something went wrong');
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
			array('howmany', InputArgument::REQUIRED, 'The amount of posts to populate'),
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
