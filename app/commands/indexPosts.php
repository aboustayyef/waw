<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class indexPosts extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lebaneseBlogs:indexPosts';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'add posts to elastic search index';

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
    if ($this->argument('hours')) {
      $hours = (int) $this->argument('hours');
    } else {
      $hours = 24 ; //default
    }
    $targetTimestamp = time() - ( $hours * 3600 );

    $posts = Post::where('post_timestamp', '>', $targetTimestamp)->get();

    // prepare Elastic Search Client
    $client = new Elasticsearch\Client();
    $this->info('preparing index');

    foreach ($posts as $key => $post) {
      $params = array();
      $params['index']='lebaneseblogs';
      $params['type']='post';
      $params['id']=$post->post_id;
      $params['body']= array(
        'title' =>  $post->post_title,
        'content'  =>  $post->post_content
      );
      try {
        $ret = $client->index($params);
      } catch (Exception $e) {
        $this->error('Failed to index post: ' . $post->post_title);
      }
      $this->comment('Indexed post ' . $post->post_title);
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
      array('hours', InputArgument::OPTIONAL, 'How many hours back to index'),
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
